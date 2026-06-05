<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateProperties extends Migration {
    public function up() {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'owner_id'         => ['type' => 'INT', 'constraint' => 11],
            'property_type_id' => ['type' => 'INT', 'constraint' => 11],
            'location_id'      => ['type' => 'INT', 'constraint' => 11],
            'title'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'      => ['type' => 'TEXT', 'null' => true],
            'price'            => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'listing_type'     => ['type' => 'ENUM', 'constraint' => ['Sale', 'Rent']],
            'status'           => ['type' => 'ENUM', 'constraint' => ['Draft', 'Pending Review', 'Approved', 'Published', 'Expired', 'Archived'], 'default' => 'Draft'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('owner_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('property_type_id', 'property_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('properties');
    }
    public function down() { $this->forge->dropTable('properties', true); }
}