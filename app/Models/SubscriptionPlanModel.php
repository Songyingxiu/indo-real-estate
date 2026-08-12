<?php namespace App\Models;
use CodeIgniter\Model;

class SubscriptionPlanModel extends Model {
    protected $table            = 'subscription_plans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'package_code', 'name', 'name_en', 'name_id', 'description', 
        'features_en', 'features_id', 'price', 
        'max_properties', 'max_agents', 'max_pois', 
        'allow_messages', 'allow_direct_email', 
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}