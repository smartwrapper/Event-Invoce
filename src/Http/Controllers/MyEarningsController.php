<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Commission;




class MyEarningsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        // language change
        $this->middleware('common');
    
        $this->middleware(['organiser']);

        $this->event             = new Event;
        $this->commission        = new Commission;
        
    }
    
    /**
     * Show my booking
     *
     * @return array
     */
    public function index($view = 'eventmie::events.event_earning', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');
        
        // show organiser event earning    
        
        return Eventmie::view($view, compact('path', 'extra'));    
        
    }

    /**
     *   Event Earning for Particular Organiser
     */

    public function organiser_event_earning(Request $request)
    {
        
        $params     = [
            'organiser_id'  => Auth::id(),
            'start_date'    => !empty($request->start_date) ? $request->start_date : null,
            'end_date'      => !empty($request->end_date) ? $request->end_date : null,
            'event_id'      => (int)$request->event_id,
            'is_paginate'   => true,
        ];

        // in case of today and tomorrow and weekand
        // if($request->start_date == $request->end_date)
        //     $params['end_date']     = null;

        
        $organiser_earning = $this->commission->show_commission_organisers_wise(Auth::id(), $params);      
        
        return response([
            'event_earning'  => $organiser_earning->jsonSerialize(),
            
        ], Response::HTTP_OK);
    }

    // organiser_total_earning
    public function organiser_total_earning()
    {
        $total_earning         = $this->commission->organiser_total_earning(Auth::id());
        
        return response([
            'currency'  => setting('regional.currency_default'),
            'total_earning'      => $total_earning,
        ], Response::HTTP_OK);
    }
    
}    