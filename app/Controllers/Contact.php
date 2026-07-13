<?php namespace App\Controllers;

use App\Models\LeadModel;

class Contact extends BaseController
{
    public function submitLead()
    {
        // 1. Enforce Login Verification
        if (!session()->get('id')) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to send a message.');
        }

        // 2. Anti-Spam & Form Validation
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'phone'   => 'required|min_length[8]|max_length[20]',
            'email'   => 'required|valid_email',
            'message' => 'required|min_length[10]|max_length[1000]',
            'source'  => 'required|in_list[Contact Form,Schedule Visit]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Spam Protection: Please ensure all fields are filled out correctly (e.g. valid phone number, message must be > 10 characters).');
        }

        // 3. Save to Database
        $leadModel = new LeadModel();
        
        $leadModel->insert([
            'property_id' => $this->request->getPost('property_id'),
            'buyer_id'    => session()->get('id'),
            'agent_id'    => $this->request->getPost('agent_id'),
            'name'        => $this->request->getPost('name'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
            'message'     => $this->request->getPost('message'),
            'source'      => $this->request->getPost('source'), 
            'lead_status' => 'New',
            'status'      => 'Active',
            'is_read'     => 0
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been securely sent to the agent!');
    }
}