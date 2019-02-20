<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Non_recibo extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('maesnomina');
	}
	public function View(){
		$respuesta=$this->maesnomina->getAll();
		$arrayRespuesta=array("data" =>$respuesta);
		$arrayRespuesta["success"]="OK";
		$arrayRespuesta["message"]= "";
		echo json_encode($arrayRespuesta);
	}
	public function Add()
	{		
		if($this->input->is_ajax_request())
		{
			$datos = array(
				'id_person' => $this->input->post('id_person') ,
				'periododesde' => $this->input->post('periododesde'),
				'periodohasta' => $this->input->post('periodohasta'),
				'created' => $this->input->post('created'),
				'audit_person' => $this->input->post('audit_person'),
				'observacion' => $this->input->post('observacion'),
				'salary' => $this->input->post('salary'),
				'status' => $this->input->post('status')
			);
			$arrayRespuesta=array("data" => array());			
			$respuesta=$this->maesnomina->save($datos);
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
	public function Delete($id)
	{		
		if($this->input->is_ajax_request())
		{			
			$arrayRespuesta=array("data" => array());			
			$respuesta=$this->maesnomina->delete($id);
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
	public function Edit($id)
	{		
		if($this->input->is_ajax_request())
		{
			$datos = array(
				'id_person' => $this->input->post('id_person') ,
				'periododesde' => $this->input->post('periododesde'),
				'periodohasta' => $this->input->post('periodohasta'),
				'created' => $this->input->post('created'),
				'audit_person' => $this->input->post('audit_person'),
				'observacion' => $this->input->post('observacion'),
				'salary' => $this->input->post('salary'),
				'status' => $this->input->post('status')
			);
			$arrayRespuesta=array("data" => array());
			
			$respuesta=$this->maesnomina->update($id,$datos);
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
}
?>