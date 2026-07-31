<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\SubscriptionModel;

class Reports extends BaseController 
{
    public function export() 
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $propertyModel = new PropertyModel();
        $subscriptionModel = new SubscriptionModel();

        // Fetch Detailed Lists
        $properties = $propertyModel->orderBy('created_at', 'DESC')->findAll();
        $subscriptions = $subscriptionModel->orderBy('created_at', 'DESC')->findAll();

        // Setup CSV headers for download
        $filename = 'System_Export_' . date('Ymd_His') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; "); 

        // Write the data directly to the output buffer
        $file = fopen('php://output', 'w');
        
        // SECTION 1: PROPERTIES
        fputcsv($file, ['--- EXPORT: PROPERTY LISTINGS ---']);
        fputcsv($file, ['ID', 'Title', 'Listing Type', 'Tax Price', 'City ID', 'Approval Status', 'Created At']);
        foreach ($properties as $prop) {
            $p = (array) $prop;
            fputcsv($file, [
                $p['id'] ?? 'N/A', 
                $p['title'] ?? 'N/A', 
                $p['listing_type'] ?? 'N/A', 
                $p['tax_price'] ?? '0', 
                $p['city_id'] ?? 'N/A', 
                $p['approval_status'] ?? 'N/A', 
                $p['created_at'] ?? 'N/A'
            ]);
        }

        fputcsv($file, []); // Spacing
        fputcsv($file, []); // Spacing

        // SECTION 2: SUBSCRIPTIONS
        fputcsv($file, ['--- EXPORT: SUBSCRIPTIONS ---']);
        fputcsv($file, ['ID', 'User ID', 'Plan ID', 'Status', 'Start Date', 'End Date', 'Created At']);
        foreach ($subscriptions as $sub) {
            $s = (array) $sub;
            fputcsv($file, [
                $s['id'] ?? 'N/A', 
                $s['user_id'] ?? 'N/A', 
                $s['plan_id'] ?? 'N/A', 
                $s['sub_status'] ?? 'N/A', 
                $s['start_date'] ?? 'N/A', 
                $s['end_date'] ?? 'N/A', 
                $s['created_at'] ?? 'N/A'
            ]);
        }
        
        fclose($file);
        exit; // Stop execution so no HTML is rendered into the CSV
    }
}