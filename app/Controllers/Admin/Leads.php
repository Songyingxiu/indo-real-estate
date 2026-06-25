<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\LeadModel;

class Leads extends BaseController 
{
    public function index() 
    {
        $roleId = session()->get('role_id');
        
        $userId = session()->get('user_id'); 
        
        // Allow Admins(4), Agents(3), and Owners(2)
        if (!in_array($roleId, [2, 3, 4])) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new LeadModel();
        
        // Join properties and users tables to get readable names instead of raw IDs
        $model->select('
            leads.*, 
            p.title as property_title, 
            p.owner_id,
            b.first_name as buyer_first, 
            b.last_name as buyer_last, 
            b.email as buyer_email, 
            a.first_name as agent_first, 
            a.last_name as agent_last
        ');
        $model->join('properties p', 'p.id = leads.property_id', 'left');
        $model->join('users b', 'b.id = leads.buyer_id', 'left');
        $model->join('users a', 'a.id = leads.agent_id', 'left');
        
        // --- DYNAMIC DATA FILTERING ---
        if ($roleId == 3) {
            // Agent: Sees only leads assigned specifically to them
            $model->where('leads.agent_id', $userId);
        } elseif ($roleId == 2) {
            // Owner: Sees only leads attached to properties they own
            $model->where('p.owner_id', $userId);
        }
        // If Role is 4 (Admin), no WHERE clause is added, so they see every lead!
        
        $data['leads'] = $model->orderBy('leads.created_date', 'DESC')->paginate(10, 'leads');
        $data['pager'] = $model->pager;

        return view('admin/leads', $data);
    }

    public function updateStatus($id)
    {
        $roleId = session()->get('role_id');
        
        $userId = session()->get('user_id');

        // Only Admins (4) and Agents (3) should update lead status. Owners just view.
        if (!in_array($roleId, [3, 4])) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new LeadModel();
        $lead = $model->find($id);

        // Security Check: If an Agent tries to update a lead, verify they actually own it!
        if ($roleId == 3 && $lead->agent_id != $userId) {
            return redirect()->to(base_url('admin/leads'))->with('error', 'Unauthorized access. You do not own this lead.');
        }

        $model->update($id, [
            'lead_status' => $this->request->getPost('lead_status')
        ]);
        
        return redirect()->to(base_url('admin/leads'))->with('success', 'Lead status updated successfully.');
    }

    public function delete($id)
    {
        $roleId = session()->get('role_id');
        
        // ONLY Admins should be able to permanently delete leads to prevent agents from hiding data.
        if ($roleId != 4) {
            return redirect()->to(base_url('admin/leads'))->with('error', 'Only administrators can permanently delete leads.');
        }
        
        $model = new LeadModel();
        $model->delete($id);
        
        return redirect()->to(base_url('admin/leads'))->with('success', 'Lead record removed.');
    }
}