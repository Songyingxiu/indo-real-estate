<?php namespace App\Models;
use CodeIgniter\Model;

class SubscriptionModel extends Model {
    protected $table            = 'subscriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'user_id', 'plan_id', 'sub_status', 'start_date', 
        'end_date', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}