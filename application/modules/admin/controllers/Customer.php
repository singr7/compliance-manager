<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    
    function __construct() {
        parent::__construct();

        is_adminprotected();
        $this->load->model('Customer_mod');
         $this->load->model('Qas_mod');
    }
    
  //======code for get customer listing=======//
    
    public function index(){
		$data['customer_listing'] = $this->Customer_mod->customer_listing();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Customer Listing");
        $data['page'] = 'customer/customer_listing';
        $data['title'] = 'Panacea || Customer';
        $this->load->view('layout', $data);
    }
 //======end code for get customer listing=========//
    
  //======code for get customer listing=======//  
  public function add_customer(){
      
         if (isPostBack()) {
            $this->form_validation->set_rules('full_name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'required');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('company_number', 'Company Number', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $result = $this->Customer_mod->add_customer();
                $this->session->set_flashdata("success", "Customer added successfully");
                redirect("/admin/customer");
            }
         }
      
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Add Customer");
        $data['page'] = 'customer/add_customer';
        $data['title'] = 'Panacea || Customer';
        $this->load->view('layout', $data);
  }


  //======end code for get customer listing=======// 
  
  
   //======code for get customer listing=======//  
  public function edit_customer(){
         $id = ID_decode($_GET['id']);
         if (isPostBack()) {
            $this->form_validation->set_rules('full_name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'required');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('company_number', 'Company Number', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $result = $this->Customer_mod->edit_customer($id);
                $this->session->set_flashdata("success", "Customer updated successfully");
                redirect("/admin/customer");
            }
         }
        $data['customer_details'] = $this->Customer_mod->customer_detail($id);
        //pr($data['customer_details']);die;
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Customer");
        $data['page'] = 'customer/edit_customer';
        $data['title'] = 'Panacea || Customer';
        $this->load->view('layout', $data);
  }


  //======end code for get customer listing=======// 
    //====== code for delete customer process=======// 
  public function deleteCustomerProcess() {
        $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (!empty($decode_id)) {
            $update = $this->Customer_mod->deleteCustomerProcess($decode_id);
            set_flashdata('success', 'Selected data has been deleted!');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            set_flashdata('error', 'Something went wrong!');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    //====== end code for delete customer process=======//
    
    //=========code for add customer process==============//
    
    public function add_process(){
        if (isPostBack()) {
             $this->form_validation->set_rules('process_name', 'Process Name', 'required');
             $this->form_validation->set_rules('customer_id', 'Customer Id', 'required');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $result = $this->Customer_mod->add_process();
                $this->session->set_flashdata("success", "Customer process added successfully");
                redirect("/admin/customer");
            }
        }
    }
//=========end code for add customer process===============//
    
 //=========code for delete customer========================//
    
 public function delete_customer(){
     $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (!empty($decode_id)) {
            $update = $this->Customer_mod->delete_customer($decode_id);
            set_flashdata('success', 'Selected customer has been deleted!');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            set_flashdata('error', 'Something went wrong!');
            redirect($_SERVER['HTTP_REFERER']);
        }
 }

 //=========end code for delete customer=====================//   
 
 //==========code for view customer details====================//
 
 public function view_customer() {
	 

        $decode_id = ID_decode($this->input->get('id'));
        $data['customer_details'] = $this->Customer_mod->get_customer($decode_id);
        $data['customer_process'] = $this->Customer_mod->get_customer_porcess($decode_id);
       //pr($data['customer_process']);die;
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "View Customer");
        $data['page'] = 'customer/view_customer';
        $data['title'] = 'Panacea || Customer';
        $this->load->view('layout', $data);
    }

    //==========end code for view customer details===============//
    
  //==========code for view customer details====================//
 
 public function view_process() {
	//pr($this->session->userdata());
        $decode_id = ID_decode($this->input->get('id'));
        $data['process_details'] = $this->Customer_mod->get_process_details($decode_id);
       //pr($data['process_details']);die;
		$data['breadcum'] = array("admin/dashboard/" => "Home", '' => "View Process");
        $data['page'] = 'customer/view_process';
        $data['title'] = 'Panacea || Customer';
        $this->load->view('layout', $data);
    }

    //==========end code for view customer details===============// 
    
    
    //=======code for genrate certificate=================//

    public function genrate_certificate() {
        $id = ID_decode($_GET['id']);
        $userDetails = $this->Qas_mod->get_User($id);
        $chr = time() . $userDetails->full_name . $userDetails->company_number . $userDetails->phone_number . $userDetails->company_name;
        $len = strlen($chr);
        $result = '';
        for ($i = 0; $i < 450; $i++) {
            $k = mt_rand(0, $len - 1);
            $result .= $chr[$k];
        }
//pr($result);die;
        $certificate['certificate_key'] = $result;
        $certificate['is_certificate_verified'] = 0;
        $certificate['new_certificate_key'] ='';
        $this->db->where('id', $id);
        $update = $this->db->update('users', $certificate);


        $handle = fopen("certificate.txt", "w");
        fwrite($handle, $result);
        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename('certificate.txt'));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('certificate.txt'));
        readfile('certificate.txt');
        exit;
    }

