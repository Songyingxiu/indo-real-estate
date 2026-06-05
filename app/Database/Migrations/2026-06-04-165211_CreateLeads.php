<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateLeads extends Migration {
    public function up() {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'property_id' => ['type' => 'INT', 'constraint' => 11],
            'buyer_id'    => ['type' => 'INT', 'constraint' => 11],
            'agent_id'    => ['type' => 'INT', 'constraint' => 11],
            'source'      => ['type' => 'ENUM', 'constraint' => ['Contact Form', 'Phone Inquiry', 'Schedule Visit']],
            'status'      => ['type' => 'ENUM', 'constraint' => ['New', 'Contacted', 'Follow Up', 'Qualified', 'Negotiation', 'Won', 'Lost'], 'default' => 'New'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('agent_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('leads');
    }
    public function down() { $this->forge->dropTable('leads', true); }
}