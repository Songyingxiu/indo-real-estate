<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreatePropertyVerifications extends Migration {
    public function up() {
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'property_id'           => ['type' => 'INT', 'constraint' => 11],
            'ownership_certificate' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'land_certificate'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'supporting_documents'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'                => ['type' => 'ENUM', 'constraint' => ['Not Verified', 'Pending Verification', 'Verified', 'Rejected'], 'default' => 'Not Verified'],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('property_verifications');
    }
    public function down() { $this->forge->dropTable('property_verifications', true); }
}