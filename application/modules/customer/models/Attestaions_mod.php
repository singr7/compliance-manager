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
class Attestaions_mod extends CI_Model {

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
    //==========start code for get AOC && ROC =================//

    public function attestaions_list($year) {
        $this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("service_id", $year);
        //echo $this->db->last_query();
        return $query = $this->db->get()->result();
    }

    //==========start code for get AOC && ROC==================//


    public function process_name($process_id) {
        $this->db->select('*');
        $this->db->from('process');
        $this->db->where("customer_id", $process_id);
        $query = $this->db->get()->result();
        $processId = array();
        foreach ($query as $process) {
            $processId[] = $process->id;
        }
      // pr($query);
        $this->db->select('*,(select service_name from services where id=report_aoc_roc.service_id) AS ServiceName');
        $this->db->from('report_aoc_roc');
        $this->db->where_in("process_id", $processId);
        $aoc1 = $this->db->get()->result();
		 // echo $this->db->last_query();die;
		 
         $compliance = array();
         $j = 0;
         foreach($aoc1 as $project){
         $compliance[$project->customer_id][$project->process_id][$j]  = $project;
         $j++;
        }
       
        $serviceview = array();
        $i = 0;
        foreach ($query as $customerprocess) {
            $serviceview[$i] = $customerprocess;
            $serviceview[$i]->service = @$compliance[$customerprocess->customer_id][$customerprocess->id];
             $i++;
        }
        return $serviceview;
    }

    //==========start code for get AOC && ROC==================//
	
    public function get_process_data($service_id) {
        $this->db->select('*');
        $this->db->from('services');
        $this->db->where("id", $service_id);
     //   $this->db->where("report_of", $service_id);
        //echo $this->db->last_query();
        return $query = $this->db->get()->row();
    }

    //==========start code for get AOC && ROC==================//
	
	 //==========start code for get AOC && ROC =================//

    public function year($customer_id) {
		$this->db->distinct();
        $this->db->select('year');
        $this->db->from('report_aoc_roc');
        $this->db->where("customer_id", $customer_id);
        return $query = $this->db->get()->result();
		
    }

    //==========start code for get AOC && ROC==================//
}
