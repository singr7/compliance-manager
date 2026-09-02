<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    /**
     *  Auth Controller
     *
     * @package        Auth
     * @category    Auth
     * @author        Arvind Soni
     * @website        http://www.tekshapers.com
     * @company     Tekshapers Inc
     * @since        Version 1.0
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_mod');
    }

    /* End of constructor */

    /**
     *index
     *
     * This function dispaly login page
     *
     * @access    public
     * @return    html data
     */
    public function index()
    {
        if ($this->session->userdata('isLogin') == 'yes') {
            redirect(base_url('admin/dashboard'));
        } else {

            redirect(base_url('admin/auth/login'));

        }

    }

    /*End of Function*/

    /**
     * Login
     *
     * This function display login page
     *
     * @access    public
     * @return    html data
     */
    public function login()
    {

        if (isPostBack()) {
            $remember = $this->input->post('remember', true);
            $email = $this->input->post('email', true);
            $password = $this->input->post('password', true);

            $rs_data = $this->Auth_mod->login_authorize();
            if ($rs_data['status'] == "success") {

                $this->session->set_userdata("userinfo", $rs_data['result']);
                $this->session->set_userdata("isLogin", 'yes');
                $this->session->set_userdata("user_type", $rs_data['result']->user_type);

                $email_enc = custom_encryption($email, 'ak!@#s$on!', 'encrypt');
                $password_enc = custom_encryption($password, 'ak!@#s$on!', 'encrypt');
                if ($remember) // set remember username and password in cookie
                {
                    setcookie('email', $email_enc, time() + (86400 * 30), "/");
                    setcookie('password', $password_enc, time() + (86400 * 30), "/");
                    setcookie('remember', $remember, time() + (86400 * 30), "/");

                } else {
                    setcookie('email', '', time() + (86400 * 30), "/");
                    setcookie('password', '', time() + (86400 * 30), "/");
                    setcookie('remember', $remember, time() + (86400 * 30), "/");
                }
                
                    redirect(base_url('admin/dashboard'));
              
                
            } else {
                if ($rs_data['error_msg'] != '') {
                    $this->session->set_flashdata("error", $rs_data['error_msg']);
                }
                redirect(base_url('admin/auth/login'));
            }

        } else {
            if ($this->session->userdata('isLogin') == 'yes') {
                redirect(base_url('admin/dashboard'));
            } else {

                $data['title'] = 'Panacea || Login';
                $this->load->view('auth/login', $data);
            }
        }
    }

    /*End of Function*/

    /**
     *Forget
     *
     * This function send password to user mail in case forget
     *
     * @access    public
     * @return    html data
     */

    public function forgot()
    {
        if ($this->session->userdata('isLogin') == 'yes') {
            redirect(base_url('admin/dashboard'));
        } else {
           
            $arr = $this->input->post(null, true);
            $email = $this->input->post('email', true);

                $rs_data = $this->Auth_mod->forgot();
                if ($rs_data['valid']) {
                    $email_data['to'] = $email;
                    $email_data['from'] = ADMIN_EMAIL;
                    $email_data['sender_name'] = "Panacea Admin";
                    $email_data['subject'] = "Password Retrieve";
                    $email_data['message'] = array('header' => 'Password Changed !',
                        'body' => 'Hello <strong style="font-weight: bolder;font-size: 14px;">' . $rs_data['name'] . ', </strong>
                                                                <br/>Your Password has been changed successfully
                    						<br/><strong style="font-weight: bolder;font-size: 14px;">Your new password is:</strong> ' . $rs_data['new_password'] . '
                    						<br><a href="' . base_url() . 'admin/auth"> Click here to login </a>.<br><br>Thanks,<br/>Team Panacea');

                    _sendMailPhpMailer($email_data);

                    set_flashdata('success', 'Your password has been send to your Email Address.');
                    $res['status'] = 'success';
                    
                } else {
                     $res['status'] = 'error';
                     $res['msg'] = "Enter your correct e-mail address below to reset your password";
                     
                }
                echo json_encode($res);
            } 

       

    }
    /*End of Function*/

    /**
     * Logout
     *
     * This function destroy all saved session
     *
     * @access    public
     * @return    html data
     */
    public function logout()
    {
          $this->session->sess_destroy();
          redirect(base_url() . 'admin/auth/login');
        
    }
    /*End of Function*/
    
    
    function permission_denied() {    
        is_adminprotected();
        $data['title'] = 'Access Denied';
        $data['subTitle'] = 'Access Denied';
        $data['page'] = 'auth/access_denied';        
        $this->load->view('layout', $data);
    }
    
    
    public function upload_certificate(){
        $computerId = md5(@$_SERVER['HTTP_USER_AGENT'].@$_SERVER['LOCAL_ADDR'].@$_SERVER['LOCAL_PORT'].@$_SERVER['REMOTE_ADDR']);
        if (@$_FILES['certificate']['name'] != '') {
        $uploads_dir = './uploads/certificate/';
        $path = $_FILES['certificate']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $tmp_name = $_FILES["certificate"]["tmp_name"];
        
        $file_name = time().$_FILES['certificate']['name'];;
        if(move_uploaded_file($tmp_name, "$uploads_dir/$file_name")){
            $filepath = $_SERVER['DOCUMENT_ROOT'].'/uploads/certificate/'.$file_name;
            $myfile = fopen($filepath, "r") or die("Unable to open file!");
            $certificatestring = fread($myfile,filesize($filepath));
            $newcertificatestring = $certificatestring.$computerId;
            setcookie('certificate_key', $newcertificatestring, time() + (86400 * 30), "/");
            fclose($myfile);
            set_flashdata('success', 'Certificate installed successfully!');
           redirect(base_url() . 'admin/auth/login');
        }
       }else{
            
        }

   
    }
}
