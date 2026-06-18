<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCities extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'state_id'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        // Links to states table
        $this->forge->addForeignKey('state_id', 'states', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('cities');
    }

    public function down() { 
        $this->forge->dropTable('cities', true); 
    }
}