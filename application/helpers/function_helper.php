<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Database helper
 *
 * @package		Helpers
 * @category            database helper
 * @author		Ankit Rajput
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc 
 * @since		Version 1.0
 */
/**
 * is_adminprotected
 *
 * This function check superadminuser already login or not
 * 
 * @access	public
 * @return	boolean
 */
if (!function_exists('is_adminprotected')) {

    function is_adminprotected() {
        $CI = &get_instance();
        if ($CI->session->userdata('isLogin') == 'yes') {
            return true;
        } else {
            redirect('admin/auth');
        }
    }

}

/* End of Function */
/**
 * is_userprotected
 *
 * This function check superadminuser already login or not
 * 
 * @access	public
 * @return	boolean
 */
if (!function_exists('is_userprotected')) {

    function is_userprotected() {
        $CI = &get_instance();
        if ($CI->session->userdata('isLogin') == 'yes') {

            return true;
        } else {
            redirect('/site');
        }
    }

}
/* End of Function */


/**
 * validate_admin_login
 *
 * This function check user type and redirect according
 * 
 * @access	public
 * @return	boolean
 */
if (!function_exists('validate_admin_login')) {

    function validate_admin_login() {
        $CI = &get_instance();
        if ($CI->session->userdata('isLogin') == 'yes') {
            if ($CI->session->userdata('user_type') == 1) {
                
            } else if (in_array($CI->session->userdata('user_type'), array(5, 6, 7))) {
                
            } else {
                redirect('/admin/dashboard');
            }
        }
    }

}
/* End of Function */


/**
 * validate_user_login
 *
 * This function check user type and redirect according
 * 
 * @access	public
 * @return	boolean
 */
if (!function_exists('validate_user_login')) {

    function validate_user_login() {
        $CI = &get_instance();
        if ($CI->session->userdata('isLogin') == 'yes') {
            if ($CI->session->userdata('user_type') == 4) {
                
            } else if ($CI->session->userdata('user_type') == 3) {
                redirect('/admin/dashboard');
            } else {
                redirect('/admin/dashboard');
            }
        }
    }

}
/* End of Function */

/**
 * @Function _layout
 * @purpose load layout page 
 * @created  2 dec 2014
 */
if (!function_exists('_layout')) {

    function _layout($data = null) {
        $CI = &get_instance();
        $CI->load->view('layout', $data);
    }

}
/* End of Function */


/* start of Function view evidence data */
/**
 * @Function _layout
 * @purpose load layout page 
 * @created  2 dec 2014
 */
if (!function_exists('common_function')) {

    function common_function($questionid,$service_id,$process_id,$get_userid) {
		   $CI = &get_instance();
		    $userdata=$CI->session->userdata();
		   $CI->db->select('id,parent_id');
		   $CI->db->from('users');
		   $CI->db->where("id", $get_userid);
		   $user_id = $CI->db->get()->result();
		   $users_id="";
		   if(isset($user_id[0]->parent_id)){
				if(trim(@$user_id[0]->parent_id) == "0"){
					$users_id=$user_id[0]->id;
				}else{
					$users_id=@$user_id[0]->parent_id;
				}
		   }
				$CI->db->select('id,parent_id');
				$CI->db->from('users');
				$CI->db->where("parent_id", $users_id);
				$multiuser_id = $CI->db->get()->result();
				 $ids=array();
		   $parent_id=0;
		   foreach($multiuser_id as $multiuser_ids){
			   $ids[]=$multiuser_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$get_userid;
		   }else{
			  $parent_id=$users_id.",".$parent_id;
		   }
		   $parents_id=explode(',',$parent_id);
		   $CI->db->select('*');
		   $CI->db->from('uplaod_evidence');
		   $CI->db->where("service_id", $service_id);
		   $CI->db->where("questionnaire_id",$questionid);
		   $CI->db->where("process_id",$process_id);
		   $CI->db->where_in("customer_id",$parents_id);
		   $query = $CI->db->get()->result();
			//echo $CI->db->last_query();
			return $query;
	}

}
/* End of Function view evidence data */

if (!function_exists('user_name')) {

    function user_name($user_id) {
		if(isset($user_id) && !empty($user_id)){
		$CI = &get_instance();
		$CI->db->select('full_name');
		$CI->db->from('users');
		$CI->db->where("id", $user_id);
		$query = $CI->db->get()->row();
		if($query){
			return $query;
		}else{
			return false;
		}
		}
		   
	}

}

if (!function_exists('service_id')) {

    function service_id($service_id) {
		$CI = &get_instance();
		$CI->db->select('id');
		$CI->db->from('services');
		$CI->db->where("id", $service_id);
		$query = $CI->db->get()->row();
		if($query){
			return $query;
		}else{
			return false;
		}
		   
	}

}

if (!function_exists('admin_log')) {

    function admin_log($question_id,$service_id,$process_id,$customer_id) {
		$CI = &get_instance();
		$CI->db->select('*');
		$CI->db->from('admin_status_log');
		$CI->db->where("qestion_id", $question_id);
		$CI->db->where("service_id", $service_id);
		$CI->db->where("process_id", $process_id);
		$CI->db->where("customer_id", $customer_id);
		$query = $CI->db->get()->result();
		if($query){
			return $query;
		}else{
			return false;
		}
		   
	}

}



/* start of Function view evidence data */
/**
 * @Function _layout
 * @purpose load layout page 
 * @created  2 dec 2014
 */
if (!function_exists('Admin_status_change')) {

    function Admin_status_change($service_id,$process_id,$get_userid) {
		   $CI = &get_instance();
		   $CI->db->select('id');
		   $CI->db->from('questionnaire');
		   $CI->db->where("service_id", $service_id);
		   $quetinarie = $CI->db->get()->result();
		   $counts=0;
		   if(count($quetinarie)>0){
			foreach($quetinarie as $quetinaries){
			   $CI->db->select('admin_status');
			   $CI->db->from('uplaod_evidence');
			   $CI->db->where("service_id", $service_id);
			   $CI->db->where("process_id",$process_id);
			   $CI->db->where("customer_id",$get_userid);
			   $CI->db->where("questionnaire_id",$quetinaries->id);
			   $fetchdata= $CI->db->get()->result();
			//   echo $CI->db->last_query();
			  	if(trim(@$fetchdata[0]->admin_status) == "1"){
						$counts++; 
				}
			}
			   if($counts ==count($quetinarie)){
				  return "Approved";
				   
			   }else{
				   return "In Progress";
			   }
		  }
		}

}
/* End of Function view evidence data */



if (!function_exists('Admin_status_approved_change')) {

    function Admin_status_approved_change($service_id,$process_id,$get_userid) {
		   $CI = &get_instance();
		   $CI->db->select('id');
		   $CI->db->from('questionnaire');
		   $CI->db->where("service_id", $service_id);
		   $quetinarie = $CI->db->get()->result();
		   $counts=0;
		   if(count($quetinarie)>0){
			foreach($quetinarie as $quetinaries){
			   $CI->db->select('qa_status');
			   $CI->db->from('uplaod_evidence');
			   $CI->db->where("service_id", $service_id);
			   $CI->db->where("process_id",$process_id);
			   $CI->db->where("customer_id",$get_userid);
			   $CI->db->where("questionnaire_id",$quetinaries->id);
			   $fetchdata= $CI->db->get()->result();
			  // echo $CI->db->last_query();
			  	if(trim(@$fetchdata[0]->qa_status) == "1"){
						$counts++; 
				}
			}
			   if($counts ==count($quetinarie)){
				  return "disabled";
				   
			   }else{
				   return "";
			   }
		  }
		}

}
/* End of Function view evidence data */

