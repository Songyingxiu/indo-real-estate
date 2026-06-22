<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\AgentVerificationModel;
use App\Models\OfflinePaymentModel;
use App\Models\SubscriptionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $propertyModel = new PropertyModel();

        // Count Top Statistics
        $data['totalUsers'] = $userModel->countAllResults();
        $data['pendingProperties'] = $propertyModel->where('approval_status', 'Pending Review')->countAllResults();
        $data['activeProperties'] = $propertyModel->where('approval_status', 'Published')->countAllResults();
        
        // Fetch Verification Center Data
        $agentVerifModel = new AgentVerificationModel();
        $paymentModel = new OfflinePaymentModel();
        $subscriptionModel = new SubscriptionModel();

        $pendingAgents = $agentVerifModel->where('approval_status', 'Pending Review')->findAll();
        $pendingPayments = $paymentModel->where('approval_status', 'Pending Review')->findAll();

        $verifications = [];

        // Map Agent Verifications
        foreach ($pendingAgents as $agent) {
            $user = $userModel->find($agent->user_id);
            if ($user) {
                $firstName = $user['first_name'] ?? 'Unknown';
                $lastName = $user['last_name'] ?? '';
                
                $verifications[] = [
                    'type'      => 'Agent KTP',
                    'icon'      => 'badge',
                    'submitter' => trim($firstName . ' ' . $lastName),
                    'initials'  => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)) ?: 'U',
                    'date'      => $agent->created_date,
                    'status'    => $agent->approval_status
                ];
            }
        }

        // Map Offline Payments
        foreach ($pendingPayments as $payment) {
            $sub = $subscriptionModel->find($payment->subscription_id);
            if ($sub) {
                $user = $userModel->find($sub->user_id);
                if ($user) {
                    $firstName = $user['first_name'] ?? 'Unknown';
                    $lastName = $user['last_name'] ?? '';
                    
                    $verifications[] = [
                        'type'      => 'Offline Payment',
                        'icon'      => 'receipt_long',
                        'submitter' => trim($firstName . ' ' . $lastName),
                        'initials'  => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)) ?: 'U',
                        'date'      => $payment->created_date,
                        'status'    => $payment->approval_status
                    ];
                }
            }
        }

        // Sort by Newest First and limit to 5 items for the dashboard view
        usort($verifications, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        $data['verifications'] = array_slice($verifications, 0, 5);

        // Update total "Pending Tasks" number at the top
        $data['pendingTasks'] = $data['pendingProperties'] + count($verifications); 

        return view('admin/dashboard', $data);
    }
}