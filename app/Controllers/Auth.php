<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptRegister()
    {
        // Validate the incoming form data
        $rules = [
            'role'             => 'required|in_list[buyer,owner,agent]',
            'fullname'         => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email',
            'phone'            => 'required|min_length[8]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hash the password securely using PHP's built-in BCRYPT
        $plaintextPassword = $this->request->getPost('password');
        $hashedPassword = password_hash($plaintextPassword, PASSWORD_BCRYPT);

        // Prepare the data array for the database
        $userData = [
            'role'       => $this->request->getPost('role'),
            'fullname'   => $this->request->getPost('fullname'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'password'   => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        ];

        /* 
         * TODO: UNCOMMENT WHEN DATABASE IS APPROVED
         * $userModel = new \App\Models\UserModel();
         * $userModel->insert($userData);
         */

        // Redirect to dashboard for now to simulate success
        return redirect()->to(base_url('admin/dashboard'))->with('success', 'Account created successfully!');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        /* 
         * TODO: UNCOMMENT WHEN DATABASE IS APPROVED
         * $userModel = new \App\Models\UserModel();
         * $user = $userModel->where('email', $email)->first();
         *
         * // Verify the hashed password against the database
         * if ($user && password_verify($password, $user['password'])) {
         *     // Set User Session Here
         *     return redirect()->to(base_url('admin/dashboard'));
         * } else {
         *     return redirect()->back()->with('error', 'Invalid Email or Password');
         * }
         */

        // For now, bypass the database check and just log them in so you can test the UI!
        return redirect()->to(base_url('admin/dashboard'));
    }

    public function logout()
    {
        // TODO: Destroy session when database/sessions are active
        // session()->destroy();
        return redirect()->to(base_url('login'));
    }
}