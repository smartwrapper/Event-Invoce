<?php

namespace Classiebit\Eventmie\Services;

use Facades\Classiebit\Eventmie\Eventmie;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Models\Booking;

use Classiebit\Eventmie\Charts\EventChart;
use Classiebit\Eventmie\Models\Notification;
use Yajra\Datatables\Datatables;
use Classiebit\Eventmie\Http\Controllers\Voyager\VoyagerBaseController;
use App\Http\Controllers\Controller; 


class Dashboard extends Controller
{
    public function __construct()
    {
        $this->event         = new Event; 
        $this->booking       = new Booking;
        $this->notification  = new Notification;
        $this->user          = new User;
    }

    /**
     *  index page
     */
    public function index(Request $request, $user_id = null, $view = 'eventmie::vendor.voyager.dashboard')
    {
        $total_organizers         = $this->user->total_organizers($user_id);
        $total_customers          = $this->user->total_customers($user_id);
        $total_events             = $this->event->total_event($user_id);
        $total_bookings           = $this->booking->total_bookings($user_id);
        $total_revenue            = $this->booking->total_revenue($user_id);
        $total_notifications      = $this->notification->total_notifications($user_id);
        $events                   = $this->event->get_all_events([], $user_id);
        
        $top_selling_events       = $this->event->top_selling_events($user_id);
        $labels = [];
        $values = [];
        if(!empty($top_selling_events))
        {
            foreach($top_selling_events as $val)
            {
                $labels[] = strlen($val['title']) > 25 ? mb_substr($val['title'], 0, 25, 'utf-8')."..." : $val['title'];
                $values[] = $val['total_bookings'];
            }
        }
        $eventsChart = new EventChart;
        $eventsChart
        ->labels($labels)
        ->dataset(__('voyager::generic.total').' '.__('voyager::generic.Bookings'), 'bar', $values)
        ->color("rgba(27, 137, 239, 1)")
        ->backgroundcolor("rgba(26, 136, 239, 0.7)");

        // if organizer dashboard
        $isOrgDash = false;
        if($user_id)
            $isOrgDash = true;
        
        return Eventmie::view($view, compact(
            'eventsChart', 'total_customers', 'total_organizers', 'total_bookings', 
            'total_revenue', 'total_notifications', 'total_events', 'events', 'isOrgDash'));
    }

    /**
     * sales report
     */

    public function sales_report(Request $request, $user_id = null)
    {
        $event_id    = (int) $request->event_id;

        $ticket      = (int) $request->ticket;

        $full_query  = null;

        $query       = Booking::query();

        $full_query  = $query->select([
                                'bookings.*', 
                                
                                'users.name', 
                                'users.email',
                                
                                'commissions.organiser_earning',
                                'commissions.transferred', 
                                'commissions.admin_commission', 
                                'commissions.admin_tax', 

                                'events.slug as event_slug',
                            ])
                        ->from('bookings')->join('events', 'events.id', '=', 'bookings.event_id')
                        ->join('users', 'users.id', '=', 'bookings.organiser_id')
                        ->leftjoin('commissions', 'commissions.booking_id', '=', 'bookings.id')
                        ->orderBy('bookings.created_at', 'DESC');

                        
        // searching event by event id
        if($event_id > 0)
        {
            $full_query = $query->where('bookings.event_id', $event_id);
        }
        // dd($full_query->get());
        if(!empty($user_id))
        {
            $full_query = $query->where('bookings.organiser_id', $user_id);
        }

        if(!empty($ticket))
        {
            
            $full_query =$query->where(['bookings.ticket_id' => $ticket]);
        }

        return Datatables::of($full_query)->make(true);

    }

    
    /**
     * sale report of particular event
     */

