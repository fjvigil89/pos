<?php
class Viewer_file extends CI_Model
{
    function save($id = -1, $data)
    {
        if($id == -1)
            $result =  $this->db->insert('viewer_file', $data);
        else
        {
            $this->db->where('id',$id);
            $result =  $this->db->update('viewer_file',$data);
        }

        return $result;
    }
    function get_info($id = -1)
    {
        $this->db->from('viewer_file');	
		$this->db->where('id',$id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			$obj_file = new stdClass;
			
			//Get all the fields from price_tiers table
			$fields = $this->db->list_fields('viewer_file');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$obj_file->$field='';
			}
			
			return $obj_file;
		}
    }
    function delete($id,$path_long)
    {
        
        $img_info = $this->get_info($img_id);

        unlink($path_long."/". $img_info->new_name);

        $this->db->where('id',$id);
        $result =  $this->db->update('viewer_file',array("deleted"=>1));
    }
    function get_list_by_location($location_id)
    {
        $this->db->from('viewer_file');	
        $this->db->where('location_id',$location_id);
        $this->db->where('deleted',0);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();

        return $query;
    }
    function delete_list($img_ids,$path_long)
	{        

		foreach($img_ids as $img_id)
		{
            $img_info = $this->get_info($img_id);
            unlink($path_long."/". $img_info->new_name);
		}	
		
		$this->db->where_in('id',$img_ids);		
		return $this->db->update('viewer_file', array('deleted' => 1));
 	}
}
?>
