
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Non_deduc_asig extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	
		$this->load->model('deducion_asignacion');
		

	}
	public function Index(){
		$this->load->View('nomina/form.php');

	}

	/**
	 * detalle de todas las nominas
	 * @return type
	 */
	public function View(){
		$respuesta=$this->deducion_asignacion->getAll();
		$arrayRespuesta=array("data" =>$respuesta);
		$arrayRespuesta["success"]="OK";
		$arrayRespuesta["message"]= "";
		echo json_encode($arrayRespuesta);
	}
	/**
	 * agrega una nueva nomina
	 * @return type
	 */
	public function Add()
	{		
		if($this->input->is_ajax_request())
		{
			$datos = array(
				'tipo' => $this->input->post('tipo') ,
				'person_id' => $this->input->post('person_id'),
				'porc' => $this->input->post('porc'),
				'monto' => $this->input->post('monto'),
				'descripcion' => $this->input->post('descripcion'),
				'created' => $this->input->post('created')
			);
			$arrayRespuesta=array("data" => array());	
			// guarda los datos en BD		
			$respuesta=$this->deducion_asignacion->save($datos);
			if($respuesta>0){				
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= "Datos guardados con exito.";

			}else{				
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= "Error al guardar los datos.";
			}
			echo json_encode($arrayRespuesta);
		}

	}
	/**
	 * eleimina una nomina
	 * @param type $id 
	 * @return type
	 */
	public function Delete($id)
	{		
		if($this->input->is_ajax_request())
		{			
			$arrayRespuesta=array("data" => array());			
			$respuesta=$this->deducion_asignacion->delete($id);
			if($respuesta>0){				
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= "Registro eliminado.";
			}else{				
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= "Error al eliminar,";
			}
			echo json_encode($arrayRespuesta);
		}

	}
	/**
	 * edidta los registro de una nomina
	 * @param type $id 
	 * @return type
	 */
	public function Edit($id)
	{		
		if($this->input->is_ajax_request())
		{
			$datos = array(
				'tipo' => $this->input->post('tipo') ,
				'person_id' => $this->input->post('person_id'),
				'porc' => $this->input->post('porc'),
				'monto' => $this->input->post('monto'),
				'descripcion' => $this->input->post('descripcion'),
				'created' => $this->input->post('created')
			);
			$arrayRespuesta=array("data" => array());
			
			$respuesta=$this->deducion_asignacion->update($id,$datos);
			if($respuesta>0){				
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= "Datos guardados con exito.";

			}else{				
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= "Error al guardar los.";
			}
			echo json_encode($arrayRespuesta);
		}

	}
	/**
	 * Detalle completo de una nomina
	 * @param type $id_nomina 
	 * @return type
	 */
	public function Abrir_nomina($fecha_desde="", $fecha_hasta=""){
		$respuesta=$this->deducion_asignacion->get_detalle_nomina();
		$arrayRespuesta=array("data" => $respuesta);
		$arrayRespuesta["success"]="OK";
		$arrayRespuesta["message"]= "";
		echo json_encode($arrayRespuesta);
	}

	public function Aprobar_nomina(){
		
	} 
}
?>