//=======end code for genrate certificate==============//    
    
 //========code for genrate certificate for QSA============//
public function genrate_certificate_for_qsa(){
     $id = ID_decode($_GET['id']);
     $userDetails = $this->Qas_mod->get_User($id);
     $chr = time() .'-'.$userDetails->id.$userDetails->full_name . $userDetails->company_number . $userDetails->phone_number . $userDetails->company_name;
        $len = strlen($chr);
        $result = '';
        for ($i = 0; $i < 450; $i++) {
            $k = mt_rand(0, $len - 1);
            $result .= $chr[$k];
        }
        $result1 = ID_encode($userDetails->id).'-'.$result;
        
        $filename = 'qsa_certificate'.'_'.str_replace(' ', '_', strtolower($userDetails->full_name)).'.txt'; 
        //$certificate['certificate_key'] = $result;
        //$certificate['is_certificate_verified'] = 0;
       // $certificate['new_certificate_key'] ='';
       // $this->db->where('id', $id);
       // $update = $this->db->update('users', $certificate);
        //pr($result1);die;
        $handle = fopen($filename, "w");
        fwrite($handle, $result1);
        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
 }

//========end code for genrate certificate for QSA==========//   
    
    
  //========code for genrate certificate for QA============//
  public function genrate_certificate_for_qa(){
     $id = ID_decode($_GET['id']);
     $userDetails = $this->Qas_mod->get_User($id);
        $chr = time() .'-'.$userDetails->id.$userDetails->full_name . $userDetails->company_number . $userDetails->phone_number . $userDetails->company_name;
        $len = strlen($chr);
        $result = '';
        for ($i = 0; $i < 450; $i++) {
            $k = mt_rand(0, $len - 1);
            $result .= $chr[$k];
        }
        $result1 = ID_encode($userDetails->id).'-'.$result;
        
        $filename = 'qa_certificate'.'_'.str_replace(' ', '_', strtolower($userDetails->full_name)).'.txt'; 
        
        $handle = fopen($filename, "w");
        fwrite($handle, $result1);
        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
 }
//========end code for genrate certificate for QA==========//   
    
 //========code for genrate certificate for QA============//
  public function genrate_certificate_for_consultants(){
     $id = ID_decode($_GET['id']);
     $userDetails = $this->Qas_mod->get_User($id);
        $chr = time() .'-'.$userDetails->id.$userDetails->full_name . $userDetails->company_number . $userDetails->phone_number . $userDetails->company_name;
        $len = strlen($chr);
        $result = '';
        for ($i = 0; $i < 450; $i++) {
            $k = mt_rand(0, $len - 1);
            $result .= $chr[$k];
        }
        $result1 = ID_encode($userDetails->id).'-'.$result;
        
        $filename = 'consultant_certificate'.'_'.str_replace(' ', '_', strtolower($userDetails->full_name)).'.txt'; 
        
        $handle = fopen($filename, "w");
        fwrite($handle, $result1);
        fclose($handle);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
 }
//========end code for genrate certificate for QA==========//   
 
 
 //========code for get user password========================//
    
 public function view_password(){
     if(isPostBack()){
           $pwd = md5($this->input->post('pwd')); 
           $id = $this->input->post('id');
           $query_pwd = $this->db->select('id')->from('users')->where('password',$pwd)->get();
           if( $query_pwd->num_rows() >0){
                $query_pwd_user = $this->db->select('pwdstring')
                        ->from('users')
                        ->where('id',$id)
                        ->get()->row();
             echo $query_pwd_user->pwdstring; die;
           }else{
                echo "false";
           }
        }
 }
 //========end code for get user password========================//
 
 //===========code for sub customer listing==================//
 
 public function sub_customer_listing(){
        $data['customer_id'] = ID_decode($this->input->get('id')); 
        $data['sub_customer_list'] = $this->Customer_mod->sub_customer_listing($data['customer_id']);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Sub Customer Listing");
        $data['page'] = 'customer/sub_customer_list';
        $data['title'] = 'Panacea || Sub Customer Listing';
        $this->load->view('layout', $data);
 }
 //===========end code for sub customer listing==================//
 
 
 //==========code for add sub customer==============================//
 
 public function add_sub_customer() {
     $data['customer_id'] = ID_decode($this->input->get('id'));
     $id = ID_encode($data['customer_id']);
        if (isPostBack()) {
            $this->form_validation->set_rules('full_name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                   $result = $this->Customer_mod->add_sub_customer($data['customer_id']);
                   $this->session->set_flashdata("success", "Sub customer added successfully");
                   redirect("/admin/customer/sub_customer_listing?id=".$id);
            }
        }
        $data['process'] = $this->Customer_mod->get_customer_process($data['customer_id']);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Add Sub Customer");
        $data['page'] = 'customer/add_sub_customer';
        $data['title'] = 'Panacea || Add Sub Customer';
        $this->load->view('layout', $data);
    }

    //=========end code for sub customer=============================//
    
    //========code for asign process to customer======================//
    
    public function asign_process(){
        $id = ID_decode($this->input->get('id'));
        $data['customer_id'] = ID_decode($this->input->get('customer_id'));
        $data['process'] = $this->Customer_mod->get_customer_process($data['customer_id']);
        if(isPostBack()){
           $processId = $_POST['process_id'];
           $customer_id = $id;
           $checkexistance = $this->Customer_mod->check_preexistace($processId,$customer_id);
           
            if($checkexistance){
               $this->session->set_flashdata("error", "This process is already asign to this customer");
                redirect($_SERVER['HTTP_REFERER']);
            }else{
           $result = $this->Customer_mod->asign_process($id);
           $this->session->set_flashdata("success", "Sub customer added successfully");
           redirect("/admin/customer/sub_customer_listing?id=".ID_encode($data['customer_id']));
           }
        }
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Asign Process");
        $data['page'] = 'customer/asign_process';
        $data['title'] = 'Panacea || Asign Process';
        $this->load->view('layout', $data);
    }
