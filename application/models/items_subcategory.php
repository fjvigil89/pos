<?php
class items_subcategory extends CI_Model
{
    public function save_one($subcategory)
    {
        if($this->exists($subcategory["item_id"], $subcategory["location_id"] , $subcategory['custom1'], $subcategory['custom2'])){
           
            $info = $this->get_info($subcategory["item_id"], $subcategory["location_id"] , $subcategory['custom1'], $subcategory['custom2']);
            if($info->deleted)
                $data = array("deleted"=>0,"quantity"=>0, "of_low"=>0);
            else
              $data = array("deleted"=>0, "of_low"=>0);

            return $this->update_by_id($subcategory["item_id"], $subcategory["location_id"], $subcategory['custom1'], $subcategory['custom2'],  $data);
        }else{
            return $this->db->insert('items_subcategory', $subcategory);
        }
    }
    public function save($data, $item_id, $location_id)
    {
        $error=false;
        $this->delete_all_by_id($location_id, $item_id);
        foreach($data as $subcategory){
            if($this->exists($item_id, $location_id , $subcategory['custom1'], $subcategory['custom2'])){
                $subcategory_data["deleted"] = false;
                $subcategory_data["quantity"] = $subcategory["quantity"];
                $subcategory_data["of_low"] = false;
                $subcategory_data["expiration_date"]= $subcategory["expiration_date"];
               if(! $this->update_by_id($item_id, $location_id , $subcategory['custom1'],  $subcategory['custom2'],  $subcategory_data))
               $error= true;
            }else{
                if(!$this->db->insert('items_subcategory', $subcategory))
                $error= true;
            }
        }       
        return !$error;
    }
    public function get_custom1( $item_id, $location_id=false){
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        }
        $this->db->select('DISTINCT(custom1),quantity');  
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('deleted',0);
        $this->db->where('of_low',0);
        $query = $this->db->get("items_subcategory");
         return $query->result();
        
    }
    function get_category_suggestions_custom($search, $custom="custom1", $item_id = false)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select($custom);
        $this->db->from('items_subcategory');
        if($item_id != false)
            $this->db->where('item_id',$item_id);
        
		$this->db->like($custom, $search);
		//$this->db->where('deleted', 0);
		$this->db->limit(25);
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=array('label' => $row->$custom);
		}

		return $suggestions;
	}
    public function get_quantity($item_id, $location_id=false, $custom1,$custom2){
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        } 
        
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('custom2', $custom2);
        $this->db->where('deleted', 0);

        $query = $this->db->get("items_subcategory");

        if ($query->num_rows() == 1) {
            return  $query->row()->quantity;
        }
        return 0;
    }
    public function get_all_indexed($date ){         
        $this->db->from('items_subcategory');

        if($date=="0000-00-00 00:00:00"){
			$this->db->where('deleted',0);
		}else{
			$this->db->where('update_subcategory_item > ',$date);
		}
       
        $query = $this->db->get();       
            return $query;   

    }
    public function get_custom2( $item_id, $location_id=false, $custom1){
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        }
        $this->db->select('DISTINCT(custom2),quantity');  
        $this->db->where('item_id', $item_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('location_id', $location_id);
        $this->db->where('deleted',0);
        $this->db->where('of_low',0);

        $query = $this->db->get("items_subcategory");

       
            return $query->result();
        

    }
    public function delete_all_by_id($location_id, $item_id)
    {
        
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->set('deleted',true);
        $query= $this->db->update('items_subcategory');        
        return $query;
    }
    public function get_all($location_id, $item_id)
    {      
        $this->db->where('deleted', 0);
        //$this->db->where('of_low', 0);
        $query = $this->db->get("items_subcategory");
        if($query->num_rows() >0) {
            return $query->result();
        } else {
          
            $item_subcategory_obj = new stdClass();

            //Get all the fields from items_subcategory table
            $fields = $this->db->list_fields('items_subcategory');

            foreach ($fields as $field) {
                if($field=="quantity")
                 $item_subcategory_obj->$field = 0;
                $item_subcategory_obj->$field = '';
            }

            return $item_subcategory_obj;
        }
    }
    public function get_all_by_id($location_id, $item_id)
    {   if (!$location_id) {
             $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        }
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('deleted', 0);
        $query = $this->db->get("items_subcategory");
        if($query->num_rows() >0) {
            return $query->result();
        } else {
          
            $item_subcategory_obj = new stdClass();

            //Get all the fields from items_subcategory table
            $fields = $this->db->list_fields('items_subcategory');

            foreach ($fields as $field) {
                if($field=="quantity")
                 $item_subcategory_obj->$field = 0;
               else $item_subcategory_obj->$field = '';
            }
            $tem[]=$item_subcategory_obj;
            return $tem;
        }
    }
    public function update_by_id($item_id, $location_id , $custom1, $custom2, $data)
    {       
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('custom2', $custom2);
        return $this->db->update('items_subcategory', $data);
    }
    public function exists($item_id, $location_id , $custom1, $custom2)
    {   
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
       }    
        $this->db->from('items_subcategory');
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('custom2', $custom2);
        $query = $this->db->get();
       
        return ($query->num_rows() == 1);
    }
    
    public function get_info($item_id, $location_id = false, $custom1, $custom2)
    {
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        }

        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('custom2', $custom2);

        $query = $this->db->get("items_subcategory");

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_subcategory_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('items_subcategory');

            foreach ($fields as $field) {
                $item_subcategory_obj->$field = '';
            }

            return $item_subcategory_obj;
        }

    } 
    public function save_quantity($quantity, $item_id, $location_id = false, $custom1, $custom2)
    {
        if (!$location_id) {
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        }
        $this->db->set('quantity', $quantity);
        $this->db->where('item_id', $item_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('custom1', $custom1);
        $this->db->where('custom2', $custom2);
        return $this->db->update('items_subcategory');
    }
}
