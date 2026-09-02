<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Country Model 
 *
 * @package		Masters
 * @subpackage	Models
 * @category	Masters
 * @author      Mukul
 * @website		http://www.tekshapers.com
 * @company     Tekshapers Inc
 * @since		Version 1.0
 */
class Compliances_mod extends CI_Model {

    var $user_table = "compliances";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
	
    //====code for get customer list=====//
    public function customer_list(){
        $this->db->select('id,full_name');
        $this->db->from('users');
        $this->db->where("user_type",5);
        $this->db->where("parent_id",0);
        $this->db->where("status",'active');
        $query = $this->db->get()->result();
        return $query;
    }
     //====end code for get customer list=====//
    
     //====code for get qsa list=====//
    public function qsa_list(){
        $this->db->select('id,full_name');
        $this->db->from('users');
        $this->db->where("user_type",2);
        $this->db->where("status",'active');
        $query = $this->db->get()->result();
        return $query;
    }
     //====end code for get qsa list=====//
    
     //====code for get qa list=====//
    public function qa_list(){
        $this->db->select('id,full_name');
        $this->db->from('users');
        $this->db->where("user_type",3);
        $this->db->where("status",'active');
        $query = $this->db->get()->result();
        return $query;
    }
     //====end code for get qa list=====//
    
     //====code for get consultants list=====//
    public function consulants_list(){
        $this->db->select('id,full_name');
        $this->db->from('users');
        $this->db->where("user_type",4);
        $this->db->where("status",'active');
        $query = $this->db->get()->result();
        return $query;
    }
     //====end code for get consultants list=====//
    
     //====code for get customer process=====//
    public function get_customer_process($id){
      $this->db->select('id,process_name');
        $this->db->from('process');
        $this->db->where("customer_id",$id);
        $this->db->where("status",'0');
        $query = $this->db->get()->result();
        return $query;
        
    }
     //====end code for get customer process=====//
    
    //======code for add compliance project=========//
    public function add_project(){
        $data['service_id'] = ID_decode($_POST['service_id']);
        $data['customer_id'] = $_POST['customer_id'];
        $data['process_id'] = $_POST['process_id'];
        $data['qsa_id'] = $_POST['qsa_id'];
        $data['consultant_id'] = $_POST['consultant_id'];
        $data['qa_id'] = $_POST['qa_id'];
        $data['start_date'] = $_POST['start_date'];
        $data['created_date'] = date('Y-m-d H:i:s');
        if($this->db->insert('compliance_project',$data)){
               return TRUE;
           }else{
               return FALSE;
           }
        
        
    }
 //=====end code for add compliance project====//
    
    //==========code for get project list==========//
    
    public function project_list($id){
        $this->db->select('*,(select full_name from users where id=compliance_project.customer_id) AS CustomerName,(select full_name from users where id=compliance_project.qsa_id) AS QSAName,(select full_name from users where id=compliance_project.consultant_id) AS ConsultantName,(select full_name from users where id=compliance_project.qa_id) AS QaName,(select process_name from process where id = compliance_project.process_id) AS ProcessName');
        $this->db->from('compliance_project');
        $this->db->where("service_id",$id);
        $this->db->where("status",'0');
		$this->db->order_by("id", "desc");
        $query = $this->db->get()->result();
         return $query;
    }


    //==========end code for get project list=========//
    
//===========code for get project details==========//
     public function project_details($id){
       $this->db->select('*,(select process_name from process where id = compliance_project.process_id) AS ProcessName');
        $this->db->from('compliance_project');
        $this->db->where("id",$id);
        $this->db->where("status",'0');
        $query = $this->db->get()->row();
        //pr($query);die;
        return $query;
    }
//==========end code for get project details==========//
    
