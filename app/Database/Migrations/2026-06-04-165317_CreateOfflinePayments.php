<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateOfflinePayments extends Migration {
    public function up() {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'subscription_id' => ['type' => 'INT', 'constraint' => 11],
            'invoice_number'  => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'payment_proof'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'          => ['type' => 'ENUM', 'constraint' => ['Pending', 'Under Review', 'Verified', 'Rejected'], 'default' => 'Pending'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('subscription_id', 'subscriptions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('offline_payments');
    }
    public function down() { $this->forge->dropTable('offline_payments', true); }
}