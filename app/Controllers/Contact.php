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

        $rules = [
            'subject' => 'required|min_length[5]|max_length[150]',
            'message' => 'required|min_length[10]|max_length[2000]',
            'email'   => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'validation_error', 
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userModel = new UserModel();
        $admin = $userModel->where('role_id', 4)->first();
        $adminId = $admin ? (is_array($admin) ? $admin['id'] : $admin->id) : 1; 

        $inquiryModel = new InquiryModel();
        
        $subject = $this->request->getPost('subject');
        $originalMessage = $this->request->getPost('message');
        $replyEmail = $this->request->getPost('email');
        
        $formattedMessage = "General Support Inquiry\nSubject: " . $subject . "\nReply-To Email: " . $replyEmail . "\n\n" . $originalMessage;
        
        try {
           $inquiryModel->insert([
                'sender_id'   => session()->get('id'),
                'receiver_id' => $adminId,
                'message'     => $formattedMessage,
                'status'      => 'Pending',
                'created_at'  => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON(['status' => 'success', 'message' => 'Message sent directly to Support!']);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Database configuration error: The inquiries table requires property_id to be nullable for general support tickets. Please run: ALTER TABLE inquiries MODIFY property_id INT NULL DEFAULT NULL;'
            ]);
        }
    }
}