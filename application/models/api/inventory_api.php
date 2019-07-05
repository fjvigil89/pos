<?php
class Inventory_api extends CI_Model 
{	
	function insert($inventory_data)
	{
		$api = $this->load->database('shop_api', TRUE); 
		if(is_numeric($inventory_data['trans_inventory']))
		{
			return $api->insert('inventory',$inventory_data);
		}
		
		return TRUE;
	}
	
	function get_inventory_data_for_item($item_id, $location_id = false)
	{
		$api = $this->load->database('shop_api', TRUE); 
		if (!$location_id)
		{
			$location_id=1;
		}
		$api->from('inventory');
		$api->where('trans_items',$item_id);
		$api->where('location_id',$location_id);
		$api->order_by("trans_date", "desc");
		return $api->get();		
	}
}

?>