<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFeaturesToSubscriptionPlans extends Migration
{
    public function up()
    {
        $fields = [
            'package_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_properties' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'max_agents' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'allow_messages' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'allow_direct_email' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
        ];

        $this->forge->addColumn('subscription_plans', $fields);

        $this->db->query("ALTER TABLE subscription_plans ADD UNIQUE (package_code)");
    }

    public function down()
    {
        $this->forge->dropColumn('subscription_plans', [
            'package_code', 
            'description', 
            'max_properties', 
            'max_agents', 
            'allow_messages', 
            'allow_direct_email'
        ]);
    }
}