<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('MasterSeeder');
        $this->call('UserSeeder');
        $this->call('PropertySeeder');
    }
}