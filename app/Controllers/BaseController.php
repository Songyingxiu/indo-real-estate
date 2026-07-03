<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\LeadModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();

        // --------------------------------------------------------------------
        // GLOBAL ADMIN NOTIFICATIONS
        // --------------------------------------------------------------------
        // If an Agent/Owner/Admin is logged in, fetch their unread messages
        if ($this->session->get('id')) {
            $leadModel = new LeadModel();
            
            // Join users (to get buyer name) and properties (to get property title)
            $GLOBALS['global_notifications'] = $leadModel
                ->select('leads.*, users.first_name, users.last_name, properties.title as property_title')
                ->join('users', 'users.id = leads.buyer_id', 'left')
                ->join('properties', 'properties.id = leads.property_id', 'left')
                ->where('leads.agent_id', $this->session->get('id'))
                ->where('leads.is_read', 0)
                ->orderBy('leads.created_date', 'DESC')
                ->findAll();
        } else {
            $GLOBALS['global_notifications'] = [];
        }
    }
}