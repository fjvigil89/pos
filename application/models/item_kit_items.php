<?php
class Item_kit_items extends CI_Model
{
	function get_all($date="0000-00-00 00:00:00")
	{
		$this->db->select('item_kit_items.*');
		$this->db->from('item_kit_items');
		$this->db->join('item_kits', 'item_kit_items.item_kit_id = item_kits.item_kit_id ');
		if($date=="0000-00-00 00:00:00"){
			$this->db->where($this->db->dbprefix('item_kits').".deleted",0);
		}else{
			$this->db->where('update_item_kit_item > ',$date);
			$this->db->or_where('update_kit > ',$date);
		}
		

		
		return $this->db->get();
	}
	/*
	Gets item kit items for a particular item kit
	*/
	function get_info($item_kit_id)
	{
		$this->db->from('item_kit_items');
		$this->db->where('item_kit_id',$item_kit_id);
		//return an array of item kit items for an item
		return $this->db->get()->result();
	}
	
	/*
	Inserts or updates an item kit's items
	*/
	function save(&$item_kit_items_data, $item_kit_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->delete($item_kit_id);
		
		foreach ($item_kit_items_data as $row)
		{
			$row['item_kit_id'] = $item_kit_id;
			$this->db->insert('item_kit_items',$row);		
		}
		
		$this->db->trans_complete();
		return true;
	}
	
	/*
	Deletes item kit items given an item kit
	*/
	function delete($item_kit_id)
	{
		return $this->db->delete('item_kit_items', array('item_kit_id' => $item_kit_id)); 
	}
	
	/**
	 * Get kits with item
	 * @param type $ite_id
	 * @return type
	 */
	function get_kits_have_item($item_id)
	{
	    $this->db->from('item_kit_items');
	    $this->db->where('item_id',$item_id);	    
	    return $this->db->get()->result_array();
	}
}
?>
