<?php
class Schedule extends CI_Model 
{
	/*Determines whether the given person exists*/
	function exists($schedule_id)
	{
		$this->db->from('schedule');	
		$this->db->where('schedule.id',$schedule_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	/*Gets all schedule*/
	function get_all($limit=10000, $offset=0)
	{
		$this->db->from('schedule');
		$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
	}

	/**
	 * funcion obtiene los schedules del que esta logueado
	 */
	function get_schedule($employee_id, $limit=10000, $offset=0)
	{

		$this->db->from('schedule');
		$this->db->where('employee_id', $employee_id);
		$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
		
	}

	/**
	 * funcion obtiene los schedules del que esta logueado
	 */
	function get_schedule_FacilPos($employee_id, $limit=10000, $offset=0)
	{

		$this->db->from('schedule');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('parent', 'FacilPos');
		$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
		
	}
	
	/**
	 * funcion obtiene los schedules del que esta logueado
	 */
	function exist_Google_FacilPos($id_google, $limit=10000, $offset=0)
	{

		$this->db->from('schedule');	
		$this->db->where('schedule.id_google',$id_google);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);	
		
	}
	/**
	 * retorna todos los schedules que tienen ese id
	 */
	function get_scheduleID($schedule_id, $limit=10000, $offset=0)
	{
		
		$this->db->from('schedule');
		$this->db->where('id', $schedule_id);
		$this->db->order_by("title", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();		
		
	}
	function count_all()
	{
		$this->db->from('schedule');		
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a schedule as an array.
	*/
	function get_info($schedule_id)
	{
		$query = $this->db->get_where('schedule', array('id' => $schedule_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//create object with empty properties.
			$fields = $this->db->list_fields('schedule');
			$schedule_obj = new stdClass;
			
			foreach ($fields as $field)
			{
				$schedule_obj->$field='';
			}
			
			return $schedule_obj;
		}
	}
	
	/*
	Get people with specific ids
	*/
	function get_multiple_info($schedule_id)
	{
		$this->db->from('schedule');
		$this->db->where_in('id',$schedule_ids);
		$this->db->order_by("title", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a schedule
	*/
	function save(&$schedule_data,$schedule_id=false)
	{		
		
		if (!$schedule_id or !$this->exists($schedule_id))
		{
			if ($this->db->insert('schedule',$schedule_data))
			{
				$schedule_data['id']=$this->db->insert_id();
				return $schedule_data['id'];
			}
			
			return false;
		}
		
		$this->db->where('id', $schedule_id);
		return $this->db->update('schedule',$schedule_data);
	}

	
	
	/*
	Deletes one schedule (doesn't actually do anything)
	*/
	function delete($schedule_id)
	{
		//var_dump($schedule_id);
		$this->db->where('id', $schedule_id);
		return $this->db->delete("schedule");
		
	}
	
	/*
	Deletes a list of people (doesn't actually do anything)
	*/
	function delete_list($schedule_ids)
	{	
		return true;	
 	}

		
	public function create_schedule_temp_table($params)
	 {
		 
		 set_time_limit(0);
		 
		 $location_id = $this->Employee->get_logged_in_employee_current_location_id();
 
		 $where = '';
		 
		 if (isset($params['start_date']) && isset($params['end_date']))
		 {
			 $where = 'WHERE create_at BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			 ' and '.$this->db->dbprefix('schedule').'.employee_id='.$this->db->escape($location_id);
		 }
		 
		 if(isset($params['id_empleado']) && $params['id_empleado']!="all"){
			 $where .= ' and '.$this->db->dbprefix('schedule').'.employee_id='.$this->db->escape($params["id_empleado"]);
 
		 }
		 var_dump("CREATE TEMPORARY TABLE ".$this->db->dbprefix('schedule_temp')."
		 (SELECT * FROM ".$this->db->dbprefix('schedule')."$where )");

		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('schedule_temp')."
		(SELECT * FROM ".$this->db->dbprefix('schedule')."$where )");

		
		 
		 
 
	 
	 }
	 	
	
}
?>
