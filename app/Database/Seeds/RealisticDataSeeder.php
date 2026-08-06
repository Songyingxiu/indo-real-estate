<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RealisticDataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // 1. FRESH START: Disable foreign key checks and truncate transactional tables
        $db->query('SET FOREIGN_KEY_CHECKS=0;');
        
        $tablesToClear = [
            'properties', 'property_images', 'property_features', 'property_feature_map', 
            'points_of_interest', 'inquiries', 'saved_properties', 'saved_searches', 
            'advertisements', 'agent_verifications', 'property_verifications', 
            'offline_payments', 'subscriptions', 'ci_sessions',
            'states', 'cities', 'zipcodes', 'users'
        ];
        
        foreach ($tablesToClear as $table) {
            $db->table($table)->truncate();
        }
        
        $db->query('SET FOREIGN_KEY_CHECKS=1;');
        echo "Database wiped successfully. Retained all configurations.\n";

        // 2. Create the exact Users from the provided database records
        $users = [
            ['id' => 1, 'role_id' => 4, 'first_name' => 'Reza', 'last_name' => 'Avanluna', 'phone_number' => '081234567890', 'email' => 'reza@estate.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 2, 'role_id' => 3, 'first_name' => 'Taka', 'last_name' => 'Radjiman', 'phone_number' => '081234567891', 'email' => 'taka@agent.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 3, 'role_id' => 2, 'first_name' => 'Amacia', 'last_name' => 'Michella', 'phone_number' => '081234567892', 'email' => 'amacia@owner.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 4, 'role_id' => 2, 'first_name' => 'Miyu', 'last_name' => 'Ottavia', 'phone_number' => '081234567893', 'email' => 'miyu@owner.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 5, 'role_id' => 3, 'first_name' => 'Bonnivier', 'last_name' => 'Pranaja', 'phone_number' => '081234567894', 'email' => 'bonni@agent.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 6, 'role_id' => 1, 'first_name' => 'Riksa', 'last_name' => 'Dhirendra', 'phone_number' => '081234567895', 'email' => 'riksa@buyer.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')],
            ['id' => 7, 'role_id' => 1, 'first_name' => 'Etna', 'last_name' => 'Crimson', 'phone_number' => '081234567896', 'email' => 'etna@buyer.com', 'password' => password_hash('password123', PASSWORD_BCRYPT), 'status' => 'Active', 'created_date' => date('Y-m-d H:i:s')]
        ];
        $db->table('users')->insertBatch($users);
        
        // Define arrays of valid IDs for randomization
        $ownerAgentIds = [2, 3, 4, 5]; // Taka, Amacia, Miyu, Bonnivier
        $buyerIds = [6, 7]; // Riksa, Etna

        // 3. Load JSON Data
        $jsonFile = WRITEPATH . 'uploads/massive_seed_data.json';
        if (!file_exists($jsonFile)) {
            echo "JSON file not found. Please run python scraper.py first.\n";
            return;
        }
        $properties = json_decode(file_get_contents($jsonFile), true);

        // Builders
        $propertyBuilder = $db->table('properties');
        $imageBuilder = $db->table('property_images');
        $poiBuilder = $db->table('points_of_interest');

        $count = 0;
        foreach ($properties as $prop) {
            $stateId = $this->getOrCreateState($prop['province_name']);
            $cityId = $this->getOrCreateCity($prop['city_name'], $stateId);
            $typeId = $this->getOrCreateType($prop['property_type_name']);
            
            // Randomly assign one of the 4 agents/owners
            $assignedOwnerId = $ownerAgentIds[array_rand($ownerAgentIds)];

            $propertyData = [
                'owner_id'         => $assignedOwnerId,
                'city_id'          => $cityId,
                'property_type_id' => $typeId,
                'title'            => $prop['title'],
                'slug'             => url_title($prop['title'] . '-' . uniqid(), '-', true),
                'description'      => $prop['description'],
                'listing_type'     => $prop['listing_type'],
                'tax_price'        => $prop['tax_price'],
                'bed'              => $prop['bed'],
                'bath'             => $prop['bath'],
                'total_area'       => $prop['total_area'],
                'latitude'         => $prop['latitude'],
                'longitude'        => $prop['longitude'],
                'status'           => 'Active',
                'approval_status'  => 'Published',
                'created_date'     => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
            ];
            
            $propertyBuilder->insert($propertyData);
            $propertyId = $db->insertID();

            // Insert Images
            $imageData = [];
            foreach ($prop['images'] as $index => $imageUrl) {
                $imageData[] = [
                    'property_id' => $propertyId,
                    'image_path'  => $imageUrl,
                    'is_primary'  => $index === 0 ? 1 : 0
                ];
            }
            $imageBuilder->insertBatch($imageData);

            // Insert POIs
            $poiData = [];
            foreach ($prop['pois'] as $poi) {
                $poiData[] = [
                    'property_id' => $propertyId,
                    'name'        => $poi['name'],
                    'category'    => $poi['category'],
                    'latitude'    => $poi['latitude'],
                    'longitude'   => $poi['longitude'],
                    'distance_km' => $poi['distance_km'],
                    'created_at'  => date('Y-m-d H:i:s')
                ];
            }
            $poiBuilder->insertBatch($poiData);

            // Create inquiries for ~25% of properties
            if (rand(1, 100) <= 25) {
                $assignedBuyerId = $buyerIds[array_rand($buyerIds)];
                $this->createDummyInquiry($propertyId, $assignedBuyerId, $assignedOwnerId);
            }

            $count++;
        }

        echo "Seeded $count unique properties with POIs! Assigned correctly to Taka, Amacia, Miyu, and Bonnivier. Inquiries sent by Riksa and Etna.\n";
    }

    private function getOrCreateState($name) {
        $db = \Config\Database::connect();
        $row = $db->table('states')->where('name', $name)->get()->getRow();
        if ($row) return $row->id;
        $db->table('states')->insert(['name' => $name, 'status' => 'Active']);
        return $db->insertID();
    }

    private function getOrCreateCity($name, $stateId) {
        $db = \Config\Database::connect();
        $row = $db->table('cities')->where('name', $name)->get()->getRow();
        if ($row) return $row->id;
        $db->table('cities')->insert(['state_id' => $stateId, 'name' => $name, 'status' => 'Active']);
        return $db->insertID();
    }

    private function getOrCreateType($name) {
        $db = \Config\Database::connect();
        $row = $db->table('property_types')->where('type_name', $name)->get()->getRow();
        if ($row) return $row->id;
        $db->table('property_types')->insert(['type_name' => $name, 'status' => 'Active']);
        return $db->insertID();
    }

    private function createDummyInquiry($propertyId, $buyerId, $ownerId) {
        $db = \Config\Database::connect();
        $statuses = ['Pending', 'In Discussion', 'Negotiating', 'Under Contract'];
        $db->table('inquiries')->insert([
            'property_id' => $propertyId,
            'sender_id'   => $buyerId,
            'receiver_id' => $ownerId,
            'message'     => 'Hi, I saw this property on HuniKita and I am very interested. Can we schedule a viewing?',
            'status'      => $statuses[array_rand($statuses)],
            'created_at'  => date('Y-m-d H:i:s', strtotime('-' . rand(1, 7) . ' days'))
        ]);
    }
}