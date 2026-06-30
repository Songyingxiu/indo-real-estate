<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Facing Routes
$routes->get('/', 'Home::index');            
$routes->get('search', 'Home::search');      

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');

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
    $routes->get('properties/get-cities/(:any)', 'Properties::getCities/$1');
    
    // Property Moderation Queue
    $routes->get('moderation', 'Moderation::index');
    $routes->post('moderation/approve/(:num)', 'Moderation::approve/$1');
    $routes->post('moderation/reject/(:num)', 'Moderation::reject/$1');

    // Subscriptions Management
    $routes->get('subscriptions', 'Subscriptions::index');
    $routes->post('subscriptions/store', 'Subscriptions::store');
    $routes->post('subscriptions/update/(:num)', 'Subscriptions::update/$1');
    $routes->post('subscriptions/delete/(:num)', 'Subscriptions::delete/$1');
    
    // Master Data & Configuration
    $routes->get('master-data', 'MasterData::index');
    $routes->post('master-data/store-type', 'MasterData::storeType');
    $routes->post('master-data/store-city', 'MasterData::storeCity');
    $routes->post('master-data/store-state', 'MasterData::storeState');
    $routes->post('master-data/store-feature', 'MasterData::storeFeature'); 
    
    $routes->post('master-data/delete-type/(:num)', 'MasterData::deleteType/$1'); 
    $routes->post('master-data/delete-city/(:num)', 'MasterData::deleteCity/$1'); 
    $routes->post('master-data/delete-state/(:num)', 'MasterData::deleteState/$1');
    $routes->post('master-data/delete-feature/(:num)', 'MasterData::deleteFeature/$1');
    
    $routes->post('master-data/update-type/(:num)', 'MasterData::updateType/$1');
    $routes->post('master-data/update-state/(:num)', 'MasterData::updateState/$1');
    $routes->post('master-data/update-city/(:num)', 'MasterData::updateCity/$1');

    // Lead & Verification Management
    $routes->get('leads', 'Leads::index');       
    $routes->post('leads/update-status/(:num)', 'Leads::updateStatus/$1');
    $routes->post('leads/delete/(:num)', 'Leads::delete/$1');        
    $routes->get('verifications', 'Verifications::index'); 
    $routes->post('verifications/process/(:num)', 'Verifications::process/$1');
    
    // System Settings & Content
    $routes->get('cms', 'Cms::index');
    $routes->post('cms/save', 'Cms::savePost');
    $routes->get('seo', 'Seo::index');
    $routes->post('seo/save', 'Seo::saveSettings');

    // Reports and Support
    $routes->get('reports/export', 'Reports::export');
    $routes->get('support', 'Support::index');

    // Profile & Settings
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/update-password', 'Profile::updatePassword');
    
});