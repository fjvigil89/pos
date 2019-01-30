<?php
class support_payment extends CI_Model
{
    function save($data,$id_support)
    {   
           
        if (!$this->db->insert('support_payments', $data))
        {      
            return false;            
        }       
        return true;
    } 
    function get_sum_payment($id_support)
    {
        $this->db->select("SUM(payment) as suma ");
       
        $this->db->from('support_payments');		
        $this->db->where('id_support',$id_support);
        $this->db->where('deleted',0);
        $query=$this->db->get();
        if($query->num_rows() ==1) {
            $row= $query->row();
            return $row->suma !=null ? $row->suma:0;
        }       
        return 0;
    }  
    function get_all_by_support($id_support)
    {      
       
        $this->db->from('support_payments');		
        $this->db->where('id_support',$id_support);
        $this->db->where('deleted',0);
        $query=$this->db->get();
        return $query->result();	       
           
         
       
    }  
    function delete_by_id_support($id_support)
    {         
        $this->db->where('id_support',$id_support);
        return $this->db->delete("support_payments");  
    }  

}
?>