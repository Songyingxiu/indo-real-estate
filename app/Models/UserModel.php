<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // soft deletes so user records are never permanently lost
    protected $useSoftDeletes   = true;

    // Registration form & Password reset
    protected $allowedFields    = [
        'role_id', 
        'first_name', 
        'last_name', 
        'email', 
        'phone_number', 
        'password', 
        'status',
        'remember_token',
        'reset_token',
        'reset_expires_at',
        'auth_provider', 
        'google_id'      
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
    protected $deletedField  = 'deleted_at';
}