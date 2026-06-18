<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeatures extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'category_id'   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        $this->forge->addForeignKey('category_id', 'feature_categories', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('features');
    }

    public function down() { 
        $this->forge->dropTable('features', true); 
    }
}