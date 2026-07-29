<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PoiSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Wipe existing POIs for a clean slate
        $db->table('points_of_interest')->emptyTable();

        $pois = [
            // Bali
            ['name' => 'Denpasar Central Market', 'category' => 'Other', 'latitude' => -8.6705, 'longitude' => 115.2128],
            ['name' => 'Badung Regional Hospital', 'category' => 'Hospital', 'latitude' => -8.5816, 'longitude' => 115.1772],
            ['name' => 'Gianyar Art School', 'category' => 'School', 'latitude' => -8.5385, 'longitude' => 115.3259],
            ['name' => 'Buleleng Train Station', 'category' => 'Station', 'latitude' => -8.1120, 'longitude' => 115.0884],
            ['name' => 'Tabanan Grand Mall', 'category' => 'Mall', 'latitude' => -8.5372, 'longitude' => 115.1257],

            // Jawa Barat (NOTE: Depok is intentionally omitted for manual testing)
            ['name' => 'Bandung Institute of Tech', 'category' => 'School', 'latitude' => -6.9175, 'longitude' => 107.6191],
            ['name' => 'Bogor Botanical Station', 'category' => 'Station', 'latitude' => -6.5971, 'longitude' => 106.7902],
            ['name' => 'Bekasi Cyber Mall', 'category' => 'Mall', 'latitude' => -6.2383, 'longitude' => 106.9756],
            ['name' => 'Cimahi Military Hospital', 'category' => 'Hospital', 'latitude' => -6.8725, 'longitude' => 107.5456],

            // DKI Jakarta
            ['name' => 'South Jakarta International School', 'category' => 'School', 'latitude' => -6.2615, 'longitude' => 106.8106],
            ['name' => 'Plaza Indonesia', 'category' => 'Mall', 'latitude' => -6.1805, 'longitude' => 106.8284],
            ['name' => 'West Jakarta General Hospital', 'category' => 'Hospital', 'latitude' => -6.1683, 'longitude' => 106.7588],
            ['name' => 'Tanjung Priok Station', 'category' => 'Station', 'latitude' => -6.1384, 'longitude' => 106.8869],
            ['name' => 'East Jakarta Cultural Center', 'category' => 'Other', 'latitude' => -6.2250, 'longitude' => 106.9004],
        ];

        foreach ($pois as $poi) {
            $poi['status'] = 'Active';
            $poi['created_at'] = date('Y-m-d H:i:s');
            $poi['updated_at'] = date('Y-m-d H:i:s');
            $db->table('points_of_interest')->insert($poi);
        }

        echo "Seeded " . count($pois) . " Points of Interest successfully! (Depok omitted for manual entry)\n";
    }
}