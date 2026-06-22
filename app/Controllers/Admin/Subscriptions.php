<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Subscriptions extends BaseController {
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        return view('admin/subscriptions');
    }
}