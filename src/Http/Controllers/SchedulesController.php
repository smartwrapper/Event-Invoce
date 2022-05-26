<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Auth;


use Classiebit\Eventmie\Models\Event;
use Classiebit\Eventmie\Models\Schedule;
use Illuminate\Support\Carbon;
use DB;
use Doctrine\DBAL\Types\ObjectType;

class SchedulesController extends Controller
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
    
        $this->schedule     = new Schedule;
        $this->event        = new Event;
        $this->organiser_id = null;   
    }

    // get event's schedules while creating event
    // GET SHEDULE OF LOGING USER AND BASE ON EVENT ID 
    public function schedules(Request $request)
    {   
        // if logged in user is admin
        $this->is_admin($request);

        $request->validate([
            'event_id'          => 'required',
        ]);

        $event    = $this->event->get_user_event($request->event_id, $this->organiser_id);

        if(empty($event))
        {
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        }

        $params      = [
            'event_id'  => $request->event_id,
            'user_id'   => $this->organiser_id,
        ];
        $schedules   = $this->schedule->get_schedule($params);
           
        if(empty($schedules))
        {
            return error(__('eventmie-pro::em.schedule').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        }
        
        if($schedules[0]['repetitive_type'] == 1 || $schedules[0]['repetitive_type'] == 3)
            $schedules    = $this->prepare_schedules($request, $schedules);
        
        return response()->json(['status' => true, 'schedules' => $schedules]);
    }


    // get event's schedules while showing event
    // GET SCHEDULE  CLIENT  WTIHOUT LOGIN USER ID SHEDULE BASE ON EVENT ID
    public function event_schedule(Request $request)
    {   
        $request->validate([
            'event_id'          => 'required',
        ]);

        if(empty($request->event_id))
        {
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        }

        $params      = [
            'event_id'  => $request->event_id,
        ];

        $schedules   = $this->schedule->get_event_schedule($params);
        
        if(empty($schedules))
        {
            return error(__('eventmie-pro::em.event').' '.__('eventmie-pro::em.not_found'), Response::HTTP_BAD_REQUEST );
        }
        
        if($schedules[0]['repetitive_type'] == 1 || $schedules[0]['repetitive_type'] == 3)
            $schedules    = $this->prepare_schedules($request, $schedules);
        
        
        return response()->json(['status' => true, 'schedules' => $schedules]);
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
     *  prepare schedules
     */

    protected function prepare_schedules(Request $request, array $schedules = []) : array
    {
        $serverside_dates = DB::table('serverside_dates')->where(['event_id' => $request->event_id])->get()->keyBy('from_date');
        
        if($serverside_dates->isEmpty())
        {
            foreach($schedules as $key => $schedule)
            {
                if(!empty($schedule['repetitive_dates']))
                {
                    $s = (array)$schedule['repetitive_dates'];
                    
                   $schedules[$key]['repetitive_dates'] = json_encode(array_values($s));
                }   

                else
                   $schedules[$key]['repetitive_dates'] = json_encode([]);
            }
            
            return $schedules;
        }    

        $event    = Event::where(['id' =>  $request->event_id])->first();

        $start_date     = userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'Y-m-d');
        $end_date       = userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', 'Y-m-d');
        

        $repetitive_type  = $schedules[0]['repetitive_type']; 

        $user_schedules           = [];
        $schedules_dates          = [];

        $from_time = [];
        $to_time   = [];

        foreach($schedules as $key => $value)
        {
            $repetitive_dates = $value['repetitive_type'] == 2 ? json_decode($value['repetitive_days']) : json_decode($value['repetitive_dates']); 

            $server_dates = [];
            
            if(!empty($repetitive_dates))
                $server_dates =   explode(",", $repetitive_dates);
            
            $dates   = [];
            
            $count_days       = \Carbon\Carbon::create($value['from_date'])->daysInMonth;
            
            for($i = 1; $i <= $count_days; $i++ )
            {
                $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);;
            }

        
            $month = \Carbon\Carbon::createFromFormat('Y-m-d', $value['from_date'])->format('Y-m');
            
            if(!empty($server_dates))
            {
                // if($value['repetitive_type'] === 1)
                // {
                //     $server_dates  = array_diff($dates, $server_dates);
                // }

                $server_dates = json_decode($serverside_dates[$month.'-'.'01']->dates);
                   
                foreach($server_dates as $key1 => $value1)
                {
                    $date =    userTimezone($value1, 'Y-m-d H:i:s', 'Y-m-d H:i:s');
                
                    $from_time[\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m')][] =    userTimezone($value['from_time'], 'H:i:s', 'H:i:s');
                    
                    $to_time[\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m')][]   =    userTimezone($value['to_time'], 'H:i:s', 'H:i:s');

                    $schedules_dates[\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m')][] = $date;

                }
                
                foreach($schedules_dates as $key1 => $d)
                {
                    $first_day_month   = new \DateTime($key1);
                    $last_day_month  = new \DateTime($key1);
                    

                    $count_days       = \Carbon\Carbon::create($key1)->daysInMonth;
                    $dates            = [];

                    for($i = 1; $i <= $count_days; $i++ )
                    {
                        $dates[] = str_pad($i, 2, '0', STR_PAD_LEFT);;
                    }
                    
                    $excepted_dates = [];

                    foreach($d as $key2 => $date)
                    {
                        $excepted_dates[]  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d');
                    }    
                    if($value['repetitive_type'] == 1)
                    {
                        $excepted_dates  = array_diff($dates, $excepted_dates);
                    }
                    
                    
                    $user_schedules[$key1]['event_id']             = $request->event_id;
                    $user_schedules[$key1]['user_id']              = $event->user_id;
                    $user_schedules[$key1]['repetitive_type']      = $repetitive_type;
                    
                    $user_schedules[$key1]['from_time']            = ($value['from_time'] == null) ? null : $from_time[$key1][count($from_time[$key1]) -1 ];
                    $user_schedules[$key1]['to_time']              = ($value['to_time'] == null) ? null : $to_time[$key1][count($to_time[$key1]) -1 ];
                    // generate from start_date and end_date
                    $user_schedules[$key1]['from_date']            = $first_day_month->modify('first day of this month')->format('Y-m-d');;
                    $user_schedules[$key1]['to_date']              = $last_day_month->modify('last day of this month')->format('Y-m-d');
                    

                    if($value['repetitive_type'] == 1 || $value['repetitive_type'] == 3)
                    {
                        $user_schedules[$key1]['repetitive_dates'] = json_encode(array_values($excepted_dates));
                        $user_schedules[$key1]['repetitive_days']      = null;
                    }
                    else
                    {
                        $user_schedules[$key1]['repetitive_days'] = json_encode(array_values($excepted_dates));
                        $user_schedules[$key1]['repetitive_dates']      = null;
                    }
                    
                }
            }
            else
            {
                $first_day_month   = new \DateTime($month);
                $last_day_month    = new \DateTime($month);

                $user_schedules[$month]['event_id']             = $request->event_id;
                $user_schedules[$month]['user_id']              = $event->user_id;
                $user_schedules[$month]['repetitive_type']      = $repetitive_type;
                
                $user_schedules[$month]['from_time']            = null;
                $user_schedules[$month]['to_time']              = null;
                // generate from start_date and end_date
                $user_schedules[$month]['from_date']            = $first_day_month->modify('first day of this month')->format('Y-m-d');;
                $user_schedules[$month]['to_date']              = $last_day_month->modify('last day of this month')->format('Y-m-d');
                

                if($value['repetitive_type'] == 1 || $value['repetitive_type'] == 3)
                {
                    $user_schedules[$month]['repetitive_dates'] = json_encode([]);
                    $user_schedules[$month]['repetitive_days']      = null;
                }
                else
                {
                    $user_schedules[$month]['repetitive_days'] = json_encode([]);
                    $user_schedules[$month]['repetitive_dates']      = null;
                }
            }
      
        }
           
        $temp_schedules = [];

        
        $i = 0;
        
        foreach($user_schedules as $key => $schedule)
        {
            if(!empty($schedules[$i]))
                $schedule['id'] = $schedules[$i]['id'];
            else
                $schedule['id'] = random_int(100,1000);
            
            $temp_schedules[] = $schedule;
            $i++;
        }

        $schedules =  collect($temp_schedules)->sortBy([
            ['from_date', 'asc'],
        ]);

        
        $missing_schedules = $this->add_missing_schedules($request, $repetitive_type, $schedules);

        if(!empty($missing_schedules));
        {
            $schedules = $schedules->merge($missing_schedules);
        }
        
        $start_date     = userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'Y-m');
        $end_date       = userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', 'Y-m');
        
        $first_day_month   = new \DateTime($start_date);
        $last_day_month    = new \DateTime($end_date);

        $start_date     = $first_day_month->modify('first day of this month')->format('Y-m-d');;
        $end_date       = $last_day_month->modify('last day of this month')->format('Y-m-d');
        
        $schedules = $schedules->sortBy([
            ['from_date', 'asc'],
        ])->whereBetween('from_date', [$start_date, $end_date])->all();
            

        $refresh_index = []; 
        //refresh index
        foreach($schedules as $key => $value)
        {
            $refresh_index[] = $value;
        }
        
        $schedules = $refresh_index;
        
        return $schedules;

    }

    /**
     *  missing schedule
     */

    protected function add_missing_schedules(Request $request, $repetitive_type = null, $schedules = [])
    {
        $schedules = (object)$schedules;

        $missing_schedules = [];

        $event    = Event::where(['id' =>  $request->event_id])->first();

        $start_date     = userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'Y-m-d');
        $end_date       = userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', 'Y-m-d');
        
        $start_date     = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->modify('first day of this month');
        $end_date       = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->modify('last day of this month');

        // get months name between two dates
        $month_names_temp = \Carbon\CarbonPeriod::create($start_date, '1 month', $end_date);
        

        $month_names     = [];
        $from_date       = [];
        
        
        foreach ($month_names_temp as $key => $value) 
        {
            $from_date[$key]['from_date'] = $value->format("Y-m-d") ;
        }

        $from_date      = collect($from_date);
        $temp_schedules = $schedules->keyBy('from_date');
        
        
        $missing_schedules = [];
        
        foreach($from_date as $key => $item)
        {
            $month = Carbon::createFromFormat('Y-m-d', $item['from_date'])->format('Y-m-d');

            $first_day_month   = new \DateTime($month);
            $last_day_month    = new \DateTime($month);

            if(!$temp_schedules->has($month) && $month != $schedules[$schedules->count() - 1]['from_date'] )
            {
                $missing_schedules[$key]['event_id']             = $request->event_id;
                $missing_schedules[$key]['user_id']              = $event->user_id;
                $missing_schedules[$key]['repetitive_type']      = $repetitive_type;
                
                $missing_schedules[$key]['from_time']            = null;
                $missing_schedules[$key]['to_time']              = null;
                // generate from start_date and end_date
                $missing_schedules[$key]['from_date']            = $first_day_month->modify('first day of this month')->format('Y-m-d');;
                $missing_schedules[$key]['to_date']              = $last_day_month->modify('last day of this month')->format('Y-m-d');
                $missing_schedules[$key]['repetitive_dates']     = json_encode([]);
                $missing_schedules[$key]['id'] = random_int(100,1000);

            }

        };

        return $missing_schedules;
    }


}