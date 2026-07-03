<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToLeads extends Migration
{
    public function up()
    {
        $fields = [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'message' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ];
        
        $this->forge->addColumn('leads', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('leads', ['name', 'phone', 'email', 'message', 'is_read']);
    }
}