<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionModel;
use App\Models\OfflinePaymentModel;
use Cloudinary\Cloudinary;

class Subscription extends BaseController
{
    public function pricing()
    {
        $planModel = new SubscriptionPlanModel();
        
        $data['title'] = 'Pricing Plans - HuniKita';
        $data['plans'] = $planModel->where('status', 'Active')->findAll();
        
        return view('admin/subscription/pricing', $data);
    }

    public function checkout()
    {
        $planId = $this->request->getPost('plan_id') ?? session()->get('checkout_plan_id');
        if (!$planId) return redirect()->to(base_url('admin/pricing'));

        // --- GATEKEEPER: Prevent Duplicate Subscriptions ---
        $subModel = new SubscriptionModel();
        $existingSub = $subModel->where('user_id', session()->get('id'))
                                ->whereIn('sub_status', ['Active', 'Pending'])
                                ->first();
        
        if ($existingSub) {
            session()->remove('checkout_plan_id');
            $statusText = (isset($existingSub->sub_status) ? $existingSub->sub_status : $existingSub['sub_status']) == 'Pending' ? 'a pending payment awaiting verification' : 'an active subscription';
            return redirect()->to(base_url('admin/dashboard'))->with('error', "You already have $statusText. You can only subscribe to one plan per year.");
        }
        // ---------------------------------------------------

        session()->set('checkout_plan_id', $planId);

        $planModel = new SubscriptionPlanModel();
        $plan = $planModel->find($planId);

        if (!$plan) return redirect()->to(base_url('admin/pricing'))->with('error', 'Plan not found.');

        // If it's a Free plan, auto-activate it
        if ($plan->price == 0) {
            $subModel->insert([
                'user_id'    => session()->get('id'),
                'plan_id'    => $plan->id,
                'sub_status' => 'Active',
                'start_date' => date('Y-m-d H:i:s'),
                'end_date'   => date('Y-m-d H:i:s', strtotime('+1 year')),
                'status'     => 'Active'
            ]);
            session()->remove('checkout_plan_id');
            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Free plan activated successfully!');
        }

        // Generate Invoice for paid plans
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        
        // Create Pending Subscription
        $subModel->insert([
            'user_id'    => session()->get('id'),
            'plan_id'    => $plan->id,
            'sub_status' => 'Pending',
            'start_date' => null,
            'end_date'   => null,
            'status'     => 'Active'
        ]);
        $subscriptionId = $subModel->getInsertID();

        $data = [
            'title'          => 'Checkout - HuniKita',
            'plan'           => $plan,
            'invoice_number' => $invoiceNumber,
            'subscription_id'=> $subscriptionId
        ];

        return view('admin/subscription/checkout', $data);
    }

    public function uploadProof()
    {
        $rules = [
            'phone_number'  => 'required|min_length[8]',
            'payment_proof' => 'uploaded[payment_proof]|ext_in[payment_proof,png,jpg,jpeg]|max_size[payment_proof,5120]'
        ];

        if (!$this->validate($rules)) {
            $errorMsg = $this->validator->getError('payment_proof') ?: 'Please upload a valid image file and provide your phone number.';
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $proofFile = $this->request->getFile('payment_proof');
        
        // 1. Initialize Cloudinary using env()
        $cloudinaryUrl = env('CLOUDINARY_URL') ?: getenv('CLOUDINARY_URL');
        
        if (empty($cloudinaryUrl)) {
            return redirect()->back()->with('error', 'Cloudinary configuration is missing from environment variables.');
        }

        try {
            $cloudinary = new Cloudinary($cloudinaryUrl);
            
            // 2. Upload to Cloudinary
            $response = $cloudinary->uploadApi()->upload($proofFile->getTempName(), [
                'folder' => 'hunikita_receipts', 
            ]);
            
            // 3. Get the secure cloud URL
            $secureUrl = $response['secure_url'];
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cloudinary Error: ' . $e->getMessage());
        }

        // 4. Save the Cloudinary URL to Database
        $paymentModel = new OfflinePaymentModel();
        $inserted = $paymentModel->insert([
            'subscription_id' => $this->request->getPost('subscription_id'),
            'phone_number'    => $this->request->getPost('phone_number'),
            'invoice_number'  => $this->request->getPost('invoice_number'),
            'payment_proof'   => $secureUrl, 
            'approval_status' => 'Pending',
            'status'          => 'Active'
        ]);

        if (!$inserted) {
            return redirect()->back()->with('error', 'Database error: Failed to save payment record.');
        }

        $paymentId = $paymentModel->getInsertID();

        // Clear the session state to prevent checkout loops
        session()->remove('checkout_plan_id');
        
        // Redirect directly to the newly generated invoice
        return redirect()->to(base_url("admin/subscription/invoice/{$paymentId}"))->with('success', 'Payment proof uploaded! Your invoice has been generated and is pending verification.');
    }

    public function invoice($paymentId)
    {
        $paymentModel = new OfflinePaymentModel();
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();

        $payment = $paymentModel->find($paymentId);
        
        if (!$payment) {
            return redirect()->to(base_url('admin/pricing'))->with('error', 'Invoice not found.');
        }

        $subscription = $subModel->find($payment->subscription_id);
        
        // Security check: ensure the user viewing the invoice owns it (unless they are an admin)
        if (session()->get('role_id') != 4 && $subscription->user_id != session()->get('user_id')) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Unauthorized access to invoice.');
        }

        $plan = $planModel->find($subscription->plan_id);

        $data = [
            'title'        => 'Invoice ' . $payment->invoice_number,
            'payment'      => $payment,
            'subscription' => $subscription,
            'plan'         => $plan,
        ];

        return view('admin/subscription/invoice', $data); 
    }
}