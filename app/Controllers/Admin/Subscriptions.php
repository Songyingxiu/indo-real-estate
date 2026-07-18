<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\OfflinePaymentModel;

class Subscriptions extends BaseController 
{
    public function index() 
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionModel();
        
        // Joined the offline_payments table to get the receipt image and invoice details
        $data['subscriptions'] = $model->select('subscriptions.*, users.first_name, users.last_name, subscription_plans.name as plan_name, subscription_plans.price, offline_payments.payment_proof, offline_payments.invoice_number, offline_payments.phone_number')
                                       ->join('users', 'users.id = subscriptions.user_id', 'left')
                                       ->join('subscription_plans', 'subscription_plans.id = subscriptions.plan_id', 'left')
                                       ->join('offline_payments', 'offline_payments.subscription_id = subscriptions.id', 'left')
                                       ->orderBy('subscriptions.created_date', 'DESC')
                                       ->findAll();
        
        return view('admin/subscriptions', $data);
    }

    public function activate($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $subModel = new SubscriptionModel();
        $paymentModel = new OfflinePaymentModel();
        
        // Set the subscription to Active and define the 1-year validity period
        $subModel->update($id, [
            'status'     => 'Active',
            'sub_status' => 'Active',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date'   => date('Y-m-d H:i:s', strtotime('+1 year'))
        ]);
        
        // Also update the offline payment record to Verified
        $paymentModel->where('subscription_id', $id)
                     ->set(['approval_status' => 'Verified'])
                     ->update();
        
        return redirect()->to(base_url('admin/subscriptions'))->with('success', 'Payment verified! The user subscription is now active for 1 year.');
    }

    public function manage($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $subModel = new SubscriptionModel();
        $subscription = $subModel->find($id);
        
        if (!$subscription) {
            return redirect()->to(base_url('admin/subscriptions'))->with('error', 'Subscription record not found.');
        }

        $data['subscription'] = $subscription;
        
        // Load the view for managing the active subscription
        return view('admin/subscription_manage', $data);
    }
}