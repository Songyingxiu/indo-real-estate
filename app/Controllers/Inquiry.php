<?php namespace App\Controllers;

use App\Models\InquiryModel;

class Inquiry extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        
        // Fetch inquiries where the user is the sender
        $inquiries = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.receiver_id', 'left')
            ->where('inquiries.sender_id', session()->get('id'))
            ->orderBy('inquiries.created_at', 'DESC')
            ->paginate(10);

        $data = [
            'inquiries' => $inquiries,
            'pager'     => $inquiryModel->pager
        ];

        return view('front/user/inbox', $data);
    }
}