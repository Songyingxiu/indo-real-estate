<?php namespace App\Controllers;

use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionModel;
use App\Models\OfflinePaymentModel;

class Subscription extends BaseController
{
    public function pricing()
    {
        $planModel = new SubscriptionPlanModel();
        
        $data['title'] = 'Pricing Plans - HuniKita';
        // Only fetch Active plans from the database
        $data['plans'] = $planModel->where('status', 'Active')->findAll();
        
        return view('front/subscription/pricing', $data);
    }

    public function checkout()
    {
        $planId = $this->request->getPost('plan_id');
        if (!$planId) return redirect()->to(base_url('pricing'));

        $planModel = new SubscriptionPlanModel();
        $plan = $planModel->find($planId);

        if (!$plan) return redirect()->to(base_url('pricing'))->with('error', 'Plan not found.');

        // Generate Invoice Number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        
        // Create Pending Subscription record
        $subModel = new SubscriptionModel();
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

        return view('front/subscription/checkout', $data);
    }

    public function uploadProof()
    {
        // Require a valid image file and a phone number
        $rules = [
            'phone_number'  => 'required|min_length[8]',
            'payment_proof' => 'uploaded[payment_proof]|is_image[payment_proof]|max_size[payment_proof,5120]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please upload a valid image file (JPG/PNG) and provide your phone number.');
        }

        $proofFile = $this->request->getFile('payment_proof');
        $newName = $proofFile->getRandomName();
        // Make sure to create the "payments" folder inside public/uploads!
        $proofFile->move(FCPATH . 'uploads/payments', $newName);

        $paymentModel = new OfflinePaymentModel();
        $paymentModel->insert([
            'subscription_id' => $this->request->getPost('subscription_id'),
            'phone_number'    => $this->request->getPost('phone_number'),
            'invoice_number'  => $this->request->getPost('invoice_number'),
            'payment_proof'   => $newName,
            'approval_status' => 'Pending Verification',
            'status'          => 'Active'
        ]);

        return redirect()->to(base_url('pricing'))->with('success', 'Payment proof uploaded! Our Admin team will verify it shortly and activate your package.');
    }
}