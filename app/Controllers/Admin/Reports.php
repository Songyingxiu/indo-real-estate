<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\SubscriptionModel;

class Reports extends BaseController
{
    public function export()
    {
        if (session()->get('role_id') != 4) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $propertyModel = new PropertyModel();
        $subscriptionModel = new SubscriptionModel();

        $properties = $propertyModel->findAll();
        
        $subscriptionModel->select('subscriptions.*, users.first_name, users.last_name, subscription_plans.name as plan_name')
                          ->join('users', 'users.id = subscriptions.user_id', 'left')
                          ->join('subscription_plans', 'subscription_plans.id = subscriptions.plan_id', 'left');
                          
        $subscriptions = $subscriptionModel->findAll();

        $filename = 'HuniKita_Platform_Report_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        fputs($output, "\xEF\xBB\xBF");

        $delimiter = ';';

        fputcsv($output, ['HuniKita Platform Report'], $delimiter);
        fputcsv($output, ['Generated On:', date('F j, Y, g:i a')], $delimiter);
        fputcsv($output, [], $delimiter);

        fputcsv($output, ['--- PROPERTIES LISTING ---'], $delimiter);
        fputcsv($output, ['Property ID', 'Title', 'Listing Type', 'Status', 'Tax Price', 'Created Date'], $delimiter);
        
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

        fputcsv($output, [], $delimiter);
        fputcsv($output, [], $delimiter);

        fputcsv($output, ['--- PLATFORM SUBSCRIPTIONS ---'], $delimiter);
        
        fputcsv($output, ['Subscription ID', 'User Name', 'Plan Name', 'Status', 'Start Date', 'End Date'], $delimiter);
        
        foreach ($subscriptions as $sub) {
            
            $userName = trim(($sub->first_name ?? '') . ' ' . ($sub->last_name ?? ''));
            
            fputcsv($output, [
                $sub->id,
                $userName ?: 'N/A',
                $sub->plan_name ?? 'N/A',
                $sub->status ?? 'N/A',
                date('d M Y', strtotime($sub->start_date ?? date('Y-m-d'))),
                date('d M Y', strtotime($sub->end_date ?? date('Y-m-d')))
            ], $delimiter);
        }

        fclose($output);
        exit();
    }
}