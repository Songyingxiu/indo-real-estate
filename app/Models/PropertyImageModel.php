<?php namespace App\Models;
use CodeIgniter\Model;

class PropertyImageModel extends Model {
    protected $table            = 'property_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'property_id', 'title', 'image_path', 'seq_no', 
        'is_primary', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}