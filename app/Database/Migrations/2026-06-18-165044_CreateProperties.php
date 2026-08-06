<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProperties extends Migration {
    public function up() {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'owner_id'              => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'property_type_id'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'city_id'               => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'zipcode_id'            => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'title'                 => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'                  => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'           => ['type' => 'TEXT', 'null' => true],
            'listing_type'          => ['type' => 'ENUM', 'constraint' => ['Sale', 'Rent']],
            'address_line_1'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'address_line_2'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'area_name'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'unit_number'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'building_society_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'latitude'              => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude'             => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'year_built'            => ['type' => 'YEAR', 'null' => true],
            'total_floors'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'bed'                   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'bath'                  => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'total_area'            => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'total_land_area'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'usable_area'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'parking'               => ['type' => 'ENUM', 'constraint' => ['Y', 'N'], 'default' => 'N'],
            'total_parking'         => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'basement'              => ['type' => 'ENUM', 'constraint' => ['Y', 'N'], 'default' => 'N'],
            'water_facility'        => ['type' => 'ENUM', 'constraint' => ['Y', 'N'], 'default' => 'N'],
            'video_url'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tax_price'             => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'property_tax_number'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'approval_status'       => ['type' => 'ENUM', 'constraint' => ['Draft', 'Pending Review', 'Approved', 'Published', 'Expired', 'Archived'], 'default' => 'Draft'],
            'status'                => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'          => ['type' => 'DATETIME', 'null' => true],
            'modified_date'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        // Add foreign keys targeting the correct master tables
        $this->forge->addForeignKey('owner_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('property_type_id', 'property_types', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('city_id', 'cities', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('zipcode_id', 'zipcodes', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('properties');
    }
    
    public function down() { 
        $this->forge->dropTable('properties', true); 
    }
}