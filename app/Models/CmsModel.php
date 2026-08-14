<?php namespace App\Models;

use CodeIgniter\Model;

class CmsModel extends Model
{
    protected $table = 'cms_posts';
    protected $primaryKey = 'id';
    
    protected $returnType = 'object'; 
    
    protected $allowedFields = [
        'title', 'title_en', 'title_id', 
        'slug', 'category', 'faq_category',
        'content_body', 'content_body_en', 'content_body_id', 
        'author_id', 'status', 'published_at'
    ];
    
    protected $useTimestamps = true; 

    /**
     * @param string
     * @param int|null.
     */
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        helper('url');

        $baseSlug = url_title(strtolower($title), '-', true);
        $slug     = $baseSlug;
        $suffix   = 2;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->where('slug', $slug);

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}