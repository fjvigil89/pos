<?php
class Additional_item_numbers extends CI_Model
{
	function get_all($date="0000-00-00 00:00:00")
	{
		$this->db->select('additional_item_numbers.*');
		$this->db->from('additional_item_numbers');
		$this->db->join('items', 'items.item_id = additional_item_numbers.item_id');
		if($date=="0000-00-00 00:00:00"){
			$this->db->where($this->db->dbprefix('items').'.deleted', 0);
		}else{
			$this->db->where('update_additional_item_number  > ',$date);			
		}
		
		
		return $this->db->get();
	}
	/*
	Returns all the item numbers for a given item
	*/
	function get_item_numbers($item_id)
	{
		$this->db->from('additional_item_numbers');
		$this->db->where('item_id',$item_id);
		return $this->db->get();
	}
	
	function save($item_id, $additional_item_numbers)
	{
		$this->db->trans_start();

		$this->db->delete('additional_item_numbers', array('item_id' => $item_id));
		
		foreach($additional_item_numbers as $item_number)
		{
			if ($item_number!='')
			{
				$this->db->insert('additional_item_numbers', array('item_id' => $item_id, 'item_number' => $item_number));
			}
		}
		
		$this->db->trans_complete();
		
		return TRUE;
	}
	
	function delete($item_id)
	{
		return $this->db->delete('additional_item_numbers', array('item_id' => $item_id));
	}
	
	function get_item_id($item_number)
	{
		$this->db->from('additional_item_numbers');
		$this->db->where('item_number',$item_number);

		$query = $this->db->get();

		if($query->num_rows() >= 1)
		{
			return $query->row()->item_id;
		}
		
		return FALSE;
	}
}
?>
