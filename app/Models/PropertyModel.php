<?php namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table            = 'properties';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'owner_id', 'property_type_id', 'city_id', 'zipcode_id', 'title', 'slug', 
        'description', 'listing_type', 'address_line_1', 'address_line_2', 
        'area_name', 'unit_number', 'building_society_name', 'latitude', 
        'longitude', 'year_built', 'total_floors', 'bed', 'bath', 
        'total_area', 'total_land_area', 'usable_area', 'parking', 
        'total_parking', 'basement', 'water_facility', 
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
        $request = \Config\Services::request();
        
        $selects = 'properties.*, property_types.name as type_name, users.first_name, users.last_name, property_images.image_path, zipcodes.zipcode, cities.name as city_name';

        // Apply Haversine formula if coordinates and radius are provided
        if ($lat && $lng && $radius) {
            $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(properties.latitude)) * cos(radians(properties.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(properties.latitude))))";
            $selects .= ", {$haversine} AS distance";
        }

        $builder->select($selects);
        $builder->join('property_types', 'property_types.id = properties.property_type_id', 'left');
        $builder->join('users', 'users.id = properties.owner_id', 'left');
        $builder->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left');
        $builder->join('zipcodes', 'zipcodes.id = properties.zipcode_id', 'left');
        $builder->join('cities', 'cities.id = properties.city_id', 'left'); 
        
        $builder->where('properties.status', 'Active');
        $builder->where('properties.approval_status', 'Published');

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('properties.title', $keyword)
                    ->orLike('properties.area_name', $keyword)
                    ->orLike('cities.name', $keyword) 
                    ->groupEnd();
        }

        if (!empty($listingType)) {
            $builder->where('properties.listing_type', $listingType);
        }

        if (!empty($types) && is_array($types)) {
            $builder->whereIn('properties.property_type_id', $types);
        }

        // --- NEW FILTERS: Price, Bed, Bath ---
        $minPrice = $request->getGet('min_price');
        $maxPrice = $request->getGet('max_price');
        $bed = $request->getGet('bed');
        $bath = $request->getGet('bath');

        if (is_numeric($minPrice) && $minPrice > 0) {
            $builder->where('properties.tax_price >=', (float)$minPrice);
        }
        if (is_numeric($maxPrice) && $maxPrice > 0) {
            $builder->where('properties.tax_price <=', (float)$maxPrice);
        }
        if (!empty($bed)) {
            $builder->where('properties.bed >=', (int)$bed);
        }
        if (!empty($bath)) {
            $builder->where('properties.bath >=', (int)$bath);
        }

        // Apply radius filter (Using WHERE instead of HAVING to fix Pagination Bug)
        if ($lat && $lng && $radius) {
            $builder->where("{$haversine} <=", (float)$radius);
            $builder->orderBy('distance', 'ASC'); 
        } else {
            $builder->orderBy('properties.created_date', 'DESC'); 
        }

        return $this;
    }

    // State Page Stats
    public function getCityStatsByState($stateId)
    {
        return $this->db->table('properties')
            ->select('cities.name as city_name, COUNT(properties.id) as property_count, AVG(properties.tax_price) as avg_price')
            ->join('cities', 'cities.id = properties.city_id')
            ->where('cities.state_id', $stateId) // FIXED: Uses cities.state_id instead of properties.state_id
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->groupBy('cities.id')
            ->get()->getResult();
    }

    // Map Markers
    public function getMapMarkers($conditions = [], $limit = 150)
    {
        $builder = $this->select('properties.id, properties.title, properties.tax_price, properties.latitude, properties.longitude, properties.listing_type');

        foreach ($conditions as $key => $val) {
            // Explicitly bind to properties table to prevent ambiguous column errors
            $builder->where('properties.' . $key, $val);
        }

        return $builder->where('properties.status', 'Active')
                       ->where('properties.approval_status', 'Published')
                       ->where('properties.latitude IS NOT NULL')
                       ->where('properties.longitude IS NOT NULL')
                       ->limit($limit)
                       ->find();
    }

    // Detail Page Algorithms
    public function getNearbyProperties($lat, $lng, $excludeId, $limit = 5)
    {
        if (!$lat || !$lng) return [];
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(properties.latitude)) * cos(radians(properties.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(properties.latitude))))";
        
        return $this->select("properties.*, property_images.image_path, cities.name as city_name, {$haversine} AS distance")
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('properties.id !=', $excludeId)
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->having('distance <=', 10) 
            ->orderBy('distance', 'ASC')
            ->limit($limit)
            ->find();
    }

    public function getSimilarProperties($field, $value, $excludeId, $limit = 5)
    {
        return $this->select('properties.*, property_images.image_path, cities.name as city_name')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where("properties.{$field}", $value)
            ->where('properties.id !=', $excludeId)
            ->where('properties.status', 'Active')
            ->where('properties.approval_status', 'Published')
            ->orderBy('properties.created_date', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function updatePropertyStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}