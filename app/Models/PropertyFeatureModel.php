<?php namespace App\Models;

use CodeIgniter\Model;

class PropertyFeatureModel extends Model {
    protected $table            = 'property_features';
    
    protected $useAutoIncrement = false; 
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['property_id', 'feature_id', 'status'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}