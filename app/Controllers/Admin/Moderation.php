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
        $pendingProperty = $propertyModel->where('approval_status', 'Pending Review')->first();

        if ($pendingProperty) {
            $userModel = new UserModel();
            $owner = $userModel->find($pendingProperty['owner_id']);
            $pendingProperty['owner_name'] = ($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '');
        }

        $data['property'] = $pendingProperty;
        return view('admin/moderation', $data);
    }

    public function approve($id)
    {
        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => 'Published']);
        return redirect()->to(base_url('admin/moderation'))->with('success', 'Property published!');
    }

    public function reject($id)
    {
        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => 'Rejected']);
        return redirect()->to(base_url('admin/moderation'))->with('error', 'Property rejected.');
    }
}