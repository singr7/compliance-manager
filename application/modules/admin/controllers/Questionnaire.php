<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Questionnaire extends CI_Controller {
    
    /**
     * Constructor
     */ 
    function __construct() {
        parent::__construct();
        $this->load->model('Questionnaire_mod');
	is_adminprotected();
    }
   
    
    public function questionnaire_list(){
        $id = ID_decode($_GET['id']);
       
         if (!empty($this->input->post('selected'))) {
            $data['selected'] = (array) $this->input->post('selected');
        } else {
            $data['selected'] = array();
        }
        $data['pci_dss_question_list'] = $this->Questionnaire_mod->questionnaire_list($id);
        $data['service_name'] = $this->Questionnaire_mod->service_name($id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Questionnaire Listing");
        $data['page'] = 'questionnaire/questionnaire_list';
        $data['title'] = 'Panacea || Questionnaire';
        $this->load->view('layout', $data);
    }
    
   
    
    public function active_questionnaire(){
        $ids = $this->input->get('ids');
        $ids = explode(',', $ids);
        $result = $this->Questionnaire_mod->active_questionnaire($ids);
        if($result){
            set_flashdata('success','Selected questions have been active successfully');
		redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_flashdata('error','Something went wrong');
		redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public function inactive_questionnaire(){
        $ids = $this->input->get('ids');
        $ids = explode(',', $ids);
        $result = $this->Questionnaire_mod->inactive_questionnaire($ids);
        if($result){
            set_flashdata('success','Selected questions have been inactive successfully');
		redirect($_SERVER['HTTP_REFERER']);
        }else{
            set_flashdata('error','Something went wrong');
		redirect($_SERVER['HTTP_REFERER']);
        }
    }
   
    //=======function for edit question ========================//
    public function edit_question(){
        $id = ID_decode($_GET['id']);
        $data['question_detail'] = $this->Questionnaire_mod->get_Question($id);
        $serviceId = $data['question_detail']->Service;
        if (isPostBack()) {
            $this->form_validation->set_rules('question', 'Question', 'required');
            if ($this->form_validation->run() == FALSE) {
                set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            } else {
            $this->Questionnaire_mod->edit_question($id);
                 $this->session->set_flashdata("success", "Question updated successfully");
                  redirect("/admin/questionnaire/questionnaire_list?id=".ID_encode($serviceId));
            
        }
        }
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Edit Question");
        $data['page'] = 'questionnaire/edit_questionnaire';
        $data['title'] = 'Panacea || Questionnaire';
        $this->load->view('layout', $data);
    }
    //=======function for edit question ========================//
}