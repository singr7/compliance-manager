<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Archive extends CI_Controller {

    function __construct() {
        parent::__construct();

        is_adminprotected();
        $this->load->model('Archive_mod');
    }

    public function index() {
        $data['archived_data'] = $this->Archive_mod->archived_list();
        $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Archive Listing");
        $data['page'] = 'archive/archive_listing';
        $data['title'] = 'Panacea || Archive';
        $this->load->view('layout', $data);
    }

}
