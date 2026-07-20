<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table            = 'properties';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'owner_id', 'property_type_id', 'city_id', 'zipcode_id', 'title', 
        'description', 'listing_type', 'address_line_1', 'address_line_2', 
        'area_name', 'unit_number', 'building_society_name', 'latitude', 
        'longitude', 'year_built', 'total_floors', 'bed', 'bath', 
        'total_area', 'total_land_area', 'usable_area', 'parking', 
        'total_parking', 'basement', 'water_facility', 'video_url', 
        'tax_price', 'property_tax_number', 'approval_status', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';

    // --- Custom Search Engine Logic ---
    public function searchProperties($keyword = null, $listingType = null, $types = [], $lat = null, $lng = null, $radius = null)
    {
        $builder = $this->builder();
        
        // Base selections
        $selects = 'properties.*, property_types.name as type_name, users.first_name, users.last_name, property_images.image_path';

        // Apply Haversine formula if coordinates and radius are provided
        if ($lat && $lng && $radius) {
            $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(properties.latitude)) * cos(radians(properties.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(properties.latitude))))";
            $selects .= ", {$haversine} AS distance";
        }

        $builder->select($selects);
        $builder->join('property_types', 'property_types.id = properties.property_type_id', 'left');
        $builder->join('users', 'users.id = properties.owner_id', 'left');
        $builder->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left');
        
        $builder->where('properties.status', 'Active');
        $builder->where('properties.approval_status', 'Published');

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('properties.title', $keyword)
                    ->orLike('properties.area_name', $keyword)
                    ->groupEnd();
        }

        if (!empty($listingType)) {
            $builder->where('properties.listing_type', $listingType);
        }

        if (!empty($types) && is_array($types)) {
            $builder->whereIn('properties.property_type_id', $types);
        }

        // Apply radius filter and ordering
        if ($lat && $lng && $radius) {
            $builder->having('distance <=', (float)$radius);
            $builder->orderBy('distance', 'ASC'); // Closest first
        } else {
            $builder->orderBy('properties.created_date', 'DESC'); // Newest first
        }

        return $this;
    }
}