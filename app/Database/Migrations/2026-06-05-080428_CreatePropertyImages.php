<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertyImages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'property_id' => ['type' => 'INT', 'constraint' => 11],
            'image_path'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_primary'  => ['type' => 'BOOLEAN', 'default' => false],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('property_images');
    }

    public function down()
    {
        $this->forge->dropTable('property_images', true);
    }
}