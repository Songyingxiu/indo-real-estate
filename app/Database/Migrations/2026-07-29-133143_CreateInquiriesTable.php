<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInquiriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'inquiry_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'property_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'receiver_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Pending', 
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('inquiry_id', true);
        
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('inquiries');
    }

    public function down()
    {
        $this->forge->dropTable('inquiries');
    }
}