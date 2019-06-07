<?php
class Categories extends CI_Model
{	
    function  get_all()
    {
        $this->db->select('name,img,id,name_img_original');		
		$this->db->from('categories');
		$this->db->where('deleted',0);
		
		return $this->db->get()->result_array();
    }
    function category_exists($name)
	{
		$this->db->from('categories');
        $this->db->where('name',$name);
        $this->db->where('deleted',0);
        $query =  $this->db->get();
        return $query->num_rows() == 1;
	}
	
    function save($id = -1, $data)
    {
        $info = $this->info_by_name($data["name"]);

        if($id == -1 and $info->deleted == 1)
        {
            $this->db->where('name',$data["name"]);
            $data["deleted"] = 0;
            $result =  $this->db->update('categories',$data);
        }
        else if($id == -1){      
            if( $this->db->insert('categories', $data))
                $result = $this->db->insert_id();
            else  $result = false;
        }
        else
        {
            $this->db->where('id',$id);
            $result =  $this->db->update('categories',$data);
        }

        return $result;
    }
    function existe($name)
    {        	
		$this->db->from('categories');
        $this->db->where('name',$name);
        $query =  $this->db->get();
        return $query->num_rows() == 1;
    }
    function delete($id,$path_long)
    {
        
        $info = $this->get_info($id);

        unlink($path_long."/". $info->img);

        $this->db->where('id',$id);
        $result =  $this->db->update('categories',array("deleted"=>1));
    }
    function  get_info($id)
    {
        	
		$this->db->from('categories');
        $this->db->where('deleted',0);
        $this->db->where('id',$id);
        $query =  $this->db->get();;
        if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			$obj_file = new stdClass;
			
			//Get all the fields from price_tiers table
			$fields = $this->db->list_fields('categories');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$obj_file->$field='';
			}
			
			return $obj_file;
		}		
		
    }	
    function  info_by_name($name)
    {
        	
		$this->db->from('categories');
        $this->db->where('name',$name);
        $query =  $this->db->get();;
        if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			$obj_file = new stdClass;
			
			//Get all the fields from price_tiers table
			$fields = $this->db->list_fields('categories');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$obj_file->$field='';
			}
			
			return $obj_file;
		}		
		
    }	
}
?>
