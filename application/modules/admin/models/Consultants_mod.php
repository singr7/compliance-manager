<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Department Model
 *
 * @package		Qsa's
 * @category            Qsa's
 * @author		Mukul Kumar
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc
 * @since		Version 1.0
 */
class Consultants_mod extends CI_Model {

    var $tbl_users = "users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    
    
    //=======code for create new Consultants ===========//
    
    public function add_consultants(){
        
            $data['empty'] = true;
            $postdata['All'] = $this->input->post(NULL, TRUE);
            $qsaData = array('password'=>md5(time()),'pwdstring'=>time(),'user_type'=>4,'created_date' => date('Y-m-d H:i:s'));
            foreach ($postdata['All'] AS $field1 => $value1) {
                if ($field1 != 'id') {
                    $qsaData[$field1] = $value1;
                }
            }
           // pr($qsaData);die;
           if($this->db->insert('users',$qsaData)){
               return TRUE;
           }else{
               return FALSE;
           }
    }


    //=======end code for create new Consultants =========//
    
    
  //=======code for create new Consultants ===========//
    
    public function edit_consultants($id){
        
            $data['empty'] = true;
            $postdata['All'] = $this->input->post(NULL, TRUE);
            foreach ($postdata['All'] AS $field1 => $value1) {
                if ($field1 != 'id') {
                    $qsaData[$field1] = $value1;
                }
            }
            $this->db->where('id', $id);
           if($this->db->update('users',$qsaData)){
               return TRUE;
           }else{
               return FALSE;
           }
    }


    //=======end code for create new Consultants =========//  
    
    
  //============code for Consultants listing ==============//
  public function consultants_listing(){
       $this->db->select('*');
        $this->db->from('users');
        $this->db->where("user_type",4);
        $this->db->order_by('id', 'desc');
        $this->db->where("status !=",'delete');
        $query = $this->db->get()->result();
        return $query;
      
  }
  //============end code for Consultants listing ============//  
  
  //============code for get user details ===========//
  
  public function get_User($id){
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where("id",$id);
      $query = $this->db->get()->row();
       return $query;
  }


  //===========end code for get user details ===========//
  
 //===========code for delete consultant ===========//
  public function deleteConsultants($id){
        $data['status'] = 'delete';
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $update = $this->db->affected_rows();
        if ($update) {
            return true;
        } else {
            return false;
        }
  }
  //===========end code for delete consultant===========// 
  
}
