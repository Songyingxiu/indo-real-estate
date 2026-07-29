<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\AgentVerificationModel;

class Profile extends BaseController 
{
    public function index() 
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to(base_url('login'));
        
        $userModel = new UserModel();
        $data['user'] = $userModel->find($userId);
        
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        
        $activeSub = $subModel->where('user_id', $userId)
                              ->where('sub_status', 'Active')
                              ->orderBy('id', 'DESC')
                              ->first();
                              
        $data['activeSubscription'] = $activeSub;
        $data['activePlan'] = null;
        
        if ($activeSub) {
            $data['activePlan'] = $planModel->find($activeSub->plan_id);
        }

        // Fetch Agent Verification Status
        $agentVerifyModel = new AgentVerificationModel();
        $data['agentVerification'] = $agentVerifyModel->where('user_id', $userId)->orderBy('id', 'DESC')->first();
        
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

        if ($newPassword !== $confirmPassword) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'New passwords do not match.');
        }

        if (strlen($newPassword) < 8) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'Password must be at least 8 characters long.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'Current password is incorrect.');
        }

        $userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Password updated successfully.');
    }
}