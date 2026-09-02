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
class Customer_mod extends CI_Model {

    var $user_table = "users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    
    
    function add_customer(){
        $data['full_name'] = $_POST['full_name'];
        $data['email'] = $_POST['email'];
        $data['phone_number'] = $_POST['phone_number'];
        $data['company_name'] = $_POST['company_name'];
        $data['company_number'] = $_POST['company_number'];
        $data['address'] = $_POST['address'];
        $data['status'] = $_POST['status'];
        $data['user_type'] = 5;
        $data['unique_id'] = $_POST['unique_id'];
        $data['password'] = md5(time());
        $data['pwdstring'] = time();
        $data['created_date'] = date('Y-m-d H:i:s');
        if($this->db->insert("users", $data)){
        $insert_id = $this->db->insert_id();
        
        $len = count($_POST['varient_name']);
        
        for ($i = 0; $i < $len; $i++) {
                if(isset($_POST['customer_process'][$i]) && !empty($_POST['customer_process'][$i])){
                $customer_varient['customer_id'] = $insert_id;
                $customer_varient['process_name'] = $_POST['customer_process'][$i];
                $customer_varient['created_date'] = date('Y-m-d H:i:s');
                $this->db->insert('process', $customer_varient);
            }
        }
            return TRUE;
        }else{
            return FALSE;
        }
            
    }
    
    public function customer_listing(){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("status !=",'delete');
        $this->db->where("user_type", '5');
        $this->db->where("parent_id", '0');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get()->result();
        return $query;
    }
    
    public function customer_detail($id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("status", 'active');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        
        /* fetching customer process */
        $this->db->select('*');
        $this->db->where('customer_id', $id);
        $customerProcess = $this->db->get("process")->result();
        /* end customer process */
        $query->customer_process = $customerProcess;
        return $query;
    }
    
