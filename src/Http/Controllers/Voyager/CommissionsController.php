<?php

namespace Classiebit\Eventmie\Http\Controllers\Voyager;
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Database\Eloquent\SoftDeletes;
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

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Classiebit\Eventmie\Models\Commission;
use Classiebit\Eventmie\Models\User;

class CommissionsController extends VoyagerBaseController
{
    use BreadRelationshipParser;

    public function __construct()
    {
        // disable modification functions that are not managed from admin panel
        $route_name     = "voyager.commissions";
        $enable_routes = ["$route_name.index", "$route_name.destroy", "$route_name.show", "$route_name.update", "$route_name.edit", "eventmie.commission_update", "eventmie.settlement_update"];
        if(! in_array(\Route::current()->getName(), $enable_routes))
        {
            return redirect()->route('voyager.commissions.index')->send();
        }

        $this->commission   = new Commission;
        $this->user         = new User;
        // ---------------------------------------------------------------------
    }

    // show all commission of organisers
    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $commissions = $this->commission->admin_commission();
        
        $view = 'eventmie::vendor.voyager.commissions.browse';

        return Eventmie::view($view, compact(
            'dataType',    
            'commissions'   
        ));
    }

    // show  commission organisers and month_year wise for admin
    public function show(Request $request, $organiser_id = null)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        
        $params     = [
            'start_date'    => !empty($request->start_date) ? $request->start_date : null,
            'end_date'      => !empty($request->end_date) ? $request->end_date : null,
            'event_id'      => null,
            'is_paginate'   => false,
        ];

        $commissions = $this->commission->show_commission_organisers_wise($organiser_id, $params);
        $refunds = $this->commission->show_commission_organisers_wise($organiser_id, $params, true);
        $organiser = $this->user->get_user(['id'=>$organiser_id]);
        
        $view = 'eventmie::vendor.voyager.commissions.update_commissions';

        return Eventmie::view($view, compact(
            'dataType',
            'commissions',
            'refunds',
            'organiser'
        ));
    }

    
    public function commission_update(Request $request)
    {
        $slug     = 'commissions';
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
    
        // extra data update ========================================================================
        
        $params = [
            'organiser_id'     => $request->organiser_id,
            'month_year'       => $request->month_year,
            'transferred'      => $request->transferred  == "on" ? 1 : 0,
            'event_id'         => (int)$request->event_id,
        ];

        
        // edit commision table status when change booking table status change by organiser 
        $edit_commission  = $this->commission->admin_edit_commission($params);    
        
        if(empty($edit_commission))
            return error('Commission not found!', Response::HTTP_BAD_REQUEST );
        // extra data update ========================================================================
            
        return redirect()
        ->route("voyager.{$dataType->slug}.show",[$request->organiser_id])
        ->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
            'alert-type' => 'success',
        ]);
    }

    // settlement update
    public function settlement_update(Request $request)
    {
        $slug     = 'commissions';
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
    
        // extra data update ========================================================================
        
        $params = [
            'organiser_id'     => $request->organiser_id,
            'month_year'       => $request->month_year,
            'settled'          => $request->settled  == "on" ? 1 : 0,
            'event_id'         => (int)$request->event_id,
        ];

        
        // edit commision table status when change booking table status change by organiser 
        $edit_commission  = $this->commission->admin_edit_settlement($params);    
        
        if(empty($edit_commission))
            return error('Commission not found!', Response::HTTP_BAD_REQUEST );
        // extra data update ========================================================================
            
        return redirect()
        ->route("voyager.{$dataType->slug}.show",[$request->organiser_id])
        ->with([
            'message'    => __('voyager::generic.successfully_updated').' '.__('voyager::generic.settlement'),
            'alert-type' => 'success',
        ]);
    }
}
