<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AgentVerificationModel;

class Verifications extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $db = \Config\Database::connect();
        $builder = $db->table('agent_verifications');
        $builder->select('agent_verifications.*, users.first_name, users.last_name');
        $builder->join('users', 'users.id = agent_verifications.user_id');
        $builder->whereIn('agent_verifications.approval_status', ['Pending', 'Under Review']);
        
        $data['verifications'] = $builder->get()->getResultArray();
        
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