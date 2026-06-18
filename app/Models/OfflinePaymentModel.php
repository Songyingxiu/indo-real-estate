<?php namespace App\Models;
use CodeIgniter\Model;

class OfflinePaymentModel extends Model {
    protected $table            = 'offline_payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'subscription_id', 'phone_number', 'invoice_number', 
        'payment_proof', 'approval_status', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}