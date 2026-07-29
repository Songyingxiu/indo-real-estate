<?php namespace App\Controllers;

use App\Models\InquiryModel;

class Inquiry extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        $userId = session()->get('id');
        
        // Fetch inquiries where the user is EITHER the sender OR the receiver
        $inquiries = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name, users.email')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.sender_id', 'left') // Join to get the person who sent the current message row
            ->groupStart()
                ->where('inquiries.sender_id', $userId)
                ->orWhere('inquiries.receiver_id', $userId)
            ->groupEnd()
            ->orderBy('inquiries.created_at', 'DESC')
            ->paginate(10);

        $data = [
            'inquiries' => $inquiries,
            'pager'     => $inquiryModel->pager
        ];

        return view('front/user/inbox', $data);
    }
}