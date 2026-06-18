<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);
        $date = date('Y-m-d H:i:s');

        $users = [
            ['id' => 1, 'role_id' => 4, 'first_name' => 'Reza', 'last_name' => 'Avanluna', 'phone_number' => '081234567890', 'email' => 'reza@estate.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Admin
            ['id' => 2, 'role_id' => 3, 'first_name' => 'Taka', 'last_name' => 'Radjiman', 'phone_number' => '081234567891', 'email' => 'taka@agent.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Agent
            ['id' => 3, 'role_id' => 2, 'first_name' => 'Amacia', 'last_name' => 'Michella', 'phone_number' => '081234567892', 'email' => 'amacia@owner.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Owner
            ['id' => 4, 'role_id' => 2, 'first_name' => 'Miyu', 'last_name' => 'Ottavia', 'phone_number' => '081234567893', 'email' => 'miyu@owner.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Owner
            ['id' => 5, 'role_id' => 3, 'first_name' => 'Bonnivier', 'last_name' => 'Pranaja', 'phone_number' => '081234567894', 'email' => 'bonni@agent.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Agent
            ['id' => 6, 'role_id' => 1, 'first_name' => 'Riksa', 'last_name' => 'Dhirendra', 'phone_number' => '081234567895', 'email' => 'riksa@buyer.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Buyer
            ['id' => 7, 'role_id' => 1, 'first_name' => 'Etna', 'last_name' => 'Crimson', 'phone_number' => '081234567896', 'email' => 'etna@buyer.com', 'password' => $defaultPassword, 'status' => 'Active', 'created_date' => $date], // Buyer
        ];

        $this->db->table('users')->insertBatch($users);
    }
}