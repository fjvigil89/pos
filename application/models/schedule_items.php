<?php
class Schedule_Items extends CI_Model 
{
	/*Determines whether the given person exists*/
	function exists($schedule_id)
	{
		$this->db->from('schedule_items');	
		$this->db->where('schedule.id',$schedule_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	/*Gets all schedule*/
	function get_all($limit=10000, $offset=0)
	{
		$this->db->from('schedule_items');
		//$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
	}

	/**
	 * funcion obtiene la relacion pasandole un schedule
	 */
	function get_schedule($schedule_id, $limit=10000, $offset=0)
	{
		
		$this->db->from('schedule_items');
		$this->db->where('schedule_id', $schedule_id);
		//$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
		
	}
	
	/**
	 * retorna todos los schedules que tienen ese id
	 */
	function get_schedule_items($items_id, $limit=10000, $offset=0)
	{
		
		$this->db->from('schedule_items');
		$this->db->where('items_id', $items_id);
		//$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
		
	}
	function count_all()
	{
		$this->db->from('schedule_items');		
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a schedule as an array.
	*/
	function get_info($schedule_id)
	{
		$query = $this->db->get_where('schedule_items', array('schedule_id' => $schedule_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//create object with empty properties.
			$fields = $this->db->list_fields('schedule_items');
			$schedule_obj = new stdClass;
			
			foreach ($fields as $field)
			{
				$schedule_obj->$field='';
			}
			
			return $schedule_obj;
		}
	}
	
	/*
	Get people with specific array ids
	*/
	function get_multiple_info($schedule_ids)
	{
		$this->db->from('schedule_items');
		$this->db->where_in('schedule_id',$schedule_ids);
		//$this->db->order_by("title", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a schedule
	*/
	function save(&$schedule_data,$schedule_id=false)
	{		
		
		if (!$schedule_id or !$this->exists($schedule_id))
		{
			if ($this->db->insert('schedule_items',$schedule_data))
			{
				$schedule_data['id']=$this->db->insert_id();
				return $schedule_data['id'];
			}
			
			return false;
		}
		
		$this->db->where('schedule_id', $schedule_id);
		return $this->db->update('schedule_items',$schedule_data);
	}
	
	/*
	Deletes one schedule (doesn't actually do anything)
	*/
	function delete($schedule_id)
	{
		$this->db->where('schedule_id', $schedule_id);
		return $this->db->delete("schedule_items");
		
	}
	
	/*
	Deletes a list of people (doesn't actually do anything)
	*/
	function delete_list($schedule_ids)
	{	
		return true;	
 	}

	 	
	
}
?>
