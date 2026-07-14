<?php namespace App\Controllers;

use App\Models\LeadModel;
use App\Models\UserModel;
use App\Models\AgentVerificationModel;

class User extends BaseController
{
    public function inbox()
    {
        // Enforce role security: Only Buyers (Role 1) should access this specific frontend inbox
        if (session()->get('role_id') != 1) {
            return redirect()->to(base_url('login'))->with('error', 'Please log in as a user to view your inbox.');
        }

        $leadModel = new LeadModel();
        
        $data['title'] = 'My Inbox - HuniKita';
        
        $data['leads'] = $leadModel
            ->select('leads.*, properties.title as property_title, properties.address_line_1')
            ->join('properties', 'properties.id = leads.property_id', 'left')
            ->where('leads.buyer_id', session()->get('id'))
            ->orderBy('leads.created_date', 'DESC')
            ->paginate(10);
            
        $data['pager'] = $leadModel->pager;

        return view('front/user/inbox', $data);
    }

    public function profile()
    {
        // Allowed for ALL logged in users (Buyers, Owners, Agents)
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $userModel = new UserModel();
        $data['title'] = 'My Profile - HuniKita';
        $data['user']  = $userModel->find(session()->get('id'));

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

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Password validation failed. Ensure your new password is at least 8 characters and matches the confirmation.');
        }

        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Your current password is incorrect.');
        }

        $userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT)
        ]);

        return redirect()->to(base_url('user/profile'))->with('success', 'Password updated successfully.');
    }

    public function uploadAgentDocs()
    {
        if (!session()->get('id')) return redirect()->to(base_url('login'));

        $agentVerifyModel = new AgentVerificationModel();
        
        $ktpName = null; $npwpName = null; $licenseName = null;

        if ($ktpFile = $this->request->getFile('ktp_document')) {
            if ($ktpFile->isValid() && !$ktpFile->hasMoved()) {
                $ktpName = $ktpFile->getRandomName();
                $ktpFile->move(FCPATH . 'uploads/documents', $ktpName);
            }
        }
        if ($npwpFile = $this->request->getFile('npwp')) {
            if ($npwpFile->isValid() && !$npwpFile->hasMoved()) {
                $npwpName = $npwpFile->getRandomName();
                $npwpFile->move(FCPATH . 'uploads/documents', $npwpName);
            }
        }
        if ($licenseFile = $this->request->getFile('business_license')) {
            if ($licenseFile->isValid() && !$licenseFile->hasMoved()) {
                $licenseName = $licenseFile->getRandomName();
                $licenseFile->move(FCPATH . 'uploads/documents', $licenseName);
            }
        }

        if ($ktpName) {
            $agentVerifyModel->insert([
                'user_id'          => session()->get('id'),
                'ktp_document'     => $ktpName,
                'npwp'             => $npwpName,
                'business_license' => $licenseName,
                'approval_status'  => 'Pending',
                'status'           => 'Active'
            ]);
            return redirect()->back()->with('success', 'Verification documents uploaded! Please wait for Admin review.');
        }

        return redirect()->back()->with('error', 'KTP document is required for verification.');
    }
}