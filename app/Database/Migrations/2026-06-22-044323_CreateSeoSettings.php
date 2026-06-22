<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeoSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'target_page'      => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'meta_title'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'meta_description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'focus_keywords'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('seo_settings');
    }

    public function down()
    {
        $this->forge->dropTable('seo_settings');
    }
}