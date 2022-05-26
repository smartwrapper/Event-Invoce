<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Composer\DependencyResolver\Request;
use Classiebit\Eventmie\Models\Tax;
use Classiebit\Eventmie\Models\Booking;

class Ticket extends Model
{
    // include
    protected $guarded = [];

   // create and update tickets
    public function add_tickets($params = [], $ticket_id = null)
    {
        // if ticket_id exist then update otherwise create
        return Ticket::updateOrCreate(
            ['id'=> $ticket_id],      
            $params
        );
    
    }

    /**
     * Get only one event's tickets  when param is emtpy 
      */
    public function get_event_tickets($params = [])
    {
        
        if(!empty($params['ticket_ids']))
        {
            $result = Ticket::with(['taxes'])
                    ->whereIn('id', $params['ticket_ids'])
                    ->where('event_id', $params['event_id'])
                    ->orderBy('price')
                    ->get();
        }
        else
        {
            $result = Ticket::with(['taxes'])->where(['event_id' => $params['event_id'] ])
                ->orderBy('price')
                ->get();
        }
            
        return $result;
    }

    /**
     * Get only one ticket 
      */
    public function get_ticket($params = [])
    {
        return Ticket::with(['taxes'])
                ->where('id', $params['ticket_id'])
                ->first();
                   
    }

    // delete tickets
    public function delete_tickets($ticket_id = null)
    {   
        return Ticket::where(['id' => $ticket_id])->delete();
    }

    // get tickets for multiple events with related event_id for events listing with two tickets
    public function get_events_tickets($event_ids = [])
    {
        $result = Ticket::whereIn('event_id',$event_ids)->orderBy('price')->get();
        return to_array($result);
    }

    // check free tickets with related event id
    public function check_free_tickets($event_id = null)
    {
        $result = Ticket::where('price',"0")->where('event_id', $event_id)->get();
        return to_array($result);
    }

    /**
     * The taxes that belong to the tickes.
     */
    public function taxes()
    {
        return $this->belongsToMany(Tax::class);
    }

    // update to multiple-taxes when upgrading to v1.4.x
    // run this only if tax_id column exists
    public static function update_to_multiple_taxes()
    {
        $tickets      = Ticket::whereNotNull('tax_id')->get();
        $tax_ticket = [];
        if($tickets->isNotEmpty())
        {
            foreach($tickets as $key => $value)
            {   
                if(!empty($value->tax_id))
                {
                    $tax_ticket[$key] = [
                        'ticket_id' => $value->id,
                        'tax_id' => $value->tax_id,
                    ];
                }
            }
        }    

        // copy tax_id to tax_ticket table
        if(!empty($tax_ticket))
        {
            DB::table('tax_ticket')->insertOrIgnore($tax_ticket);
        }

    }

    // get event's booked tickets
    public function get_booked_tickets($ticket_ids = [])
    {
        return DB::table("tickets")
                ->whereIn("id", $ticket_ids)
                ->get();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    

}
