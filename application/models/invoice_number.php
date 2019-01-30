<?php
class Invoice_number extends CI_Model
{
    function get_info($location_id){
        $this->db->from('invoice_number');
        $this->db->where('location_id',$location_id);
        $this->db->where('deleted',0);
        $query = $this->db->get();
        if($query->num_rows()==1)
		{
			return $query->row();
		}
		else		
        {           
            $invoice_number_obj=new stdClass();
            $fields = $this->db->list_fields('invoice_number');
            foreach ($fields as $field)
            {
                $invoice_number_obj->$field='';
            }
            return $invoice_number_obj;	
        }
    }
    function get_serie_number($location_id){
        $this->db->select("serie_number");
        $this->db->from('invoice_number');
        $this->db->where('location_id',$location_id);
        $query = $this->db->get();
        if($query->num_rows()==1)
		{
			return $query->row()->serie_number;
        }
        return "1";
    }
    function existe($location_id){
        $this->db->from('invoice_number');
        $this->db->where('location_id',$location_id);
        $query = $this->db->get();
        if($query->num_rows()==1)
		{
			return true;
		}
		return false;
    }
    function save($location_id,$data){
        if($this->existe($location_id)){
            $data["deleted"]=0;
            $this->db->where('location_id', $location_id);
            return $this->db->update('invoice_number', $data);
        }else{
            $data["location_id"]=$location_id;
            $data["deleted"]=0;
            return $this->db->insert('invoice_number', $data);
        }
    }
}
?>