<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        $data = ['title' => 'Sign In - EstateAdmin Pro'];
        return view('auth/login', $data);
    }

    public function attemptRegister()
    {
        return redirect()->to(base_url('admin/dashboard'));
    }
    
    // We will build the logic for this after the database is approved!
    public function attemptLogin()
    {
        return redirect()->to(base_url('admin/dashboard'));
    }
    
    public function logout()
    {
        return redirect()->to(base_url('login'));
    }
}