<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterInquiriesTable extends Migration
{
    public function up()
    {
        // 1. Drop the legacy leads table
        $this->forge->dropTable('leads', true); // The 'true' acts as IF EXISTS

        // 2. Add the parent_id column for threading
        $fields = [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'inquiry_id'
            ],
        ];
        
        $this->forge->addColumn('inquiries', $fields);

        // 3. Add the foreign key constraint
        // (Using raw SQL here is the most reliable way to add a constraint to an existing table in CI4)
        $this->db->query('ALTER TABLE `inquiries` ADD CONSTRAINT `fk_inquiries_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `inquiries`(`inquiry_id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // To rollback, we drop the foreign key first
        $this->db->query('ALTER TABLE `inquiries` DROP FOREIGN KEY `fk_inquiries_parent_id`');
        
        // Then drop the column
        $this->forge->dropColumn('inquiries', 'parent_id');
        
        // Note: We don't automatically rebuild the old `leads` table on rollback 
        // since it's being permanently deprecated change from leads to inquiries.
    }
}