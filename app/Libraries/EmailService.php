<?php namespace App\Libraries;

use App\Models\EmailTemplateModel;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    /**
     * Send an email using a database template
     *
     * @param string $templateName The exact name of the template in the database
     * @param string $toEmail The recipient's email address
     * @param array $mergeFields Associative array of merge fields e.g., ['{name}' => 'Debra']
     * @return bool
     */
    public function sendDynamicEmail(string $templateName, string $toEmail, array $mergeFields = []): bool
    {
        // 1. Fetch template from DB (Assuming you have a standard CodeIgniter model for it)
        $db = \Config\Database::connect();
        $template = $db->table('email_templates')->where('name', $templateName)->where('status', 'Active')->get()->getRowArray();

        if (!$template) {
            log_message('error', "EmailService: Template '{$templateName}' not found or inactive.");
            return false;
        }

        // 2. Process Merge Fields
        $subject = $template['subject'];
        $body = $template['body'];

        foreach ($mergeFields as $placeholder => $value) {
            $subject = str_replace($placeholder, $value, $subject);
            $body = str_replace($placeholder, $value, $body);
        }

        // 3. Configure and Send
        $this->email->clear();
        $this->email->setTo($toEmail);
        $this->email->setSubject($subject);
        $this->email->setMessage($body);
        $this->email->setMailType('html');

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'EmailService: Failed to send email to ' . $toEmail . '. Error: ' . $this->email->printDebugger(['headers']));
            return false;
        }
    }
}