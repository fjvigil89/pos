<?php
class Cajas_empleados extends CI_Model{
    function guardar($data){
       return $this->db->insert('cajas_empleados',$data);
    }
   
    function eleiminar_por_id_empleado($id_empleado){
        return $this->db->delete('cajas_empleados', array('person_id' => $id_empleado));
     }
     function get_caja($register_id,$person_id){
        $this->db->from('cajas_empleados');
        $this->db->join('registers', 'registers.register_id = cajas_empleados.register_id ');
        $this->db->where($this->db->dbprefix('cajas_empleados').'.register_id', $register_id);
        $this->db->where('person_id', $person_id);
        $this->db->where('deleted', 0);
        $result = $this->db->get();
        return $result;
     }
     function get_cajas(){
        $this->db->select('cajas_empleados.*');
        $this->db->from('cajas_empleados');
        $this->db->join('registers', 'registers.register_id = cajas_empleados.register_id ');
        
        $this->db->where('deleted', 0);
        $result = $this->db->get();
        return $result;
     }
     function get_caja_ubicacion_por_persona($register_id,$person_id,$location_id){
        $this->db->select('cajas_empleados.*,registers.location_id,registers.name');
        $this->db->from('cajas_empleados');
        $this->db->join('registers', 'registers.register_id = cajas_empleados.register_id ');
        $this->db->where($this->db->dbprefix('cajas_empleados').'.register_id', $register_id);
        $this->db->where('person_id', $person_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('deleted', 0);
        $result = $this->db->get();
        return $result;
     }
     function get_cajas_ubicacion_por_persona($person_id,$location_id){
        $this->db->select('cajas_empleados.*,registers.location_id,registers.name');
        $this->db->from('cajas_empleados');
        $this->db->join('registers', 'registers.register_id = cajas_empleados.register_id ');
        $this->db->where('person_id', $person_id);
        $this->db->where('location_id', $location_id);
        $this->db->where('deleted', 0);
        $result = $this->db->get();
        return $result;
     }

}
?>