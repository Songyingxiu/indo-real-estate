<?php namespace App\Models;

use CodeIgniter\Model;

class CmsModel extends Model
{
    protected $table = 'cms_posts';
    protected $primaryKey = 'id';
    
    protected $returnType = 'object'; 
    
    protected $allowedFields = [
        'title', 'title_en', 'title_id', 
        'slug', 'category', 
        'content_body', 'content_body_en', 'content_body_id', 
        'author_id', 'status', 'published_at'
    ];
    
    protected $useTimestamps = true; 
}