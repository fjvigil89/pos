<?php
class Appfile extends CI_Model 
{		
	function get_transfer($file_id)
	{
		$query = $this->db->get_where('transfer_files', array('file_id' => $file_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return ""; 
		
	}
	function get($file_id)
	{
		$query = $this->db->get_where('app_files', array('file_id' => $file_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return ""; 
		
	}
	function get_logo($file_id)
	{
		$login_db = $this->load->database('login',true);
		$query = $login_db->get_where('resellers', array('id' => $file_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return "";
		
	}
	
	function save($file_name,$file_data, $file_id = false)
	{
		$file_data=array(
		'file_name'=>$file_name,
		'file_data'=>$file_data
		);
				
		if (!$file_id)
		{
			if ($this->db->insert('app_files',$file_data))
			{
				return $this->db->insert_id();
			}
			
			return false;
		}
		
		$this->db->where('file_id', $file_id);
		if ($this->db->update('app_files',$file_data))
		{
			return $file_id;
		}
		
		return false;
	}
	function save_transfer($file_name,$file_data, $file_id = false)
	{
		$file_data=array(
			'name_file'=>$file_name,
			'data_file'=>$file_data,
			"date_at"=>date('Y-m-d H:i:s')
		);
				
		if (!$file_id)
		{
			if ($this->db->insert('transfer_files',$file_data))
			{				
				$file_id = $this->db->insert_id();
				
				$this->db->query("COMMIT");

				return $file_id;
			}			
			return false;
		}
		
		$this->db->where('file_id', $file_id);
		if ($this->db->update('transfer_files',$file_data))
		{
			$this->db->query("COMMIT");
			return $file_id;
		}
		
		return false;
	}
		
	function delete($file_id)
	{
		return $this->db->delete('app_files', array('file_id' => $file_id)); 
	}
}

?>