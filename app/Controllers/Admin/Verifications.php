<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AgentVerificationModel;

class Verifications extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $verificationModel = new AgentVerificationModel();
        
        // Select all fields from verifications, plus the user's name
        $verificationModel->select('agent_verifications.*, users.first_name, users.last_name');
        $verificationModel->join('users', 'users.id = agent_verifications.user_id');
        $verificationModel->whereIn('agent_verifications.approval_status', ['Pending', 'Under Review']);
        
        // Pagination instead of getResultArray()
        $data['verifications'] = $verificationModel->orderBy('agent_verifications.created_date', 'DESC')->paginate(10, 'verifications');
        $data['pager'] = $verificationModel->pager;
        
        return view('admin/verifications', $data);
    }

    public function process($id) {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $action = $this->request->getPost('action');
        $verificationModel = new AgentVerificationModel();
        
        if ($action === 'approve') {
            $verificationModel->update($id, ['approval_status' => 'Verified']);
        } elseif ($action === 'reject') {
            $verificationModel->update($id, ['approval_status' => 'Rejected']);
        }

        return redirect()->to(base_url('admin/verifications'))->with('success', 'Document status updated successfully!');
    }
}