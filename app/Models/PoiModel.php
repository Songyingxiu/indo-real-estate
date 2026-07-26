<?php

namespace App\Models;

use CodeIgniter\Model;

class PoiModel extends Model
{
    protected $table            = 'points_of_interest';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['name', 'category', 'latitude', 'longitude', 'status'];
    protected $useTimestamps    = true;

    // Get POIs within a certain radius (in km)
    public function getNearbyPOIs($lat, $lng, $radius = 5)
    {
        if (!$lat || !$lng) return [];

        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude))))";
        
        return $this->select("*, {$haversine} AS distance")
                    ->where('status', 'Active')
                    ->having('distance <=', $radius)
                    ->orderBy('distance', 'ASC')
                    ->limit(15)
                    ->find();
    }
}