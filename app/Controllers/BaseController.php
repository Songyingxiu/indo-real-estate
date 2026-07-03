<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\LeadModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();

        // --------------------------------------------------------------------
        // GLOBAL NOTIFICATIONS (For both Users and Admins)
        // --------------------------------------------------------------------
        if ($this->session->get('id')) {
            $leadModel = new LeadModel();
            $roleId = $this->session->get('role_id');

            if ($roleId == 1) {
                // Buyer: Fetch their sent inquiries and status updates
                $notifs = $leadModel
                    ->select('leads.*, properties.title as property_title')
                    ->join('properties', 'properties.id = leads.property_id', 'left')
                    ->where('leads.buyer_id', $this->session->get('id'))
                    ->orderBy('leads.modified_date', 'DESC')
                    ->findAll(5); 
                
                $GLOBALS['global_notifications'] = $notifs;
                $GLOBALS['unread_count'] = 0; 
            } else {
                // Agent/Owner/Admin: Fetch unread inbox messages
                $notifs = $leadModel
                    ->select('leads.*, users.first_name, users.last_name, properties.title as property_title')
                    ->join('users', 'users.id = leads.buyer_id', 'left')
                    ->join('properties', 'properties.id = leads.property_id', 'left')
                    ->where('leads.agent_id', $this->session->get('id'))
                    ->where('leads.is_read', 0)
                    ->orderBy('leads.created_date', 'DESC')
                    ->findAll();
                
                $GLOBALS['global_notifications'] = $notifs;
                $GLOBALS['unread_count'] = count($notifs);
            }
        } else {
            $GLOBALS['global_notifications'] = [];
            $GLOBALS['unread_count'] = 0;
        }
    }
}