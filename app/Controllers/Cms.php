<?php namespace App\Controllers;

class Cms extends BaseController
{
    public function page($slug)
    {
        $title = ucwords(str_replace('-', ' ', $slug));
        
        $data['title'] = $title . ' - HuniKita';
        $data['slug']  = $slug;
        $data['pageTitle'] = $title;
        
        return view('front/cms/page', $data);
    }
}