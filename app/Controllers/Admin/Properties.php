<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyTypeModel; 
use App\Models\StateModel;
use App\Models\CityModel; 
use App\Models\FeatureModel;
use App\Models\PropertyFeatureModel;
use App\Models\PropertyVerificationModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\PoiModel;
use Cloudinary\Cloudinary; 

class Properties extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $roleId = session()->get('role_id');
        $userId = session()->get('user_id');

        if ($roleId == 4) {
            $data['properties'] = $propertyModel->findAll();
        } else {
            $data['properties'] = $propertyModel->where('owner_id', $userId)->findAll();
        }

        return view('admin/properties/properties', $data); 
    }

    public function create()
    {
        $propertyTypeModel = new PropertyTypeModel();
        $stateModel = new StateModel();
        $featureModel = new FeatureModel(); 
        
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        $poiModel = new PoiModel();
        
        $userId = session()->get('user_id');
        $roleId = session()->get('role_id');

        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        
        $maxPois = 0;
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? 0) : ($plan->max_pois ?? 0);
            }
        }

        if ($roleId == 4) {
            $maxPois = 9999;
        }

        $poisCreated = $poiModel->where('added_by', $userId)->countAllResults();

        $data['maxPois']     = $maxPois;
        $data['poisCreated'] = $poisCreated;
        
        $data['pois'] = $poiModel->where('status', 'Active')->findAll();
        $data['propertyTypes'] = $propertyTypeModel->findAll();
        $data['states'] = $stateModel->where('status', 'Active')->findAll();
        $data['features'] = $featureModel->where('status', 'Active')->findAll();
        
        return view('admin/properties/create', $data);
    }

    public function store()
    {
        $rules = [
            'title'            => 'required|min_length[5]|max_length[255]',
            'property_type_id' => 'required|numeric',
            'state_id'         => 'required|numeric',
            'city_id'          => 'required|numeric',
            'tax_price'        => 'required|numeric',
            'address_line_1'   => 'required',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'property_images'  => 'uploaded[property_images]|is_image[property_images]',
            'shm_document'     => 'uploaded[shm_document]|ext_in[shm_document,pdf,jpg,jpeg,png]|max_size[shm_document,5120]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
        if (empty($cloudinaryUrl)) {
            return redirect()->back()->withInput()->with('error', 'Cloudinary configuration is missing.');
        }
        $cloudinary = new Cloudinary($cloudinaryUrl);

        $propertyModel = new PropertyModel();
        $imageModel = new PropertyImageModel();
        $propertyFeatureModel = new PropertyFeatureModel(); 
        
        $data = [
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), 
            'city_id'          => $this->request->getPost('city_id'),  
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $this->request->getPost('bed'),
            'bath'             => $this->request->getPost('bath'),
            'total_land_area'  => $this->request->getPost('total_land_area'),
            'usable_area'      => $this->request->getPost('usable_area'),
            'total_area'       => $this->request->getPost('total_area') ?? $this->request->getPost('total_land_area'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'area_name'        => $this->request->getPost('area_name'), 
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'owner_id'         => session()->get('user_id'),
            'approval_status'  => 'Draft',
            'status'           => 'Active',
        ];

        $propertyModel->insert($data);
        $propertyId = $propertyModel->getInsertID();

        $selectedFeatures = $this->request->getPost('features');
        if (!empty($selectedFeatures) && is_array($selectedFeatures)) {
            foreach ($selectedFeatures as $featureId) {
                $propertyFeatureModel->builder()->insert([
                    'property_id' => $propertyId,
                    'feature_id'  => $featureId,
                    'status'      => 'Active'
                ]);
            }
        }

        if ($imagefile = $this->request->getFiles()) {
            if (array_key_exists('property_images', $imagefile)) {
                $isFirst = true;
                foreach ($imagefile['property_images'] as $img) {
                    if ($img->isValid() && ! $img->hasMoved()) {
                        $response = $cloudinary->uploadApi()->upload($img->getTempName(), [
                            'folder' => 'hunikita_properties',
                        ]);
                        
                        $imageModel->builder()->insert([
                            'property_id' => $propertyId,
                            'image_path'  => $response['secure_url'], 
                            'is_primary'  => $isFirst ? 1 : 0 
                        ]);
                        $isFirst = false;
                    }
                }
            }
        }

        $shmFile = $this->request->getFile('shm_document');
        if ($shmFile && $shmFile->isValid() && ! $shmFile->hasMoved()) {
            $response = $cloudinary->uploadApi()->upload($shmFile->getTempName(), [
                'folder' => 'hunikita_documents',
            ]);

            $propVerifyModel = new PropertyVerificationModel();
            $propVerifyModel->builder()->insert([
                'property_id' => $propertyId,
                'ownership_certificate' => $response['secure_url'], 
                'approval_status' => 'Pending Verification',
                'status' => 'Active'
            ]);
        }

        return redirect()->to(base_url('admin/properties'))->with('success', 'Property saved! SHM document sent to verification center.');
    }

    public function edit($id)
    {
        $propertyModel = new PropertyModel();
        
        $propertyData = $propertyModel->find($id);
        if (!$propertyData) {
            return redirect()->to(base_url('admin/properties'))->with('error', 'Property not found.');
        }
        
        $property = is_object($propertyData) ? (array) $propertyData : $propertyData;

        if (session()->get('role_id') != 4 && $property['owner_id'] != session()->get('user_id')) {
            return redirect()->to(base_url('admin/properties'))->with('error', 'Unauthorized access.');
        }

        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        $poiModel = new PoiModel();
        
        $userId = session()->get('user_id');
        $roleId = session()->get('role_id');

        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        
        $maxPois = 0;
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? 0) : ($plan->max_pois ?? 0);
            }
        }

        if ($roleId == 4) {
            $maxPois = 9999;
        }

        $poisCreated = $poiModel->where('added_by', $userId)->countAllResults();

        $data['maxPois']     = $maxPois;
        $data['poisCreated'] = $poisCreated;
        
        $data['pois'] = $poiModel->where('status', 'Active')->findAll();
        $data['property'] = $property;
        $data['propertyTypes'] = (new PropertyTypeModel())->findAll();
        $data['states'] = (new StateModel())->where('status', 'Active')->findAll();
        
        $stateId = is_array($property) ? ($property['state_id'] ?? null) : ($property->state_id ?? null);

        if ($stateId) {
            $data['cities'] = (new CityModel())->where('state_id', $stateId)->findAll();
        } else {
            $data['cities'] = [];
        }

        $data['features'] = (new FeatureModel())->where('status', 'Active')->findAll();
        
        $propertyFeatureModel = new PropertyFeatureModel();
        $currentFeatures = $propertyFeatureModel->where('property_id', $id)->findAll();
        
        $featureIds = [];
        foreach ($currentFeatures as $cf) {
            $featureIds[] = is_array($cf) ? $cf['feature_id'] : $cf->feature_id;
        }
        $data['selectedFeatureIds'] = $featureIds;

        return view('admin/properties/edit', $data);
    }

    public function update($id)
    {
        $propertyModel = new PropertyModel();
        $propertyData = $propertyModel->find($id);

        if (!$propertyData) {
            return redirect()->to(base_url('admin/properties'))->with('error', 'Property not found.');
        }

        $property = is_object($propertyData) ? (array) $propertyData : $propertyData;

        if (session()->get('role_id') != 4 && $property['owner_id'] != session()->get('user_id')) {
            return redirect()->to(base_url('admin/properties'))->with('error', 'Unauthorized access.');
        }

        $rules = [
            'title'            => 'required|min_length[5]|max_length[255]',
            'property_type_id' => 'required|numeric',
            'state_id'         => 'required|numeric',
            'city_id'          => 'required|numeric',
            'tax_price'        => 'required|numeric',
            'address_line_1'   => 'required',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), 
            'city_id'          => $this->request->getPost('city_id'),  
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $this->request->getPost('bed'),
            'bath'             => $this->request->getPost('bath'),
            'total_land_area'  => $this->request->getPost('total_land_area'),
            'usable_area'      => $this->request->getPost('usable_area'),
            'total_area'       => $this->request->getPost('total_area') ?? $this->request->getPost('total_land_area'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'area_name'        => $this->request->getPost('area_name'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'approval_status'  => 'Draft' 
        ];

        $propertyModel->update($id, $updateData);

        $propertyFeatureModel = new PropertyFeatureModel();
        $propertyFeatureModel->where('property_id', $id)->delete();
        $selectedFeatures = $this->request->getPost('features');
        
        if (!empty($selectedFeatures) && is_array($selectedFeatures)) {
            foreach ($selectedFeatures as $featureId) {
                $propertyFeatureModel->builder()->insert([
                    'property_id' => $id,
                    'feature_id'  => $featureId,
                    'status'      => 'Active'
                ]);
            }
        }

        $shmFile = $this->request->getFile('shm_document');
        if ($shmFile && $shmFile->isValid() && ! $shmFile->hasMoved()) {
            $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
            if (!empty($cloudinaryUrl)) {
                $cloudinary = new Cloudinary($cloudinaryUrl);
                $response = $cloudinary->uploadApi()->upload($shmFile->getTempName(), [
                    'folder' => 'hunikita_documents',
                ]);
                
                $propVerifyModel = new PropertyVerificationModel();
                $propVerifyModel->where('property_id', $id)->delete();
                $propVerifyModel->builder()->insert([
                    'property_id' => $id,
                    'ownership_certificate' => $response['secure_url'], 
                    'approval_status' => 'Pending Verification',
                    'status' => 'Active'
                ]);
            }
        }

        return redirect()->to(base_url('admin/properties'))->with('success', 'Property updated and reset to Draft.');
    }
    
    public function getCities($stateId)
    {
        $cityModel = new CityModel();
        $cities = $cityModel->where('state_id', $stateId)->findAll();
        
        return $this->response->setJSON($cities);
    }

    public function getZipcodes($cityId)
    {
        $zipModel = new \App\Models\ZipcodeModel();
        $zipcodes = $zipModel->where('city_id', $cityId)->findAll();
        
        return $this->response->setJSON($zipcodes);
    }

    public function updateStatus($id)
    {
        $propertyModel = new \App\Models\PropertyModel();
        
        $json = $this->request->getJSON();
        $status = $json->status ?? '';
        
        if (empty($status)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No status provided.']);
        }

        if ($propertyModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated successfully.']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Database update failed.']);
    }
}