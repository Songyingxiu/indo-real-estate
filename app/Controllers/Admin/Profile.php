<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\AgentVerificationModel;
use App\Libraries\EmailService;
use Cloudinary\Cloudinary;

class Profile extends BaseController 
{
    public function index() 
    {
        $userId = session()->get('user_id') ?? session()->get('id');
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
            $data['activePlan'] = $planModel->find(is_object($activeSub) ? $activeSub->plan_id : $activeSub['plan_id']);
        }

        $agentVerifyModel = new AgentVerificationModel();
        $data['agentVerification'] = $agentVerifyModel->where('user_id', $userId)->orderBy('id', 'DESC')->first();
        
        return view('admin/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
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
        $userId = session()->get('user_id') ?? session()->get('id');
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

        $hasLocalPassword = !empty($user['password']);

        if ($hasLocalPassword && !password_verify($currentPassword, $user['password'])) {
            return redirect()->to(base_url('admin/profile'))->with('error', 'Current password is incorrect.');
        }

        $userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->to(base_url('admin/profile'))->with('success', 'Password updated successfully. You can now use this password to log in.');
    }

    public function uploadDocs()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) return redirect()->to(base_url('login'));

        $agentVerifyModel = new AgentVerificationModel();
        
        $existingVerification = $agentVerifyModel->where('user_id', $userId)->first();
        $isRejected = false;
        $existingId = null;

        if ($existingVerification) {
            $status = is_object($existingVerification) ? $existingVerification->approval_status : $existingVerification['approval_status'];
            
            if ($status !== 'Rejected') {
                return redirect()->back()->with('error', 'You have already submitted a verification document.');
            }
            
            $isRejected = true;
            $existingId = is_object($existingVerification) ? $existingVerification->id : $existingVerification['id'];
        }

        $rules = [
            'ktp_document' => 'uploaded[ktp_document]|ext_in[ktp_document,pdf,jpg,jpeg,png]|max_size[ktp_document,5120]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid file. Please upload an image or PDF under 5MB.');
        }

        $file = $this->request->getFile('ktp_document');
        
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
            
            if (!empty($cloudinaryUrl)) {
                $cloudinary = new Cloudinary($cloudinaryUrl);
                $response = $cloudinary->uploadApi()->upload($file->getTempName(), [
                    'folder' => 'hunikita_documents',
                ]);
                
                if ($isRejected && $existingId) {
                    $agentVerifyModel->update($existingId, [
                        'ktp_document'    => $response['secure_url'], 
                        'approval_status' => 'Pending'
                    ]);
                } else {
                    $agentVerifyModel->builder()->insert([
                        'user_id'         => $userId,
                        'ktp_document'    => $response['secure_url'], 
                        'approval_status' => 'Pending',
                        'status'          => 'Active'
                    ]);
                }

                return redirect()->back()->with('success', 'Your identity document has been submitted and is pending verification.');
            }
        }

        return redirect()->back()->with('error', 'Failed to upload document.');
    }

    public function deleteAccount()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) return redirect()->to(base_url('login'));

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if ($user) {
            $propertyModel = new PropertyModel();
            $propertyModel->where('owner_id', $userId)->delete();

            $emailService = new EmailService();
            $emailService->sendDynamicEmail(
                'Account Deleted',
                $user['email'],
                ['{first_name}' => $user['first_name']]
            );

            $userModel->delete($userId);
            session()->destroy();
            
            return redirect()->to(base_url('/'))->with('success', 'Your agent/owner account and active listings have been hidden. You have 60 days to restore them by logging back in.');
        }

        return redirect()->back()->with('error', 'We encountered an issue deleting your account. Please try again.');
    }
}