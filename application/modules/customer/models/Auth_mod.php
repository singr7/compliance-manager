<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Auth_mod Model 
 *
 * @package		Auth_mod
 * @subpackage	Models
 * @category	Auth_mod 
 * @author		Dharmendra Pal
 * @website		http://www.tekshapers.com
 * @company     Tekshapers Inc
 * @since		Version 1.0
 */
class Auth_mod extends CI_Model {

    var $user_table = "users";

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }

    /* End of Constructor */

    /**
     *
     * This function login authenticate 
     * 
     * @access	public
     * @param   String   plain string
     * @return	String   encrypted string
     */
    function login_authorize() {		
        $this->form_validation->set_rules('email', "Email Id", 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $email = $this->security->xss_clean($this->input->post('email', true));
        $password = $this->security->xss_clean($this->input->post('password', true));
        $data = array();
        if ($this->form_validation->run() === false) {           
        }
        $this->db->where("u.email", $email);
        $query = $this->db->get("$this->user_table as u");
        if ($query->num_rows() > 0){
            $row = $query->row();            
            if($row->user_type == 5){
            $password = md5($password);  
           
            if ($password == $row->password){
                $user_info = $row;
                unset($user_info->password);
                //-----------------------------------------------------
                
                if ($user_info->status == "inactive") {
                     $data['error_msg'] = "Your account has been inactive";
                     $data['status'] = 'error';
                    return $data;
                } else if ($user_info->status == "delete") {
                     $data['error_msg'] = "Your account has been deleted";
                     $data['status'] = 'error';
                    return $data;
                } else {
                   if($user_info->user_type ==2 || $row->user_type == 3 || $row->user_type == 4){
                            

                       $date1 = $user_info->created_date;
                       $date2 = date("Y-m-d");

//Convert them to timestamps.
                        $date1Timestamp = strtotime($date1);
                        $date2Timestamp = strtotime($date2);

//Calculate the difference.
                        $difference = $date2Timestamp - $date1Timestamp;
                        $days = floor($difference / (60 * 60 * 24));
//end Calculate the difference.                       
                        if($days > 180){
                                $up['password'] = '';
                                $this->db->where('id', $user_info->id);
                                $this->db->update($this->user_table, $up);
                                $data['status'] = 'error';
                                $data['error_msg'] = "Your password has been expried";
                            
                            
                        }else{
                            
                                $login_time = date("Y-m-d h:i:s");
                                $up['last_login'] = $login_time;
                                $this->db->where('id', $user_info->id);
                                $this->db->update($this->user_table, $up);
                                $data['status'] = 'success';
                                $data['result'] = $user_info;
                            }
                        }else{
                    //------update last login date time------
                    $login_time = date("Y-m-d h:i:s");
                    $up['last_login'] = $login_time;
                    $this->db->where('id', $user_info->id);
                    $this->db->update($this->user_table, $up);
                    $data['status'] = 'success';
                    $data['result'] = $user_info;
                    
                    //---------- end ---------------------------------------
                    //-----------
                }  
                }
                //------------------------------------------------------
                return $data;
                }
            }
        }

        $data['error_msg'] = "Invalid login credentials";
        $data['status'] = 'error';
        return $data;
    }
    
     /**
     * forget
     *
     * This function set password and send verification mail
     * 
     * @access	public
     * @return	mixed Array 
     */
    function forgot() {
       // pr($_POST);die;
     	$this->form_validation->set_rules('email', "Email Id", 'trim|required|valid_email');	
        $email  =   $this->input->post('email', true);        		                 
        if ($this->form_validation->run() === false) {
            $return['error_msg']    =   validation_errors();
            $return['valid']        =	false;
           
            return $return;
        }        
		
        $this->db->where("email", $email);
        $this->db->where("status", 'active');
        $result	= $this->db->get($this->user_table);
         //echo $this->db->last_query();die;
        if ($result->num_rows() > 0) {
		
            $userData       =	$result->row();						
			
            $mail_password  =	random_string('alnum',6);
            $name           =	$userData->full_name;
            //------------- secure encryption-------------------
            $password			=	md5($mail_password);
            $updateData			=	array('password' => $password,'pwdstring'=>$mail_password);
            $this->db->where('id', $userData->id);	
            $this->db->update($this->user_table,$updateData);
            
            $return['valid']		=   true;
            $return['new_password']	=   $mail_password;
            $return['name']             =   $name;
            return $return;
			
        }else{
            
            $return['valid']        =	false;
            return $return;
        }
    }    

    
     public function get_User($id){
      $this->db->select('*');
      $this->db->from('users');
      $this->db->where("id",$id);
      $query = $this->db->get()->row();
       return $query;
  }
  
  
  public function get_count_process($id){
      $this->db->select('count(id) as process');
      $this->db->from('process');
      $this->db->where("customer_id",$id);
      $query = $this->db->get()->row();
	  //echo $this->db->last_query();die;
       return $query;
  }
}

/* End of class */
?>