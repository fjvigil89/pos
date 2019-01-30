<?php
class Point extends CI_Model 
{
	
	function get_id($customer_id)
	{
		$this->db->from('point');
		$this->db->where('customer_id',$customer_id);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->id_point;
		}

		return false;		
	}

	function point_customer($customer_id)
	{
		$this->db->from('point');
		$this->db->where('customer_id',$customer_id);
		$query = $this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->points;
		}

		return false;
	}

	function save_point($points,$customer_id,$detail)
	{
		
		
		$data_point= array(
			'customer_id' => $customer_id,
			'points' => $this->point_customer($customer_id) + $points
			);


		$id_point = $this->get_id($customer_id);

		if($id_point)
		{
			$this->db->where('id_point', $id_point);
			$this->db->update('phppos_point', $data_point);
		}
		else
		{
			$this->db->insert('phppos_point',$data_point);
			$id_point = $this->db->insert_id();
		}

		$data_point_movement = array(
			'id_point' => $id_point,
			'point_quantity' => $points,
			'detail' => $detail,
			'date_time' => date('Y-m-d H:i:s')
			);

		$success = $this->db->insert('phppos_point_movement',$data_point_movement);

		return $success;
	}
}

?>