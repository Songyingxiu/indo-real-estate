<?php namespace App\Models;
use CodeIgniter\Model;

class AgentVerificationModel extends Model {
    protected $table            = 'agent_verifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; 
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'user_id', 'ktp_document', 'business_license', 'npwp', 
        'approval_status', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}