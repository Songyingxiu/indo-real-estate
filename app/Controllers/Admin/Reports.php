<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\SubscriptionModel;

class Reports extends BaseController
{
    public function export()
    {
        // Security check: Only Admin (Role 4) should be able to download reports
        if (session()->get('role_id') != 4) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $propertyModel = new PropertyModel();
        $subscriptionModel = new SubscriptionModel();

        // Fetch Data
        $properties = $propertyModel->findAll();
        $subscriptions = $subscriptionModel->findAll();

        // Define the Filename with today's date
        $filename = 'HuniKita_Platform_Report_' . date('Y-m-d') . '.csv';

        // Set Headers to force browser to download the file as a CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output stream
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM to ensure Excel reads special characters correctly
        fputs($output, "\xEF\xBB\xBF");

        // Define the delimiter as a semicolon for Excel compatibility
        $delimiter = ';';

        // section 1 meta data
        fputcsv($output, ['HuniKita Platform Report'], $delimiter);
        fputcsv($output, ['Generated On:', date('F j, Y, g:i a')], $delimiter);
        fputcsv($output, [], $delimiter); // Blank Row

        // section 2 properties data
        fputcsv($output, ['--- PROPERTIES LISTING ---'], $delimiter);
        // Column Headers
        fputcsv($output, ['Property ID', 'Title', 'Listing Type', 'Status', 'Tax Price', 'Created Date'], $delimiter);
        
        // Loop through Properties
        foreach ($properties as $prop) {
            fputcsv($output, [
                $prop['id'],
                $prop['title'] ?? 'N/A',
                $prop['listing_type'] ?? 'N/A',
                $prop['status'] ?? 'N/A',
                'Rp ' . number_format((float)($prop['tax_price'] ?? 0), 0, ',', '.'),
                date('d M Y', strtotime($prop['created_date'] ?? date('Y-m-d')))
            ], $delimiter);
        }

        fputcsv($output, [], $delimiter); // Blank Row
        fputcsv($output, [], $delimiter); // Blank Row

        // section 3: subscription data
        fputcsv($output, ['--- PLATFORM SUBSCRIPTIONS ---'], $delimiter);
        // Column Headers
        fputcsv($output, ['Subscription ID', 'User ID', 'Plan ID', 'Status', 'Start Date', 'End Date'], $delimiter);
        
        // Loop through Subscriptions
        foreach ($subscriptions as $sub) {
            fputcsv($output, [
                $sub->id,
                $sub->user_id ?? 'N/A',
                $sub->plan_id ?? 'N/A',
                $sub->status ?? 'N/A',
                date('d M Y', strtotime($sub->start_date ?? date('Y-m-d'))),
                date('d M Y', strtotime($sub->end_date ?? date('Y-m-d')))
            ], $delimiter);
        }

        // Close the stream and exit
        fclose($output);
        exit();
    }
}