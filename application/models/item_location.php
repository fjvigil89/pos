<?php
class Item_location extends CI_Model
{
	function get_tier_price_all()
	{
		$this->db->select('location_items_tier_prices.*');
		$this->db->from('location_items_tier_prices');
		$this->db->join('items', 'items.item_id = location_items_tier_prices.item_id');
		$this->db->where($this->db->dbprefix('items').'.deleted', 0);
					
		return $this->db->get();

	}
		

	function get_all_for_indexeDb($limit=1000,$offset=0,$date)
	{
		
		$this->db->select('location_items.*');
		$this->db->from('location_items');
		$this->db->join('items', 'location_items.item_id = items.item_id  ');
		if("0000-00-00 00:00:00"==$date){
			$this->db->where($this->db->dbprefix('items').".deleted",0);
		}else{
			$this->db->where('update_item_location  > ',$date);
			$this->db->or_where('update_item > ',$date);
		}
		
		
	//	$this->db->limit($limit);
		//$this->db->offset($offset);

		$query = $this->db->get();

		return $query;
		

	}
	
	function exists($item_id,$location=false)
	{
		if(!$location)
		{
			$location= $this->Employee->get_logged_in_employee_current_location_id();
		}
		$this->db->from('location_items');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	
	function save($item_location_data,$item_id=-1,$location_id=false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}
		/*echo "Error: " .  $this->db->_error_message();*/
		if (!$this->exists($item_id,$location_id))
		{
			$item_location_data['item_id'] = $item_id;
			$item_location_data['location_id'] = $location_id;
			return $this->db->insert('location_items',$item_location_data);
		}

		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location_id);
		return $this->db->update('location_items',$item_location_data);
		
	}
	
	function save_quantity($quantity, $item_id, $location_id=false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$sql = 'INSERT INTO '.$this->db->dbprefix('location_items'). ' (quantity, item_id, location_id)'
		    . ' VALUES (?, ?, ?)'
		    . ' ON DUPLICATE KEY UPDATE quantity = ?'; 
		
		return $this->db->query($sql, array($quantity, $item_id, $location_id,$quantity));		
	}
    
    function save_quantity_defect($quantity_warehouse, $quantity_defect, $total_defective_quantity, $description, $defective_user_id, $location_id, $item_id){

        if(!$location_id){
            $location_id= $this->Employee->get_logged_in_employee_current_location_id();
        }

        $location_items = "UPDATE ".$this->db->dbprefix('location_items')
                ." SET `quantity_warehouse` =  ?, `quantity_defect` =  ? "
                ."WHERE  `phppos_location_items`.`location_id` = ? AND `phppos_location_items`.`item_id` =?"
        ;

        $defective_audit = "INSERT INTO ".$this->db->dbprefix('defective_audit')
                ." (`location_id` , `item_id` ,`user_id` , `description`, `quantity`  ) "
                . "VALUES (?,  ?,  ? , ?, ?)";
        ;
        $this->db->trans_start();
            $this->db->query( $location_items, array($quantity_warehouse, $total_defective_quantity,$location_id,$item_id) );		
            $this->db->query( $defective_audit, array($location_id,$item_id,$defective_user_id, $description, $quantity_defect) );
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE){
            //should generate an error
            return FALSE;
        } else{
            return TRUE;
        }
        
    }
	
	/*
	Updates multiple item locations at once
	*/
	function update_multiple($item_location_data, $item_ids,$select_inventory=0, $location_id = false)
	{
		if(!$location_id)
		{
			$location_id= $this->Employee->get_logged_in_employee_current_location_id();
		}

		if(!$select_inventory)
		{
			$this->db->where_in('item_id',$item_ids);
		}

		
		$this->db->where('location_id', $location_id);
		return $this->db->update('location_items',$item_location_data);
	}
	
	
	function get_info($item_id,$location=false)
	{
		if(!$location)
		{
			$location= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('location_items');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location);
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			$row = $query->row();
			
			//Store a boolean indicating if the price has been overwritten
			$row->is_overwritten = ($row->cost_price !== NULL ||
			$row->unit_price !== NULL ||
			$row->promo_price !== NULL || 
			$this->is_tier_overwritten($item_id, $location));
			return $row;
		
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item_location
			$item_location_obj=new stdClass();

			//Get all the fields from item_locations table
			$fields = $this->db->list_fields('location_items');

			foreach ($fields as $field)
			{
				$item_location_obj->$field='';
			}
			
			$item_location_obj->is_overwritten = FALSE;

			return $item_location_obj;
		}

	}
	
	function get_location_quantity($item_id,$location=false)
	{
		if(!$location)
		{
			$location= $this->Employee->get_logged_in_employee_current_location_id();
		}
		
		$this->db->from('location_items');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location);
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			$row=$query->row();
			return $row->quantity;
		}

		return NULL;
	}
	
	function get_tier_price_row($tier_id,$item_id, $location_id)
	{
		$this->db->from('location_items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id ',$item_id);
		$this->db->where('location_id ',$location_id);
		return $this->db->get()->row();
	}
		
	function delete_tier_price($tier_id, $item_id, $location_id)
	{
		$this->db->where('tier_id', $tier_id);
		$this->db->where('item_id', $item_id);
		$this->db->where('location_id', $location_id);
		$this->db->delete('location_items_tier_prices');
	}
	
	function tier_exists($tier_id, $item_id, $location_id)
	{
		$this->db->from('location_items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
		
	}
	
	function save_item_tiers($tier_data,$item_id, $location_id)
	{	
		if($this->tier_exists($tier_data['tier_id'],$item_id,$location_id))
		{
			$this->db->where('tier_id', $tier_data['tier_id']);
			$this->db->where('item_id', $item_id);
			$this->db->where('location_id', $location_id);

			return $this->db->update('location_items_tier_prices',$tier_data);
			
		}

		return $this->db->insert('location_items_tier_prices',$tier_data);	
	}

	function is_tier_overwritten($item_id, $location_id)
	{
		$this->db->from('location_items_tier_prices');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
	}
}
?>