//========end code for asign process to customer==================//
    
    
  //==========code for edit sub customer======================//
    
  public function edit_sub_customer(){
        $subcustomerid = ID_decode($this->input->get('id'));
        $data['customer_id'] = ID_decode($this->input->get('customer_id'));
        if(isPostBack()){
           $this->form_validation->set_rules('full_name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                   $result = $this->Customer_mod->edit_sub_customer($subcustomerid);
                   $this->session->set_flashdata("success", "Sub customer added successfully");
                   redirect("/admin/customer/sub_customer_listing?id=".ID_encode($data['customer_id']));
            } 
        }
        $data['subCustomerDetails'] = $this->Customer_mod->get_subcustomer_details($subcustomerid);
        $data['subCustomerProcess'] = $this->Customer_mod->get_subcustomer_process($subcustomerid);
        $data['process'] = $this->Customer_mod->get_customer_process($data['customer_id']);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Sub Customer");
        $data['page'] = 'customer/edit_sub_customer';
        $data['title'] = 'Panacea || Edit Sub Customer';
        $this->load->view('layout', $data);
  }
//===========end code for edit sub customer=====================//
  
//============code for move process in archive folder================//

    public function move_archive(){
        $processId = ID_decode($this->input->get('id'));
        if(!empty($processId)){
           $result = $this->Customer_mod->move_archive($processId); 
           if($result){
               $this->session->set_flashdata("success", "Process successfully moved in archived folder");
                redirect($_SERVER['HTTP_REFERER']);
           }else{
                $this->session->set_flashdata("error", "Some thing went wrong");
                redirect($_SERVER['HTTP_REFERER']);
           }
        }
    }

//============end code for move process in archive folder=======//  



public function piaChart(){
		// get value service id
		$service_id = ID_decode(@$this->input->post('service_id'));
		// get value process id
	    $customer_id = ID_decode(@$this->input->post('customer_id'));
		// get commnet
		 $process_id = ID_decode(@$this->input->post('process_id'));
		// get total question
		$total_question = $this->Customer_mod->get_total_question($service_id);
	    echo $total_question[0]->totalQuestion;
		echo ",";
		// get attemted question
		$dataAttempted = $this->Customer_mod->get_Attempted_questoin($service_id,$customer_id,$process_id);
	    echo $dataAttempted[0]->totalAttempted;
		echo ",";
		// get rejected question
		$rejected_question = $this->Customer_mod->get_rejcted_questoin($service_id,$customer_id,$process_id);
		echo $rejected_question[0]->totalQuestionRejected;
		echo ",";
		// get approved question
		$approved_questions = $this->Customer_mod->get_approved_questoin($service_id,$customer_id,$process_id);
		echo $approved_questions[0]->totalQuestionApproved;
		echo ",";
		echo $approved_questions[0]->totalQuestionApproved+$rejected_question[0]->totalQuestionRejected;
		echo ",";
		// get assinged to qsa
		$assingedtoqsa_question = $this->Customer_mod->get_assingedtoqsa_questoin($service_id,$customer_id,$process_id);
		//pr($assingedtoqsa_question);
		echo $assingedtoqsa_question[0]->totalAssignedtoqsa;
		echo ",";
		// get assinged to qsa
		$assingedtoqa_question = $this->Customer_mod->get_assingedtoqa_questoin($service_id,$customer_id,$process_id);
		//pr($assingedtoqa_question);
	    echo $assingedtoqa_question[0]->totalAssignedtoqa;
		$incomplete_question = $this->Customer_mod->get_incomplete_questoin($service_id,$customer_id,$process_id);
		echo ",";
		echo $incomplete_question[0]->totalQuestionIncomplete;
		$disapprovedbyqsa_question = $this->Customer_mod->get_disapprovedbyQSA_questoin($service_id,$customer_id,$process_id);
		echo ",";
		echo $disapprovedbyqsa_question[0]->totalDisapprovedbyqsa;
		$disapprovedbyqa_question = $this->Customer_mod->get_disapprovedbyQA_questoin($service_id,$customer_id,$process_id);
		echo ",";
		echo $disapprovedbyqa_question[0]->totalDisapprovedbyqa;
	   // echo $incomplete_question[0]->totalQuestionIncomplete;
		
	 
		}

}