/* start of Function for customer_comments */
/**
 * @Function customer_comments
 * @purpose fethc the customer comment
 * @created  30 nove 2018
 */
if (!function_exists('customer_comments')) {

    function customer_comments($questionid,$service_id,$process_id,$customer_id){
		$CI = &get_instance();
		   $userdata=$CI->session->userdata();
		   $CI->db->select('id,parent_id');
		   $CI->db->from('users');
		   $CI->db->where("id", $customer_id);
		   $user_id = $CI->db->get()->result();
		        $users_id="";
				if(isset($user_id[0]->parent_id)){
				if(trim(@$user_id[0]->parent_id) == "0"){
					$users_id=@$user_id[0]->id;
					
				}else{
					$users_id=@$user_id[0]->parent_id;
					
				}
				}
				$CI->db->select('id,parent_id');
				$CI->db->from('users');
				$CI->db->where("parent_id", $users_id);
				$multiuser_id = $CI->db->get()->result();
		   $ids=array();
		   $parent_id=0;
		   foreach($multiuser_id as $multiuser_ids){
			   $ids[]=$multiuser_ids->id;
			  
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$users_id.",".$parent_id;
		   }
		   $parents_id=explode(',',$parent_id);
		    $CI->db->select('*');
		    $CI->db->from('all_comments');
		   $CI->db->where("qestion_id",$questionid);
			$CI->db->where_in("customer_id",$parents_id);
			$CI->db->where("service_id",$service_id);
			$CI->db->where("process_id", $process_id );
			//$CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		    $query = $CI->db->get();
		//	 echo $CI->db->last_query();
		  if ($query->num_rows() > 0) {
				return $data=$query->result();
			} else {
				return false;
			}
		
		   
	}

}
/* End of Function for view customer_comments */

/* start of Function for customer_comments */
/**




 * @Function customer_comments
 * @purpose fethc the customer comment
 * @created  30 nove 2018
 */
if (!function_exists('qsa_comments')) {

    function qsa_comments($questionid,$service_id,$process_id,$customer_id) {
		    $CI = &get_instance();
			//$userdata=$CI->session->userdata();
			$CI->db->select('id');
			$CI->db->from('users');
			$CI->db->where("parent_id", $customer_id);
			$userid_id = $CI->db->get()->result();
			$ids=array();
		   $parent_id=0;
		   foreach($userid_id as $user_ids){
			   $ids[]=$user_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$customer_id.",".$parent_id;
		   }
		   $parent_ids=explode(',',$parent_id);
		   $CI->db->select('qsa_comments,qsa_id,updated_date');
		   $CI->db->from('qsa_comments');
		   $CI->db->where("qestion_id",@$questionid);
		   $CI->db->where("service_id",@$service_id);
		   $CI->db->where("process_id",@$process_id);
		   $CI->db->where_in("customer_id",@$parent_ids);
		   //$CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		   $query = $CI->db->get();
		 //  echo $CI->db->last_query();
			if ($query->num_rows() > 0) {
				$data=$query->result();
				return $data; 
			} else {
				return false;
			}
		   
	}

}
/* End of Function for customer_comments */


/* start of Function for _comments */
/**
 * @Function qa_comments
 * @purpose fethc the customer comment
 * @created  30 nove 2018
 */
if (!function_exists('qa_comments')) {

    function qa_comments($questionid,$service_id,$process_id,$customer_id) {
			$CI = &get_instance();
			//$userdata=$CI->session->userdata();
			$CI->db->select('id');
			$CI->db->from('users');
			$CI->db->where("parent_id", $customer_id);
			$userid_id = $CI->db->get()->result();
			$ids=array();
		   $parent_id=0;
		   foreach($userid_id as $user_ids){
			   $ids[]=$user_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$customer_id.",".$parent_id;
		   }
		   $parent_ids=explode(',',$parent_id);
		   $CI->db->select('qa_comments,qa_id,updated_date');
		   $CI->db->from('qa_comments');
		   $CI->db->where("qestion_id",@$questionid);
		   $CI->db->where("service_id",@$service_id);
		   $CI->db->where("process_id",@$process_id);
		   $CI->db->where_in("customer_id",@$parent_ids);
		  // $CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		   $query = $CI->db->get();
		//    echo $CI->db->last_query();
			if ($query->num_rows() > 0) {
				$data=$query->result();
				return $data; 
			} else {
				return false;
			}
	}
}
/* End of Function for qa_comments */

/* start of Function for consultant comments */
/**
 * @Function qa_comments
 * @purpose fethc the customer comment
 * @created  30 nove 2018
 */
if (!function_exists('consultant_comments')) {

    function consultant_comments($questionid,$service_id,$process_id,$customer_id) {
		    $CI = &get_instance();
		//	$userdata=$CI->session->userdata();
		    $CI->db->select('id');
			$CI->db->from('users');
			$CI->db->where("parent_id", $customer_id);
			$user_id = $CI->db->get()->result();
			$ids=array();
		   $parent_id=0;
		   foreach($user_id as $user_ids){
			   $ids[]=$user_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$customer_id.",".$parent_id;
		   }
		    $parent_ids=explode(',',$parent_id);
		   $CI->db->select('consultant_comments,consultant_id');
		   $CI->db->from('consultant_comments');
		   $CI->db->where("qestion_id",$questionid);
		   $CI->db->where("service_id",$service_id);
		   $CI->db->where("process_id",$process_id);
		   $CI->db->where_in("customer_id",$parent_ids);
		  // $CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		   $query = $CI->db->get();
		///   echo $CI->db->last_query();
			if ($query->num_rows() > 0) {
				$data=$query->result();
				return $data; 
			} else {
				return false;
			}
	}
}
/* end of Function for consultant comments */


/**
 * @Function _layout
 * @purpose load layout page 
 * @created  2 dec 2014
 */
if (!function_exists('common_status_change_function')) {

    function common_status_change_function($statuschange) {
		  $disabled="";
		  $buttonName="";
		  //for status botton name change and disabled 
		  if(trim(@$statuschange) == '1'){
			   $disabled="disabled";
		  }else{
			   $disabled="";
		  }
		return $disabled;
	}

}
/* End of Function view evidence data */