    public function deleteCustomerProcess($decode_id) {
        $this->db->where('id', $decode_id);
        $this->db->delete('process');
        $update = $this->db->affected_rows();
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
    
    function edit_customer($id){
        $data['full_name'] = $_POST['full_name'];
        $data['email'] = $_POST['email'];
        $data['phone_number'] = $_POST['phone_number'];
        $data['company_name'] = $_POST['company_name'];
        $data['company_number'] = $_POST['company_number'];
        $data['address'] = $_POST['address'];
        $data['status'] = $_POST['status'];
        $this->db->where('id', $id);
        if($this->db->update("users", $data)){
        //$insert_id = $this->db->insert_id();
       // $_POST['varient_name'] = array_filter($_POST['varient_name']);
        $len = count($_POST['varient_name']);
         //code for inser varient//
            $len = count($_POST['varient_name']);
            for ($i = 0; $i < $len; $i++) {
                
                if ($_POST['varient_name'][$i] != '') {
                    if(isset($_POST['customer_process'][$i]) && !empty($_POST['customer_process'][$i])){
                    $varient_id = $_POST['varient_name'][$i];
                    $customer_varient['process_name'] = $_POST['customer_process'][$i];
                    $this->db->where('id', $varient_id);
                    $this->db->update('process', $customer_varient);
                    }
                }else{
                    if(isset($_POST['customer_process'][$i]) && !empty($_POST['customer_process'][$i])){
                    $customer_varient['customer_id'] = $id;
                    $customer_varient['process_name'] = $_POST['customer_process'][$i];
                    $customer_varient['created_date'] = date('Y-m-d H:i:s');
                    $this->db->insert('process', $customer_varient);
                    }
                }
                
            }   
           
            
            return TRUE;
        }else{
            return FALSE;
        }
            
    }
    
    //===========code for add process===========//
    
    public function add_process(){
                $process['customer_id'] = $_POST['customer_id'];
                $process['process_name'] = $_POST['process_name'];
                $process['created_date'] = date('Y-m-d H:i:s');
                if($this->db->insert('process', $process)){
                    return TRUE;
                }else{
                    return FALSE;
                }
    }


    //==========end code for add process==========//
    
    //=========code for delete customer ==========//
    public function delete_customer($id){
        $data['status'] = 'delete';
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $update = $this->db->affected_rows();
        if ($update) {
            $this->db->select('id');
            $this->db->where('customer_id', $id);
            $customerProcess = $this->db->get("process")->result_array();
            if(isset($customerProcess) && !empty($customerProcess)){
                foreach($customerProcess as $process){
                    $id = $process['id'];
                    $data['status'] = '1';
                    $this->db->where('id', $id);
                    $this->db->update('process', $data);
                    $update = $this->db->affected_rows();
                }
            }
            return true;
        } else {
            return false;
       }
    }
//=========end code for delete customer=========//
    
//==========code for get customer details=============//
    public function get_customer($id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
    }
//==========end code for get customer details==========//
    
    
 //==========code for get customer process=============//
    public function get_customer_porcess($id){
        $this->db->select('*');
        $this->db->from('process');
        $this->db->where("process.customer_id", $id);
        $query = $this->db->get()->result();
        
        $processId = array();
        foreach($query as $process){
        $processId[] = $process->id;
        
        }
        $this->db->select('*');
        $this->db->where_in('process_id', $processId);
        $this->db->where('customer_id', $id);
        $customerProcess = $this->db->get("compliance_project")->result();
		
		$this->db->select('*');
        $this->db->where_in('process_id', $processId);
        $this->db->where('customer_id', $id);
        $customertestingProcess = $this->db->get("testing_project")->result();
	    $compliance = array();
        $testing = array();
      
        foreach($customerProcess as $project){
         $compliance[$project->customer_id][$project->process_id][$project->service_id]  = $project;
           
        }
		//pr($compliance);
		foreach($customertestingProcess as $projecttesting){
		//	pr($projecttesting);
         $testing[$projecttesting->customer_id][$projecttesting->process_id][$projecttesting->testing_id]  = $projecttesting;
           
        }
		//die;
		//pr($testing);die;
        $customerview = array();
        $i =0;
        foreach($query as $customerprocess1){
            $customerview[$i] = $customerprocess1;
            $customerview[$i]->customer_process = @$compliance[$customerprocess1->customer_id][$customerprocess1->id];
            $customerview[$i]->customer_testing_process = @$testing[$customerprocess1->customer_id][$customerprocess1->id];
            $i++;
        }
		//pr($customerview);die;
        return $customerview;
    }
//==========end code for get customer process==========//   

//==========code for get customer process=============//
    public function get_process_details($id){
        $this->db->select('*');
        $this->db->from('process');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        
        /* fetching customer process */
        $this->db->select('*');
        $this->db->where('process_id', $id);
        $customerProcess = $this->db->get("compliance_project")->result();
        /* end customer process */
		
		 $this->db->select('*');
        $this->db->where('process_id', $id);
        $customertestingProcess = $this->db->get("testing_project")->result();
        $query->customer_process = $customerProcess;
        $query->customer_testing_process = $customertestingProcess;
        return $query;
    }
//==========end code for get customer details==========//


//==========code for get total question=============//
    public function get_total_question($id){
        $this->db->select('count(id) as totalQuestion');
        $this->db->from('questionnaire');
        $this->db->where("service_id", $id);
        $this->db->where("status", 1);
        $query = $this->db->get()->result();
        return $query;
    }
//==========end code for get total question=============//
//==========code for get total rejected  question=============//
    public function get_rejcted_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalQuestionRejected');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
		$this->db->where("(status='2' OR qa_status='2' OR admin_status='2')");
        //$this->db->where("status", 2);
       // $this->db->or_where("qa_status", 2);
       // $this->db->or_where("admin_status", 2);
        $query = $this->db->get()->result();
		//echo $this->db->last_query();
	//	die;
        return $query;
    }
//==========end code for get total rejected question=============//

//==========code for get total approevd question=============//
    public function get_approved_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalQuestionApproved');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("status", 1);
        $this->db->where("qa_status", 1);
        $this->db->where("(admin_status='0' OR admin_status='1')");
        $query = $this->db->get()->result();
		//echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total approved question=============//

//==========code for get total assigned  question=============//
    public function get_assingedtoqsa_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalAssignedtoqsa');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("status", 0);
        $this->db->where("qa_status", 0);
	    $this->db->where("(admin_status='0' OR admin_status='1')");
        $query = $this->db->get()->result();
	//	echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total assigned question=============//

//==========code for get total assigned  question=============//
    public function get_assingedtoqa_questoin($service_id,$customer_id,$process_id){
		$this->db->select('count(id) as totalAssignedtoqa');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("status", 1);
		$this->db->where("qa_status", 0);
		$this->db->where("(admin_status='0' OR admin_status='1')");
		$query = $this->db->get()->result();
		//echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total assigned question=============//



//==========code for get total question=============//
    public function get_incomplete_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalQuestionIncomplete');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
		//$this->db->where("status", 4);
		//$this->db->or_where("qa_status", 4);
		//$this->db->or_where("admin_status", 4);
       $this->db->where("(status='4' OR qa_status='4' OR admin_status='4')");
        $query = $this->db->get()->result();
		//echo $this->db->last_query();
		///die;
        return $query;
    }
//==========end code for get total question=============//

//==========code for get total question=============//
    public function get_Attempted_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalAttempted');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
      //  $this->db->where("status", 0);
        $query = $this->db->get()->result();
	//	echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total question=============//
//==========code for get total question disapproved by qsa=============//
    public function get_disapprovedbyQSA_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalDisapprovedbyqsa');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
        $this->db->where("status", 2);
        $this->db->where("qa_status", 0);
		$this->db->where("(admin_status='0' OR admin_status='1' OR admin_status='2')");
        $query = $this->db->get()->result();
	//	echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total question=============//

//==========code for get total question disapproved by qsa=============//
    public function get_disapprovedbyQA_questoin($service_id,$customer_id,$process_id){
        $this->db->select('count(id) as totalDisapprovedbyqa');
        $this->db->from('uplaod_evidence');
        $this->db->where("process_id", $process_id);
        $this->db->where("service_id",$service_id);
        $this->db->where("customer_id",$customer_id);
		$this->db->where("status", 1);
        $this->db->where("qa_status", 2);
		$this->db->where("(admin_status='0' OR admin_status='1' OR admin_status='2')");
        $query = $this->db->get()->result();
	//	echo $this->db->last_query();
		//die;
        return $query;
    }
//==========end code for get total question=============//
    
//==========code for add sub customer====================//

