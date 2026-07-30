<?php namespace App\Controllers\Admin;
/** @author*/

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AgentVerificationModel;

class Users extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $userModel = new UserModel();
        // Fetch users and display newest first
        $data['users'] = $userModel->withDeleted()->orderBy('created_date', 'DESC')->findAll();
        
        // Fetch latest agent verifications to attach to the user list
        $agentVerifyModel = new AgentVerificationModel();
        $allDocs = $agentVerifyModel->orderBy('id', 'DESC')->findAll();
        
        $mappedDocs = [];
        foreach ($allDocs as $doc) {
            $row = (object) $doc;
            // Only store the most recent document submission per user
            if (!isset($mappedDocs[$row->user_id])) {
                $mappedDocs[$row->user_id] = $row;
            }
        }
        $data['agentDocs'] = $mappedDocs;
        
        return view('admin/users/user', $data);
    }

    public function create()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        // Fetch active roles to populate the dropdown
        $db = \Config\Database::connect();
        $data['roles'] = $db->table('roles')->where('status', 'Active')->get()->getResultArray();

        return view('admin/users/create', $data);
    }

    public function store()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $userModel = new UserModel();

        // Validate the incoming form
        $rules = [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'password'   => 'required|min_length[8]',
            'role_id'    => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'role_id'      => $this->request->getPost('role_id'),
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'password'     => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'status'       => 'Active'
        ];

        $userModel->insert($data);
        return redirect()->to(base_url('admin/users'))->with('success', 'New user account created successfully.');
    }

    public function updateRole($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $userModel = new UserModel();
        $newRoleId = $this->request->getPost('role_id');

        $userModel->update($id, ['role_id' => $newRoleId]);
        return redirect()->to(base_url('admin/users'))->with('success', 'User role updated successfully.');
    }

    public function delete($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $userModel = new UserModel();
        $userModel->delete($id);
        
        return redirect()->to(base_url('admin/users'))->with('success', 'User account has been suspended (Soft Deleted).');
    }
}