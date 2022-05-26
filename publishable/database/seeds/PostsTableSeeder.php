<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Post;

class PostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $post = $this->post('id', 1);
        if (!$post->exists) 
        {
            $post->fill([
                'author_id' => 1,
                'category_id' => 1,
                'title' => 'How Eventmie Works For Organisers',
                'seo_title' => 'How Eventmie Works For Organisers',
                'excerpt' => 'Post demo content',
                'body' => '<p><strong>Post demo content</strong>',
                'image' => 'posts/September2019/fTER87e1L3Oz3jVk5hBm.jpg',
                'slug' => 'how-eventmie-works-for-organisers',
                'meta_description' => 'Post demo content',
                'meta_keywords' => NULL,
                'status' => 'PUBLISHED',
                'featured' => 0,
            ])->save();
        }
        
        $post = $this->post('id', 2);
        if (!$post->exists) 
        {
            $post->fill([
                'author_id' => 1,
                'category_id' => 1,
                'title' => 'How Eventmie Works For Customers',
                'seo_title' => 'How Eventmie Works For Customers',
                'excerpt' => 'Post demo content',
                'body' => '<p><strong>Post demo content</strong>',
                'image' => 'posts/September2019/yfPw86UOUDYc4WDgUCrG.jpg',
                'slug' => 'how-eventmie-works-for-customers',
                'meta_description' => 'Post demo content',
                'meta_keywords' => NULL,
                'status' => 'PUBLISHED',
                'featured' => 0,
            ])->save();
        }
        
        $post = $this->post('id', 3);
        if (!$post->exists) 
        {
            $post->fill([
                'author_id' => 1,
                'category_id' => 1,
                'title' => 'How Eventmie Works As Multi-Vendor',
                'seo_title' => 'How Eventmie Works As Multi-Vendor',
                'excerpt' => 'Post demo content',
                'body' => '<p><strong>Post demo content</strong>',
                'image' => 'posts/September2019/zU68cPYMfcWlVD7bKIrB.jpg',
                'slug' => 'how-eventmie-works-as-multi-vendor',
                'meta_description' => 'Post demo content',
                'meta_keywords' => NULL,
                'status' => 'PUBLISHED',
                'featured' => 0,
            ])->save();
        }
    
    }

    protected function post($field, $for)
    {
        return Post::firstOrNew([$field => $for]);
    }
}