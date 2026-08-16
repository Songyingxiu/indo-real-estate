<?php namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table              = 'properties';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement = true;
    protected $returnType         = 'array';
    
    protected $allowedFields    = [
        'owner_id', 'property_type_id', 'city_id', 'zipcode_id', 'title', 'title_en', 'title_id', 'slug', 
        'description', 'description_en', 'description_id', 'listing_type', 'address_line_1', 'address_line_2', 
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

    // Search Engine Logic with Sorting
    public function searchProperties($keyword = null, $listingType = null, $types = [], $lat = null, $lng = null, $radius = null, $sort = 'new')
    {
        $builder = $this->builder();
        $request = \Config\Services::request();
        
        // Subquery to calculate unique views per property based on IP / User ID
        $viewSubquery = "(SELECT COUNT(DISTINCT IP_ADDRESS) FROM PROPERTY_VIEWS WHERE PROPERTY_VIEWS.PROPERTY_ID = PROPERTIES.ID)";
        
        $selects = 'properties.*, property_types.name as type_name, users.first_name, users.last_name, property_images.image_path, zipcodes.zipcode, cities.name as city_name, ' . $viewSubquery . ' as unique_views';

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
        
        $builder->where('properties.approval_status !=', 'Draft');

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('properties.title_en', $keyword)
                    ->orLike('properties.title_id', $keyword)
                    ->orLike('properties.title', $keyword)
                    ->orLike('properties.area_name', $keyword)
                    ->orLike('cities.name', $keyword) 
                    ->groupEnd();
        }

        if (!empty($listingType)) {
            $builder->where('properties.listing_type', ucfirst(strtolower($listingType)));
        }

        if (!empty($types) && is_array($types)) {
            $builder->whereIn('properties.property_type_id', $types);
        }

        // filters Price, Bed, Bath
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

        // Apply radius filter or sorting rules
        if ($lat && $lng && $radius) {
            $builder->where("{$haversine} <=", (float)$radius);
            $builder->orderBy('distance', 'ASC'); 
        } else {
            switch ($sort) {
                case 'popular':
                    $builder->orderBy('unique_views', 'DESC');
                    break;
                case 'price_low':
                    $builder->orderBy('properties.tax_price', 'ASC');
                    break;
                case 'price_high':
                    $builder->orderBy('properties.tax_price', 'DESC');
                    break;
                case 'sold':
                    // Puts 'Sold' properties at the very top, then sorts by newest
                    $builder->orderBy("CASE WHEN properties.status = 'Sold' THEN 1 ELSE 2 END", 'ASC');
                    $builder->orderBy('properties.created_date', 'DESC');
                    break;
                case 'new':
                default:
                    $builder->orderBy('properties.created_date', 'DESC');
                    break;
            }
        }

        return $this;
    }

    // Get Popular Listings for Homepage based on Unique Views
    public function getPopularProperties($limit = 6)
    {
        $viewSubquery = "(SELECT COUNT(DISTINCT ip_address) FROM property_views WHERE property_views.property_id = properties.id)";
        
        return $this->asObject()->select("properties.*, property_types.name as type_name, property_images.image_path, cities.name as city_name, {$viewSubquery} as unique_views")
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('cities', 'cities.id = properties.city_id', 'left')
            ->where('properties.approval_status !=', 'Draft')
            ->orderBy('unique_views', 'DESC')
            ->orderBy('properties.created_date', 'DESC')
            ->limit($limit)
            ->find();
    }

    // State Page Stats
    public function getCityStatsByState($stateId)
    {
        return $this->db->table('properties')
            ->select('cities.name as city_name, COUNT(properties.id) as property_count, AVG(properties.tax_price) as avg_price')
            ->join('cities', 'cities.id = properties.city_id')
            ->where('cities.state_id', $stateId)
            ->where('properties.approval_status !=', 'Draft')
            ->groupBy('cities.id')
            ->get()->getResult();
    }

    // Map Markers
    public function getMapMarkers($conditions = [], $limit = 150)
    {
        $builder = $this->select('properties.id, properties.title, properties.title_en, properties.title_id, properties.tax_price, properties.latitude, properties.longitude, properties.listing_type, properties.status, properties.approval_status');

        foreach ($conditions as $key => $val) {
            $builder->where('properties.' . $key, $val);
        }

        return $builder->where('properties.approval_status !=', 'Draft')
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
            ->where('properties.approval_status !=', 'Draft')
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
            ->where('properties.approval_status !=', 'Draft')
            ->orderBy('properties.created_date', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function updatePropertyStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}