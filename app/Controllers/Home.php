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
use App\Models\ZipcodeModel;
use App\Models\PoiModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $cmsModel = new CmsModel();
        $adsModel = new AdsModel();
        
        $today = Time::now('Asia/Jakarta')->toDateString();
        
        $data['banners'] = $adsModel->where('placement', 'home_banner')
            ->where('status', 'Active')
            ->groupStart()
                ->where('start_date <=', $today)
                ->orWhere('start_date', null)
                ->orWhere('start_date', '0000-00-00')
                ->orWhere('start_date', '')
            ->groupEnd()
            ->groupStart()
                ->where('end_date >=', $today)
                ->orWhere('end_date', null)
                ->orWhere('end_date', '0000-00-00')
                ->orWhere('end_date', '')
            ->groupEnd()
            ->findAll();
        
        $data['featuredProperties'] = $propertyModel->getPopularProperties(6);

        $data['newestProperties'] = $propertyModel
            ->asObject() 
            ->select('properties.*, property_types.name as type_name, property_images.image_path, cities.name as city_name')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('properties.approval_status !=', 'Draft')
            ->orderBy('properties.created_date', 'DESC')
            ->limit(6)
            ->find();

        $data['tips'] = $cmsModel
            ->where('category', 'Tips')
            ->where('status', 'Published')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->find();

        $data['faqs'] = $cmsModel
            ->where('category', 'FAQ')
            ->where('status', 'Published')
            ->orderBy('published_at', 'ASC')
            ->find();

        $data['title'] = 'HuniKita - Real Estate Platform';

        return view('front/home', $data);
    }

    public function promo($id)
    {
        $adsModel = new AdsModel();
        $promo = $adsModel->find($id);

        if (!$promo || $promo->status !== 'Active') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Advertisement not found or inactive.");
        }

        $data['promo'] = $promo;
        $data['title'] = $promo->title . ' - HuniKita';
        
        return view('front/promo_detail', $data);
    }

    public function search($type = null)
    {
        $propertyModel = new PropertyModel();
        $typeModel = new PropertyTypeModel();
        
        $data['propertyTypes'] = $typeModel->asObject()->where('status', 'Active')->findAll();

        $keyword     = $this->request->getGet('q');
        $types       = $this->request->getGet('type') ?? [];
        $lat         = $this->request->getGet('lat');
        $lng         = $this->request->getGet('lng');
        $radius      = $this->request->getGet('radius');
        $sort        = $this->request->getGet('sort') ?? 'new';

        $listingType = $type ? ucfirst(strtolower($type)) : $this->request->getGet('listing_type');
        if (empty($listingType)) $listingType = 'Sale';

        $propertyModel->searchProperties($keyword, $listingType, $types, $lat, $lng, $radius, $sort);

        $data['properties'] = $propertyModel->paginate(9);
        $data['pager']      = $propertyModel->pager;
        $data['total']      = $propertyModel->pager->getTotal(); 
        
        $data['keyword']    = $keyword;
        $data['listingType']= $listingType;
        $data['lat']        = $lat;
        $data['lng']        = $lng;
        $data['radius']     = $radius;
        $data['sort']       = $sort;
        
        $data['title'] = 'Search Properties - HuniKita';
        return view('front/properties/search', $data);
    }

    public function detail($id)
    {
        $propertyModel = new PropertyModel();
        $imageModel = new PropertyImageModel();
        $poiModel = new PoiModel();
        $adsModel = new AdsModel();
        
        $property = $propertyModel
            ->asObject()
            ->select('properties.*, property_types.name as type_name, users.first_name, users.last_name, users.phone_number, users.email, users.role_id, zipcodes.zipcode')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('users', 'users.id = properties.owner_id', 'left')
            ->join('zipcodes', 'zipcodes.id = properties.zipcode_id', 'left') 
            ->where('properties.id', $id)
            ->where('properties.approval_status !=', 'Draft')
            ->first();

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Property not found");
        }

        $db = \Config\Database::connect();
        $ipAddress = $this->request->getIPAddress();
        $userId = session()->get('id') ?? null;

        $viewBuilder = $db->table('property_views');
        $existingView = $viewBuilder->where('property_id', $id)
            ->where('ip_address', $ipAddress)
            ->when($userId, function($q) use ($userId) {
                return $q->where('user_id', $userId);
            })
            ->get()->getRow();

        if (!$existingView) {
            $viewBuilder->insert([
                'property_id' => $id,
                'user_id'     => $userId,
                'ip_address'  => $ipAddress,
                'viewed_at'   => date('Y-m-d H:i:s')
            ]);
        }

        $data['timeAgo'] = Time::parse($property->created_date)->humanize();

        $subModel = new \App\Models\SubscriptionModel();
        $planModel = new \App\Models\SubscriptionPlanModel();
        $userModel = new \App\Models\UserModel();

        $activeSub = $subModel->where('user_id', $property->owner_id)->where('sub_status', 'Active')->first();
        
        $maxPois = 5; 
        $allowDirectEmail = false;
        
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? 5) : ($plan->max_pois ?? 5);
                $allowDirectEmail = is_array($plan) ? ($plan['allow_direct_email'] ?? false) : ($plan->allow_direct_email ?? false);
            }
        }

        $owner = $userModel->find($property->owner_id);
        $ownerRole = is_array($owner) ? ($owner['role_id'] ?? null) : ($owner->role_id ?? null);
        
        if ($ownerRole == 4) { 
            $maxPois = 9999;
            $allowDirectEmail = true;
        }

        $data['allowDirectEmail'] = $allowDirectEmail;

        $isSaved = false;
        if ($userId) {
            $savedModel = new SavedPropertyModel();
            $check = $savedModel->where('user_id', $userId)->where('property_id', $id)->first();
            if ($check) $isSaved = true;
        }

        $data['images'] = $imageModel->asObject()->where('property_id', $id)->findAll();
        $data['property'] = $property;
        $data['isSaved'] = $isSaved;
        $data['title'] = $property->title . ' - HuniKita';

        $featuresRaw = $db->table('property_features pf')
            ->select('f.name as feature_name, fc.name as category_name')
            ->join('features f', 'f.id = pf.feature_id')
            ->join('feature_categories fc', 'fc.id = f.category_id', 'left')
            ->where('pf.property_id', $id)
            ->get()->getResult();

        $categorizedFeatures = [];
        foreach ($featuresRaw as $f) {
            $catName = !empty($f->category_name) ? $f->category_name : 'General Amenities';
            $categorizedFeatures[$catName][] = $f->feature_name;
        }
        $data['propertyFeatures'] = $categorizedFeatures;

        $today = Time::now('Asia/Jakarta')->toDateString();
        $data['detailAds'] = $adsModel->where('placement', 'property_detail')
            ->where('status', 'Active')
            ->groupStart()
                ->where('start_date <=', $today)
                ->orWhere('start_date', null)
            ->groupEnd()
            ->groupStart()
                ->where('end_date >=', $today)
                ->orWhere('end_date', null)
            ->groupEnd()
            ->limit(2)
            ->findAll();

        $data['nearbyProperties'] = [];
        $data['nearbyPOIs'] = [];

        if (!empty($property->latitude) && !empty($property->longitude)) {
            $data['nearbyProperties'] = $propertyModel->getNearbyProperties($property->latitude, $property->longitude, $property->id);
            
            $allNearbyPOIs = $poiModel->getNearbyPOIs($property->latitude, $property->longitude);
            $data['nearbyPOIs'] = array_slice($allNearbyPOIs, 0, $maxPois);
        }

        $data['similarType'] = $propertyModel->getSimilarProperties('property_type_id', $property->property_type_id, $property->id);
        
        $minPrice = $property->tax_price * 0.8;
        $maxPrice = $property->tax_price * 1.2;
        
        $data['similarPrice'] = $propertyModel->asObject()
            ->select('properties.*, property_images.image_path, cities.name as city_name')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('tax_price >=', $minPrice)
            ->where('tax_price <=', $maxPrice)
            ->where('properties.id !=', $property->id)
            ->limit(5)
            ->find();
        
        return view('front/properties/details', $data);
    }

    public function province_listing($provinceSlug, $listingType = 'sale')
    {
        $stateModel = new StateModel();
        $propertyModel = new PropertyModel();
        $type = ucfirst(strtolower($listingType));

        $state = $stateModel->where("LOWER(REPLACE(name, ' ', '-'))", $provinceSlug)->first();
        if (!$state) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Province not found");

        $data['state'] = $state;
        $data['cityStats'] = $propertyModel->getCityStatsByState($state->id);
        
        $propertyModel->select('properties.*, property_images.image_path, cities.name as city_name')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->join('cities', 'cities.id = properties.city_id', 'left')
                      ->where('cities.state_id', $state->id) 
                      ->where('properties.listing_type', $type)
                      ->where('properties.approval_status !=', 'Draft');
                      
        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = $state->name . ' Real Estate - HuniKita';
        $data['currentType'] = $type;
        $data['listingType'] = $type;

        return view('front/properties/state', $data);
    }

    public function city_listing($citySlug, $provinceSlug, $listingType = 'sale')
    {
        $cityModel = new CityModel();
        $propertyModel = new PropertyModel();
        $type = ucfirst(strtolower($listingType));

        $city = $cityModel->where("LOWER(REPLACE(name, ' ', '-'))", $citySlug)->first();
        if (!$city) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("City not found");

        $data['city'] = $city;
        
        $db = \Config\Database::connect();
        $data['markers'] = $db->table('properties')
            ->select('properties.id, properties.title, properties.tax_price, properties.latitude, properties.longitude, cities.name as city_name, properties.status, properties.approval_status')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('properties.city_id', $city->id)
            ->where('properties.listing_type', $type)
            ->where('properties.approval_status !=', 'Draft')
            ->where('properties.latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();
        
        $propertyModel->select('properties.*, property_images.image_path, cities.name as city_name')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->join('cities', 'cities.id = properties.city_id', 'left')
                      ->where('properties.city_id', $city->id)
                      ->where('properties.listing_type', $type)
                      ->where('properties.approval_status !=', 'Draft');

        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = 'Properties in ' . $city->name . ' - HuniKita';
        $data['currentType'] = $type;
        $data['listingType'] = $type;

        return view('front/properties/city', $data);
    }

    public function zipcode_listing($zipcode, $listingType = 'sale')
    {
        $zipcodeModel = new ZipcodeModel();
        $propertyModel = new PropertyModel();
        $type = ucfirst(strtolower($listingType));

        $zip = $zipcodeModel->where('zipcode', $zipcode)->first();
        if (!$zip) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Zipcode not found");

        $data['zipcode'] = $zip;
        
        $db = \Config\Database::connect();
        $data['markers'] = $db->table('properties')
            ->select('properties.id, properties.title, properties.tax_price, properties.latitude, properties.longitude, cities.name as city_name, properties.status, properties.approval_status')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('properties.zipcode_id', $zip->id)
            ->where('properties.listing_type', $type)
            ->where('properties.approval_status !=', 'Draft')
            ->where('properties.latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();
        
        $propertyModel->select('properties.*, property_images.image_path, cities.name as city_name')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->join('cities', 'cities.id = properties.city_id', 'left')
                      ->where('properties.zipcode_id', $zip->id)
                      ->where('properties.listing_type', $type)
                      ->where('properties.approval_status !=', 'Draft');

        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = 'Properties in ' . $zip->zipcode . ' - HuniKita';
        $data['currentType'] = $type;
        $data['listingType'] = $type;

        return view('front/properties/zipcode', $data);
    }

    public function suggest()
    {
        helper('url');
        $query = $this->request->getGet('q');
        $typeFilter = strtolower($this->request->getGet('type') ?: 'sale');

        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([]);
        }

        $cityModel = new CityModel();
        $stateModel = new StateModel();
        $zipcodeModel = new ZipcodeModel();
        $propertyModel = new PropertyModel();

        $results = [];

        $states = $stateModel->like('name', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($states as $state) {
            $slug = url_title(strtolower($state->name), '-', true);
            $results[] = [
                'text' => $state->name,
                'category' => 'Region',
                'url' => base_url("properties/{$typeFilter}/province/{$slug}")
            ];
        }

        $cities = $cityModel->select('cities.*, states.name as state_name')
            ->join('states', 'states.id = cities.state_id', 'left')
            ->like('cities.name', $query)
            ->where('cities.status', 'Active')
            ->limit(3)
            ->find();
            
        foreach ($cities as $city) {
            $citySlug = url_title(strtolower($city->name), '-', true);
            $stateSlug = url_title(strtolower($city->state_name ?? 'unknown'), '-', true);
            $results[] = [
                'text' => $city->name . ', ' . $city->state_name,
                'category' => 'Location',
                'url' => base_url("properties/{$typeFilter}/city/{$citySlug}/{$stateSlug}")
            ];
        }

        $zipcodes = $zipcodeModel->like('zipcode', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($zipcodes as $zip) {
            $results[] = [
                'text' => $zip->zipcode,
                'category' => 'Zip Code',
                'url' => base_url("properties/{$typeFilter}/zipcode/{$zip->zipcode}")
            ];
        }

        $properties = $propertyModel->asObject()
            ->select('properties.*, cities.name as city_name')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->groupStart()
                ->like('properties.title', $query)
                ->orLike('properties.area_name', $query)
            ->groupEnd()
            ->where('properties.listing_type', ucfirst($typeFilter))
            ->where('properties.approval_status !=', 'Draft')
            ->limit(4)
            ->find();

        foreach ($properties as $prop) {
            $citySlug = url_title(strtolower($prop->city_name ?? 'indonesia'), '-', true);
            $titleSlug = url_title(strtolower($prop->title), '-', true);
            $results[] = [
                'text' => $prop->title,
                'category' => 'Property Listing',
                'url' => base_url("property/{$citySlug}/{$titleSlug}-{$prop->id}")
            ];
        }

        return $this->response->setJSON($results);
    }

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

    public function saveSearch()
    {
        $userId = session()->get('id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $json = $this->request->getJSON();
        $filters = $json->filters ?? [];
        
        $type = ucfirst($filters->listing_type ?? 'Sale');
        $loc = !empty($filters->q) ? $filters->q : 'Any Location';
        $searchName = $type . ' in ' . $loc;
        
        $savedSearchModel = new SavedSearchModel();
        $savedSearchModel->insert([
            'user_id'    => $userId,
            'name'       => $searchName,
            'filters'    => json_encode($filters),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Search parameters saved successfully.']);
    }
}