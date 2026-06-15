<?php namespace App\Models;

use CodeIgniter\Model;

class PropertyTypeModel extends Model {
    protected $table            = 'property_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    // Core Configuration
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['name', 'status'];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}