<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {
    function __construct() {
        parent::__construct();

        is_adminprotected();
        $this->load->model('admin/Role_mod');
    }
    //==============code for department listing =============================//
     public function index() {
        $data['department_listing'] = $this->Role_mod->department_listing();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Depatment");
        $data['page'] = 'role/listing';
        $data['title'] = 'Panacea || Role';
        $this->load->view('layout', $data);
    }
    //==============end code for department listing =============================//
    
    //==============code for view role permission =============================//
    public function view_role(){
        $decode_id = ID_decode($this->input->get('id'));
        $data['role_data'] = $this->Role_mod->view($decode_id);
        $data['mypermission']	= $this->Role_mod->get_permission($decode_id);
        //pr($data['mypermission']);die;
        $data['breadcum'] = array("admin/dashboard/" => "Home", 'admin/role' => "Department Listing", '' => "View Permission");
        $data['page'] = 'role/view';
        $data['title'] = 'Panacea || View';
        $this->load->view('layout', $data);
    }
    //==============end code for view role permission =============================//
    
    //==================code for save role permission =========================//
    public function save_permission(){
       if (isPostBack())
		{
           
                        $decode_id = ID_decode($this->input->get('id'));
			$response = $this->Role_mod->save_permission($decode_id);
                        if(!$response['empty'])
			{
				set_flashdata('success','Permission saved Successfully');
				redirect(base_url('admin/role'));
			}
			else 
			{
				$data['error_msg'] = 'Permission could not be saved, Please try again latter';
			}
                }
                $data['role_data'] = $this->Role_mod->view($decode_id);
        $data['mypermission'] = $this->Role_mod->get_permission($decode_id);
        //pr($data['mypermission']);die;
        $data['breadcum'] = array("admin/dashboard/" => "Home", 'admin/role' => "Department Listing", '' => "View Permission");
        $data['page'] = 'role/view';
        $data['title'] = 'Panacea || View';
        $this->load->view('layout', $data);
    }




    //==================end code for save role permission =========================//
    
}
