<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Fetch Notifications Globally
        $db = \Config\Database::connect();
        
        // Example: Let's fetch the 5 newest leads to use as "Notifications"
        $notifications = $db->table('leads')
            ->select('leads.*, properties.title as property_title, users.first_name, users.last_name')
            ->join('properties', 'properties.id = leads.property_id', 'left')
            ->join('users', 'users.id = leads.buyer_id', 'left')
            ->orderBy('leads.created_date', 'DESC')
            ->limit(5)
            ->get()
            ->getResultObject();

        // Share the variable with every view
        $GLOBALS['global_notifications'] = $notifications;
    }
}
