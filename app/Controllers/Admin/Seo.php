<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SeoModel;

class Seo extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $seoModel = new SeoModel();
        // Fetch the Global 'Homepage' SEO settings from the database
        $data['seo'] = $seoModel->where('target_page', 'Homepage')->first();
        
        return view('admin/seo', $data);
    }

    public function saveSettings() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $seoModel = new SeoModel();
        
        $data = [
            'target_page'      => 'Homepage',
            'meta_title'       => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'focus_keywords'   => $this->request->getPost('focus_keywords')
        ];

        $existing = $seoModel->where('target_page', 'Homepage')->first();
        
        if ($existing) {
            $seoModel->update($existing['id'], $data);
        } else {
            $seoModel->insert($data);
        }

        return redirect()->to(base_url('admin/seo'))->with('success', 'SEO Settings updated successfully!');
    }
}