    public function export_sales_report(Request $request, $user_id = null)
    {  
        $request->validate([
            'export_event_id' => 'required|numeric|gt:0',
            
        ]);
        $ticket        = (int) $request->ticket_id;

        $query         = Booking::query();

        $query->select([
                'bookings.*', 
                'bookings.id as booking_id',
                'bookings.created_at as booking_on',
                'users.name as organizer', 
                'users.email',
                
                'commissions.organiser_earning',
                'commissions.transferred', 
                'commissions.admin_commission', 
                'commissions.admin_tax', 

                'events.slug as event_slug',
            ])
        ->from('bookings')->join('events', 'events.id', '=', 'bookings.event_id')
        ->join('users', 'users.id', '=', 'bookings.organiser_id')
        ->leftjoin('commissions', 'commissions.booking_id', '=', 'bookings.id')
        ->orderBy('bookings.created_at', 'DESC');

        if(!empty($user_id))
        {
            $query->where('bookings.organiser_id', $user_id);
        }

        if(!empty($ticket))
        {
            $query->where(['bookings.ticket_id' => $ticket]);
        }

        $sales_report   = $query->where('bookings.event_id', $request->export_event_id)->get();

        // convert array to collection for csv
        $sales_report = collect($sales_report);
                        
        if($sales_report->isEmpty())
            return redirect()->back();

        $csvData = [];

        foreach($sales_report as  $key => $item) 
        {
            $csvData[$key][__('voyager::generic.Order Number')]    = $item['order_number'];
            $csvData[$key][__('voyager::generic.Event')]    = $item['event_title'];
            $csvData[$key][__('voyager::generic.Timing')]   = $item['event_start_date'] .' - '. $item['event_end_date'];
            $csvData[$key][__('voyager::generic.Customer')]  = $item['customer_name'].' ('.$item['customer_email'].')';
            $csvData[$key][ __('voyager::generic.Booking').' '. __('voyager::generic.Date')]  = $item['booking_on'];
            $csvData[$key][ __('voyager::generic.Booking').' '. __('voyager::generic.Date')]  = $item['booking_on'];
            
            $csvData[$key][ __('voyager::generic.Checked In')]  = $item['checked_in'] > 0 ? __('eventmie-pro::em.yes') : __('eventmie-pro::em.no') ;
            
            $csvData[$key][ __('voyager::generic.Ticket')]  =  $item['ticket_price'] .' '. $item['currency'].'('.$item['ticket_title']. ' X '.$item['quantity']. ')';

            $csvData[$key][__('voyager::generic.Order') .' '. __('voyager::generic.total')] = $item['net_price'] .' '. $item['currency'];
            $csvData[$key][__('voyager::generic.Organiser') ] = $item['organizer'].' ('.$item['email'].')';
            
            $csvData[$key][__('voyager::generic.Organiser Earning') ] = $item['organiser_earning'] ? $item['organiser_earning'].' '. $item['currency'] : 0 .' '. $item['currency'];
            
            $csvData[$key][__('voyager::generic.Admin Commission') ] = $item['admin_commission'] ? $item['admin_commission'].' '. $item['currency'] : 0 .' '. $item['currency'];
            
            $csvData[$key][__('voyager::generic.Admin Tax') ] = $item['admin_tax'] ? $item['admin_tax'].' '. $item['currency'] : 0 .' '. $item['currency'];

            $csvData[$key][__('voyager::generic.Payout') ] = ($item['transferred'] <= 0 && $item['organiser_earning'] > 0) ?  __('eventmie-pro::em.pending') : __('eventmie-pro::em.transferred');

        }
        
        // add extra row
        $csvData[count($sales_report) + 1][__('voyager::generic.Order Number')]    =  null;
        $csvData[count($sales_report) + 1][__('voyager::generic.Event')]    =  __('eventmie-pro::em.total');
        $csvData[count($sales_report) + 1][__('voyager::generic.Timing')]   = null;
        $csvData[count($sales_report) + 1][__('voyager::generic.Customer')] = null;
        $csvData[count($sales_report) + 1][ __('voyager::generic.Booking').' '. __('voyager::generic.Date')]  = null;
        $csvData[count($sales_report) + 1][ __('voyager::generic.Booking').' '. __('voyager::generic.Date')]  = null;
        
        $csvData[count($sales_report) + 1][ __('voyager::generic.Checked In')]  = null;
        
        $csvData[count($sales_report) + 1][ __('voyager::generic.Ticket')]  =  $sales_report->sum('ticket_price').' '. $item['currency'];

        $csvData[count($sales_report) + 1][__('voyager::generic.Order') .' '. __('voyager::generic.total')] = $sales_report->sum('net_price').' '. $item['currency'];
        $csvData[count($sales_report) + 1][__('voyager::generic.Organiser') ] = null;
        
        $csvData[count($sales_report) + 1][__('voyager::generic.Organiser Earning') ] = $sales_report->sum('organiser_earning').' '. $item['currency'];
        
        $csvData[count($sales_report) + 1][__('voyager::generic.Admin Commission') ] = $sales_report->sum('admin_commission').' '. $item['currency'];
        
        $csvData[count($sales_report) + 1][__('voyager::generic.Admin Tax') ] = $sales_report->sum('admin_tax').' '. $item['currency'];

        $csvData[count($sales_report) + 1][__('voyager::generic.Payout') ] = null;

        // convert array to collection for csv
        $csvData = collect($csvData);

        // create object of laracsv
        $csvExporter = new \Laracsv\Export();
    
        // create csv 
        $csvExporter->build($csvData, [
            
            //events fields which will be include
            'Order Number',
            'Event',
            'Timing',
            'Customer',
            'Booking Date',
            'Checked In',
            'Ticket',
            'Order Total',
            'Organiser',
            'Organiser Earning',
            'Admin Commission',
            'Admin Tax',
            'Payout'
            
        ]);
        
        // download csv
        $csvExporter->download($sales_report[0]->event_slug.'-sales_report.csv');
    } 

