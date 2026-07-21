<?php namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\PropertyTypeModel;
use App\Models\PropertyImageModel;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\CmsModel;
use App\Models\SavedPropertyModel;
use App\Models\SavedSearchModel;
use App\Models\AdsModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $cmsModel = new CmsModel();
        $adsModel = new AdsModel();
        
        $today = date('Y-m-d');
        
        // Fetch ads for the home banner that are Active and within the date range
        $data['banners'] = $adsModel->where('placement', 'home_banner')
            ->where('status', 'Active')
            ->groupStart()
                ->where('start_date <=', $today)
                ->orWhere('start_date IS NULL')
            ->groupEnd()
            ->groupStart()
                ->where('end_date >=', $today)
                ->orWhere('end_date IS NULL')
            ->groupEnd()
            ->findAll();
        
        // 1. POPULAR LISTINGS
        $data['featuredProperties'] = $propertyModel
            ->asObject() 
            ->select('properties.*, property_types.name as type_name, property_images.image_path')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->orderBy('RAND()') 
            ->limit(6)
            ->find();

        // 2. NEWEST LISTINGS
        $data['newestProperties'] = $propertyModel
            ->asObject() 
            ->select('properties.*, property_types.name as type_name, property_images.image_path')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->orderBy('properties.created_date', 'DESC')
            ->limit(6)
            ->find();

        // 3. TIPS & GUIDES
        $data['tips'] = $cmsModel
            ->where('category', 'Tips')
            ->where('status', 'Published')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->find();

        $data['title'] = 'HuniKita - Real Estate Platform';
        return view('front/home', $data);
    }

    public function search()
    {
        $propertyModel = new PropertyModel();
        $typeModel = new PropertyTypeModel();
        
        $data['propertyTypes'] = $typeModel->asObject()->where('status', 'Active')->findAll();

        // Retrieve all GET parameters including new radius fields
        $keyword     = $this->request->getGet('q');
        $types       = $this->request->getGet('type') ?? [];
        $listingType = $this->request->getGet('listing_type');
        $lat         = $this->request->getGet('lat');
        $lng         = $this->request->getGet('lng');
        $radius      = $this->request->getGet('radius');

        // Execute custom search through the PropertyModel
        $propertyModel->searchProperties($keyword, $listingType, $types, $lat, $lng, $radius);

        $data['properties'] = $propertyModel->paginate(9);
        $data['pager']      = $propertyModel->pager;
        $data['total']      = $propertyModel->pager->getTotal(); 
        
        // Pass parameters back to view to keep filters active
        $data['keyword']    = $keyword;
        $data['listingType']= $listingType;
        $data['lat']        = $lat;
        $data['lng']        = $lng;
        $data['radius']     = $radius;
        
        $data['title'] = 'Search Properties - HuniKita';
        return view('front/properties/search', $data);
    }

    public function detail($id)
    {
        $propertyModel = new PropertyModel();
        $imageModel = new PropertyImageModel();
        
        $property = $propertyModel
            ->asObject()
            ->select('properties.*, property_types.name as type_name, users.first_name, users.last_name, users.phone_number, users.email')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('users', 'users.id = properties.owner_id', 'left')
            ->where('properties.id', $id)
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->first();

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Property not found");
        }

        // Check if current user has saved this property
        $isSaved = false;
        $userId = session()->get('id');
        if ($userId) {
            $savedModel = new SavedPropertyModel();
            $check = $savedModel->where('user_id', $userId)->where('property_id', $id)->first();
            if ($check) $isSaved = true;
        }

        $data['images'] = $imageModel->asObject()->where('property_id', $id)->findAll();
        $data['property'] = $property;
        $data['isSaved'] = $isSaved; // Pass variable to view
        $data['title'] = $property->title . ' - HuniKita';
        
        return view('front/properties/details', $data);
    }

    public function suggest()
    {
        $query = $this->request->getGet('q');

        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([]);
        }

        $cityModel = new CityModel();
        $stateModel = new StateModel();
        $propertyModel = new PropertyModel();

        $results = [];

        // 1. Search Active Cities
        $cities = $cityModel->like('name', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($cities as $city) {
            $results[] = [
                'text' => $city->name,
                'category' => 'Location'
            ];
        }

        // 2. Search Active Regions/States
        $states = $stateModel->like('name', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($states as $state) {
            $results[] = [
                'text' => $state->name,
                'category' => 'Region'
            ];
        }

        // 3. Search Published Properties
        $properties = $propertyModel->asObject()
            ->groupStart()
                ->like('title', $query)
                ->orLike('area_name', $query)
            ->groupEnd()
            ->where('status', 'Active')
            ->where('approval_status', 'Published')
            ->limit(4)
            ->find();

        foreach ($properties as $prop) {
            $results[] = [
                'text' => $prop->title,
                'category' => 'Property Listing'
            ];
        }

        return $this->response->setJSON($results);
    }

    // METHOD TO HANDLE AJAX SAVE/UNSAVE
    public function toggleSaveProperty()
    {
        $userId = session()->get('id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $propertyId = $json->property_id ?? null;

        if (!$propertyId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid property ID'])->setStatusCode(400);
        }

        $savedModel = new SavedPropertyModel();
        $existing = $savedModel->where('user_id', $userId)->where('property_id', $propertyId)->first();

        if ($existing) {
            $savedModel->delete($existing->id);
            return $this->response->setJSON(['status' => 'success', 'action' => 'removed']);
        } else {
            $savedModel->insert([
                'user_id' => $userId, 
                'property_id' => $propertyId, 
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $this->response->setJSON(['status' => 'success', 'action' => 'added']);
        }
    }

    // SAVE SEARCH PARAMETERS
    public function saveSearch()
    {
        $userId = session()->get('id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        
        $savedSearchModel = new SavedSearchModel();
        $savedSearchModel->insert([
            'user_id'    => $userId,
            'name'       => $json->name ?? 'Saved Search ' . date('M d, Y'),
            'filters'    => json_encode($json->filters ?? []),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Search parameters saved successfully.']);
    }
}