<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdvertisementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'placement' => [
                'type'       => 'ENUM',
                'constraint' => ['home_banner', 'sidebar', 'property_list'],
                'default'    => 'home_banner',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default'    => 'Active',
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('advertisements');
    }

    public function down()
    {
        $this->forge->dropTable('advertisements');
    }
}