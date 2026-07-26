<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PropertyScraperSeeder extends Seeder
{
    public function run()
    {
        $jsonFile = WRITEPATH . 'uploads/massive_seed_data.json';
        
        if (!file_exists($jsonFile)) {
            echo "Error: Seed file not found at " . $jsonFile . "\n";
            return;
        }

        $properties = json_decode(file_get_contents($jsonFile), true);
        echo "Found " . count($properties) . " properties to seed.\n";

        $db = \Config\Database::connect();

        // 1. HARD WIPE USING EMPTY TABLE
        echo "Forcing database clean...\n";
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        if ($db->tableExists('property_images')) {
            $db->table('property_images')->emptyTable();
        }
        $db->table('properties')->emptyTable();
        
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        // 2. MANUAL ID OVERRIDE TO PREVENT DUPLICATES
        $propertyIdCounter = 1;

        foreach ($properties as $item) {
            
            // Get or Create Property Type ID
            $pType = $db->table('property_types')->where('name', $item['property_type_name'])->get()->getRowArray();
            if (!$pType) {
                $db->table('property_types')->insert(['name' => $item['property_type_name']]);
                $propertyTypeId = $db->insertID();
            } else {
                $propertyTypeId = $pType['id'];
            }

            // Get or Create City ID
            $city = $db->table('cities')->where('name', $item['city_name'])->get()->getRowArray();
            if (!$city) {
                $db->table('cities')->insert([
                    'name' => $item['city_name'] // <--- Removed the slug line here
                ]);
                $cityId = $db->insertID();
            } else {
                $cityId = $city['id'];
            }

            // Get or Create Zipcode ID
            $zip = $db->table('zipcodes')->where('zipcode', $item['zipcode'])->get()->getRowArray();
            if (!$zip) {
                $db->table('zipcodes')->insert([
                    'city_id' => $cityId,
                    'zipcode' => $item['zipcode']
                ]);
                $zipcodeId = $db->insertID();
            } else {
                $zipcodeId = $zip['id'];
            }

            // Insert Property Record with EXPLICIT ID
            $propertyData = [
                'id'               => $propertyIdCounter,
                'owner_id'         => 1,
                'property_type_id' => $propertyTypeId,
                'city_id'          => $cityId,
                'zipcode_id'       => $zipcodeId,
                'title'            => $item['title'],
                'description'      => $item['description'],
                'listing_type'     => $item['listing_type'],
                'address_line_1'   => 'Jl. Utama No. ' . rand(1, 100),
                'area_name'        => $item['city_name'],
                'latitude'         => $item['latitude'],
                'longitude'        => $item['longitude'],
                'bed'              => $item['bed'],
                'bath'             => $item['bath'],
                'total_area'       => $item['total_area'],
                'total_land_area'  => $item['total_land_area'],
                'tax_price'        => $item['tax_price'],
                'approval_status'  => 'Published', 
                'status'           => 'Active',
                'created_date'     => date('Y-m-d H:i:s'),
                'modified_date'    => date('Y-m-d H:i:s'),
            ];

            $db->table('properties')->insert($propertyData);
            
            // Insert Related Property Images
            if ($db->tableExists('property_images') && !empty($item['images'])) {
                $seqNo = 1;
                foreach ($item['images'] as $index => $imgUrl) {
                    $db->table('property_images')->insert([
                        'property_id'  => $propertyIdCounter,
                        'title'        => $item['title'] . ' - Image ' . $seqNo,
                        'image_path'   => $imgUrl,                   
                        'seq_no'       => $seqNo,                    
                        'is_primary'   => ($index === 0) ? 1 : 0,    
                        'status'       => 'Active',
                        'created_date' => date('Y-m-d H:i:s'),       
                        'modified_date'=> date('Y-m-d H:i:s')        
                    ]);
                    $seqNo++;
                }
            }

            $propertyIdCounter++;
        }

        echo "\nSeeding completed successfully! Total properties seeded: " . count($properties) . "\n";
    }
}