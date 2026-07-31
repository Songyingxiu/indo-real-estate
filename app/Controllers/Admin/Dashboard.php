<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\AgentVerificationModel;
use App\Models\OfflinePaymentModel;
use App\Models\SubscriptionModel;
use App\Models\InquiryModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $roleId = session()->get('role_id');
        
        $userModel = new UserModel();
        $propertyModel = new PropertyModel();
        $subscriptionModel = new SubscriptionModel();
        $inquiryModel = new InquiryModel();

        // Base Date Calculations
        $today = date('Y-m-d');
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $fourteenDaysAgo = date('Y-m-d', strtotime('-14 days'));
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        $thisMonthStart = date('Y-m-01');
        $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-t', strtotime('last day of last month'));

        // property metrics (uses created_date)
        $data['totalUsers'] = $userModel->countAllResults();
        $data['activeProperties'] = $propertyModel->where('approval_status', 'Published')->countAllResults();
        $data['propTotal'] = $propertyModel->countAllResults();
        $data['propToday'] = $propertyModel->where("DATE(created_date) = '$today'")->countAllResults();
        $data['prop7Days'] = $propertyModel->where("DATE(created_date) >= '$sevenDaysAgo'")->countAllResults();
        $data['propLastWeek'] = $propertyModel->where("DATE(created_date) >= '$fourteenDaysAgo' AND DATE(created_date) < '$sevenDaysAgo'")->countAllResults();
        $data['propThisMonth'] = $propertyModel->where("DATE(created_date) >= '$thisMonthStart'")->countAllResults();
        $data['propLastMonth'] = $propertyModel->where("DATE(created_date) >= '$lastMonthStart' AND DATE(created_date) <= '$lastMonthEnd'")->countAllResults();
        
        // inquiries metrics (uses created_at)
        $data['inqTotal'] = $inquiryModel->countAllResults();
        $data['inqCompleted'] = $inquiryModel->whereIn('status', ['Closed', 'Completed', 'Under Contract'])->countAllResults();
        $data['inqToday'] = $inquiryModel->where("DATE(created_at) = '$today'")->countAllResults();
        $data['inqWeekly'] = $inquiryModel->where("DATE(created_at) >= '$sevenDaysAgo'")->countAllResults();
        $data['inqMonthly'] = $inquiryModel->where("DATE(created_at) >= '$thisMonthStart'")->countAllResults();

        // Prepare arrays for the Chart.js Graphic
        $chartLabels = ['Active Users', 'Published Properties', 'Total Inquiries'];
        $chartValues = [$data['totalUsers'], $data['activeProperties'], $data['inqTotal']];

        // admin only data (role 4)
        if ($roleId == 4) {
            $data['pendingProperties'] = $propertyModel->where('approval_status', 'Pending Review')->countAllResults();
            
            // subscription metrics (uses created_date)
            $data['subTotal'] = $subscriptionModel->countAllResults();
            $data['subToday'] = $subscriptionModel->where("DATE(created_date) = '$today'")->countAllResults();
            $data['sub7Days'] = $subscriptionModel->where("DATE(created_date) >= '$sevenDaysAgo'")->countAllResults();
            $data['sub30Days'] = $subscriptionModel->where("DATE(created_date) >= '$thirtyDaysAgo'")->countAllResults();
            
            // Calculate Revenue (Assuming amount field or joining with payments)
            $db = \Config\Database::connect();
            $revenueQuery = $db->table('offline_payments')->selectSum('amount')->where('approval_status', 'Verified')->get()->getRow();
            $data['revenueSum'] = $revenueQuery->amount ?? 0;

            // Add pending to chart
            $chartLabels[] = 'Pending Properties';
            $chartValues[] = $data['pendingProperties'];

            $agentVerifModel = new AgentVerificationModel();
            $paymentModel = new OfflinePaymentModel();

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
                        'date'      => $agentArr['created_at'] ?? $agentArr['created_date'] ?? date('Y-m-d H:i:s'),
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
                            'date'      => $paymentArr['created_at'] ?? $paymentArr['created_date'] ?? date('Y-m-d H:i:s'),
                            'status'    => $paymentArr['approval_status']
                        ];
                    }
                }
            }

            usort($verifications, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
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