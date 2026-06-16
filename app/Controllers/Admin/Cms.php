<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Cms extends BaseController
{
    public function index()
    {
        $data = ['title' => 'CMS Management - EstateAdmin Pro'];
        return view('admin/cms/cms', $data);
    }
}