<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdsModel;

class Advertisements extends BaseController
{
    public function index()
    {
        $adsModel = new AdsModel();
        $data['advertisements'] = $adsModel->orderBy('created_at', 'DESC')->findAll();
        $data['title'] = 'Advertisement Management - HuniKita';
        
        return view('admin/advertisements/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Advertisement - HuniKita';
        return view('admin/advertisements/form', $data);
    }

    public function edit($id)
    {
        $adsModel = new AdsModel();
        $data['ad'] = $adsModel->find($id);
        
        if (!$data['ad']) {
            return redirect()->to(base_url('admin/advertisements'))->with('error', 'Advertisement not found.');
        }

        $data['title'] = 'Edit Advertisement - HuniKita';
        return view('admin/advertisements/form', $data);
    }

    public function save()
    {
        $adsModel = new \App\Models\AdsModel();
        $id = $this->request->getPost('id');

        $validationRule = [
            'title'      => 'required|min_length[3]',
            'placement'  => 'required|in_list[home_banner,sidebar,property_list]',
            'status'     => 'required|in_list[Active,Inactive]',
        ];

        if (!$id) {
            $validationRule['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]';
        }

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title'      => $this->request->getPost('title'),
            'target_url' => $this->request->getPost('target_url'),
            'placement'  => $this->request->getPost('placement'),
            'status'     => $this->request->getPost('status'),
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date'   => $this->request->getPost('end_date') ?: null,
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/ads';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $image->getRandomName();
            $image->move($uploadPath, $newName);
            $data['image_path'] = 'uploads/ads/' . $newName;
        }

        if ($id) {
            $adsModel->update($id, $data);
            $message = 'Advertisement updated successfully.';
        } else {
            $adsModel->insert($data);
            $message = 'Advertisement created successfully.';
        }

        return redirect()->to(base_url('admin/advertisements'))->with('success', $message);
    }
    public function delete($id)
    {
        $adsModel = new AdsModel();
        $ad = $adsModel->find($id);
        
        if ($ad) {
            // Optionally delete the physical image file here
            if (file_exists(FCPATH . $ad->image_path)) {
                unlink(FCPATH . $ad->image_path);
            }
            $adsModel->delete($id);
            return redirect()->to(base_url('admin/advertisements'))->with('success', 'Advertisement deleted.');
        }

        return redirect()->to(base_url('admin/advertisements'))->with('error', 'Advertisement not found.');
    }
}