<?php namespace App\Controllers;

use App\Models\InquiryModel;
use App\Libraries\EmailService;

class Contact extends BaseController
{
    // Renders the Contact Us frontend page
    public function index()
    {
        $data = [
            'pageTitle' => 'Contact Us - HuniKita'
        ];
        return view('front/contact', $data);
    }

    // Handles Property/Agent Specific Inquiries
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
            return redirect()->back()->withInput()->with('error', 'Spam Protection: Please ensure all fields are filled out correctly.');
        }

        // 3. Save to Database using the new Threaded Inquiry System
        $inquiryModel = new InquiryModel();
        
        $source = $this->request->getPost('source');
        $originalMessage = $this->request->getPost('message');
        
        // Append the inquiry source to the message for the agent's context
        $formattedMessage = "Inquiry Type: " . $source . "\n\n" . $originalMessage;
        
        $inquiryModel->insert([
            'property_id' => $this->request->getPost('property_id'),
            'sender_id'   => session()->get('id'),
            'receiver_id' => $this->request->getPost('agent_id'),
            'message'     => $formattedMessage,
            'status'      => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been securely sent to the agent!');
    }

    // Handles the Global "Contact Us" Form
    public function submitContact()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'subject' => 'required|min_length[5]|max_length[150]',
            'message' => 'required|min_length[10]|max_length[2000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill out all required fields correctly.');
        }

        // Process Global Contact Submission
        $emailService = new EmailService();
        
        // Ensure you have a 'Global Contact Us' template in your DB, or this sends a raw fallback
        $emailService->sendDynamicEmail('Global Contact Us', 'admin@hunikita.com', [
            '{customer_name}'  => $this->request->getPost('name'),
            '{customer_email}' => $this->request->getPost('email'),
            '{subject}'        => $this->request->getPost('subject'),
            '{message}'        => $this->request->getPost('message')
        ]);

        return redirect()->back()->with('success', 'Thank you! Your message has been sent to our support team. We will get back to you shortly.');
    }
}