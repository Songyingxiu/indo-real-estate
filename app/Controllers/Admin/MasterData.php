<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\PropertyTypeModel;
use App\Models\CityModel;

class MasterData extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $propertyTypeModel = new PropertyTypeModel();
        $cityModel = new CityModel();

        $data['propertyTypes'] = $propertyTypeModel->findAll();
        $data['cities'] = $cityModel->findAll();

        return view('admin/master_data', $data);
    }

    public function storeType()
    {
        $model = new PropertyTypeModel();
        $model->insert([
            'name'   => $this->request->getPost('name'),
            'status' => 'Active'
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New property type added!');
    }

    public function storeCity()
    {
        $model = new CityModel();
        $model->insert([
            'name'   => $this->request->getPost('name'),
            'status' => 'Active'
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New location added!');
    }
}