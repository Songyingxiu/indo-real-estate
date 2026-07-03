<?php namespace App\Controllers;

use App\Models\LeadModel;

class User extends BaseController
{
    public function inbox()
    {
        // Enforce role security: Only Buyers (Role 1) should access this specific frontend inbox
        if (session()->get('role_id') != 1) {
            return redirect()->to(base_url('login'))->with('error', 'Please log in as a user to view your inbox.');
        }

        $leadModel = new LeadModel();
        
        $data['title'] = 'My Inbox - HuniKita';
        
        // Fetch leads submitted by this specific buyer
        $data['leads'] = $leadModel
            ->select('leads.*, properties.title as property_title, properties.address_line_1')
            ->join('properties', 'properties.id = leads.property_id', 'left')
            ->where('leads.buyer_id', session()->get('id'))
            ->orderBy('leads.created_date', 'DESC')
            ->paginate(10);
            
        $data['pager'] = $leadModel->pager;

        return view('front/user/inbox', $data);
    }
}