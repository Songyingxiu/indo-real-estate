<?php namespace App\Models;

use CodeIgniter\Model;

class CmsModel extends Model
{
    protected $table = 'cms_posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'category', 'content_body', 'author_id', 'status', 'published_at'];
    
    protected $useTimestamps = true; 
}