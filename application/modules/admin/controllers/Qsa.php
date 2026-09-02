<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qsa extends CI_Controller {
    /*
     * Constructor
     */

    function __construct() {
        parent::__construct();

        is_adminprotected();
        $this->load->model('Qsa_mod');
    }

    //=====function for qas listing===================//

    public function index() {
        $data['qsa_listing'] = $this->Qsa_mod->qsa_listing();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "QSA Listing");
        $data['page'] = 'qsa/qsas_listing';
        $data['title'] = 'Panacea || QSAs';
        $this->load->view('layout', $data);
    }

//======end function for qas listing============//    
//=======dfunction for add qsa ========================//

    public function add_qsa() {
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
            $this->Qsa_mod->add_qsa();
                 $this->session->set_flashdata("success", "Qsa added successfully");
                  redirect("/admin/qsa");
            } 
        }
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Add Qsa");
        $data['page'] = 'qsa/add_qsa';
        $data['title'] = 'Panacea || QSA';
        $this->load->view('layout', $data);
    }

//======end function for add qsa =====================//
    
 //=======dfunction for edit qsa ========================//

    public function edit_qsa() {
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
            $this->Qsa_mod->edit_qsa($id);
                 $this->session->set_flashdata("success", "Qsa updated successfully");
                  redirect("/admin/qsa");
            
        }
        }
        $data['qsa_detail'] = $this->Qsa_mod->get_User($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Qsa");
        $data['page'] = 'qsa/edit_qsa';
        $data['title'] = 'Panacea || QSA';
        $this->load->view('layout', $data);
    }

//======end function for edit qsa =====================//   
    
 
  //=======dfunction for edit qsa ========================//

    public function view_qsa() {
        $id = ID_decode($_GET['id']);
        $data['qsa_detail'] = $this->Qsa_mod->get_User($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "View Qsa");
        $data['page'] = 'qsa/view_qsa';
        $data['title'] = 'Panacea || QSA';
        $this->load->view('layout', $data);
    }

//======end function for edit qsa =====================//     
 
 //=======function for delete qsa ========================//   
  public function delete() {
        $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (!empty($decode_id)) {
            
            
            $update = $this->Qsa_mod->deleteQsa($decode_id);
            set_flashdata('success', 'Selected users has been deleted!');
           redirect("/admin/qsa");
        } else {
            set_flashdata('error', 'Something went wrong!');
            redirect("/admin/qsa");
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
        $userDetails = $this->Qsa_mod->get_User($id);
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
