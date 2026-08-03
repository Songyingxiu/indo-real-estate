<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration {
    public function up() {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'role_id'          => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'first_name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone_number'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'password'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'           => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive'], 'default' => 'Active'],
            'remember_token'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'reset_token'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'reset_expires_at' => ['type' => 'DATETIME', 'null' => true],
            'created_date'     => ['type' => 'DATETIME', 'null' => true],
            'modified_date'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('users');
    }
    
    public function down() { 
        $this->forge->dropTable('users', true); 
    }
}