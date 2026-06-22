<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Roles
        $roles = [
            ['id' => 1, 'name' => 'Buyer', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Owner', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Agent', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Admin', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('roles')->ignore(true)->insertBatch($roles);

        // 2. Seed States
        $states = [
            ['id' => 1, 'name' => 'DKI Jakarta', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Jawa Barat',  'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('states')->ignore(true)->insertBatch($states);

        // 3. Seed Cities
        $cities = [
            ['id' => 1, 'state_id' => 1, 'name' => 'Jakarta Selatan', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'state_id' => 2, 'name' => 'Bandung',         'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('cities')->ignore(true)->insertBatch($cities);

        // 4. Seed Zipcodes
        $zipcodes = [
            ['id' => 1, 'city_id' => 1, 'zipcode' => '12430', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')], // Cilandak/Jaksel
            ['id' => 2, 'city_id' => 2, 'zipcode' => '40115', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')], // Bandung
        ];
        $this->db->table('zipcodes')->ignore(true)->insertBatch($zipcodes);

        // 5. Seed Property Types
        $propertyTypes = [
            ['id' => 1, 'name' => 'House',     'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Apartment', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Villa',     'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Land',      'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('property_types')->ignore(true)->insertBatch($propertyTypes);

        // 6. Users Table 
        $users = [
            [
                'id'           => 1,
                'role_id'      => 4,
                'first_name'   => 'Reza',
                'last_name'    => 'Avanluna',
                'phone_number' => '081234567890',
                'email'        => 'reza@estate.com',
                'password'     => password_hash('password123', PASSWORD_BCRYPT),
                'status'       => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ],
            [
                'id'           => 2,
                'role_id'      => 3, 
                'first_name'   => 'Taka',
                'last_name'    => 'Radjiman',
                'phone_number' => '081234567891',
                'email'        => 'taka@agent.com',
                'password'     => password_hash('password123', PASSWORD_BCRYPT),
                'status'       => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ],
            [
                'id'           => 3,
                'role_id'      => 2, 
                'first_name'   => 'Amacia',
                'last_name'    => 'Michella',
                'phone_number' => '081234567892',
                'email'        => 'amacia@owner.com',
                'password'     => password_hash('password123', PASSWORD_BCRYPT),
                'status'       => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ],
            [
                'id'           => 4,
                'role_id'      => 1, 
                'first_name'   => 'Riksa',
                'last_name'    => 'Dhirendra',
                'phone_number' => '081234567895',
                'email'        => 'riksa@buyer.com',
                'password'     => password_hash('password123', PASSWORD_BCRYPT),
                'status'       => 'Active',
                'created_date' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('users')->ignore(true)->insertBatch($users);

        // 7. Dummy Properties
        $properties = [
            [
                'id'               => 1,
                'owner_id'         => 2, 
                'property_type_id' => 1, 
                'city_id'          => 1, 
                'zipcode_id'       => 1,
                'title'            => 'Rumah Mewah Siap Huni di Pondok Indah, Jakarta Selatan',
                'description'      => 'Rumah mewah desain klasik modern di lokasi strategis Pondok Indah. Bebas banjir, keamanan 24 jam, dekat dengan Pondok Indah Mall dan rumah sakit. Fasilitas lengkap dengan private pool dan taman luas.',
                'listing_type'     => 'Sale',
                'address_line_1'   => 'Jl. Metro Pondok Indah',
                'area_name'        => 'Pondok Indah',
                'year_built'       => 2018,
                'total_floors'     => 2,
                'bed'              => 5,
                'bath'             => 4,
                'total_area'       => 450.00,
                'total_land_area'  => 600.00,
                'parking'          => 'Y',
                'total_parking'    => 4,
                'tax_price'        => 25000000000.00, 
                'approval_status'  => 'Published',
                'status'           => 'Active',
                'created_date'     => date('Y-m-d H:i:s')
            ],
            [
                'id'               => 2,
                'owner_id'         => 3, 
                'property_type_id' => 2, 
                'city_id'          => 2, 
                'zipcode_id'       => 2,
                'title'            => 'Sewa Apartemen Dago Suites 2BR Full Furnished',
                'description'      => 'Disewakan apartemen tipe 2 Bedroom di Dago Suites. Pemandangan kota Bandung sangat indah. Sudah full furnished termasuk AC, Water Heater, Kulkas, dan Smart TV. Dekat dengan kampus ITB dan UNPAD.',
                'listing_type'     => 'Rent',
                'address_line_1'   => 'Jl. Sangkuriang No.13',
                'area_name'        => 'Dago',
                'unit_number'      => '12A',
                'building_society_name' => 'Dago Suites Apartment',
                'year_built'       => 2020,
                'total_floors'     => 15,
                'bed'              => 2,
                'bath'             => 1,
                'total_area'       => 55.00,
                'total_land_area'  => 55.00,
                'parking'          => 'Y',
                'total_parking'    => 1,
                'tax_price'        => 85000000.00, 
                'approval_status'  => 'Pending Review', 
                'status'           => 'Active',
                'created_date'     => date('Y-m-d H:i:s')
            ],
            [
                'id'               => 3,
                'owner_id'         => 2, 
                'property_type_id' => 1, 
                'city_id'          => 1, 
                'zipcode_id'       => 1,
                'title'            => 'Rumah Minimalis Murah Dekat Stasiun MRT Lebak Bulus',
                'description'      => 'Kesempatan langka! Rumah secondary kondisi sangat terawat dengan desain minimalis tropis. Akses jalan 2 mobil, hanya 5 menit jalan kaki ke stasiun MRT Lebak Bulus.',
                'listing_type'     => 'Sale',
                'address_line_1'   => 'Jl. Karang Tengah Raya',
                'area_name'        => 'Lebak Bulus',
                'year_built'       => 2015,
                'total_floors'     => 2,
                'bed'              => 3,
                'bath'             => 2,
                'total_area'       => 120.00,
                'total_land_area'  => 105.00,
                'parking'          => 'Y',
                'total_parking'    => 2,
                'tax_price'        => 2800000000.00,
                'approval_status'  => 'Published',
                'status'           => 'Active',
                'created_date'     => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('properties')->ignore(true)->insertBatch($properties);

        // 8. Subscription Plans
        $subscriptions = [
            ['name' => 'Basic Agent', 'price' => 0.00, 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['name' => 'Pro Agent', 'price' => 499000.00, 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['name' => 'Agency Enterprise', 'price' => 1500000.00, 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('subscription_plans')->ignore(true)->insertBatch($subscriptions);

        // 9. Default SEO Settings
        $seoSettings = [
            [
                'target_page'      => 'Homepage',
                'meta_title'       => 'Situs Jual Beli Properti Terpercaya | EstateAdmin Pro',
                'meta_description' => 'Temukan rumah impian Anda. Jual, beli, dan sewa properti, rumah, apartemen, dan tanah dengan agen terverifikasi di seluruh Indonesia.',
                'focus_keywords'   => 'rumah dijual, sewa apartemen, properti jakarta, rumah123, lamudi',
                'updated_at'       => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('seo_settings')->ignore(true)->insertBatch($seoSettings);

        // 10. Initial CMS Post
        $cmsPosts = [
            [
                'title'        => 'Panduan Membeli Rumah Pertama Anda di 2026',
                'slug'         => 'panduan-membeli-rumah-pertama',
                'category'     => 'Guide',
                'content_body' => 'Membeli rumah pertama bisa menjadi hal yang menakutkan. Pastikan Anda menyiapkan DP minimal 20% dan memeriksa track record developer sebelum mengambil KPR.',
                'author_id'    => 1, // Authored by Admin
                'status'       => 'Published',
                'published_at' => date('Y-m-d H:i:s'),
                'created_at'   => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('cms_posts')->ignore(true)->insertBatch($cmsPosts);
    }
}