<?php
class Location_online extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/*
	Returns all the locations online
	*/
	function get_all()
	{
		$query = $this->db->get('shop_config');
		return $query->result();
	}
	
	public function update($data=array()) 
	{

		foreach($data as $key => $value)
		{
		  $this->db->where('key',$key);
		  $status=$this->db->update('shop_config', array('value'=>$value));
		}
			
			
   return $status;
	    
	}

}
