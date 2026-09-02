<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Country Model 
 *
 * @package	Masters
 * @subpackage	Models
 * @category	Masters
 * @author      Mukul
 * @website	http://www.tekshapers.com
 * @company     Tekshapers Inc
 * @since	Version 1.0
 */
class Dashboard_mod extends CI_Model {

    var $user_table = "users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    
    
    public function total_qsa(){
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('user_type',2);
       $query = $this->db->get();
       return $query->num_rows();
    }
    
    public function total_qa(){
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('user_type',3);
       $query = $this->db->get();
       return $query->num_rows();
    }
    
    public function total_customer(){
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('user_type',5);
       $query = $this->db->get();
       return $query->num_rows();
    }
    
    public function total_consultants(){
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('user_type',4);
       $query = $this->db->get();
       return $query->num_rows();
    }
    
    
}


