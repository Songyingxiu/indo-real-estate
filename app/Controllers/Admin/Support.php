<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Support extends BaseController 
{
    public function index() 
    {
        if (session()->get('role_id') == null) return redirect()->to(base_url('login'));
        return view('admin/support');
    }
}