<?php

namespace Classiebit\Eventmie\Http\Controllers;
use App\Http\Controllers\Controller; 
use Facades\Classiebit\Eventmie\Eventmie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Classiebit\Eventmie\Models\Post;

class BlogsController extends Controller
{
    
    public function __construct()
    {
        // language change
        $this->middleware('common');
    
        $this->post   = new Post;

    }

    // particulara post view
    public function view(Request $request, $slug = null,  $view = 'eventmie::blogs.show', $extra = [])
    {
        $post  = $this->post->view($request->segment(2));

        return Eventmie::view($view, compact('post', 'extra'));
    }

    // particulara post view
    public function get_posts(Request $request, $view = 'eventmie::blogs.index', $extra = [])
    {
        $posts  = $this->post->get_posts();
        
        $links = $posts->appends(['sort' => 'id'])->links();
        
        return Eventmie::view($view, compact('posts', 'extra'));
    }
}    