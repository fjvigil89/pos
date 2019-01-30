<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once ("secure_area.php");
class Nomina extends  Secure_area
{
	function __construct()
	{
		parent::__construct("Nomina");

		$this->load->model('deducion_asignacion');
		$this->load->model('Employee');
		$this->load->model('Maestro_nomina');



	}
	/*
	* el formulario para registrar las Deducciones y asignaciones
	*/
	public function Index($offset=0){
		/*$params = $this->session->userdata('nomina_search_data') ? $this->session->userdata('nomina_search_data') : array('offset' => 0, 'order_col' => 'last_name', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('nomina/index/'.$params['offset']);
		}*/

		$data["employees"]=$this->Employee->get_multiple_info_all()->result();


		$this->check_action_permission('search');
		$this->load->View('nomina/form.php',$data);

	}
	/*
	* elimina una  Deducciones o asignaciones
	*/
	public function deleted_by_id($id_registro=-1)
	{
			$this->check_action_permission('delete');

			$respuesta=$this->deducion_asignacion->delte_by_id($id_registro);
	  	$arrayRespuesta=array();
			if($respuesta){
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= lang("nomina_eliminado");
			}else{
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= lang("nomina_no_eliminado");
			}

			echo json_encode($arrayRespuesta);

	}
	public function getTable($type=0,$id_employe=-1)
	{
		$data["dedu_asig"]=$this->deducion_asignacion->getAll_Id_employe($id_employe);
		$data["type"]=$type;


		$this->load->View('nomina/table.php',$data);
	}
	public function asig_dedu_modal($type=0)
	{
	  	$data["type"]=$type;
			$this->load->View('nomina/asig_dedu_modal.php',$data);
	}
public function data_employe($id_employe=-1){
	$data=array();
	if($this->input->is_ajax_request()){
		$data=$this->Employee->get_multiple_info($id_employe);
		if($data->num_rows() > 0 )
        {
            $data = $data->row();
        }

	}
		echo json_encode($data);
	}

	/**
	 * Agrega  y/o actualiza las Deducciones y asignaciones
 	*
	 * @return type
	 */
	public function save()
	{
			$this->check_action_permission('add_update');
		///	$this->form_validation->set_rules('id_employe', '', 'required');
			$id_employe= $this->input->post('id_employe');
			$respuesta=false;
			$arrayRespuesta=array();
			$asignacion =empty( $this->input->post("asignacion")) ? array(): $this->input->post("asignacion");
			$deduccion = empty($this->input->post("deduccion")) ? array(): $this->input->post("deduccion");



		/*	for($i = 0; $i < count($asignacion); $i=$i+4)
			{
		    $porc = $asignacion[$i+1];
		    $monto = $asignacion[$i+2];
		    $descrip = $asignacion[$i+3];
				echo "string";
		    //$this->form_validation->set_rules($porc, "Porcentaje", "trim|required|numeric");
		    //$this->form_validation->set_rules($monto, "Monton", "trim|required|numeric");
		    //$this->form_validation->set_rules($descrip, "Descripcion", "trim|max_length[150]|required");

				///$this->form_validation->set_rules(asignacion[], "Asignacion", "trim|required");
			}*/

		//	if ($this->form_validation->run() == TRUE){
			// guarda los datos en BD
			$respuesta=$this->deducion_asignacion->save($id_employe,$asignacion,$deduccion);
		//}else{
			//$arrayRespuesta["success"]="ERROR";
			//$arrayRespuesta["message"]= "Datoas no validos";
		//}
			if($respuesta){
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= lang("nomina_exito_guardar");
			}else{
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= lang("nomina_error_guardar");
			}
			echo json_encode($arrayRespuesta);
	}

public function nomina_create()
{
		$this->load->View('nomina/maestro.php');
}
public function save_nomina()
{
	$periodo_desde= $this->input->post('periodo_desde');
	$periodo_hasta= $this->input->post('periodo_hasta');
	$descripcion= $this->input->post('descripcion');
	$id_employe= $this->Employee->get_logged_in_employee_info()->person_id;

	   $data = array(
			 			'periododesde' => $periodo_desde,
						'periodohasta' =>  $periodo_desde,
						'created' => date("Y-m-d"),
						'audit_person'=> $id_employe,
						'observacion'=>$descripcion,
						'status'=>0
					);
			$id_nomina=$this->Maestro_nomina->create($data);
			if($id_nomina){
				$arrayRespuesta["success"]="OK";
				$arrayRespuesta["message"]= lang("nomina_exito_guardar");
			}else{
				$arrayRespuesta["success"]="ERROR";
				$arrayRespuesta["message"]= lang("nomina_error_guardar");
			}
			echo json_encode($arrayRespuesta);
}

}
?>
