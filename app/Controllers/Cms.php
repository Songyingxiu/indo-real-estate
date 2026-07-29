<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CmsModel;

class Cms extends BaseController
{
    public function __construct()
    {
        // Load the text helper to enable word_limiter() in views
        helper('text');
    }

    public function page($slug)
    {
        $cmsModel = new CmsModel();
        
        $post = $cmsModel->where('slug', $slug)
                         ->where('status', 'Published')
                         ->first();
        
        $title = ucwords(str_replace('-', ' ', $slug));
        
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

    public function blog()
    {
        $cmsModel = new CmsModel();
        
        // Fetch posts that are meant for the feed, ordered by newest first
        $data['posts'] = $cmsModel->whereIn('category', ['Blog', 'Announcement', 'Tips'])
                                  ->where('status', 'Published')
                                  ->orderBy('published_at', 'DESC')
                                  ->findAll();
                                 
        $data['title'] = 'News & Updates - HuniKita';
        
        return view('front/cms/blog', $data);
    }
}