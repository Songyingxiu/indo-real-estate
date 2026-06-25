<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;

class Subscriptions extends BaseController 
{
    public function index() 
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionPlanModel();
        // Sorting by price
        $data['plans'] = $model->orderBy('price', 'ASC')->findAll();
        
        return view('admin/subscriptions', $data);
    }

    public function store()
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionPlanModel();
        $model->insert([
            'name'   => $this->request->getPost('name'),
            'price'  => $this->request->getPost('price'),
            'status' => 'Active'
        ]);
        
        return redirect()->to(base_url('admin/subscriptions'))->with('success', 'Subscription plan created successfully!');
    }

    public function update($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionPlanModel();
        $model->update($id, [
            'name'   => $this->request->getPost('name'),
            'price'  => $this->request->getPost('price')
        ]);
        
        return redirect()->to(base_url('admin/subscriptions'))->with('success', 'Subscription plan updated!');
    }

    public function delete($id)
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));
        
        $model = new SubscriptionPlanModel();
        $model->delete($id);
        
        return redirect()->to(base_url('admin/subscriptions'))->with('success', 'Subscription plan removed.');
    }
}