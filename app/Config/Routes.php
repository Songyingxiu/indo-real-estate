<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Public Facing Routes
// --------------------------------------------------------------------
$routes->get('/', 'Home::index');

// --------------------------------------------------------------------
// Admin Panel Routes
// --------------------------------------------------------------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('users', 'Users::index');                 
    $routes->get('moderation', 'Moderation::index');       
    $routes->get('verifications', 'Verifications::index'); 
    $routes->get('leads', 'Leads::index');                 
    $routes->get('subscriptions', 'Subscriptions::index'); 
    
});