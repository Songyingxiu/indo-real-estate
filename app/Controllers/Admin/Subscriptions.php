<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Subscriptions extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Subscription Management - EstateAdmin Pro'
        ];
        
        return view('admin/subscriptions', $data); 
    }
}