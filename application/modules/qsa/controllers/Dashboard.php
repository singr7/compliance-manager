<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
        is_adminprotected();
         $this->load->model('Dashboard_mod');
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
		 
		if (get_cookie('qsa_certificate_key', false) != null) {
            $newcertificate = get_cookie('qsa_certificate_key', false);
        } 
        if(isset($newcertificate) && !empty($newcertificate)){
          $newcertificate = explode('-',$newcertificate);
           $customerId = ID_decode($newcertificate[0]);
           $userId = $this->session->userdata('userinfo')->id;
           $data['customer_info'] = $this->Dashboard_mod->customer_info($customerId);
           $data['customer_process'] = $this->Dashboard_mod->customer_process($customerId,$userId);
           //pr($data['customer_process']);die;
           $data['breadcum'] = array("qsa/dashboard/" => "Home", '' => "Dashboard");
                $data['page'] = 'dashboard/site_dashboard';
                $data['title'] = 'Panacea || Dashboard';
                $this->load->view('front_layout', $data); 
        }else{
           $this->session->sess_destroy();
             redirect(base_url('auth/auth/login?token=nocertificate'));
        }
       
     }
	 
	

    /*End of function*/
}
/*End of class*/