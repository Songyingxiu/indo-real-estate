<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateRoles extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');
    }
    public function down() { $this->forge->dropTable('roles', true); }
}