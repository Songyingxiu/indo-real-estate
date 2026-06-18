<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $date = date('Y-m-d H:i:s');

        $properties = [
            // Property 1: Sale - High-end House in South Jakarta
            [
                'id' => 1, 'owner_id' => 2, 'property_type_id' => 1, 'city_id' => 1, 'zipcode_id' => 1,
                'title' => 'Rumah Mewah Pondok Indah Posisi Hook Siap Huni',
                'description' => 'Dijual Rumah Mewah Di Pondok Indah Posisi Hook. Kondisi sangat terawat, lokasi pinggir jalan utama, dan dekat dengan lapangan Golf. Fasilitas premium dan lingkungan elit.',
                'listing_type' => 'Sale', 'address_line_1' => 'Jl. Metro Pondok Indah', 'area_name' => 'Kebayoran Lama', 'unit_number' => null, 'building_society_name' => 'Pondok Indah Real Estate',
                'year_built' => '2018', 'total_floors' => 2, 
                'bed' => 4, 'bath' => 3, 'total_area' => 600.00, 'total_land_area' => 335.00, 'usable_area' => 580.00,
                'parking' => 'Y', 'total_parking' => 3, 'basement' => 'N', 'water_facility' => 'Y',
                'tax_price' => 25000000000.00,
                'approval_status' => 'Published', 'status' => 'Active', 'created_date' => $date
            ],

            // Property 2: Rent - Premium Apartment in Bandung
            [
                'id' => 2, 'owner_id' => 3, 'property_type_id' => 2, 'city_id' => 2, 'zipcode_id' => 2,
                'title' => 'Sewa Apartemen Landmark Residence 2BR Cicendo',
                'description' => 'For Rent: Landmark Apartment Tower A - 2 Bedroom. Fully furnished dengan pemandangan gunung dan kota. Termasuk elektronik (water heater, AC) dan dekat ke Paskal 23.',
                'listing_type' => 'Rent', 'address_line_1' => 'Jl. Bima No.81', 'area_name' => 'Cicendo', 'unit_number' => 'Tower A 15F', 'building_society_name' => 'Landmark Residence',
                'year_built' => '2020', 'total_floors' => 1, 
                'bed' => 2, 'bath' => 1, 'total_area' => 63.00, 'total_land_area' => 63.00, 'usable_area' => 63.00,
                'parking' => 'Y', 'total_parking' => 1, 'basement' => 'Y', 'water_facility' => 'Y',
                'tax_price' => 100000000.00,
                'approval_status' => 'Published', 'status' => 'Active', 'created_date' => $date
            ],

            // Property 3: Sale - Townhouse in South Jakarta
            [
                'id' => 3, 'owner_id' => 4, 'property_type_id' => 1, 'city_id' => 1, 'zipcode_id' => 1,
                'title' => 'Townhouse Classic Modern Dekat Tol Simatupang',
                'description' => 'Townhouse Classic Modern Bangunan 2 lantai Plus Rooftop Lokasi Sangat Strategis dekat tol Simatupang dan Mall AEON Jaksel. One Gate System, aman dan nyaman.',
                'listing_type' => 'Sale', 'address_line_1' => 'Jl. TB Simatupang', 'area_name' => 'Pasar Minggu', 'unit_number' => 'Kav B', 'building_society_name' => 'Townhouse AEON',
                'year_built' => '2023', 'total_floors' => 3, 
                'bed' => 3, 'bath' => 2, 'total_area' => 150.00, 'total_land_area' => 120.00, 'usable_area' => 140.00,
                'parking' => 'Y', 'total_parking' => 2, 'basement' => 'N', 'water_facility' => 'Y',
                'tax_price' => 3800000000.00,
                'approval_status' => 'Pending Review', 'status' => 'Active', 'created_date' => $date
            ],

            // Property 4: Rent - Large House in Bandung
            [
                'id' => 4, 'owner_id' => 5, 'property_type_id' => 1, 'city_id' => 2, 'zipcode_id' => 2,
                'title' => 'Rumah Mewah Disewakan di Tatar Kamandaka, Kota Baru Parahyangan',
                'description' => 'Disewakan rumah mewah full furnished di Tatar Kamandaka KBP. Tinggal bawa koper. Lingkungan elit, asri, dengan sistem keamanan 24 jam.',
                'listing_type' => 'Rent', 'address_line_1' => 'Jl. Tatar Kamandaka Raya', 'area_name' => 'Kota Baru Parahyangan', 'unit_number' => null, 'building_society_name' => 'Kota Baru Parahyangan',
                'year_built' => '2021', 'total_floors' => 2, 
                'bed' => 4, 'bath' => 3, 'total_area' => 150.00, 'total_land_area' => 180.00, 'usable_area' => 150.00,
                'parking' => 'Y', 'total_parking' => 2, 'basement' => 'N', 'water_facility' => 'Y',
                'tax_price' => 160000000.00,
                'approval_status' => 'Published', 'status' => 'Active', 'created_date' => $date
            ]
        ];

        $this->db->table('properties')->insertBatch($properties);
    }
}