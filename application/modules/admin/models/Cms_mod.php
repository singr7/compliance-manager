<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Country Model 
 *
 * @package		Masters
 * @subpackage	Models
 * @category	Masters
 * @author      Dharmendra Pal
 * @website		http://www.tekshapers.com
 * @company     Tekshapers Inc
 * @since		Version 1.0
 */
class Cms_mod extends CI_Model {

    var $user_table = "kc_users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
	
	public function listing() {
       $this->db->select('*');
	   $this->db->from('kc_cms');
	   
	    if (!empty($_GET['status'])) {
            $this->db->where("status =",$_GET["status"]);
        }else {
            $this->db->where("status",'active');
        }
	   $this->db->order_by("id","desc");
	   $query = $this->db->get();
	   if($query->num_rows()>0){
		   return $query->result();
	   } return false;
    }
	
	public function add() {
        if($_POST){            
            $data['name']           = $_POST['name'];
			$data['status']         = $_POST['status'];            
			$data['created_date']	= date('Y-m-d H:i:s');			
            $this->db->insert("fs_unit", $data);
            return true;        
        } else{
            return false;      
        }
    }
	
	   
//  THIS FUNCTION EDIT service DATA
    function edit($id) {
        $data['title']               = $this->input->post('title');
		$data['content']             = $this->input->post('content'); 
       // $data['status']             = $this->input->post('status'); 
if(get_selected_language()->code == 'en'){
			$this->db->where('id', $id);
        $update = $this->db->update('kc_cms', $data);
		}else{
			/*Putting this data into lang column*/
			$lang_data[get_selected_language()->code]	=	$data;
			$lang_data = json_encode($lang_data);
			$dat['lang']	=	$lang_data;       
        $this->db->where('id', $id);
        $update = $this->db->update('kc_cms', $data);
		}
        if($update){
            return true;          
        } else{
            return false; 
        }        
    }
	
    /**
     * check_preexistance
     *
     * function for check either color name pre exist
     * 
     * @access	public
     * @return	html data
     */
    function check_preexistance($id, $name) {
        $this->db->select('*');
        $this->db->where('id !=', $id);
        $this->db->where('name ', $name);
        $query = $this->db->get('fs_unit');        
        return $query->num_rows();        
    }
	
	//  THIS FUNCTION VIEW service DATA
    function view($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get("kc_cms")->row();
		return $query;
    }

	    
//  THIS FUNCTION DELETE service_category DATA
    function deleteUnit($unit_ids) {	
        $data['status'] = 'delete';
        $this->db->where_in('id', $unit_ids);
        $this->db->update('kc_cms', $data);		
		$update = $this->db->affected_rows();
		if($update){
			return $update;
		} else {
			return false;
		}
    }
 
	
}

// end class
?>