
<?php
class Maestro_nomina extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
			$this->load->model('deducion_asignacion');
	}

	public function create($data){
		$nomina=0;
		$this->db->query("SET autocommit=0");
		$this->db->query('LOCK TABLES ' . $this->db->dbprefix('maesnomina') . ' WRITE,'
		. $this->db->dbprefix('deducion_asignacion') . ' WRITE, '
		. $this->db->dbprefix('recibo_nominai_tems') . ' WRITE' );

		if($this->db->insert('maestro_nomina', $data) ){
		    $nomina_id =	$this->db->insert_id();
				$deduc_asig=	$this->deducion_asignacion->getAll();
					if($this->addItem($nomina_id, $deduc_asig)){
						$nomina=$nomina_id;
					}
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

  public function addItem($id_nomina, $deduc_asig)
  {
		$guardado=TRUE;
		foreach ($deduc_asig as $dato) {
		$dato = array(
										'tipo' =>$dato->tipo,
		 								'person_id'=>$dato->person_id,
										'porc'=>$dato->porc,
										'monto'=>$dato->monto,
										'created'=>$dato->created,
										'numero_nomina'=> $id_nomina,
										'eliminado'=> $dato->eliminado
									);
				if (!$this->db->insert('recibo_nomina_tems', $dato)) {
							$guardado=FALSE;
							break;
				}
		}
		return $guardado;
  }
	
	public function getNomina($id)
	{
		$this->db->where("id", $id);
		$this->db->from('maestro_nomina');
		$query = $this->db->get();
		$resultado=array();
		if($query->num_rows()>=1)
			$resultado=$query->result();

			return $resultado ;

	}
	public function genera_salario($id_nomina)
	{

	}
	public function getAll(){

		$this->db->from('maesnomina');
		$query =$this->db->get();

			return $query->result();
	}
}


?>
