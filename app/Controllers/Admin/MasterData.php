<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\PropertyTypeModel;
use App\Models\CityModel;
use App\Models\StateModel; 
use App\Models\FeatureModel;

class MasterData extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $propertyTypeModel = new PropertyTypeModel();
        $cityModel = new CityModel();
        $stateModel = new StateModel(); 
        $featureModel = new FeatureModel(); 

        $data['propertyTypes'] = $propertyTypeModel->orderBy('id', 'DESC')->paginate(5, 'types');
        
        $cityModel->select('cities.*, states.name as state_name');
        $cityModel->join('states', 'states.id = cities.state_id', 'left');
        $data['cities'] = $cityModel->orderBy('cities.id', 'DESC')->paginate(5, 'cities');

        $data['states'] = $stateModel->orderBy('id', 'DESC')->paginate(5, 'states');
        
        $data['features'] = $featureModel->orderBy('id', 'DESC')->paginate(5, 'features');

        $data['pager'] = $propertyTypeModel->pager;

        return view('admin/master_data', $data);
    }

    // --- CREATE METHODS ---

    public function storeType()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new PropertyTypeModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New property type added!');
    }

    public function storeCity()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new CityModel();
        $model->insert([
            'state_id' => $this->request->getPost('state_id'), 
            'name'     => $this->request->getPost('name'),
            'status'   => 'Active'
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New location added!');
    }

    public function storeState()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new StateModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New region added successfully!');
    }

    public function storeFeature()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new FeatureModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New feature added!');
    }
    
    // --- DELETE METHODS ---

    public function deleteType($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new PropertyTypeModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Property type removed.');
    }

    public function deleteCity($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new CityModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Location removed.');
    }

    public function deleteState($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new StateModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Region removed.');
    }

    public function deleteFeature($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new FeatureModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Feature removed.');
    }

    // --- UPDATE METHODS ---
    public function updateType($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new PropertyTypeModel();
        $model->update($id, ['name' => $this->request->getPost('name')]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Property type updated successfully.');
    }

    public function updateState($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new StateModel();
        $model->update($id, ['name' => $this->request->getPost('name')]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Region updated successfully.');
    }

    public function updateCity($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        $model = new CityModel();
        $model->update($id, [
            'state_id' => $this->request->getPost('state_id'),
            'name'     => $this->request->getPost('name')
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Location updated successfully.');
    }
}