<?php namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\PropertyTypeModel;
use App\Models\PropertyImageModel;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\CmsModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $cmsModel = new CmsModel();
        
        // 1. POPULAR LISTINGS (Randomized to look active and diverse)
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

        // 2. NEWEST LISTINGS (Strictly ordered by newest created date)
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

        // 3. TIPS & GUIDES (Fetches the 3 most recent published tips)
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

        $keyword     = $this->request->getGet('q');
        $types       = $this->request->getGet('type');
        $listingType = $this->request->getGet('listing_type');

        $builder = $propertyModel
            ->asObject() 
            ->select('properties.*, property_types.name as type_name, users.first_name, users.last_name, property_images.image_path')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('users', 'users.id = properties.owner_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published');

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('properties.title', $keyword)
                    ->orLike('properties.area_name', $keyword)
                    ->groupEnd();
        }

        if (!empty($types) && is_array($types)) {
            $builder->whereIn('properties.property_type_id', $types);
        }

        if (!empty($listingType)) {
            $builder->where('properties.listing_type', $listingType);
        }

        $data['properties'] = $builder->paginate(9);
        $data['pager']      = $propertyModel->pager;
        $data['total']      = $propertyModel->pager->getTotal(); 
        $data['keyword']    = $keyword;
        $data['listingType']= $listingType;
        
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

        $data['images'] = $imageModel->asObject()->where('property_id', $id)->findAll();
        $data['property'] = $property;
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
}