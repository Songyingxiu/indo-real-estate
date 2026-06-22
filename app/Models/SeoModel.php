<?php namespace App\Models;

use CodeIgniter\Model;

class SeoModel extends Model
{
    protected $table = 'seo_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['target_page', 'meta_title', 'meta_description', 'focus_keywords'];
    
    protected $useTimestamps = true;
    protected $createdField  = ''; 
    protected $updatedField  = 'updated_at';
}