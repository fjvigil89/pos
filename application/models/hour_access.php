<?php
class Hour_access extends CI_Model 
{
    public function save( &$hora_acceso,&$employee_id){
        //First lets clear out any access the employee currently has.
        $this->db->query("SET autocommit=0");
		$success=$this->db->delete('access_employees', array('employee_id' => $employee_id));
        if($success){
            $con_day=0;
            
            foreach ($hora_acceso as $id_tienda => $tienda_acceso_day) {
                foreach($tienda_acceso_day as $key=>$acceso_day)
                {
                    for ($i=0; $i <sizeof($acceso_day) ; $i++) { 
                        $success = $this->db->insert('access_employees',
                        array(
                            'location'=>$id_tienda,
                            'id_day_access'=>$con_day,
                            'id_hour_access'=>$acceso_day[$i],
                            'employee_id'=>$employee_id
                        ));
                    }
                    
                    $con_day++;
                }
            }
        }	
        $this->db->query("COMMIT");
        
        return $success;
    }
    public function get_acceso_employee($employee_id){
        $this->db->from('access_employees');
        $this->db->where('employee_id', $employee_id);
        $this->db->order_by('id_day_access', 'ASC');
        $resul=$this->db->get()->result_array();
        
        return $resul;
    }

    public function get_has_access(&$location,&$hour,&$day,&$employee_id){
        $this->db->from('access_employees');
        $this->db->join('phppos_hour_access', 'phppos_hour_access.id = access_employees.id_hour_access');
        $this->db->where('location', $location);
        $this->db->where('id_day_access', $day-1);
        $this->db->where('hour_access', $hour.':00:00');
        $this->db->where('employee_id', $employee_id);
        $resul=$this->db->get()->num_rows();

        return ($resul==1);
    }
    function logout_access()
	{
        $this->session->sess_destroy();
        return true;
	}
}
?>