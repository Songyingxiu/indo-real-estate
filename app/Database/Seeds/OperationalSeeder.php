<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperationalSeeder extends Seeder
{
    public function run()
    {
        $date = date('Y-m-d H:i:s');

        // 1. Leads Management Data (COMMENTED OUT TO PREVENT CRASHES)
        /*
        $leads = [
            [
                'property_id'  => 1, 
                'buyer_id'     => 6, 
                'agent_id'     => 2, 
                'source'       => 'Contact Form',
                'lead_status'  => 'New', 
                'status'       => 'Active',
                'created_date' => $date
            ],
            [
                'property_id'  => 2, 
                'buyer_id'     => 7, 
                'agent_id'     => 5, 
                'source'       => 'Phone Inquiry',
                'lead_status'  => 'Contacted', 
                'status'       => 'Active',
                'created_date' => $date
            ],
        ];
        $this->db->table('leads')->ignore(true)->insertBatch($leads);
        */

        // 2. Property Verifications (Removed 'notes' and 'verified_by')
        $verifications = [
            [
                'property_id'  => 1, 
                'status'       => 'Verified', 
                'created_date' => $date
            ],
            [
                'property_id'  => 3, 
                'status'       => 'Pending', 
                'created_date' => $date
            ],
        ];
        $this->db->table('property_verifications')->ignore(true)->insertBatch($verifications);

        // 3. SEO Settings
        $seo = [
            [
                'target_page'      => 'Homepage', 
                'meta_title'       => 'Indo Real Estate | Jual Beli Properti', 
                'meta_description' => 'Platform jual beli properti terpercaya di Indonesia.', 
                'focus_keywords'   => 'jual rumah, sewa apartemen'
            ]
        ];
        $this->db->table('seo_settings')->ignore(true)->insertBatch($seo);

        // 4. CMS Management (Blog Posts)
        $cms = [
            [
                'title'        => 'Tips Memilih Lokasi Properti', 
                'slug'         => 'tips-memilih-lokasi', 
                'category'     => 'Tips', 
                'content_body' => 'Pastikan dekat dengan akses transportasi umum.', 
                'author_id'    => 1, 
                'status'       => 'Published'
            ]
        ];
        $this->db->table('cms_posts')->ignore(true)->insertBatch($cms);
    }
}