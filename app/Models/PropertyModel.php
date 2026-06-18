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
        'owner_id', 
        'property_type_id', 
        'city_id', 
        'zipcode_id', 
        'title', 
        'description', 
        'listing_type', 
        'address_line_1', 
        'address_line_2', 
        'area_name', 
        'unit_number', 
        'building_society_name', 
        'latitude', 
        'longitude', 
        'year_built', 
        'total_floors', 
        'bed',              
        'bath',             
        'total_area', 
        'total_land_area',  
        'usable_area', 
        'parking', 
        'total_parking',    
        'basement', 
        'water_facility', 
        'video_url', 
        'tax_price', 
        'property_tax_number', 
        'approval_status', 
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_date';
    protected $updatedField  = 'modified_date';
}