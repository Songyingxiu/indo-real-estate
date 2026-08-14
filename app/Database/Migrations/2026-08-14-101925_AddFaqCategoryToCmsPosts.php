<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFaqCategoryToCmsPosts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('cms_posts', [
            'faq_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'category'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('cms_posts', 'faq_category');
    }
}