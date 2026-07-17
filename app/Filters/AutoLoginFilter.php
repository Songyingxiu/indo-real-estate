<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class AutoLoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('cookie');
        $session = session();

        // Check if the user is NOT logged in, but HAS a remember cookie
        if (!$session->get('isLoggedIn') && get_cookie('remember_token')) {
            $token = get_cookie('remember_token');
            $userModel = new UserModel();
            
            // Look up the user by the token
            $user = $userModel->where('remember_token', $token)->first();

            // If the user exists and is active, automatically recreate their session!
            if ($user && $user['status'] === 'Active') {
                $sessionData = [
                    'id'         => $user['id'],
                    'user_id'    => $user['id'],
                    'role_id'    => $user['role_id'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name'],
                    'email'      => $user['email'],
                    'isLoggedIn' => true
                ];
                $session->set($sessionData);
            } else {
                // If the token is invalid or the account was suspended, destroy the faulty cookie
                delete_cookie('remember_token');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}