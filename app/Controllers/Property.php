<?php namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\InquiryModel;

class Property extends BaseController
{
    public function province($slug)
    {
        $db = \Config\Database::connect();
        
        $stateName = ucwords(str_replace('-', ' ', $slug));
        $state = $db->table('states')->where('name', $stateName)->get()->getRow();
        
        if (!$state) return redirect()->to('/');

        $cityStats = $db->table('cities c')
            ->select('c.name as city_name, COUNT(p.id) as property_count, AVG(p.tax_price) as avg_price')
            ->join('properties p', 'p.city_id = c.id', 'left')
            ->where('c.state_id', $state->id)
            ->groupBy('c.id')
            ->get()->getResult();

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('cities', 'cities.id = properties.city_id')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where("cities.state_id = " . (int)$state->id) 
            ->paginate(20);

        $data = [
            'state' => $state,
            'cityStats' => $cityStats,
            'properties' => $properties,
            'pager' => $propertyModel->pager
        ];

        return view('front/properties/state', $data);
    }

    public function city($slug, $stateSlug)
    {
        $db = \Config\Database::connect();
        
        $cityName = ucwords(str_replace('-', ' ', $slug));
        $city = $db->table('cities')->where('name', $cityName)->get()->getRowArray();
        
        if (!$city) return redirect()->to('/');

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('city_id', $city['id'])
            ->paginate(20);

        $markers = $db->table('properties')
            ->select('id, title, tax_price, latitude, longitude')
            ->where('city_id', $city['id'])
            ->where('latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();

        $data = [
            'city' => $city,
            'properties' => $properties,
            'markers' => $markers,
            'pager' => $propertyModel->pager
        ];

        return view('front/properties/city', $data);
    }

    public function zipcode($zipcode)
    {
        $db = \Config\Database::connect();
        
        $zip = $db->table('zipcodes')->where('zipcode', $zipcode)->get()->getRowArray();
        $zipcodeId = $zip ? $zip['id'] : 0;

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('zipcode_id', $zipcodeId)
            ->paginate(20);

        $markers = $db->table('properties')
            ->select('id, title, tax_price, latitude, longitude')
            ->where('zipcode_id', $zipcodeId)
            ->where('latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();

        $data = [
            'zipcode' => ['zipcode' => $zipcode],
            'properties' => $properties,
            'markers' => $markers,
            'pager' => $propertyModel->pager
        ];

        return view('front/properties/zipcode', $data);
    }

    public function submitInquiry()
    {
        $inquiryModel = new InquiryModel();
        
        // Compile the submitted form data into a readable message thread block
        $compiledMessage = "Inquiry Type: " . $this->request->getPost('source') . "\n";
        $compiledMessage .= "Name: " . $this->request->getPost('name') . "\n";
        $compiledMessage .= "Phone: " . $this->request->getPost('phone') . "\n";
        $compiledMessage .= "Email: " . $this->request->getPost('email') . "\n\n";
        $compiledMessage .= "Message:\n" . $this->request->getPost('message');

        $data = [
            'property_id' => $this->request->getPost('property_id'),
            'sender_id'   => session()->get('id'),
            'receiver_id' => $this->request->getPost('agent_id'),
            'message'     => $compiledMessage,
            'status'      => 'Pending'
        ];

        $inquiryModel->insert($data);
        return redirect()->back()->with('success', 'Your inquiry has been sent successfully! You can track it in your inbox.');
    }
}