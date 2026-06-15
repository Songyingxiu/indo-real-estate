<?php namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model {
    protected $table            = 'locations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // Returns data as objects instead of arrays
    
    // Core Configuration
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['region_name', 'latitude', 'longitude', 'status'];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}