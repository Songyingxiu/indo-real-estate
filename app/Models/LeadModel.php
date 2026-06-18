<?php namespace App\Models;
use CodeIgniter\Model;

class LeadModel extends Model {
    protected $table            = 'leads';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'property_id', 'buyer_id', 'agent_id', 'source', 
        'lead_status', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}