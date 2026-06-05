<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateLocations extends Migration {
    public function up() {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'region_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'latitude'    => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude'   => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('locations');
    }
    public function down() { $this->forge->dropTable('locations', true); }
}