<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//error_reporting(0);
class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->controller_name = $this->router->fetch_class();
		$this->method_name = $this->router->fetch_method();
		if (!@currentuserinfo()->id)
		{
			redirect('/admin/auth/login');
		}
		
		
    }

  
}
?>