if (!function_exists('get_common_docs')) {
   function get_common_docs($question_id,$service_id,$process_id,$customer_id,$user_id){
	      $CI = &get_instance();
		 // $userdata=$CI->session->userdata();
		  $CI->db->select('id,parent_id');
		  $CI->db->from('users');
		  $CI->db->where("parent_id", $customer_id);
		  $userid_id = $CI->db->get()->result();
		  $ids=array();
		   $parent_id=0;
		   foreach($userid_id as $user_ids){
			   $ids[]=$user_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$customer_id.",".$parent_id;
		   }
		$parent_ids=explode(',',$parent_id);
		//pr($parent_ids);
		$CI->db->select('*');
		$CI->db->from('common_uplaod_docs');
		$CI->db->where("questionnaire_id", $question_id);
		$CI->db->where("service_id", $service_id);
		$CI->db->where("process_id", $process_id);
		$CI->db->where_in("customer_id",$parent_ids);
		//$CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		$CI->db->where("user_id",$user_id);
		$query = $CI->db->get()->result();
		// echo $CI->db->last_query();
		return $query;
	
	}
}



if (!function_exists('get_qsa_document')) {
   function get_qsa_document($question_id,$service_id,$process_id,$customer_id,$user_id){
	      $CI = &get_instance();
		 // $userdata=$CI->session->userdata();
		  $CI->db->select('id,parent_id');
		  $CI->db->from('users');
		  $CI->db->where("parent_id", $customer_id);
		  $userid_id = $CI->db->get()->result();
		  $ids=array();
		   $parent_id=0;
		   foreach($userid_id as $user_ids){
			   $ids[]=$user_ids->id;
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$customer_id.",".$parent_id;
		   }
		$parent_ids=explode(',',$parent_id);
		//pr($parent_ids);
		$CI->db->select('*');
		$CI->db->from('common_uplaod_docs');
		$CI->db->where("questionnaire_id", $question_id);
		$CI->db->where("service_id", $service_id);
		$CI->db->where("process_id", $process_id);
		$CI->db->where_in("customer_id",$parent_ids);
		//$CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		$CI->db->where("user_id !=",$user_id);
		$query = $CI->db->get()->result();
		// echo $CI->db->last_query();
		return $query;
	
	}
}




/* start of Function view evidence data */
/**
 * @Function _layout
 * @purpose load layout page 
 * @created  2 dec 2014
 */
if (!function_exists('common_function_for_docs')) {

    function common_function_for_docs($questionid,$service_id,$process_id,$customer_id) {
		//pr($customer_id);
		 $CI = &get_instance();
		   $userdata=$CI->session->userdata();
		   $CI->db->select('id,parent_id');
		   $CI->db->from('users');
		   $CI->db->where("id", $customer_id);
		   $user_id = $CI->db->get()->result();
		        $users_id="";
				if(trim(@$user_id[0]->parent_id) == "0"){
					$users_id=@$user_id[0]->id;
					
				}else{
					$users_id=@$user_id[0]->parent_id;
					
				}
				$CI->db->select('id,parent_id');
				$CI->db->from('users');
				$CI->db->where("parent_id", $users_id);
				$multiuser_id = $CI->db->get()->result();
		   $ids=array();
		   $parent_id=0;
		   foreach($multiuser_id as $multiuser_ids){
			   $ids[]=$multiuser_ids->id;
			  
		   }
		   $parent_id=implode(',',$ids);
		   if(empty($parent_id)){
			  $parent_id=$customer_id;
		   }else{
			  $parent_id=$users_id.",".$parent_id;
		   }
		   $parents_id=explode(',',$parent_id);
		    $CI->db->select('*');
		    $CI->db->from('common_uplaod_docs');
		    $CI->db->where("questionnaire_id", $questionid);
		    $CI->db->where("service_id", $service_id);
		    $CI->db->where("process_id", $process_id);
		    $CI->db->where_in("customer_id",$parents_id);
			//$CI->db->where("parent_id",$userdata['userinfo']->parent_id);
		    $query = $CI->db->get();
		//	 echo $CI->db->last_query();
		  if ($query->num_rows() > 0) {
				return $data=$query->result();
			} else {
				return false;
			}
    }

}
/* End of Function view evidence data */

/**
 * set_flashdata
 *
 
 * This function set falsh message value
 * 
 * @access	public
 * 
 */
if (!function_exists('set_flashdata')) {

    function set_flashdata($type, $msg) {
        $CI = &get_instance();
        $CI->session->set_flashdata($type, $msg);
    }

}
/* End of Function */

/**
 * get_flashdata
 *
 * This function give custome flash message formate
 * 
 * @access	public
 * @return	html data
 */
if (!function_exists('get_flashdata')) {

    function get_flashdata() {
        $CI = &get_instance();
        $success = $CI->session->flashdata('success') ? $CI->session->flashdata('success') : '';
        $error = $CI->session->flashdata('error') ? $CI->session->flashdata('error') : '';
        $warning = $CI->session->flashdata('warning') ? $CI->session->flashdata('warning') : '';
        $msg = '';
        if ($success) {
            $msg = '<div class="alert alert-success flash-row">
                            <button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><i class="ace-icon fa fa-check green"></i>
                            ' . $success . ' </div>';
        } elseif ($error) {
            $msg = '<div class="alert alert-danger flash-row">
			<button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><i class="ace-icon fa fa-check green"></i>
			<strong>Error!</strong> ' . $error . ' </div>';
        } elseif ($warning) {
            $msg = '<div class="alert alert-warning flash-row">
			<button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
			' . $warning . '</div>';
        }
        return $msg;
    }

}
/* End of Function */

/**
 * isPostBack
 *
 * This function check request send by POST or  not
 * 
 * @access	public
 * @return	html data
 */
if (!function_exists('isPostBack')) {

    function isPostBack() {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
            return true;
        else
            return false;
    }

}
/* End of Function */

/**
 * isGetBack
 *
 * This function check request send by GET or  not
 * 
 * @access	public
 * @return	html data
 */
if (!function_exists('isGetBack')) {

    function isGetBack() {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
            return true;
        else
            return false;
    }

}
/* End of Function */

/**
 * Current Date And Time
 *
 * This function get Current Date And Time
 *
 * @param	
 * @return
 */
if (!function_exists('current_date')) {

    function current_date() {
        $dateFormat = date("Y-m-d H:i:s", time());
        $timeNdate = $dateFormat;
        return $timeNdate;
    }

}
/* End of Function */


/**
 * Date format
 *
 * This function get correct date format
 *
 * @param	
 * @return
 */
if (!function_exists('correct_date')) {

    function correct_date($posted_date) {
        $postdate = str_replace('/', '-', $posted_date);
        $dateFormat = date("Y-m-d", strtotime($postdate));
        return $dateFormat;
    }

}
/* End of Function */



/**
 * Date format for view
 *
 * This function get date format for view exp: d/m/Y
 *
 * @param	
 * @return
 */
if (!function_exists('view_date_format')) {

    function view_date_format($view_date) {
        if ($view_date) {
            $view_date = str_replace('-', '/', $view_date);
            $dateFormat = date("d/m/Y", strtotime($view_date));
            return $dateFormat;
        } else {
            return false;
        }
    }

}
/* End of Function */





/**
 * Current User Info 
 * 
 * If user loged then returl current user info
 *
 * @access	public
 * @return	mixed	boolean or depends on what the array contains
 */
if (!function_exists('currentuserinfo')) {

    function currentuserinfo() {
        $CI = &get_instance();
        return $CI->session->userdata("userinfo");
    }

}
/* End of Function */



