<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Country Model 
 *
 * @package		Archive
 * @subpackage          Models
 * @category            Archive
 * @author              Mukul Kumar
 * @website		http://www.tekshapers.com
 * @company             Tekshapers Inc
 * @since		Version 1.0
 */
class Archive_mod extends CI_Model {

    var $archive_table = "archived";
    
    public function archived_list(){
        $this->db->select('process_id');
        $this->db->from('archived');
        $query = $this->db->get()->result();
       $processId = array();
        foreach($query as $processid){
            $processId[] = $processid->process_id;
            
        }
        if(isset($processId) && !empty($processId)){
        $this->db->select('*,(select full_name from users where id=compliance_project.customer_id) AS CustomerName,(select full_name from users where id=compliance_project.qsa_id) AS QSAName,(select full_name from users where id=compliance_project.consultant_id) AS ConsultantName,(select full_name from users where id=compliance_project.qa_id) AS QaName,(select process_name from process where id = compliance_project.process_id) AS ProcessName');
        $this->db->from('compliance_project');
        $this->db->where_in('process_id',$processId);
        $this->db->group_by('process_id');
        $process = $this->db->get()->result();
         return $process;
        
        } else {
            return FALSE;    
        }
        
        
    }
    
}