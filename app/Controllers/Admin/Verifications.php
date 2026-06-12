<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Verifications extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Verification Center'];
        return view('admin/verifications', $data);
    }
}