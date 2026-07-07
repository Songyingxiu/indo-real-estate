<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\UserModel;

class Moderation extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $propertyModel = new PropertyModel();
        
        $propertyModel->select('properties.*, users.first_name, users.last_name');
        $propertyModel->join('users', 'users.id = properties.owner_id', 'left');
        $propertyModel->orderBy('properties.created_date', 'DESC');
        $data['properties'] = $propertyModel->findAll();

        return view('admin/moderation', $data);
    }

    public function updateStatus($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $status = $this->request->getPost('approval_status');
        
        $validStates = ['Draft', 'Pending Review', 'Approved', 'Published', 'Rejected', 'Expired', 'Archived'];

        if (!in_array($status, $validStates)) {
            return redirect()->back()->with('error', 'Invalid state machine status selected.');
        }

        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => $status]);
        
        return redirect()->to(base_url('admin/moderation'))->with('success', 'Property state successfully updated to ' . $status . '!');
    }
}