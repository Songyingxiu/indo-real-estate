<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplateModel;

class EmailTemplates extends BaseController
{
    public function index()
    {
        $templateModel = new EmailTemplateModel();
        $data['templates'] = $templateModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Email Templates - HuniKita Admin';
        
        return view('admin/email_templates/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create Email Template - HuniKita Admin';
        return view('admin/email_templates/form', $data);
    }

    public function edit($id)
    {
        $templateModel = new EmailTemplateModel();
        $data['template'] = $templateModel->find($id);
        
        if (!$data['template']) {
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'Template not found.');
        }

        $data['title'] = 'Edit Email Template - HuniKita Admin';
        return view('admin/email_templates/form', $data);
    }

    public function save()
    {
        $templateModel = new EmailTemplateModel();
        $id = $this->request->getPost('id');

        $validationRules = [
            'name'    => 'required|min_length[3]',
            'subject' => 'required|min_length[3]',
            'body'    => 'required',
            'status'  => 'required|in_list[Active,Inactive]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'subject'   => $this->request->getPost('subject'),
            'body'      => $this->request->getPost('body'),
            'variables' => $this->request->getPost('variables'),
            'status'    => $this->request->getPost('status')
        ];

        if ($id) {
            $templateModel->update($id, $data);
            $message = 'Email template updated successfully.';
        } else {
            $templateModel->insert($data);
            $message = 'Email template created successfully.';
        }

        return redirect()->to(base_url('admin/email-templates'))->with('success', $message);
    }

    public function delete($id)
    {
        $templateModel = new EmailTemplateModel();
        
        if ($templateModel->find($id)) {
            $templateModel->delete($id);
            return redirect()->to(base_url('admin/email-templates'))->with('success', 'Template deleted successfully.');
        }

        return redirect()->to(base_url('admin/email-templates'))->with('error', 'Template not found.');
    }
}