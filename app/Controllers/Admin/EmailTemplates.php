<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplateModel;

class EmailTemplates extends BaseController
{
    /**
     * Reusable check to ensure only Admins OR Premium Owners/Agents can access.
     */
    private function _hasAccess()
    {
        $role = session()->get('role_id');
        $planId = session()->get('plan_id') ?? 1;
        
        // Return true if Admin (4) OR (Owner/Agent AND plan is Basic/Enterprise)
        if ($role == 4) return true;
        if (in_array($role, [2, 3]) && in_array($planId, [2, 3])) return true;
        
        return false;
    }

    public function index()
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Please upgrade your plan to access Email Templates.');

        $templateModel = new EmailTemplateModel();
        $data['templates'] = $templateModel->orderBy('name', 'ASC')->findAll();
        $data['title'] = 'Email Templates - HuniKita Admin';
        
        return view('admin/email_templates/index', $data);
    }

    public function create()
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Please upgrade your plan to access Email Templates.');

        $data['title'] = 'Create Email Template - HuniKita Admin';
        return view('admin/email_templates/form', $data);
    }

    public function edit($id)
    {
        if (!$this->_hasAccess()) return redirect()->to(base_url('admin/dashboard'))->with('error', 'Please upgrade your plan to access Email Templates.');

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

        // --- Fetch the specific email from the POST request (via Alpine modal) ---
        $targetEmail = $this->request->getPost('test_email');
        if (!$targetEmail || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Please provide a valid email address.');
        }

        // Automatically generate dummy data based on the variables they saved
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

        // Replace placeholders
        $subject = str_replace($search, $replace, $template->subject);
        $body    = str_replace($search, $replace, $template->body);

        // Send the email
        $email = \Config\Services::email();
        $email->setTo($targetEmail);
        $email->setSubject('[TEST PREVIEW] ' . $subject);
        $email->setMessage($body);
        $email->setMailType('html'); 

        if ($email->send()) {
            return redirect()->to(base_url('admin/email-templates'))->with('success', 'Test email successfully sent to ' . esc($targetEmail));
        } else {
            // Log the error if SMTP fails
            log_message('error', $email->printDebugger(['headers']));
            return redirect()->to(base_url('admin/email-templates'))->with('error', 'Failed to send test email. Check your SMTP settings.');
        }
    }
}