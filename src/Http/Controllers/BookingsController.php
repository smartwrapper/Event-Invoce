<?php           

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Auth;
use Classiebit\Eventmie\Services\PaypalExpress;
use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Ticket;
use Classiebit\Eventmie\Models\Booking;
use Classiebit\Eventmie\Models\User;
use Classiebit\Eventmie\Models\Commission;
use Classiebit\Eventmie\Models\Transaction;
use Classiebit\Eventmie\Models\Tax;


class BookingsController extends Controller
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
    
        // authenticate except 
        $this->middleware('auth')->except(['login_first', 'signup_first']);

        $this->event        = new Event;
        $this->ticket       = new Ticket;
        $this->booking      = new Booking;
        $this->transaction  = new Transaction;
        $this->user         = new User;
        $this->commission   = new Commission;
        $this->tax          = new Tax;
        $this->customer_id  = null;
        $this->organiser_id = null;
    }

    // only customers can book tickets so check login user customer or not but admin and organisers can book tickets for customer
    protected function is_admin_organiser(Request $request)
    {
        
        if(Auth::check())
        {
            // get event by event_id
            $event          = $this->event->get_event(null, $request->event_id);
            
            // if event not found then access denied
            if(empty($event))
                return ['status' => false, 'error' =>  __('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found')];
            
                
            // organiser can't book other organiser event's tikcets but  admin can book any organiser events'tikcets for customer
            if(Auth::user()->hasRole('organiser'))
            {
                if(Auth::id() != $event->user_id)
                    return false;
            }
            
            //organiser_id 
            $this->organiser_id = $event->user_id;
            
            // if login user is customer then 
            // customer id = Auth::id();
            $this->customer_id = Auth::id();

            // if admin and organiser is creating booking
            // then user Auth::id() as $customer_id
            // and customer id will be the id selected from Vue dropdown
            if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('organiser') )
            {
                // 1. validate data
                $request->validate([
                    'customer_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
                ], [
                    'customer_id.*' => __('eventmie-pro::em.customer').' '.__('eventmie-pro::em.required'),
                ]);
                $this->customer_id = $request->customer_id;
            }

            return true;
        }    
    }

    
    // check for available seats
    protected function availability_validation($params = [])
    {
        $event_id           = $params['event_id'];
        $selected_tickets   = $params['selected_tickets'];
        $ticket_ids         = $params['ticket_ids'];
        $booking_date       = $params['booking_date'];
        
        // 1. Check booking.max_ticket_qty
        foreach($selected_tickets as $key => $value)
        {
            // user can't book tickets more than limitation 
            if($value['quantity'] > setting('booking.max_ticket_qty')) 
            {
                $msg = __('eventmie-pro::em.max_ticket_qty');
                return ['status' => false, 'error' => $msg.setting('booking.max_ticket_qty')];
            }
        }

        // 2. Check availability over booked tickets

        // actual tickets
        $tickets       = $this->ticket->get_booked_tickets($ticket_ids);
        
        // get the bookings for live availability check
        $bookings       = $this->booking->get_seat_availability_by_ticket($event_id);
        
        // actual tickets (quantity) - already booked tickets on booking_date (total_booked)
        foreach($tickets as $key => $ticket)
        {
            foreach($selected_tickets as $k => $selected_ticket)
            {
                if($ticket->id == $selected_ticket['ticket_id'])
                {
                    
                    // First. check selected quantity against actual ticket capacity
                    if( $selected_ticket['quantity'] > $ticket->quantity )
                        return ['status' => false, 'error' => $ticket->title .' '.__('eventmie-pro::em.vacant').' - '.$ticket->quantity];
                    
                    // Second. seat availability for selected booking-date in bookings table
                    foreach($bookings as $k2 => $booking)
                    {
                        // check for specific dates + specific ticket
                        if($booking->event_start_date == $booking_date && $booking->ticket_id == $ticket->id)
                        {
                            $available = $ticket->quantity - $booking->total_booked;
                            
                            // false condition
                            // if selected ticket quantity is greator than available
                            if( $selected_ticket['quantity'] > $available )
                                return ['status' => false, 'error' => $ticket->title .' '.__('eventmie-pro::em.vacant').' - '.$available];

                            // Customer limit check
                            $error = $this->customer_limit($ticket, $selected_ticket, $booking_date);
                            if(!empty($error))
                                return $error;
                        }
                    }
                    
                }
            }
        }

        return ['status'   => true];
    }

    // validate user post data
    protected function general_validation(Request $request)
    {
        
        $request->validate([
            'event_id'          => 'required|numeric|gte:1',
            
            'ticket_id'         => ['required', 'array'],
            'ticket_id.*'       => ['required', 'numeric'],
            
            'quantity'          => [ 'required', 'array'],
            'quantity.*'        => [ 'required', 'numeric', 'integer', 'gte:0'],

            // repetitve booking date validation
            'booking_date'      => 'date_format:Y-m-d|required',
            'start_time'        => 'date_format:H:i:s|required',
            'end_time'          => 'date_format:H:i:s|required',
        ]);

        if(!empty($request->merge_schedule))
        {
            $request->validate([
                'booking_end_date'      => 'date_format:Y-m-d|required',
            ]);
                
        }
        
        // get event by event_id
        $event          = $this->event->get_event(null, $request->event_id);
        
        // if event not found then access denied
        if(empty($event))
            return ['status' => false, 'error' =>  __('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found')];
        
        // get only ticket_ids which quantity is >0
        $ticket_ids         = [];
        $selected_tickets   = [];
        
        foreach($request->quantity as $key => $val)
        {
            if($val)
            {
                $ticket_ids[]                               = $request->ticket_id[$key];
                $selected_tickets[$key]['ticket_id']        = $request->ticket_id[$key]; 
                $selected_tickets[$key]['ticket_title']     = $request->ticket_title[$key];  
                $selected_tickets[$key]['quantity']         = $val < 1 ? 1 : $val; // min qty = 1
            }
        }
 
        if(empty($ticket_ids))
            return ['status' => false, 'error' => __('eventmie-pro::em.select_a_ticket')];
            
        $params       =  [
            'event_id'   => $request->event_id,
            'ticket_ids' => $ticket_ids,
        ];

        // check ticket in tickets table that exist or not
        $tickets   = $this->ticket->get_event_tickets($params);

        // if ticket not found then access denied
        if($tickets->isEmpty())
            return ['status' => false, 'error' => __('eventmie-pro::em.tickets').' '.__('eventmie-pro::em.not_found')];

        return [
            'status'            => true,
            'event_id'          => $request->event_id,
            'selected_tickets'  => $selected_tickets,
            'tickets'           => $tickets,
            'ticket_ids'        => $ticket_ids,
            'event'             => $event,
            'booking_date'      => $request->booking_date,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
        ];

    }

    // pre booking time validation
    protected function time_validation($params = [])
    {
        $booking_date           = $params['booking_date'];
        $start_time             = $params['start_time'];
        $start_time             = $params['end_time'];
        
        // booking date is event start date and it is less then today date then user can't book tickets
        $start_date             = Carbon::parse($booking_date.''.$start_time);  
            
        $today_date             = Carbon::parse(Carbon::now());
 
        // 1.Booking date should not be less than today's date
        if($start_date < $today_date)
            return ['status' => false, 'error' => __('eventmie-pro::em.event').' '.__('eventmie-pro::em.ended')];
        
        // 2. Check prebooking time from settings (in hour)
        $default_prebook_time = (float) setting('booking.pre_booking_time');
        
        $min        = number_format((float)($start_date->diffInMinutes($today_date) ), 2, '.', '');
        
        $hours      = (float)sprintf("%d.%02d", floor($min/60), $min%60);

        if($hours < $default_prebook_time)
            return ['status' => false, 'error' => __('eventmie-pro::em.bookings_over')];

        return ['status' => true];    
    }
    

    // book tickets
    public function book_tickets(Request $request)
    {
        // check login user role
        $status = $this->is_admin_organiser($request);

        // organiser can't book other organiser event's tikcets but  admin can book any organiser events'tikcets for customer
        if(!$status)
        {
            return response([
                'status'    => false,
                'url'       => route('eventmie.events_index'),
                'message'   => __('eventmie-pro::em.organiser_note_5'),
            ], Response::HTTP_OK);
        }

        // 1. General validation and get selected ticket and event id
        $data = $this->general_validation($request);
        if(!$data['status'])
            return error($data['error'], Response::HTTP_BAD_REQUEST);
            
        // 2. Check availability
        $check_availability = $this->availability_validation($data);
        if(!$check_availability['status'])
            return error($check_availability['error'], Response::HTTP_BAD_REQUEST);

        // 3. TIMING & DATE CHECK 
        $pre_time_booking   =  $this->time_validation($data);    
        if(!$pre_time_booking['status'])
            return error($pre_time_booking['error'], Response::HTTP_BAD_REQUEST);

        $selected_tickets   = $data['selected_tickets'];
        $tickets            = $data['tickets'];

        
        $booking_date = $request->booking_date;

        $params  = [
            'customer_id' => $this->customer_id,
        ];
        // get customer information by customer id    
        $customer   = $this->user->get_customer($params);

        if(empty($customer))
            return error($pre_time_booking['error'], Response::HTTP_BAD_REQUEST);     

        $booking        = [];
        $price          = 0;
        $total_price    = 0; 
        
        // organiser_price excluding admin_tax
        $booking_organiser_price    = [];
        $admin_tax                  = [];
        foreach($selected_tickets as $key => $value)
        {
            $key = count($booking) == 0 ? 0 : count($booking);
            
            for($i = 1; $i <= $value['quantity']; $i++)
            {
                $booking[$key]['customer_id']       = $this->customer_id;
                $booking[$key]['customer_name']     = $customer['name'];
                $booking[$key]['customer_email']    = $customer['email'];
                $booking[$key]['organiser_id']      = $this->organiser_id;
                $booking[$key]['event_id']          = $request->event_id;
                $booking[$key]['ticket_id']         = $value['ticket_id'];
                $booking[$key]['quantity']          = 1;
                $booking[$key]['status']            = 1; 
                $booking[$key]['created_at']        = Carbon::now();
                $booking[$key]['updated_at']        = Carbon::now();
                $booking[$key]['event_title']       = $data['event']['title'];
                $booking[$key]['event_category']    = $data['event']['category_name'];
                $booking[$key]['ticket_title']      = $value['ticket_title'];
                $booking[$key]['item_sku']          = $data['event']['item_sku'];
                $booking[$key]['currency']          = setting('regional.currency_default');

                $booking[$key]['event_repetitive']  = $data['event']->repetitive > 0 ? 1 : 0;

                // non-repetitive
                $booking[$key]['event_start_date']  = $data['event']->start_date;
                $booking[$key]['event_end_date']    = $data['event']->end_date;
                $booking[$key]['event_start_time']  = $data['event']->start_time;
                $booking[$key]['event_end_time']    = $data['event']->end_time;
                
                // repetitive event
                if($data['event']->repetitive)
                {
                    $booking[$key]['event_start_date']  = $booking_date;
                    $booking[$key]['event_end_date']    = $request->merge_schedule ? $request->booking_end_date : $booking_date;
                    $booking[$key]['event_start_time']  = $request->start_time;
                    $booking[$key]['event_end_time']    = $request->end_time;
                }
                
                foreach($tickets as $k => $v)
                {
                    if($v['id'] == $value['ticket_id'])
                    {
                        $price       = $v['price'];
                        break;
                    }
                }
                $booking[$key]['price']         = $price * 1;
                $booking[$key]['ticket_price']  = $price;

                // call calculate price
                $params   = [
                    'ticket_id'         => $value['ticket_id'],
                    'quantity'          => 1,
                ];
        
                // calculating net price
                $net_price    = $this->calculate_price($params);

        
                $booking[$key]['tax']        = number_format((float)($net_price['tax']), 2, '.', '');
                $booking[$key]['net_price']  = number_format((float)($net_price['net_price']), 2, '.', '');
                
                // organiser price excluding admin_tax
                $booking_organiser_price[$key]['organiser_price']  = number_format((float)($net_price['organiser_price']), 2, '.', '');

                //  admin_tax
                $admin_tax[$key]['admin_tax']  = number_format((float)($net_price['admin_tax']), 2, '.', '');


                // if payment method is offline then is_paid will be 0
                if($request->payment_method == 'offline')
                {
                    // except free ticket
                    if(((int) $booking[$key]['net_price']))
                        $booking[$key]['is_paid'] = 0;
                }
                else
                {
                    $booking[$key]['is_paid'] = 1;  
                }

                $key++;
            }
            
            
        }
        
        // calculate commission 
        $this->calculate_commission($booking, $booking_organiser_price, $admin_tax);

        // if net price total == 0 then no paypal process only insert data into booking 
        foreach($booking as $k => $v)
        {
            $total_price  += (float)$v['net_price'];
            $total_price = number_format((float)($total_price), 2, '.', '');
        }

        // check if eligible for direct checkout
        $is_direct_checkout = $this->checkDirectCheckout($request, $total_price);
    
        // IF FREE EVENT THEN ONLY INSERT DATA INTO BOOKING TABLE 
        // AND DON'T INSERT DATA INTO TRANSACTION TABLE 
        // AND DON'T CALLING PAYPAL API
        if($is_direct_checkout)
        {
            $data = [
                'order_number' => time().rand(1,988),
                'transaction_id' => 0
            ];
            $flag =  $this->finish_booking($booking, $data);

            // in case of database failure
            if(empty($flag))
            {
                return error('Database failure!', Response::HTTP_REQUEST_TIMEOUT);
            }

            // redirect no matter what so that it never turns backreturn response
            $msg = __('eventmie-pro::em.booking_success');
            session()->flash('status', $msg);

            // if customer then redirect to mybookings
            $url = route('eventmie.mybookings_index');
            
            if(Auth::user()->hasRole('organiser'))
                $url = route('eventmie.obookings_index');
            
            if(Auth::user()->hasRole('admin'))
                $url = route('voyager.bookings.index');

            return response([
                'status'    => true,
                'url'       => $url,
                'message'   => $msg,
            ], Response::HTTP_OK);
        }    
        
        // return to paypal
        session(['booking'=>$booking]);

        return $this->init_checkout($booking);
    }

     /** 
     * Initialize checkout process
     * 1. Validate data and start checkout process
    */
    protected function init_checkout($booking)
    {   
        // add all info into session
        $order = [
            'item_sku'          => $booking[key($booking)]['item_sku'],
            'order_number'      => time().rand(1,988),
            'product_title'     => $booking[key($booking)]['event_title'],
            
            'price_title'       => '',
            'price_tagline'     => '',
        ];

        $total_price   = 0;

        foreach($booking as $key => $val)
        {
            $order['price_title']   .= ' | '.$val['ticket_title'].' | ';
            $order['price_tagline'] .= ' | '.$val['quantity'].' | ';
            $total_price            += $val['net_price'];
        }
        
        // calculate total price
        $order['price']             = $total_price;

        // set session data
        session(['pre_payment' => $order]);
        
        return $this->paypal($order, setting('regional.currency_default'));
    }

    /* =================== PAYPAL ==================== */
    // 2. Create an order and redirect to payment gateway
    protected function paypal($order = [], $currency = 'USD')
    {
        $paypal_express = new PaypalExpress(setting('apps'));
        $flag           = $paypal_express->create_order($order, $currency);

        // if order creation successful then redirect to paypal
        if($flag['status'])
            return response(['status' => true, 'url'=>$flag['url'], 'message'=>''], Response::HTTP_OK);    

        return error($flag['error'], Response::HTTP_REQUEST_TIMEOUT);
    }
    
    // 3. On return from gateway check if payment fail or success
    public function paypal_callback(Request $request)
    {
        /* Filter out direct fake callback request */
        if(empty($request->paymentId))
        {
            $msg = __('eventmie-pro::em.booking').' '.__('eventmie-pro::em.failed');
            // if customer then redirect to mybookings
            $url = route('eventmie.mybookings_index');
            if(Auth::user()->hasRole('organiser'))
                $url = route('eventmie.obookings_index');

            return redirect($url)->withErrors([$msg]);
        }

        $paypal_express = new PaypalExpress(setting('apps'));
        $flag           = $paypal_express->callback($request);

        return $this->finish_checkout($flag);
    }    

    /* =================== PAYPAL ==================== */

    /** 
     * 4 Finish checkout process
     * Last: Add data to purchases table and finish checkout
    */
    protected function finish_checkout($flag = [])
    {
        // prepare data to insert into table
        $data                   = session('pre_payment');
        // unset extra columns
        unset($data['product_title']);
        unset($data['price_title']);
        unset($data['price_tagline']);
        

        $booking                = session('booking');
        
        // IMPORTANT!!! clear session data setted during checkout process
        session()->forget(['pre_payment', 'booking']);
        
        
        // if customer then redirect to mybookings
        $url = route('eventmie.mybookings_index');
        if(Auth::user()->hasRole('organiser'))
            $url = route('eventmie.obookings_index');
        
        if(Auth::user()->hasRole('admin'))
            $url = route('voyager.bookings.index');

        // if success 
        if($flag['status'])
        {
            $data['txn_id']             = $flag['transaction_id'];
            $data['amount_paid']        = $data['price'];
            unset($data['price']);
            $data['payment_status']     = $flag['message'];
            $data['payer_reference']    = $flag['payer_reference'];
            $data['status']             = 1;
            $data['created_at']         = Carbon::now();
            $data['updated_at']         = Carbon::now();
            $data['currency_code']      = setting('regional.currency_default');
            $data['payment_gateway']    = 'paypal';
            
            // insert data of paypal transaction_id into transaction table
            $flag                       = $this->transaction->add_transaction($data);

            $data['transaction_id']     = $flag; // transaction Id
            
            $flag = $this->finish_booking($booking, $data);

            // in case of database failure
            if(empty($flag))
            {
                
                $msg = __('eventmie-pro::em.booking').' '.__('eventmie-pro::em.failed');
                session()->flash('status', $msg);
                return error_redirect($msg);
            }

            // redirect no matter what so that it never turns back
            $msg = __('eventmie-pro::em.booking_success');
            session()->flash('status', $msg);
            return success_redirect($msg, $url);
        }
        
        // if fail
        // redirect no matter what so that it never turns back
        $msg = __('eventmie-pro::em.payment').' '.__('eventmie-pro::em.failed');
        session()->flash('error', $msg);
        
        
        return error_redirect($msg);
    }

    // 5. finish booking
    protected function finish_booking($booking = [], $data = [])
    {
        $admin_commission   = setting('multi-vendor.admin_commission');
            
        $params = [];
        foreach($booking as $key => $value)
        {
            $params[$key] = $value;
            $params[$key]['order_number']    = $data['order_number'];
            $params[$key]['transaction_id']  = $data['transaction_id'];
            
            // is online or offline
            $params[$key]['payment_type']       = 'offline';
            if($data['transaction_id'])
                $params[$key]['payment_type']   = 'online';
        }
        
        // get booking_id
        // update commission session array
        // insert into commission
        $commission_data            = [];
        $commission                 = session('commission');

        // delete commission data from session
        session()->forget(['commission']);
        $booking_data = [];
        foreach($booking as $key => $value)
        {
            $data     = $this->booking->make_booking($params[$key]);
            $booking_data[] = $data;
            if( $value['net_price'] > 0)
            {
                $commission_data[$key]                 = $commission[$key];
                $commission_data[$key]['booking_id']   = $data->id;
                $commission_data[$key]['month_year']   = Carbon::parse($data->created_at)->format('m Y');
                $commission_data[$key]['created_at']   = Carbon::now();
                $commission_data[$key]['updated_at']   = Carbon::now();
                $commission_data[$key]['event_id']     = $data->event_id;
                $commission_data[$key]['status']       = $data->is_paid > 0 ? 1 : 0; 
            }
        }
        
        // insert data in commission table
        $this->commission->add_commission($commission_data);
    
        // store booking date for email notification        
        session(['booking_email_data'=> $booking_data]);

        return true;
    }

    /**
     *  calculate net price for paypal
     */

    protected function calculate_price($params = [])
    {
        // check ticket in tickets table that exist or not
        $ticket   = $this->ticket->get_ticket($params);
        
        // apply admin tax
        $ticket   = $this->admin_tax($ticket);
        
        $net_price      = [];
        $amount         = 0;
        $tax            = 0;
        $excluding_tax  = 0;
        $including_tax  = 0; 
         
        $amount  = $ticket['price']*$params['quantity'];

        $net_price['tax']               = $tax;
        $net_price['net_price']         = $tax+$amount;
        
        // organiser_price = net_price excluding admin_tax
        $net_price['organiser_price']   = $tax+$amount;
        $excluding_tax_organiser        = 0;
        $including_tax_organiser        = 0; 
        $admin_tax                      = 0;

        // calculate multiple taxes on ticket
        if($ticket['taxes']->isNotEmpty() && $amount > 0)
        {
            foreach($ticket['taxes'] as $tax_k => $tax_v)
            {
                //if have no taxes then return net_price
                if(empty($tax_v->rate_type))
                    return $net_price;  
                
                // in case of percentage
                if($tax_v->rate_type == 'percent')
                {
                    $tax     = (float) ($amount * $tax_v->rate)/100; 
                 
                    // in case of including
                    if($tax_v->net_price == 'including')
                    {
                        $including_tax       = $tax + $including_tax;

                        // exclude admin tax
                        if(!$tax_v->admin_tax)
                            $including_tax_organiser  = $tax + $including_tax_organiser;

                        //admin tax
                        if($tax_v->admin_tax)
                            $admin_tax = $admin_tax + $tax;

                    }
                    

                    // in case of excluding
                    if($tax_v->net_price == 'excluding')
                    {
                        $excluding_tax       = $tax + $excluding_tax;

                        // exclude admin tax
                        if(!$tax_v->admin_tax)
                            $excluding_tax_organiser  = $tax + $excluding_tax_organiser;

                        
                        //admin tax
                        if($tax_v->admin_tax)
                            $admin_tax = $admin_tax + $tax;    
                    }
                    
                }
        
                //  in case of fixed
                if($tax_v->rate_type == 'fixed')
                {
                    $tax                     = (float) ($params['quantity'] * $tax_v->rate);
                    
                    // // in case of including
                    if($tax_v->net_price == 'including')
                    {
                        $including_tax = $tax + $including_tax;

                        // exclude admin tax
                        if(!$tax_v->admin_tax)
                            $including_tax_organiser  = $tax + $including_tax_organiser;

                        
                        //admin tax
                        if($tax_v->admin_tax)
                            $admin_tax = $admin_tax + $tax;    

                    }
                    
                    
                    // // in case of excluding
                    if($tax_v->net_price == 'excluding')
                    {
                        $excluding_tax   = $tax + $excluding_tax;

                        // exclude admin tax
                        if(!$tax_v->admin_tax)
                            $excluding_tax_organiser  = $tax + $excluding_tax_organiser;

                            
                        //admin tax
                        if($tax_v->admin_tax)
                            $admin_tax = $admin_tax + $tax;

                    }
                }
            }
        }
       
        $net_price['tax']               = (float) ($excluding_tax + $including_tax);
        $net_price['net_price']         = (float) ($amount + $excluding_tax);
        
        // organiser_price excluding admin_tax
        $net_price['organiser_price']   = (float) ($amount + $excluding_tax_organiser);

        //admin tax
        $net_price['admin_tax']         = (float) ($admin_tax);
        
        return $net_price;
    }

    // calculate admin commission
    protected function calculate_commission($booking = [], $booking_organiser_price = [], $booking_admin_tax = [])
    {
        $commission         = [];
        $admin_commission   = setting('multi-vendor.admin_commission');
        $margin             = 0;
        
        if(empty($admin_commission))
            $admin_commission = 0;
           
        foreach($booking as $key => $value)
        {
            // skip for free tickets
            // calculate commission on organiser_price
            // excluding admin_tax
            $organiser_price = $booking_organiser_price[$key]['organiser_price'];
            $admin_tax       = $booking_admin_tax[$key]['admin_tax'];
            
            if($organiser_price > 0)
            {
                $commission[$key]['organiser_id']         = $value['organiser_id'];
                $commission[$key]['customer_paid']        = $organiser_price;

                if($admin_commission > 0)
                    $margin = (float) ( ($admin_commission * $organiser_price) /100 );

                $commission[$key]['organiser_earning']    = (float) $organiser_price - $margin;

                // customer_paid - organizer_earning = admin_commission
                $commission[$key]['admin_commission']     = $commission[$key]['customer_paid'] - $commission[$key]['organiser_earning'];

                $commission[$key]['admin_tax']     = $admin_tax; 
            }
        }
    
        session(['commission'=>$commission]);

        return true;
    }

    /* Validate offline payment method */
    protected function checkDirectCheckout(Request $request, $total_price = 0)
    {
        // check if Free event
        if($total_price <= 0)
            return true;

        // if it's Admin
        if(Auth::user()->hasRole('admin'))
            return true;

        // get payment method
        // paypal will always be default payment method
        // payment_method can either 1 or offline
        $payment_method = 1;
        if($request->has('payment_method'))
        {
            if($request->payment_method == 'offline')
                $payment_method = 'offline';
            else
                $payment_method = (int) $request->payment_method;
        }

        // if not-offline
        if($payment_method != 'offline')
            return false;

        /* In case of offline method selected */
        
        // if Organizer
        // check if offline_payment_organizer enabled
        if(Auth::user()->hasRole('organiser'))
            if(setting('booking.offline_payment_organizer'))
                return true;

        // if Customer
        // check if offline_payment_customer enabled
        if(Auth::user()->hasRole('customer'))
            if(setting('booking.offline_payment_customer'))
                return true;

        return false;
    }
       
    /**
     *  admin tax apply on all tickets
     */
    protected function admin_tax($tickets = [])
    {
        // get admin taxes
        $admin_tax  = $this->tax->get_admin_taxes();
        
        // if admin taxes are not existed then return
        if($admin_tax->isEmpty())
            return $tickets;
        
        // it work when tickets show for purchasing
        // for multiple tickets 
        if($tickets instanceof \Illuminate\Database\Eloquent\Collection)
        {   
            // push admin taxes in every tickets
            foreach($tickets as $key => $value)
            {
                foreach($admin_tax as $ad_k => $ad_v)
                {
                    $value->taxes->push($ad_v);  
                }
            }
        }    
        else
        {   
            // it work when booking data prepare
            // for single ticket 
            foreach($admin_tax as $ad_k => $ad_v)
            {
                $tickets['taxes'] = $tickets['taxes']->push($ad_v);
            }
        }  
        
        return $tickets;
    } 

    /**
     * Login first
     * redirect back to the event page
     * after login
     */
    public function login_first()
    {
        // set event url to redirect back
        $event_url = url()->previous();
        session(['redirect_to_event'=>$event_url]);

        return redirect()->route('eventmie.login');
    }
    
    /**
     * Signup first
     * redirect back to the event page
     * after signup
     */
    public function signup_first()
    {
        // set event url to redirect back
        $event_url = url()->previous();
        session(['redirect_to_event'=>$event_url]);

        return redirect()->route('eventmie.register_show');
    }


    /**
     *  check that how much tickets can booked by customer
     */

    public function customer_limit($ticket = null, $selected_ticket = null, $booking_date = null)
    {
        $booked_tickets = Booking::where(['customer_id' => $this->customer_id, 'ticket_id' => $ticket->id, 'event_start_date'=>$booking_date ])->sum('quantity');
        
        $ticket = Ticket::where(['id' => $ticket->id])->first();
        
        if(!empty($ticket->customer_limit))
        {
            // check existing booked_ticket agains customer_limit
            $msg = __('eventmie-pro::em.already_booked');
            if($booked_tickets >= $ticket->customer_limit) {
                return ['status' => false, 'error' => $ticket->title.':-'.$msg];
            }
            
            // check selected quantity against remaining customer_limit
            // $ticket->customer_limit - $booked_tickets = remaining customer limit
            if( $selected_ticket['quantity'] > ($ticket->customer_limit - $booked_tickets)) {
                return ['status' => false, 'error' => $ticket->title.':-'.$msg];
            }
        }
    

    }

}
