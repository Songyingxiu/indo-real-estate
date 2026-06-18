<?php namespace App\Models;
use CodeIgniter\Model;

class PropertyVerificationModel extends Model {
    protected $table            = 'property_verifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'property_id', 'ownership_certificate', 'land_certificate', 
        'supporting_documents', 'approval_status', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}