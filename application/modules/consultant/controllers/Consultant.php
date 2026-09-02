<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Consultant extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
		  $this->load->model('Consultant_mod');
		  $this->load->helper('function');
			is_adminprotected();
       
    }
	//Start of function

	// start qsa  viewing process 
    public function consultant_view(){
		$service_id = ID_decode(@$_GET['service_id']);
		$process_id = ID_decode(@$_GET['process_id']);
		// get customer name
		$data['customer_id']=_get_consultant_customer();
		// get questionaries for qa section
		$data['get_questionair'] = $this->Consultant_mod->get_questionair(@$service_id);
		// get process for qa section
		$data['process_id'] = $this->Consultant_mod->get_process_name(@$process_id);
		// get service for qa section
		$data['service_id'] = $this->Consultant_mod->get_service_name(@$service_id);
		// get uplaod document data for qa section
		$data['get_docsdata'] = $this->Consultant_mod->get_customer_data(@$service_id,@$process_id,@$data['get_user']->id);
		$data['start_date'] = $this->Consultant_mod->start_date(@$service_id,@$data['customer_id']->id,@$process_id);
		$data['testing_start_date'] = $this->Consultant_mod->testing_start_date(@$service_id,@$data['customer_id']->id,@$process_id);
		
		//	$data['breadcum'] = array("qsa/dashboard/" => "Home", '' => "Dashboard");
		$data['page'] = 'process/consultant_view';
		$data['title'] = 'Panacea || Consultant View';
		$this->load->view('front_layout', $data); 
		
	}
	// start qsa  viewing process 
	/*End of function*/

	 /*start of function*/
    // start update status by QSA process 
    public function status_change_by_consultant(){
		if(isPostBack()){ // start post back
			// current date 
			$date = date('Y-m-d H:i:s');
			// get value customer id
			$customer_id=_get_consultant_customer();
			
			// get status id 
			$approveid = ID_decode($this->input->post('approveid'));
			// get  quetion id
			$question_id = ID_decode($this->input->post('question_id'));
			// get service id
			$service_id = ID_decode($this->input->post('service_id'));
			// get process id
			$process_id = ID_decode($this->input->post('process_id'));
			// get customer id convert to encoded 
			$user_id=$this->session->userdata();
			$user_id = $user_id['userinfo']->id;
			// table name
			$tableName="uplaod_evidence";
			// update data with column name and value
		if(trim($approveid)=="1"){
			$status=1;
			$first_Status=1;
			$message="Approved";
		}if(trim($approveid)=="2"){
			$message="Disapproved";
			$status=2;
			$first_Status=1;
		}elseif(trim($approveid)=="4"){
			$message="Marked as Incompleted";
			$status=3;
			$first_Status=3;
		}else{
			$first_Status=5;
		}
			$data = array(
			'status'=> $approveid,
			'qsa_id'=>$user_id,
			'status_date'=>$date,
			'first_status'=>$first_Status,
			'all_status'=>$status,
			'all_status_date'=>$date);
			
			$returndata=$this->Consultant_mod->status_change_by_consultant($tableName,$data,$question_id,$service_id,$process_id,$customer_id->id);
			if($returndata){
				set_flashdata('success',''.$message.' has been successfully.');
			}else{
				set_flashdata('error','Something error please try again.');
			}
		
		}// end post back
	} 
	// start update status by QSA process 
	/*End of function*/
	/*start of function*/
    // start update status by QSA process 
    public function Comment_by_consualtant(){
		if(isPostBack()){ // start post back
			$userdata=$this->session->userdata;
			// get date 
			$date=date('Y-m-d');
			// get customer id
			$customer_id=_get_consultant_customer();
			// get quetion id
			$question_id = $this->input->post('question_id');
			// get service id
			$service_id = ID_decode(@$this->input->post('service_id'));
			// get process id
			$process_id = ID_decode(@$this->input->post('process_id'));
			// get commnet
			$comment =$this->input->post('comment');
			// get login user id
			$user_id=$this->session->userdata();
			
			$consultant_id =$user_id['userinfo']->id;
			
			$tableName="all_comments";
			// update data with column name and value
			$data=array(
				'qestion_id'=>$question_id,
				'service_id'=>$service_id,
				'process_id'=>$process_id,
				'parent_id'=>$userdata['userinfo']->parent_id,
				'customer_id'=>$customer_id->id,
				'login_user_id'=>$userdata['userinfo']->id,
				'comments'=>$comment,
				'login_user_date'=>$date
				);		
			
			$Returndata=$this->Consultant_mod->Comment_by_consualtant($tableName,$data);
				if($Returndata){
					set_flashdata('success','Commnet inserted successfully.');
				}else{
					set_flashdata('error','Somethig error Please try again.');
				}
				
	}// end post back
		
	}
	// start update status by QSA process 
	/*End of function*/
    
	
	 // start update status by QSA process 
   public function RequestModificationForAdmin(){
	   if(isPostBack()){ // start post back
		// get date for qsa date 
			$date=date('Y-m-d');
			// get customer id
			$request_id = ID_decode(@$this->input->post('request_id'));
			// get customer id
			$question_id = ID_decode(@$this->input->post('question_id'));
			// get value service id
			$service_id = ID_decode(@$this->input->post('service_id'));
			// get value process id
			$process_id = ID_decode(@$this->input->post('process_id'));
			// get customer id
			$customer_id=_get_consultant_customer();
			// get table name
			$tableName="uplaod_evidence";
			// update data with column name and value
			$data = array(
				'cusaltant_modification'=>$request_id,
				'cunsaltant_date'=> $date,
			);
			$returndata=$this->Consultant_mod->RequestModificationForAdmin($tableName,$data,$question_id,$service_id,$process_id,$customer_id->id);
			if($returndata){
				set_flashdata('success','Request modification for Admin updated successfully.');
			}else{
				set_flashdata('error','Somethig error Please try again.');
			}
		
		
	}
	// start update status by QSA process 
	}// end post back
	
	
	 // start update status by QSA process 
   public function Delete_docs_by_consultant(){
	   if(isPostBack()){ // start post back
	  // get date for qsa date 
			$date=date('Y-m-d');
			// get customer id
			$docs_id = ID_decode(@$this->input->post('docs_id'));
			// get customer id
			$question_id = ID_decode(@$this->input->post('question_id'));
			// get value service id
			$service_id = ID_decode(@$this->input->post('service_id'));
			// get value process id
			$process_id = ID_decode(@$this->input->post('process_id'));
			// get customer id
			$customer_id=_get_consultant_customer();
			// get table name
			$tableName="uplaod_evidence_docs";
			// update data with column name and value
			
			$returndata=$this->Consultant_mod->Delete_docs_by_consultant($tableName,$docs_id,$question_id,$service_id,$process_id,$customer_id->id);
			if($returndata){
				set_flashdata('success','Delete document successfully.');
			}else{
				set_flashdata('error','Somethig error Please try again.');
			}
		
		
		}
	// start update status by QSA process 
	}// end post back
	
	
	
	 // start update status by QSA process 
   public function Delete_uplaod_by_consultant(){
		if(isPostBack()){ // start post back
	   	// get date for qsa date 
			$date=date('Y-m-d');
			// get customer id
			$docs_id = ID_decode(@$this->input->post('docs_id'));
			// get customer id
			$question_id = ID_decode(@$this->input->post('question_id'));
			// get value service id
			$service_id = ID_decode(@$this->input->post('service_id'));
			// get value process id
			$process_id = ID_decode(@$this->input->post('process_id'));
			// get customer id
			$customer_id=_get_consultant_customer();
			// get table name
			$tableName="common_uplaod_docs";
			// update data with column name and value
			$uploadeddata=$this->Consultant_mod->get_process_docs($docs_id,$tableName,$question_id,$service_id,$process_id,$customer_id->id);
				if($uploadeddata){
					$path = FCPATH."uploads/consultant/$uploadeddata->docs";
					if(file_exists($path)){
						unlink($path);
						$returndata=$this->Consultant_mod->Delete_docs_by_consultant($tableName,$docs_id,$question_id,$service_id,$process_id,$customer_id->id);
						if($returndata){
						set_flashdata('success','Uploaded document has been deleted successfully.');
						}
					}else{
						set_flashdata('error','something error please try again.');
					}
					set_flashdata('error','something error please try again.');
				}
			
			
		}
	// start update status by QSA process 
	}// end post back
	
	
  /*start of function*/
	  // start insert for multiple file uploading  in upliad evidence
	 public function multiple_upload_consultant(){
		 if(isPostBack()){
			 // get date for qsa date 
			$date=date('Y-m-d H:i:s');
			//get session data
			$userdata=$this->session->userdata;
			$common_data=array();
			$tmp_name_value=array();
			// start check form check boxes value
			$checkedData=$this->input->post('checked');
			if(!empty($checkedData)){
				// multiple file name of form against questionaries
				$docsname=$_FILES;
				// multiple question id against questionaries
				$questionid=$this->input->post('question_id');
				// multiple service id against questionaries
				$servide_id=$this->input->post('servide_id');
				// multiple comment against questionaries
				$process_id=$this->input->post('process_id');
				// multiple comment against questionaries
				$customer_id=_get_consultant_customer();
				// multiple comment against questionaries
				$comments=$this->input->post('comments');
				foreach($questionid as $questionids){
					$checkboxvalue="";
					if(!empty($checkedData[$questionids[0]][0])){
						if(!empty($comments[$questionids[0]][0])){
							$commentsTableName="all_comments";
								$commentsdata=array(
								'qestion_id'=>$questionids[0],
								'service_id'=>$servide_id,
								'process_id'=>$process_id,
								'parent_id'=>$userdata['userinfo']->parent_id,
								'customer_id'=>$customer_id->id,
								'login_user_id'=>$userdata['userinfo']->id,
								'comments'=>$comments[$questionids[0]][0],
								'login_user_date'=>$date
								);		
								// insert constultan comment
								$this->Consultant_mod->consultant_common_insert($commentsTableName,$commentsdata);
							}
							$checkboxvalue=$checkedData[$questionids[0]][0];
							$uploadeddata=$this->Consultant_mod->get_uploaded_docs($servide_id,$questionids[0],$process_id,$customer_id->id);
							if(!empty($uploadeddata)){
								foreach($docsname as $docsnames){
								   //if(!empty($docsnames['name'][$questionids[0]][0])){
										for($i=0;$i<count($docsnames['name'][$questionids[0]]); $i++){
											$fileName=$docsnames['name'][$questionids[0]][$i];
											$filesName1=explode('.',$fileName);
											$file_ext = substr($fileName, strripos($fileName, '.'));
											if(!empty($file_ext)){
												$tmpName=$docsnames['tmp_name'][$questionids[0]][$i];
												$uploads_dir = './uploads/consultant/';
												$unique_id = mt_rand(1000, 9999);
												$newfilename = ''.$filesName1[0]."-".$unique_id.$file_ext;
												//$newfilename = ''.time().rand() .$file_ext;
												move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
												$consultantdata=array(
												'questionnaire_id'=>$questionids[0],
												'service_id'=>$servide_id,
												'process_id'=>$process_id,
												'customer_id'=>$customer_id->id,
												'user_id'=>$userdata['userinfo']->id,
												'docs'=>$newfilename
											);
											// upload consultant document table name
											$docsTable="common_uplaod_docs";
											// insert document in table										
											$ddata=$this->Consultant_mod->consultant_common_insert($docsTable,$consultantdata);
										}
									   }
									//}else{
									//	set_flashdata('success','Minimum one file is upload in any one question.');
									//			redirect($_SERVER['HTTP_REFERER']);
									//}
								}
							}
						}
					}
				set_flashdata('success','File uploaded successfully.');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				set_flashdata('error','Some problem occurred, please check the checkbox.');
				redirect($_SERVER['HTTP_REFERER']);
			}
			// end check form check boxes value
	}
		
	 }
	  // end insert for multiple file uploading  in upliad evidence
	   //end of function
	
}
/*End of class*/