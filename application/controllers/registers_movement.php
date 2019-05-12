<?php
require_once ("secure_area.php");


class Registers_movement extends Secure_area
{

	function __construct() 
	{
		parent::__construct('Registers_movement');
		$this->load->helper('date');		
		$this->load->model('Register_movement');
		$this->load->model('Register');
		$this->load->model('Sale');
		$this->load->model('Cajas_empleados');
		$this->load->model('employee');

	}
	function receipt($id_movimiento){
		$data=array();
		$movimiento_info=$this->Register_movement->get_info($id_movimiento);
		$data["movimiento_info"]=$movimiento_info;
		$this->load->view('registers_movement/receipt',$data);
	}

	function index($register_id = null)
	{
		$cajas = $this->Cajas_empleados->get_cajas_ubicacion_por_persona($this->session->userdata('person_id'),$this->Employee->get_logged_in_employee_current_location_id())->result_array();;
		$registers=array();
		foreach ($cajas as $caja) {
			$registers[$caja["register_id"]]=$caja["name"];
		}
		//$tem= $this->Register->get_registers();
		//if ($register_id == null || !array_key_exists($register_id, $registers)) {
		if($register_id == null || !$this->Employee->tiene_caja($register_id, $this->session->userdata('person_id'))){
			if(count($registers)>0){
				foreach($registers as $key =>$value){
					$register_id =$key;
					break;
				}				
			}else{
				$registers[0]="Sin caja";	
				$register_id=0;
			}
		}
		$data['location_registers'] = $registers;// obtener cajas de la tienda selecionada

		$empleados=array(0=>"Seleccione empleado");
		$employees= $this->Employee->get_all()->result();
		foreach($employees as $empleado){
			$empleados[$empleado->person_id]=$empleado->first_name." ".$empleado->last_name;
		}
		
		$data['register_id'] = $register_id;
		$desde = $this->input->get("desde");
		$hasta = $this->input->get("hasta");
		$open_box=$this->input->get("open_box")?1:0;

		$id_empleado = $this->input->get("empleado")? $this->input->get("empleado"): false;
		$filter = $this->input->get('filter')? $this->input->get('filter') : "";
		$search = $this->input->get('search')? $this->input->get('search') : "";

		if( $this->validate_date($desde)== false){
			$desde = null;
		}else{
			if( $this->validate_date($hasta)== false){
				$desde = null;
				$hasta = null;
			}
		}

		$data["desde"]=$desde;
		$data["hasta"]=$hasta;
		$data["id_empleado"]=$id_empleado;
		$data["empleados"]=$empleados;
		$data["filter"]=$filter;
		$data["search"]=$search;
		$data["open_box"]=$open_box;

		//$data['registers_movement'] = $this->Register_movement->get_all($register_id); //Obtener todos los movimientos de la caja seleccionada
		

		$data['registers_movement'] = $this->Register_movement->get_by_date($register_id,$desde,$hasta,"",$id_empleado, $filter, $search,$open_box); //obtener todos los movimiento comprendidos en un rango de fecha o si es null los ultimos 30 dias
		$this->load->view('registers_movement/manage',$data);
	}

	function operations($operation)
	{	
		if ($operation == "depositcash" || $operation == "withdrawcash" || $operation == "move_money") {			
			$data['text_info'] = $operation == "depositcash" ? "Depositar dinero" :($operation=="move_money"?lang('move_money'):"Registrar gasto");
			$cajas = $this->Cajas_empleados->get_cajas_ubicacion_por_persona($this->session->userdata('person_id'),$this->Employee->get_logged_in_employee_current_location_id())->result_array();;
			$registers=array();
			foreach ($cajas as $caja) {
				$registers[$caja["register_id"]]=$caja["name"];
			}
			$data['location_registers'] =$registers;// $this->Register->get_registers();
			$data['operation'] = $operation;
			$data['info_categoria']=$operation=="move_money"? lang('deliver_to'):lang('config_categoria');
			$categorias_gastos=$operation!="move_money"? array("no establecido"=>"Seleccionar"):'';
			$info_categoria=$operation=="move_money"?$this->Employee->get_all()->result_object():$this->Appconfig->get_categorias_gastos();
			foreach($info_categoria as $categoria){
				$info=$operation=="move_money"?$categoria->first_name.' '.$categoria->first_name:$categoria;
				$categorias_gastos[$info]=$info;
			}
			$categorias_gastos[lang('otros')]=lang('otros');
			$data["categorias_gastos"]=$categorias_gastos;
			$this->load->view("registers_movement/form",$data);
		} else {
			redirect('/registers_movement/', 'refresh');
		}
	}
	function receipt_register($register_log_id){
		$data = $this->Register_movement->report_register($register_log_id);
		$data["credito_tienda"]=$this->config->item("customers_store_accounts");
		$data["items_range"]=$this->Item->get_items_range($register_log_id);
		$this->load->view("registers_movement/report_register",$data);
	}

	function save($operation = null)
	{

		if ($operation == "depositcash" || $operation == "withdrawcash" || $operation=="move_money") {

			$register_id = $this->input->post('register_id'); 
			$cash        = abs($this->input->post('cash'));
			$categorias_gastos =$this->input->post('categorias_gastos');
			if($categorias_gastos==lang('otros') && $this->input->post('others_category')!=""){
				$categorias_gastos=$this->input->post('others_category');
			}
			$description =$operation=='move_money'? ('<strong>'.$categorias_gastos.'</strong><br>'.$this->input->post('description')):$this->input->post('description');
			$categorias_gastos  =$operation=='move_money'? lang('move_money_category'):$this->input->post('categorias_gastos');

			$imprimir=$this->input->post('imprimir');
			
			if ($operation == "withdrawcash" || $operation == "move_money" ) {
				$cash = $cash* (-1);
			}
			$status = $this->Register_movement->save_operation($register_id, $cash, $description,$categorias_gastos,$operation);			
			
			if ($status['success']) {	
				
				$msj_lang = ($operation == "depositcash") ? 'cash_flows_successful_adding' : 'cash_flows_successful_extract';
				echo json_encode(array(
										'success' => $imprimir==1?$status['success']:true, 
										'message' => $this->lang->line($msj_lang)));
								
			} else {
				echo json_encode(array(
										'success' => false, 
										'message' => $status['error']));
			}
		}
	}
	
	//Validamos las fechas para la busqueda de movimientos
	private function validate_date($date){
		$partes= explode("-", $date);
		if(count($partes)<3){
			return false;
		}elseif(count($partes)>3){
			return false;
		}else{
			$invalic = false;
			for ($i = 0; $i <= 2; $i++) {
				if (!is_numeric( $partes[$i])){
					$invalic = true;
					break;
				}
			}
			if ($invalic == true){
				return false;
			}else{
				if (!checkdate ( $partes[1] , $partes[2] , $partes[0] ) ){
					return false;
				}
			}
			
		}
		return true;
	}
}
