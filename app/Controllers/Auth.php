<?php namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SubscriptionModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptRegister()
    {
        $rules = [
            'role'             => 'required|in_list[buyer,owner,agent]',
            'first_name'       => 'required|min_length[2]|max_length[100]',
            'last_name'        => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone_number'     => 'required|min_length[8]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMessage = 'Please fix the following errors:<br> • ' . implode('<br> • ', $errors);
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $plaintextPassword = $this->request->getPost('password');
        $hashedPassword = password_hash($plaintextPassword, PASSWORD_BCRYPT);

        $roleMap = [
            'buyer' => 1,
            'owner' => 2,
            'agent' => 3
        ];
        $selectedRole = $this->request->getPost('role');
        $roleId = $roleMap[$selectedRole] ?? 1; 

        $userData = [
            'role_id'      => $roleId,
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'password'     => $hashedPassword,
            'status'       => 'Active'
        ];

        $userModel = new UserModel();
        $userModel->insert($userData);

        return redirect()->to(base_url('login'))->with('success', 'Account created successfully! Please sign in.');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            
            if ($user['status'] !== 'Active') {
                return redirect()->back()->withInput()->with('error', 'Your account is currently suspended. Please contact support.');
            }

            // REMEMBER ME LOGIC
            helper('cookie');
            if ($this->request->getPost('remember')) {
                $token = bin2hex(random_bytes(32));
                $userModel->update($user['id'], ['remember_token' => $token]);
                set_cookie('remember_token', $token, 30 * 24 * 60 * 60);
            }

            $subModel = new SubscriptionModel();
            $activeSub = $subModel->where('user_id', $user['id'])
                                  ->where('status', 'Active')
                                  ->orderBy('id', 'DESC')
                                  ->first();
            $planId = $activeSub ? $activeSub->plan_id : 1; 

            $sessionData = [
                'id'         => $user['id'],
                'user_id'    => $user['id'],
                'role_id'    => $user['role_id'],
                'plan_id'    => $planId,
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            if ($user['role_id'] == 1) {
                return redirect()->to(base_url('/'));
            } else {
                return redirect()->to(base_url('admin/dashboard'));
            }
            
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid Email or Password.');
        }
    }

    public function logout()
    {
        helper('cookie');
        $token = get_cookie('remember_token');
        if ($token) {
            $userModel = new UserModel();
            $userModel->where('remember_token', $token)->set(['remember_token' => null])->update();
            delete_cookie('remember_token');
        }

        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }
}