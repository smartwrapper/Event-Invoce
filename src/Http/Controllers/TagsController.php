<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use File;

use Auth;

use Classiebit\Eventmie\Models\Tag;
use Classiebit\Eventmie\Models\Event;
use  Classiebit\Eventmie\Models\User;


class TagsController extends Controller
{
    
    public function __construct()
    {
        // language change
        $this->middleware('common');
    
        $this->middleware(['organiser'])->except(['tags', 'selected_event_tags']);
        $this->tag          = new Tag;
        $this->event        = new Event;
        $this->user         = new User;
        $this->organiser_id = null;   
    }

    /**
     * Create-edit tags
     *
     * @return array
     */
    public function form($id = null, $view = 'eventmie::tags.form', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        $id     = (int) $id;
        $event  = [];
        
        $organiser_id    = Auth::id();

        return Eventmie::view($view, compact('organiser_id', 'path', 'extra'));
    }

    // get tags for particular organiser
    public function tags(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);

        $params    = [
            'organizer_id' => $this->organiser_id,
        ];
        
        $tags  = $this->user->get_tags($params);

        if(empty($tags))
        {
            return response()->json(['status' => false]);    
        }

        return response()->json(['status' => true, 'tags' => $tags->jsonSerialize() ]);
    }

    // add tags
    public function store(Request $request)
    {
        // if logged in user is admin
        $this->is_admin($request);
        
        $image = null;
        // 1. validate data
        $request->validate([
            "title"          => 'required|max:512',
            "type"           => 'required|max:512',
        ]);
        
        $image  = null;

        // if website not empty then apply validation
        if(!empty($request->website))
        {
            $request->validate([  
                "website"        => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            ]);
        }

        // if phone not empty then apply validation
        if(!empty($request->phone))
        {
            $request->validate([  
                "phone"          => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            ]);
        }
        
        // in case of edit tag_id
        if(!empty($request->tag_id))
        {
            // get tag base on tag_id
            $tag     = $this->tag->get_tag($request->tag_id);
            
            if(!empty($tag))
                $image       = $tag->image;   
        }

        $path = '/tags/'.Carbon::now()->format('FY').'/';

        // for image
        if($request->hasfile('image')) 
        { 
            // image type validation 
            $request->validate([
                'image'        => 'mimes:jpeg,png,jpg',
            ]); 

            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting poster extension
            $image     = time().rand(1,988).'.'.$extension;
            // $file->storeAs('public/'.$path, $image);
            $image_resize    = Image::make($file)->resize(512,512);

            // if directory not exist then create directiory
            if (! File::exists(storage_path('/app/public/').$path)) {
                File::makeDirectory(storage_path('/app/public/').$path, 0777, true);
            }
            
            $image_resize->save(storage_path('/app/public/'.$path.$image));
            $image     = $path.$image;
        }
       
        // image is required
        if(empty($image))
        {
            // if have  image and database have images no images this event then apply this rule 
            $request->validate([
                'image'        => 'required|mimes:jpeg,png,jpg',
            ]); 
        }

        $params = [
            "title"          => $request->title,
            "type"           => strtolower($request->type),
            "sub_title"      => $request->sub_title,
            "description"    => $request->description,
            "phone"          => $request->phone,
            "email"          => $request->email,
            "instagram"      => $request->instagram,
            "facebook"       => $request->facebook,
            "twitter"        => $request->twitter,
            "linkedin"       => $request->linkedin,
            "website"        => $request->website,
            "image"          => $image,
            "is_page"        => (int) $request->is_page,
            "organizer_id"   => $this->organiser_id,
        ];

        $tag_id       = $request->tag_id; 
    
        $tags         = $this->tag->add_tags($params, $tag_id);

        if(empty($tags))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true]);
    }

    // delete tags
    public function delete(Request $request)
    {
         // if logged in user is admin
        $this->is_admin($request);
         
           // 1. validate data
        $request->validate([
            'tag_id'   => 'required',
        ]);
        $total_tags      = $this->tag->total_tags();

        // Organiser can't delete last tag
        if($total_tags <= 1)
        {
            return error(__('eventmie-pro::em.tag').' '.__('eventmie-pro::em.required'), Response::HTTP_BAD_REQUEST );
        }

        $delete  = $this->tag->delete_tags($request->tag_id);

        if(empty($delete))
        {
            return response()->json(['status' => false]);    
        }
        return response()->json(['status' => true]);
    }

    //get selected tags from event_tags table when organiser editing his event
    public function selected_event_tags(Request $request)
    {
         // if logged in user is admin
        $this->is_admin($request);
         
           // 1. validate data
        $request->validate([
            'event_id'   => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
        ]);

        // check event is valid or not
        $check_event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

        // if event not found then access denie!
        if(empty($check_event))
        {
            return error('access denied!', Response::HTTP_BAD_REQUEST );
        }

        // get seleced tags
        $selected_event_tags   = $this->event->selected_event_tags($request->event_id);
        
        if($selected_event_tags->isEmpty())
        {
            return response()->json(['status' => false, 'selected_event_tags'=> $selected_event_tags], 200);
        }

        return response()->json(['status' => true, 'selected_event_tags'=> $selected_event_tags], 200);
    }

    protected function is_admin(Request $request)
    {
        // if login user is Organiser then 
        // organiser id = Auth::id();
        $this->organiser_id = Auth::id();

        // if admin is creating event
        // then user Auth::id() as $organiser_id
        // and organiser id will be the id selected from Vue dropdown
        if(Auth::user()->hasRole('admin'))
        {
            $request->validate([
                'organiser_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ]);
            
            $this->organiser_id = $request->organiser_id;
        }
    }

    /**
     *  search tage
     */

    public function search_tags(Request $request)
    {
         // if logged in user is admin
        $this->is_admin($request);
         
        if(!empty($request->search))
        {
            // 1. validate data
            $request->validate([
                'search'   => 'max:255',
            ]);
        }    

        $params    = [
            'organizer_id' => $this->organiser_id,
            'search'       => $request->search
        ];

        $tags  = $this->user->search_tags($params);

        if($tags->isEmpty())
            return response()->json(['status' => false, 'tags'=> []], 200);

        return response()->json(['status' => true, 'tags'=> $tags], 200);    

    }

    
    
}
