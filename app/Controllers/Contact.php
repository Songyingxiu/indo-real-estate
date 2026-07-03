<?php namespace App\Controllers;

use App\Models\LeadModel;

class Contact extends BaseController
{
    public function submitLead()
    {
        // Enforce Login Verification
        if (!session()->get('id')) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to send a message.');
        }

        $leadModel = new LeadModel();
        
        $leadModel->insert([
            'property_id' => $this->request->getPost('property_id'),
            'buyer_id'    => session()->get('id'), // Attach to logged-in user
            'agent_id'    => $this->request->getPost('agent_id'),
            'name'        => $this->request->getPost('name'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
            'message'     => $this->request->getPost('message'),
            'source'      => 'Website',
            'lead_status' => 'New',
            'status'      => 'Active',
            'is_read'     => 0
        ]);

        return redirect()->back()->with('success', 'Your message has been successfully sent to the agent!');
    }
}