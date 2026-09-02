<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Testing extends CI_Controller {
    
    /*
     * Constructor
    */
    
    function __construct() {
        parent::__construct();
		is_adminprotected();
        $this->load->model('Testing_mod');
        $this->load->model('Questionnaire_mod');
        $this->load->model('Compliances_mod');
    }
    
    //=======code for get testing project list=============//
    public function testing_list() {
        $id = ID_decode($_GET['id']);
        $data['project_list'] = $this->Testing_mod->project_list($id);
        $data['testing_name'] = $this->Testing_mod->testing_name($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Testing");
        $data['page'] = 'testing/testing_list';
        $data['title'] = 'Panacea || Testing';
        $this->load->view('layout', $data);
    }
    //=======end code for get testing project list=============//
    
    //=======code for add testing project ====================//
    
    public function add_testing_project(){
        $id = ID_decode($_GET['id']);
		  if (isPostBack()) {
            $this->form_validation->set_rules('customer_id', 'Select Customer', 'required');
            $this->form_validation->set_rules('process_id', 'Select Process', 'required');
            $this->form_validation->set_rules('qsa_id', 'Select QSA', 'required');
            $this->form_validation->set_rules('consultant_id', 'Select Consultants', 'required');
            $this->form_validation->set_rules('qa_id', 'Select QA', 'required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $process = $this->input->post('process_id');
                $date = $this->input->post('start_date');
                $customerId = $this->input->post('customer_id');
                $check_data = $this->Testing_mod->check_preexistance_add($id, $process, $customerId);
			    if ($check_data) {
                    set_flashdata('error', 'This process is already used in this testing.');
                    redirect($_SERVER['HTTP_REFERER']);
                }
                $result = $this->Testing_mod->add_testing_project();
                $this->session->set_flashdata("success", "Testing project added successfully");
                redirect("/admin/testing/testing_list?id=" . $_GET['id']);
            }
        }
        $data['service_name'] = $this->Questionnaire_mod->service_name($id);
        $data['customer_list'] = $this->Compliances_mod->customer_list();
        $data['qsa_list'] = $this->Compliances_mod->qsa_list();
        $data['qa_list'] = $this->Compliances_mod->qa_list();
        $data['testing_name'] = $this->Testing_mod->testing_name($id);
        $data['consultant_list'] = $this->Compliances_mod->consulants_list();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Testing");
        $data['page'] = 'testing/add_testing_project';
        $data['title'] = 'Panacea || Testing';
        $this->load->view('layout', $data);
    }


    //=======end code for add testing project=================//
    
//=======code for edit compliance project================//
    public function edit_testing_project() {
        $id = ID_decode($_GET['id']);
        $serviceId = ID_decode($_GET['testing_id']);
        if (isPostBack()) {
            $this->form_validation->set_rules('customer_id', 'Select Customer', 'required');
            $this->form_validation->set_rules('process_id', 'Select Process', 'required');
            $this->form_validation->set_rules('qsa_id', 'Select QSA', 'required');
            $this->form_validation->set_rules('consultant_id', 'Select Consultants', 'required');
            $this->form_validation->set_rules('qa_id', 'Select QA', 'required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');

            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $process = $this->input->post('process_id');
                $date = $this->input->post('start_date');
                $customerId = $this->input->post('customer_id');
                $check_data = $this->Testing_mod->check_preexistance($id, $serviceId, $process, $date, $customerId);
                if ($check_data) {
                    set_flashdata('error', 'This process is already used in this testing.');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $result = $this->Testing_mod->edit_project($id);
                    $this->session->set_flashdata("success", "Testing project updated successfully");
                    //redirect("/admin/testing/testing_list?id=" . $_GET['testing_id']);
                }
            }
        }
        $data['project_details'] = $this->Testing_mod->project_details($id);
        //pr($data['project_details'] );die;
        $data['service_name'] = $this->Questionnaire_mod->service_name($serviceId);
        $data['customer_list'] = $this->Compliances_mod->customer_list();
        $data['qsa_list'] = $this->Compliances_mod->qsa_list();
        $data['qa_list'] = $this->Compliances_mod->qa_list();
        $data['consultant_list'] = $this->Compliances_mod->consulants_list();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Testing Project");
        $data['page'] = 'testing/edit_testing_project';
        $data['title'] = 'Panacea || Testing';
        $this->load->view('layout', $data);
    }
//=======end code for edit compliance project===============//

//=======code for delete compliance project=================//
    public function delete() {
        $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (!empty($decode_id)) {
            $update = $this->Compliances_mod->deleteCompliances($decode_id);
            set_flashdata('success', 'Compliance has been deleted!');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            set_flashdata('error', 'Something went wrong!');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

//======end code for delete compliance project================//
   
//========code for view compliance =====================//
    public function view_testing_project() {
		$serviceid = ID_decode($this->input->get('testing_id'));
		$customer_id = ID_decode($this->input->get('customer_id'));
		$process_id = ID_decode($this->input->get('process_id'));
		$data['testing_name'] = $this->Questionnaire_mod->testing_name($serviceid);
		$data['questionair'] = $this->Questionnaire_mod->questionair($serviceid);
		$data['start_date'] = $this->Testing_mod->start_date($serviceid,$customer_id,$process_id);
		$data['rot'] = $this->Compliances_mod->get_data_rot($serviceid,$process_id,$customer_id);
		$data['aot'] = $this->Compliances_mod->get_data_aot($serviceid,$process_id,$customer_id);
		$data['breadcum'] = array("admin/dashboard/" => "Home", '' => "View Testing");
        $data['page'] = 'testing/view_testing_project';
        $data['title'] = 'Panacea || Testing';
	    $this->load->view('layout', $data);
    }
//=======end code for view compliance===================//

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
				// multiple question id against questionaries
				$questionid=$this->input->post('question_id');
				// multiple process id against questionaries 
				$process_id=$this->input->post('process_id');
				// multiple service id against questionaries
				$servide_id=$this->input->post('servide_id');
				// multiple cutomer id against questionaries
				 $customer_id=$this->input->post('customer_id');
				 
				if(!empty(ID_decode($this->input->post('Approved')))){
					$Approved_id=ID_decode($this->input->post('Approved'));
				}elseif(!empty(ID_decode($this->input->post('Disapprove')))){
					$Approved_id=ID_decode($this->input->post('Disapprove'));
				}elseif(!empty(ID_decode($this->input->post('Markincomplete')))){
					$Approved_id=ID_decode($this->input->post('Markincomplete'));
				}elseif(!empty(ID_decode($this->input->post('inprogress')))){
					$Approved_id=ID_decode($this->input->post('inprogress'));
				}elseif(!empty(ID_decode($this->input->post('Approved')))){
					$Approved_id=ID_decode($this->input->post('inprogress'));
				}
					foreach($checkedData as $key => $value){
						$evidencedata[$key] = array_merge($questionid[$key], $checkedData[$key]);
					}
					foreach($evidencedata as $evidencedatas){
						$fetchData=$this->Compliances_mod->get_data($evidencedatas[0],$process_id,$servide_id,$customer_id);
						if(!empty($fetchData)){
							$tableName='uplaod_evidence';
							$data=array(
							'admin_status'=>$Approved_id,
							'admin_status_date'=>$date,
							);
							$updateData=$this->Compliances_mod->Common_Update_Compliances($tableName,$data,$evidencedatas[0],$servide_id,$process_id,$customer_id);
						}
					}
						set_flashdata('success','Selected  question has been approve successfully.');
						redirect($_SERVER['HTTP_REFERER']);
				}else{
				  set_flashdata('error','Some problem occurred, please check the checkbox.');
					redirect($_SERVER['HTTP_REFERER']);
				}
			// end check form check boxes value
		}
		
		
	}
	// end all approve, disapprove, mark incomelete and in progress update in uplaod_evidence 
	
	//========start code for reply to against modificatio of qa =====================//
    public function accepttoqa() {
		// get quetion id
		$question_id = ID_decode($this->input->post('question_id'));
		// get service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get process id
		$process_id = ID_decode(@$this->input->post('process_id'));
		// get customer id
		$customer_id = ID_decode($this->input->post('customer_id'));
		// get login user id
		$accepted_id = ID_decode($this->input->post('accepted_id'));
		// update admin accept to cutomer 
		$returnData=$this->Compliances_mod->accepttoqa($accepted_id,$question_id,$service_id,$process_id,$customer_id);
		if($returnData){
			set_flashdata('success','Modification request accepted successfully.');
		}else{
			set_flashdata('error','Some problem occurred, please check the checkbox.');
		}
    }
