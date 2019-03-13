<?php
class Denomination_currency extends CI_Model
{
	/*
	Gets information about a particular tier
	*/
	function get_info($currency_id)
	{
		$this->db->from('denomination_currency');	
		$this->db->where('id',$currency_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			$tier_obj = new stdClass;
			
			//Get all the fields from price_tiers table
			$fields = $this->db->list_fields('denomination_currency');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$tier_obj->$field='';
			}
			
			return $tier_obj;
		}
	}
	
	/*
	Determines if a given tier_id is a tier
	*/
	function exists($currency_id)
	{
		$this->db->from('denomination_currency');	
		$this->db->where('id',$currency_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function get_all()
	{
		$this->db->from('denomination_currency');
		$this->db->order_by('id');
		return $this->db->get();
	}

	function count_all()
	{
		$this->db->from('price_tiers');
		return $this->db->count_all_results();
	}
	
	/*
	Inserts or updates a tier
	*/
	function save(&$currency_data,$currency_id=false)
	{

		if (!$currency_id or !$this->exists($currency_id))
		{

			if($this->db->insert('denomination_currency',$currency_data))
			{
				$currency_data['id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('id', $currency_id);
		return $this->db->update('denomination_currency',$currency_data);
	}
	
	function delete($currency_id)
	{
	
		$this->db->where('id', $currency_id);
		return $this->db->delete('denomination_currency'); 
	}
	

}
?>