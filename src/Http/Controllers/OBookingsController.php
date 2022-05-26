<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Auth;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Transaction;
use Classiebit\Eventmie\Models\Commission;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Notifications\MailNotification;



class OBookingsController extends Controller
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
    
        $this->middleware(['admin','organiser'])->except(['organiser_bookings_show', 'delete_booking', 
        'get_customers']);

        $this->event        = new Event;
        $this->ticket       = new Ticket;
        $this->booking      = new Booking;
        $this->transaction  = new Transaction;
        $this->commission   = new Commission;
    }
    
    /**
     * Show my booking
     *
     * @return array
     */
    public function index($view = 'eventmie::bookings.organiser_bookings', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');
        
        // if have booking email data then send booking notification
        $is_success = !empty(session('booking_email_data')) ? 1 : 0;  

        // show organiser_bookings
        return Eventmie::view($view, compact('path', 'is_success', 'extra'));

    }

    
    /**
     * Show organiser bookings
     *
     * @return array
     */
    public function organiser_bookings(Request $request)
    {
        $params     = [
            'organiser_id'  => Auth::id(),
            'start_date'    => !empty($request->start_date) ? $request->start_date : null,
            'end_date'      => !empty($request->end_date) ? $request->end_date : null,
            'event_id'      => (int)$request->event_id,
        ];

        // in case of today and tomorrow and weekand
        if($request->start_date == $request->end_date)
            $params['end_date']     = null;
    
        $bookings    = $this->booking->get_organiser_bookings($params);
        
        return response([
            'bookings'  => $bookings->jsonSerialize(),
            'currency'  => setting('regional.currency_default'),
        ], Response::HTTP_OK);
    }

    // booking edit for customer by organiser
    public function organiser_bookings_edit(Request $request)
    {
        $request->validate([
            'event_id'           => 'required|numeric',
            'ticket_id'          => 'required|numeric',
            'booking_id'         => 'required|numeric',
            'customer_id'        => 'required|numeric',
            'booking_cancel'     => 'required|numeric',
            'status'             => 'numeric|nullable',
            'is_paid'            => 'numeric|nullable',
        ]);

        $params = [
            'event_id'         => $request->event_id,
            'ticket_id'        => $request->ticket_id,
            'id'               => $request->booking_id,
            'organiser_id'     => Auth::id(),
            'customer_id'      => $request->customer_id,
        ];

        // check booking id in booking table for organiser
        $check_booking     = $this->booking->organiser_check_booking($params);

        if(empty($check_booking))
            return error(__('eventmie-pro::em.booking').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        
        $start_date              = Carbon::parse($check_booking['event_start_date'].' '.$check_booking['event_start_time']);
        $end_date                = Carbon::parse(Carbon::now());
        
        // check date expired or not
        if($end_date > $start_date)
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );

        // pre booking time cancellation check    
        $pre_cancellation_time   = (float) setting('booking.pre_cancellation_time'); 
        $min                     = number_format((float)($start_date->diffInMinutes($end_date) ), 2, '.', '');
        $hour_difference         = (float)sprintf("%d.%02d", floor($min/60), $min%60);
        
        if($pre_cancellation_time > $hour_difference)
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );

        $params = [
            'event_id'         => $request->event_id,
            'ticket_id'        => $request->ticket_id,
            'id'               => $request->booking_id,
            'organiser_id'     => Auth::id(),
            'customer_id'      => $request->customer_id,
        ];

        $data = [
            'booking_cancel'   => $request->booking_cancel,
            'status'           => $request->status ? $request->status : 0 ,
            
            // is_paid
            'is_paid'          =>  $request->is_paid,
        ];
        // booking edit
        $booking_edit    = $this->booking->organiser_edit_booking($data, $params);

        if(empty($booking_edit))
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );


        $params = [
            'booking_id'       => $request->booking_id,
            'organiser_id'     => Auth::id(),
            'status'           => $request->status ? $request->status : 0,
        ];
       
        // edit commision table status when change booking table status change by organiser 
        $edit_commission  = $this->commission->edit_commission($params);    

        if(empty($edit_commission))
            return error(__('eventmie-pro::em.commission').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );

        /* use updated booking data */
        $check_booking->booking_cancel = $data['booking_cancel'];
        $check_booking->status         = $data['status'];
        $check_booking->is_paid        = $data['is_paid'];
        
        // ====================== Notification ====================== 
        //send notification after bookings
        $msg[]                  = __('eventmie-pro::em.customer').' - '.$check_booking->customer_name;
        $msg[]                  = __('eventmie-pro::em.email').' - '.$check_booking->customer_email;
        $msg[]                  = __('eventmie-pro::em.event').' - '.$check_booking->event_title;
        $msg[]                  = __('eventmie-pro::em.category').' - '.$check_booking->event_category;
        $msg[]                  = __('eventmie-pro::em.ticket').' - '.$check_booking->ticket_title;
        $msg[]                  = __('eventmie-pro::em.price').' - '.$check_booking->ticket_price;
        $msg[]                  = __('eventmie-pro::em.order').' - #'.$check_booking->order_number;
        $msg[]                  = __('eventmie-pro::em.status').' - '.($check_booking->status ? __('eventmie-pro::em.enabled') : __('eventmie-pro::em.disabled'));
        $msg[]                  = __('eventmie-pro::em.payment').' - '.($check_booking->is_paid ? __('eventmie-pro::em.paid') : __('eventmie-pro::em.unpaid'));
        $cancellation_msg           = __('eventmie-pro::em.no_cancellation');
        if($check_booking->booking_cancel == 1)
            $cancellation_msg       = __('eventmie-pro::em.pending');
        elseif($check_booking->booking_cancel == 2)
            $cancellation_msg       = __('eventmie-pro::em.approved');
        elseif($check_booking->booking_cancel == 3)
            $cancellation_msg       = __('eventmie-pro::em.refunded');

        $msg[]                  = __('eventmie-pro::em.cancellation').' - '.$cancellation_msg;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventmie-pro::em.booking_cancellation_update');
        $mail['mail_message']   = __('eventmie-pro::em.booking_status');
        $mail['action_title']   = __('eventmie-pro::em.mybookings');
        $mail['action_url']     = route('eventmie.mybookings_index');
        $mail['n_type']       = "cancel";

        
        $notification_ids       = [1, $check_booking->organiser_id, $check_booking->customer_id];
        
        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ======================  
        
        return response([
            'status'=> true,
        ], Response::HTTP_OK);
    }

    // view coustomer booking by oraganiser
    public function organiser_bookings_show($id = null, $view = 'eventmie::bookings.show', $extra = [])
    {
        
        $id    = (int) $id;
        $organiser_id  = Auth::id(); 

        if(!$id)
              // redirect no matter what so that it never turns back
              return response(['status'=>__('eventmie-pro::em.invalid').' '.__('eventmie-pro::em.data'), 'url'=>'/events'], Response::HTTP_OK);    

        // admin can see booking detail page
        if(Auth::user()->hasRole('admin'))
        {
            // when admin wiil be login and he can see booking help or organiser id
            $params   = [
                'id'  => $id,
            ];

            $booking   = $this->booking->organiser_check_booking($params);
            if(empty($booking))
                // redirect no matter what so that it never turns back
                return success_redirect(__('eventmie-pro::em.booking').' '.__('eventmie-pro::em.not_found'), route('eventmie.events_index'));  

            $organiser_id  = $booking->organiser_id;
        }

        $params = [
            'organiser_id' => $organiser_id,
            'id'           => $id,
        ];

        // get customer booking by orgniser
        $booking = $this->booking->organiser_view_booking($params);   
    
        if(empty($booking))
        {
            // redirect no matter what so that it never turns back
            return success_redirect(__('eventmie-pro::em.booking').' '.__('eventmie-pro::em.not_found'), route('eventmie.events_index'));  
        }    

        $currency   = setting('regional.currency_default');
        
        $params = [
            'transaction_id' => $booking['transaction_id'],
            'order_number'   => $booking['order_number']
        ];

        // get transaction information by orgniser for this booking
        $payment = $this->transaction->organiser_payment_info($params);   
        
        return Eventmie::view($view, compact('booking', 'payment', 'currency', 'extra'));

    }

    /**
     *   only admin can delete booking
     */

    public function delete_booking($id = null)
    {
        // only admin can delete booking
        if(Auth::check() && !Auth::user()->hasRole('admin'))
        {
            return redirect()->route('eventmie.events');
        }

        // get event by event_slug
        if(empty($id))
            return error('Booking Not Found!', Response::HTTP_BAD_REQUEST );
        
        $params    = [
            'id'     => $id,
        ];

        $delete_booking     = $this->booking->delete_booking($params);

        if(empty($delete_booking))
        {
            return error(__('eventmie-pro::em.booking_deleted_fail'), Response::HTTP_BAD_REQUEST );   
        }

        $msg = __('eventmie-pro::em.booking_deleted');
        
        return redirect()
        ->route("voyager.bookings.index")
        ->with([
            'message'    => $msg,
            'alert-type' => 'success',
        ]);
        
    }

    
    /**
     *  get customers
     */

    public function get_customers(Request $request)
    {
        $request->validate([
            'search'        => 'required|email|max:256',
        ]);

        $search     = $request->search;
        $customers  = $this->event->search_customers($search);

        if(empty($customers))
        {
            return response()->json(['status' => false, 'customers' => $customers]);    
        }

        foreach($customers as $key => $val)
            $customers[$key]->name = $val->name.'  ( '.$val->email.' )';

        return response()->json(['status' => true, 'customers' => $customers ]);
    }

}
