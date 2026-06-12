<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Leads extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Lead Management'];
        return view('admin/leads', $data);
    }
}