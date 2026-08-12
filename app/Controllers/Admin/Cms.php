<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CmsModel;

class Cms extends BaseController
{
    public function index()
    {
        $cmsModel = new CmsModel();
        $data['posts'] = $cmsModel->orderBy('created_at', 'DESC')->findAll();
        
        return view('admin/cms/cms', $data);
    }

    public function savePost()
    {
        $cmsModel = new CmsModel();
        
        $id = $this->request->getPost('id');
        $titleEN = $this->request->getPost('title_en');
        
        $data = [
            'title'           => $titleEN, // Fallback
            'title_en'        => $titleEN,
            'title_id'        => $this->request->getPost('title_id'),
            'slug'            => url_title(strtolower($titleEN), '-', true),
            'category'        => $this->request->getPost('category'),
            'content_body'    => $this->request->getPost('content_body_en'), // Fallback
            'content_body_en' => $this->request->getPost('content_body_en'),
            'content_body_id' => $this->request->getPost('content_body_id'),
            'status'          => 'Published',
            'author_id'       => session()->get('id')
        ];

        if ($id) {
            $cmsModel->update($id, $data);
            $message = 'Content updated successfully.';
        } else {
            $data['published_at'] = date('Y-m-d H:i:s');
            $cmsModel->insert($data);
            $message = 'Content published successfully.';
        }

        return redirect()->to('admin/cms')->with('success', $message);
    }

    public function delete($id)
    {
        $cmsModel = new CmsModel();
        
        $post = $cmsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/cms')->with('error', 'Content not found.');
        }

        $cmsModel->delete($id);
        
        return redirect()->to('admin/cms')->with('success', 'Content deleted successfully.');
    }
}