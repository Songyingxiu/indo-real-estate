<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Facing Routes
$routes->get('/', 'Home::index');            
$routes->get('search', 'Home::search');
$routes->get('api/suggest', 'Home::suggest');
$routes->post('search/save', 'Home::saveSearch');
$routes->get('property/(:num)', 'Home::detail/$1');
$routes->post('property/toggle-save', 'Home::toggleSaveProperty');

$routes->post('property/submit-inquiry', 'Property::submitInquiry');

$routes->get('page/(:segment)', 'Cms::page/$1');
$routes->get('faq', 'Cms::faq');
$routes->get('news', 'Cms::blog');
$routes->get('promo/(:num)', 'Home::promo/$1');      

// SEO-Friendly Location Routes
$routes->get('properties/province/(:segment)', 'Property::province/$1');
$routes->get('properties/city/(:segment)/(:segment)', 'Property::city/$1/$2');
$routes->get('properties/zipcode/(:num)', 'Property::zipcode/$1');

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::attemptForgotPassword');
$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password', 'Auth::attemptResetPassword');
$routes->get('logout', 'Auth::logout');
$routes->post('auth/google-login', 'Auth::googleLogin'); // Added for Firebase

// User Routes
$routes->group('user', ['filter' => 'userAuth'], static function ($routes) {
    $routes->get('inbox', 'Inquiry::index');
    $routes->get('inbox/thread/(:num)', 'Inquiry::getThread/$1');
    $routes->post('inbox/reply', 'Inquiry::reply');
    
    $routes->get('profile', 'User::profile');
    $routes->post('update-profile', 'User::updateProfile');
    $routes->post('update-password', 'User::updatePassword');
    $routes->get('saved-properties', 'User::savedProperties');
});

// Agent AJAX Routes
$routes->group('agent', ['namespace' => 'App\Controllers\Agent', 'filter' => 'adminAuth'], static function ($routes) {
    $routes->post('poi/store-ajax', 'PoiAjax::store');
});

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
    $routes->get('properties/edit/(:num)', 'Properties::edit/$1');
    $routes->post('properties/update/(:num)', 'Properties::update/$1');
    $routes->get('properties/get-cities/(:any)', 'Properties::getCities/$1');
    $routes->get('properties/get-zipcodes/(:any)', 'Properties::getZipcodes/$1');
    
    // Property Status AJAX Update
    $routes->post('properties/update-status/(:num)', 'Properties::updateStatus/$1');
    
    // Property Moderation & State Machine
    $routes->get('moderation', 'Moderation::index');
    $routes->post('moderation/update-status/(:num)', 'Moderation::updateStatus/$1');

    // POI
    $routes->group('poi', ['namespace' => 'App\Controllers\Admin'], function($routes) {
        $routes->get('/', 'Poi::index');
        $routes->get('create', 'Poi::create');
        $routes->post('store', 'Poi::store');
        $routes->get('edit/(:num)', 'Poi::edit/$1');
        $routes->post('update/(:num)', 'Poi::update/$1');
        $routes->get('delete/(:num)', 'Poi::delete/$1'); 
    });

    // Subscriptions Management (User Approvals)
    $routes->get('subscriptions', 'Subscriptions::index');
    $routes->post('subscriptions/activate/(:num)', 'Subscriptions::activate/$1');
    $routes->post('subscriptions/revoke/(:num)', 'Subscriptions::revoke/$1');
    
    // Agent/Owner Subscription Upgrade Routes
    $routes->get('pricing', 'Subscription::pricing');
    $routes->match(['get', 'post'], 'subscription/checkout', 'Subscription::checkout');
    $routes->post('subscription/upload-proof', 'Subscription::uploadProof');
    $routes->get('subscription/invoice/(:num)', 'Subscription::invoice/$1');

    // Master Data & Configuration
    $routes->get('master-data', 'MasterData::index');
    
    // Store
    $routes->post('master-data/store-type', 'MasterData::storeType');
    $routes->post('master-data/store-city', 'MasterData::storeCity');
    $routes->post('master-data/store-state', 'MasterData::storeState');
    $routes->post('master-data/store-feature', 'MasterData::storeFeature'); 
    $routes->post('master-data/store-plan', 'MasterData::storePlan');
    $routes->post('master-data/store-zipcode', 'MasterData::storeZipcode'); 
    $routes->post('master-data/store-poi', 'MasterData::storePoi'); 
    
    // Delete
    $routes->post('master-data/delete-type/(:num)', 'MasterData::deleteType/$1'); 
    $routes->post('master-data/delete-city/(:num)', 'MasterData::deleteCity/$1'); 
    $routes->post('master-data/delete-state/(:num)', 'MasterData::deleteState/$1');
    $routes->post('master-data/delete-feature/(:num)', 'MasterData::deleteFeature/$1');
    $routes->post('master-data/delete-plan/(:num)', 'MasterData::deletePlan/$1');
    $routes->post('master-data/delete-zipcode/(:num)', 'MasterData::deleteZipcode/$1');
    $routes->post('master-data/delete-poi/(:num)', 'MasterData::deletePoi/$1');
    
    // Update
    $routes->post('master-data/update-type/(:num)', 'MasterData::updateType/$1');
    $routes->post('master-data/update-state/(:num)', 'MasterData::updateState/$1');
    $routes->post('master-data/update-city/(:num)', 'MasterData::updateCity/$1');
    $routes->post('master-data/update-plan/(:num)', 'MasterData::updatePlan/$1');
    $routes->post('master-data/update-zipcode/(:num)', 'MasterData::updateZipcode/$1');
    
    // Inquiries (Chat System)
    $routes->get('inquiries', 'Inquiries::index');
    $routes->get('inquiries/thread/(:num)', 'Inquiries::getThread/$1');
    $routes->post('inquiries/update-status/(:num)', 'Inquiries::updateStatus/$1');
    $routes->post('inquiries/reply', 'Inquiries::reply');
    
    // Verifications
    $routes->get('verifications', 'Verifications::index'); 
    $routes->post('verifications/process-agent/(:num)', 'Verifications::processAgent/$1');
    $routes->post('verifications/process-property/(:num)', 'Verifications::processProperty/$1');

    // System Settings & Content
    $routes->get('cms', 'Cms::index');
    $routes->post('cms/save', 'Cms::savePost');
    $routes->post('cms/delete/(:num)', 'Cms::delete/$1');
    $routes->get('seo', 'Seo::index');
    $routes->post('seo/save', 'Seo::saveSettings');

    // Advertisements
    $routes->get('advertisements', 'Advertisements::index');
    $routes->get('advertisements/create', 'Advertisements::create');
    $routes->post('advertisements/save', 'Advertisements::save');
    $routes->get('advertisements/edit/(:num)', 'Advertisements::edit/$1');
    $routes->get('advertisements/delete/(:num)', 'Advertisements::delete/$1');

    // Email Templates
    $routes->get('email-templates', 'EmailTemplates::index');
    $routes->get('email-templates/create', 'EmailTemplates::create');
    $routes->post('email-templates/save', 'EmailTemplates::save');
    $routes->get('email-templates/edit/(:num)', 'EmailTemplates::edit/$1');
    $routes->get('email-templates/delete/(:num)', 'EmailTemplates::delete/$1');
    $routes->post('email-templates/test/(:num)', 'EmailTemplates::sendTest/$1');

    // Reports and Support
    $routes->get('reports/export', 'Reports::export');
    $routes->get('support', 'Support::index');

    // Profile & Settings
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/update-password', 'Profile::updatePassword');
    $routes->post('profile/upload-docs', 'Profile::uploadDocs');
});