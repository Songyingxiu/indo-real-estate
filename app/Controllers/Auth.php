<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

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
            'first_name'       => 'required|min_length[2]|max_length[100]',
            'last_name'        => 'required|min_length[2]|max_length[100]',
            // Ensure email is unique in the users table to prevent database errors
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone_number'     => 'required|min_length[8]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hash the password securely using PHP's built-in BCRYPT
        $plaintextPassword = $this->request->getPost('password');
        $hashedPassword = password_hash($plaintextPassword, PASSWORD_BCRYPT);

        // Map the string role from the form to the new integer role_ids in your DB
        $roleMap = [
            'buyer' => 1,
            'owner' => 2,
            'agent' => 3
        ];
        $selectedRole = $this->request->getPost('role');
        $roleId = $roleMap[$selectedRole] ?? 1; // Default to buyer

        // Prepare the data array for the database
        $userData = [
            'role_id'      => $roleId,
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'password'     => $hashedPassword,
            'status'       => 'Active'
            // 'created_date' is handled automatically by the Model's useTimestamps
        ];

        // Insert into the database
        $userModel = new UserModel();
        $userModel->insert($userData);

        // Redirect to login page with a success message
        return redirect()->to(base_url('login'))->with('success', 'Account created successfully! Please sign in.');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Verify the user exists and the password matches
        if ($user && password_verify($password, $user['password'])) {
            
            // Prevent inactive or suspended users from logging in
            if ($user['status'] !== 'Active') {
                return redirect()->back()->withInput()->with('error', 'Your account is currently inactive. Please contact support.');
            }

            // Set User Session
            $sessionData = [
                'user_id'    => $user['id'],
                'role_id'    => $user['role_id'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            return redirect()->to(base_url('admin/dashboard'));
            
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid Email or Password');
        }
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }
}