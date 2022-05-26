<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends \TCG\Voyager\Models\Post
{
    protected $guarded = [];
    protected $translatable = [];

    // posts with limit for welcome page
    public function index()
    {
        $result = Post::where(['status' => 'PUBLISHED'])
                    ->limit(3)->orderBy('updated_at', 'DESC')->get();

        return to_array($result);

    }

    // particular post view
    public function view($slug = null)
    {
        return Post::where(['slug' => $slug])->first();
        
    }

    // get posts
    public function get_posts()
    {
        return Post::where(['status' => 'PUBLISHED'])->orderBy('updated_at', 'DESC')->paginate(9);
    }

}    