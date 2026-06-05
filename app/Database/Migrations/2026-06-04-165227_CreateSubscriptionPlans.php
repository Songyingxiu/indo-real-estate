<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateSubscriptionPlans extends Migration {
    public function up() {
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'name'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subscription_plans');
    }
    public function down() { $this->forge->dropTable('subscription_plans', true); }
}