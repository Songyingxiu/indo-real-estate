<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsModel;

class Cms extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $cmsModel = new CmsModel();
        // Fetch all pages and blog posts from the database, newest first
        $data['posts'] = $cmsModel->orderBy('created_at', 'DESC')->findAll();
        
        return view('admin/cms/cms', $data);
    }

    public function savePost() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $cmsModel = new CmsModel();
        $id = $this->request->getPost('id');
        $title = $this->request->getPost('title');
        
        $data = [
            'title'        => $title,
            'slug'         => strtolower(url_title($title)),
            'category'     => $this->request->getPost('category'),
            'content_body' => $this->request->getPost('content_body'),
            'author_id'    => session()->get('id') ?? session()->get('user_id'),
            'status'       => 'Published',
            'published_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($id)) {
            $cmsModel->update($id, $data);
            $message = 'Content item updated successfully!';
        } else {
            $cmsModel->insert($data);
            $message = 'New content published successfully!';
        }
        
        return redirect()->to(base_url('admin/cms'))->with('success', $message);
    }
}