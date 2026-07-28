<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePointsOfInterest extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'category'      => ['type' => 'ENUM', 'constraint' => ['School', 'Station', 'Hospital', 'Mall', 'Other'], 'default' => 'Other'],
            // Decimal 10,8 and 11,8 are standard for precise map coordinates
            'latitude'      => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude'     => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('points_of_interest');
    }

    public function down() { 
        $this->forge->dropTable('points_of_interest', true); 
    }
}