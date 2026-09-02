<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Hr Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Mohit sharma 
 * @website		http://www.hr_enabler.com
 * @company     Tekshapers Inc
 * @since		Version 1.0
 */ 
//------------------------------------------------------------------------
function function_list()
  {
    $CI=& get_instance();
    $CI->db->select('id,function_name');
    $CI->db->from('functions');
    $CI->db->order_by('function_name');
    $CI->db->where('status','1');
    $result=$CI->db->get()->result();
    $function_list=array();
    $function_list['']='Select Function';
    foreach($result as $rows)
    {
      $function_list[$rows->id]= $rows->function_name;
    }
    return $function_list;
  }
//------------------------------------------------------------------------
  function events_category_list()
  {
    $CI=& get_instance();
    $CI->db->select('id,event_type');
    $CI->db->from('fs_events_type_category');
    $CI->db->order_by('event_type');
    $CI->db->where('status','1');
    $result=$CI->db->get()->result();
    $event_list=array();
    $event_list['']='Select Event Type';
    foreach($result as $rows)
    {
      $event_list[$rows->id]= $rows->event_type;
    }
    return $event_list;
  }
  
  function events_types_category_list()
  {
    $CI=& get_instance();
    $CI->db->select('id,category');
    $CI->db->from('fs_events_category');
    $CI->db->order_by('category');
    $CI->db->where('status','1');
    $result=$CI->db->get()->result();
    $event_list=array();
   // $event_list['']='Select Event Category';
    foreach($result as $rows)
    {
      $event_list[$rows->id]= $rows->category;
    }
    return $event_list;
  }
//-----------------------------------------------------------------------------  
function status($val){
   if($val){
     return "Active";
   }
   return "InActive"; 
}
//----------------------------------------------------------------------------
function currency_list(){
    $CI=& get_instance();
    $CI->db->select('id,currency_name');
    $CI->db->from('currency');
    $CI->db->order_by('currency_name');
    $CI->db->where('status','1');
    $result=$CI->db->get()->result();
    $currency_list=array();
    $currency_list['']='Select Currency';
    foreach($result as $rows)
    {
      $currency_list[$rows->id]= $rows->currency_name;
    }
    return $currency_list; 
}
//-----------------------------------------------------------------------
  function event_category_by_id($id=null)
  {
    $CI=& get_instance();
    $CI->db->select('id,event_type');
    $CI->db->from('fs_events_type_category');
    $CI->db->limit(1);
    $CI->db->where('status','1');
    $CI->db->where('id',$id);
    $row=$CI->db->get()->row();
    return $row->event_type;
  }
//-----------------------------------------------------------------------------
function fee($val){
   if($val==1){
     return "No Fee";
   }
    if($val==2){
     return "Paid";
   }
    if($val==3){
     return "By Invitation";
   }
   
   return "---"; 
}
//-----------------------------------------------------------------------------
?>