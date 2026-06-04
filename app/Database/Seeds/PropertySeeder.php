<?php 

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PropertySeeder extends Seeder 
{
    public function run() 
    {
        // 1. Fetch the correct newly seeded users
        $owner = $this->db->table('users')->where('email', 'taka.owner@indo-realestate.id')->get()->getRow();
        $agent = $this->db->table('users')->where('email', 'mika.agent@indo-realestate.id')->get()->getRow();
        $buyer = $this->db->table('users')->where('email', 'riksa.buyer@gmail.com')->get()->getRow();

        // 2. Map properties to the new owner ($owner->id)
        $properties = [
            [
                'owner_id'         => $owner->id, 
                'property_type_id' => 1, // House
                'location_id'      => 1, // Bekasi
                'title'            => 'Burgundy Residence Summarecon Bekasi Full Renovasi',
                'description'      => 'DIJUAL RUMAH SIAP HUNI DI BURGUNDY - BANGUNAN LUAS, FUNGSIONAL, DAN SUDAH FULL RENOVASI. 2,5 Lantai Siap Huni. Luas Tanah: 91 m², Luas Bangunan: 84 m². 5 Kamar Tidur, 3 Kamar Mandi.',
                'price'            => 2450000000.00, // Rp 2.45 Billion
                'listing_type'     => 'Sale', 
                'status'           => 'Published',
                'created_at'       => Time::now(), 
                'updated_at'       => Time::now()
            ],
            [
                'owner_id'         => $owner->id, 
                'property_type_id' => 9, // Ruko
                'location_id'      => 2, // Rawamangun
                'title'            => 'Sewa Ruko Rawamangun 2 Lantai Bekas Percetakan',
                'description'      => 'Disewakan Ruko Rawamangun (bentuk Ngantong/Hoki) di Rawamangun. Luas Bangunan 167m² bentuk L (lebar di belakang). 2 Lantai, 1 Kamar Mandi.',
                'price'            => 350000000.00, // Rp 350 million/year
                'listing_type'     => 'Rent', 
                'status'           => 'Published',
                'created_at'       => Time::now(), 
                'updated_at'       => Time::now()
            ],
            [
                'owner_id'         => $owner->id, 
                'property_type_id' => 3, // Apartment
                'location_id'      => 3, // Kemang
                'title'            => 'Apartemen Kemang Village Tower Tiffany 2 Bed Plus 1',
                'description'      => 'Jual Apartemen Kemang Village 2 Bedroom+1, 2 Bathroom+1. Luas 144 m², Tower Tiffany Lantai Rendah. View City, Full Furnished.',
                'price'            => 3400000000.00, // Rp 3.4 Billion
                'listing_type'     => 'Sale', 
                'status'           => 'Published',
                'created_at'       => Time::now(), 
                'updated_at'       => Time::now()
            ],
            [
                'owner_id'         => $owner->id, 
                'property_type_id' => 2, // Villa
                'location_id'      => 4, // Canggu
                'title'            => 'Villa Canggu Kolam Renang Eksklusif 2 Kamar Tidur',
                'description'      => 'DI JUAL Villa Canggu Kolam Renang Eksklusif 2 Kamar Tidur Domisili Villas Canggu! Luas tanah 120 m², Luas bangunan 108 m². Full furnished, siap huni.',
                'price'            => 5500000000.00, // Rp 5.5 Billion
                'listing_type'     => 'Sale', 
                'status'           => 'Published',
                'created_at'       => Time::now(), 
                'updated_at'       => Time::now()
            ]
        ];
        $this->db->table('properties')->insertBatch($properties);

        // 3. Seed an initial pipeline lead mapping with the new buyer and agent
        $insertedProperty = $this->db->table('properties')->get()->getRow()->id;
        $this->db->table('leads')->insert([
            'property_id' => $insertedProperty,
            'buyer_id'    => $buyer->id,
            'agent_id'    => $agent->id,
            'source'      => 'Contact Form',
            'status'      => 'New',
            'created_at'  => Time::now(),
            'updated_at'  => Time::now()
        ]);
    }
}