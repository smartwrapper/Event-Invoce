<?php

namespace Classiebit\Eventmie\Http\Controllers\Voyager;
use Facades\Classiebit\Eventmie\Eventmie;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use Illuminate\Http\RedirectResponse;

use TCG\Voyager\Http\Controllers\VoyagerUserController as BaseVoyagerUserController;
use Auth;
use Classiebit\Eventmie\Models\User;

use Illuminate\Support\Carbon;
use Classiebit\Eventmie\Notifications\MailNotification;

class VoyagerUserController extends BaseVoyagerUserController
{

    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchable = SchemaManager::describeTable(app($dataType->model_name)->getTable())->pluck('name')->toArray();
            $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->get();
            foreach ($searchable as $key => $value) {
                $field = $dataRow->where('field', $value)->first();
                $displayName = ucwords(str_replace('_', ' ', $value));
                if ($field !== null) {
                    $displayName = $field->getTranslatedAttribute('display_name');
                }
                $searchNames[$value] = $displayName;
            }
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $model->{$dataType->scope}();
            } else {
                $query = $model::select('*');
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
                $query->where('id', '<>', 1)->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func([
                    $query->where('id', '<>', 1)->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        $view = 'eventmie::vendor.voyager.users.browse';

        return Eventmie::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn'
        ));
    }


     // POST BR(E)AD
    public function update(Request $request, $id)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return redirect()
                    ->route("voyager.users.index")
                    ->with([
                        'message'    => 'Demo mode',
                        'alert-type' => 'info',
                    ])
                    ->send();
        }

        /* VoyagerUserController update method */
        if (Auth::user()->getKey() == $id) {
            $request->merge([
                'role_id'                              => Auth::user()->role_id,
                'user_belongstomany_role_relationship' => Auth::user()->roles->pluck('id')->toArray(),
            ]);
        }
        /* VoyagerUserController update method */

        /*  */

        /* VoyagerBaseController update method */
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = $model->findOrFail($id);
        }

        /* CUSTOM */
        // Current user role id
        $currentRoleId = $data->role_id;
        /* CUSTOM */

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        /* CUSTOM */
        // If approved customer to organizer
        if($request->role_id > $currentRoleId) {
            $this->approvedOrganiserNotification($data);
        }
        
        /* CUSTOM */

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }



    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'edit', $isModelTranslatable);

        $view = 'eventmie::vendor.voyager.users.edit-add';

        return Eventmie::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'add', $isModelTranslatable);

        $view = 'eventmie::vendor.voyager.users.edit-add';

        return Eventmie::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }
    
    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();

        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        /* CUSTOM */
        // default email verify true if Admin creates user
        User::where('id', $data->id)->update(['email_verified_at'  => Carbon::now()]);
        /* CUSTOM */

        event(new BreadDataAdded($dataType, $data));

        /* CUSTOM */
        // Send signup email
        $this->registrationNotification($data);

        // Send approved organizer email
        if($data->role_id == 3) {
            $this->approvedOrganiserNotification($data);
        }
        /* CUSTOM */

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    // public function relation(Request $request)
    // {
        
    //     $slug = $this->getSlug($request);
    //     $page = $request->input('page');
    //     $on_page = 50;
    //     $search = $request->input('search', false);
    //     $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

    //     $method = $request->input('method', 'add');

    //     $model = app($dataType->model_name);

    //     if ($method != 'add') {
    //         $model = $model->find($request->input('id'));
    //     }

    //     $this->authorize($method, $model);

    //     $rows = $dataType->{$method.'Rows'};
    //     foreach ($rows as $key => $row) {
    //         if ($row->field === $request->input('type')) {
    //             $options = $row->details;
    //             $model = app($options->model);
    //             $skip = $on_page * ($page - 1);

    //             $additional_attributes = $model->additional_attributes ?? [];

    //             // Apply local scope if it is defined in the relationship-options
    //             if (isset($options->scope) && $options->scope != '' && method_exists($model, 'scope'.ucfirst($options->scope))) {
    //                 $model = $model->{$options->scope}();
    //             }

    //             // If search query, use LIKE to filter results depending on field label
    //             if ($search) {
    //                 // If we are using additional_attribute as label
    //                 if (in_array($options->label, $additional_attributes)) {
                        
    //                     $relationshipOptions = $model->where('id', '<>', 1)->get();

    //                     $relationshipOptions = $relationshipOptions->filter(function ($model) use ($search, $options) {
    //                         return stripos($model->{$options->label}, $search) !== false;
    //                     });

                        
    //                     $total_count = $relationshipOptions->count();
    //                     $relationshipOptions = $relationshipOptions->forPage($page, $on_page);
    //                 } else {
    //                     $total_count = $model->where('id', '<>', 1)->where($options->label, 'LIKE', '%'.$search.'%')->count();
    //                     $relationshipOptions = $model->take($on_page)->skip($skip)->where('id', '<>', 1)
    //                         ->where($options->label, 'LIKE', '%'.$search.'%')
    //                         ->get();
    //                 }
    //             } else {
    //                 $total_count = $model->count();
    //                 $relationshipOptions = $model->where('id', '<>', 1)->take($on_page)->skip($skip)->get();
                    
    //             }

    //             $results = [];

    //             if (!$row->required && !$search && $page == 1) {
    //                 $results[] = [
    //                     'id'   => '',
    //                     'text' => __('voyager::generic.none'),
    //                 ];
    //             }

    //             // Sort results
    //             if (!empty($options->sort->field)) {
    //                 if (!empty($options->sort->direction) && strtolower($options->sort->direction) == 'desc') {
    //                     $relationshipOptions = $relationshipOptions->sortByDesc($options->sort->field);
    //                 } else {
    //                     $relationshipOptions = $relationshipOptions->sortBy($options->sort->field);
    //                 }
    //             }

    //             foreach ($relationshipOptions as $relationshipOption) {
    //                 $results[] = [
    //                     'id'   => $relationshipOption->{$options->key},
    //                     'text' => $relationshipOption->{$options->label},
    //                 ];
    //             }

    //             return response()->json([
    //                 'results'    => $results,
    //                 'pagination' => [
    //                     'more' => ($total_count > ($skip + $on_page)),
    //                 ],
    //             ]);
    //         }
    //     }

    //     // No result found, return empty array
    //     return response()->json([], 404);
    // }


    protected function registrationNotification($user)
    {
        // send signup notification
        // ====================== Notification ====================== 
        $mail['mail_subject']   = __('eventmie-pro::em.register_success');
        $mail['mail_message']   = __('eventmie-pro::em.get_tickets');
        $mail['action_title']   = __('eventmie-pro::em.login');
        $mail['action_url']     = eventmie_url();
        $mail['n_type']         = "user";

        // notification for
        $notification_ids       = [
            1, // admin
            $user->id, // new registered user
        ];
        
        $users = User::whereIn('id', $notification_ids)->get();
        if(checkMailCreds()) 
        {
            try {
                \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail));
            } catch (\Throwable $th) {}
        }
        // ====================== Notification ======================     
    }
    
    protected function approvedOrganiserNotification($user)
    {
        // ====================== Notification ====================== 
        
        // Became Organizer successfully email
        $msg[]                  = __('eventmie-pro::em.name').' - '.$user->name;
        $msg[]                  = __('eventmie-pro::em.email').' - '.$user->email;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventmie-pro::em.became_organiser_successful');
        $mail['mail_message']   = __('eventmie-pro::em.became_organiser_successful_msg');
        $mail['action_title']   = __('eventmie-pro::em.view').' '.__('eventmie-pro::em.profile');
        $mail['action_url']     = route('eventmie.profile');
        $mail['n_type']         = "Approved-Organizer";
        
        // notification for
        $notification_ids       = [
            1, // admin
            $user->id, // the organizer
        ];
        
        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ====================== 

    }
    
    

    
}
