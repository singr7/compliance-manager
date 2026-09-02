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
class Qsa_mod extends CI_Model {
    
    
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
    
    //==========code for get service name =================//
    
    public function get_service_name($id){
        $this->db->select('*');
        $this->db->from('services');
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
        return $query;
    }
    //==========end code for get questionair==================//
	
	 //==========code for get upload evidence =================//
    
    public function get_docsdata($service_id,$process_id,$userdata_id){
		$userdata=$this->session->userdata;
        $this->db->select('*');
        $this->db->from('uplaod_evidence');
        $this->db->where("service_id",$service_id);
        $this->db->where("process_id",$process_id);
        $this->db->where("customer_id",$userdata_id);
        $query = $this->db->get();
		//echo $this->db->last_query();
		return $num = $query->result();
    }
    //==========end code for upload evidence==================//
	
	
	
	//==========code for insert upload questionaries docs =================//
    
	public function get_uploaded_docs($servide_id,$evidencedatas){
			$userdata=$this->session->userdata;
			$this->db->select('*');
			$this->db->from('uplaod_evidence');
			$this->db->where("service_id", $servide_id);
			$this->db->where("customer_id",$userdata['userinfo']->id);
			$this->db->where("questionnaire_id",$evidencedatas);
			$query = $this->db->get()->row();
			//echo $this->db->last_query();
			return $query;
		
	}
	
	//==========code for insert upload questionaries docs =================//
    
	public function get_fetch_evidence_data($servide_id,$questionids,$process_id,$customer_id){
			$this->db->select('*');
			$this->db->from('uplaod_evidence');
			$this->db->where("service_id", $servide_id);
			$this->db->where("customer_id",$customer_id);
			$this->db->where("questionnaire_id",$questionids);
			$this->db->where("process_id",$process_id);
			$query = $this->db->get()->row();
			return $query;
		
	}
	
	
	
	
	public function insert_evidence_data($servide_id,$evidencedata0,$evidencedata1,$evidencedata2){
			$userdata=$this->session->userdata;
			$data=array(
				'questionnaire_id'=>$evidencedata0,
				'service_id'=>$servide_id,
				'customer_id'=>$userdata['userinfo']->id,
				'comments'=>$evidencedata2,
				'process_id'=>$userdata['process_id'],
				'quest_checked_val	'=>$evidencedata1
				);
			   if($this->db->insert('uplaod_evidence',$data)){
					return TRUE;
				}else{
					return FALSE;
				}
		
	}
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
				//		echo $this->db->last_query();die;
					return TRUE;
				}else{
					return FALSE;
				}
			}
		
	}
    //==========code for insert upload questionaries docs==================//\
	
	//====================== start code for insert common function===========================//
	public function insert_common_function($tableName,$data){
		    if($this->db->insert($tableName,$data)){
				return TRUE;
			}else{
				return false;
			}
		
	}
	
	 //==============end code for insert common function===============//
	
    //==========start code for delete upload dcoument questionnareis wise==================//
	public function delete_process_docs($docid){
		$this->db->where('id', $docid);
		if($this->db->delete('common_uplaod_docs')){
			//echo $this->db->last_query();
               return TRUE;
           }else{
               return FALSE;
           }
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	
	 //==========start code for delete upload dcoument questionnareis wise==================//
	public function updateStatusBy_Qsa($tableName,$data,$question_id,$service_id,$process_id,$userid_id){
		
		   $this->db->select('id,parent_id');
		   $this->db->from('users');
		   $this->db->where("id", $userid_id);
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
			  $parent_id=$userid_id;
		   }else{
			  $parent_id=$userid_id.",".$parent_id;
		   }
		//   pr($parent_id);
		 $parents_id=explode(',',$parent_id);
		// pr($parents_id);
		// die;
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where_in('customer_id', $parents_id);
		if($this->db->update($tableName, $data)){
		//	echo $this->db->last_query(); die;
               return TRUE;
           }else{
               return FALSE;
           }
		
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	
	//==========start code for delete upload dcoument questionnareis wise==================//
	public function RequestForModification($tableName,$data,$question_id,$service_id,$process_id,$customer_id){
		$this->db->where('questionnaire_id', $question_id);
		$this->db->where('process_id', $process_id);
		$this->db->where('service_id', $service_id);
		$this->db->where('customer_id', $customer_id);
		//$this->db->update($tableName, $data);
		if($this->db->update($tableName, $data)){
			//echo $this->db->last_query();
               return TRUE;
           }else{
               return FALSE;
           }
	
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
	 //==========start code for delete upload dcoument questionnareis wise==================//
	public function delete_roc_aoc_docs($docs_id){
		$this->db->where('id', $docs_id);
		if($this->db->delete('common_uplaod_docs')){
			   return TRUE;
           }else{
               return FALSE;
           }
		
	}
	
    //==========end code for delete upload dcoument questionnareis wise==================//
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
	
	//==========code for get upload evidence =================//
    
    public function testing_start_date($serviceid,$customer_id,$process_id){
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
    //==========end code for upload evidence==================//
	
	
}
