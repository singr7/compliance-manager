<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('Cms_mod');
        $this->load->helper('function');
        //$this->load->library('upload');
        is_adminprotected();
    }

    /**
     * Index .
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        
//        if (!empty($this->input->post('selected'))) {
//            $data['selected'] = (array) $this->input->post('selected');
//        } else {
//            $data['selected'] = array();
//        }

        $data['listing'] = $this->Cms_mod->listing();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Cms Listing");
        $data['page'] = 'cms/listing';
        $data['title'] = 'Panacea || Cms';
        $this->load->view('layout', $data);
    }

    /* End of index function */

    /**
     * Add
     *
     * function add new country
     * 
     * @access	public
     * @return	html data
     */
    public function add() {
        if (isPostBack()) {
            $this->form_validation->set_rules('name', 'Unit Name', 'trim|required|max_length[35]|is_unique[fs_unit.name]');
            $this->form_validation->set_rules('status', 'Status', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $this->Cms_mod->add();
                set_flashdata('success', lang('add_success'));
                redirect('/admin/unit');
            }
        }

        $data['breadcum'] = array("admin/dashboard/" => "Home", 'admin/unit/' => 'Cms Listing', '' => 'Add');
        $data['page'] = 'unit/add';
        $data['title'] = lang('add_title');
        $this->load->view('layout', $data);
    }

    /* End of country function */

    /**
     * Edit
     *
     * this function update country
     * 
     * @access	public
     * @return	html data
     */
    public function edit($id = "") {
        $decode_id = ID_decode($this->input->get('id'));
        $encoded_id = $this->input->get('id');
        if (isPostBack()) {
            $this->form_validation->set_rules('name', 'Page Name', 'trim|required|max_length[35]');
            $this->form_validation->set_rules('title', 'Page Title', 'trim|required|max_length[35]');
            $this->form_validation->set_rules('content', 'Page Content', 'trim|required');
            //$this->form_validation->set_rules('status', 'Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                
            } else {

                $this->Cms_mod->edit($decode_id);
                set_flashdata('success', lang('edit_success'));
                redirect('/admin/cms');
            }
        }
        $data['result'] = $this->Cms_mod->view($decode_id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", 'admin/cms/' => "Cms Listing", '' => "Edit");
        $data['page'] = 'cms/edit';
        $data['title'] = lang('edit_title');
        $this->load->view('layout', $data);
    }

    /* End of country function */

    public function delete($id = "") {
        
 $decode_id = ID_decode($this->input->get('id'));
            if (!empty($decode_id)) {
                $update = $this->Cms_mod->deleteUnit($decode_id);

                if ($update) {
                    set_flashdata('success', 'Delete successfully');
                   redirect('admin/cms');                                                         
                } else {
                    set_flashdata('success', 'Something went wrong');
                    redirect('admin/cms');                                                                 		
                }
            } else {
                 set_flashdata('success', 'Something went wrong');
                redirect('admin/cms');                           
            }

    }

    function restoreData() {
        $arr = json_decode($_POST['arr']);
        $res = restoreData($arr);
        set_flashdata('success', 'Data has been restored');
        echo json_encode($res);
    }

    /* End of function */

    public function export() {

        $colNames = array('Page Name', 'Content');
        $ids = $this->input->get('ids');
        $ids = explode(',', $ids);
        $fields = array('name', 'title', 'status');
        $col1 = 'id';
        $table = 'fs_cms';
        $filename = date('d-m-Y') . "_" . 'cms.xls';
        $arr = array('table' => 'fs_cms', 'column1' => $col1, 'ids' => $ids);
        $result = export_data($arr, $fields);

        $header = "S.No \t Page Name \t Page Title";

        foreach ($result as $row) {
            //pr($row);die;  
            $data_array[] = array('Page Name' => $row->name, 'Page Title' => ucfirst($row->title));
        }
        /* ====================using helper function========================= */
        array_to_exl($header, $data_array, $filename); /* calling helper from function_helper */
    }

    /* End of function */
    public function view(){
    $decode_id = ID_decode($this->input->get('id'));
    $data['result'] = $this->Cms_mod->view($decode_id);
        $data['breadcum'] = array("admin/dashboard/" => "Home", 'admin/cms/' =>"Cms Listing", '' => "View");
        $data['page'] = 'cms/view';
        $data['title'] = "View";
        $this->load->view('layout', $data);
}
}



/*End of class*/