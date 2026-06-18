<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertyFeatures extends Migration {
    public function up() {
        $this->forge->addField([
            'property_id'   => ['type' => 'INT', 'constraint' => 11],
            'feature_id'    => ['type' => 'INT', 'constraint' => 11],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
      
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('feature_id', 'features', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('property_features');
    }

    public function down() { 
        $this->forge->dropTable('property_features', true); 
    }
}