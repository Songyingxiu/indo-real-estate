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
        
        // Grab ALL pending properties and join the users table to get the agent's name
        $propertyModel->select('properties.*, users.first_name, users.last_name');
        $propertyModel->join('users', 'users.id = properties.owner_id', 'left');
        $data['properties'] = $propertyModel->where('approval_status', 'Pending Review')->findAll();

        return view('admin/moderation', $data);
    }

    public function approve($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => 'Published']);
        return redirect()->to(base_url('admin/moderation'))->with('success', 'Property successfully published to the marketplace!');
    }

    public function reject($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => 'Rejected']);
        return redirect()->to(base_url('admin/moderation'))->with('error', 'Property listing rejected.');
    }
}