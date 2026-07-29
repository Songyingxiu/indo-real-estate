<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplateModel;

class EmailTemplates extends BaseController
{
    /**
     * Strictly Admin Only Access
     */
    private function _hasAccess()
    {
        return session()->get('role_id') == 4;
    }

    public function index()
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access.');

        $templateModel = new EmailTemplateModel();
        $data['templates'] = $templateModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Email Templates - HuniKita Admin';
        
        return view('admin/email_templates/index', $data);
    }

    public function create()
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access.');

        $data['title'] = 'Create Email Template - HuniKita Admin';
        return view('admin/email_templates/form', $data);
    }

    public function edit($id)
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access.');

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
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized.');

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
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized.');

        $templateModel = new EmailTemplateModel();
        
        if ($templateModel->find($id)) {
            $templateModel->delete($id);
            return redirect()->to(base_url('admin/email-templates'))->with('success', 'Template deleted successfully.');
        }

        return redirect()->to(base_url('admin/email-templates'))->with('error', 'Template not found.');
    }

    public function sendTest($id)
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized.');

        $templateModel = new EmailTemplateModel();
        $template = $templateModel->find($id);
        
        if (!$template) {
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'Template not found.');
        }

        $targetEmail = $this->request->getPost('test_email');
        if (!$targetEmail || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Please provide a valid email address.');
        }

        $search = [];
        $replace = [];
        
        if (!empty($template->variables)) {
            $variablesList = explode(',', $template->variables);
            foreach ($variablesList as $var) {
                $var = trim($var);
                if (!empty($var)) {
                    $search[] = $var;
                    $replace[] = 'Test_' . trim($var, '{}'); 
                }
            }
        }

        $subject = str_replace($search, $replace, $template->subject);
        $body    = str_replace($search, $replace, $template->body);

        $email = \Config\Services::email();
        $email->setTo($targetEmail);
        $email->setSubject('[TEST PREVIEW] ' . $subject);
        $email->setMessage($body);
        $email->setMailType('html'); 

        try {
            if ($email->send()) {
                return redirect()->to(base_url('admin/email-templates'))->with('success', 'Test email successfully sent to ' . esc($targetEmail));
            } else {
                log_message('error', $email->printDebugger(['headers']));
                return redirect()->to(base_url('admin/email-templates'))->with('error', 'Failed to send test email. Check your SMTP settings.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Email Exception: ' . $e->getMessage());
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'An error occurred while sending the email. Please check system logs.');
        }
    }
}