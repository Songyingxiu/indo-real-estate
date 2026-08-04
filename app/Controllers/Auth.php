<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SubscriptionModel;
use App\Libraries\EmailService;

class Auth extends BaseController
{
    /**
     * Evaluates soft-deleted accounts for 60-day expiration.
     * Restores the account if <= 60 days. Hard wipes data if > 60 days.
     */
    private function processAccountRecovery(&$user, UserModel $userModel)
    {
        if ($user && $user['deleted_at'] !== null) {
            $deletedTime = strtotime($user['deleted_at']);
            $daysPassed  = (time() - $deletedTime) / 86400; // 86400 seconds = 1 day

            if ($daysPassed > 60) {
                // 1. Account expired. Hard-delete related data first to prevent orphans.
                $db = \Config\Database::connect();
                
                // Note: Ensure these table names match your database exactly
                $db->table('inquiries')->where('user_id', $user['id'])->delete();
                $db->table('favorites')->where('user_id', $user['id'])->delete();
                
                // 2. Hard delete the user (the true parameter bypasses soft-deletes)
                $userModel->delete($user['id'], true);
                
                // 3. Reset user to null so the system treats this as a brand new account
                $user = null;
            } else {
                // Account is within 60 days. Restore it!
                $userModel->builder()->where('id', $user['id'])->update([
                    'deleted_at' => null,
                    'status'     => 'Active'
                ]);
                
                // Re-fetch the clean record
                $user = $userModel->where('id', $user['id'])->first();
            }
        }
    }

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

        $email = $this->request->getPost('email');
        $firstName = $this->request->getPost('first_name');

        $userData = [
            'role_id'      => $roleId,
            'first_name'   => $firstName,
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $email,
            'phone_number' => $this->request->getPost('phone_number'),
            'password'     => $hashedPassword,
            'status'       => 'Active',
            'auth_provider'=> 'local'
        ];

        $userModel = new UserModel();
        $userModel->insert($userData);

        $emailService = new EmailService();
        $emailService->sendDynamicEmail('User Sign Up', $email, [
            '{first_name}' => $firstName,
            '{login_link}' => base_url('login') 
        ]);

        return redirect()->to('/login')->with('success', 'Account created successfully! Please sign in.');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        
        // Fetch user, including soft-deleted ones, to process potential account recovery
        $user = $userModel->withDeleted()->where('email', $email)->first();
        
        // Run the 60-day expiration check
        $this->processAccountRecovery($user, $userModel);

        if ($user && $user['auth_provider'] === 'google' && empty($user['password'])) {
             return redirect()->back()->withInput()->with('error', 'This account uses Google Sign-In. Please click "Continue with Google".');
        }

        if ($user && password_verify($password, $user['password'])) {
            
            if ($user['status'] !== 'Active') {
                return redirect()->back()->withInput()->with('error', 'Your account is currently suspended. Please contact support.');
            }

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

            return redirect()->to($user['role_id'] == 1 ? '/' : '/admin/dashboard');
            
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
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function attemptForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please enter a valid email address.');
        }

        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $userModel->update($user['id'], [
                'reset_token'      => $token,
                'reset_expires_at' => $expiresAt
            ]);

            $resetLink = base_url('reset-password/' . $token);

            $emailService = new EmailService();
            $emailService->sendDynamicEmail('Forgot Password', $user['email'], [
                '{first_name}' => $user['first_name'],
                '{reset_link}' => $resetLink 
            ]);
        }

        return redirect()->to('/login')->with('success', 'If an account exists with that email, a password reset link has been sent.');
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Invalid password reset token.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            return redirect()->to('/login')->with('error', 'This password reset token is invalid or has expired.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function attemptResetPassword()
    {
        $rules = [
            'token'            => 'required',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMessage = implode('<br> • ', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $token = $this->request->getPost('token');
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            return redirect()->to('/login')->with('error', 'Token expired or invalid.');
        }

        $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);

        $userModel->update($user['id'], [
            'password'         => $hashedPassword,
            'reset_token'      => null,
            'reset_expires_at' => null
        ]);

        return redirect()->to('/login')->with('success', 'Password reset successfully! You may now sign in.');
    }

    public function googleLogin()
    {
        $json = $this->request->getJSON();
        if (!$json) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request data.']);
        }

        $email = $json->email ?? '';
        $googleId = $json->uid ?? '';
        $displayName = $json->displayName ?? '';
        
        if (empty($email) || empty($googleId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing Google authentication data.']);
        }

        $userModel = new UserModel();
        
        // Fetch user, including soft-deleted ones, to process potential account recovery
        $user = $userModel->withDeleted()->where('email', $email)->first();

        // Run the 60-day expiration check
        $this->processAccountRecovery($user, $userModel);

        if ($user) {
            if (empty($user['google_id'])) {
                $userModel->update($user['id'], ['google_id' => $googleId, 'auth_provider' => 'google']);
            }
            
            if ($user['status'] !== 'Active') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Your account is currently suspended.']);
            }
        } else {
            // New user registration (or registering after a 60-day hard wipe)
            $nameParts = explode(' ', $displayName, 2);
            $firstName = $nameParts[0] ?? 'Google';
            $lastName = $nameParts[1] ?? 'User';

            $userData = [
                'role_id'       => 1, 
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email,
                'phone_number'  => null,
                'password'      => null, 
                'status'        => 'Active',
                'auth_provider' => 'google',
                'google_id'     => $googleId
            ];

            $userModel->insert($userData);
            $user = $userModel->where('email', $email)->first();
            
            $emailService = new EmailService();
            $emailService->sendDynamicEmail('User Sign Up', $email, [
                '{first_name}' => $firstName,
                '{login_link}' => base_url('login')
            ]);
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

        return $this->response->setJSON([
            'status' => 'success', 
            'redirect' => $user['role_id'] == 1 ? '/' : '/admin/dashboard'
        ]);
    }
}