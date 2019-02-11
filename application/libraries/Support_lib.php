<?php
class Support_lib
{
	var $CI;
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		
    }
    function get_id_customer() 
	{
		return $this->CI->session->userdata('id_customer_support') ? $this->CI->session->userdata('id_customer_support') : '';
	}
	function set_id_customer($id_customer_support) 	
	{
		$this->CI->session->set_userdata('id_customer_support', $id_customer_support);
		
	}
	
}
?>