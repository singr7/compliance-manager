<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
        $this->load->model('Auth_mod');
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
            
        $newcertificate = '';
        if (get_cookie('certificate_key', false) != null) {
            $newcertificate = get_cookie('certificate_key', false);
        }
       // pr($newcertificate);
        $certificate = substr($newcertificate, 0, 450);
      // pr($certificate);die;
        $id = $this->session->userdata('userinfo')->id;
        $userDetails = $this->Auth_mod->get_User($id);
        if (isset($userDetails->certificate_key) && !empty($userDetails->certificate_key)) {
            if ($userDetails->is_certificate_verified == 0) {
                if ($certificate == $userDetails->certificate_key) {
                    $data['is_certificate_verified'] = 1;
                    $data['new_certificate_key'] = $newcertificate;
                    $this->db->where('id', $id);
                    $this->db->update('users', $data);

                    $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
                    $data['page'] = 'dashboard/site_dashboard';
                    $data['title'] = 'Panacea || Dashboard';
                    $this->load->view('layout', $data);
                } else {
                     
                    $this->session->sess_destroy();
                    set_flashdata('error', 'Invalid certificate');
                    redirect(base_url('auth/auth/login?token=nocertificate'));
                }
            } else {
                if ($newcertificate == $userDetails->new_certificate_key) {
                    $data['process_name'] = $this->Auth_mod->get_count_process($id);
                    $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
                    $data['page'] = 'dashboard/site_dashboard';
                    $data['title'] = 'Panacea || Dashboard';
                    $this->load->view('layout', $data);
                } else {
                    $this->session->sess_destroy();
                     set_flashdata('error', 'Invalid certificate');
                    redirect(base_url('auth/auth/login?token=invalid'));

                     
                }
            }
        } else {
            $this->session->sess_destroy();
            set_flashdata('error', 'Invalid certificate');
            redirect(base_url('auth/auth/login?token=invalid'));
            
        }
    }

//     public function index() {
//         $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
//                $data['page'] = 'dashboard/site_dashboard';
//                $data['title'] = 'Panacea || Dashboard';
//                $this->load->view('layout', $data);
//     }
    
    

    /*End of function*/
}
/*End of class*/