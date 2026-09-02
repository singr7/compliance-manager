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
class Process_mod extends CI_Model {
    
    
    //==========code for get process name =================//
    
    public function get_process_name($id){
        $this->db->select('*');
        $this->db->from('process');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
    }


    //==========end code for process name==================//
    
    //==========code for get process details =================//
    
    public function get_process_details($id){
        $this->db->select('*');
        $this->db->from('compliance_project');
        $this->db->where("process_id", $id);
        $query = $this->db->get()->result();
        return $query;
    }


    //==========end code for process details ==================//
	
	 //==========code for get process details =================//
    
    public function get_testing_details($id){
		$this->db->select('*');
        $this->db->from('testing_project');
        $this->db->where("process_id", $id);
        $query = $this->db->get()->result();
	//	echo $this->db->last_query();
	    return $query;
    }


    //==========end code for process details ==================//
    
    //==========code for get service name =================//
    
    public function get_service_name($id){
        $this->db->select('*');
        $this->db->from('services');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
    }


    //==========end code for service name==================//
	
	//==========code for get service name =================//
    
    public function get_testing_service_name($id){
        $this->db->select('*');
        $this->db->from('testing');
        $this->db->where("id", $id);
        $query = $this->db->get()->row();
        return $query;
    }


    //==========end code for service name==================//
    
     //==========code for get questionair =================//
    
    public function get_questionair($id){
        $this->db->select('*');
        $this->db->from('questionnaire');
        $this->db->where("service_id", $id);
        $this->db->where("status",1);
        $query = $this->db->get()->result();
		//echo  $this->db->last_query();
        return $query;
    }
    //==========end code for get questionair==================//
	
	 //==========code for get upload evidence =================//
    
    public function get_docsdata($id){
		$userdata=$this->session->userdata;
        $this->db->select('*');
        $this->db->from('uplaod_evidence');
        $this->db->where("service_id", $id);
        $this->db->where("process_id",$userdata['process_id']);
        $this->db->where("customer_id",$userdata['userinfo']->id);
        $query = $this->db->get();
        return $num = $query->num_rows();
    }
    //==========end code for upload evidence==================//
	
	
	//==========code for insert upload questionaries docs =================//
    
	public function get_uploaded_docs($servide_id,$evidencedatas,$process_id){
		   $userdata=$this->session->userdata;
		   $this->db->select('id,parent_id');
		   $this->db->from('users');
		   $this->db->where("id", $userdata['userinfo']->id);
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
			  $parent_id=$userdata['userinfo']->id;
		   }else{
			  $parent_id=$users_id.",".$parent_id;
		   }
		   
		    $parents_id=explode(',',$parent_id);
			
			$this->db->select('*');
			$this->db->from('uplaod_evidence');
			$this->db->where("service_id", $servide_id);
			$this->db->where_in("customer_id",$parents_id);
			$this->db->where("questionnaire_id",$evidencedatas);
			$this->db->where("process_id",$process_id);
			$query = $this->db->get()->row();
				//echo $this->db->last_query();die;
			return $query;
		
	}
	
	 // start code for insert common function
	public function insert_common_function($tableName,$data){
		 if($this->db->insert($tableName,$data)){
					return TRUE;
				}else{
					return FALSE;
				}
		
	}
	 // end code for insert common function
	
	
	public function insert_uploaded_docs($questionid,$newfilename,$service_id,$process_id,$customerid,$parent_id){
		if(isset($questionid) && !empty($questionid)){
			$userdata=$this->session->userdata;
			$docsdata=array(
				'questionnaire_id'=>$questionid,
				'service_id'=>$service_id,
				'process_id'=>$process_id,
				'parent_id'=>$parent_id,
				'customer_id'=>$customerid,
				'user_id'=>$userdata['userinfo']->id,
				'docs'=>$newfilename);
			    if($this->db->insert('common_uplaod_docs',$docsdata)){

					return TRUE;
				}else{
					return FALSE;
				}
				
		}
		
	}
	
    //==========code for insert upload questionaries docs==================//
    //==========start code for delete upload dcoument questionnareis wise==================//
	public function delete_process_docs($docid){
		$this->db->where('id', $docid);
		$this->db->delete('common_uplaod_docs'); 
		echo $this->db->last_query();
		
		die;

		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	 //==========start code for delete upload dcoument questionnareis wise==================//
	public function RequestForModification($tableName,$data,$question_id,$service_id,$process_id,$user_id){
		$userid=ID_decode(@$user_id);
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $userid);
		$this->db->update($tableName, $data);
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	 //==========start code for delete upload dcoument questionnareis wise==================//
	public function RequestForStatus($tableName,$data,$question_id,$service_id,$process_id,$customer_id){
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
		if($this->db->update($tableName, $data)){
			//echo $this->db->last_query();
               return TRUE;
           }else{
               return FALSE;
           }
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	  //==========code for insert upload questionaries docs==================//
    //==========start code for delete upload dcoument questionnareis wise==================//
	public function get_process_docs($docid){
			$this->db->select('docs');
			$this->db->from('common_uplaod_docs');
			$this->db->where("id",$docid);
			$query = $this->db->get()->row();
			return $query;
			//echo $this->db->last_query();
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	
}
