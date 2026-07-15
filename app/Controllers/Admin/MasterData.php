<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyTypeModel;
use App\Models\CityModel;
use App\Models\StateModel; 
use App\Models\FeatureModel;
use App\Models\SubscriptionPlanModel;
use App\Models\ZipcodeModel;

class MasterData extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $propertyTypeModel = new PropertyTypeModel();
        $cityModel = new CityModel();
        $stateModel = new StateModel(); 
        $featureModel = new FeatureModel(); 
        $planModel = new SubscriptionPlanModel();
        $zipcodeModel = new ZipcodeModel();

        $data['propertyTypes'] = $propertyTypeModel->orderBy('id', 'DESC')->paginate(5, 'types');
        
        $cityModel->select('cities.*, states.name as state_name');
        $cityModel->join('states', 'states.id = cities.state_id', 'left');
        $data['cities'] = $cityModel->orderBy('cities.id', 'DESC')->paginate(5, 'cities');

        $zipcodeModel->select('zipcodes.*, cities.name as city_name');
        $zipcodeModel->join('cities', 'cities.id = zipcodes.city_id', 'left');
        $data['zipcodes'] = $zipcodeModel->orderBy('zipcodes.id', 'DESC')->paginate(5, 'zipcodes');

        $data['states'] = $stateModel->orderBy('id', 'DESC')->paginate(5, 'states');
        $data['features'] = $featureModel->orderBy('id', 'DESC')->paginate(5, 'features');
        $data['plans'] = $planModel->orderBy('price', 'ASC')->paginate(10, 'plans');

        $data['pager'] = $propertyTypeModel->pager;

        return view('admin/master_data', $data);
    }

    // --- CREATE METHODS ---

    public function storeType() 
    { 
        $model = new PropertyTypeModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']); 
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New property type added!'); 
    }

    public function storeCity()
    {
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
        $model = new StateModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New region added successfully!');
    }

    public function storeFeature()
    {
        $model = new FeatureModel();
        $model->insert(['name' => $this->request->getPost('name'), 'status' => 'Active']);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'New feature added!');
    }

    public function storeZipcode()
    {
        $model = new ZipcodeModel();
        $model->insert([
            'city_id' => $this->request->getPost('city_id'), 
            'zipcode' => $this->request->getPost('zipcode'),
            'status'  => 'Active'
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Zipcode added!');
    }

    public function storePlan()
    {
        $model = new SubscriptionPlanModel();
        $model->insert([
            'package_code'       => strtoupper($this->request->getPost('code')),
            'name'               => $this->request->getPost('name'),
            'description'        => $this->request->getPost('description'),
            'price'              => $this->request->getPost('price'),
            'max_properties'     => $this->request->getPost('max_properties'),
            'max_agents'         => $this->request->getPost('max_agents'),
            'allow_messages'     => $this->request->getPost('allow_messages'),
            'allow_direct_email' => $this->request->getPost('direct_email_inquiry'),
            'status'             => 'Active'
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Subscription Plan created!');
    }
    
    // --- DELETE METHODS ---

    public function deleteType($id) { (new PropertyTypeModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Property type removed.'); }
    public function deleteCity($id) { (new CityModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Location removed.'); }
    public function deleteState($id) { (new StateModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Region removed.'); }
    public function deleteFeature($id) { (new FeatureModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Feature removed.'); }
    public function deletePlan($id) { (new SubscriptionPlanModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Plan removed.'); }
    public function deleteZipcode($id) { (new ZipcodeModel())->delete($id); return redirect()->to(base_url('admin/master-data'))->with('success', 'Zipcode removed.'); }

    // --- UPDATE METHODS ---

    public function updateType($id) { (new PropertyTypeModel())->update($id, ['name' => $this->request->getPost('name')]); return redirect()->to(base_url('admin/master-data'))->with('success', 'Property type updated successfully.'); }
    public function updateState($id) { (new StateModel())->update($id, ['name' => $this->request->getPost('name')]); return redirect()->to(base_url('admin/master-data'))->with('success', 'Region updated successfully.'); }
    public function updateCity($id) { 
        (new CityModel())->update($id, [
            'state_id' => $this->request->getPost('state_id'), 
            'name' => $this->request->getPost('name')
        ]); 
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Location updated successfully.'); 
    }
    public function updateZipcode($id) { 
        (new ZipcodeModel())->update($id, [
            'city_id' => $this->request->getPost('city_id'), 
            'zipcode' => $this->request->getPost('zipcode')
        ]); 
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Zipcode updated successfully.'); 
    }

    public function updatePlan($id)
    {
        $model = new SubscriptionPlanModel();
        $model->update($id, [
            'package_code'       => strtoupper($this->request->getPost('code')),
            'name'               => $this->request->getPost('name'),
            'description'        => $this->request->getPost('description'),
            'price'              => $this->request->getPost('price'),
            'max_properties'     => $this->request->getPost('max_properties'),
            'max_agents'         => $this->request->getPost('max_agents'),
            'allow_messages'     => $this->request->getPost('allow_messages'),
            'allow_direct_email' => $this->request->getPost('direct_email_inquiry')
        ]);
        return redirect()->to(base_url('admin/master-data'))->with('success', 'Subscription Plan updated!');
    }
}