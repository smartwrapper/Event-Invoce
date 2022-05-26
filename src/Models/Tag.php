<?php

namespace Classiebit\Eventmie\Models;

use Auth;
use DB;
use Classiebit\Eventmie\Models\Event;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    // get particular event tags
    public function get_event_tags($event_id = null)
    {
        $tags_id_temp    = DB::table('event_tag')->select('tag_id')->where('event_id', $event_id)->get();
        $tags_id_temp    = to_array($tags_id_temp);

        $tags_id         = [];
        
        if(empty($tags_id_temp))
            return  false;
        
        foreach($tags_id_temp as $key => $value)
        {
            $tags_id[$key]  = $value->tag_id;
        }
        
        $result = Tag::whereIn('id', $tags_id)->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        return to_array($result);
    }

    // add tags
    public function add_tags($params = [], $tag_id = null)
    {
        return Tag::updateOrCreate(
                    
                    ['id' => $tag_id],
                    $params
                );
    }

    // delete tags
    public function delete_tags($tag_id = null)
    {   
        return Tag::where(['id' => $tag_id])->delete();
    }

    // total tags
    public function total_tags()
    {
        return Tag::where(['status' => 1])->count();
    }

    // get only one tag on tag id
    public function get_tag($tag_id = null)
    {
        return Tag::where('id', $tag_id)->first();
    }

    // get only one tag by title
    public function get_tag_by_title($tag_title = null)
    {
        return Tag::where('title', $tag_title)->where('status', 1)->first();
    }

    
}
