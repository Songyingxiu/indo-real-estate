<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SuperAdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is logged in
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'You must be logged in to access this area.');
        }

        $userRoleId = session()->get('role_id');
        
        // STRICT CHECK: ONLY Admin (4) is allowed
        if ($userRoleId != 4) {
            // Redirect Agents and Owners back to their safe dashboard
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access. Super Administrator privileges required.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}