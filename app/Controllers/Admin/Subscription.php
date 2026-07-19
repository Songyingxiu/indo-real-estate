<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;
use App\Models\SubscriptionModel;
use App\Models\OfflinePaymentModel;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

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
        $planId = $this->request->getPost('plan_id');
        if (!$planId) return redirect()->to(base_url('admin/pricing'));

        $planModel = new SubscriptionPlanModel();
        $plan = $planModel->find($planId);

        if (!$plan) return redirect()->to(base_url('admin/pricing'))->with('error', 'Plan not found.');

        // If it's a Free plan, auto-activate it
        if ($plan->price == 0) {
            $subModel = new SubscriptionModel();
            $subModel->insert([
                'user_id'    => session()->get('id'),
                'plan_id'    => $plan->id,
                'sub_status' => 'Active',
                'start_date' => date('Y-m-d H:i:s'),
                'end_date'   => date('Y-m-d H:i:s', strtotime('+1 year')),
                'status'     => 'Active'
            ]);
            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Free plan activated successfully!');
        }

        // Generate Invoice for paid plans
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        
        // Create Pending Subscription
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

        return view('admin/subscription/checkout', $data);
    }

    public function uploadProof()
    {
        $rules = [
            'phone_number'  => 'required|min_length[8]',
            'payment_proof' => 'uploaded[payment_proof]|is_image[payment_proof]|max_size[payment_proof,5120]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please upload a valid image file and provide your phone number.');
        }

        $proofFile = $this->request->getFile('payment_proof');
        
        // 1. Initialize Cloudinary
        Configuration::instance(getenv('CLOUDINARY_URL'));
        $uploadApi = new UploadApi();

        try {
            // 2. Upload to Cloudinary
            $response = $uploadApi->upload($proofFile->getTempName(), [
                'folder' => 'hunikita_receipts', 
            ]);
            
            // 3. Get the secure cloud URL
            $secureUrl = $response['secure_url'];
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload image to cloud storage. Please try again.');
        }

        // 4. Save the Cloudinary URL to TiDB
        $paymentModel = new OfflinePaymentModel();
        $paymentModel->insert([
            'subscription_id' => $this->request->getPost('subscription_id'),
            'phone_number'    => $this->request->getPost('phone_number'),
            'invoice_number'  => $this->request->getPost('invoice_number'),
            'payment_proof'   => $secureUrl, 
            'approval_status' => 'Pending',
            'status'          => 'Active'
        ]);

        return redirect()->to(base_url('admin/pricing'))->with('success', 'Payment proof uploaded! Our team will verify it shortly and activate your package.');
    }
}