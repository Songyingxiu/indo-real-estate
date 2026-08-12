<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocalizationColumns extends Migration
{
    public function up()
    {
        // 1. Update Properties Table
        $this->forge->addColumn('properties', [
            'title_en'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title'],
            'title_id'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title_en'],
            'description_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'description'],
            'description_id' => ['type' => 'TEXT', 'null' => true, 'after' => 'description_en'],
        ]);

        // 2. Update CMS Posts Table (Covers News, Pages, and FAQs)
        $this->forge->addColumn('cms_posts', [
            'title_en'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title'],
            'title_id'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title_en'],
            'content_body_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'content_body'],
            'content_body_id' => ['type' => 'TEXT', 'null' => true, 'after' => 'content_body_en'],
        ]);

        // 3. Update Features Table
        $this->forge->addColumn('features', [
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name'],
            'name_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name_en'],
        ]);

        // 4. Update Feature Categories Table
        $this->forge->addColumn('feature_categories', [
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name'],
            'name_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name_en'],
        ]);

        // 5. Update Property Types Table
        $this->forge->addColumn('property_types', [
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name'],
            'name_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name_en'],
        ]);

        // 6. Update Subscription Plans Table
        $this->forge->addColumn('subscription_plans', [
            'name_en'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name'],
            'name_id'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'name_en'],
            'features_en' => ['type' => 'TEXT', 'null' => true],
            'features_id' => ['type' => 'TEXT', 'null' => true],
        ]);

        // 7. Update Advertisements (Promos) Table
        $this->forge->addColumn('advertisements', [
            'title_en'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title'],
            'title_id'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'title_en'],
            'description_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'description'],
            'description_id' => ['type' => 'TEXT', 'null' => true, 'after' => 'description_en'],
        ]);

        // 8. Update SEO Settings Table
        $this->forge->addColumn('seo_settings', [
            'meta_title_en'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'meta_title'],
            'meta_title_id'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'meta_title_en'],
            'meta_description_en' => ['type' => 'TEXT', 'null' => true, 'after' => 'meta_description'],
            'meta_description_id' => ['type' => 'TEXT', 'null' => true, 'after' => 'meta_description_en'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('properties', ['title_en', 'title_id', 'description_en', 'description_id']);
        $this->forge->dropColumn('cms_posts', ['title_en', 'title_id', 'content_body_en', 'content_body_id']);
        $this->forge->dropColumn('features', ['name_en', 'name_id']);
        $this->forge->dropColumn('feature_categories', ['name_en', 'name_id']);
        $this->forge->dropColumn('property_types', ['name_en', 'name_id']);
        $this->forge->dropColumn('subscription_plans', ['name_en', 'name_id', 'features_en', 'features_id']);
        $this->forge->dropColumn('advertisements', ['title_en', 'title_id', 'description_en', 'description_id']);
        $this->forge->dropColumn('seo_settings', ['meta_title_en', 'meta_title_id', 'meta_description_en', 'meta_description_id']);
    }
}