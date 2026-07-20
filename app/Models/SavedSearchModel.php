<?php namespace App\Models;

use CodeIgniter\Model;

class SavedSearchModel extends Model 
{
    protected $table            = 'saved_searches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    protected $allowedFields    = ['user_id', 'name', 'filters', 'created_at'];
    protected $useTimestamps    = false; 
}