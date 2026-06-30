<?php namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\PropertyTypeModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        
        // Fetch 3 most recent Published properties
        $data['featuredProperties'] = $propertyModel
            ->select('properties.*, property_types.name as type_name')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->orderBy('properties.created_date', 'DESC')
            ->limit(3)
            ->find();

        $data['title'] = 'Lunera - Real Estate Platform';
        return view('front/home', $data);
    }

    public function search()
    {
        $propertyModel = new PropertyModel();
        $typeModel = new PropertyTypeModel();
        
        // Fetch dynamic property types for the Sidebar checkboxes
        $data['propertyTypes'] = $typeModel->where('status', 'Active')->findAll();

        // Capture search inputs
        $keyword = $this->request->getGet('q');
        $types   = $this->request->getGet('type'); // Array of ID numbers now!

        $builder = $propertyModel
            ->select('properties.*, property_types.name as type_name, users.first_name, users.last_name')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('users', 'users.id = properties.owner_id', 'left')
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published');

        // Apply Keyword Filter
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('properties.title', $keyword)
                    ->orLike('properties.area_name', $keyword)
                    ->groupEnd();
        }

        // Apply Property Type Filter (Proper ID matching!)
        if (!empty($types) && is_array($types)) {
            $builder->whereIn('properties.property_type_id', $types);
        }

        $data['properties'] = $builder->paginate(9);
        $data['pager']      = $propertyModel->pager;
        $data['total']      = $builder->countAllResults(false);
        $data['keyword']    = $keyword;
        
        $data['title'] = 'Search Properties - Lunera';
        return view('front/properties/search', $data);
    }
}