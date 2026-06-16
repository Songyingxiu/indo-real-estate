<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'User Management'
        ];
        
        return view('admin/users/user', $data);
    }

    public function create()
    {
        $data = ['title' => 'Create New User - EstateAdmin Pro'];
        return view('admin/users/create', $data);
    }
}