<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Leads extends BaseController {
    public function index() {
        return view('admin/leads');
    }
}