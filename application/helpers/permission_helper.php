<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**

 * Permission helper
 *
 * @package		Permission Helper
 * @subpackage          Helpers
 * @auothor	   	Ankit Rajput
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc 
 * @since		Version 1.0
 */

/**
 * check_permission
 * this function check permission of access module for the current user
 *
 */
if (!function_exists('check_permission')) {
    function check_permission() 
    {
        $CI = &get_instance();
        $CI->lang->load('leftnav',get_language());
        
    	//create array permission as a key and user type as a value
        $arr        =       array(
            'admin'                 =>  array('0'),
            'dashboard'             =>  array('1','2','3','4'),
            'categories'            =>  array('1'),
            'user/profile_account'  =>  array('1','2','3','4'),
            'user/user'             =>  array('1','2','3','4'),
            'user/coordinators'     =>  array('1'),
            'user/tutor'            =>  array('4'),
            'user/learner'          =>  array('1','2'),
            'courses'               =>  array('4'),
            'topics'                =>  array('4'),
            'semesters'             =>  array('1'),
            'groups'                =>  array('4','2'),
            'banks'	=>  array('4'),
            'banks/questions_bank'	=>  array('4'),
            'banks/quizzes_bank'	=>  array('4'),
            'banks/question_view'	=>  array('4'),
            'banks/quiz_view'	=>  array('4'),
            'questions'             =>  array('2'),
            'quizzes'               =>  array('2','3'),
            'learners'              =>  array('3'),
            'pages'                 =>  array('0','1'),
            'contacts'              =>  array('0','1'),
            'community/forums'      =>  array('1','3','2'),
            'community/messages'    =>  array('3','2'),
            'community/chats'       =>  array('3','2'),
        );
        $url=uri_segment(1);
        $url= ($url == 'user'||$url == 'community') ? $url.'/'.uri_segment(2) : $url; 
        if(!in_array(currentuserinfo()->user_type,$arr[$url])) //check permission of access module for the current user
        {
            show_error(lang('permission_denied'));
        }
    }
}

