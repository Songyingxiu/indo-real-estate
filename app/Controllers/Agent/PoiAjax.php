<?php namespace App\Controllers\Agent;

use App\Controllers\BaseController;
use App\Models\PoiModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;

class PoiAjax extends BaseController
{
    public function store()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Unauthorized. Please log in.'
            ])->setStatusCode(401);
        }

        $json = $this->request->getJSON();

        if (empty($json->name) || empty($json->category) || empty($json->latitude) || empty($json->longitude)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'All fields are required.'
            ])->setStatusCode(400);
        }

        // Backend Security: Check if this POI already exists globally to avoid duplicates
        $poiModel = new PoiModel();
        $existingPoi = $poiModel->where('name', $json->name)
            ->where('latitude', $json->latitude)
            ->where('longitude', $json->longitude)
            ->first();

        if ($existingPoi) {
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Existing Point of Interest linked successfully!'
            ]);
        }

        // Backend Security: Check user subscription limits again before inserting
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        
        $activeSub = $subModel->where('user_id', $userId)->where('sub_status', 'Active')->first();
        $maxPois = 0;
        
        if ($activeSub) {
            $planId = is_array($activeSub) ? $activeSub['plan_id'] : $activeSub->plan_id;
            $plan = $planModel->find($planId);
            if ($plan) {
                $maxPois = is_array($plan) ? ($plan['max_pois'] ?? 0) : ($plan->max_pois ?? 0);
            }
        }

        if (session()->get('role_id') == 4) {
            $maxPois = 9999;
        }

        $poisCreated = $poiModel->where('added_by', $userId)->countAllResults();

        if ($poisCreated >= $maxPois) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'POI limit reached for your current subscription plan.'
            ])->setStatusCode(403);
        }

        $poiModel->insert([
            'name'      => $json->name,
            'category'  => $json->category,
            'latitude'  => $json->latitude,
            'longitude' => $json->longitude,
            'status'    => 'Active',
            'added_by'  => $userId
        ]);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Point of Interest added successfully!'
        ]);
    }
}