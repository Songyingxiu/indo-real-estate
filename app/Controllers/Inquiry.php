<?php namespace App\Controllers;

use App\Models\InquiryModel;

class Inquiry extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        $userId = session()->get('id');
        
        // Fetch only threads initiated by the buyer
        $threads = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.receiver_id', 'left') // Get the agent details
            ->where('inquiries.sender_id', $userId)
            ->where('inquiries.parent_id', null)
            ->orderBy('inquiries.created_at', 'DESC')
            ->findAll();

        return view('front/user/inbox', ['threads' => $threads]);
    }

    public function getThread($id)
    {
        $inquiryModel = new InquiryModel();
        
        // Mark as read if the buyer is opening an agent's reply
        $parent = $inquiryModel->find($id);
        if ($parent && $parent['sender_id'] == session()->get('id') && $parent['status'] == 'Replied') {
            $inquiryModel->update($id, ['status' => 'In Discussion']);
        }
        
        // Fetch thread messages
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

    public function reply()
    {
        $inquiryModel = new InquiryModel();
        $json = $this->request->getJSON();
        
        $data = [
            'parent_id'   => $json->parent_id,
            'property_id' => $json->property_id,
            'sender_id'   => session()->get('id'),
            'receiver_id' => $json->receiver_id, // The agent
            'message'     => $json->message,
            'status'      => 'Replied'
        ];

        if ($inquiryModel->insert($data)) {
            $data['inquiry_id'] = $inquiryModel->getInsertID();
            $data['created_at'] = date('Y-m-d H:i:s');
            
            // Buyers replying sets status to 'Pending' so agents know it needs attention
            $inquiryModel->update($json->parent_id, ['status' => 'Pending']);
            
            return $this->response->setJSON(['status' => 'success', 'message_data' => $data]);
        }
        
        return $this->response->setJSON(['status' => 'error']);
    }
}