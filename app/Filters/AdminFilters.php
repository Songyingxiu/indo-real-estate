<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is logged in at all
        if (! session()->get('isLoggedIn')) {
            // Kick them back to the login page with an error
            return redirect()->to(base_url('login'))->with('error', 'You must be logged in to access the dashboard.');
        }

        // Role-Based Access Control (RBAC)
        $userRole = session()->get('role');
        $allowedRoles = ['admin', 'owner', 'agent']; // Define who gets access

        // checking role
        if (! in_array($userRole, $allowedRoles)) {
            return redirect()->to(base_url('/'))->with('error', 'You do not have permission to access this area.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}