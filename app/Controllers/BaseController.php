<?php namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\InquiryModel;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        
        // --- MULTI-LANGUAGE LOCALE INJECTION ---
        $locale = $this->session->get('locale') ?? $request->getLocale();
        $request->setLocale($locale);
        \Config\Services::language()->setLocale($locale);
        // ---------------------------------------

        // GLOBAL NOTIFICATIONS (For both Users and Admins)
        if ($this->session->get('id')) {
            $inquiryModel = new InquiryModel();
            $roleId = $this->session->get('role_id');

            if ($roleId == 1) {
                // Buyer: Fetch threads they started where the agent has 'Replied'
                $notifs = $inquiryModel
                    ->select('inquiries.*, properties.title as property_title')
                    ->join('properties', 'properties.id = inquiries.property_id', 'left')
                    ->where('inquiries.sender_id', $this->session->get('id'))
                    ->where('inquiries.status', 'Replied')
                    ->orderBy('inquiries.updated_at', 'DESC')
                    ->findAll(5); 
                
                $GLOBALS['global_notifications'] = $notifs;
                $GLOBALS['unread_count'] = count($notifs); 
            } else {
                // Agent/Owner/Admin: Fetch unread inbox messages ('Pending' status)
                $notifs = $inquiryModel
                    ->select('inquiries.*, users.first_name, users.last_name, properties.title as property_title')
                    ->join('users', 'users.id = inquiries.sender_id', 'left')
                    ->join('properties', 'properties.id = inquiries.property_id', 'left')
                    ->where('inquiries.receiver_id', $this->session->get('id'))
                    ->where('inquiries.status', 'Pending')
                    ->orderBy('inquiries.created_at', 'DESC')
                    ->findAll(5);
                
                $GLOBALS['global_notifications'] = $notifs;
                $GLOBALS['unread_count'] = count($notifs);
            }
        } else {
            $GLOBALS['global_notifications'] = [];
            $GLOBALS['unread_count'] = 0;
        }
    }
}