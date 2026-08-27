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
            $planId = is_object($activeSub) ? $activeSub->plan_id : $activeSub['plan_id'];
            $data['activePlan'] = $planModel->find($planId);
        }

        $agentVerifyModel = new AgentVerificationModel();
        $data['agentVerification'] = $agentVerifyModel->where('user_id', $userId)->orderBy('id', 'DESC')->first();
        
        return view('admin/profile', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) return redirect()->to(base_url('login'));

        $rules = [
            'first_name'   => 'required|min_length[2]',
            'last_name'    => 'required|min_length[2]',
            'phone_number' => 'required|min_length[8]',
            'email'        => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $hasLocalPassword = !empty(is_object($user) ? $user->password : $user['password']);

        $rules = [
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if ($hasLocalPassword) {
            $rules['current_password'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $userPass = is_object($user) ? $user->password : $user['password'];

        if ($hasLocalPassword && !password_verify($currentPassword, $userPass)) {
            return redirect()->back()->withInput()->with('errors', ['current_password' => 'Current password is incorrect.']);
        }

        $userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
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
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
        if (empty($cloudinaryUrl)) {
            return redirect()->back()->with('error', 'Cloudinary configuration is missing.');
        }
        $cloudinary = new Cloudinary($cloudinaryUrl);

        $ktpUrl = null; $npwpUrl = null; $licenseUrl = null;

        if ($ktpFile = $this->request->getFile('ktp_document')) {
            if ($ktpFile->isValid() && !$ktpFile->hasMoved()) {
                $resp = $cloudinary->uploadApi()->upload($ktpFile->getTempName(), ['folder' => 'hunikita_documents']);
                $ktpUrl = $resp['secure_url'];
            }
        }
        if ($npwpFile = $this->request->getFile('npwp')) {
            if ($npwpFile->isValid() && !$npwpFile->hasMoved()) {
                $resp = $cloudinary->uploadApi()->upload($npwpFile->getTempName(), ['folder' => 'hunikita_documents']);
                $npwpUrl = $resp['secure_url'];
            }
        }
        if ($licenseFile = $this->request->getFile('business_license')) {
            if ($licenseFile->isValid() && !$licenseFile->hasMoved()) {
                $resp = $cloudinary->uploadApi()->upload($licenseFile->getTempName(), ['folder' => 'hunikita_documents']);
                $licenseUrl = $resp['secure_url'];
            }
        }

        if ($ktpUrl) {
            $updateData = [
                'ktp_document'    => $ktpUrl, 
                'approval_status' => 'Pending'
            ];
            if ($npwpUrl) $updateData['npwp'] = $npwpUrl;
            if ($licenseUrl) $updateData['business_license'] = $licenseUrl;

            if ($isRejected && $existingId) {
                $agentVerifyModel->update($existingId, $updateData);
            } else {
                $updateData['user_id'] = $userId;
                $updateData['status']  = 'Active';
                $agentVerifyModel->builder()->insert($updateData);
            }

            return redirect()->back()->with('success', 'Your identity documents have been submitted and are pending verification.');
        }

        return redirect()->back()->with('error', 'Failed to upload KTP document.');
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

            $userEmail = is_object($user) ? $user->email : $user['email'];
            $userName = is_object($user) ? $user->first_name : $user['first_name'];

            $emailService = new EmailService();
            $emailService->sendDynamicEmail(
                'Account Deleted',
                $userEmail,
                ['{first_name}' => $userName]
            );

            $userModel->delete($userId);
            session()->destroy();
            
            return redirect()->to(base_url('/'))->with('success', 'Your account and active listings have been hidden. You have 60 days to restore them by logging back in.');
        }

        return redirect()->back()->with('error', 'We encountered an issue deleting your account. Please try again.');
    }
}