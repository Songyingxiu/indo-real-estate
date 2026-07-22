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

    public function sendTest($id)
    {
        $templateModel = new EmailTemplateModel();
        $template = $templateModel->find($id);
        
        if (!$template) {
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'Template not found.');
        }

        // Get the logged-in admin's email to send the test to
        $adminEmail = session()->get('email') ?? 'test@example.com'; 

        // Automatically generate dummy data based on the variables they saved
        $search = [];
        $replace = [];
        
        if (!empty($template->variables)) {
            $variablesList = explode(',', $template->variables);
            foreach ($variablesList as $var) {
                $var = trim($var);
                if (!empty($var)) {
                    $search[] = $var;
                    // Creates a dummy string like "Test_user_name" for {user_name}
                    $replace[] = 'Test_' . trim($var, '{}'); 
                }
            }
        }

        // Replace placeholders
        $subject = str_replace($search, $replace, $template->subject);
        $body    = str_replace($search, $replace, $template->body);

        // Send the email
        $email = \Config\Services::email();
        $email->setTo($adminEmail);
        $email->setSubject('[TEST PREVIEW] ' . $subject);
        $email->setMessage($body);
        $email->setMailType('html'); 

        if ($email->send()) {
            return redirect()->to(base_url('admin/email-templates'))->with('success', 'Test email successfully sent to ' . $adminEmail);
        } else {
            // Log the error if SMTP fails
            log_message('error', $email->printDebugger(['headers']));
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'Failed to send test email. Check your SMTP settings.');
        }
    }
}