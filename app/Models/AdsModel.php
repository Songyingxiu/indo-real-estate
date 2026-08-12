<?php namespace App\Models;

use CodeIgniter\Model;

class AdsModel extends Model 
{
    protected $table            = 'advertisements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    protected $allowedFields    = [
        'title', 'title_en', 'title_id', 
        'image_path', 
        'description', 'description_en', 'description_id', 
        'placement', 'status', 'start_date', 'end_date'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}