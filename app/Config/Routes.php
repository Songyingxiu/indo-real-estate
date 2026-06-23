<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Facing Routes
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Admin Dashboard Routes (Protected by AdminFilter)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], static function ($routes) {

    // Dashboard Core
    $routes->get('dashboard', 'Dashboard::index');
    
    // User Management
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users/store', 'Users::store');                          
    $routes->post('users/updateRole/(:num)', 'Users::updateRole/$1');
    $routes->post('users/delete/(:num)', 'Users::delete/$1');                
    
    // Property Management & Listings
    $routes->get('properties', 'Properties::index');
    $routes->get('properties/create', 'Properties::create');
    $routes->post('properties/store', 'Properties::store');
    
    // Property Moderation Queue
    $routes->get('moderation', 'Moderation::index');
    $routes->post('moderation/approve/(:num)', 'Moderation::approve/$1');
    $routes->post('moderation/reject/(:num)', 'Moderation::reject/$1');
    
    // Master Data & Configuration
    $routes->get('master-data', 'MasterData::index');
    $routes->post('master-data/store-type', 'MasterData::storeType');
    $routes->post('master-data/store-city', 'MasterData::storeCity');
    
    // Lead & Verification Management
    $routes->get('leads', 'Leads::index');                
    $routes->get('verifications', 'Verifications::index'); 
    $routes->post('verifications/process/(:num)', 'Verifications::process/$1'); // ADDED VERIFICATION ROUTE
    
    // System Settings & Content
    $routes->get('subscriptions', 'Subscriptions::index'); 
    $routes->get('cms', 'Cms::index');
    $routes->post('cms/save', 'Cms::savePost');
    $routes->get('seo', 'Seo::index');
    $routes->post('seo/save', 'Seo::saveSettings');
    
});