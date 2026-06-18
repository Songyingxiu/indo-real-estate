<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateOfflinePayments extends Migration {
    public function up() {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'subscription_id' => ['type' => 'INT', 'constraint' => 11],
            'phone_number'    => ['type' => 'VARCHAR', 'constraint' => 20],
            'invoice_number'  => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'payment_proof'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'approval_status' => ['type' => 'ENUM', 'constraint' => ['Pending', 'Under Review', 'Verified', 'Rejected'], 'default' => 'Pending'],
            'status'          => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'    => ['type' => 'DATETIME', 'null' => true],
            'modified_date'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('subscription_id', 'subscriptions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('offline_payments');
    }
    public function down() { $this->forge->dropTable('offline_payments', true); }
}