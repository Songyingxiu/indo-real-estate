<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreatePropertyTypesTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'   => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('property_types');
    }
    public function down() { $this->forge->dropTable('property_types', true); }
}