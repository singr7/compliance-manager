<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qsa extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
		  $this->load->model('Qsa_mod');
		  $this->load->helper('function');
        is_adminprotected();
       
    }
	
	
	 /*End of function*/
    // start qsa  viewing process 
    public function qsa_view(){
		$service_id = ID_decode(@$_GET['service_id']);
		$process_id = ID_decode(@$_GET['process_id']);
		$data['get_user']= _get_user();
		$data['get_questionair'] = $this->Qsa_mod->get_questionair(@$service_id);
		$data['get_process_name'] = $this->Qsa_mod->get_process_name(@$process_id);
		$data['get_service_name'] = $this->Qsa_mod->get_service_name(@$service_id);
		$data['get_docsdata'] = $this->Qsa_mod->get_docsdata(@$service_id,@$process_id,@$data['get_user']->id);
		$data['start_date'] = $this->Qsa_mod->start_date(@$service_id,@$data['get_user']->id,@$process_id);
		$data['testing_start_date'] = $this->Qsa_mod->testing_start_date(@$service_id,@$data['get_user']->id,@$process_id);
		//	$data['breadcum'] = array("qsa/dashboard/" => "Home", '' => "Dashboard");
		$data['page'] = 'process/qsa_view';
		$data['title'] = 'Panacea || Qsa View';
		$this->load->view('front_layout', $data); 
		
	}
	// start qsa  viewing process 
	/*End of function*/

	 /*start of function*/
    // start update status by QSA process 
    public function updateStatusBy_Qsa(){
		// get date for qsa date 
	 	$date=date('Y-m-d H:i:s');
		// get value user
	    $user_id['get_user']= _get_user();
		// session data
		$userdata=$this->session->userdata;
		$statusid = ID_decode(@$this->input->post('statusid'));
		
	// get value quetion id
		$question_id = ID_decode($this->input->post('question_id'));
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
		$process_id = ID_decode(@$this->input->post('process_id'));
		// get value user id convert to encoded 
		$user_id = $user_id['get_user']->id;
		// table name
		$status="";
		$first_Status="";
		$tableName="uplaod_evidence";
		$message="";
		if(trim($statusid)=="1"){
			$status=1;
			$first_Status=1;
			$message="Approved";
		}if(trim($statusid)=="2"){
			$message="Disapproved";
			$status=2;
			$first_Status=1;
		}elseif(trim($statusid)=="4"){
			$message="Marked as Incompleted";
			$status=3;
			$first_Status=3;
		}else{
			$first_Status=5;
		}
		// update data with column name and value
		$data = array(
			'status'=> $statusid,
			'qsa_id'=>$userdata['userinfo']->id,
			'status_date'=>$date,
			'first_status'=>$first_Status,
			'all_status'=>$status,
			'all_status_date'=>$date);
		$this->Qsa_mod->updateStatusBy_Qsa(@$tableName,@$data,@$question_id,@$service_id,@$process_id,@$user_id);
		set_flashdata('success','Question has been '.$message.'  successfully.');
		
	}
	// start update status by QSA process 
	/*End of function*/


	/*start of function*/
    // start update status by QSA process 
    public function updateCommentBy_Qsa(){
		
		// get date for qsa date 
	 	$date=date('Y-m-d H:i:s');
		// get customer id
	    $customer_id= _get_user();
		// get value quetion id
		$question_id = @$this->input->post('question_id');
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
		$process_id = ID_decode(@$this->input->post('process_id'));
		// get commnet
		$comment =$this->input->post('comment');
		// get login user id
		$userdata=$this->session->userdata;
		// table name
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
		$this->Qsa_mod->insert_common_function(@$tableName,@$data);
		set_flashdata('success','Comment has been inserted successfully.');
		
		
	}
	// start update status by QSA process 
	/*End of function*/

 //==========start code for delete upload dcoument==================//
	
	 public function delete_qsa_docs(){
		 
		 if(isPostBack()){
			 $docs_id =ID_decode($this->input->post('docs_id'));
			// get docs id
			$returndata=$this->Qsa_mod->delete_process_docs($docs_id);
			if($returndata){
				set_flashdata('success','Delete uploaded document successfully.');
			}else{
				set_flashdata('error','something error please try again.');
			}
			
		}
	}
	  //==========end code for delete upload dcoument==================//
	/*start of function*/
    // start update status by QSA process 
   public function RequestForModification(){
		// get date for qsa date 
	 	$date=date('Y-m-d H:i:s');
		// get customer id
	    $request_id = ID_decode(@$this->input->post('request_id'));
		// get value service id
		 $question_id = ID_decode(@$this->input->post('question_id'));
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
	    $process_id = ID_decode(@$this->input->post('process_id'));
		// get customer id
		$customer_id= ID_decode(@$this->input->post('customer_id'));
		
		// get table name
		$tableName="uplaod_evidence";
		// update data with column name and value
		$data = array(
			'qsa_modification'=>$request_id,
			'qsa_date'=> $date,
		);
		$returnData=$this->Qsa_mod->RequestForModification($tableName,$data,$question_id,$service_id,$process_id,$customer_id);
		if($returnData){
			set_flashdata('success','Modification request for Admin has been sent successfully.');
		}else{
			set_flashdata('success','something error please try again.');
		}
		
	}
	// start update status by QSA process 
	/*End of function*/
	/*start of function*/
	  // start insert for multiple file uploading  in upliad evidence
	 public function multiple_upload_qsa(){
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
				//pr($docsname);die;
				// multiple question id against questionaries
				$questionid=$this->input->post('question_id');
				//pr($questionid);
				// multiple service id against questionaries
				$servide_id=$this->input->post('servide_id');
				// multiple comment against questionaries
				$process_id=$this->input->post('process_id');
				// multiple comment against questionaries
				$customer_id= _get_user();
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
								$this->Qsa_mod->insert_common_function($commentsTableName,$commentsdata);
						}
						$checkboxvalue=$checkedData[$questionids[0]][0];
						$uploadeddata=$this->Qsa_mod->get_fetch_evidence_data($servide_id,$questionids[0],$process_id,$customer_id->id);
						if(!empty($uploadeddata)){
							foreach($docsname as $docsnames){
							 	for($i=0;$i<count($docsnames['name'][$questionids[0]]); $i++){
										$fileName=$docsnames['name'][$questionids[0]][$i];
										$filesName1=explode('.',$fileName);
										$file_ext = substr($fileName, strripos($fileName, '.'));
										if(!empty($file_ext)){
											$tmpName=$docsnames['tmp_name'][$questionids[0]][$i];
											$uploads_dir = './uploads/qsa/';
											$unique_id = mt_rand(1000, 9999);
											$newfilename = ''.$filesName1[0]."-".$unique_id.$file_ext;
											//$newfilename = ''.time().rand() .$file_ext;
											move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
											$ddata=$this->Qsa_mod->insert_uploaded_docs($questionids[0],$newfilename,$servide_id,$process_id,$customer_id->id,$userdata['userinfo']->parent_id);
										}
									}
								
						}
						}
				}
			}
				set_flashdata('success','Document has been uploaded successfully.');
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