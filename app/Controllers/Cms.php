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
        $data['post']      = $post; 
        
        return view('front/cms/page', $data);
    }

    public function faq()
    {
        $cmsModel = new CmsModel();
        
        $data['faqs'] = $cmsModel->where('category', 'FAQ')
                                 ->where('status', 'Published')
                                 ->orderBy('published_at', 'ASC')
                                 ->findAll();
                                 
        $data['title'] = 'Frequently Asked Questions - HuniKita';
        
        return view('front/cms/faq', $data);
    }
}