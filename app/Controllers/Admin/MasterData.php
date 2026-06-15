<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MasterData extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Master Data Configuration - EstateAdmin Pro'];
        return view('admin/master_data', $data);
    }
}