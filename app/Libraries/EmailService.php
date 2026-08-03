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
     * Send an email using a database template or dynamic fallback
     *
     * @param string $templateName The exact name of the template in the database
     * @param string $toEmail The recipient's email address
     * @param array $mergeFields Associative array of merge fields e.g., ['{name}' => 'Debra']
     * @return bool
     */
    public function sendDynamicEmail(string $templateName, string $toEmail, array $mergeFields = []): bool
    {
        $db = \Config\Database::connect();
        $template = $db->table('email_templates')
                       ->where('name', $templateName)
                       ->where('status', 'Active')
                       ->get()
                       ->getRowArray();

        if ($template) {
            $subject = $template['subject'];
            $body    = $template['body'];
        } else {
            // Fallback templates in case DB template is not seeded yet
            if ($templateName === 'Forgot Password') {
                $subject = 'Reset Your Password - HuniKita';
                $body    = '<p>Hello {first_name},</p><p>We received a request to reset your password. Click the link below to set a new password:</p><p><a href="{reset_link}">Reset Password</a></p><p>This link expires in 1 hour.</p>';
            } elseif ($templateName === 'User Sign Up') {
                $subject = 'Welcome to HuniKita!';
                $body    = '<p>Hi {first_name},</p><p>Thank you for signing up with HuniKita! Click here to login: <a href="{login_link}">Login</a></p>';
            } else {
                log_message('error', "EmailService: Template '{$templateName}' not found and no fallback provided.");
                return false;
            }
        }

        foreach ($mergeFields as $placeholder => $value) {
            $subject = str_replace($placeholder, $value, $subject);
            $body    = str_replace($placeholder, $value, $body);
        }

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