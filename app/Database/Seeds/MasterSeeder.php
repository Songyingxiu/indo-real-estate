<?php 

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder 
{
    public function run() 
    {
        // System Roles
        $roles = [
            ['name' => 'Visitor'], 
            ['name' => 'Buyer'], 
            ['name' => 'Property Owner'], 
            ['name' => 'Agent'], 
            ['name' => 'Admin']
        ];
        $this->db->table('roles')->insertBatch($roles);


        // Subscription Plans
        $plans = [
            [
                'name'  => 'Free', 
                'price' => 0.00
            ],
            [
                'name'  => 'Basic', 
                'price' => 150000.00
            ],
            [
                'name'  => 'Premium', 
                'price' => 500000.00
            ],
            [
                'name'  => 'Enterprise', 
                'price' => 1500000.00
            ]
        ];
        $this->db->table('subscription_plans')->insertBatch($plans);


        // Property Types
        $types = [
            ['name' => 'House'], 
            ['name' => 'Villa'], 
            ['name' => 'Apartment'],
            ['name' => 'Condominium'], 
            ['name' => 'Office'], 
            ['name' => 'Warehouse'],
            ['name' => 'Land'], 
            ['name' => 'Hotel'], 
            ['name' => 'Ruko']
        ];
        $this->db->table('property_types')->insertBatch($types);


        // Locations by Map
        $locations = [
            [
                'region_name' => 'Summarecon Bekasi, West Java', 
                'latitude'    => -6.22390000, 
                'longitude'   => 106.98960000
            ],
            [
                'region_name' => 'Rawamangun, East Jakarta', 
                'latitude'    => -6.19550000, 
                'longitude'   => 106.88350000
            ],
            [
                'region_name' => 'Kemang, South Jakarta', 
                'latitude'    => -6.26150000, 
                'longitude'   => 106.81060000
            ],
            [
                'region_name' => 'Canggu, Bali', 
                'latitude'    => -8.64780000, 
                'longitude'   => 115.13850000
            ]
        ];
        $this->db->table('locations')->insertBatch($locations);
    }
}