    public function add_sub_customer($id){
        $password = time();
        $data['full_name'] = $_POST['full_name'];
        $data['parent_id'] = $id;
        $data['email'] = $_POST['email'];
        $data['password'] = md5($password);
        $data['pwdstring'] = $password;
        $data['status'] = $_POST['status'];
        $data['user_type'] = 5;
        $data['created_date'] = date('Y-m-d H:i:s');
        if($this->db->insert("users", $data)){
            $insert_id = $this->db->insert_id();
            if(isset($_POST['process_id']) && !empty($_POST['process_id'])){
                $processId = $_POST['process_id'];
                $processId1 = implode(',',$processId);
                
//=====code for assign process to customer 
                $data1['customer_id'] = $insert_id;
                $data1['process_id'] = $processId1;
                $data1['created_date'] = date('Y-m-d H:i:s');
                $this->db->insert("subcustomer_process", $data1);

//                $this->db->select('asigned_customer_id');
//                $this->db->from('process');
//                $this->db->where_in('id', $processId);
//                $query = $this->db->get()->row();
//                if ($query->asigned_customer_id != 0) {
//                    $data1['asigned_customer_id'] = $query->asigned_customer_id . ',' . $insert_id;
//                } else {
//                    $data1['asigned_customer_id'] = $insert_id;
//                }
//                $this->db->where_in('id', $processId);
//                $this->db->update('process', $data1);
//                $update = $this->db->affected_rows();
                //=====code for assign process to customer 
            }
            return TRUE;
        }else{
            return FALSE;
        }
    }

//=========end code for add sub customer================//    
    
 //======code for get sub customer==============//
    
 public function sub_customer_listing($id){
     $this->db->select('*');
     $this->db->from('users');
     $this->db->where('parent_id', $id);
     $query = $this->db->get()->result();
     
     $customerId = array();
     foreach ($query as $customer) {
         $customerId[] = $customer->id;
     }
     if(isset($customerId) && !empty($customerId)){
     $this->db->select('*');
     $this->db->from('subcustomer_process');
     $this->db->where_in('customer_id',$customerId);
     $processdata = $this->db->get()->result();
    
     $subprocess = array();
     foreach($processdata as $subcusdata){
         $subprocess[$subcusdata->customer_id] = $subcusdata;
     }
      $subcustomerview = array();
        $i =0;
        foreach($query as $subcustomerprocess){
            $subcustomerview[$i] = $subcustomerprocess;
            $subcustomerview[$i]->sub_customer_process = @$subprocess[$subcustomerprocess->id]->process_id;
            $i++;
        }
       
        return $subcustomerview;
     }
    
 }








// public function sub_customer_listing($id) {
//        $this->db->select('*');
//        $this->db->from('users');
//        $this->db->where('parent_id', $id);
//        $query = $this->db->get()->result();
//        // pr($query);
//        $customerId = array();
//        foreach ($query as $customer) {
//            $customerId[] = $customer->id;
//        }
//        $this->db->select('*');
//        foreach ($customerId as $id) {
//            //$this->db->or_where("FIND_IN_SET($id,asigned_customer_id)!=", 0);
//            $this->db->where("customer_id",$id);
//        }
//        $customerProcess = $this->db->get("process")->result();
//        $custprocess = array();
//        $i = 0;
//        foreach ($customerProcess as $project) {
//            $asignedcusId = explode(',',$project->asigned_customer_id);
//            foreach($asignedcusId as $customerid){
//            $custprocess[$customerid][$i] = $project;
//            }
//            $i++;
//        }
//
//        $subcustomerview = array();
//        $i =0;
//        foreach($query as $subcustomerprocess){
//            $subcustomerview[$i] = $subcustomerprocess;
//            $subcustomerview[$i]->sub_customer_process = @$custprocess[$subcustomerprocess->id];
//            $i++;
//        }
//        return $subcustomerview;
//    }

