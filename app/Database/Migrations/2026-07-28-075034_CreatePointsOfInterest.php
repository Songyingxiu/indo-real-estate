<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePointsOfInterest extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'property_id'   => ['type' => 'INT', 'constraint' => 11],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'category'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'latitude'      => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude'     => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'distance_km'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('points_of_interest');
    }

    public function down() { 
        $this->forge->dropTable('points_of_interest', true); 
    }
}