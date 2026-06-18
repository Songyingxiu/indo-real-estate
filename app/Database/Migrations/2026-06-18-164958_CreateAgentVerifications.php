<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAgentVerifications extends Migration {
    public function up() {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'user_id'          => ['type' => 'INT', 'constraint' => 11],
            'ktp_document'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'business_license' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'npwp'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'approval_status'  => ['type' => 'ENUM', 'constraint' => ['Pending', 'Under Review', 'Verified', 'Rejected', 'Suspended'], 'default' => 'Pending'],
            'status'           => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'     => ['type' => 'DATETIME', 'null' => true],
            'modified_date'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('agent_verifications');
    }
    public function down() { $this->forge->dropTable('agent_verifications', true); }
}