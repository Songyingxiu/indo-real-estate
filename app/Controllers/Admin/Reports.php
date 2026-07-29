<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\InquiryModel;

class Reports extends BaseController 
{
    public function export() 
    {
        if (session()->get('role_id') != 4) return redirect()->to(base_url('admin/dashboard'));

        $userModel = new UserModel();
        $propertyModel = new PropertyModel();
        $inquiryModel = new InquiryModel();

        // Gather quick stats from existing tables
        $totalUsers = $userModel->countAllResults();
        $totalProperties = $propertyModel->countAllResults();
        $totalInquiries = $inquiryModel->countAllResults();

        // Setup CSV headers for download
        $filename = 'System_Overview_Report_' . date('Ymd_His') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; "); 

        // Write the data directly to the output buffer
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Report Type', 'System Overview']);
        fputcsv($file, ['Generated On', date('M d, Y H:i:s')]);
        fputcsv($file, []); // Blank row for spacing
        fputcsv($file, ['Metric', 'Total Count']);
        fputcsv($file, ['Registered Users', $totalUsers]);
        fputcsv($file, ['Property Listings', $totalProperties]);
        fputcsv($file, ['Buyer Inquiries Generated', $totalInquiries]);
        
        fclose($file);
        exit; // Stop execution so no HTML is rendered into the CSV
    }
}