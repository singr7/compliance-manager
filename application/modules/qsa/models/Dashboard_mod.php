<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Profile Model
 *
 * @package		Dashboard
 * @category            Dashboard
 * @author		Mukul
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc
 * @since		Version 1.0
 */
class Dashboard_mod extends CI_Model {

    var $tbl_users = "users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * customer_info
     *
     * this function give user profile info by user id
     * @access	public
     * @return array
     */
    
    public function customer_info($id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
         
    }
    
    public function customer_process($id,$qsa){
        $this->db->select('*');
        $this->db->from('process');
        $this->db->where("customer_id", $id);
        $query = $this->db->get()->result();
        
        $processId = array();
        foreach($query as $process){
        $processId[] = $process->id;
        }
        $this->db->select('*');
        $this->db->where_in('process_id', $processId);
        $this->db->where('customer_id', $id);
        $this->db->where('qsa_id', $qsa);
        $customerProcess = $this->db->get("compliance_project")->result();
        
        $compliance = array();
        foreach($customerProcess as $project){
        $compliance[$project->customer_id][$project->process_id][$project->service_id]  = $project;
        }
		
		 $this->db->select('*');
        $this->db->where_in('process_id', $processId);
        $this->db->where('customer_id', $id);
        $this->db->where('qsa_id', $qsa);
        $customertestingProcess = $this->db->get("testing_project")->result();
        
		 $testing = array();
        foreach($customertestingProcess as $testings){
			$testing[$testings->customer_id][$testings->process_id][$testings->testing_id]  = $testings;
        }
        $customerview = array();
        $i =0;
        foreach($query as $customerprocess){
            $customerview[$i] = $customerprocess;
            $customerview[$i]->customer_process = @$compliance[$customerprocess->customer_id][$customerprocess->id];
            $customerview[$i]->customer_testing_process = @$testing[$customerprocess->customer_id][$customerprocess->id];
            $i++;
        }
        //pr($customerview);die;
        return $customerview;
    }
	
	 
}
