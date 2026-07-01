<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\SubscriptionModel;

class Subscriptions extends BaseController 
{
    public function index() 
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionModel();
        
        $data['subscriptions'] = $model->select('subscriptions.*, users.first_name, users.last_name, subscription_plans.name as plan_name, subscription_plans.price')
                                       ->join('users', 'users.id = subscriptions.user_id', 'left')
                                       ->join('subscription_plans', 'subscription_plans.id = subscriptions.plan_id', 'left')
                                       ->orderBy('subscriptions.created_date', 'DESC')
                                       ->findAll();
        
        return view('admin/subscriptions', $data);
    }

    public function activate($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionModel();
        // Update user's offline payment request to Active
        $model->update($id, ['status' => 'Active']);
        
        return redirect()->to(base_url('admin/subscriptions'))->with('success', 'User subscription successfully activated!');
    }
}