    //======end code for get sub customer===========//   
 
 //======code for get customer process==============//
    
 public function get_customer_process($id){
     $this->db->select('*');
     $this->db->from('process');
     $this->db->where('customer_id',$id);
     $query = $this->db->get()->result();
     return $query;
 }
 //======end code for get  customer process===========//   
 
 //======code for asign process to customer===========//
 
 public function asign_process($id){
     if($_POST){
         $this->db->select('asigned_customer_id');
         $this->db->from('process');
         $this->db->where('id',$_POST['process_id']);
         $query = $this->db->get()->row();
         if($query->asigned_customer_id !=0){
            $data['asigned_customer_id'] = $query->asigned_customer_id.','.$id; 
         }else{
          $data['asigned_customer_id'] =  $id;   
         }
         $this->db->where('id', $_POST['process_id']);
         $this->db->update('process', $data);
         $update = $this->db->affected_rows();
         if($update){
             return TRUE;
         }else{
             return FALSE;
         }
     }
 }
//======end code for asign process to customer=======//
 
 //=====code for check preexistance =================//
 public function check_preexistace($processId,$customer_id){
       $this->db->select('*');
        $this->db->where('id', $processId);
        $this->db->where("FIND_IN_SET($customer_id,asigned_customer_id)!=", 0);
        $query = $this->db->get('process');
     // echo  $this->db->last_query();die;
        return $query->num_rows();
 }
 //=====end code for check preexistance==============// 
 
 
 //=====code for get sub customer details =================//
 public function get_subcustomer_details($customer_id){
       $this->db->select('*');
        $this->db->where('id', $customer_id);
        $query = $this->db->get('users');
     // echo  $this->db->last_query();die;
        return $query->row();
 }
 //=====end code for get sub customer details==============// 
 
 //=====code for get sub customer process =================//
 public function get_subcustomer_process($customer_id){
       $this->db->select('process_id');
       // $this->db->where("FIND_IN_SET($customer_id,asigned_customer_id)!=", 0);
       $this->db->where("customer_id",$customer_id);
        $query = $this->db->get('subcustomer_process')->row();
        @$processId = explode(',',$query->process_id);
        return $processId;
 }
 //=====end code for get sub customer process==============// 
 
 //======code for asign process to customer===========//
 
 public function edit_sub_customer($id){
        
        $data['full_name'] = $_POST['full_name'];
        $data['email'] = $_POST['email'];
        $data['status'] = $_POST['status'];
        $this->db->where('id', $id);
        $update = $this->db->update('users', $data);
        if($update){
            
                
               //=====code for assign process to customer 
                $this->db->where('customer_id', $id);
                $update1 = $this->db->delete('subcustomer_process');
                if($update1){
                if(isset($_POST['process_id']) && !empty($_POST['process_id'])){    
                $processId = $_POST['process_id'];
                $processId1 = implode(',',$processId);
                $data1['customer_id'] = $id;
                $data1['process_id'] = $processId1;
                $data1['created_date'] = date('Y-m-d H:i:s');
                $this->db->insert("subcustomer_process", $data1);
                }
                }
               //=====code for assign process to customer 
            
            return TRUE;
        }else{
            return FALSE;
        }
    }
//======end code for asign process to customer=======//

//=========code for move process in archive folder=========//

    public function move_archive($id){
        if(!empty($id)){
            $data['process_id'] = $id;
            $data['created_date'] = date('Y-m-d H:i:s');
            if($this->db->insert('archived',$data)){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }






//=========end code for move process in archive folder=====//    
    
        }