    /**
     *  Event total by sales price
     */

    public function EventTotalBySalesPrice(Request $request, $user_id = null)
    {
        $columns = array( 
            0 =>  'title', 
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir   = $request->input('order.0.dir');


        $event_id    = (int) $request->event_id;

        $full_query  = null;

        $query       = Event::query();

        $full_query = $query->has('bookings')->with(['tickets', 'bookings'])->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir);

        // searching event by event id
        if($event_id > 0)
        {
            $full_query = $query->where('id', $event_id);
        }

        
        $totalData     = Event::has('bookings')->count();        
        $totalFiltered = $totalData; 

        if(!empty($user_id))
        {
            $full_query = $query->where('user_id', $user_id);
                
            $totalData     = Event::has('bookings')->where(['user_id' => $user_id])->count(); 

            $totalFiltered = $totalData; 
        }

        
        
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $events = $full_query->get();
        
        $data = [];

        if($events->isNotEmpty())
        {
            $custom_key          = 0;

            foreach ($events as $key => $event)
            {
                //heading row
                $data[$custom_key]['title']            = '<small class="badge badge-primary">'.$event->title.'</small>';
                $data[$custom_key]['tickets']          = null;

                $data[$custom_key]['tickets_quantity'] = '<span class="badge badge-primary">'.$event->bookings->sum('quantity').'</span>';

                $data[$custom_key]['total_price']      = '<span class="badge badge-primary">'.number_format($event->bookings->sum('net_price'), 2).'</span>';

                $custom_key                      = $custom_key + 1;
                
                foreach($event->tickets as $key1 => $ticket)
                {
                   if($ticket->bookings->isEmpty())
                        continue;

                    $data[$custom_key]['title']             = $event->title;
                    $data[$custom_key]['tickets']           = $ticket->title;
                    $data[$custom_key]['tickets_quantity']  = $ticket->bookings->sum('quantity');
                    $data[$custom_key]['total_price']       = number_format($ticket->bookings->sum('net_price'), 2);

                    $custom_key                      = $custom_key + 1;

                }

            }
            
        }
        
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data
            );

        return $json_data; 

    }

    /**
     *  Event total by sales price
     */

    public function getEvent(Request $request, $user_id = null)
    {
        $event_id = (int)$request->event_id;

        $event = Event::with(['tickets'])->where(['id' => $event_id])->first();

        if(empty($event))
            return response()->json(['status' => false]);

        
        return response()->json(['status' => true, 'event' => $event]);    

    }

    
    
}    