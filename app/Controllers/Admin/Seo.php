<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Seo extends BaseController
{
    public function index()
    {
        $data = ['title' => 'SEO Management - EstateAdmin Pro'];
        return view('admin/seo', $data);
    }
}