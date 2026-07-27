<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Insert States (Provinces)
        $states = [
            ['name' => 'Bali', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['name' => 'West Java', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['name' => 'Jakarta', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')]
        ];
        $this->db->table('states')->insertBatch($states);

        // Fetch IDs to link relationships
        $baliId = $this->db->table('states')->where('name', 'Bali')->get()->getRow()->id;
        $westJavaId = $this->db->table('states')->where('name', 'West Java')->get()->getRow()->id;

        // 2. Insert Cities
        $cities = [
            ['state_id' => $baliId, 'name' => 'Denpasar', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['state_id' => $baliId, 'name' => 'Ubud', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['state_id' => $westJavaId, 'name' => 'Bandung', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['state_id' => $westJavaId, 'name' => 'Bogor', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('cities')->insertBatch($cities);

        $denpasarId = $this->db->table('cities')->where('name', 'Denpasar')->get()->getRow()->id;
        $bandungId = $this->db->table('cities')->where('name', 'Bandung')->get()->getRow()->id;

        // 3. Insert Dummy Properties
        $properties = [
            [
                'city_id' => $denpasarId,
                'title' => 'Luxury Villa Seminyak',
                'listing_type' => 'Sale',
                'area_name' => 'Seminyak, Bali',
                'latitude' => -8.6838,
                'longitude' => 115.1630,
                'bed' => 4,
                'bath' => 3,
                'total_area' => 350.00,
                'tax_price' => 5500000000.00,
                'approval_status' => 'Published',
                'status' => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ],
            [
                'city_id' => $bandungId,
                'title' => 'Dago Pakar Modern House',
                'listing_type' => 'Sale',
                'area_name' => 'Dago, Bandung',
                'latitude' => -6.8643,
                'longitude' => 107.6256,
                'bed' => 3,
                'bath' => 2,
                'total_area' => 210.00,
                'tax_price' => 3200000000.00,
                'approval_status' => 'Published',
                'status' => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ],
            [
                'city_id' => $denpasarId,
                'title' => 'Sanur Beachfront Apartment',
                'listing_type' => 'Rent',
                'area_name' => 'Sanur, Bali',
                'latitude' => -8.6946,
                'longitude' => 115.2638,
                'bed' => 2,
                'bath' => 2,
                'total_area' => 120.00,
                'tax_price' => 150000000.00,
                'approval_status' => 'Published',
                'status' => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('properties')->insertBatch($properties);

        // 4. Insert CMS FAQs (Assuming user ID 1 is a valid author)
        $faqs = [
            [
                'title' => 'How do I search for properties in a specific city?',
                'slug' => 'faq-how-to-search-city',
                'category' => 'FAQ',
                'content_body' => '<p>You can use the search bar on our homepage. Type the city name (e.g., "Denpasar") and click the suggestion to see all properties exclusively in that region.</p>',
                'author_id' => 1,
                'status' => 'Published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'How can I contact a property owner?',
                'slug' => 'faq-contact-owner',
                'category' => 'FAQ',
                'content_body' => '<p>Once you navigate to a specific property detail page, log into your account and use the "Send Inquiry" button to message the owner directly.</p>',
                'author_id' => 1,
                'status' => 'Published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('cms_posts')->insertBatch($faqs);
    }
}