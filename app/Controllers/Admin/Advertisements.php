<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdsModel;
use Cloudinary\Cloudinary;

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
            'title_en'       => 'required|min_length[3]',
            'title_id'       => 'required|min_length[3]',
            'description_en' => 'required|min_length[10]', 
            'description_id' => 'required|min_length[10]', 
            'placement'      => 'required|in_list[home_banner,sidebar,property_list]',
            'status'         => 'required|in_list[Active,Inactive]',
        ];

        if (!$id) {
            $validationRule['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]';
        }

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $titleEN = $this->request->getPost('title_en');

        $data = [
            'title'          => $titleEN, // fallback for legacy queries
            'title_en'       => $titleEN,
            'title_id'       => $this->request->getPost('title_id'),
            'description'    => $this->request->getPost('description_en'), // fallback
            'description_en' => $this->request->getPost('description_en'), 
            'description_id' => $this->request->getPost('description_id'), 
            'placement'      => $this->request->getPost('placement'),
            'status'         => $this->request->getPost('status'),
            'start_date'     => $this->request->getPost('start_date') ?: null,
            'end_date'       => $this->request->getPost('end_date') ?: null,
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            
            // Upload to Cloudinary
            $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
            if (empty($cloudinaryUrl)) {
                return redirect()->back()->withInput()->with('error', 'Cloudinary configuration is missing.');
            }
            
            $cloudinary = new Cloudinary($cloudinaryUrl);
            $response = $cloudinary->uploadApi()->upload($image->getTempName(), [
                'folder' => 'hunikita_ads',
            ]);
            
            // Save the secure URL directly to the database
            $data['image_path'] = $response['secure_url'];
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
            $adsModel->delete($id);
            return redirect()->to(base_url('admin/advertisements'))->with('success', 'Advertisement deleted.');
        }

        return redirect()->to(base_url('admin/advertisements'))->with('error', 'Advertisement not found.');
    }
}