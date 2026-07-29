<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PoiModel;

class Poi extends BaseController
{
    protected $poiModel;

    public function __construct()
    {
        $this->poiModel = new PoiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master POI Management',
            'pois'  => $this->poiModel->orderBy('created_date', 'DESC')->findAll()
        ];

        return view('admin/poi/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Add New POI'];
        return view('admin/poi/form', $data);
    }

    public function store()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[255]',
            'category'  => 'required|in_list[School,Station,Hospital,Mall,Other]',
            'latitude'  => 'required|decimal',
            'longitude' => 'required|decimal',
            'status'    => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->poiModel->save([
            'name'      => $this->request->getPost('name'),
            'category'  => $this->request->getPost('category'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'status'    => $this->request->getPost('status')
        ]);

        return redirect()->to('admin/poi')->with('message', 'Point of Interest added successfully.');
    }

    public function edit($id)
    {
        $poi = $this->poiModel->find($id);

        if (!$poi) {
            return redirect()->to('admin/poi')->with('error', 'POI not found.');
        }

        $data = [
            'title' => 'Edit POI',
            'poi'   => $poi
        ];

        return view('admin/poi/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[255]',
            'category'  => 'required|in_list[School,Station,Hospital,Mall,Other]',
            'latitude'  => 'required|decimal',
            'longitude' => 'required|decimal',
            'status'    => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->poiModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'category'  => $this->request->getPost('category'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'status'    => $this->request->getPost('status')
        ]);

        return redirect()->to('admin/poi')->with('message', 'Point of Interest updated successfully.');
    }

    public function delete($id)
    {
        if ($this->poiModel->delete($id)) {
            return redirect()->to('admin/poi')->with('message', 'POI deleted successfully.');
        }
        
        return redirect()->to('admin/poi')->with('error', 'Failed to delete POI.');
    }
}