	 //==========start code for common delete fucntion==================//
	public function deleteCompliances($prject_id,$service_id,$process_id,$customer_id,$qsa_id,$qa_id,$consultant_id){
		
			$this->db->delete('customer_comments', array('process_id'=>$process_id,'service_id'=>$service_id,'customer_id'=>$customer_id)); 
			$this->db->delete('consultant_comments', array('process_id'=>$process_id,'service_id'=>$service_id,'customer_id'=>$customer_id,'consultant_id'=>$consultant_id));
			$this->db->delete('qsa_comments', array('process_id'=>$process_id,'service_id'=>$service_id,'customer_id'=>$customer_id,'qsa_id'=>$qsa_id));
			$this->db->delete('qa_comments', array('process_id'=>$process_id,'service_id'=>$service_id,'customer_id'=>$customer_id,'qa_id'=>$qa_id));
		
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
//===========code for delete compliance ===========//
  public function deleteComp11liances($id){
        $data['status'] = '1';
        $this->db->where('id', $id);
        $this->db->update('compliance_project', $data);
        $update = $this->db->affected_rows();
        if ($update) {
            return true;
        } else {
            return false;
        }
  }
//===========end code for delete compliance===========// 
  
  //===== code for check preexistance=====//
    function check_preexistance($id, $serviceId,$process,$customerId) {
        
        $this->db->select('*');
        $this->db->where('id !=', $id);
        $this->db->where('service_id', $serviceId);
        $this->db->where('process_id', $process);
        $this->db->where('customer_id', $customerId);
        $this->db->where('end_date', '');
        $query = $this->db->get('compliance_project');
       // echo $this->db->last_query();die;
        //pr($query->result());
        return $query->num_rows();
    }
  //===== end code for check preexistance=====//
  
  
  //===== code for disable button preexistance=====//
    function check_disabledbutton($serviceid,$cus_id,$process_id) {
        
        $this->db->select('count(*) as total');
        $this->db->where('service_id', $serviceid);
        $this->db->where('process_id', $process_id);
        $this->db->where('customer_id', $cus_id);
        $this->db->where('admin_status', 1);
		$query = $this->db->get('uplaod_evidence');
       // echo $this->db->last_query();die;
        return $query->result();
        //return $query->num_rows();
    }
  //===== end code for disabled button preexistance=====//
    
    //===== code for check preexistance=====//
    function check_preexistance_add($serviceId,$process,$customerId) {
        $this->db->select('*');
        $this->db->where('service_id', $serviceId);
        $this->db->where('process_id', $process);
        $this->db->where('customer_id', $customerId);
        $this->db->where('end_date', '');
        $query = $this->db->get('compliance_project');
        //echo $this->db->last_query();die;
        //pr($query->result());
        return $query->num_rows();
    }
  //===== end code for check preexistance=====//
    
  
  //======code for edit compliance project=========//
    
    public function edit_project($id){
        $data['customer_id'] = $_POST['customer_id'];
        $data['process_id'] = $_POST['process_id'];
        $data['qsa_id'] = $_POST['qsa_id'];
        $data['consultant_id'] = $_POST['consultant_id'];
        $data['qa_id'] = $_POST['qa_id'];
        $data['start_date'] = $_POST['start_date'];
        $this->db->where('id', $id);
        if($this->db->update('compliance_project', $data)){
               return TRUE;
           }else{
               return FALSE;
           }
        
        
    }
 //=====end code for edit compliance project====// 
 
 //======code for admin accept/reject of qsa=========//
    
    public function accepttoqsa($accepted_id,$question_id,$service_id,$process_id,$customer_id){
		$date=date('Y-m-d');
		$data = array(
			'admin_qsa'=>@$accepted_id,
			'admin_qsa_date'=>@$date,
			'status'=>'0',
			'status_date'=>'',
			'qsa_modification'=>'0',
			'qsa_date'=>'',
		);
    	$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
        if($this->db->update('uplaod_evidence', $data)){
		       return TRUE;
           }else{
               return FALSE;
           }
    }
 //=====end code for admin accept/reject of qsa====//
 //======code for admin accept/reject of qa=========//
    
    public function accepttoqa($accepted_id,$question_id,$service_id,$process_id,$customer_id){
		$date=date('Y-m-d H:i:s');
		if(trim($accepted_id) =="1"){
			
			$statusdata = array(
				'qestion_id'=>$question_id,
				'customer_id'=>$customer_id,
				'service_id'=>$service_id,
				'process_id'=>$process_id,
				 'admin_status'=>1
				);
			
			$this->db->insert('admin_status_log',$statusdata);
             
			$data = array(
				'admin_qa'=>$accepted_id,
				'admin_qa_date'=>$date,
				'qa_status'=>'0',
				'qa_status_date'=>'',
				'qa_modification'=>'0',
				'qa_date'=>'',
				'status'=>'0',
				'status_date'=>'',
			);
		}else{
			$data = array(
				'admin_qa'=>$accepted_id,
				'admin_qa_date'=>$date,
			);
		}
		
		
        $this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
        if($this->db->update('uplaod_evidence', $data)){
			//echo $this->db->last_query();
		//	die;
	          return TRUE;
           }else{
               return FALSE;
           }
       
        
    }
 //=====end code for admin accept/reject of qa====//
 
 //======code for admin accept/reject of qa=========//
    
    public function accepttocustomer($accepted_id,$question_id,$service_id,$process_id,$customer_id){
		$date=date('Y-m-d');
		$data = array(
			'admin_customer'=>@$accepted_id,
			'admin_customer_date'=>@$date,
			'cus_modification'=>'0',
			'cus_modification_date'=>'',
		);
		
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
        if($this->db->update('uplaod_evidence', $data)){
		       return TRUE;
           }else{
               return FALSE;
           }
       
        
    }
 //=====end code for admin accept/reject of qa====//
 
 //======code for admin accept/reject of qa=========//
    
    public function AcceptToConsultant($accepted_id,$question_id,$service_id,$process_id,$customer_id){
		$date=date('Y-m-d');
		$data = array(
			'admin_cusaltant'=>@$accepted_id,
			'admin_cusaltant_date'=>@$date,
			'consultant_status'=>'0',
			'consultant_status_date'=>'',
			'cusaltant_modification'=>'0',
			'cunsaltant_date'=>'',
			
		);
        
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
        if($this->db->update('uplaod_evidence', $data)){
			//echo $this->db->last_query();
               return TRUE;
           }else{
               return FALSE;
           }
       
        
    }
 //=====end code for admin accept/reject of qa====//
 
// start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
	public function Common_Update_Compliances($tableName,$data,$question_id,$service_id,$process_id,$customer_id){
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
		//$this->db->where('status', 1);
		//$this->db->where('qa_status',1);
		//$this->db->where('customer_id', $customer_id);
		if($this->db->update($tableName, $data)){
			//echo $this->db->last_query();
			   return TRUE;
           }else{
               return FALSE;
           }
		
		
	}
	
   // start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
   
    //==========code for get upload evidence =================//
    
    public function get_data($question_id,$process_id,$service_id,$customer_id){
		   $this->db->select('id,parent_id');
		   $this->db->from('users');
		   $this->db->where("id", $customer_id);
		   $user_id = $this->db->get()->result();
		   $users_id="";
				if(trim($user_id[0]->parent_id) == "0"){
					$users_id=$user_id[0]->id;
				}else{
					$users_id=$user_id[0]->parent_id;
				}
				$this->db->select('id,parent_id');
				$this->db->from('users');
				$this->db->where("parent_id", $users_id);
				$multiuser_id = $this->db->get()->result();
				 $ids=array();
		   $parent_id=0;
		   foreach($multiuser_id as $multiuser_ids){
			   $ids[]=$multiuser_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$users_id.",".$parent_id;
		   }
		   
		 $parents_id=explode(',',$parent_id);
        $this->db->select('*');
        $this->db->from('uplaod_evidence');
        $this->db->where("questionnaire_id",$question_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where_in("customer_id",$parents_id);
        $query = $this->db->get();
		return $num = $query->result();
    }
    //==========end code for upload evidence==================//
 
 
 // start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
	public function Common_Update_End_date($tableName,$data,$service_id,$process_id,$customer_id,$project_id){
		$this->db->where('id', $project_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
		if($this->db->update($tableName, $data)){
			   return TRUE;
           }else{
               return FALSE;
           }
		
		
	}
	
   // start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
  //==========code for get compliance_project =================//
    
    public function get_data_compliance_project($service_id,$process_id,$userdata_id){
		$this->db->select('*');
        $this->db->from('compliance_project');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$userdata_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for compliance_project==================//
	
	
	 //==========code for get upload evidence =================//
    
    public function get_data_without_question_id($process_id,$service_id,$customer_id,$user_id){
		$userdata=$this->session->userdata;
        $this->db->select('*');
        $this->db->from('common_uplaod_docs');
         $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("user_id",$user_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for upload evidence==================//
	
	 // start code for insert common function
	public function insert_common_function($tableName,$data){
		 if($this->db->insert($tableName,$data)){
					return TRUE;
				}else{
					return FALSE;
				}
		
	}
	 // end code for insert common function
	 
	  //==========code for get upload evidence =================//
    
    public function get_data_without_question($process_id,$service_id,$customer_id){
		$userdata=$this->session->userdata;
        $this->db->select('admin_status');
        $this->db->from('uplaod_evidence');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for upload evidence==================// 
	  //==========code for get upload evidence =================//
    
    public function start_date($serviceid,$customer_id,$process_id){
		$userdata=$this->session->userdata;
        $this->db->select('start_date,end_date');
        $this->db->from('compliance_project');
        $this->db->where("service_id",$serviceid);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for upload evidence==================//
	
	 //==========code for get compliance_project =================//
    
    public function get_data_roc_aoc($docs_id,$service_id,$process_id,$year,$customer_id){
		
		//echo $docs_id."--".$service_id,$process_id,$year,$customer_id;
		$this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("id",$docs_id);
		 $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("year",$year);
        $query = $this->db->get();
		//echo $this->db->last_query();die;
		return $num = $query->row();
    }
    //==========end code for compliance_project==================//
	
	//==========code for get compliance_project =================//
    
    public function get_data_roc($service_id,$process_id,$customer_id,$year=null){
		$this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("report_of","ROC");
		if(!empty($year)){
			   $this->db->where("year",$year);
		}
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->row();
    }
    //==========end code for compliance_project==================//
	
	//==========code for get compliance_project =================//
    
    public function get_data_rot($service_id,$process_id,$customer_id,$year=null){
		$this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("report_of","ROT");
		if(!empty($year)){
			   $this->db->where("year",$year);
		}
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->row();
    }
    //==========end code for compliance_project==================//
	
	//==========code for get compliance_project =================//
    
    public function get_data_aoc($service_id,$process_id,$customer_id,$year=null){
		$this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("report_of","AOC");
		if(!empty($year)){
			   $this->db->where("year",$year);
		}
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->row();
    }
    //==========end code for compliance_project==================//
	//==========code for get compliance_project =================//
    
    public function get_data_aot($service_id,$process_id,$customer_id,$year=null){
		$this->db->select('*');
        $this->db->from('report_aoc_roc');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("report_of","AOT");
		if(!empty($year)){
			   $this->db->where("year",$year);
		}
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->row();
    }
    //==========end code for compliance_project==================//
	
	 //==========start code for delete upload dcoument questionnareis wise==================//
	public function delete_roc_aoc_docs($docs_id){
		$this->db->where('id', $docs_id);
		if($this->db->delete('report_aoc_roc')){
			   return TRUE;
           }else{
               return FALSE;
           }
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
}
