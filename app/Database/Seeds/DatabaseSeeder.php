<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('MasterSeeder');       // Locations, Roles, Subscriptions
        $this->call('UserSeeder');         // Users
        $this->call('PropertySeeder');     // Properties
        $this->call('OperationalSeeder');  // Leads, Verifications, SEO, CMS
    }
}