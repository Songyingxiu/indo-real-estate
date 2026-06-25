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
        $roleId = session()->get('role_id');
        
        $userModel = new UserModel();
        $propertyModel = new PropertyModel();

        // These stats are safe for everyone to see
        $data['totalUsers'] = $userModel->countAllResults();
        $data['activeProperties'] = $propertyModel->where('approval_status', 'Published')->countAllResults();
        
        // --- ADMIN ONLY DATA (ROLE 4) ---
        if ($roleId == 4) {
            $data['pendingProperties'] = $propertyModel->where('approval_status', 'Pending Review')->countAllResults();
            
            $agentVerifModel = new AgentVerificationModel();
            $paymentModel = new OfflinePaymentModel();
            $subscriptionModel = new SubscriptionModel();

            $pendingAgents = $agentVerifModel->where('approval_status', 'Pending')->findAll();
            $pendingPayments = $paymentModel->where('approval_status', 'Pending')->findAll();

            $verifications = [];

            // Map Agent Verifications (Safely casted to array to prevent object errors)
            foreach ($pendingAgents as $agent) {
                $agentArr = (array) $agent;
                $user = (array) $userModel->find($agentArr['user_id']);
                
                if (!empty($user)) {
                    $firstName = $user['first_name'] ?? 'Unknown';
                    $lastName = $user['last_name'] ?? '';
                    
                    $verifications[] = [
                        'type'      => 'Agent KTP',
                        'icon'      => 'badge',
                        'submitter' => trim($firstName . ' ' . $lastName),
                        'initials'  => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)) ?: 'U',
                        'date'      => $agentArr['created_date'],
                        'status'    => $agentArr['approval_status']
                    ];
                }
            }

            // Map Offline Payments (Safely casted to array)
            foreach ($pendingPayments as $payment) {
                $paymentArr = (array) $payment;
                $sub = (array) $subscriptionModel->find($paymentArr['subscription_id']);
                
                if (!empty($sub)) {
                    $user = (array) $userModel->find($sub['user_id']);
                    if (!empty($user)) {
                        $firstName = $user['first_name'] ?? 'Unknown';
                        $lastName = $user['last_name'] ?? '';
                        
                        $verifications[] = [
                            'type'      => 'Offline Payment',
                            'icon'      => 'receipt_long',
                            'submitter' => trim($firstName . ' ' . $lastName),
                            'initials'  => strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)) ?: 'U',
                            'date'      => $paymentArr['created_date'],
                            'status'    => $paymentArr['approval_status']
                        ];
                    }
                }
            }

            // Sort by Newest First and limit to 5 items
            usort($verifications, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            
            $data['verifications'] = array_slice($verifications, 0, 5);

            // Update total "Pending Tasks"
            $data['pendingTasks'] = $data['pendingProperties'] + count($verifications); 
        }

        return view('admin/dashboard', $data);
    }
}