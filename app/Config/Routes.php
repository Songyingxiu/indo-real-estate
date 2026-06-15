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

// Admin Panel Routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');                 
    $routes->get('moderation', 'Moderation::index');       
    $routes->get('verifications', 'Verifications::index'); 
    $routes->get('leads', 'Leads::index');                 
    $routes->get('subscriptions', 'Subscriptions::index'); 
    $routes->get('master-data', 'MasterData::index');
    $routes->get('cms', 'Cms::index');
    $routes->get('seo', 'Seo::index');
    $routes->get('email-templates', 'EmailTemplates::index');
    
});