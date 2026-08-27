<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\AgentVerificationModel;
use App\Models\SavedPropertyModel;
use App\Models\SavedSearchModel;
use App\Libraries\EmailService;
use Cloudinary\Cloudinary; 

class User extends BaseController
{
    public function profile()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $userModel = new UserModel();
        $data['title'] = 'My Profile - HuniKita';
        $data['user']  = $userModel->find(session()->get('id'));

        $agentVerifyModel = new AgentVerificationModel();
        $data['agentVerification'] = $agentVerifyModel->where('user_id', session()->get('id'))->orderBy('id', 'DESC')->first();

        return view('front/user/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $userId = session()->get('id');
        $userModel = new UserModel();

        $rules = [
            'first_name'   => 'required|min_length[2]',
            'last_name'    => 'required|min_length[2]',
            'phone_number' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please check your inputs and try again.');
        }

        $updateData = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'phone_number' => $this->request->getPost('phone_number')
        ];

        $userModel->update($userId, $updateData);

        session()->set([
            'first_name' => $updateData['first_name'],
            'last_name'  => $updateData['last_name']
        ]);

        return redirect()->to(base_url('user/profile'))->with('success', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $userId = session()->get('id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $hasLocalPassword = !empty($user['password']);

        $rules = [
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if ($hasLocalPassword) {
            $rules['current_password'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Password validation failed. Ensure your new password is at least 8 characters and matches the confirmation.');
        }

        if ($hasLocalPassword && !password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Your current password is incorrect.');
        }

        $userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT)
        ]);

        return redirect()->to(base_url('user/profile'))->with('success', 'Password updated successfully. You can now use this password to log in.');
    }

    public function uploadAgentDocs()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));
        $userId = session()->get('id');

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
                $agentVerifyModel->insert($updateData);
            }
            return redirect()->back()->with('success', 'Verification documents uploaded! Please wait for Admin review.');
        }

        return redirect()->back()->with('error', 'KTP document is required for verification.');
    }

    public function savedProperties()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $savedModel = new SavedPropertyModel();
        $searchModel = new SavedSearchModel();
        
        $data['title'] = 'My Saved Properties - HuniKita';
        
        $data['properties'] = $savedModel
            ->select('properties.*, property_types.name as type_name, property_images.image_path, saved_properties.created_at as saved_at')
            ->join('properties', 'properties.id = saved_properties.property_id', 'inner')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('saved_properties.user_id', session()->get('id'))
            ->orderBy('saved_properties.created_at', 'DESC')
            ->paginate(9);

        $data['pager'] = $savedModel->pager;
        
        $data['searches'] = $searchModel->where('user_id', session()->get('id'))
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();

        return view('front/user/saved_properties', $data);
    }

    public function deleteSearch($id)
    {
        if (!session()->get('id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $searchModel = new SavedSearchModel();
        $search = $searchModel->find($id);

        if ($search && (is_array($search) ? $search['user_id'] : $search->user_id) == session()->get('id')) {
            $searchModel->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Search removed successfully.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Search not found or you do not have permission to delete it.'])->setStatusCode(404);
    }

    public function deleteAccount()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $userId = session()->get('id');
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
            
            return redirect()->to(base_url('/'))->with('success', 'Your account and active listings have been hidden. You have 60 days to restore them by logging back in.');
        }

        return redirect()->back()->with('error', 'We encountered an issue deleting your account. Please try again.');
    }
}