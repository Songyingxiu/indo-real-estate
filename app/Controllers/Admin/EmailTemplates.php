<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class EmailTemplates extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Email Templates - EstateAdmin Pro'];
        return view('admin/email_templates', $data);
    }
}