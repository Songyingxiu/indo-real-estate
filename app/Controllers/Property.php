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
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'phone'   => 'required|min_length[8]|max_length[20]',
            'email'   => 'required|valid_email',
            'message' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'validation_error', 
                'errors' => $this->validator->getErrors()
            ]);
        }

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
        $parentId = $inquiryModel->getInsertID();

        // Check Subscription to Trigger Auto-Reply
        $subModel = new \App\Models\SubscriptionModel();
        $planModel = new \App\Models\SubscriptionPlanModel();
        $agentSub = $subModel->where('user_id', $receiverId)->where('sub_status', 'Active')->first();
        
        $allowMessages = false;
        if ($agentSub) {
            $planId = is_array($agentSub) ? $agentSub['plan_id'] : $agentSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $allowMessages = is_array($plan) ? $plan['allow_messages'] : $plan->allow_messages;
            }
        }
        
        $agent = $userModel->find($receiverId);
        $agentRole = is_array($agent) ? $agent['role_id'] : $agent->role_id;
        if ($agentRole == 4) {
            $allowMessages = true;
        }

        if (!$allowMessages) {
            $autoReply = [
                'parent_id'   => $parentId,
                'property_id' => $propertyId,
                'sender_id'   => $receiverId,
                'receiver_id' => session()->get('id') ?? null,
                'message'     => "System Auto-Reply: Thank you for your inquiry! My current plan does not support live chat, but I have received your message and will contact you shortly via the email or phone number you provided.",
                'status'      => 'Replied'
            ];
            $inquiryModel->insert($autoReply);
            $inquiryModel->update($parentId, ['status' => 'Replied']);
        }

        $emailService->sendDynamicEmail('New Inquiry Customer', $customerEmail, [
            '{first_name}' => $customerName,
            '{property_id}' => $propertyId
        ]);

        if ($agent) {
            $agentEmail = is_array($agent) ? $agent['email'] : $agent->email;
            $emailService->sendDynamicEmail('New Inquiry Agent', $agentEmail, [
                '{property_id}' => $propertyId
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Inquiry submitted successfully.']);
    }
}