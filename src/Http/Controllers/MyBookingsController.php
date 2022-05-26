<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

use Auth;

use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Category;
use Classiebit\Eventmie\Models\Country;
use Classiebit\Eventmie\Models\Schedule;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\Transaction;
use Classiebit\Eventmie\Models\Commission;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Notifications\MailNotification;



class MyBookingsController extends Controller
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

        $this->middleware(['admin','customer']);

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
    public function index($view = 'eventmie::bookings.customer_bookings', $extra = [])
    {
        // get prifex from eventmie config
        $path = false;
        if(!empty(config('eventmie.route.prefix')))
            $path = config('eventmie.route.prefix');

        // if have booking email data then send booking notification
        $is_success = !empty(session('booking_email_data')) ? 1 : 0;    
    
        return Eventmie::view($view, compact('path', 'is_success','extra'));
    }

    // get bookings by customer id
    public function mybookings(Request $request)
    {
        $params     = [
            'customer_id'  => Auth::id(),
        ];

        $bookings    = $this->booking->get_my_bookings($params);

        return response([
            'bookings'  => $bookings->jsonSerialize(),
            'currency'  => setting('regional.currency_default'),
        ], Response::HTTP_OK);

    }

    // booking cancellation
    public function cancel(Request $request)
    {
        if(!empty(setting('booking.disable_booking_cancellation')))
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );


        $request->validate([
            'event_id'           => 'required|numeric',
            'ticket_id'          => 'required|numeric',
            'booking_id'         => 'required|numeric',
        ]);

        $params = [
            'event_id'    => $request->event_id,
            'ticket_id'   => $request->ticket_id,
            'booking_id'  => $request->booking_id,
            'customer_id' => Auth::id(),
        ];

        // get event by event_id
        $event              = $this->event->get_event(null, $request->event_id);

        if(empty($event))
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );

        // check booking id in booking table for login user
        $check_booking     = $this->booking->check_booking($params);
        
        if(empty($check_booking))
            return error(__('eventmie-pro::em.booking').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );

        $start_date              = Carbon::parse($check_booking['event_start_date'].' '.$check_booking['event_start_time']);
        $end_date                = Carbon::parse(Carbon::now());
        
        // check date expired or not
        if($end_date > $start_date)
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );

        // pre booking time cancellation check    
        $pre_cancellation_time  = (float) setting('booking.pre_cancellation_time'); 
        $min                    = number_format((float)($start_date->diffInMinutes($end_date) ), 2, '.', '');
        $hour_difference        = (float)sprintf("%d.%02d", floor($min/60), $min%60);
        
        if($pre_cancellation_time > $hour_difference)
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );

        // booking cancellation
        $booking_cancel    = $this->booking->booking_cancel($params);

        if(empty($booking_cancel))
            return error(__('eventmie-pro::em.booking_cancellation_fail'), Response::HTTP_BAD_REQUEST );

        /* use updated booking data */
        $check_booking->booking_cancel = 1;
        
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

        $mail['mail_subject']   = __('eventmie-pro::em.booking_cancellation_pending');
        $mail['mail_message']   = __('eventmie-pro::em.booking_cancellation_processing');
        $mail['action_title']   = __('eventmie-pro::em.mybookings');
        $mail['action_url']     = route('eventmie.mybookings_index');
        $mail['n_type']       =  "cancel";

        $notification_ids       = [1, Auth::id(), $check_booking->organiser_id];
        
        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ======================
        

        return response([
            'status'=> true,
        ], Response::HTTP_OK);
        
    }

    


}
