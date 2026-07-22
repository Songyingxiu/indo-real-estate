<?php namespace App\Models;

use CodeIgniter\Model;

class EmailTemplateModel extends Model 
{
    protected $table            = 'email_templates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    
    protected $allowedFields    = ['name', 'subject', 'body', 'variables', 'status'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}