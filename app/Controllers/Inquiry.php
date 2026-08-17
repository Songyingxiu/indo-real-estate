<?php namespace App\Controllers;

use App\Models\InquiryModel;

class Inquiry extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        $userId = session()->get('id');
        
        $threads = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name')
            ->select('(SELECT MAX(created_at) FROM inquiries AS replies WHERE replies.parent_id = inquiries.inquiry_id OR replies.inquiry_id = inquiries.inquiry_id) AS last_activity', false)
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.receiver_id', 'left')
            ->where('inquiries.sender_id', $userId)
            ->where('inquiries.parent_id', null)
            ->orderBy('last_activity', 'DESC')
            ->findAll();

        return view('front/user/inbox', ['threads' => $threads]);
    }

    public function getThread($id)
    {
        $inquiryModel = new InquiryModel();
        
        $parent = $inquiryModel->where('inquiry_id', $id)->first();
        
        if ($parent) {
            $senderId = is_object($parent) ? $parent->sender_id : $parent['sender_id'];
            $status   = is_object($parent) ? $parent->status : $parent['status'];
            
            if ($senderId == session()->get('id') && $status == 'Replied') {
                $inquiryModel->where('inquiry_id', $id)->set(['status' => 'In Discussion'])->update();
            }
        }
        
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
            'property_id' => $json->property_id ?: null,
            'sender_id'   => session()->get('id'),
            'receiver_id' => $json->receiver_id,
            'message'     => $json->message,
            'status'      => 'Replied',
            'created_at'  => date('Y-m-d H:i:s') 
        ];

        if ($inquiryModel->insert($data)) {
            $data['inquiry_id'] = $inquiryModel->getInsertID();
            
            $inquiryModel->where('inquiry_id', $json->parent_id)->set(['status' => 'Pending'])->update();
            
            return $this->response->setJSON(['status' => 'success', 'message_data' => $data]);
        }
        
        return $this->response->setJSON(['status' => 'error']);
    }
}