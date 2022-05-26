<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Classiebit\Eventmie\Models\Booking;

use Auth;

class TicketScanController extends Controller
{
    
    public function __construct()
    {
        // language change
        $this->middleware('organiser');
        $this->booking      = new Booking;
    
    }

    // ticket scan
    public function index(Request $request, $view = 'eventmie::ticket_scan.index', $extra = [])
    {
        return Eventmie::view($view, compact('extra'));
    }

    public function get_booking(Request $request)
    {
        $request->validate([
            'id'            => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'order_number'  => 'required',
        ]);

        $booking = Booking::where(['id'=>$request->id, 'order_number'=>$request->order_number])->firstOrFail();

        return response()->json(['status' => true, 'booking' => $booking ]);
    }

    // verify tikcet after scan
    public function verify_ticket(Request $request, $organiser_id = null)
    {
        $request->validate([
            'booking_id'          => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            'order_number'        => 'required',
        ]);
        
        $params = [
            'id'            => $request->booking_id,
            'order_number'  => $request->order_number,
        ];

        // so that we can pass organizer other than logged in user
        if(!$organiser_id)
            $organiser_id = Auth::id();

        // check for organizer id except for Admin
        if(!Auth::user()->hasRole('admin'))
            $params['organiser_id'] = $organiser_id;
        
        // check booking 
        // if it's organizer's booking
        // and ticket already scan or not
        $booking = $this->booking->organiser_check_booking($params);

        // ticket already scan then show error message
        if(empty($booking))
        {
            $msg = __('eventmie-pro::em.ticket').' '.__('eventmie-pro::em.not_found');
            session()->flash('error', $msg);
            return error_redirect($msg);
        }

        if($booking->status != 1) 
        {
            $msg = __('eventmie-pro::em.disabled_ticket');
            session()->flash('error', $msg);
            return error_redirect($msg);
        }

        if($booking->is_paid != 1) 
        {
            $msg = __('eventmie-pro::em.disabled_ticket');
            session()->flash('error', $msg);
            return error_redirect($msg);
        }

        if($booking->checked_in == $booking->quantity) 
        {
            $msg = __('eventmie-pro::em.already_cheked_in');
            session()->flash('error', $msg);
            return error_redirect($msg);
        }


        $data = [
            'checked_in' => $booking->checked_in + 1,
        ];

        // update checked_in by 1        
        $booking = $this->booking->organiser_edit_booking($data, $params);
        
        $url = route('eventmie.ticket_scan');
        $msg = __('eventmie-pro::em.success_cheked_in');
        
        session()->flash('status', $msg);
        return success_redirect($msg, $url);
    
    }

}    