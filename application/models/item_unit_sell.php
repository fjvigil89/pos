<?php
class Item_unit_sell extends CI_Model
{

    function save($data,$item_id)
    {
        $this->delete_by_item($item_id);
        $error = false;

        foreach ($data as $unit) 
        {
            if($this->exists($unit["id"]))
            {
                if(!$this->update($unit,$unit["id"]))
                    $error = true;
            }
                
            else
            {
                if(!$this->db->insert('item_unit_sell',$unit))
                    $error = true;
            }
        }

        return $error;
    }

    function update($data,$id)
    {
        $this->db->where('id',$id);
        $this->db->update('item_unit_sell',$data);
    }

    function exists($id)
	{
		$this->db->from('item_unit_sell');
		$this->db->where('id',$id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

    function delete_by_item($item_id)
    {        
        $this->db->where('item_id',$item_id);
        return $this->db->update('item_unit_sell',array("deleted"=>1));
    }
    function get_select_by_item($item_id)
    {        
        $this->db->from('item_unit_sell');
        $this->db->where('item_id',$item_id);
        $this->db->where('deleted',0);
        $this->db->where('default_select',1);
        $result = $this->db->get()->row();
        
        return $result;
    }
    function get_info($id)
    {
        $this->db->from('item_unit_sell');
        $this->db->where('id',$id);
        //$this->db->where('deleted',0);
        $result = $this->db->get()->row();
        return $result;
    }

    function get_all_by_item($item_id)
    {
        $this->db->from('item_unit_sell');
        $this->db->where('item_id',$item_id);
        $this->db->where('deleted',0);
        
        $result = $this->db->get();
        
        return $result;
    }
}
?>