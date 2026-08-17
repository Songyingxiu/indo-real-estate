<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\UserModel;
use App\Models\PropertyVerificationModel;

class Moderation extends BaseController
{
    public function index()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $propertyModel = new PropertyModel();
        
        $propertyModel->select('properties.*, users.first_name, users.last_name, property_verifications.approval_status as doc_status');
        $propertyModel->join('users', 'users.id = properties.owner_id', 'left');
        $propertyModel->join('property_verifications', 'property_verifications.property_id = properties.id', 'left');
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

        if ($status === 'Published') {
            $docModel = new PropertyVerificationModel();
            $doc = $docModel->where('property_id', $id)->first();
            $docStatus = $doc ? (is_object($doc) ? $doc->approval_status : $doc['approval_status']) : 'Not Submitted';

            if ($docStatus !== 'Verified') {
                return redirect()->back()->with('error', 'Cannot publish property. The ownership document status is currently: ' . $docStatus);
            }
        }

        $propertyModel = new PropertyModel();
        $propertyModel->update($id, ['approval_status' => $status]);
        
        return redirect()->to(base_url('admin/moderation'))->with('success', 'Property state successfully updated to ' . $status . '!');
    }
}