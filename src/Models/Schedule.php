<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{
    protected $guarded = [];

      /**
     * The attributes that should be cast.
     *
     * @var array
     */
    
    public function create_schedule($schedules = [], $event_id = null)
    {
        // if have no event id then create event schedules
        if(!empty($event_id))
        {
            return Schedule::insert($schedules);
        }
        
        return false;
    }

    // get user event repetitive schedule
    public function get_schedule($params = [])
    {
        $result = Schedule::where([
                    'event_id' => $params['event_id'], 
                    'user_id' => $params['user_id'] 
                ])->get();

        return to_array($result);
    }

    // check event repetitive schedule that schedule exist or not exist
    public function check_schedule($params = [])
    {
        $result = Schedule::whereIn('id', $params['schedule_ids'])
                            ->where([
                                    'event_id' => $params['event_id'], 
                                    'user_id' => $params['user_id']
                            ])->get();
        
        return to_array($result);
    }

    // delete repetitive event schedule
    public function delete_schedule($params = [])
    {
        return Schedule::where(['event_id' => $params['event_id'], 'user_id' => $params['user_id']])->delete();
    }

    // update schedule 
    public function update_schedule($params = [])
    {
        // if changed date is false then update schedule table
        if(!empty($params['schedule_ids']))
        {
            foreach($params['schedule_ids'] as $key => $value)
            {
                $update_schedule =  Schedule::where('id',$value)
                                    ->where(['id' => $value, 'event_id' => $params['event_id'], 'user_id' => $params['user_id']])
                                    ->update($params['schedules'][$key]);
                if(empty($update_schedule))
                    return false;                    
            }            
            return true;    
        }
        
        return false;
    }

    // get user event repetitive schedule
    public function get_event_schedule($params = [])
    {
        $result = Schedule::where(['event_id' => $params['event_id'] ])->get();
        return to_array($result);
    }

    /**
     * ==================== particular organiser can disable own event's schdule=====================================
     */

    public function disable_event_schedules($params = [])
    {
        return Schedule::where(['event_id' => $params['event_id'], 'user_id' => $params['organiser_id']])
                        ->update(['status' => '0']);
    }
}