/**
 * uri_segment
 * this function give url segment value
 * @param int 
 * @return string
 */
if (!function_exists('uri_segment')) {

    function uri_segment($val) {
        $CI = &get_instance();
        return $CI->uri->segment($val);
    }

}
/* End of Function */

/**
 * pr
 * this function print data with <pre> tag
 * @access	public
 */
if (!function_exists('pr')) {

    function pr($data = null) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}
/* End of Function */



/**
 * is_ajax_post
 *
 * This function check request send by Ajax or not
 *
 * 	
 * @return boolean
 */
if (!function_exists('is_ajax_post')) {

    function is_ajax_post() {
        $CI = &get_instance();
        if (!$CI->input->is_ajax_request()) {
            show_error('No direct script access allowed');
            exit;
        }
    }

}
/* End of Function */



/**
 * Function to check ajax request
 *
 * @access	public
 */
if (!function_exists('is_ajax_request')) {

    function is_ajax_request() {
        $CI = &get_instance();
        if (!$CI->input->is_ajax_request()) {
            show_error('No direct script access allowed');
            exit;
        }
    }

}
/* End of Function */






/**
 * _show404
 *
 * This function show error message
 *
 * 	
 * @return array 
 */
if (!function_exists('_show404')) {

    function _show404() {
        $CI = &get_instance();
        $data['title'] = 'Error';
        $data['subTitle'] = 'Wrong Page';
        $data['page'] = 'error';
        _layout($data);
    }

}
/* End of Function */





/**
 * custom_encryption
 *
 * This function encryt and decrypt value on the base action value
 * @param string
 * @param string
 * @param string
 * 	
 * @return string
 */
if (!function_exists('custom_encryption')) {

    function custom_encryption($string, $key, $action) {  //echo die($action);
        if ($action == 'encrypt')
            return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
        elseif ($action == 'decrypt')
            return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }

}
/* End of Function */


/**
 * get_topics
 *
 * This function give  captcha image
 * 
 * @return html data
 * 	
 */
if (!function_exists('getcaptchacode')) {

    function getcaptchacode() {
        $CI = & get_instance();
        $CI->load->helper('captcha');
        $listAlpha = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $numAlpha = 5;
        $captcha = substr(str_shuffle($listAlpha), 0, $numAlpha);

        $path = config_item('base_url') . 'captcha/';
        //$fontpath = config_item('base_url').'bucket/frontend/assets/fonts/TitilliumWeb-BoldItalic.ttf';
        $fontpath = 'assets/fonts/verdana.ttf';
        $vals = array(
            'word' => $captcha,
            'img_path' => './captcha/',
            'img_url' => $path,
            //'font_path'	 => 'c:/windows/fonts/verdana.ttf',
            'font_path' => $fontpath,
            'img_width' => '159',
            'img_height' => '32',
            'border' => 0,
            'expiration' => 1800
        );
        $get_captcha = create_captcha($vals); //pr($get_captcha); die;
        $CI->session->set_userdata('codecaptcha', $get_captcha['word']);
        return $get_captcha;
    }

}
/* End of Function */

/**
 * obj_to_arr
 *
 * This function convert std object array into array 
 * 
 * @return array
 * 	
 */
if (!function_exists('obj_to_arr')) {

    function obj_to_arr($obj_arr) {
        $obj_arr = (array) $obj_arr;
        $chkey = array_keys($obj_arr);
        $chval = array_values($obj_arr);
        unset($obj_arr);
        $obj_arr = array_combine($chkey, $chval);
        return $obj_arr;
    }

}
/* End of Function */


/**
 * Id_encode
 *
 * This function to encode ID by a custom number
 * @param string
 * 	
 */
if (!function_exists('ID_encode')) {

    function ID_encode($id) {
        $encode_id = '';
        if ($id) {
            $encode_id = rand(1111, 9999) . (($id + 19)) . rand(1111, 9999);
        } else {
            $encode_id = '';
        }
        return $encode_id;
    }

}
/* End of function */

/**
 * Id_decode
 *
 * This function to decode ID by a custom number
 * @param string
 * 	
 */
if (!function_exists('ID_decode')) {

    function ID_decode($encoded_id) {
        $id = '';
        if ($encoded_id) {
            $id = substr($encoded_id, 4, strlen($encoded_id) - 8);
            $id = $id - 19;
        } else {
            $id = '';
        }
        return $id;
    }

}
/* End of function */




/**
 * _sendMailPhpMailer
 *
 * This function send mail to the given email id 
 * @param string
 * 	
 */
