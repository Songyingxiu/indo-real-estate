<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;

class Profile extends BaseController 
{
    public function index() 
    {
        // Make sure they are logged in
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to(base_url('login'));
        
        $userModel = new UserModel();
        // Fetch the freshest data straight from the database
        $data['user'] = $userModel->find($userId);
        
        // Fetch Subscription Data
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        
        // Get the most recent active subscription for this user
        $activeSub = $subModel->where('user_id', $userId)
                              ->where('sub_status', 'Active')
                              ->orderBy('id', 'DESC')
                              ->first();
                              
        $data['activeSubscription'] = $activeSub;
        $data['activePlan'] = null;
        
        // If they have an active subscription, fetch the plan details (like name and price)
        if ($activeSub) {
            $data['activePlan'] = $planModel->find($activeSub->plan_id);
        }
        
        return view('admin/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to(base_url('login'));

        $userModel = new UserModel();
        
        $userModel->update($userId, [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'email'        => $this->request->getPost('email')
        ]);

        // Update the active session variables so the header updates instantly
        session()->set([
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email')
        ]);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to(base_url('login'));

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Security Check 1: Do the new passwords match?
        if ($newPassword !== $confirmPassword) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'New passwords do not match.');
        }

        // Security Check 2: Is the new password long enough?
        if (strlen($newPassword) < 8) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'Password must be at least 8 characters long.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Security Check 3: Is their current password correct?
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'Current password is incorrect.');
        }

        // Hash the new password and save it
        $userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Password updated successfully.');
    }
}