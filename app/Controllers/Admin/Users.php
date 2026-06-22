<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access.');
        }

        $userModel = new UserModel();
        $data['users'] = $userModel->withDeleted()->findAll();
        
        return view('admin/users/user', $data);
    }

    public function delete($id)
    {
        if (session()->get('role_id') != 4) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access.');
        }

        $userModel = new UserModel();
        $userModel->delete($id);
        
        return redirect()->to(base_url('admin/users'))->with('success', 'User account has been suspended (Soft Deleted).');
    }
}