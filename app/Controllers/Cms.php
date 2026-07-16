<?php namespace App\Controllers;

use App\Models\CmsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Cms extends BaseController
{
    public function page($slug)
    {
        $cmsModel = new CmsModel();
        
        // Find the published post matching the slug
        $post = $cmsModel->where('slug', $slug)
                         ->where('status', 'Published')
                         ->first();
        
        if (!$post) {
            throw PageNotFoundException::forPageNotFound("The page '{$slug}' could not be located on our servers.");
        }
        
        $data['title']     = $post->title . ' - HuniKita';
        $data['slug']      = $slug;
        $data['post']      = $post;
        
        return view('front/cms/page', $data);
    }
}