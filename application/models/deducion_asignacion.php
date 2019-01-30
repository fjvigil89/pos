
<?php
class Deducion_asignacion extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
/**
 * agrega una nueva nomina
 * @param type $data
 * @return type
 */

 public function add_deduccion_asignacion($id_employe,$arraydato,$type)
 {
	 // indica la cantidad de datos que se envian, estos son lo que se muestran en las tablas de deduccion
	 $cantidadDatosGuardar=4;
	 try {
		 for ($i=0; $i < sizeof($arraydato); $i=$i+$cantidadDatosGuardar) {
			 $data = array(
				 'porc' => $arraydato[$i+1],
				 'monto' =>  $arraydato[$i+2],
				 'descripcion' => $arraydato[$i+3],
			 );
			 // si  existe se actualiza
			 if(count($this->getId($arraydato[$i]))>0){
				 if(!$this->update($arraydato[$i],$data)){
					 	return 0;
				 }
			 }else{
					   $data['tipo'] = $type ;
						 $data['person_id'] =$id_employe;
						 $data['created'] = date("Y-m-d");
						 $data['eliminado'] = 0;

					 if(!$this->db->insert('deducion_asignacion', $data)){
			 				return 0;
			 		}
			}
		}
	} catch (Exception $e) {
			return 0;
	}
	return 1;
 }
	public function save($id_employe,$asignacion,$deduccion){
	    	$guardado=1;
		    $this->db->query("SET autocommit=0");
			  $this->db->query('LOCK TABLES ' . $this->db->dbprefix('deducion_asignacion') . ' WRITE');
					// se agregan
				if(!$this->add_deduccion_asignacion($id_employe,$asignacion,0) ||
							!$this->add_deduccion_asignacion($id_employe,$deduccion,1)){
								$guardado=0;
				}
				if(!$guardado){
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');

				}else{
				$this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');
			}
		return $guardado;
	}
	/**
	 * Edita los datos de una nimina
	 * @param type $id
	 * @param type $data
	 * @return type
	 */
	public function update($id,$data){
		$this->db->where("id", $id);
		$query = $this->db->update("deducion_asignacion", $data);
		return $query;
	}

	/**
	 * Elimina las deducciones y asignaciones de un empleado
	 * @param type $id_employe
	 * @return type
	 */
	public function delte_by_id($id)
	{
	  $data['eliminado'] = 1;
		$this->db->where("id", $id);
		$query = $this->db->update("deducion_asignacion", $data);
		return $query;
	}
	/**
	 * Consulta los datos de una deduccion o asignacion por el id
	 * @param type $id
	 * @return type
	 */
	public function getId($id)
	{
		$this->db->where("id", $id);
		$this->db->from('deducion_asignacion');
		$query = $this->db->get();
		$resultado=array();
		if($query->num_rows()>=1)
			$resultado=$query->result();
		return $resultado ;
	}


	public function getAll_Id_employe($Id_employe){

		$this->db->from('deducion_asignacion');
		$this->db->where("person_id", $Id_employe);
			$this->db->where("eliminado", 0);
		$query =$this->db->get();
		return $query->result();
	}
	public function getAll(){

		$this->db->from('deducion_asignacion');
			$this->db->where("eliminado", 0);
		$query =$this->db->get();
		return $query->result();
	}
	/*public function get_detalle_nomina(){
			$this->db->from('deducion_asignacion');
			$this->db->join('employees', 'employees.id = deducion_asignacion.person_id');
			$query = $this->db->get();
			$resultado=array();
			if($query->num_rows()>=1)
				$resultado= $query->result();
			return $resultado;
		}
		*/
}
?>
