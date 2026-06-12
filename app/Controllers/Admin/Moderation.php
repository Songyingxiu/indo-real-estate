<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Moderation extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Property Moderation'
        ];
        
        return view('admin/moderation', $data); 
    }
}