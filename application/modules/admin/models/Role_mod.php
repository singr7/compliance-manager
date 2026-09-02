<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Role_mod extends CI_Model {
    

     var $tbl_users = "kc_roles";
     var $tbl_role  = "kc_role_permission";
     /*
     * Constructor
     */
     function __construct() {
         parent::__construct();
    }

    /*
     * function for get role listing
     */
    
    public function department_listing(){
        $this->db->select('*');
        $this->db->from('kc_departments');
        $query = $this->db->get();
        return $query->result();
    }
    
    /*
     * function for view role details
     */
    
    public function view($id=null){
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get("kc_departments")->row();
        return $query;
    }
    
    //=============Save permission===========//
	public function save_permission($role_id)
	{
		$result['empty'] = TRUE;
		$names=$this->input->post('permission');
               
		$actions=array();
		if(isset($names) && !empty($names)) 
		{
			foreach($names as $key=>$name)
			{
				$actions[$key]=$name;
			}
		}
		$data['permission']= json_encode($actions);
                $data['dept_id']= $_POST['department'];
                $data['cretaed_date']= current_date();
               
                $this->db->where('id' , $role_id);
		$qry = $this->db->update($this->tbl_role, $data);
		if($qry)
		{
			$result['empty'] = FALSE;	
		}
		return $result;
	}
//===========Close save permission========//
        
        
        //============Get Role Permission===============//
	public function get_permission($id=NULL)
	{
		$result=$this->db->select('id,permission')->get_where($this->tbl_role, array('dept_id' =>$id));
		if($result->num_rows())
        {
            $grppermarr=json_decode($result->row()->permission);
            if(isset($grppermarr) && !empty($grppermarr))
            {
                $rows=get_object_vars(json_decode($result->row()->permission));
	  	        $obj_controller=array();
	  	        $obj_method=array();
	  	        foreach($rows as $key=>$val)
	  	        {
		 	        $obj_method[$key]=get_object_vars($val);
		 	        //echo '<pre>';pr($obj_method); die;
		 	        foreach($obj_method as $mkey=>$mval)
		 	        {
		 		       foreach($mval as $smkey=>$smval)
		 		       {
						   if(isset($smval) && !empty($smval))
						   {
							   $obj_array[$mkey][$smkey]=$smval;
					           //$obj_array[$mkey][$smkey]=get_object_vars($smval);
                            }
				        }
					}
				}
				return $obj_array;
            }
            return false;
        } 
		else
        {
            return false;
        } 
	}
}  
