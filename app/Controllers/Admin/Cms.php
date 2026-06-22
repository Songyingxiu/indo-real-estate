<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsModel;

class Cms extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $cmsModel = new CmsModel();
        // Fetch all blog posts from the database, newest first
        $data['posts'] = $cmsModel->orderBy('created_at', 'DESC')->findAll();
        
        return view('admin/cms/cms', $data);
    }

    public function savePost() {
        $cmsModel = new CmsModel();
        
        $title = $this->request->getPost('title');
        
        $data = [
            'title'        => $title,
            'slug'         => strtolower(url_title($title)),
            'category'     => 'Blog',
            'content_body' => $this->request->getPost('content_body'),
            'author_id'    => session()->get('user_id'),
            'status'       => 'Published',
            'published_at' => date('Y-m-d H:i:s')
        ];
        
        $cmsModel->insert($data);
        return redirect()->to(base_url('admin/cms'))->with('success', 'Blog post published successfully!');
    }
}