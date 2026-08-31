<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyTypeModel; 
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\ZipcodeModel; 
use App\Models\FeatureModel;
use App\Models\PropertyFeatureModel;
use App\Models\PropertyVerificationModel;
use App\Models\AgentVerificationModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\PoiModel;
use Cloudinary\Cloudinary; 
use App\Libraries\EmailService;

class Properties extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $roleId = session()->get('role_id');
        $userId = session()->get('user_id');

        $propertyModel->select('properties.*, property_verifications.approval_status as doc_status');
        $propertyModel->join('property_verifications', 'property_verifications.property_id = properties.id', 'left');

        if ($roleId == 4) {
            $data['properties'] = $propertyModel->findAll();
        } else {
            $data['properties'] = $propertyModel->where('properties.owner_id', $userId)->findAll();
        }

        return view('admin/properties/properties', $data); 
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $roleId = session()->get('role_id');

        $propertyTypeModel = new PropertyTypeModel();
        $stateModel = new StateModel();
        $propertyModel = new PropertyModel();
        
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        $poiModel = new PoiModel();

        // 1. Fetch the default "Free" plan dynamically from Master Data
        $freePlan = $planModel->where('price', 0)->first();
        $maxPois = $freePlan ? (is_array($freePlan) ? $freePlan['max_pois'] : $freePlan->max_pois) : 0;
        $maxProperties = $freePlan ? (is_array($freePlan) ? $freePlan['max_properties'] : $freePlan->max_properties) : 1;

        // 2. Override with Active Subscription if they bought one
        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? $maxPois) : ($plan->max_pois ?? $maxPois);
                $maxProperties = is_array($plan) ? ($plan['max_properties'] ?? $maxProperties) : ($plan->max_properties ?? $maxProperties);
            }
        }

        if ($roleId == 4) {
            $maxPois = 9999;
            $maxProperties = 9999;
        }

        $currentListings = $propertyModel->where('owner_id', $userId)->countAllResults();
        
        if ($currentListings >= $maxProperties && $roleId != 4) {
            return redirect()->to(base_url('admin/properties'))->with('error', 'You have reached your limit of ' . $maxProperties . ' properties on your current plan. Please upgrade to post more.');
        }

        $poisCreated = $poiModel->where('added_by', $userId)->countAllResults();

        $data['maxPois']         = $maxPois;
        $data['poisCreated']     = $poisCreated;
        $data['maxProperties']   = $maxProperties;
        $data['currentListings'] = $currentListings;
        
        $data['pois'] = $poiModel->where('status', 'Active')->findAll();
        $data['propertyTypes'] = $propertyTypeModel->findAll();
        $data['states'] = $stateModel->where('status', 'Active')->findAll();
        
        $db = \Config\Database::connect();
        $rawFeatures = $db->table('features')
            ->select('features.id, features.name, features.name_en, features.name_id, feature_categories.name as category_name')
            ->join('feature_categories', 'feature_categories.id = features.category_id', 'left')
            ->where('features.status', 'Active')
            ->get()->getResult();

        $categorizedFeatures = [];
        foreach ($rawFeatures as $feature) {
            $catName = !empty($feature->category_name) ? $feature->category_name : 'Uncategorized';
            $categorizedFeatures[$catName][] = $feature;
        }
        $data['categorizedFeatures'] = $categorizedFeatures;
        
        return view('admin/properties/create', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $roleId = session()->get('role_id');
        $userFirstName = session()->get('first_name') ?? 'User';
        $userEmail = session()->get('email');

        $propertyModel = new PropertyModel();
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        
        // 1. Fetch the default "Free" plan limits
        $freePlan = $planModel->where('price', 0)->first();
        $maxProperties = $freePlan ? (is_array($freePlan) ? $freePlan['max_properties'] : $freePlan->max_properties) : 1;

        // 2. Override with active subscription
        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxProperties = is_array($plan) ? ($plan['max_properties'] ?? $maxProperties) : ($plan->max_properties ?? $maxProperties);
            }
        }

        if ($roleId != 4) {
            $currentListings = $propertyModel->where('owner_id', $userId)->countAllResults();
            if ($currentListings >= $maxProperties) {
                return redirect()->to(base_url('admin/properties'))->with('error', 'Limit reached. Please upgrade your subscription to post more than ' . $maxProperties . ' properties.');
            }
        }

        $uploadedImages = $this->request->getFileMultiple('property_images');
        if (!empty($uploadedImages) && count($uploadedImages) > 20) {
            return redirect()->back()->withInput()->with('error', 'You can upload a maximum of 20 property images.');
        }

        $rules = [
            'title_en'         => 'required|min_length[5]|max_length[255]',
            'title_id'         => 'required|min_length[5]|max_length[255]',
            'property_type_id' => 'required|numeric',
            'state_id'         => 'required|numeric',
            'city_id'          => 'required|numeric',
            'tax_price'        => 'required|numeric',
            'address_line_1'   => 'required',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'parking'          => 'permit_empty|in_list[Available,Not Available]',
            'basement'         => 'permit_empty|in_list[Yes,No]',
            'water_facility'   => 'permit_empty|max_length[255]',
            'rental_period'    => 'permit_empty|in_list[Month,Year]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
        if (empty($cloudinaryUrl)) {
            return redirect()->back()->withInput()->with('error', 'Cloudinary configuration is missing.');
        }
        $cloudinary = new Cloudinary($cloudinaryUrl);

        $imageModel = new PropertyImageModel();
        $propertyFeatureModel = new PropertyFeatureModel(); 
        
        $propTitleEN = $this->request->getPost('title_en');

        $getNumericPost = function($field) {
            $val = $this->request->getPost($field);
            return ($val === '' || $val === null) ? null : $val;
        };

        $data = [
            'title'             => $propTitleEN, 
            'title_en'         => $propTitleEN,
            'title_id'         => $this->request->getPost('title_id'),
            'description'      => $this->request->getPost('description_en'), 
            'description_en'   => $this->request->getPost('description_en'),
            'description_id'   => $this->request->getPost('description_id'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'rental_period'    => $this->request->getPost('listing_type') === 'Rent' ? $this->request->getPost('rental_period') : null,
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), 
            'city_id'          => $this->request->getPost('city_id'),
            'zipcode_id'       => $getNumericPost('zipcode_id'),
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $getNumericPost('bed'),
            'bath'             => $getNumericPost('bath'),
            'total_land_area'  => $getNumericPost('total_land_area'),
            'usable_area'      => $getNumericPost('usable_area'),
            'total_area'       => $getNumericPost('total_area') ?? $getNumericPost('total_land_area'),
            'total_floors'     => $getNumericPost('total_floors'),
            'year_built'       => $getNumericPost('year_built'),
            'total_parking'    => $getNumericPost('total_parking'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'address_line_2'   => $this->request->getPost('address_line_2'),
            'area_name'        => $this->request->getPost('area_name'), 
            'unit_number'      => $this->request->getPost('unit_number'), 
            'building_society_name' => $this->request->getPost('building_society_name'), 
            'parking'          => $this->request->getPost('parking'), 
            'basement'         => $this->request->getPost('basement'), 
            'water_facility'   => $this->request->getPost('water_facility'), 
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'owner_id'         => $userId,
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

        $emailService = new EmailService();
        
        $emailService->sendDynamicEmail('Property Listed Customer', $userEmail, [
            '{first_name}' => $userFirstName,
            '{property_title}' => $propTitleEN
        ]);

        $emailService->sendDynamicEmail('Property Listed Admin', 'admin@hunikita.com', [
            '{property_id}' => $propertyId,
            '{property_title}' => $propTitleEN
        ]);

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

        // 1. Fetch the default "Free" plan limits
        $freePlan = $planModel->where('price', 0)->first();
        $maxPois = $freePlan ? (is_array($freePlan) ? $freePlan['max_pois'] : $freePlan->max_pois) : 0;

        // 2. Override with active subscription
        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? $maxPois) : ($plan->max_pois ?? $maxPois);
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

        $db = \Config\Database::connect();
        $rawFeatures = $db->table('features')
            ->select('features.id, features.name, features.name_en, features.name_id, feature_categories.name as category_name')
            ->join('feature_categories', 'feature_categories.id = features.category_id', 'left')
            ->where('features.status', 'Active')
            ->get()->getResult();

        $categorizedFeatures = [];
        foreach ($rawFeatures as $feature) {
            $catName = !empty($feature->category_name) ? $feature->category_name : 'Uncategorized';
            $categorizedFeatures[$catName][] = $feature;
        }
        $data['categorizedFeatures'] = $categorizedFeatures;
        
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

        $uploadedImages = $this->request->getFileMultiple('property_images');
        if (!empty($uploadedImages) && $uploadedImages[0]->isValid() && count($uploadedImages) > 20) {
            return redirect()->back()->withInput()->with('error', 'You can upload a maximum of 20 property images.');
        }

        $rules = [
            'title_en'         => 'required|min_length[5]|max_length[255]',
            'title_id'         => 'required|min_length[5]|max_length[255]',
            'property_type_id' => 'required|numeric',
            'state_id'         => 'required|numeric',
            'city_id'          => 'required|numeric',
            'tax_price'        => 'required|numeric',
            'address_line_1'   => 'required',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'parking'          => 'permit_empty|in_list[Available,Not Available]',
            'basement'         => 'permit_empty|in_list[Yes,No]',
            'water_facility'   => 'permit_empty|max_length[255]',
            'rental_period'    => 'permit_empty|in_list[Month,Year]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $getNumericPost = function($field) {
            $val = $this->request->getPost($field);
            return ($val === '' || $val === null) ? null : $val;
        };

        $updateData = [
            'title'             => $this->request->getPost('title_en'), 
            'title_en'         => $this->request->getPost('title_en'),
            'title_id'         => $this->request->getPost('title_id'),
            'description'      => $this->request->getPost('description_en'), 
            'description_en'   => $this->request->getPost('description_en'),
            'description_id'   => $this->request->getPost('description_id'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'rental_period'    => $this->request->getPost('listing_type') === 'Rent' ? $this->request->getPost('rental_period') : null, 
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), 
            'city_id'          => $this->request->getPost('city_id'),
            'zipcode_id'       => $getNumericPost('zipcode_id'),
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $getNumericPost('bed'),
            'bath'             => $getNumericPost('bath'),
            'total_land_area'  => $getNumericPost('total_land_area'),
            'usable_area'      => $getNumericPost('usable_area'),
            'total_area'       => $getNumericPost('total_area') ?? $getNumericPost('total_land_area'),
            'total_floors'     => $getNumericPost('total_floors'),
            'year_built'       => $getNumericPost('year_built'),
            'total_parking'    => $getNumericPost('total_parking'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'address_line_2'   => $this->request->getPost('address_line_2'),
            'area_name'        => $this->request->getPost('area_name'),
            'unit_number'      => $this->request->getPost('unit_number'),
            'building_society_name' => $this->request->getPost('building_society_name'),
            'parking'          => $this->request->getPost('parking'),
            'basement'         => $this->request->getPost('basement'),
            'water_facility'   => $this->request->getPost('water_facility'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude')
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

        $successMessage = 'Property updated successfully.';

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

                $successMessage = 'Property updated! New SHM document sent to verification center.';
            }
        }

        return redirect()->to(base_url('admin/properties'))->with('success', $successMessage);
    }
    
    public function getCities($stateId = null)
    {
        $id = $stateId ?? $this->request->getGet('state_id');
        $cityModel = new CityModel();
        $cities = $cityModel->where('state_id', $id)->findAll();
        
        return $this->response->setJSON($cities);
    }

    public function getZipcodes($cityId = null)
    {
        $id = $cityId ?? $this->request->getGet('city_id');
        $zipModel = new ZipcodeModel();
        $zipcodes = $zipModel->where('city_id', $id)->findAll();
        
        return $this->response->setJSON($zipcodes);
    }

    public function get_cities($stateId = null)
    {
        return $this->getCities($stateId);
    }

    public function get_zipcodes($cityId = null)
    {
        return $this->getZipcodes($cityId);
    }

    public function updateStatus($id)
    {
        $propertyModel = new PropertyModel();
        
        $json = $this->request->getJSON();
        $status = $json->status ?? '';
        
        if (empty($status)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No status provided.']);
        }

        if ($status === 'Active') {
            $docModel = new PropertyVerificationModel();
            $doc = $docModel->where('property_id', $id)->first();
            $docStatus = $doc ? (is_object($doc) ? $doc->approval_status : $doc['approval_status']) : 'Not Submitted';

            if ($docStatus !== 'Verified') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot activate listing. The required document is currently: ' . $docStatus]);
            }
        }

        if ($propertyModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated successfully.']);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Database update failed.']);
    }
}