//=======end code for reply to against modification of qa===================//

// start  file uploading for Attestations of Complience(AOC)
	public function FileUploadAOC(){
			if(isPostBack()){
				if(!empty($_FILES)){
					$userdata=$this->session->userdata();
					// get service id
					$servide_id=ID_decode($this->input->post('service_id'));
					// get customer_id
					$customer_id=ID_decode($this->input->post('customer_id'));
					// get process_id
				    $process_id=ID_decode($this->input->post('process_id'));
					// get date 
					$GetData=$this->Compliances_mod->get_data_aoc($servide_id,$process_id,$customer_id);
					if(empty($GetData)){
						$date=date('Y-m-d');
						$year=date('Y');
						$filename=$_FILES['file']['name'];
						$filesName=explode('.',$filename);
						$file_ext = substr($filename, strripos($filename, '.'));
							if(!empty($file_ext)){
								$tmpName=$_FILES['file']['tmp_name'];
								$uploads_dir = './uploads/report/';
								$pin = mt_rand(1000, 9999);
								$newfilename = ''.$filesName[0]."-".$pin.$file_ext;
								move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
								$commntsTableName="report_aoc_roc";
									$reportsdata=array(
									'service_id'=>$servide_id,
									'process_id'=>$process_id,
									'customer_id'=>$customer_id,
									'user_id'=>$userdata['userinfo']->id,
									'report_of'=>"AOT",
									'report_docs'=>$newfilename,
									'date'=>$date,
									'year'=>$year);
									$returnData=$this->Compliances_mod->insert_common_function($commntsTableName,$reportsdata);
									if($returnData){
										set_flashdata('success','Attestation of Testing(AOT) has been uploaded successfully.');
									}else{
										set_flashdata('error','Attestation of Testing(AOT) has been not  uploaded please try again.');
									}
							}
					}else{
						set_flashdata('success','Attestation of Testing(AOT) has been already uploaded.');
						
					}
				}
			}
		}
		
	// end  file uploading for Attestations of Complience(AOC)
	
	// start  file uploading for Attestations of Complience(AOC)
		public function FileUploadROC(){
			if(isPostBack()){
				if(!empty($_FILES)){
					$userdata=$this->session->userdata();
					// get service id
					$servide_id=ID_decode($this->input->post('service_id'));
					// get customer_id
					$customer_id=ID_decode($this->input->post('customer_id'));
					// get process_id
				    $process_id=ID_decode($this->input->post('process_id'));
					// get date 
					$GetData=$this->Compliances_mod->get_data_roc($servide_id,$process_id,$customer_id);
					if(empty($GetData)){
						$date=date('Y-m-d');
						$year=date('Y');
						$filename=$_FILES['file']['name'];
						$filesName=explode('.',$filename);
						$file_ext = substr($filename, strripos($filename, '.'));
							if(!empty($file_ext)){
								$tmpName=$_FILES['file']['tmp_name'];
								$uploads_dir = './uploads/report/';
								$pin = mt_rand(1000, 9999);
								$newfilename = ''.$filesName[0]."-".$pin.$file_ext;
								move_uploaded_file($tmpName, "$uploads_dir/$newfilename");
								$commntsTableName="report_aoc_roc";
									$reportsdata=array(
									'service_id'=>$servide_id,
									'process_id'=>$process_id,
									'customer_id'=>$customer_id,
									'user_id'=>$userdata['userinfo']->id,
									'report_of'=>"ROT",
									'report_docs'=>$newfilename,
									'date'=>$date,
									'year'=>$year);
									$returnData=$this->Compliances_mod->insert_common_function($commntsTableName,$reportsdata);
									if($returnData){
										set_flashdata('success','Report of Testing(ROT) has been uploaded successfully.');
									}else{
										set_flashdata('error','Something went wrong.');
									}
							}
					}else{
						set_flashdata('success','Report of Testing(ROT) has been already uploaded.');
						
					}
				}
			}
		}
	// end  file uploading for Attestations of Complience(AOC)
	
	 // start end date function from admin
	public function EndDateformAdmin(){
		 if(isPostBack()){
			
			//get date
			$date=$this->input->post('date');
			//get project id
			$project_id = ID_decode(@$this->input->post('project_id'));
			// get service id
			$service_id = ID_decode(@$this->input->post('service_id'));
			
			// get process id
			$process_id = ID_decode(@$this->input->post('process_id'));
			// get customer id
			$customer_id = ID_decode($this->input->post('customer_id'));
			// get login user id
			$check = $this->input->post('checkedval');
			
			if(!empty($date)){
				$GetData=$this->Testing_mod->get_data_testing_project($service_id,$process_id,$customer_id);
					if(!empty($GetData)){
						$tableName='testing_project';
						$data=array('end_date'=>$date);
						$updateData=$this->Testing_mod->Common_Update_End_date($tableName,$data,$service_id,$process_id,$customer_id,$project_id);
						set_flashdata('success','End Date has been updated successfully.');
					}else{
						set_flashdata('error','Something went wrong.');
					}
				
			}
		}
		
	}
    //end end date function from admin
	

}