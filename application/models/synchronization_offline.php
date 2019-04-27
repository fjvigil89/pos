<?php
class synchronization_offline extends CI_Model
{
    function exists($token, $id){
        $this->db->select('id');
        $this->db->from('synchronizations_offline');
        $this->db->where('token', $token);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }
    public function save($ip=null)
    {
        
        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock 
        $this->db->query('LOCK TABLES '.$this->db->dbprefix('sales') . 'WRITE, '
        .$this->db->dbprefix('synchronizations_offline') . ' WRITE ');

        $id_insert = false;
        $data=array();
         $location_id = $this->Employee->get_logged_in_employee_current_location_id();
           
       
        $data["date"]=date('Y-m-d H:i:s');
        $data["ip"]=$ip;
        $data["person_id"]=$this->session->userdata('person_id');
       
        $data["token"]="-";
        $query=$this->db->insert('synchronizations_offline', $data);
        if (!$query) {
            $this->db->query("ROLLBACK");
            $this->db->query('UNLOCK TABLES');
            return null;
        }
        $id_insert = $this->db->insert_id();   

        $numbers=$this->Sale->get_last_sale_numbers();//->result_array()  ;     
        $arr_number=array();
        foreach($numbers as $number){
           $number["ticket_number"]= $number["ticket_number"]==NULL? 1: $number["ticket_number"];
           $number["invoice_number"]= $number["invoice_number"]==NULL? 1: $number["invoice_number"];
            $arr_number[$number["location_id"]]= $number;
        }
        $data["last_invoice"]=$arr_number;          
       
        $token = md5($id_insert."-".$data["date"]);
        $data["token"]=$token;
        $data["id"]=$id_insert;
        $this->db->where('id', $id_insert);
        if (!$this->db->update('synchronizations_offline', array("token" => $token))) {
            $this->db->query("ROLLBACK");
            $this->db->query('UNLOCK TABLES');
            return null;
        }
                
        $this->db->select('NOW() as date_mysql', false);   
        $data_mysql= $this->db->get()->row_array();
        $data["date_mysql"]=$data_mysql["date_mysql"];
        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');
        return $data;
    }
}
