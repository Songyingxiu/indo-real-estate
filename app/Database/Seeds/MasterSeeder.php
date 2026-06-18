<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Seed Roles (Visitor removed)
        $roles = [
            ['id' => 1, 'name' => 'Buyer', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Owner', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Agent', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Admin', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('roles')->insertBatch($roles);

        // Seed States
        $states = [
            ['id' => 1, 'name' => 'DKI Jakarta', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Jawa Barat',  'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('states')->insertBatch($states);

        // Seed Cities
        $cities = [
            ['id' => 1, 'state_id' => 1, 'name' => 'Jakarta Selatan', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'state_id' => 2, 'name' => 'Bandung',         'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('cities')->insertBatch($cities);

        // Seed Zipcodes
        $zipcodes = [
            ['id' => 1, 'city_id' => 1, 'zipcode' => '12430', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')], // Jaksel
            ['id' => 2, 'city_id' => 2, 'zipcode' => '40115', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')], // Bandung
        ];
        $this->db->table('zipcodes')->insertBatch($zipcodes);

        // Seed Property Types
        $propertyTypes = [
            ['id' => 1, 'name' => 'House',     'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'Apartment', 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'Villa',     'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'Land',      'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('property_types')->insertBatch($propertyTypes);
    }
}