<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Attestaions extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
        is_adminprotected();
         $this->load->model('Attestaions_mod');
		  $this->load->model('admin/Compliances_mod');
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
		$userdata=$this->session->userdata();
		$data['year'] = $this->Attestaions_mod->year($userdata['userinfo']->id); 
		$data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
        $data['page'] = 'Attestaions/attestaions_dashboard';
        $data['title'] = 'Panacea || Attestaions';
        $this->load->view('layout', $data);
    }
    
	
	// start report of ROC and AOC
	
     public function attestaions_report() {
		 $year =  ID_decode(@$_GET['year']);
		 $userdata=$this->session->userdata();
		// pr($userdata['userinfo']->id);die;
		$data['process'] = $this->Attestaions_mod->process_name($userdata['userinfo']->id);
		$data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Attestaions listing");
        $data['page'] = 'Attestaions/attestaions_report';
        $data['title'] = 'Panacea || Attestaions';
        $this->load->view('layout', $data);
    }
	// end report of ROC and AOC
	
    // start report of ROC and AOC
	
     public function attestaions_details() {
		 $userdata=$this->session->userdata();
		$service_id = ID_decode(@$_GET['service_id']);
		$year = ID_decode(@$_GET['year']);
		$process_id = ID_decode(@$_GET['process_id']);
		$data['service_name'] = $this->Attestaions_mod->get_process_data($service_id);
		//$data['aoc_roc'] = $this->Attestaions_mod->attestaions_list($service_id);
		$data['roc'] = $this->Compliances_mod->get_data_roc($service_id,$process_id,$userdata['userinfo']->id,$year);
		$data['aoc'] = $this->Compliances_mod->get_data_aoc($service_id,$process_id,$userdata['userinfo']->id,$year);
		$data['rot'] = $this->Compliances_mod->get_data_rot($service_id,$process_id,$userdata['userinfo']->id,$year);
		$data['aot'] = $this->Compliances_mod->get_data_aot($service_id,$process_id,$userdata['userinfo']->id,$year);
		$data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Attestaions listing");
        $data['page'] = 'Attestaions/attestaions_details';
        $data['title'] = 'Panacea || Attestaions';
        $this->load->view('layout', $data);
    }
	// end report of ROC and AOC
	
	//==========start code for delete upload dcoument==================//
	
	 public function Delete_aoc_roc(){
		
		 if(isPostBack()){
			$customer_id=ID_decode(@$this->input->post('customer_id'));
			$docs_id =ID_decode(@$this->input->post('docs_id'));
			$service_id =ID_decode(@$this->input->post('service_id'));
			$process_id =ID_decode(@$this->input->post('process_id'));
			$year =ID_decode(@$this->input->post('year'));
			$uploadeddata=$this->Compliances_mod->get_data_roc_aoc($docs_id,$service_id,$process_id,$year,$customer_id);
			if($uploadeddata){
					$path = FCPATH."uploads/report/$uploadeddata->report_docs";
					if(file_exists($path)){
						unlink($path);
						$returndata=$this->Compliances_mod->delete_roc_aoc_docs($docs_id);
						if($returndata){
						set_flashdata('success','Uploaded document has been deleted successfully.');
						}
					}else{
						set_flashdata('error','something error please try again.');
					}
					
				}
		
			
		}
	}
	  //==========end code for delete upload dcoument==================//
	

    /*End of function*/
}
/*End of class*/