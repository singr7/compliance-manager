<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Department Model
 *
 * @package		Questionnaire's
 * @category            Questionnaire's
 * @author		Mukul Kumar
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc
 * @since		Version 1.0
 */
class Questionnaire_mod extends CI_Model {

    var $tbl_users = "questionnaire";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    
    //============code for Questionnaire listing ==============//
  public function questionnaire_list($id){
       $this->db->select('*');
        $this->db->from('questionnaire');
        $this->db->where("service_id",$id);
        $query = $this->db->get()->result();
        return $query;
  }
  //============end code for Questionnaire listing ============//  
    
  
   //============code for active questionnaire ==============//
  public function active_questionnaire($id){
       
        $this->db->where_in("id",$id);
        $data['status'] = 1;
        $this->db->update('questionnaire', $data);
        return true;
  }
  //============end code for active questionnaire ============//  
    
   //============code for inactive questionnaire ==============//
  public function inactive_questionnaire($id){
     // pr($ids);die;
       $this->db->where_in("id",$id);
        $data['status'] = 2;
        $this->db->update('questionnaire', $data);
        return true;
  }
  //============end code for inactive questionnaire ============// 
  
  //============code for inactive questionnaire ==============//
  public function get_Question($id){
        $this->db->select('*,(select id from services where id=questionnaire.service_id) AS Service');
        $this->db->from('questionnaire');
        $this->db->where("id",$id);
        $query = $this->db->get()->row();
        //pr($query);die;
        return $query;
  }
  //============end code for inactive questionnaire ============// 
  
  //============code for edit questionnaire ==============//
  public function edit_question($id){
      
        $data['question'] = $_POST['question'];
         $this->db->where_in("id",$id);
        $this->db->update('questionnaire', $data);
        return true;
  }
  //============end code for edit questionnaire ============// 
  
   //============code for get service name ==============//
  public function service_name($id){
       $this->db->select('*');
        $this->db->from('services');
        $this->db->where("id",$id);
        $query = $this->db->get()->row();
        return $query;
  }
  //============end code for get service name ============// 
  //============code for get testing name ==============//
  public function testing_name($id){
	    $this->db->select('*');
        $this->db->from('services');
        $this->db->where("id",$id);
        $query = $this->db->get()->row();
		
        return $query;
  }
  //============end code for get testing name ============//
  
   //============code for get questionair ==============//
  public function questionair($id){
        $this->db->select('*');
        $this->db->from('questionnaire');
        $this->db->where("service_id",$id);
        $this->db->where("status",1);
        $query = $this->db->get()->result();
        return $query;
  }
  //============end code for get questionair ============// 
  
}