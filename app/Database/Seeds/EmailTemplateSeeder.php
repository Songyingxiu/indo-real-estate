<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'    => 'User Sign Up',
                'subject' => 'Welcome to HuniKita, {first_name}!',
                'body'    => '<h2>Welcome, {first_name}!</h2><p>Your account has been successfully created. You can now log in at {login_link}.</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Forgot Password',
                'subject' => 'Password Reset Request',
                'body'    => '<p>Hi {first_name},</p><p>You requested a password reset. Please click <a href="{reset_link}">here</a> to reset it.</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Password Reset Success',
                'subject' => 'Your Password Has Been Updated',
                'body'    => '<p>Hi {first_name},</p><p>Your password has been successfully updated. If you did not make this change, please contact support immediately.</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'New Inquiry Customer',
                'subject' => 'Message Sent Successfully!',
                'body'    => '<p>Hi {first_name},</p><p>Your inquiry for property ID {property_id} has been sent to the agent/owner. They will be in touch soon.</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'New Inquiry Agent',
                'subject' => 'New Lead: Someone is interested in your property!',
                'body'    => '<p>Hello,</p><p>You have received a new inquiry on property ID {property_id}. Check your HuniKita inbox to reply!</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Property Listed Customer',
                'subject' => 'Your Listing is Pending Moderation',
                'body'    => '<p>Hi {first_name},</p><p>Your property "{property_title}" has been saved and is currently under review by our moderation team.</p>',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Property Listed Admin',
                'subject' => 'ADMIN ALERT: New Property Moderation Request',
                'body'    => '<p>A new property "{property_title}" (ID: {property_id}) has been submitted and requires administrative moderation.</p>',
                'status'  => 'Active',
            ]
        ];

        $this->db->table('email_templates')->insertBatch($data);
    }
}