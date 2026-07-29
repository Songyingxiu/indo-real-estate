<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InquiryModel;

class Inquiries extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        $userId = session()->get('id') ?? session()->get('user_id');
        
        $threads = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name, users.email, users.phone_number')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.sender_id', 'left')
            ->where('inquiries.receiver_id', $userId)
            ->where('inquiries.parent_id', null)
            ->orderBy('inquiries.created_at', 'DESC')
            ->findAll();

        $role = session()->get('role_id');
        $planId = session()->get('plan_id') ?? 1;
        $canReply = ($role == 4) || in_array($planId, [2, 3]);

        $data = [
            'threads'  => $threads,
            'canReply' => $canReply
        ];

        return view('admin/inquiries/index', $data);
    }

    public function getThread($id)
    {
        $inquiryModel = new InquiryModel();
        
        $messages = $inquiryModel->select('inquiries.*, users.first_name, users.last_name')
            ->join('users', 'users.id = inquiries.sender_id', 'left')
            ->groupStart()
                ->where('inquiries.inquiry_id', $id)
                ->orWhere('inquiries.parent_id', $id)
            ->groupEnd()
            ->orderBy('inquiries.created_at', 'ASC')
            ->findAll();
            
        return $this->response->setJSON($messages);
    }

    public function updateStatus($id)
    {
        $inquiryModel = new InquiryModel();
        $status = $this->request->getJSON()->status ?? '';
        
        if ($inquiryModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Update failed.']);
    }

    public function reply()
    {
        // Enforce Server-Side Plan Restrictions
        $role = session()->get('role_id');
        $planId = session()->get('plan_id') ?? 1;
        if ($role != 4 && !in_array($planId, [2, 3])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Your current subscription plan does not support Live Chat.']);
        }

        $inquiryModel = new InquiryModel();
        $json = $this->request->getJSON();
        
        $data = [
            'parent_id'   => $json->parent_id,
            'property_id' => $json->property_id,
            'sender_id'   => session()->get('id') ?? session()->get('user_id'),
            'receiver_id' => $json->receiver_id,
            'message'     => $json->message,
            'status'      => 'Replied'
        ];

        if ($inquiryModel->insert($data)) {
            $data['inquiry_id'] = $inquiryModel->getInsertID();
            $data['created_at'] = date('Y-m-d H:i:s');
            
            $inquiryModel->update($json->parent_id, ['status' => 'Replied']);
            
            return $this->response->setJSON(['status' => 'success', 'message_data' => $data]);
        }
        
        return $this->response->setJSON(['status' => 'error']);
    }
}