<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $date = date('Y-m-d H:i:s');

        // 1. Seed Roles
        $roles = [
            ['id' => 1, 'name' => 'Buyer', 'status' => 'Active', 'created_date' => $date],
            ['id' => 2, 'name' => 'Owner', 'status' => 'Active', 'created_date' => $date],
            ['id' => 3, 'name' => 'Agent', 'status' => 'Active', 'created_date' => $date],
            ['id' => 4, 'name' => 'Admin', 'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('roles')->ignore(true)->insertBatch($roles);

        // 2. Seed States (Provinces)
        $states = [
            ['id' => 1, 'name' => 'DKI Jakarta', 'status' => 'Active', 'created_date' => $date],
            ['id' => 2, 'name' => 'Jawa Barat',  'status' => 'Active', 'created_date' => $date],
            ['id' => 3, 'name' => 'Jawa Timur',  'status' => 'Active', 'created_date' => $date],
            ['id' => 4, 'name' => 'Bali',        'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('states')->ignore(true)->insertBatch($states);

        // 3. Seed Cities
        $cities = [
            ['id' => 1, 'state_id' => 1, 'name' => 'Jakarta Selatan', 'status' => 'Active', 'created_date' => $date],
            ['id' => 2, 'state_id' => 1, 'name' => 'Jakarta Barat',   'status' => 'Active', 'created_date' => $date],
            ['id' => 3, 'state_id' => 2, 'name' => 'Bandung',         'status' => 'Active', 'created_date' => $date],
            ['id' => 4, 'state_id' => 2, 'name' => 'Bekasi',          'status' => 'Active', 'created_date' => $date],
            ['id' => 5, 'state_id' => 3, 'name' => 'Surabaya',        'status' => 'Active', 'created_date' => $date],
            ['id' => 6, 'state_id' => 4, 'name' => 'Denpasar',        'status' => 'Active', 'created_date' => $date],
            ['id' => 7, 'state_id' => 4, 'name' => 'Badung',          'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('cities')->ignore(true)->insertBatch($cities);

        // 4. Seed Zipcodes
        $zipcodes = [
            ['id' => 1, 'city_id' => 1, 'zipcode' => '12430', 'status' => 'Active', 'created_date' => $date], // Cilandak/Jaksel
            ['id' => 2, 'city_id' => 1, 'zipcode' => '12190', 'status' => 'Active', 'created_date' => $date], // Kebayoran
            ['id' => 3, 'city_id' => 3, 'zipcode' => '40115', 'status' => 'Active', 'created_date' => $date], // Bandung
            ['id' => 4, 'city_id' => 4, 'zipcode' => '17111', 'status' => 'Active', 'created_date' => $date], // Bekasi
            ['id' => 5, 'city_id' => 5, 'zipcode' => '60271', 'status' => 'Active', 'created_date' => $date], // Surabaya
            ['id' => 6, 'city_id' => 6, 'zipcode' => '80113', 'status' => 'Active', 'created_date' => $date], // Denpasar
            ['id' => 7, 'city_id' => 7, 'zipcode' => '80361', 'status' => 'Active', 'created_date' => $date], // Badung
        ];
        $this->db->table('zipcodes')->ignore(true)->insertBatch($zipcodes);

        // 5. Seed Property Types
        $propertyTypes = [
            ['id' => 1, 'name' => 'House',     'status' => 'Active', 'created_date' => $date],
            ['id' => 2, 'name' => 'Apartment', 'status' => 'Active', 'created_date' => $date],
            ['id' => 3, 'name' => 'Villa',     'status' => 'Active', 'created_date' => $date],
            ['id' => 4, 'name' => 'Land',      'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('property_types')->ignore(true)->insertBatch($propertyTypes);

        // 6. Seed Features (Amenities)
        $features = [
            ['name' => 'Swimming Pool', 'status' => 'Active', 'created_date' => $date],
            ['name' => 'Wi-Fi', 'status' => 'Active', 'created_date' => $date],
            ['name' => '24/7 Security', 'status' => 'Active', 'created_date' => $date],
            ['name' => 'Smart Home System', 'status' => 'Active', 'created_date' => $date],
            ['name' => '2-Car Garage', 'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('features')->ignore(true)->insertBatch($features);

        // 7. Seed Subscription Plans (Strictly aligned with CreateSubscriptionPlans migration)
        $subscriptions = [
            ['name' => 'Basic Agent', 'price' => 0.00, 'status' => 'Active', 'created_date' => $date],
            ['name' => 'Pro Agent', 'price' => 499000.00, 'status' => 'Active', 'created_date' => $date],
            ['name' => 'Agency Enterprise', 'price' => 1500000.00, 'status' => 'Active', 'created_date' => $date],
        ];
        $this->db->table('subscription_plans')->ignore(true)->insertBatch($subscriptions);
    }
}