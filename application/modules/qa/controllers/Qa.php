<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qa extends CI_Controller {
    
  /**
     * Constructor
     */ 
    function __construct() {
        
        parent::__construct();
		  $this->load->model('Qa_mod');
		  $this->load->helper('function');
			is_adminprotected();
       
    }
	//Start of function

	// start qsa  viewing process 
    public function qa_view(){
		$service_id = ID_decode(@$_GET['service_id']);
		$process_id = ID_decode(@$_GET['process_id']);
		$data['get_user']= _get_qa_customer();
		// get questionaries for qa section
		$data['get_questionair'] = $this->Qa_mod->get_questionair(@$service_id);
		// get process for qa section
		$data['get_process_name'] = $this->Qa_mod->get_process_name(@$process_id);
		// get service for qa section
		$data['get_service_name'] = $this->Qa_mod->get_service_name(@$service_id);
		// get uplaod document data for qa section
		$data['get_docsdata'] = $this->Qa_mod->get_customer_data(@$service_id,@$process_id,@$data['get_user']->id);
			// get customer id
		$data['start_date'] = $this->Qa_mod->start_date(@$service_id,@$data['get_user']->id,@$process_id);
		$data['testing_start_date'] = $this->Qa_mod->testing_start_date(@$service_id,@$data['get_user']->id,@$process_id);
		$customer_id= _get_qa_customer();
		$data['Fetchddata']=$this->Qa_mod->get_customer_data($service_id,$process_id,$customer_id->id);
		//	$data['breadcum'] = array("qsa/dashboard/" => "Home", '' => "Dashboard");
		$data['page'] = 'process/qa_view';
		$data['title'] = 'Panacea || Qa View';
		$this->load->view('front_layout', $data); 
		
	}
	// start qsa  viewing process 
	/*End of function*/

	 /*start of function*/
    // start update status by QSA process 
    public function updateStatusBy_Qa(){
		//date 
		$date=date('Y-m-d H:i:s');
		// get value user
	    $user_id['get_user']= _get_qa_customer();
		// session data
		$userdata=$this->session->userdata;
		$approveid = ID_decode(@$this->input->post('approveid'));
		// get  quetion id
		$question_id = ID_decode($this->input->post('question_id'));
		// get service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get process id
		$process_id = ID_decode(@$this->input->post('process_id'));
		// get customer id convert to encoded 
		$user_id = $user_id['get_user']->id;
		// table name
		$tableName="uplaod_evidence";
		// update data with column name and value
		if(trim($approveid) =="1"){
			$status=4;
		}elseif(trim($approveid) =="2"){
			$status=5;
		}elseif(trim($approveid) =="4"){
			$status=6;
		}
		$data = array(
		'qa_status'=> $approveid,
		'qa_id'=>$userdata['userinfo']->id,
		'qa_status_date'=>$date,
		'first_status'=>2,
		'all_status'=>$status,
		'all_status_date'=>$date
		);
		$returndata=$this->Qa_mod->updateStatusBy_Qa(@$tableName,@$data,@$question_id,@$service_id,@$process_id,@$user_id);
		if($returndata){
			set_flashdata('success','Status has been changed successfully.');
		}else{
			set_flashdata('success','Something went wrong.');
		}
		
		
		
	}
	// start update status by QSA process 
	/*End of function*/


	/*start of function*/
    // start update status by QSA process 
    public function updateCommentBy_Qa(){
		$userdata=$this->session->userdata;
		// get date for qsa date 
	 	$date=date('Y-m-d H:i:s');
		// get customer id
	    $customer_id= _get_qa_customer();
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
		$qa_id =$user_id['userinfo']->id;
		
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
	
		$returndata=$this->Qa_mod->insert_common_function($tableName,$data);
			set_flashdata('success','Your comemnt has been inserted successfully.');
		
		
	}
	// start update status by QSA process 
	/*End of function*/

 //==========start code for delete upload dcoument==================//
	
	 public function delete_process_docs_qa(){
	
		 if(isPostBack()){
			// get date for qsa date 
			$docs_id =ID_decode(@$this->input->post('docsid'));
			$returndata=$this->Qa_mod->delete_process_docs_qa($docs_id);
			set_flashdata('success','Delete uploaded document successfully.');
			
		}
		
	 }
	  //==========end code for delete upload dcoument==================//

 // start update status by QSA process 
   public function RequestForModification(){
		$userdata=$this->session->userdata();
		//pr($userdata['userinfo']->email); die;
		// get date for qsa date 
	 	$date=date('Y-m-d');
		// get customer id
	    $request_id = ID_decode($this->input->post('request_id'));
		// get customer id
	    $question_id = ID_decode($this->input->post('question_id'));
		// get value service id
		$service_id = ID_decode($this->input->post('service_id'));
		// get value process id
	    $process_id = ID_decode($this->input->post('process_id'));
		// get login user id
		$user_id=$this->session->userdata();
		// get customer id
		$customer_id =ID_decode($this->input->post('customer_id'));
		// get table name
		$tableName="uplaod_evidence";
		// update data with column name and value
		$data = array(
			'admin_qa'=>0,
			'qa_modification'=>$request_id,
			'qa_date'=> $date,
		);
		
		// mailing function
	           /*sending mail to user's registered email*/
			   /*
                $email_data['to'] 			= panacea@yopmail.com';
				$email_data['from'] 		= $userdata['userinfo']->email;
				$email_data['sender_name'] 	= $userdata['userinfo']->full_name;
				$email_data['subject'] 		= "Panacea:: Query from QA";
				$email_data['message'] 		= array('header' => 'Query from QA!',
					'body' => 'Hello <br/>I have sent the Modification request for Admin<br><br>Thanks,<br/>QA Login');
				_sendMailPhpMailer($email_data);
				
				 set_flashdata('success', 'We have recieved your query. We will get back to you soon!');
                redirect('/contact_us');
          	
	
		*/
		$Returndata=$this->Qa_mod->RequestForModification($tableName,$data,$question_id,$service_id,$process_id,$customer_id);
		set_flashdata('success','Modification Request has been sent successfully.');
		
	}
	// start update status by QSA process 
	// start all approve, disapprove, mark incomelete and in progress update in uplaod_evidence  
   public function select_multiple_change_status(){
	  	 if(isPostBack()){
			 // get date for qsa date 
			$date=date('Y-m-d H:i:s');
			//get session data
			$userdata=$this->session->userdata;
			$evidencedata=array();
			$tmp_name_value=array();
			// start check form check boxes value
			$checkedData=$this->input->post('checked');
			if(!empty($checkedData)){
				$Approved_id="";
				// multiple file name of form against questionaries
				$docsname=$_FILES;
				// multiple question id against questionaries
				$questionid=$this->input->post('question_id');
				// multiple service id against questionaries
				$servide_id=$this->input->post('servide_id');
				// multiple comment against questionaries
				$comments=$this->input->post('comments');
				// multiple process id against questionaries 
				$process_id=$this->input->post('process_id');
				// get customer id
				$customer_id= _get_qa_customer();
			     // for comments
				$comments=$this->input->post('comments');
					if(!empty(ID_decode($this->input->post('fileupload')))){// start multiple file uploading in qa
						if(!empty($_FILES)){
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
										
										
										$this->Qa_mod->insert_common_function($commentsTableName,$commentsdata);
									}
									$checkboxvalue=$checkedData[$questionids[0]][0];
									$uploadeddata=$this->Qa_mod->get_uploaded_docs($servide_id,$questionids[0],$process_id,$customer_id->id);
									if(!empty($uploadeddata)){
										foreach($docsname as $docsnames){
											//if(!empty($docsnames['name'][$questionids[0]][0])){
												for($i=0;$i<count($docsnames['name'][$questionids[0]]); $i++){
													$fileName=$docsnames['name'][$questionids[0]][$i];
													$filesName1=explode('.',$fileName);
													$file_ext = substr($fileName, strripos($fileName, '.'));
													if(!empty($file_ext)){
														$tmpName=$docsnames['tmp_name'][$questionids[0]][$i];
														$uploads_dir = './uploads/qa/';
														$unique_id = mt_rand(1000, 9999);
														$newfilename = ''.$filesName1[0]."-".$unique_id.$file_ext;
														//$newfilename = ''.time().rand() .$file_ext;
														move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
														$ddata=$this->Qa_mod->insert_uploaded_docs($questionids[0],$newfilename,$servide_id,$process_id,$customer_id->id,$userdata['userinfo']->parent_id);
													}
											   }
											//}else{
											//	set_flashdata('success','Minimum one file is upload in any one question.');
											//	redirect($_SERVER['HTTP_REFERER']);
												
											//}
										}
									}
								}
							}
						set_flashdata('success','Document has been uploaded successfully.');
						redirect($_SERVER['HTTP_REFERER']);
						}else{
							set_flashdata('error','Please select the files.');
							redirect($_SERVER['HTTP_REFERER']);
						}
						
						// end multiple file uploading in qa
						
					}else{
					
					if(ID_decode($this->input->post('Approved'))){
						$Approved_id=ID_decode($this->input->post('Approved'));
					}elseif(ID_decode($this->input->post('Disapprove'))){
						$Approved_id=ID_decode($this->input->post('Disapprove'));
					}elseif(ID_decode($this->input->post('Markincomplete'))){
						$Approved_id=ID_decode($this->input->post('Markincomplete'));
					}elseif(ID_decode($this->input->post('inprogress'))){
						$Approved_id=ID_decode($this->input->post('inprogress'));
					}
					
					
						foreach($checkedData as $key => $value){
							$evidencedata[$key] = array_merge($questionid[$key], $checkedData[$key]);
						}
						foreach($evidencedata as $evidencedatas){
								$fetchData=$this->Qa_mod->get_data($evidencedatas[0],$process_id,$servide_id,$customer_id->id);
								if(!empty($fetchData)){
									
									if(trim($Approved_id) =="1"){
										$status=4;
									}elseif(trim($Approved_id) =="2"){
										$status=5;
									}elseif(trim($Approved_id) =="4"){
										$status=6;
									}
									$tableName='uplaod_evidence';
									$data = array(
									'qa_status'=> $Approved_id,
									'qa_id'=>$userdata['userinfo']->id,
									'qa_status_date'=>$date,
									'first_status'=>2,
									'all_status'=>$status,
									'all_status_date'=>$date
									);
									/*$data=array(
									'qa_status'=>$Approved_id,
									'qa_status_date'=>$date,
									'qa_id'=>$userdata['userinfo']->id
									);
									*/
									$updateData=$this->Qa_mod->Common_Update_qa($tableName,$data,$evidencedatas[0],$servide_id,$process_id,$customer_id->id);
								}
							}
						set_flashdata('success','All question approve successfully.');
						redirect($_SERVER['HTTP_REFERER']);
					}
					}else{
					  set_flashdata('error','Some problem occurred, please check the checkbox.');
						redirect($_SERVER['HTTP_REFERER']);
					}
			// end check form check boxes value
		}
		
		
	}
	// end all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
	
    
}
/*End of class*/