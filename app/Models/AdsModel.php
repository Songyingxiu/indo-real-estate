<?php namespace App\Models;

use CodeIgniter\Model;

class AdsModel extends Model 
{
    protected $table            = 'advertisements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    protected $allowedFields    = ['title', 'image_path', 'description', 'placement', 'status', 'start_date', 'end_date'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}