<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AgentVerificationModel;
use App\Models\PropertyVerificationModel;

class Verifications extends BaseController {
    
    public function index() {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        // 1. Fetch Agent Verifications (Using LEFT JOIN and Grouped Statuses for safety)
        $agentModel = new AgentVerificationModel();
        $agentModel->select('agent_verifications.*, users.first_name, users.last_name');
        $agentModel->join('users', 'users.id = agent_verifications.user_id', 'left');
        $agentModel->groupStart()
                   ->whereIn('agent_verifications.approval_status', ['Pending', 'Under Review'])
                   ->orWhere('agent_verifications.approval_status IS NULL')
                   ->orWhere('agent_verifications.approval_status', '')
                   ->groupEnd();
        $data['agent_verifications'] = $agentModel->orderBy('agent_verifications.created_date', 'DESC')->findAll();

        // 2. Fetch Property Verifications (Using LEFT JOIN and Grouped Statuses for safety)
        $propModel = new PropertyVerificationModel();
        $propModel->select('property_verifications.*, properties.title as property_title, users.first_name, users.last_name');
        $propModel->join('properties', 'properties.id = property_verifications.property_id', 'left');
        $propModel->join('users', 'users.id = properties.owner_id', 'left');
        $propModel->groupStart()
                  ->whereIn('property_verifications.approval_status', ['Pending', 'Pending Verification', 'Under Review'])
                  ->orWhere('property_verifications.approval_status IS NULL')
                  ->orWhere('property_verifications.approval_status', '')
                  ->groupEnd();
        $data['prop_verifications'] = $propModel->orderBy('property_verifications.created_date', 'DESC')->findAll();
        
        return view('admin/verifications', $data);
    }

    public function processAgent($id) {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $action = $this->request->getPost('action');
        $model = new AgentVerificationModel();
        
        if ($action === 'approve') {
            $model->update($id, ['approval_status' => 'Verified']);
        } elseif ($action === 'reject') {
            $model->update($id, ['approval_status' => 'Rejected']);
        }

        return redirect()->to(base_url('admin/verifications'))->with('success', 'Agent Identity status updated!');
    }

    public function processProperty($id) {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $action = $this->request->getPost('action');
        $model = new PropertyVerificationModel();
        
        if ($action === 'approve') {
            $model->update($id, ['approval_status' => 'Verified']);
        } elseif ($action === 'reject') {
            $model->update($id, ['approval_status' => 'Rejected']);
        }

        return redirect()->to(base_url('admin/verifications'))->with('success', 'Property Document status updated!');
    }
}