if (!function_exists('_sendMailPhpMailer')) {

    function _sendMailPhpMailer($email_data) {
        $CI = &get_instance();
        $isCISendmail = $CI->config->item('sendmailCI');
        if ($isCISendmail) {// mail send by CI sendmail
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = true;

            $CI->email->set_mailtype("html");
            $CI->email->initialize($config);
            $CI->email->from($email_data['from'], ucwords($email_data['sender_name']));

            if (@$email_data['to'] != '') {
                $CI->email->to(@$email_data['to']);
            }

            if (@$email_data['cc'] != '') {
                $CI->email->cc(@$email_data['cc']);
            }

            if (@$email_data['bcc'] != '') {
                $CI->email->bcc(@$email_data['bcc']);
            }

            $i = 0;
            if (@$email_data['file'] != '') {
                if (is_array(@$email_data['file']) && count(@$email_data['file']) > 0) {
                    $arr_files = array();
                    $arr_files = @$email_data['file'];
                    $arr_files_name = @$email_data['file_name'];
                    foreach ($arr_files as $file) {
                        $CI->email->attach($file, 'attachment', $arr_files_name[$i]);
                        $i++;
                    }
                } else {

                    $CI->email->attach($email_data['file'], 'attachment', @$email_data['file_name']);
                }
            }

            $CI->email->subject(ucfirst($email_data['subject']));
            $data['message'] = $email_data['message'];
            if (isset($email_data['cmp_logo'])) {
                $data['cmp_logo'] = @$email_data['cmp_logo'];
            } else {
                $data['cmp_logo'] = @currentuserinfo()->cmp_logo;
            }

            $msg = $CI->load->view('email_template/email_layout', $data, true);
            $CI->email->message($msg);

            if ($CI->email->send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $CI->load->library('Sendmail');
            $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; // or 587
            $mail->IsHTML(true);
            $mail->Username = "test.tekshapers@gmail.com"; //ankit2@tekshapers.com";
            $mail->Password = "developer@tekshapers1";


            //
            //$mail->SetFrom($email_data['from'],$email_data['sender_name'],0);
            $mail->Subject = $email_data['subject'];
            $data['message'] = $email_data['message'];
            if (isset($email_data['cmp_logo'])) {
                $data['cmp_logo'] = @$email_data['cmp_logo'];
            } else {
                $data['cmp_logo'] = @currentuserinfo()->cmp_logo;
            }

            $msg = $CI->load->view('email_templates/email_layout', $data, true);
            //echo $msg;die;
            $mail->Body = $msg;

            if (@$email_data['from'] != '') {

                $mail->SetFrom(@$email_data['from'], @$email_data['sender_name'], '1');
            }

            if (@$email_data['to'] != '') {
                $arr_to = explode(',', @$email_data['to']);
                foreach ($arr_to as $to) {
                    $mail->AddAddress($to);
                }
            }
            if (@$email_data['cc'] != '') {
                $arr_cc = explode(',', @$email_data['cc']);
                foreach ($arr_cc as $cc) {
                    $mail->AddCC($cc);
                }
            }

            if (@$email_data['bcc'] != '') {
                $arr_bcc = explode(',', @$email_data['bcc']);
                foreach ($arr_bcc as $bcc) {
                    $mail->AddBCC($bcc);
                }
            }

            if (@$email_data['file'] != '') {
                if (is_array(@$email_data['file']) && count(@$email_data['file']) > 0) {
                    $arr_files = array();
                    $arr_files_name = array();
                    $arr_files = @$email_data['file'];
                    $i = 0;
                    if (is_array(@$email_data['file_name']) && count(@$email_data['file_name']) > 0) {
                        $arr_files_name = @$email_data['file_name'];
                        foreach ($arr_files as $file) {

                            $mail->AddAttachment($file, $arr_files_name[$i]);
                            $i++;
                        }
                    } else {
                        foreach ($arr_files as $file) {

                            $mail->AddAttachment($file);
                        }
                    }
                } else {
                    $CI->email->attach();
                    $mail->AddAttachment($email_data['file']);
                }
            }

            if ($mail->Send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
/* End of Function */



/**
 * _sendMailOrderConfirmPhpMailer
 *
 * This function send mail to the given email id 
 * @param string
 * 	
 */
if (!function_exists('_sendMailOrderConfirmPhpMailer')) {

    function _sendMailOrderConfirmPhpMailer($email_data, $order_template_data) {
        $CI = &get_instance();
        $isCISendmail = $CI->config->item('sendmailCI');
        if ($isCISendmail) {// mail send by CI sendmail
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = true;

            $CI->email->set_mailtype("html");
            $CI->email->initialize($config);
            $CI->email->from($email_data['from'], ucwords($email_data['sender_name']));

            if (@$email_data['to'] != '') {
                $CI->email->to(@$email_data['to']);
            }

            if (@$email_data['cc'] != '') {
                $CI->email->cc(@$email_data['cc']);
            }

            if (@$email_data['bcc'] != '') {
                $CI->email->bcc(@$email_data['bcc']);
            }

            $i = 0;
            if (@$email_data['file'] != '') {
                if (is_array(@$email_data['file']) && count(@$email_data['file']) > 0) {
                    $arr_files = array();
                    $arr_files = @$email_data['file'];
                    $arr_files_name = @$email_data['file_name'];
                    foreach ($arr_files as $file) {
                        $CI->email->attach($file, 'attachment', $arr_files_name[$i]);
                        $i++;
                    }
                } else {

                    $CI->email->attach($email_data['file'], 'attachment', @$email_data['file_name']);
                }
            }

            $CI->email->subject(ucfirst($email_data['subject']));
            $data['message'] = $email_data['message'];
            $data['order_details'] = $order_template_data['order_details'];
            $data['cart_data'] = $order_template_data['cart_data'];

            if (isset($email_data['cmp_logo'])) {
                $data['cmp_logo'] = @$email_data['cmp_logo'];
            } else {
                $data['cmp_logo'] = @currentuserinfo()->cmp_logo;
            }

            $msg = $CI->load->view('email_template/email_order_template', $data, true);
            $CI->email->message($msg);

            if ($CI->email->send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $CI->load->library('Sendmail');
            $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; // or 587
            $mail->IsHTML(true);
            $mail->Username = "test.tekshapers@gmail.com"; //ankit2@tekshapers.com";
            $mail->Password = "developer@tekshapers1";


            //
            //$mail->SetFrom($email_data['from'],$email_data['sender_name'],0);
            $mail->Subject = $email_data['subject'];
            $data['message'] = $email_data['message'];
            $data['order_details'] = $order_template_data['order_details'];
            $data['cart_data'] = $order_template_data['cart_data'];

            if (isset($email_data['cmp_logo'])) {
                $data['cmp_logo'] = @$email_data['cmp_logo'];
            } else {
                $data['cmp_logo'] = @currentuserinfo()->cmp_logo;
            }

            $msg = $CI->load->view('email_templates/email_order_template', $data, true);
            //echo $msg;die;
            $mail->Body = $msg;

            if (@$email_data['from'] != '') {

                $mail->SetFrom(@$email_data['from'], @$email_data['sender_name'], '1');
            }

            if (@$email_data['to'] != '') {
                $arr_to = explode(',', @$email_data['to']);
                foreach ($arr_to as $to) {
                    $mail->AddAddress($to);
                }
            }
            if (@$email_data['cc'] != '') {
                $arr_cc = explode(',', @$email_data['cc']);
                foreach ($arr_cc as $cc) {
                    $mail->AddCC($cc);
                }
            }

            if (@$email_data['bcc'] != '') {
                $arr_bcc = explode(',', @$email_data['bcc']);
                foreach ($arr_bcc as $bcc) {
                    $mail->AddBCC($bcc);
                }
            }

            if (@$email_data['file'] != '') {
                if (is_array(@$email_data['file']) && count(@$email_data['file']) > 0) {
                    $arr_files = array();
                    $arr_files_name = array();
                    $arr_files = @$email_data['file'];
                    $i = 0;
                    if (is_array(@$email_data['file_name']) && count(@$email_data['file_name']) > 0) {
                        $arr_files_name = @$email_data['file_name'];
                        foreach ($arr_files as $file) {

                            $mail->AddAttachment($file, $arr_files_name[$i]);
                            $i++;
                        }
                    } else {
                        foreach ($arr_files as $file) {

                            $mail->AddAttachment($file);
                        }
                    }
                } else {
                    $CI->email->attach();
                    $mail->AddAttachment($email_data['file']);
                }
            }

            if ($mail->Send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
/* End of Function */





/**
 * send mobile one time password 
 * send one time password api function
 */
if (!function_exists('send_otp')) {

    function send_otp($content, $number) {
        $content = urlencode($content);
        $Url = "http://tra.smsmyntraa.com/API/WebSMS/Http/v1.0a/index.php?username=" . USERNAME . "&password=" . PASSWORD . "&sender=" . SENDERID . "&to=" . $number . "&message=" . $content . "&reqid=1&format={json|text}&route_id=Transactional&callback=&unique=0&sendondate=" . date('Y-m-d H:i:s') . "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        $errmsg = curl_error($ch);
        $cInfo = curl_getinfo($ch);
        curl_close($ch);
        return $output;
    }

}


if (!function_exists('array_to_excel')) {

    function array_to_excel($data, $filename = "") {
        if ($filename != "") {
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        }

        ob_start();
        $flag = false;
        foreach ($data as $row) {

            /*  if(!$flag) {
              // display field/column names as first row
              echo implode("\t", array_keys($row)) . "\n";
              $flag = true;
              } */
            // array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode("\t", array_values($row)) . "\n";
        }
    }

}

function convertNumber($number) {
    list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-") {
        $output = "negative ";
        $integer = ltrim($integer, "-");
    } else if ($integer{0} == "+") {
        $output = "positive ";
        $integer = ltrim($integer, "+");
    }

    if ($integer{0} == "0") {
        $output .= "zero";
    } else {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group = rtrim(chunk_split($integer, 3, " "), " ");
        $groups = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g) {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++) {
            if ($groups2[$z] != "") {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11]{0} == '0' ? " and " : ", "
                        );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0) {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++) {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;
}

function convertGroup($index) {
    switch ($index) {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3) {
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
        return "";
    }

    if ($digit1 != "0") {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0") {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0") {
        $buffer .= convertTwoDigit($digit2, $digit3);
    } else if ($digit3 != "0") {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2) {
    if ($digit2 == "0") {
        switch ($digit1) {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1") {
        switch ($digit2) {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else {
        $temp = convertDigit($digit2);
        switch ($digit1) {
            case "2":
                return "twenty $temp";
            case "3":
                return "thirty $temp";
            case "4":
                return "forty $temp";
            case "5":
                return "fifty $temp";
            case "6":
                return "sixty $temp";
            case "7":
                return "seventy $temp";
            case "8":
                return "eighty $temp";
            case "9":
                return "ninety $temp";
        }
    }
}

function convertDigit($digit) {
    switch ($digit) {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}

function get_datetime_by_defined_timezone($datetime, $timezone = NULL) {
    if ($timezone == '') {
        $timezone_dat = fieldByCondition("users", array('id' => currentuserinfo()->id), "timezone");
        if (!empty($timezone_dat)) {
            $timezone = $timezone_dat->timezone;
        } else {
            $timezone = date_default_timezone_get();
        }
        $date = new DateTime($datetime, new DateTimeZone(date_default_timezone_get()));
    } else {
        $date = new DateTime($datetime, new DateTimeZone($timezone));
    }


    //$date->format('Y-m-d H:i:s') . "\n";
    $date->setTimezone(new DateTimeZone($timezone));
    return $date->format('Y-m-d H:i:s');
}

function convert_datetime_by_defined_timezone($datetime, $timezone_from, $timezone_to) {
    $date = new DateTime($datetime, new DateTimeZone($timezone_from));
    //$date->format('Y-m-d H:i:s') . "\n";
    $date->setTimezone(new DateTimeZone($timezone_to));
    return $date->format('Y-m-d H:i:s');
}

function get_default_timezone_of_user() {
    $timezone_dat = fieldByCondition("users", array('id' => currentuserinfo()->id), "timezone");
    if (!empty($timezone_dat)) {
        $timezone = $timezone_dat->timezone;
    } else {
        $timezone = date_default_timezone_get();
    }
    return $timezone;
}

/**
 * Function for restore data
 */
if (!function_exists('restoreData')) {

    function restoreData($arr) {
        $CI = &get_instance();
        $table = $arr->table;
        $col1 = $arr->col1;
        if ($arr->col2) {
            $col2 = $arr->col2;
        }
        $whr[$col2] = $arr->id;
        $upd[$col1] = $arr->value;
        $CI->db->update($table, $upd, $whr);
//        echo $CI->db->last_query(); die;
        if ($CI->db->affected_rows()) {
            $res['status'] = 'success';
            $res['message'] = null;
        } else {
            $res['status'] = 'error';
            $res['message'] = $CI->db->_error_message();
        }
        return $res;
    }

}

/* End of Function */

/**
 * export_data
 *
 * This function give data on given condition 
 *
 * 	
 * @return array or boolean
 */
if (!function_exists('export_data')) {

    function export_data($conArr, $field) {
        $CI = &get_instance();
        $CI->db->select($field, false);
        $CI->db->from($conArr['table']);
        if (!empty($conArr['column1'])) {
            $CI->db->where_in($conArr['column1'], $conArr['ids']);
        }
        $CI->db->order_by($conArr['column1'], 'desc');
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
/* End of Function */



//=============================================Export=================================================	
if (!function_exists('array_to_exl')) {

    function array_to_exl($header, $excellists, $download = "") {
        $num = 0;
        $data = NULL;
        if ($excellists != null) {
            foreach ($excellists as $row) {
                $num++;
                $line = $num . "\t";
                foreach ($row as $value) {
                    if (!isset($value) || trim($value) == "") {
                        $value = "\t";
                    } else {
                        $value = str_replace('"', '""', $value);
                        $value = '"' . $value . '"' . "\t";
                    }
                    $line .= $value;
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);
            if (trim($data) == "") {
                $data = "\n(0)Records Found!\n";
            }
        }
        if ($download != "") {
            header('Content-Type: application/msexcel');
            header('Content-Disposition: attachement; filename="' . $download . '"');
            header("Pragma: no-cache");
            header("Expires: 0");
            print "$header\n$data";
        }
    }

//=============================================End Export=================================================	
}
/* End of Function */


/*

 * function:: generate password 
 * author:: Arvind Soni    
 * This function generate random 6 digit number  
 */
if (!function_exists('generate_password')) {

    function generate_password() {
        return random_string('numeric', 6);
    }

}
/* End of Function */




/*

 * function:: get_cat
  author:: Dharmendra Pal
 * This function single row  */

function get_cat($table, $id = NULL) {
    $CI = & get_instance();

    $CI->db->where('id', $id);
    $r = $CI->db->get($table);

    return $r->row();
}

/* End of Function */


/*
 * function:: get image
  author:: Dharmendra Pal
 * This function get image */
if (!function_exists('get_file')) {

    function get_file($path = null, $filename = null) {
        if (isset($path) && isset($filename)) {
            $uploaded_path = base_url() . "uploads/" . $path;
            $filename = $uploaded_path . '/' . $filename;
        } else {
            $filename = 'uploads/placeholder.png';
        }

        return $filename;
    }

}
/* End of Function */



/*
 * function:: get image thumb
  author:: Dharmendra Pal
 * This function get image */
if (!function_exists('get_image_thumb')) {

    function get_image_thumb($filename = null, $type) {

        /* type= _thumb, 40x40, 100x100, 200x200 */
        if ($type && $filename) {
            $image_expl = explode('.', $filename);
            $thumb_name = $image_expl[0] . "_" . $type . '.' . @$image_expl[1];
        } else {
            $thumb_name = '';
        }

        return $thumb_name;
    }

}
/* End of Function */

/*
 * function:: get image thumb with ext a
  author:: Dharmendra Pal
 * This function get image */
if (!function_exists('get_image_thumb_a')) {

    function get_image_thumb_a($filename = null, $type) {

        /* type= _thumb, 40x40, 100x100, 200x200 */
        if ($type && $filename) {
            $image_expl = explode('.', $filename);
            $thumb_name = $image_expl[0] . "a_" . $type . '.' . @$image_expl[1];
        } else {
            $thumb_name = '';
        }

        return $thumb_name;
    }

}
/* End of Function */

function createUrlByTitleAndId($title, $id) {
    return RemoveSpecialChar($title) . "-" . ID_encode($id);
}

/*
 * function:: 
  author:: Dharmendra Pal
 * This function get image */
if (!function_exists('RemoveSpecialChar')) {

    function RemoveSpecialChar($value) {
        $result = preg_replace('/[^a-zA-Z0-9_]/s', '_', $value);

        return $result;
    }

}
/* End of Function */

if (!function_exists('getIdByUrl')) {

    function getIdByUrl($url) {
        $url_break = explode('-', $url);
        $Id = ID_decode($url_break[1]);
        return $Id;
    }

}


/*
 * function:: get_days
 * author:: Arvind Soni
 *  This function get days
 *  */
if (!function_exists('get_days')) {

    function get_days() {
        $days = array();
        $days['Monday'] = 'Monday';
        $days['Tuesday'] = 'Tuesday';
        $days['Wednesday'] = 'Wednesday';
        $days['Thursday'] = 'Thursday';
        $days['Friday'] = 'Friday';
        $days['Saturday'] = 'Saturday';
        $days['Sunday'] = 'Sunday';
        return $days;
    }

}
/* End of Function */

/*
 * function:: get_hours
 * author:: Arvind Soni
 *  This function get hours
 *  */
if (!function_exists('get_hours')) {

    function get_hours() {
        $hours = array();
        $hour = 0;
        while ($hour < 24) {
            $hours[date('H:i:s', mktime($hour, 0, 0, 1, 1, 2011))] = date('H:i', mktime($hour, 0, 0, 1, 1, 2011));
            $hour++;
        }
        return $hours;
    }

}
/* End of Function */

/*
 * function:: get_radious
 * author:: Arvind Soni
 *  This function get radious
 *  */
if (!function_exists('get_radious')) {

    function get_radious() {
        $radious = array();
        $r = 100;
        while ($r < 2000) {
            $radious[$r] = $r . ' M';
            $r = $r + 100;
        }
        return $radious;
    }

}
/* End of Function */
if (!function_exists('generate_otp')) {

    function generate_otp() {
        $otp = rand(1000, 9999);
        return $otp;
    }

}




function get_location_by_session($session_index, $type = '') {
    $CI = & get_instance();
    $locationSession = $CI->session->userdata($session_index);
    if ($type) {
        return $locationSession[$type];
    } else {
        $locationSession = $CI->session->userdata($session_index);
        return $locationSession;
    }
}

function get_cart_stall_id() {
    $CI = & get_instance();
    $stall_data_session = $CI->session->userdata('stall_data_session');
    return $stall_data_session['stall_id'];
}


/**
 * has user permission
 * this function is used to check user permission
 * */
function getUserPermissions() {
    $CI = & get_instance();
    $userData = @currentuserinfo();
    //$userinfo = $this->session->all_userdata();
     /* $r1 = explode(',', $userData->other_roles);
    $r2 = array($userData->user_type);
       
    $role_ids = array_merge($r1, $r2); */
    $role_ids = $CI->session->userdata['userinfo']->id;
    $query = $CI->db->select("user.*")
            ->from("kc_users user")
            ->where(array("user.status" => "active"))
			->where("user.id",$role_ids )
            ->get()
            ->result();
			
    $tmpA = array();
    foreach ($query as $value) {
        $per = $rr[] =  !empty($value->permission)?json_decode($value->permission, true):array();
        $tmpA = array_merge_recursive($tmpA, $per);
    }
	
	return $tmpA;
}

/**
 * has user permission
 * this function is used to check user permission
 * */
function has_permission($controller, $action) {
    
    $CI = & get_instance();
    $userData = @currentuserinfo();
    $role_ids = $CI->session->userdata['userinfo']->id;
    $role_ids1 = $CI->session->userdata['userinfo']->department_id;
    $query1 = $CI->db->select("user.*")
            ->from("kc_users user")
            ->where(array("user.status" => "active"))
            ->where("user.id", $role_ids)
            ->get()
            ->result();
    $query2 = $CI->db->select("permission.*")
            ->from("kc_role_permission permission")
            ->where("permission.dept_id", $role_ids1)
            ->get()
            ->result();

    if ($query1[0]->permission=='[]' || $query1[0]->permission=='') {
        $query = $query2;
    } else {
        $query = $query1;
    }


    $tmpA = array();
    foreach ($query as $value) {
        $per = $rr[] = !empty($value->permission) ? json_decode($value->permission, true) : array();
        $tmpA = array_merge_recursive($tmpA, $per);
    }
    
    if (array_key_exists($controller, $tmpA)) {
        if (!array_key_exists($action, $tmpA[$controller])) {
            return FALSE;
        } else {
            return TRUE;
        }
    } else {
        return FALSE;
    }
}

function social_media() {
    $CI = &get_instance();
    $CI->db->select('id,social_media_name,link');
    $CI->db->from('fs_social_media');
    $query = $CI->db->get();
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}

/* End of Function */


/**
 * unique_email
 *
 * This function to check  unique user email
 * @param string
 *  	
 */
if (!function_exists('unique_email')) {

    function unique_email($email = '', $id = '') {

        $CI = &get_instance();
        $CI->db->select('id');
        $CI->db->where('email', $email);

        if ($id != '') {
            $CI->db->where("id != ", "$id");
        }
        $query = $CI->db->get("fs_newbee");

        if ($query->num_rows()) {

            return 200;
        } else {

            return 404;
        }

        return 404;
    }

}
/* End of function */

/**
 * unique_mobile
 *
 * This function to check  unique mobile
 * @param string
 *  	
 */
if (!function_exists('unique_mobile')) {

    function unique_mobile($mobile = '', $id = '') {

        $CI = &get_instance();
        $CI->db->select('id');
        $CI->db->where('mobile', $mobile);

        if ($id != '') {
            $CI->db->where("id != ", "$id");
        }
        $query = $CI->db->get("fs_newbee");

        if ($query->num_rows()) {

            return 200;
        } else {

            return 404;
        }

        return 404;
    }

}
/* End of function */

/**
 * get_adminInfo
 *
 * This function to fetch admin Info
 * @param string
 *  	
 */
if (!function_exists('get_adminInfo')) {

    function get_adminInfo() {
        $CI = &get_instance();
        $CI->db->select("id,concat(first_name,' ',last_name) as user_name,first_name,last_name,email,mobile_number,profile_image");
        $CI->db->where('user_type', '1');
        $query = $CI->db->get("fs_users");
        if ($query->num_rows()) {
            $rs_data['result'] = $query->row();
            $rs_data['status'] = "success";
        } else {
            $rs_data['result'] = '';
            $rs_data['status'] = "error";
        }
        return $rs_data;
    }

}
/* End of function */

//===================valid email==================================================//
if (!function_exists('_is_valid_email')) {

    function _is_valid_email($email = NULL) {
        if ($email) {
            if (preg_match("/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $email)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}

//==================Get Table Data ===================//
if (!function_exists('_get_table_details')) {

    function _get_table_details($field_id = NULL, $match_field = NULL, $table = NULL, $return_field = NULL) {
        if ($field_id && $match_field && $table) {
            $CI = &get_instance();
            $qry = $CI->db->select($return_field)->get_where($table, array($match_field => $field_id));
            if ($qry->num_rows()) {
                if (strpos($return_field, ',')) {
                    return $qry->row();
                } else {
                    return ucwords($qry->row()->$return_field);
                }
            }
            return false;
        }
    }

}

//======================valid password========================//

if (!function_exists('_is_valid_password')) {

    function _is_valid_password($password = NULL) {
        if ($password) {
            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/", $password)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

}

//==========end code for get user name======================//

//============code for get service ====================//
 if (!function_exists('_get_all_service')) {   
function _get_all_service(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from('services');
        $CI->db->where("status",1);
        $CI->db->where("fetch_id",4);
        $query = $CI->db->get()->result();
        return $query;
    }

 }
//============end code for get service====================//

//============code for get service ====================//
 if (!function_exists('_get_all_testing_service')) {   
function _get_all_testing_service(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from('services');
        $CI->db->where("status",1);
        $CI->db->where("fetch_id",5);
        $query = $CI->db->get()->result();
        return $query;
    }

 }
//============end code for get service====================//


//============code for get testing service ====================//
 if (!function_exists('_get_all_testing_service')) {   
function _get_all_testing_service(){
		$CI = &get_instance();
		$CI->db->select('*');
        $CI->db->from('services');
        $CI->db->where("status",1);
        $CI->db->where("fetch_id",5);
        $query = $CI->db->get()->result();
        return $query;
    }

 }
//============end code for get testing  service====================//

if (!function_exists('ajax_layout')) {

    function ajax_layout($page_name=null, $data = array()) {
        $CI = & get_instance();
        $CI->load->view($page_name, $data);
    }

}


//=============end code get service==============//

//============ code for get all customer process====================//
if (!function_exists('_get_customer_process')) {

    function _get_customer_process($id) {
       $CI = &get_instance();
       $CI->db->select('id,process_name');
        $CI->db->from('process');
        $CI->db->where("customer_id",$id);
        $CI->db->or_where("FIND_IN_SET($id,asigned_customer_id)!=", 0);
        $CI->db->where("status",0);
         $query = $CI->db->get()->result();
        return $query;
    }

}
//=============end code for check  invoice time==============//

//============ code for get all sub customer process====================//
if (!function_exists('_get_sub_customer_process')) {

    function _get_sub_customer_process($id) {
        $CI = &get_instance();
        $CI->db->select('process_id');
        $CI->db->from('subcustomer_process');
        $CI->db->where("customer_id", $id);
        $query = $CI->db->get()->row();
        
        $processId = explode(',',$query->process_id);
        $CI->db->select('id,process_name');
        $CI->db->from('process');
        $CI->db->where_in("id", $processId); 
        $CI->db->where("status", 0);
        $processname = $CI->db->get()->result();
        

        return $processname;
    }

}
//=============end code for get all sub customer process==============//




//============ code for get all customer process====================//
if (!function_exists('_get_service_name')) {

    function _get_service_name($id) {
       $CI = &get_instance();
       $CI->db->select('service_name');
        $CI->db->from('services');
        $CI->db->where("id",$id);
         $query = $CI->db->get()->row();
		 //echo $CI->db->last_query();
        return $query;
    }
}
//=============end code for check  invoice time==============//
//============ code for get all customer process====================//
if (!function_exists('_get_testing_service_name')) {

    function _get_testing_service_name($id) {
		$CI = &get_instance();
		$CI->db->select('testing_name');
        $CI->db->from('testing');
        $CI->db->where("id",$id);
        $query = $CI->db->get()->row();
	    return $query;
    }
}
//=============end code for check  invoice time==============//


//============ code for get all customer process====================//
if (!function_exists('_get_testing')) {

    function _get_testing() {
        $CI = &get_instance();
        $CI->db->select('id,testing_name');
        $CI->db->from('testing');
        $query = $CI->db->get()->result();
        return $query;
    }

}
//=============end code for check  invoice time==============//

//=============start code for check customer name ==============//

if(!function_exists('_get_user')){
	function _get_user(){
		if (get_cookie('qsa_certificate_key', false) != null) {
            $newcertificate = get_cookie('qsa_certificate_key', false);
        } 
		
	    if(isset($newcertificate) && !empty($newcertificate)){
			$newcertificate = explode('-',$newcertificate);
			$customerId = ID_decode($newcertificate[0]);
			$CI = &get_instance();
			$CI->db->select('id,full_name');
			$CI->db->from('users');
			$CI->db->where("id",$customerId);
			$query = $CI->db->get()->row();
			return $query;
		}else{
			// $CI->session->sess_destroy();
            // redirect(base_url('auth/auth/login?token=nocertificate'));
        }
				
	}
}
//=============end code for check customer name ==============//
//=============start code for check customer name ==============//

if(!function_exists('_get_qa_customer')){
	function _get_qa_customer(){
		$CI = &get_instance();
		if (get_cookie('qa_certificate_key', false) != null) {
            $newcertificate = get_cookie('qa_certificate_key', false);
        } 
	    if(isset($newcertificate) && !empty($newcertificate)){
			$newcertificate = explode('-',$newcertificate);
			$customerId = ID_decode($newcertificate[0]);
			$CI = &get_instance();
			$CI->db->select('id,full_name');
			$CI->db->from('users');
			$CI->db->where("id",$customerId);
			$query = $CI->db->get()->row();
			return $query;
		  
        }else{
			
        }
				
	}
	
	
}
//=============end code for check customer name ==============//

//=============start code for check consultant name ==============//

if(!function_exists('_get_consultant_customer')){
	function _get_consultant_customer(){
		if (get_cookie('consultant_certificate_key', false) != null) {
            $newcertificate = get_cookie('consultant_certificate_key', false);
        } 
	    if(isset($newcertificate) && !empty($newcertificate)){
			$newcertificate = explode('-',$newcertificate);
			$customerId = ID_decode($newcertificate[0]);
			$CI = &get_instance();
			$CI->db->select('id,full_name');
			$CI->db->from('users');
			$CI->db->where("id",$customerId);
			$query = $CI->db->get()->row();
			return $query;
		}else{
			 $this->session->sess_destroy();
             redirect(base_url('auth/auth/login?token=nocertificate'));
        }
				
	}
	
	
}
//=============end code for check consultant name ==============//


//=========code for get total customer for asign=============//
if (!function_exists('_get_total_customer')) {

    function _get_total_customer($field,$id) {
         $CI = &get_instance();
         $CI->db->select('*');
         $CI->db->from('compliance_project');
         $CI->db->where($field,$id);
         $query = $CI->db->get()->num_rows();
         return $query;
      }

}
//========end code for get total customer for asign=========//

//=========code for get process name=============//
if (!function_exists('_get_process_name')) {

    function _get_process_name($id) {
         $CI = &get_instance();
         $CI->db->select('id,process_name');
         $CI->db->from('process');
         $CI->db->where('id',$id);
         $query = $CI->db->get()->row();
         return $query;
        }

}
//========end code for get process name=========//

//========code for check archived existance==========//

if (!function_exists('_check_archived')) {

    function _check_archived($id) {
         $CI = &get_instance();
         $CI->db->select('*');
         $CI->db->from('archived');
         $CI->db->where('process_id',$id);
         $query = $CI->db->get()->num_rows();
         return $query;
        }

}

//========end code for check archived existance=======//
