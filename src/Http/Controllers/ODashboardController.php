<?php

namespace Classiebit\Eventmie\Http\Controllers;
use Facades\Classiebit\Eventmie\Eventmie;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Models\Booking;

use Classiebit\Eventmie\Charts\EventChart;
use Classiebit\Eventmie\Models\Notification;
use Yajra\Datatables\Datatables;

use Classiebit\Eventmie\Services\Dashboard;
use Classiebit\Eventmie\Http\Controllers\Voyager\VoyagerBaseController;
use Auth;
class ODashboardController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware(['organiser']);

        $this->dashboard_service = new Dashboard;
    }

    /**
     *  index page
     */
    public function index(Request $request)
    {
        // redirect if admin
        if(Auth::user()->hasRole('admin'))
            return redirect(route('eventmie.welcome'));

        return $this->dashboard_service->index($request, Auth::user()->id);
    }

    /**
     *  Event total by sales price
     */

    public function EventTotalBySalesPrice(Request $request)
    {
        $data = $this->dashboard_service->EventTotalBySalesPrice($request, Auth::user()->id);
        
        echo json_encode($data);

    }

    /**
     *  get Event
     */

    public function getEvent(Request $request)
    {
        return $this->dashboard_service->getEvent($request, Auth::user()->id);
    }
    
    
}    