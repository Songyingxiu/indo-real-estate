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
        
        // Force the timezone to WIB
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

        $data['tips'] = $cmsModel
            ->where('category', 'Tips')
            ->where('status', 'Published')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->find();

        // Fetching FAQs from the CMS table
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

    public function search()
    {
        $propertyModel = new PropertyModel();
        $typeModel = new PropertyTypeModel();
        
        $data['propertyTypes'] = $typeModel->asObject()->where('status', 'Active')->findAll();

        $keyword     = $this->request->getGet('q');
        $types       = $this->request->getGet('type') ?? [];
        $listingType = $this->request->getGet('listing_type');
        $lat         = $this->request->getGet('lat');
        $lng         = $this->request->getGet('lng');
        $radius      = $this->request->getGet('radius');

        $propertyModel->searchProperties($keyword, $listingType, $types, $lat, $lng, $radius);

        $data['properties'] = $propertyModel->paginate(9);
        $data['pager']      = $propertyModel->pager;
        $data['total']      = $propertyModel->pager->getTotal(); 
        
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
        $poiModel = new PoiModel();
        $adsModel = new AdsModel();
        
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

        // Calculate how many POIs this property owner is allowed to display
        $subModel = new \App\Models\SubscriptionModel();
        $planModel = new \App\Models\SubscriptionPlanModel();
        $userModel = new \App\Models\UserModel();

        $activeSub = $subModel->where('user_id', $property->owner_id)->where('sub_status', 'Active')->first();
        $maxPois = 0;
        
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? 0) : ($plan->max_pois ?? 0);
            }
        }

        $owner = $userModel->find($property->owner_id);
        $ownerRole = is_array($owner) ? ($owner['role_id'] ?? null) : ($owner->role_id ?? null);
        
        if ($ownerRole == 4) { // Admins get unlimited display
            $maxPois = 9999;
        }

        $isSaved = false;
        $userId = session()->get('id');
        if ($userId) {
            $savedModel = new SavedPropertyModel();
            $check = $savedModel->where('user_id', $userId)->where('property_id', $id)->first();
            if ($check) $isSaved = true;
        }

        $data['images'] = $imageModel->asObject()->where('property_id', $id)->findAll();
        $data['property'] = $property;
        $data['isSaved'] = $isSaved;
        $data['title'] = $property->title . ' - HuniKita';

        // Fetch features and group them by category for the detail view
        $db = \Config\Database::connect();
        $featuresRaw = $db->table('property_feature_map pfm')
            ->select('f.name as feature_name, fc.name as category_name')
            ->join('features f', 'f.id = pfm.feature_id')
            ->join('feature_categories fc', 'fc.id = f.category_id', 'left')
            ->where('pfm.property_id', $id)
            ->get()->getResult();

        $categorizedFeatures = [];
        foreach ($featuresRaw as $f) {
            $catName = !empty($f->category_name) ? $f->category_name : 'General Amenities';
            $categorizedFeatures[$catName][] = $f->feature_name;
        }
        $data['propertyFeatures'] = $categorizedFeatures;

        // Fetch specific ads meant for the detail page sidebar/content
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

        // Geospatial & Recommendation Data
        $data['nearbyProperties'] = [];
        $data['nearbyPOIs'] = [];

        // Safeguard: Only run geographic queries if the property has coordinates
        if (!empty($property->latitude) && !empty($property->longitude)) {
            $data['nearbyProperties'] = $propertyModel->getNearbyProperties($property->latitude, $property->longitude, $property->id);
            
            // Fetch all, but strictly enforce the owner's subscription POI limit
            $allNearbyPOIs = $poiModel->getNearbyPOIs($property->latitude, $property->longitude);
            $data['nearbyPOIs'] = array_slice($allNearbyPOIs, 0, $maxPois);
        }

        $data['similarType'] = $propertyModel->getSimilarProperties('property_type_id', $property->property_type_id, $property->id);
        
        $minPrice = $property->tax_price * 0.8;
        $maxPrice = $property->tax_price * 1.2;
        $data['similarPrice'] = $propertyModel->where('tax_price >=', $minPrice)->where('tax_price <=', $maxPrice)->where('id !=', $property->id)->limit(5)->find();
        
        return view('front/properties/details', $data);
    }

    // LOCATION LANDING PAGES
    public function province($provinceSlug)
    {
        $stateModel = new StateModel();
        $propertyModel = new PropertyModel();

        $state = $stateModel->where("LOWER(REPLACE(name, ' ', '-'))", $provinceSlug)->first();
        if (!$state) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Province not found");

        $data['state'] = $state;
        $data['cityStats'] = $propertyModel->getCityStatsByState($state->id);
        
        $propertyModel->select('properties.*, property_images.image_path')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->where('state_id', $state->id)
                      ->where('status', 'Active')
                      ->where('approval_status', 'Published');
                      
        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = $state->name . ' Real Estate - HuniKita';

        return view('front/properties/state', $data);
    }

    public function city($citySlug, $provinceSlug)
    {
        $cityModel = new CityModel();
        $propertyModel = new PropertyModel();

        $city = $cityModel->where("LOWER(REPLACE(name, ' ', '-'))", $citySlug)->first();
        if (!$city) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("City not found");

        $data['city'] = $city;
        $data['markers'] = $propertyModel->getMapMarkers(['city_id' => $city->id]);
        
        $propertyModel->select('properties.*, property_images.image_path')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->where('city_id', $city->id)
                      ->where('status', 'Active')
                      ->where('approval_status', 'Published');

        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = 'Properties in ' . $city->name . ' - HuniKita';

        return view('front/properties/city', $data);
    }

    public function zipcode($zipcode)
    {
        $zipcodeModel = new ZipcodeModel();
        $propertyModel = new PropertyModel();

        $zip = $zipcodeModel->where('zipcode', $zipcode)->first();
        if (!$zip) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Zipcode not found");

        $data['zipcode'] = $zip;
        $data['markers'] = $propertyModel->getMapMarkers(['zipcode_id' => $zip->id]);
        
        $propertyModel->select('properties.*, property_images.image_path')
                      ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                      ->where('zipcode_id', $zip->id)
                      ->where('status', 'Active')
                      ->where('approval_status', 'Published');

        $data['properties'] = $propertyModel->paginate(20);
        $data['pager'] = $propertyModel->pager;
        $data['title'] = 'Properties in ' . $zip->zipcode . ' - HuniKita';

        return view('front/properties/zipcode', $data);
    }

    public function suggest()
    {
        helper('url');
        $query = $this->request->getGet('q');

        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([]);
        }

        $cityModel = new CityModel();
        $stateModel = new StateModel();
        $zipcodeModel = new ZipcodeModel();
        $propertyModel = new PropertyModel();

        $results = [];

        // 1. Search Active Regions/States
        $states = $stateModel->like('name', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($states as $state) {
            $slug = url_title(strtolower($state->name), '-', true);
            $results[] = [
                'text' => $state->name,
                'category' => 'Region',
                'url' => base_url("properties/province/{$slug}")
            ];
        }

        // 2. Search Active Cities
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
                'url' => base_url("properties/city/{$citySlug}/{$stateSlug}")
            ];
        }

        // 3. Search Zipcodes
        $zipcodes = $zipcodeModel->like('zipcode', $query)->where('status', 'Active')->limit(3)->find();
        foreach ($zipcodes as $zip) {
            $results[] = [
                'text' => $zip->zipcode,
                'category' => 'Zip Code',
                'url' => base_url("properties/zipcode/{$zip->zipcode}")
            ];
        }

        // 4. Search Published Properties
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
                'category' => 'Property Listing',
                'url' => base_url("properties/{$prop->id}")
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