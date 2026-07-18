<?php namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateSubscriptions extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11],
            'plan_id'       => ['type' => 'INT', 'constraint' => 11],
            'sub_status'    => ['type' => 'ENUM', 'constraint' => ['Active', 'Pending', 'Expired'], 'default' => 'Pending'],
            'start_date'    => ['type' => 'DATE', 'null' => true],
            'end_date'      => ['type' => 'DATE', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'created_date'  => ['type' => 'DATETIME', 'null' => true],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('plan_id', 'subscription_plans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subscriptions');
    }
    public function down() { $this->forge->dropTable('subscriptions', true); }
}