<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Process extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
		$this->load->library('upload');
        $this->load->model('Process_mod');
        $this->load->model('qa/Qa_mod');
        $this->load->model('admin/Customer_mod');
			is_adminprotected();
    }
	
	/**
     * End of function
     */
	 
	 /**
     * index
     *
     * This function to render dashboard page initially
     * 
     * @access	public
     * @return  html
     */
	//start of function
     public function index() {
		// get process id for complience and testing
		$id = ID_decode($_GET['process_id']);
		$this->session->set_userdata('process_id',$id);
		$data['process_name'] = $this->Process_mod->get_process_name($id);
               
		// get complience data
		$data['process_details'] = $this->Process_mod->get_process_details($id);
                // get testing data
		$data['testing_details'] = $this->Process_mod->get_testing_details($id);
		//pr($data['process_details']);die;
		$data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
		$data['page'] = 'process/customer_process_details';
		$data['title'] = 'Panacea || Dashboard';
		$this->load->view('layout', $data);
     }
	 //end of function
	 
    //start of function/
    // start get for servide details
     public function service_details(){
         $id = ID_decode($_GET['id']);
         $data['service_name'] = $this->Process_mod->get_service_name($id);
		 $data['testing_service_name'] = $this->Process_mod->get_testing_service_name($id);
		 $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
         $data['page'] = 'process/service_details';
         $data['title'] = 'Panacea || Dashboard';
         $this->load->view('layout', $data);
     }
	 // end for servide details
	  //end of function
	  
		//start of function/
	   // start get for upload evidence data
     public function upload_evidences(){
		 $userdata=$this->session->userdata;
         $id = ID_decode($_GET['id']);
         $data['get_questionair'] = $this->Process_mod->get_questionair($id);
		 $data['start_date'] = $this->Qa_mod->start_date($id,$userdata['userinfo']->id,$userdata['process_id']);
         //pr($data['get_questionair']);die;
		 $data['testing_start_date'] = $this->Qa_mod->testing_start_date(@$id,@$userdata['userinfo']->id,@$userdata['process_id']);
         $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
         $data['page'] = 'process/upload_evidences';
         $data['title'] = 'Panacea || Dashboard';
         $this->load->view('layout', $data);
     }
      // end get for upload evidence data
	   //end of function
	 
	  /*start of function*/
	  // start insert for multiple file uploading  in upliad evidence
	 public function multiple_upload_evidence(){
		 if(isPostBack()){
			 // get date for qsa date 
			$date=date('Y-m-d H:i:s');
			//get session data
			$userdata=$this->session->userdata;
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
				$comments=$this->input->post('comments');
				foreach($questionid as $questionids){
					$checkboxvalue="";
					if(!empty($checkedData[$questionids[0]][0])){
						if(!empty($comments[$questionids[0]][0])){
							$commntsTableName="all_comments";
								$commentsdata=array(
								'qestion_id'=>$questionids[0],
								'service_id'=>$servide_id,
								'process_id'=>$userdata['process_id'],
								'parent_id'=>$userdata['userinfo']->parent_id,
								'customer_id'=>$userdata['userinfo']->id,
								'login_user_id'=>$userdata['userinfo']->id,
								'comments'=>$comments[$questionids[0]][0],
								'login_user_date'=>$date
								);
								$this->Process_mod->insert_common_function($commntsTableName,$commentsdata);
						}
						$checkboxvalue=$checkedData[$questionids[0]][0];
						$uploadeddata=$this->Process_mod->get_uploaded_docs($servide_id,$questionids[0],$userdata['process_id']);
						if(empty($uploadeddata)){
							foreach($docsname as $docsnames){
							   if(!empty($docsnames['name'][$questionids[0]][0])){
									for($i=0;$i<count($docsnames['name'][$questionids[0]]); $i++){
									$uploadeddata=$this->Process_mod->get_uploaded_docs($servide_id,$questionids[0],$userdata['process_id']);
									if(empty($uploadeddata)){
												$Questiondata=array(
											'questionnaire_id'=>$questionids[0],
											'service_id'=>$servide_id,
											'customer_id'=>$userdata['userinfo']->id,
											'process_id'=>$userdata['process_id'],
											'parent_id'=>$userdata['userinfo']->parent_id,
											'quest_checked_val'=>$checkboxvalue
										);
											$questinableTable="uplaod_evidence";
											$this->Process_mod->insert_common_function($questinableTable,$Questiondata);
										}
										$fileName=$docsnames['name'][$questionids[0]][$i];
										$filesName1=explode('.',$fileName);
										$file_ext = substr($fileName, strripos($fileName, '.'));
										if(!empty($file_ext)){
											$tmpName=$docsnames['tmp_name'][$questionids[0]][$i];
											$uploads_dir = './uploads/evidence/';
											$unique_id = mt_rand(1000, 9999);
											$newfilename = ''.$filesName1[0]."-".$unique_id.$file_ext;
											//$newfilename = ''.time().rand() .$file_ext;
											move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
											$ddata=$this->Process_mod->insert_uploaded_docs($questionids[0],$newfilename,$servide_id,$userdata['process_id'],$userdata['userinfo']->id,$userdata['userinfo']->parent_id);
										}
									}
								}
						}
						}else{
							foreach($docsname as $docsnames){
							   //if(!empty($docsnames['name'][$questionids[0]][0])){
									for($i=0;$i<count($docsnames['name'][$questionids[0]]); $i++){
										$fileName=$docsnames['name'][$questionids[0]][$i];
										$file_ext = substr($fileName, strripos($fileName, '.'));
										if(!empty($file_ext)){
											$tmpName=$docsnames['tmp_name'][$questionids[0]][$i];
											$uploads_dir = './uploads/evidence/';
											$newfilename = ''.time().rand() .$file_ext;
											move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
											$ddata=$this->Process_mod->insert_uploaded_docs($questionids[0],$newfilename,$servide_id,$userdata['process_id'],$userdata['userinfo']->id,$userdata['userinfo']->parent_id);
										}
									}
								//}else{
								//	set_flashdata('success','Minimum one file is upload in any one question.');
								//				redirect($_SERVER['HTTP_REFERER']);
									
								///}
						}
					}
				}
			}
				set_flashdata('success','Document has been uploaded successfully.');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				set_flashdata('error', 'Please checck the check box!');
				redirect($_SERVER['HTTP_REFERER']);
			}
			// end check form check boxes value
	}
		
}
	  // end insert for multiple file uploading  in upliad evidence
	   //end of function
	   
	   
	  //start of function
	   // start delete file  for customer docsin view evidence and upload evidence
	 public function delete_cunstomer_docs(){
		 if(isPostBack){
			$id =$this->input->post();
			$this->Process_mod->delete_process_docs($id['docs_id']);
			set_flashdata('success','Delete uploaded document successfully.');
			
		}
		
	 }
	  // end delete file  for customer docsin view evidence and upload evidence
	   //end of function
	   
	   
	   // start update comment  by QSA process 
    public function updateCommentBy_process(){
		//get session data
			$userdata=$this->session->userdata;
		// get date for qsa date 
	 	$date=date('Y-m-d H:i:s');
		// get customer id
	    $question_id = @$this->input->post('question_id');
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
	    $process_id = ID_decode(@$this->input->post('process_id'));
		// get commnet
		$comment =$this->input->post('comment');
		// get login user id
		$user_id=$this->session->userdata();
		// get customer id
		$customer_id =$user_id['userinfo']->id;
		// get table name
		$tableName="all_comments";
			$data=array(
				'qestion_id'=>$question_id,
				'service_id'=>$service_id,
				'process_id'=>$process_id,
				'parent_id'=>$userdata['userinfo']->parent_id,
				'customer_id'=>$userdata['userinfo']->id,
				'login_user_id'=>$userdata['userinfo']->id,
				'comments'=>$comment,
				'login_user_date'=>$date
				);
		
		$this->Process_mod->insert_common_function($tableName,$data);
		
	}
	// end update comment  by QSA process 
	
	// start of function
	// start view evidence
	 public function view_evidences(){
		 
		 $userData=$this->session->userdata();
		 $id = ID_decode($_GET['id']);
		 $data['get_questionair'] = $this->Process_mod->get_questionair($id);
         $data['docs_data'] = $this->Process_mod->get_docsdata($id);
		 $data['start_date'] = $this->Qa_mod->start_date($id,$userData['userinfo']->id,$userData['process_id']);
		 $data['testing_start_date'] = $this->Qa_mod->testing_start_date(@$id,@$userData['userinfo']->id,@$userData['process_id']);
         $data['breadcum'] = array("customer/dashboard/" => "Home", '' => "Dashboard");
         $data['page'] = 'process/view_evidence';
         $data['title'] = 'Panacea || Dashboard';
         $this->load->view('layout', $data);
     }
	 // start view evidence
    //End of function
	
	
	 /*start of function*/
    // start update status by QSA process 
   public function RequestForModification(){
		
		// get date for qsa date 
	 	$date=date('Y-m-d');
		// get customer id
	    $question_id = ID_decode(@$this->input->post('question_id'));
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
	    $process_id = ID_decode(@$this->input->post('process_id'));
		// get login user id
		$user_id=$this->session->userdata();
		// get customer id
		$customer_id =ID_encode($user_id['userinfo']->id);
		// get table name
		$tableName="uplaod_evidence";
		// update data with column name and value
		$data = array(
			'cus_modification'=>1,
			'cus_modification_date'=> $date,
		);
		$returnData=$this->Process_mod->RequestForModification($tableName,$data,$question_id,$service_id,$process_id,$customer_id);
		if($returnData){
			set_flashdata('error', 'Request modification has been sent successfully!');
				redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_flashdata('error', 'Something went wrong!');
				redirect($_SERVER['HTTP_REFERER']);
		}
		
		
	}
	// start update status by QSA process 
	/*End of function*/
	
	 /*start of function*/
    // start update status by QSA process 
   public function RequestForstatus(){
	  
		// get date for qsa date 
	 	$date=date('Y-m-d');
		// get status id
	    $status_id = ID_decode(@$this->input->post('status_id'));
		// get customer id
	    $question_id = ID_decode(@$this->input->post('question_id'));
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
	    $process_id = ID_decode(@$this->input->post('process_id'));
		// get login user id
		$customer_id =ID_decode(@$this->input->post('customer_id'));;
		// get table name
		$tableName="uplaod_evidence";
		// update data with column name and value
		$data = array(
			'customer_status'=>$status_id,
			'customer_date'=> $date,
		);
		$returnData=$this->Process_mod->RequestForStatus($tableName,$data,$question_id,$service_id,$process_id,$customer_id);
		if($returnData){
			set_flashdata('error', 'Status has been changed successfully!');
				redirect($_SERVER['HTTP_REFERER']);
		}else{
			set_flashdata('error', 'Something went wrong!');
				redirect($_SERVER['HTTP_REFERER']);
		}
		
		
	}
	// start update status by QSA process 
	/*End of function*/
	
	//==========start code for delete upload dcoument==================//
	
	 public function delete_process_docs(){
			if(isPostBack()){
			$docs_id =ID_decode(@$this->input->post('docs_id'));
			$uploadeddata=$this->Process_mod->get_process_docs($docs_id);
				if($uploadeddata){
					$returndata=$this->Process_mod->delete_process_docs($docs_id);
					$path = FCPATH."uploads/evidence/$uploadeddata->docs";
					if(file_exists($path)){
						unlink($path);
					}else{
					set_flashdata('error', 'Something went wrong!');
					}
					set_flashdata('success','Uploaded document has been deleted successfully.');
				}else{
					set_flashdata('error', 'Something went wrong!');
				}	
		}
		
}
	  //==========end code for delete upload dcoument==================//
	 
	
	
}
/*End of class*/