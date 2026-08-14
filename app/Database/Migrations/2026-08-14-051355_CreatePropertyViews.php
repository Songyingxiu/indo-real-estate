<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertyViews extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'property_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'ip_address'  => ['type' => 'VARCHAR', 'constraint' => 45],
            'viewed_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['property_id', 'ip_address', 'user_id']);
        $this->forge->createTable('property_views');
    }

    public function down()
    {
        $this->forge->dropTable('property_views');
    }
}