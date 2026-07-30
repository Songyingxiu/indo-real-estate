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

        $data['totalUsers'] = $userModel->countAllResults();
        $data['activeProperties'] = $propertyModel->where('approval_status', 'Published')->countAllResults();
        
        // Prepare arrays for the Chart.js Graphic
        $chartLabels = ['Active Users', 'Published Properties'];
        $chartValues = [$data['totalUsers'], $data['activeProperties']];

        // --- ADMIN ONLY DATA (ROLE 4) ---
        if ($roleId == 4) {
            $data['pendingProperties'] = $propertyModel->where('approval_status', 'Pending Review')->countAllResults();
            
            // Add pending to chart
            $chartLabels[] = 'Pending Properties';
            $chartValues[] = $data['pendingProperties'];

            $agentVerifModel = new AgentVerificationModel();
            $paymentModel = new OfflinePaymentModel();
            $subscriptionModel = new SubscriptionModel();

            $pendingAgents = $agentVerifModel->where('approval_status', 'Pending')->findAll();
            $pendingPayments = $paymentModel->where('approval_status', 'Pending')->findAll();

            $verifications = [];

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
                        // Fix: Check for created_at first, then created_date
                        'date'      => $agentArr['created_at'] ?? $agentArr['created_date'] ?? null,
                        'status'    => $agentArr['approval_status']
                    ];
                }
            }

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
                            // Fix: Check for created_at first, then created_date
                            'date'      => $paymentArr['created_at'] ?? $paymentArr['created_date'] ?? null,
                            'status'    => $paymentArr['approval_status']
                        ];
                    }
                }
            }

            usort($verifications, function($a, $b) {
                return strtotime($b['date'] ?? 'now') - strtotime($a['date'] ?? 'now');
            });
            
            $data['verifications'] = array_slice($verifications, 0, 5);
            $data['pendingTasks'] = $data['pendingProperties'] + count($verifications); 
        }

        // Pass Chart Data to View
        $data['chartLabels'] = json_encode($chartLabels);
        $data['chartValues'] = json_encode($chartValues);

        return view('admin/dashboard', $data);
    }
}