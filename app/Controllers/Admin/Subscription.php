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
        if (!in_array(session()->get('role_id'), [3, 4])) {
            return redirect()->to(base_url('user/profile'))->with('error', 'You must verify your identity and become a Verified Agent before unlocking subscription packages.');
        }

        $planModel = new SubscriptionPlanModel();
        
        $data['title'] = 'Pricing Plans - HuniKita';
        $data['plans'] = $planModel->where('status', 'Active')->findAll();
        
        return view('admin/subscription/pricing', $data);
    }

    public function checkout()
    {
        if (!in_array(session()->get('role_id'), [3, 4])) {
            return redirect()->to(base_url('user/profile'))->with('error', 'You must verify your identity and become a Verified Agent before purchasing a premium subscription.');
        }

        $planId = $this->request->getPost('plan_id') ?? session()->get('checkout_plan_id');
        if (!$planId) return redirect()->to(base_url('admin/pricing'));

        // --- GATEKEEPER UPDATE: Allow Upgrades and Replace Pending ---
        $subModel = new SubscriptionModel();
        $planModel = new SubscriptionPlanModel();
        
        $existingSub = $subModel->where('user_id', session()->get('id'))
                                ->whereIn('sub_status', ['Active', 'Pending'])
                                ->first();
        
        if ($existingSub) {
            $existingStatus = is_array($existingSub) ? $existingSub['sub_status'] : $existingSub->sub_status;
            $existingPlanId = is_array($existingSub) ? $existingSub['plan_id'] : $existingSub->plan_id;
            $existingSubId  = is_array($existingSub) ? $existingSub['id'] : $existingSub->id;
            
            $existingPlan = $planModel->find($existingPlanId);

            if ($existingStatus == 'Pending') {
                // Delete the old abandoned checkout attempt so they can try again
                $subModel->delete($existingSubId);
            } elseif ($existingPlan && $existingPlan->price == 0) {
                // They are on a free plan, allow them to checkout an upgrade by updating old sub status
                $subModel->update($existingSubId, ['sub_status' => 'Upgraded', 'status' => 'Inactive']);
            } else {
                session()->remove('checkout_plan_id');
                return redirect()->to(base_url('admin/dashboard'))->with('error', "You already have an active paid subscription.");
            }
        }
        // ---------------------------------------------------

        session()->set('checkout_plan_id', $planId);
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
        // Strict Server-Side Validation
        $rules = [
            'phone_number'  => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'A WhatsApp/Phone number is required for verification updates.',
                    'min_length' => 'Please provide a valid phone number.'
                ]
            ],
            'payment_proof' => [
                'rules'  => 'uploaded[payment_proof]|ext_in[payment_proof,png,jpg,jpeg]|max_size[payment_proof,5120]',
                'errors' => [
                    'uploaded' => 'You must attach a valid transfer receipt before submitting.',
                    'ext_in'   => 'The receipt must be a PNG, JPG, or JPEG image.',
                    'max_size' => 'The image size cannot exceed 5MB.'
                ]
            ]
        ];

        // If validation fails, return the inline errors array to the view
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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

        // Clear the session state to prevent checkout loops
        session()->remove('checkout_plan_id');
        
        // Redirect directly to the dashboard per user request instead of invoice
        return redirect()->to(base_url("admin/dashboard"))->with('success', 'Payment proof uploaded! Your plan is pending verification.');
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