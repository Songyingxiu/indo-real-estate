<?php namespace App\Models;
use CodeIgniter\Model;

class SubscriptionPlanModel extends Model {
    protected $table            = 'subscription_plans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'code', 'name', 'description', 'price', 
        'max_properties', 'max_agents', 
        'allow_messages', 'direct_email_inquiry', 
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}