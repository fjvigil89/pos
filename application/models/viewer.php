<?php
class Viewer extends CI_Model
{	
    function crate_viewer($data = false)
    {
        if(!$data)
        {
            $data = array(
                "employee_id" => $this->Employee->person_id_logged_in(),
                "cart_data"=> "[]",
                "toke" => md5($this->Employee->person_id_logged_in()) 
            ); 
        }

        $result =  $this->db->insert('viewer_cart', $data);
        return $result;
    }
    
    function update_viewer($employee_id, $data_cart)
    {
        if(!$this->exists_viewer_by_employe($employee_id))
            $this->crate_viewer();

        $result = $this->db->update('viewer_cart',$data_cart);
        if($data_cart["is_cart"]== 3)
            $this->db->query("COMMIT");
    }

    function exists_viewer_by_employe($employee_id)
    {
        $this->db->from('viewer_cart');	
		$this->db->where('employee_id',$employee_id);
        $query = $this->db->get();
        		
		return ($query->num_rows() == 1);
    }
    
    function get_viewer_by_employee($employee_id)
    {
        $this->db->from('viewer_cart');	
		$this->db->where('employee_id',$employee_id);
        $query = $this->db->get();

        return $query->row_array();
    }
}
?>