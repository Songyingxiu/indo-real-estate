<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCmsPosts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'         => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'category'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'content_body' => ['type' => 'TEXT'],
            'author_id'    => ['type' => 'INT', 'constraint' => 11], 
            'status'       => ['type' => 'ENUM', 'constraint' => ['Draft', 'Published'], 'default' => 'Draft'],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        // Link the author to your existing users table
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('cms_posts');
    }

    public function down()
    {
        $this->forge->dropTable('cms_posts');
    }
}