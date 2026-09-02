<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  

    /**
     * Constructor
     */ 
    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_mod');
	is_adminprotected();
    }
	
	/**
     * End of function
     */
	 
	 /**
     * index
     *
     * This function to render dashboard page initially
     * 
     * @access	public
     * @return  html
     */


     public function index() {
         //echo base_url();die;
         $data['total_qsa'] = $this->Dashboard_mod->total_qsa();
         $data['total_qa'] = $this->Dashboard_mod->total_qa();
         $data['total_customer'] = $this->Dashboard_mod->total_customer();
         $data['total_consultants'] = $this->Dashboard_mod->total_consultants();
         $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Dashboard");
                $data['page'] = 'dashboard/site_dashboard';
                $data['title'] = 'Panacea || Dashboard';
                $this->load->view('layout', $data);
     }
    
    

    /*End of function*/
}
/*End of class*/