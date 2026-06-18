<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateZipcodes extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'city_id'       => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'zipcode'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        // Links to cities table
        $this->forge->addForeignKey('city_id', 'cities', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('zipcodes');
    }

    public function down() { 
        $this->forge->dropTable('zipcodes', true); 
    }
}