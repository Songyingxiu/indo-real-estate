<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // We use soft deletes so user records are never permanently lost
    protected $useSoftDeletes   = true;

    // These are the exact columns we set up in your Registration form
    protected $allowedFields    = [
        'role', 
        'fullname', 
        'email', 
        'phone', 
        'password', 
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}