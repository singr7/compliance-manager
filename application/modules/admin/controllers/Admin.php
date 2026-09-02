<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    /**
     *  Admin Controller
     *
     * @package        Admin
     * @category    Admin
     * @author        Dharmendra Pal
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
		
        //validate_admin_login();
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
        redirect(base_url('admin/auth/login'));

    }

    /*End of Function*/
	

    
    
    
}
