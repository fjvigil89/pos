<?php
class Register extends CI_Model
{
	/*
	Gets information about a particular register
	*/
	function get_info($register_id)
	{
		$this->db->from('registers');	
		$this->db->where('register_id',$register_id);
		$query = $this->db->get();		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			$register_obj = new stdClass;			
			//Get all the fields from registers table
			$fields = $this->db->list_fields('registers');			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$register_obj->$field='';
			}			
			return $register_obj;
		}
	}
	
	function get_register_name($register_id)
	{
		$info = $this->get_info($register_id);		
		if ($info && $info->name)
		{
			return $info->name;
		}		
		return false;
	}
	
	/*
	Determines if a given register_id is a register
	*/
	function exists($register_id)
	{
		$this->db->from('registers');	
		$this->db->where('register_id',$register_id);
		$query = $this->db->get();		
		return ($query->num_rows()==1);
	} 
	
	function get_all($location_id = false)
	{
		if (!$location_id) {

			$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('registers');
		$this->db->where('location_id', $location_id);
		$this->db->where('deleted', 0);
		$this->db->order_by('register_id');
	
		return  $this->db->get();
	}
	function get_all_indexdb()
	{			
		$this->db->from('registers');
		$this->db->where('deleted', 0);	
		return  $this->db->get();
	}
	function get_all_store()
	{			
		$this->db->from('registers');
		$this->db->where('deleted', 0);
		$this->db->order_by('register_id');	
		return  $this->db->get()->result();
	}

	function get_registers()
	{
		$registers = array();
		
		foreach ($this->get_all()->result() as $key => $register) {
			
			$registers[$register->register_id] = $register->name;
		}

		return $registers;
	}

	function get_default()
	{
		return $this->get_all()->row();		
	}

	function count_all($location_id = false)
	{
		if (!$location_id)
		{
			$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('registers');
		$this->db->where('location_id', $location_id);
		$this->db->where('deleted', 0);
		return $this->db->count_all_results();
	}
	
	/*
	Inserts or updates a register
	*/
	function save(&$register_data,$register_id=false)
	{
		if (!$register_id or !$this->exists($register_id))
		{
			if($this->db->insert('registers',$register_data))
			{
				$register_data['register_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('register_id', $register_id);
		return $this->db->update('registers',$register_data);
	}
	
	function delete($register_id)
	{
		$this->db->where('register_id', $register_id);
		return $this->db->update('registers', array('deleted' => 1));
	}
	
	function is_register_log_open()
	{
		$register_id = $this->Employee->get_logged_in_employee_current_register_id();

		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('register_id',$register_id);
		$this->db->where('deleted', 0);
		$query = $this->db->get();
		if($query->num_rows())
		return true	;
		else
		return false;
	
	 }
}
?>
