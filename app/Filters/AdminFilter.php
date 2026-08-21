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
            return redirect()->to(base_url('login'))->with('error', 'You must be logged in to access the dashboard.');
        }

        // Role-Based Access Control (RBAC)
        // We use role_id numbers based on the MasterSeeder:
        // 1 = Standard User (Buyer/Owner)
        // 2 = Owner (Legacy)
        // 3 = Agent
        // 4 = Admin
        
        $userRoleId = session()->get('role_id');
        
        // Define who gets access to the admin panel. 
        // Role 1 is now explicitly allowed since all users can post properties.
        $allowedRoles = [1, 2, 3, 4]; 

        // Kick out unauthorized users and destroy their session
        if (! in_array($userRoleId, $allowedRoles)) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Your account type does not have dashboard access.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}