<?php
class Additional_item_seriales extends CI_Model
{
	
	function get_item_serales_unsold($item_id){
		$this->db->from('additional_item_seriales');
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();	
		return $query;			
	}
	function save($item_id, $additional_item_seriales)
	{
		$this->db->trans_start();

		$this->db->delete('additional_item_seriales', array('item_id' => $item_id));
		
		foreach($additional_item_seriales as $item_serial)
		{
			if ($item_serial!='')
			{
				$this->db->insert('additional_item_seriales', 
				array('item_id' => $item_id, 'item_serial' => $item_serial));
			}
		}
		
		$this->db->trans_complete();
		
		return TRUE;
	}
	function save_one($item_id, $item_serial)
	{		
		if ($item_serial!=''and $item_serial!=null)
		{
			return	$this->db->insert('additional_item_seriales', 
			array('item_id' => $item_id, 'item_serial' => $item_serial));
		}		
		return TRUE;
	}
	
	function delete($item_id)
	{
		return $this->db->delete('additional_item_seriales', array('item_id' => $item_id));
	}
	function delete_serial($item_id,$item_serial)
	{
		return $this->db->delete('additional_item_seriales',
		 array('item_id' => $item_id,
				"item_serial"=>$item_serial)
			);
	}
	
	function get_item_id($item_serial)
	{
		$this->db->from('additional_item_seriales');
		$this->db->where('item_serial',$item_serial);
		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->item_id;
		}		
		return FALSE;
	}
	function get_all()
	{
		$table_serial = $this->db->dbprefix('additional_item_seriales');
		$this->db->select($table_serial.".*");
		$this->db->from('items');
		$this->db->join('additional_item_seriales', 'additional_item_seriales.item_id = items.item_id ');
		$this->db->where('deleted',0);
		return  $this->db->get();
	}
	
}
?>
