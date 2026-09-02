<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
     *  Profile Controller
     *
     * @package		Profile
     * @category        Profile
     * @author		Arvind Soni
     * @website		http://www.tekshapers.com
     * @company         Tekshapers Inc
     * @since		Version 1.0
     */
class Profile extends CI_Controller {
    

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
		
        is_adminprotected();
        $this->load->model('Profile_mod');
    }

    /* End of constructor */

    /**
     * index
     *
     * This function show user profile info
     * 
     * @access	public
     * @return	html data
     */
    public function index() {
        $user_id = currentuserinfo()->id;

        if (isPostBack()) {
           // $mobile = $this->input->post('phone_number');
           // $check_data = $this->Profile_mod->check_preexistance($user_id, $mobile);
            
            // if($check_data){
            //       set_flashdata('error', 'Mobile number is already in use.');
            //       redirect($_SERVER['HTTP_REFERER']);
            //   }else{
             
             $rs_data = $this->Profile_mod->profile_edit($user_id);
            
            if ($rs_data['status'] == "success") {
               
                $userdata = $this->session->userdata("userinfo");
                $userdata->full_name = $this->input->post('full_name', NULL);
                $userdata->phone_number = $this->input->post('phone_number',NULL);
                $userdata->email = $this->input->post('email', NULL);
                
                $this->session->set_userdata("userinfo", $userdata);
                $this->session->set_flashdata('success','Profile info updated successfully');
                redirect('qsa/profile');
            } else {
                $this->session->set_flashdata('error', $rs_data['error_msg']);
                redirect('qsa/profile');
            }
                //}
        } else {
            $result = $this->Profile_mod->profile_info($user_id);
            $data['result'] = ($result['status'] == "success") ? $result['result'] : '';
            $data['breadcum'] = array("qsa/dashboard/" => "Home", '' => "My Profile");
            $data['page'] = 'profile/profile';
            $data['title'] = 'Panacea || Profile';
            $this->load->view('layout', $data);
        }
    }

    /* End of Function */

    /**
     * changeImage
     *
     * this function update user profile image by user id
     * @access	public
     * @return array
     */
    public function changeImage() {

        $user_id = currentuserinfo()->id;

        if (@$_FILES['userfile']['name'] != '') {

            $path = $_FILES['userfile']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $name = md5(time());
            $file_name = $name . "." . $ext;
            $thumb = $name . "_thumb." . $ext;
            $_FILES['userfile']['name'] = $file_name;
            $config['upload_path'] = "./uploads/profile_image/";
            $config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|JPEG|jpeg';
            //$config['max_size'] 		= '2048';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {
                $error = array('error_msg' => $this->upload->display_errors(), 'status' => 'error');
                echo json_encode($error);
                die;
            } else {
                $config['image_library'] = 'gd2';
                $config['source_image'] = "./uploads/profile_image/$file_name";
                $config['create_thumb'] = true;
                $config['width'] = 200;
                $config['height'] = 200;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                unlink($config['upload_path'] . $file_name);
            }
            $result = $this->Profile_mod->changeImage($user_id, $thumb);
            if ($result['status'] == "success") {
                $userdata = $this->session->userdata("userinfo");
                $userdata->profile_image = $thumb;
                $this->session->set_userdata("userinfo", $userdata);
            }
            $this->session->set_flashdata('success', "Profile image has been updated successfully");
            echo json_encode($result);
            die;
        } else {
            $data['status'] = 'error';
            $data['error_msg'] = "Please select image";
            echo json_encode($data);
            die;
        }
    }

    /* END Function */

    /**
     * reset_password
     *
     * this function reset password
     * 
     * @access	public
     * @return	html data
     */
    public function reset_password() {

        $user_id = currentuserinfo()->id;
        if (isPostBack()) {
            $arr = $this->input->post(null, true);

            $result = $this->Profile_mod->reset_password($user_id);

            if ($result['status'] == "success") {

               
//                $remember='';
//                $this->session->set_userdata('userinfo');
//                $this->session->set_userdata('isLogin');
//                setcookie('kc_email','',time()+(86400 * 30),"/");
                $this->session->set_flashdata('success',"Password changed successfully");
                redirect('qsa/profile/reset_password');
            } else {

                $this->session->set_flashdata('error', $result['error_msg']);
                redirect('qsa/profile/reset_password');
            }
        }else{
            $data['result'] = '';
            $data['breadcum'] = array("admin/dashboard/" => "Home", '' => "Reset Password");
            $data['page'] = 'profile/reset_password';
            $data['title'] = 'Panacea || Reset Password';
            $this->load->view('layout', $data);
        }
    }

    /* END Function */
}
