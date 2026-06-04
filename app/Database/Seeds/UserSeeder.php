<?php 

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder 
{
    public function run() 
    {
        $password = password_hash('password123', PASSWORD_BCRYPT);
        
        $users = [
            [
                'role_id'    => 3, // Property Owner
                'name'       => 'Taka Radjiman', 
                'email'      => 'taka.owner@indo-realestate.id', 
                'password'   => $password, 
                'created_at' => Time::now(), 
                'updated_at' => Time::now(),
                'deleted_at' => null
            ],
            [
                'role_id'    => 4, // Agent
                'name'       => 'Mika Melatika', 
                'email'      => 'mika.agent@indo-realestate.id', 
                'password'   => $password, 
                'created_at' => Time::now(), 
                'updated_at' => Time::now(),
                'deleted_at' => null
            ],
            [
                'role_id'    => 2, // Buyer
                'name'       => 'Riksa Dhirendra', 
                'email'      => 'riksa.buyer@gmail.com', 
                'password'   => $password, 
                'created_at' => Time::now(), 
                'updated_at' => Time::now(),
                'deleted_at' => null
            ]
        ];

        $this->db->table('users')->insertBatch($users);
    }
}