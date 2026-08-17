<?php namespace App\Controllers;

use App\Models\InquiryModel;
use App\Models\UserModel;
use App\Libraries\EmailService;

class Contact extends BaseController
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Contact Us - HuniKita'
        ];
        return view('front/contact', $data);
    }

    public function submitContact()
    {
        if (!session()->get('id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        // Inline Validation
        $rules = [
            'subject' => 'required|min_length[5]|max_length[150]',
            'message' => 'required|min_length[10]|max_length[2000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'validation_error', 
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userModel = new UserModel();
        $admin = $userModel->where('role_id', 4)->first();
        $adminId = $admin ? $admin['id'] : 1; 

        $inquiryModel = new InquiryModel();
        
        $subject = $this->request->getPost('subject');
        $originalMessage = $this->request->getPost('message');
        
        $formattedMessage = "General Support Inquiry\nSubject: " . $subject . "\n\n" . $originalMessage;
        
        $inquiryModel->insert([
            'property_id' => null, 
            'sender_id'   => session()->get('id'),
            'receiver_id' => $adminId,
            'message'     => $formattedMessage,
            'status'      => 'Pending',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Message sent directly to Support!']);
    }
}