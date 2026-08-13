<?php namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\InquiryModel;
use App\Models\UserModel;
use App\Libraries\EmailService;

class Property extends BaseController
{
    public function province($slug, $listingType = 'sale')
    {
        $db = \Config\Database::connect();
        $type = ucfirst(strtolower($listingType));
        
        $stateName = ucwords(str_replace('-', ' ', $slug));
        $state = $db->table('states')->where('name', $stateName)->get()->getRow();
        
        if (!$state) return redirect()->to('/');

        $cityStats = $db->table('cities c')
            ->select('c.name as city_name, COUNT(p.id) as property_count, AVG(p.tax_price) as avg_price')
            ->join('properties p', "p.city_id = c.id AND p.listing_type = '{$type}'", 'left')
            ->where('c.state_id', $state->id)
            ->groupBy('c.id')
            ->get()->getResult();

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('cities', 'cities.id = properties.city_id')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where("cities.state_id = " . (int)$state->id) 
            ->where('properties.listing_type', $type)
            ->asArray()
            ->paginate(20);

        $data = [
            'state' => $state,
            'cityStats' => $cityStats,
            'properties' => $properties,
            'pager' => $propertyModel->pager,
            'currentType' => $type
        ];

        return view('front/properties/state', $data);
    }

    public function city($slug, $stateSlug, $listingType = 'sale')
    {
        $db = \Config\Database::connect();
        $type = ucfirst(strtolower($listingType));
        
        $cityName = ucwords(str_replace('-', ' ', $slug));
        $city = $db->table('cities')->where('name', $cityName)->get()->getRowArray();
        
        if (!$city) return redirect()->to('/');

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('city_id', $city['id'])
            ->where('properties.listing_type', $type)
            ->asArray()
            ->paginate(20);

        $markers = $db->table('properties')
            ->select('id, title, tax_price, latitude, longitude')
            ->where('city_id', $city['id'])
            ->where('listing_type', $type)
            ->where('latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();

        $data = [
            'city' => $city,
            'properties' => $properties,
            'markers' => $markers,
            'pager' => $propertyModel->pager,
            'currentType' => $type
        ];

        return view('front/properties/city', $data);
    }

    public function zipcode($zipcode, $listingType = 'sale')
    {
        $db = \Config\Database::connect();
        $type = ucfirst(strtolower($listingType));
        
        $zip = $db->table('zipcodes')->where('zipcode', $zipcode)->get()->getRowArray();
        $zipcodeId = $zip ? $zip['id'] : 0;

        $propertyModel = new PropertyModel();
        
        $properties = $propertyModel->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('zipcode_id', $zipcodeId)
            ->where('properties.listing_type', $type)
            ->asArray()
            ->paginate(20);

        $markers = $db->table('properties')
            ->select('id, title, tax_price, latitude, longitude')
            ->where('zipcode_id', $zipcodeId)
            ->where('listing_type', $type)
            ->where('latitude IS NOT NULL')
            ->limit(150)
            ->get()->getResultArray();

        $data = [
            'zipcode' => ['zipcode' => $zipcode],
            'properties' => $properties,
            'markers' => $markers,
            'pager' => $propertyModel->pager,
            'currentType' => $type
        ];

        return view('front/properties/zipcode', $data);
    }

    public function submitInquiry()
    {
        $inquiryModel = new InquiryModel();
        $userModel = new UserModel();
        $emailService = new EmailService();
        
        $propertyId = $this->request->getPost('property_id');
        $receiverId = $this->request->getPost('agent_id');
        $customerEmail = $this->request->getPost('email');
        $customerName = $this->request->getPost('name');

        $compiledMessage = "Inquiry Type: " . $this->request->getPost('source') . "\n";
        $compiledMessage .= "Name: " . $customerName . "\n";
        $compiledMessage .= "Phone: " . $this->request->getPost('phone') . "\n";
        $compiledMessage .= "Email: " . $customerEmail . "\n\n";
        $compiledMessage .= "Message:\n" . $this->request->getPost('message');

        $data = [
            'property_id' => $propertyId,
            'sender_id'   => session()->get('id') ?? null,
            'receiver_id' => $receiverId,
            'message'     => $compiledMessage,
            'status'      => 'Pending'
        ];

        $inquiryModel->insert($data);

        $emailService->sendDynamicEmail('New Inquiry Customer', $customerEmail, [
            '{first_name}' => $customerName,
            '{property_id}' => $propertyId
        ]);

        $agent = $userModel->find($receiverId);
        if ($agent) {
            $emailService->sendDynamicEmail('New Inquiry Agent', $agent['email'], [
                '{property_id}' => $propertyId
            ]);
        }

        return redirect()->back()->with('success', 'Your inquiry has been sent successfully! You can track it in your inbox.');
    }
}