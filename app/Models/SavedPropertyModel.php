<?php namespace App\Models;

use CodeIgniter\Model;

class SavedPropertyModel extends Model 
{
    protected $table            = 'saved_properties';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['user_id', 'property_id', 'created_at'];

    protected $useTimestamps = false; 
}