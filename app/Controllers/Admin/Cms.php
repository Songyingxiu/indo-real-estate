<?php namespace App\Controllers;

use App\Models\CmsModel;

class Cms extends BaseController
{
    public function page($slug)
    {
        $cmsModel = new CmsModel();
        
        // 1. Try to find the published post in the database
        $post = $cmsModel->where('slug', $slug)
                         ->where('status', 'Published')
                         ->first();
        
        // 2. Format a fallback title (e.g., 'about-us' becomes 'About Us')
        $title = ucwords(str_replace('-', ' ', $slug));
        
        // 3. Pass the data to the view
        $data['title']     = ($post ? $post->title : $title) . ' - HuniKita';
        $data['pageTitle'] = $post ? $post->title : $title;
        $data['slug']      = $slug;
        $data['post']      = $post; // This will be null if not found in the DB
        
        return view('front/cms/page', $data);
    }
}