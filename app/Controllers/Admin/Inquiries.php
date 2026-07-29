<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InquiryModel;

class Inquiries extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        
        // Fetch inquiries where the agent/admin is the receiver
        $inquiries = $inquiryModel->select('inquiries.*, properties.title as property_title, properties.address_line_1, users.first_name, users.last_name, users.email')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('users', 'users.id = inquiries.sender_id', 'left')
            ->where('inquiries.receiver_id', session()->get('id'))
            ->orderBy('inquiries.created_at', 'DESC')
            ->paginate(15);

        $data = [
            'inquiries' => $inquiries,
            'pager'     => $inquiryModel->pager
        ];

        return view('admin/inquiries/index', $data);
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
}