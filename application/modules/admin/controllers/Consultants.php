<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Consultants extends CI_Controller {
    /*
     * Constructor
     */

    function __construct() {
        parent::__construct();

        is_adminprotected();
        $this->load->model('Consultants_mod');
    }

    //=====function for Consultants listing===================//

    public function index() {
        $data['consultant_listing'] = $this->Consultants_mod->consultants_listing();
        //pr($data['consultant_listing']);die;
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Consultants Listing");
        $data['page'] = 'consultants/consultants_listing';
        $data['title'] = 'Panacea || Consultants';
        $this->load->view('layout', $data);
    }

//======end function for Consultants listing============//    
//=======dfunction for add Consultants ========================//

    public function add_consultants() {
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
            $this->Consultants_mod->add_consultants();
                 $this->session->set_flashdata("success", "Consultant added successfully");
                  redirect("/admin/consultants");
            } 
        }
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Add Consultants");
        $data['page'] = 'consultants/add_consultants';
        $data['title'] = 'Panacea || Consultants';
        $this->load->view('layout', $data);
    }

//======end function for add Consultants =====================//
    
 //=======dfunction for edit Consultants ========================//

    public function edit_consultants() {
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
            $this->Consultants_mod->edit_consultants($id);
                 $this->session->set_flashdata("success", "Consultant updated successfully");
                  redirect("/admin/consultants");
            
        }
        }
        $data['consultant_detail'] = $this->Consultants_mod->get_User($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Consultants");
        $data['page'] = 'consultants/edit_consultants';
        $data['title'] = 'Panacea || Consultants';
        $this->load->view('layout', $data);
    }

//======end function for edit qsa =====================//   
    
 
  //=======dfunction for edit qsa ========================//

    public function view_consultants() {
        $id = ID_decode($_GET['id']);
        $data['consultant_detail'] = $this->Consultants_mod->get_User($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "View Consultants");
        $data['page'] = 'consultants/view_consultants';
        $data['title'] = 'Panacea || Consultants';
        $this->load->view('layout', $data);
    }

//======end function for edit qsa =====================//     
 
 //=======function for delete qsa ========================//   
  public function delete() {
        $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (!empty($decode_id)) {
            
            
            $update = $this->Consultants_mod->deleteConsultants($decode_id);
            set_flashdata('success', 'Selected Consultant has been deleted!');
           redirect("/admin/consultants");
        } else {
            set_flashdata('error', 'Something went wrong!');
            redirect("/admin/consultants");
        }
    }  
 //=======endd function for delete qsa ========================//
 //
 //
//=======function for check existing email============//

public function signup_email(){
        if(isPostBack()){
           $email = $this->input->post('email'); 
           $query_email=$this->db->select('*')->from('users')->where('email',$email)->get();
           if( $query_email->num_rows() >0){
                echo "false";
           }else{
                echo "true";
           }
        }
    }
//=======end code for check existing email =============//    
    
    
//=======code for genrate certificate=================//

    public function genrate_certificate() {
        $id = ID_decode($_GET['id']);
        $userDetails = $this->Qas_mod->get_User($id);
        $chr = time() . $userDetails->full_name . $userDetails->company_number . $userDetails->phone_number . $userDetails->company_name;
        $len = strlen($chr);
        $result = '';
        for ($i = 0; $i < 43; $i++) {
            $k = mt_rand(0, $len - 1);
            $result .= $chr[$k];
        }

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
}
