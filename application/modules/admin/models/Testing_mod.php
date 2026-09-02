<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Country Model 
 *
 * @package		Testing
 * @subpackage	Models
 * @category	Masters
 * @author      Mukul
 * @website		http://www.tekshapers.com
 * @company     Tekshapers Inc
 * @since		Version 1.0
 */
class Testing_mod extends CI_Model {

    var $user_table = "compliances";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }

    //====code for get testing name=====//
    public function testing_name($id) {
        $this->db->select('id,service_name');
        $this->db->from('services');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
    }

    //====end code for get testing name=====//
    
    //====code for add testing project======//
    public function add_testing_project() {
        $data['testing_id'] = ID_decode($_POST['testing_id']);
        $data['customer_id'] = $_POST['customer_id'];
        $data['process_id'] = $_POST['process_id'];
        $data['qsa_id'] = $_POST['qsa_id'];
        $data['consultant_id'] = $_POST['consultant_id'];
        $data['qa_id'] = $_POST['qa_id'];
        $data['start_date'] = $_POST['start_date'];
        $data['created_date'] = date('Y-m-d H:i:s');
        if($this->db->insert('testing_project',$data)){
               return TRUE;
           }else{
               return FALSE;
           }
    }
    //====end code for testing project=======//
    
     //===== code for check preexistance=====//
    function check_preexistance_add($testingId,$process,$customerId) {
        $this->db->select('*');
        $this->db->where('testing_id', $testingId);
        $this->db->where('process_id', $process);
        $this->db->where('end_date', '');
        $this->db->where('customer_id', $customerId);
        $query = $this->db->get('testing_project');
        //echo $this->db->last_query();die;
        //pr($query->result());
        return $query->num_rows();
    }
  //===== end code for check preexistance=====//
    
    
     //==========code for get project list==========//
    
    public function project_list($id){
        $this->db->select('*,(select full_name from users where id=testing_project.customer_id) AS CustomerName,(select full_name from users where id=testing_project.qsa_id) AS QSAName,(select full_name from users where id=testing_project.consultant_id) AS ConsultantName,(select full_name from users where id=testing_project.qa_id) AS QaName,(select process_name from process where id = testing_project.process_id) AS ProcessName');
        $this->db->from('testing_project');
        $this->db->where("testing_id",$id);
        $this->db->where("status",'0');
		$this->db->order_by("id", "desc");
        $query = $this->db->get()->result();
        //pr($query);die;
        return $query;
    }
//==========end code for get project list=========//
    
    //===========code for get project details==========//
    
    public function project_details($id){
       $this->db->select('*,(select process_name from process where id = testing_project.process_id) AS ProcessName');
        $this->db->from('testing_project');
        $this->db->where("id",$id);
        $this->db->where("status",'0');
        $query = $this->db->get()->row();
        //pr($query);die;
        return $query;
    }


    //==========end code for get project details==========//
	
	 //===== code for check preexistance=====//
    function check_preexistance($id, $serviceId,$process,$date,$customerId) {
        
        $this->db->select('*');
        $this->db->where('id !=', $id);
        $this->db->where('testing_id', $serviceId);
        $this->db->where('process_id', $process);
        $this->db->where('start_date', $date);
        $this->db->where('customer_id', $customerId);
        $query = $this->db->get('testing_project');
       // echo $this->db->last_query();die;
        //pr($query->result());
        return $query->num_rows();
    }
  //===== end code for check preexistance=====//
  
 //======code for edit testing project=========//
    
    public function edit_project($id){
        $data['customer_id'] = $_POST['customer_id'];
        $data['process_id'] = $_POST['process_id'];
        $data['qsa_id'] = $_POST['qsa_id'];
        $data['consultant_id'] = $_POST['consultant_id'];
        $data['qa_id'] = $_POST['qa_id'];
        $data['start_date'] = $_POST['start_date'];
        $this->db->where('id', $id);
        if($this->db->update('testing_project', $data)){
		       return TRUE;
           }else{
               return FALSE;
           }
        
        
    }
 //=====end code for edit testing project====// 
 
  public function start_date($serviceid,$customer_id,$process_id){
		$userdata=$this->session->userdata;
        $this->db->select('start_date,end_date');
        $this->db->from('testing_project');
        $this->db->where("testing_id",$serviceid);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
	
	
	
	// start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
	public function Common_Update_End_date($tableName,$data,$service_id,$process_id,$customer_id,$project_id){
		$this->db->where('id', $project_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('testing_id', $service_id);
		$this->db->where('customer_id', $customer_id);
		if($this->db->update($tableName, $data)){
			   return TRUE;
           }else{
               return FALSE;
           }
		
		
	}
	
   // start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
   
    //==========code for get compliance_project =================//
    
    public function get_data_testing_project($service_id,$process_id,$userdata_id){
		$this->db->select('*');
        $this->db->from('testing_project');
        $this->db->where("testing_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$userdata_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for compliance_project==================//
}
