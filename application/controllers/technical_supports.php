<?php if (!defined('BASEPATH'))
exit('No direct script access allowed');
require_once("secure_area.php");

class technical_supports extends Secure_area
{
	function __construct()
	{
		parent::__construct("technical_supports");
		//$this->load->library('sale_lib');
		$this->load->library('carrito_lib');
		$this->load->model('support_payment');
		$this->load->model('CarritoModel');
		$this->load->model('Register_movement');

	}

	function Index()
	{
		$this->check_action_permission('search');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$data['controller_name'] = strtolower(get_class());

		$data['servicios'] = $this->technical_support->lista_servicios($this->input->get('statusVer'));
		$data['serviciosStatus'] = $this->technical_support->lista_servicios_total_entregado("ENTREGADO");

		$data["options"] = array(null => "", lang("technical_supports_recibido") => lang("technical_supports_recibido"), lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"),
			lang("technical_supports_aprobado") => lang("technical_supports_aprobado"), lang("technical_supports_rechazado") => lang("technical_supports_rechazado"),
			lang("technical_supports_reparado") => lang("technical_supports_reparado"), lang("technical_supports_retirado") => lang("technical_supports_retirado"));
		$table_data = $this->technical_support->get_all();
		$data["total_rows"] = $table_data->num_rows();
		$data['manage_table'] = get_support_manage_table($table_data, $this);
		if ($this->input->get('quien') == '') {
			$this->load->View('technical_supports/manage.php', $data);
		}
		if ($this->input->get('quien') == '1') {
			$this->load->View('technical_supports/manage_tabla', $data);
		}

	}

	function registrarCliente($customer_id = -1, $redirect_code = 0)
	{
		$this->check_action_permission('add_update');

		$tiers = array();
		$tiers_result = $this->Tier->get_all()->result_array();

		if (count($tiers_result) > 0) {
			$tiers[0] = lang('items_none');
			foreach ($tiers_result as $tier) {
				$tiers[$tier['id']] = $tier['name'];
			}
		}

		$data['controller_name'] = strtolower(get_class());
		$data['tiers'] = $tiers;
		$data['person_info'] = $this->Customer->get_info($customer_id);
		$data['person_info_point'] = $this->Customer->get_info_points($customer_id);
		$data['redirect_code'] = $redirect_code;
		$this->load->view("technical_supports/cliente/nuevo_cliente", $data);
	}

	/*public function editar_servicio()
	{
		$id_support = $this->input->get('id_support');

		$data = [
			'id_employee_receive' => $this->input->get('empleado'),
			'id_employee_register' => $this->input->get('tecnico'),
			'model' => $this->input->get('model'),
			'custom1_support_name' => $this->input->get('custom1'),
			'custom2_support_name' => $this->input->get('custom2'),
			'do_have_accessory' => $this->input->get('do_have_accessory'),
			'accessory' => $this->input->get('accessory'),
			'damage_failure' => $this->input->get('falla'),
			'state' => $this->input->get('state'),
			'color' => $this->input->get('color'),
			'type_team' => $this->input->get('equipo'),
			'repair_cost' => $this->input->get('costo'),
			'marca' => $this->input->get('marca'),
			'ubi_equipo' => $this->input->get('ubi_equipo')
		];

		$result = $this->technical_support->actualizarServicioCliente($id_support, $data);

		$data['controller_name'] = strtolower(get_class());

		$data['servicios'] = $this->technical_support->lista_servicios($this->input->get('statusVer'));

		$data["options"] = [

			lang("technical_supports_recibido") => lang("technical_supports_recibido"),
			lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"),
			lang("technical_supports_aprobado") => lang("technical_supports_aprobado"),
			lang("technical_supports_rechazado") => lang("technical_supports_rechazado"),
			lang("technical_supports_reparado") => lang("technical_supports_reparado"),
			lang("technical_supports_retirado") => lang("technical_supports_retirado")
		];

		$table_data = $this->technical_support->get_all();

		$data['manage_table'] = get_support_manage_table($table_data, $this);

		$data['mensaje_editar_servicio'] = "Actualizacion exitosa";

		$this->load->View('technical_supports/manage_tabla', $data);

	}
*/
	public function mostrarResumen($id_equipo)
	{
		$result = $this->technical_support->get_info_equipo($id_equipo)->row();
		$abono = $this->technical_support->get_info_abono_orden($id_equipo);
		$this->load->view("technical_supports/tecnico/index", compact("result", "abono"));
	}

	/*public function viewEditarServicios()
	{
		$data["id_support"] = $this->input->get('id_support');

		$data['cliente_equipo'] = $this->technical_support->getClienteEquipo($data["id_support"]);

		$empleados = $this->Employee->get_multiple_info_all()->result();
		foreach ($empleados as $empleados) {
			$emp["$empleados->person_id"] = $empleados->first_name . " " . $empleados->last_name;
		}
		$data['empleados'] = $emp;

		$data["states"] = [
			lang("technical_supports_recibido") => lang("technical_supports_recibido"),
			lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"),
			lang("technical_supports_aprobado") => lang("technical_supports_aprobado"),
			lang("technical_supports_rechazado") => lang("technical_supports_rechazado"),
			lang("technical_supports_reparado") => lang("technical_supports_reparado"),
			lang("technical_supports_retirado") => lang("technical_supports_retirado")
		];
		$eServ = $data["cliente_equipo"]->type_team;
		$serv["$eServ"] = $eServ;
		$servicios = $this->Appconfig->get_tipo_servicios()->result();
		foreach ($servicios as $servicios) {
			$serv["$servicios->tservicios"] = $servicios->tservicios;
		}
		$data['servicios'] = $serv;
		$efall = $data["cliente_equipo"]->damage_failure;
		$fall["$efall"] = $efall;
		$fallas = $this->Appconfig->get_tipo_fallas()->result();
		foreach ($fallas as $fallas) {
			$fall["$fallas->tfallas"] = $fallas->tfallas;
		}
		$data['fallas'] = $fall;
//            $eUbica=$data["cliente_equipo"]->ubi_equipo; 
//            $ubi["$eUbica"] = $eUbica;
		$data['ubic'] = $this->Appconfig->get_ubica_equipos()->result();
//            foreach ($ubicacion as $ubicacion) {
//                    $ub["$ubicacion->ubicacion"] = $ubicacion->ubicacion;
//            }
//            $data['ubic'] = $ub;

		$data["payment"] = $this->support_payment->get_sum_payment($data["id_support"]);

		$this->load->View('technical_supports/editar/index', $data);
	}*/

	public function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->technical_support->get_search_suggestions($this->input->get('term'), 100);
		echo json_encode($suggestions);
	}

	/*function view($id_support = -1)
	{
		$this->check_action_permission('add_update');
		$this->load->View('technical_supports/form.php');
	}
	

	public function getClientsInfo()
	{
		$data["result"] = $this->Customer->get_info($this->input->get('hc'));
		$data["Cliente"] = $this->technical_support->hCliente($this->input->get('hc'));
		$this->load->view("technical_supports/cliente/index", $data);
	}*/

	/*public function getClientsInfo2()
	{
		$this->check_action_permission('add_update');
		$clientID = $this->input->get('hc');
		$id_support = $this->input->get('idSupport');
		$array_employees = array();
		$employees = $this->Employee->get_multiple_info_all()->result();
		foreach ($employees as $employee) {
			$array_employees["$employee->person_id"] = $employee->person_id . " - " . $employee->first_name . " " . $employee->last_name;
		}
		$data["employees"] = $array_employees;


		$data["fallas_comunes"] = array(
			null => "Seleccione",
			"Display partido" => "Display partido",
			"Táctil" => "Táctil",
			"Apagado" => "Apagado",
			"Software" => "Software",
			"Puerto de Carga" => "Puerto de Carga",
			"Baño Químico" => "Baño Químico",
			"Cambio de Táctil" => "Cambio de Táctil",
			"Cambio de Pantalla" => "Cambio de Pantalla",
			"Cambio de Batería" => "Cambio de Batería",
			"Cambio de Audio" => "Cambio de Audio",
			"Otro" => "Otro");

		$data["id_customers_type"] = array(null => "Seleccione", "Pasaporte" => "Pasaporte", "Cedula" => "Cedula", "DNI" => "DNI");

		$data["teams"] = array(null => "SELECCIONAR", "CELULAR" => "CELULAR", "TABLET" => "TABLET", "Ps3" => "Ps3", "Ps4" => "Ps4", "OTRO" => "OTRO");
		$data["Id_support"] = $id_support;
		$data["states"] = array(lang("technical_supports_recibido") => lang("technical_supports_recibido"), lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"),
			lang("technical_supports_aprobado") => lang("technical_supports_aprobado"), lang("technical_supports_rechazado") => lang("technical_supports_rechazado"),
			lang("technical_supports_reparado") => lang("technical_supports_reparado"), lang("technical_supports_retirado") => lang("technical_supports_retirado"));
		$data["id_employee_login"] = $this->Employee->get_logged_in_employee_info()->person_id;

		$data["payment"] = $this->support_payment->get_sum_payment($id_support);
		$data["support"] = $this->technical_support->get_info_by_id($id_support, false);

		//$data["customer"]=$this->Customer-> get_info($data["support"]->id_customer);

		$data["customer"] = $this->Customer->get_info($clientID);
		$data['ubiequipos'] = $this->Appconfig->get_ubica_equipos();
		$data['tservice'] = $this->Appconfig->get_tipo_servicios();
		$data['tfallas'] = $this->Appconfig->get_tipo_fallas();

		$this->load->view("technical_supports/cliente/servicios", $data);

	}*/


	/*function view($id_support=-1){
		$this->check_action_permission('add_update');
		$array_employees=array();
		$employees=$this->Employee->get_multiple_info_all()->result();
		foreach($employees as $employee){
			$array_employees["$employee->person_id"]=$employee->person_id." - ".$employee->first_name." ". $employee->last_name;;
		}
		$data["employees"]=$array_employees;
		
	
		$data["fallas_comunes"]=array(
			null=>"Seleccione",
			"Display partido"=>"Display partido",
			"Táctil"=>"Táctil",
			"Apagado"=>"Apagado",
			"Software"=>"Software",
			"Puerto de Carga"=>"Puerto de Carga",
			"Baño Químico"=>"Baño Químico",
			"Cambio de Táctil"=>"Cambio de Táctil",
			"Cambio de Pantalla"=>"Cambio de Pantalla",
			"Cambio de Batería"=>"Cambio de Batería",
			"Cambio de Audio"=>"Cambio de Audio",
			"Otro"=>"Otro");
		
		$data["id_customers_type"]=array(null=>"Seleccione","Pasaporte"=>"Pasaporte","Cedula"=>"Cedula","DNI"=>"DNI");
		
		$data["teams"]=array(null=>"SELECCIONAR","CELULAR"=>"CELULAR", "TABLET"=>"TABLET","Ps3"=>"Ps3","Ps4"=>"Ps4","OTRO"=>"OTRO");
		$data["Id_support"]=$id_support;
		$data["states"]=array(lang("technical_supports_recibido")=>lang("technical_supports_recibido"),lang("technical_supports_diagnosticado")=>lang("technical_supports_diagnosticado"),
		lang("technical_supports_aprobado")=>lang("technical_supports_aprobado"),lang("technical_supports_rechazado")=>lang("technical_supports_rechazado"),
		lang("technical_supports_reparado")=>lang("technical_supports_reparado"),lang("technical_supports_retirado")=>lang("technical_supports_retirado"));

		$data["payment"]=$this->support_payment->get_sum_payment($id_support);
		$data["support"]=$this->technical_support->get_info_by_id($id_support,false);
		
		$data["customer"]=$this->Customer-> get_info($data["support"]->id_customer);
		$this->load->View('technical_supports/form.php',$data);

	}*/

	function get_info_customer()
	{
		$customer_id = $this->input->post("customer");
		$customer_data = $this->Customer->get_info($customer_id);
		$data = array(
			"id" => $customer_data->id,
			"account_number" => $customer_data->account_number,
			"first_name" => $customer_data->first_name,
			"last_name" => $customer_data->last_name,
			"email" => $customer_data->email,
			"phone_number" => $customer_data->phone_number
		);
		echo json_encode($data);
	}

	function imprime_order($id_support = -1)
	{
		if ($this->input->get("ventana") == 1) {
			$id_support = $this->input->get("idOrd");
		}
		$info_order = $this->technical_support->get_info_by_id($id_support, false);
		if (is_numeric($info_order->Id_support)) {
			$data_support = array(
				"id_customer" => $info_order->id_customer,
				"id_employee_receive" => $info_order->id_employee_receive,
				"id_employee_register" => $info_order->id_employee_register,
				"model" => $info_order->model,
				"custom1_support_name" => $info_order->custom1_support_name,
				"custom2_support_name" => $info_order->custom2_support_name,
				"do_have_accessory" => $info_order->do_have_accessory,
				"accessory" => $info_order->accessory,
				"damage_failure" => $info_order->damage_failure,
				"damage_failure_peronalizada" => $info_order->damage_failure_peronalizada,
				"state" => $info_order->state,
				"order_support" => $info_order->order_support,
				"type_team" => $info_order->type_team,
				"id_technical" => $info_order->id_technical,
				"date_register" => $info_order->date_register,
				"repair_cost" => $info_order->repair_cost,
				"marca" => $info_order->marca,
				"color" => $info_order->color,
				"observaciones_entrega" => $info_order->observaciones_entrega,
				"date_garantia" => $info_order->date_garantia,
				"do_have_guarantee" => $info_order->do_have_guarantee,
				"retirado_por" => $info_order->retirado_por,
				"color" => $info_order->marca
			);
			$data_support["date_register"] = date(get_date_format() . ' ' . get_time_format(), strtotime(date("Y-m-d H:i:s")));

			$info_employee = $this->Employee->get_info($data_support["id_employee_receive"]);
			$data_support["name_employee"] = $info_employee->first_name . " " . $info_employee->last_name;
			$info_employee = $this->Employee->get_info($data_support["id_technical"]);
			$data_support["name_tecnico"] = $info_employee->first_name . " " . $info_employee->last_name;


			$data_support["payments"] = $this->support_payment->get_all_by_support($id_support);
			$data_support["customer"] = $this->Customer->get_info($data_support["id_customer"]);
			$data_support["Id_support"] = $id_support;
			$this->carrito_lib->cargar_cart($id_support);
			$respuestos=$this->carrito_lib->get_cart();
			$data_support["spare_parts"] = $this->CarritoModel->get_respuesto_viejos($id_support);
			$data_support["spare_parts_new"] = $respuestos;
			//$data_suppor["abonado"] = $this->support_payment->get_sum_payment($id_support);
			$data_support["precio_total"]=$this->carrito_lib->getPrecioTotal();
			//$data['subtotal_total']=  $this->carrito_lib->getPrecioSubtotal();
			//$data['articulos_total']=  $this->carrito_lib->getArticulosTotal();
			//$data['articulos_iva']= $this->carrito_lib->getIvaProductos();
			//$data["saldo_restante"]=$data['precio_total']-$data['abonado'];	
			//$diferencia= $precio_total-$abonado;
			//$data['payments']= $this->carrito_lib->getPagos();
			if ($this->input->get("ventana") == 1) {
				$this->load->View('technical_supports/cliente/imprit_orden', $data_support);
			} else {
				$this->load->View('technical_supports/receipt', $data_support);
			}
		} else echo "<script>$.alert('Orden no existe');</script>";
	}


	public function search()
	{
		$this->check_action_permission('search');
		$search = $this->input->post('search');
		$state = $this->input->post('state');
		$table_data = $this->technical_support->search($search, $state);

		$data["total_rows"] = $table_data->num_rows();
		$data['manage_table'] = get_support_manage_table($table_data, $this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => ""));
	}

	function repair($id_support = -1, $is_ajax = 0)
	{
		$this->check_action_permission('add_update');
		if ($id_support == '' Or $id_support == '-1') {
			$id_support = $this->input->get('hc');
		}
		$this->carrito_lib->cargar_cart($id_support);
		$data['controller_name'] = strtolower(get_class());
//		$data["options"]=array(null=>"",lang("technical_supports_recibido")=>lang("technical_supports_recibido"),lang("technical_supports_diagnosticado")=>lang("technical_supports_diagnosticado"),
//			lang("technical_supports_aprobado")=>lang("technical_supports_aprobado"),lang("technical_supports_rechazado")=>lang("technical_supports_rechazado"),
//			lang("technical_supports_reparado")=>lang("technical_supports_reparado"),lang("technical_supports_retirado")=>lang("technical_supports_retirado"));
		$data["options"] = array(null => "", lang("technical_supports_recibido") => lang("technical_supports_recibido"), lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"));
		$support = $this->technical_support->get_info_by_id($id_support, false);
		$data["support"] = $support;
		$data["customer"] = $this->Customer->get_info($data["support"]->id_customer);

		//$data["spare_parts"] = $this->technical_support->det_spare_part_all_by_id_order($id_support);
		$data["dataDiagnostico"] = $this->technical_support->get_diagnosticos($id_support);
		$data["result"] = $this->technical_support->get_info_equipo($id_support)->row();
		$data["abono"] = $this->technical_support->get_info_abono_orden($id_support);
		$data["support_id"]=$id_support;	
		//$this->load->view('technical_supports/tecnico/reparar_inicial', $data);

		$this->reload_reparar($data,false,false);

		//$this->load->view('technical_supports/tecnico/buscar_servicio', $data);
	
		//$this->_reload($data, $is_ajax);

		//$this->load->View('technical_supports/repair',$data);

	}

	function add_spare_part()
	{
		$this->check_action_permission('add_update');
		if ($this->input->is_ajax_request() && $this->input->post()) {
			$this->form_validation->set_rules('id_support', 'id orden', 'required|numeric');
			$this->form_validation->set_rules('quantity', 'cantidad', 'required|numeric');
			$this->form_validation->set_rules('serie', 'serie', 'required');
			if ($this->form_validation->run() == TRUE) {
				$id_support = $this->input->post('id_support');
				$serie = $this->input->post('serie');
				$name = $this->input->post('name_spare_part');
				$quantity = $this->input->post('quantity');
				$data_spare_part = array(
					"serie" => $serie,
					"name" => $name,
					"quantity" => $quantity,
					"id_support" => $id_support
				);
				$this->technical_support->add_spare_part($data_spare_part);
			}
		}
		redirect("technical_supports/repair/" . $id_support . "/1");
	}
/*
	function delete_spare($id)
	{
		$this->check_action_permission('delete');
		if ($this->input->is_ajax_request()) {
			echo $this->technical_support->delete_spare_part_by_id($id);
		} else { 
			echo false;
		}
	}*/

	/*function _reload($data = array(), $is_ajax = 1)
	{
		if ($is_ajax == 1) {
			$this->load->view("technical_supports/repair", $data);
		} else {
			$this->load->View('technical_supports/repair_initial', $data);
		}
	}*/

	function rechazar($id_support)
	{
		$this->check_action_permission('add_update');
		$data_["state"] = lang("technical_supports_rechazado");
		$this->technical_support->set_state_by_id($id_support, $data_);
		redirect("technical_supports");
	}

	function aceptar($id_support)
	{
		$this->check_action_permission('add_update');
		$data_["state"] = lang("technical_supports_aprobado");
		$this->technical_support->set_state_by_id($id_support, $data_);
		redirect("technical_supports");
		//redirect("technical_supports/repair/$id_support");
	}

	function return_state($id_support, $new_state = "RECIBIDO")
	{
		$this->check_action_permission('add_update');
		if ($this->input->is_ajax_request()) {
			$data_["state"] = $new_state;
			$this->technical_support->set_state_by_id($id_support, $data_);
			redirect("technical_supports/repair/" . $id_support . "/1");
		} else {
			redirect("technical_supports/repair/" . $id_support . "/0");
		}

	}

	function repair_save($id_support = -1)
	{
		$this->check_action_permission('add_update');
		
		$support = $this->technical_support->get_info_by_id($id_support, false);
		if ($support->state == lang("technical_supports_recibido")) {
			$data_["state"] = lang("technical_supports_diagnosticado");
			$data_["technical_failure"] = $this->input->post('damage_failure');
			$this->technical_support->set_state_by_id($id_support, $data_);
		}
		if ($support->state == lang("technical_supports_diagnosticado") || $support->state == lang("technical_supports_aprobado")) {
			$data_["state"] = lang("technical_supports_reparado");
			$this->technical_support->set_state_by_id($id_support, $data_);
		}
		if ($support->state == lang("technical_supports_reparado") || $support->state == lang("technical_supports_rechazado")) {
			$data_["state"] = lang("technical_supports_retirado");
			$data_["repair_cost"] = $this->input->post('repair_cost');
			$data_["observaciones_entrega"] = $this->input->post('observacion_entrega');
			$data_["date_garantia"] = $this->input->post('date_garantia') ? date('Y-m-d', strtotime($this->input->post('date_garantia'))) : "0000-000-00";
			if (!$this->input->post("do_have_guarantee")) {
				$data_["date_garantia"] = "0000-00-00";
			}
			$data_["date_entregado"] = date("Y-m-d H:i:s");
			$data_["do_have_guarantee"] = $this->input->post("do_have_guarantee");
			$this->technical_support->set_state_by_id($id_support, $data_);
			//redirect("technical_supports");
		}
		redirect("technical_supports/repair/" . $id_support . "/1");

	}

	function get_info_customer_by_account_number()
	{
		$data = array();
		if ($this->input->is_ajax_request()) {
			$info_customer = $this->Customer->get_info_by_account_number($this->input->post('account_number'));
			$data["account_number"] = $info_customer->account_number;
			$data["first_name"] = $info_customer->first_name;
			$data["last_name"] = $info_customer->last_name;
			$data["email"] = $info_customer->email;
			$data["phone_number"] = $info_customer->phone_number;
			$data["id_customer"] = $info_customer->person_id;

		}
		echo json_encode($data);
	}

	function save()
	{
		$this->check_action_permission('add_update');

		$this->form_validation->set_rules('id_employee', 'Empleado', 'required|numeric');
		$this->form_validation->set_rules('id_technical', 'Empleado', 'required|numeric');
		$this->form_validation->set_rules('team_type', 'Equipo', 'required');
		$this->form_validation->set_rules('marca', 'marca', 'required');
		$this->form_validation->set_rules('model', 'modelo', 'required');
		$this->form_validation->set_rules('damage_failure', 'Daño o falla', 'required');
		$this->form_validation->set_rules('state', 'Estado', 'required');
		$id_support= $this->input->post('support_id');
		$customer_id= $this->input->post('customer_id');
		if ($this->form_validation->run() == FALSE) {
			$this->load->View('technical_supports/error_order', array("mensaje" => "Error al registrar la orden"));
			return;
		} else {
			$payments = $this->input->post('payments');
			$update_date = $this->input->post('update_date');
			$data_support = array(
				"id_customer" => $customer_id,
				"id_employee_receive" => $this->input->post('id_employee'),
				"model" => $this->input->post('model'),
				"custom1_support_name" => $this->input->post('custom1_support_name'),
				"custom2_support_name" => $this->input->post('custom2_support_name'),
				"do_have_accessory" => $this->input->post('do_have_accessory'),
				"accessory" => $this->input->post('accessory'),
				"damage_failure" => $this->input->post('damage_failure'),
				"damage_failure_peronalizada" => $this->input->post('damage_failure_peronalizada'),
				"state" => $this->input->post('state'),
				"order_support" => 0,
				"deleted" => 0,
				"type_team" => $this->input->post('team_type'),
				"id_location" => $this->Employee->get_logged_in_employee_current_location_id(),
				"id_technical" => $this->input->post('id_technical'),
				//"date_register" => date("Y-m-d H:i:s"),
				"repair_cost" => $this->input->post('repair_cost'),
				"marca" => $this->input->post('marca'),
				"color" => $this->input->post('color'),
				"ubi_equipo" => $this->input->post('ubi_equipo')
			);
			$show_receipt=$this->input->post('show_receipt');
			if ($id_support == -1) {
				// empledo que hace el registro en el sistema no el que resive
				$data_support["id_employee_register"] = $this->Employee->get_logged_in_employee_info()->person_id;
				$data_support["date_register"] = date("Y-m-d H:i:s");
			}else{
				$suport_info= $this->technical_support->get_info_by_id($id_support);
				$data_support["date_register"]= $suport_info->date_register;

			}
			$payments_data = array();
			if ($payments) {
				$payments_data[] = array(
					"payment" => $payments,
					"deleted" => 0,
					"date" => date("Y-m-d H:i:s"),
					"type" => "--"
				);
			}
			$id_support = $this->technical_support->save($data_support, $id_support, $payments_data);

			if ($id_support <=0) {
				$this->load->View('technical_supports/error_order', array("mensaje" => "Error al registrar la orden"));
				return;
			}
			if($show_receipt){
				redirect('technical_supports');
			}

			$empleado_recive = $this->Employee->get_info($data_support["id_employee_receive"]);

			$tecnico = $this->Employee->get_info($data_support["id_technical"]);

			$data_support["date_register"] = date(get_date_format() . ' ' . get_time_format(), strtotime($data_support["date_register"]));

			$data_support["name_employee"] = $empleado_recive->first_name . " " . $empleado_recive->last_name;

			$data_support["name_tecnico"] = $tecnico->first_name . " " . $tecnico->last_name;

			$data_support["abono"] = $payments ? $payments : 0;

			$data_support["payments"] = $this->support_payment->get_all_by_support($id_support);

			$data_support["customer"] = $this->Customer->get_info($data_support["id_customer"]);

			$data_support["Id_support"] = $id_support;

			$this->load->View('technical_supports/receipt', $data_support);

		}

	}

	//////////////////////////////Modulo de Servicio Tecnico
	function ver_historial_cliente_serv()
	{
		$this->check_action_permission('add_update');
		
		$dataCliente['hCliente'] = $this->technical_support->get_historial_serv_tec_cliente($this->input->get('hc'));
		$dataCliente['controller_name'] = strtolower(get_class());

		$this->load->View('technical_supports/cliente/repair_h_cliente', $dataCliente);
	}
	
	function get_fallas_tecnicas($supprt_id){
		$dataDiagnostico = $this->technical_support->get_diagnosticos($idSupport);
		$dataSupport = $this->technical_support->get_datos_support($idSupport);
	}
	function ingresar_fallas_tecnica()
	{
		$this->check_action_permission('add_update');
		$status = "";
		$tiene = "";
		$inst = "";
		$elm = "";
		$dataAcc = $this->input->get('vIng');
		$idSupport = $this->input->get('supprt');

		$dataRespServ = $this->technical_support->det_spare_part_all_by_id_order($idSupport);
		//if($dataRespServ->num_rows() > 0) { $tiene=1; }

		if ($this->input->get('eld') != '' and $tiene == '') {
			$this->technical_support->delete_diagnostico($this->input->get('eld'), $idSupport, $this->input->get('ant'));
			$elm = "1";
		}

		if ($this->input->get('diag') != "" and $this->input->get('eld') == '') {
			$diagnostico['diag'] = $this->input->get('diag');
			$diagnostico['idSupport'] = $idSupport;
			$this->technical_support->add_diagnostico($diagnostico);
			$inst = "1";
		}

		$dataDiagnostico = $this->technical_support->get_diagnosticos($idSupport);
		$dataSupport = $this->technical_support->get_datos_support($idSupport);
		foreach ($dataSupport->result() as $dataSupports) {
			$status = $dataSupports->state;
		}

		$this->load->View('technical_supports/tecnico/buscar_servicio_asig_diag', compact("dataAcc", "idSupport", "dataSupport", "dataDiagnostico", "tiene", "dataRespServ", "inst", "elm"));
	}

	#==================CARRITO MODIFICACION DE METODO asignar_detalles_falla_tec============================

	/*function asignar_detalles_falla_tec()
	{
		$current_location = $this->Employee->get_logged_in_employee_current_location_id();

		$idSupport = $this->input->get('supprt');

		$cliente_equipo = $this->technical_support->getClienteEquipo("$idSupport");

		$this->carrito_lib->updateCarrito("$idSupport");

		$this->carrito_lib->updateCarrito("$idSupport");

		$data['precio_total'] =  $this->carrito_lib->getPrecioTotal();
		$data['precio_subtotal'] =  $this->carrito_lib->getPrecioSubtotal();
		$data['articulos_total'] =  $this->carrito_lib->getArticulosTotal();
		$data['articulos_iva'] = $this->carrito_lib->getIvaProductos();
		$data['carrito'] = $this->carrito_lib->getContenidoCarrito();

		//$this->check_action_permission('add_update');

		$status = "";
		$tiene = "";
		$dataST = "";
		$dataRespr = "";
		$data['instrp'] = "";
		$data['elmrp'] = "";
		$data['dataCond'] = "";
		$data['t_resp'] = "";


		if ($this->input->get('eld') != '') {
			$this->technical_support->delete_spare_part_by_id($this->input->get('eld'), $idSupport);
			$data['elmrp'] = "1";
		}

		if ($this->input->get('codigo') != '' and $this->input->get('respuesto') != '' and $this->input->get('cant') != '') {
			$dataRespr = $this->input->get('respuesto');
			$data_spare_part = array(
				"serie" => $this->input->get('codigo'),
				"name" => $this->input->get('respuesto'),
				"quantity" => $this->input->get('cant'),
				"id_support" => $idSupport
			);
			$this->technical_support->add_spare_part($data_spare_part);
			$data['instrp'] = "1";
		}

		if ($this->input->get('acept') == '2') {
			$data['dataCond'] = "REPARADO";
			$dataST['fecha_garantia'] = $this->input->get('fecha_garantia');
			if ($dataST['fecha_garantia'] != '') {
				$dataST['garantia'] = 1;
			} else {
				$dataST['garantia'] = 0;
			}
			$dataST['comentarios'] = $this->input->get('comentarios');
			$dataST['costo'] = $this->input->get('costo');


			foreach ($this->carrito_lib->getContenidoCarrito() as $row) {

				$data_carrito = [
					'repuesto_support' => $idSupport,
					'repuesto_item' => $row['id'],
					'repuesto_precio' => $row['precio'],
					'respuesto_cantidad' => $row['cantidad'],
					'repuesto_iva_uno' => $row['iva_uno'], 
					'repuesto_iva_dos' => $row['iva_dos'],
					'repuesto_iva_tres' => $row['iva_tres'],
					'repuesto_iva_cuatro' => $row['iva_cuatro'],
					'repuesto_iva_cinco' => $row['iva_cinco'],
					'repuesto_subtotal' => $row['subtotal'],
					'repuesto_total' =>  $row['total'],
					'repuesto_location' => $current_location,
					'repuesto_persona' => $cliente_equipo->id_customer,
					'repuesto_fecha' => 'CURRENT_DATE',
					'created_at' => 'CURRENT_DATE',
				];

				$this->CarritoModel->guardarCarrito($data_carrito);

				$this->carrito_lib->destroy("$idSupport");
			}

		}

		$dataRespuesto = $this->technical_support->det_spare_part_all_by_id_order($idSupport);

		if ($this->input->get('rechaz') == '2') {
			$data['dataCond'] = "RECHAZADO";
			$dataST['garantia'] = 0;
			$dataST['fecha_garantia'] = '';
			$dataST['comentarios'] = "";
			$dataST['costo'] = "0";
			if ($dataRespuesto->num_rows() > 0) {
				$tiene = 1;
			}
		}
		if (($this->input->get('acept') == '2' or ($this->input->get('rechaz') == '2' and $tiene == '')) and $dataRespr == '') {
			$data['eDatos'] = $this->technical_support->get_sev_tec_cliente($idSupport, "1");
			$this->technical_support->updat_status_serv_tecnico($idSupport, $data['dataCond'], $dataST);
			if ($this->config->item("st_correo_of_items") == '1') {
				$this->load->library('Email_send');
				$para = $data['eDatos']->email;
				$subject = "Servicio esta {$data['dataCond']}";
				$name = "";
				$company = $this->config->item('company');
				$from = $this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
				$this->email_send->send_($para, $subject, $name,
					$this->load->view("technical_supports/correo/env_correo", $data, true), $from, $company);
			}
		}
		if ($this->input->get('rechaz') == '2' and $tiene == '1') {
			$data['t_resp'] = 1;
			$data['dataCond'] = "";
		}
		$data['idSupport'] = $idSupport;
		$data['dataSupport'] = $this->technical_support->get_datos_support($idSupport);
		$this->load->view('technical_supports/carrito/index', $data);
		//$this->load->View('technical_supports/tecnico/ingfallatecnica', compact("dataAcc","idSupport","dataSupport","dataRespuesto","instrp","elmrp","dataCond","t_resp"));

	}
	*/

	function detalles_serv_tecnico()
	{
		$this->check_action_permission('add_update');
		$idSupport = $this->input->get('hc');
		if ($this->input->get('Entregar') == '1') {
			$dataEnt['nota'] = $this->input->get('nota');
			$this->technical_support->updat_status_serv_tec_cliente($idSupport, $dataEnt);
			echo "<script>$.alert('El equipo fue marcado como entregado.');</script>";
		}
		$dataDiagnostico   = $this->technical_support->get_diagnosticos($idSupport);
		$respuestos_viejos    = $this->CarritoModel->get_respuesto_viejos($idSupport);
		$dataServTecCliente= $this->technical_support->get_sev_tec_cliente($idSupport)->row();
		$abonado  = $this->support_payment->get_sum_payment($idSupport);
		$this->carrito_lib->cargar_cart($idSupport);
		$respuestos=$this->carrito_lib->get_cart();

		$precio_total= $this->carrito_lib->getPrecioTotal();
		//$data['subtotal_total']=  $this->carrito_lib->getPrecioSubtotal();
		//$data['articulos_total']=  $this->carrito_lib->getArticulosTotal();
		//$data['articulos_iva']= $this->carrito_lib->getIvaProductos();
		//$data["saldo_restante"]=$data['precio_total']-$data['abonado'];	
		$diferencia= $precio_total-$abonado;
		//$data['payments']= $this->carrito_lib->getPagos();	

		$this->load->View('technical_supports/cliente/orden_entrega', 
		compact("dataServTecCliente", 
		"dataDiagnostico", 
		"respuestos_viejos",
		 "abonado",
		 "respuestos",
		"precio_total",
		"diferencia"));
	}

	function ver_opciones()
	{
		$this->check_action_permission('add_update');
		$idSupport = $this->input->get('supprt');
		if ($this->input->get('cambOpcion') == '1') {
			$cambOpcion = $this->input->get('cambOpcion');
			$verListaDiagnostico = '';
			$servicios = $this->technical_support->get_datos_support($idSupport);
			$this->load->View('technical_supports/apcion/apcion', compact("servicios", "cambOpcion", "verListaDiagnostico"));
		}
		if ($this->input->get('verListaDiagnostico') == '1') {
			$verListaDiagnostico = $this->input->get('verListaDiagnostico');
			$cambOpcion = '';
			$dataDiagnostico = $this->technical_support->get_diagnosticos($this->input->get('supprt'));
			$dataSupport = $this->technical_support->get_datos_support($this->input->get('supprt'));
			$this->load->view("technical_supports/apcion/apcion", compact("dataDiagnostico", "dataSupport", "cambOpcion", "verListaDiagnostico"));
		}
	}

	 public function buscar_cliente_serv_tecnico() {
            $this->check_action_permission('add_update');  
            $data['controller_name'] = strtolower(get_class());
            $t=  $this->input->get('t'); 
            $searchword =  $this->input->get('ciCont');  
			$suggestions = $this->Customer->get_customer_search_suggestions($searchword,25);
            $ListarBusquedaT=$this->technical_support->buscar_servicios_total($searchword);
            $ListarBusqueda=$this->technical_support->buscar_servicios($searchword,$this->input->get('bas'));            
            $this->load->view("technical_supports/cliente/buscar_servivios", compact("ListarBusqueda","ListarBusquedaT","t","data","suggestions")); 
    }

	public function actualizar_diagnostico()
	{
		$this->check_action_permission('add_update');	
		$resultado= $this->technical_support->updat__diagnosticos($this->input->post('id_diagnostico'), $this->input->post('nuevo_diagnostico'));
		if($resultado){
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_mod_diag_titu_menj"),"id"=>$this->input->post('id_diagnostico'));
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);

		
	}

	function ver_servicios_activos_resumen()
	{
		$data['servicios'] = $this->technical_support->lista_servicios();
		$this->load->View('technical_supports/manage_resumen', $data);
	}


	#==================METODOS CARRITO SERVICIO======================

	public function carritoServicio()
	{
		$idSupport = $this->input->get('hc');
		$data ['support_id'] = $this->input->get('hc');
		$data["is_cart_reparar"]=0;
		$this->carrito_lib->cargar_cart($idSupport);
		$this->carrito_lib->set_comment_ticket($this->config->item('default_sales_type'));

		$this->reload_cart($data,false);
		
	}

	public function buscarItems()
	{
		$buscar = $this->input->get('buscar');
		$data['idSupport'] = $this->input->get('idSupport');
		$data['busqueda'] = $this->CarritoModel->buscarItems("$buscar");
		$this->load->view("technical_supports/carrito/resultado_busqueda", $data);
	}

	public function agregarItem()
	{
		$item = $this->input->get('id');
		$this->carrito_lib->add_item($item);
		$idSupport = $this->input->get('idSupport');
		$location = $this->Employee->get_logged_in_employee_current_location_id();
		//$this->carrito_lib->agregarItem("$item", "$location", "$idSupport");
		$data['precio_total'] =  $this->carrito_lib->getPrecioTotal();
		$data['subtotal_total'] =  $this->carrito_lib->getPrecioSubtotal();
		$data['articulos_total'] =  $this->carrito_lib->getArticulosTotal();
		$data['articulos_iva'] = $this->carrito_lib->getIvaProductos();
		$data['carrito'] = $this->carrito_lib->get_cart();
		$data['abonado']= $this->support_payment->get_sum_payment($idSupport);
		$data["saldo_restante"]=$data['precio_total']-$data['abonado'];	
		$data["a_pagar"]= $data["saldo_restante"]-$this->carrito_lib->get_pagos_agregados();

		$data["support_id"] = $idSupport;
		$data["is_cart_reparar"]=1;
		
		
		$this->load->view("technical_supports/carrito/table_items", $data);
	}

	public function updateCantidad()
	{
		$item = $this->input->get('id');
		$cantidad = $this->input->get('cantidad');
		$idSupport = $this->input->get('idSupport');
		$location = $this->Employee->get_logged_in_employee_current_location_id();

		$this->carrito_lib->updateCantidad("$item", "$cantidad", "$location", "$idSupport");
		$data['precio_total'] =  $this->carrito_lib->getPrecioTotal();
		$data['subtotal_total'] =  $this->carrito_lib->getPrecioSubtotal();
		$data['articulos_total'] =  $this->carrito_lib->getArticulosTotal();
		$data['articulos_iva'] = $this->carrito_lib->getIvaProductos();
		$data['carrito'] = $this->carrito_lib->getContenidoCarrito();
		$data["idSupport"] = $idSupport;
		$this->load->view("technical_supports/carrito/table_items", $data);
	}

	public function eliminarArticulo()
	{
		$line = $this->input->get('id');
		$idSupport = $this->input->get('idSupport');
		$data['resultado'] = $this->carrito_lib->delete_item($line);
		$data['precio_total'] =  $this->carrito_lib->getPrecioTotal();
		$data['subtotal_total'] =  $this->carrito_lib->getPrecioSubtotal();
		$data['articulos_total'] =  $this->carrito_lib->getArticulosTotal();
		$data['articulos_iva'] = $this->carrito_lib->getIvaProductos();
		$data['carrito'] = $this->carrito_lib->getContenidoCarrito();
		$data["idSupport"] = $idSupport;
		$this->load->view("technical_supports/carrito/table_items", $data);
	}

	//CARRITO DE AFUERA

	public function buscarItemsSupport()
	{
		$buscar = $this->input->get('buscar');
		$data['idSupport'] = $this->input->get('idSupport');
		$data['busqueda'] = $this->CarritoModel->buscarItems("$buscar");
		$this->load->view("technical_supports/carrito/resultado_busqueda_afuera", $data);
	} 
	function guardar_cart($is_ajax=true){
		$support_id = $this->input->post("support_id");
		$cart= $this->carrito_lib->get_cart();
		$resultado=!$this->CarritoModel->agregar_respustos($cart,$support_id);
		if(!$is_ajax){
			return $resultado;
		}
		if($resultado){
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_respuesto_add"));
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);
	}
	function reload_reparar($data=array(),$cart=false, $is_ajax=false){
			$data['precio_total']=  $this->carrito_lib->getPrecioTotal();
			$data['subtotal_total']=  $this->carrito_lib->getPrecioSubtotal();
			$data['articulos_total']=  $this->carrito_lib->getArticulosTotal();
			$data['articulos_iva']= $this->carrito_lib->getIvaProductos();
			$data['carrito']=$this->carrito_lib->get_cart();		
			$data['payments']= $this->carrito_lib->getPagos();
			$data['abonado']= $this->support_payment->get_sum_payment($data ['support_id']);
			$data["saldo_restante"]=$data['precio_total']-$data['abonado'];	
			$data["a_pagar"]= $data["saldo_restante"]-$this->carrito_lib->get_pagos_agregados();
			$data['otros_respustos']= $this->CarritoModel->get_respuesto_viejos($data ['support_id']);
			$data['is_cart_reparar']= 1;	
			
			if(!$cart and !$is_ajax){
				// se carga toda la vista reparar 
				$this->load->view('technical_supports/tecnico/reparar_inicial', $data);
			}
			if($cart){
				//solo se carga el carrito de repracion
				$this->load->view('technical_supports/carrito/cart_reparar', $data);

			}


	}
	function buscar_servicios(){
        session_write_close();		
		$suggestions = $this->technical_support->get_search_suggestions($this->input->get('term'),30);
		echo json_encode($suggestions);
    }
	function reload_cart($data=array(),$is_ajax=true){

			$data['dataServTecCliente'] = $this->technical_support->get_sev_tec_cliente($data ['support_id'], "1");
			//$data['dataRespuesto']= $this->technical_support->det_spare_part_all_by_id_order($data ['support_id']);
			//$data['dataRespuestomas']= $this->technical_support->det_spare_part_all_by_id_order_respuesto($data ['support_id']);
			$data['abonado']= $this->support_payment->get_sum_payment($data ['support_id']);
			$data['precio_total']=  $this->carrito_lib->getPrecioTotal();
			$data['subtotal_total']=  $this->carrito_lib->getPrecioSubtotal();
			$data['articulos_total']=  $this->carrito_lib->getArticulosTotal();
			$data['articulos_iva']= $this->carrito_lib->getIvaProductos();
			$data['carrito']= $this->carrito_lib->get_cart();	
			$data["saldo_restante"]=$data['precio_total']-$data['abonado'];	
			$data["a_pagar"]= $data["saldo_restante"]-$this->carrito_lib->get_pagos_agregados();
			$data['payments']= $this->carrito_lib->getPagos();
			$data['otros_respustos']= $this->CarritoModel->get_respuesto_viejos($data ['support_id']);
			$data['pagar_otra_moneda']= 0;
			$data["amount_due"]=0;
			$data["change_sale_date_enable"]=1;// para cambiar la fecha de la venta cuando se está editando
			$data["comment"]=$this->carrito_lib->get_comentario();
			$data["change_sale_id"]=false;// cuando se está editando
			$data["payments_cover_total"]=$this->_payments_cover_total($data['support_id']);
			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				//lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit')
			);

			if($this->config->item('customers_store_accounts'))
			{
				$data['payment_options']=array_merge($data['payment_options'],	array(lang('sales_store_account') => lang('sales_store_account')));
			}
		//}

		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}

		$data["is_cart_reparar"]=0;	
		if($is_ajax ){
			// solo se carga el cuerpo del carrito del modal de la vista principal
			$this->load->view("technical_supports/carrito/carrito_cuerpo", $data);
		}else{
			// se carga el modal con el carrito de la vista principal
			$this->load->view("technical_supports/carrito/modal_carrito", $data);
		}

	}
	public function agregarItemSupport()
	{

		$item = $this->input->get('id');
		$support_id = $this->input->get('idSupport');
		//$location = $this->Employee->get_logged_in_employee_current_location_id();
		$this->carrito_lib->add_item($item);
		$data=array("support_id"=>$support_id);
		$this->reload_cart($data,true);

		}

	/*public function updateCantidadSupport()
	{
		$item = $this->input->get('id');
		$cantidad = $this->input->get('cantidad');
		$support_id = $this->input->get('idSupport');
		$location = $this->Employee->get_logged_in_employee_current_location_id();

		$this->carrito_lib->updateCantidad("$item", "$cantidad", "$location", "$support_id");

		
		$data['support_id']= $idSupport;
		$this->reload_cart($data,true);
	}*/
/*
	public function eliminarArticuloSupport()
	{
		$item = $this->input->get('id');
		$uni = $this->input->get('uni');

		$idSupport = $this->input->get('idSupport');

		if ($this->CarritoModel->getResp("$uni")) {
			
			$excepcion = true;
			$this->carrito_lib->updateCarrito($idSupport);
			$this->carrito_lib->updatePrecioCantidad($idSupport);

		} else {

			$excepcion = false;
			$data['resultado'] = $this->carrito_lib->deleteItemCarrito($item, $idSupport);

		}

		$data = [
			'dataServTecCliente' => $this->technical_support->get_sev_tec_cliente($idSupport, "1"),
			'dataRespuesto' => $this->technical_support->det_spare_part_all_by_id_order($idSupport),
			'dataRespuestomas' => $this->technical_support->det_spare_part_all_by_id_order_respuesto($idSupport),
			'dataServTecAbono' => $this->technical_support->get_abonos_serv_tecnico($idSupport),
			'precio_total' =>  $this->carrito_lib->getPrecioTotal(),
			'subtotal_total' =>  $this->carrito_lib->getPrecioSubtotal(),
			'articulos_total' =>  $this->carrito_lib->getArticulosTotal(),
			'articulos_iva' => $this->carrito_lib->getIvaProductos(),
			'carrito' => $this->carrito_lib->getContenidoCarrito(),
			'excepcion' => $excepcion,
			'idSupport' => $idSupport,
			'payments' => $this->carrito_lib->getPagos(),
		];

		$this->load->view("technical_supports/carrito/refrescar_carro_support", $data);
	}
*/
function _payments_cover_total($support_id)
	{
		$total_payments = 0;

		$precio_total =  $this->carrito_lib->getPrecioTotal();	
		$abonado= $this->support_payment->get_sum_payment($support_id);
		$saldo_restante=$precio_total-$abonado;	
		

		foreach($this->carrito_lib->getPagos() as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}
		$round_value=to_currency_no_money(round($saldo_restante - $total_payments));
        $value=to_currency_no_money( $saldo_restante - $total_payments );
        $value_compare=$this->config->item('round_value')==1 ? $round_value : $value;
		/* Changed the conditional to account for floating point rounding */
		if ( /*( $this->sale_lib->get_mode() == 'sale' || $this->sale_lib->get_mode() == 'store_account_payment' )&& */$value_compare > 1e-6  )
		{
			return false;
		}

		return true;
	}
	public function agregarPago()
	{
		
		$data=array();
		$payment_type= $this->input->post('payment_type') ;
		$amount_tendered=$this->input->post('amount_tendered');		
		$data ['support_id'] = $this->input->post('support_id');
		$data["is_cart_reparar"]=0;
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == lang('sales_giftcard') )
				$data['error']=lang('sales_must_enter_numeric_giftcard');
			else
				$data['error']=lang('sales_must_enter_numeric');		
			$this->reload_cart($data,false);
 			return;
		}
		/*if ($this->input->post('payment_type') == lang('sales_store_account') && $this->sale_lib->get_customer() == -1){
				$data['error']=lang('sales_customer_required_store_account');
				$this->reload_cart($data,false);
				return;
		}	*/


		/*if ( $payment_type == lang('sales_giftcard') )
		{
			if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($this->input->post('amount_tendered'))))
			{
				$data['error']=lang('sales_giftcard_does_not_exist');
				$this->reload_cart($data,false);
				return;
			}

			$payment_type=$this->input->post('payment_type').':'.$this->input->post('amount_tendered');
			$current_payments_with_giftcard = $this->sale_lib->get_payment_amount($payment_type);
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value <= 0 && $this->sale_lib->get_total() > 0)
			{
				$data['error']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->reload_cart($data,false);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			$payment_amount=min( $this->sale_lib->get_amount_due(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}*/
		//else
		//{
			$payment_amount=$this->input->post('amount_tendered');
		//}

		if( !is_numeric($payment_amount) ||  !$this->carrito_lib->add_pago( $payment_type, $payment_amount))
		{
			$data['error']=lang('sales_unable_to_add_payment');
		}

		$this->reload_cart($data,false);

	}

	public function delete_pago($payment_id,$support_id)
	{
		$data=array("support_id"=>$support_id);
		$this->carrito_lib->deletePago($payment_id);	
		$this->reload_cart($data,true);	
		
	}

	public function facturar()
	{
		$data['is_sale'] = TRUE;
		$data['cart'] = $this->carrito_lib->get_cart();
		$data['mode']= $this->carrito_lib->get_mode();
		$sale_id_raw=-1;
        $suspended_change_sale_id =false;// $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id();
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
		$support_id= $this->input->post("support_id");
		$support_info= $this->technical_support->get_info_by_id($support_id);
		$serie_number =$this->carrito_lib->get_serie_number();
		$data['support_info']=$support_info;

		if($suspended_change_sale_id)
        {
            $sale_data = $this->Sale->get_info($suspended_change_sale_id)->row();


            if( $this->sale_lib->get_comment_ticket() == 1 and  $sale_data->is_invoice == 1)
            {
                $invoice_type['invoice_number'] = NULL;
                $invoice_type['ticket_number']  = $this->Sale->get_next_sale_number(0,$location_id);
				$invoice_type['is_invoice']     = 0;
				$invoice_type['serie_number_invoice']=$serie_number;
                $data['sale_number']            = $invoice_type['ticket_number'];
            }
            elseif ( $this->sale_lib->get_comment_ticket() == 0 and $sale_data->is_invoice == 0)
            {
                $invoice_type['invoice_number'] = $this->Sale->get_next_sale_number(1,$location_id);
				$invoice_type['ticket_number']  = NULL;
				$invoice_type['serie_number_invoice']=$serie_number;
                $invoice_type['is_invoice']     = 1;
                $data['sale_number']            = $invoice_type['invoice_number'];
            }
            else
            {
                $invoice_type['invoice_number'] = ($sale_data->invoice_number) ? $sale_data->invoice_number : NULL;
                $invoice_type['ticket_number']  = ($sale_data->ticket_number)  ? $sale_data->ticket_number  : NULL;
                $invoice_type['is_invoice']     = ($sale_data->is_invoice) ? 1 : 0;
				$data['sale_number']            = $invoice_type['invoice_number'];
				$invoice_type['serie_number_invoice']=$serie_number;
            }
		}
		else 
        {
            if($this->carrito_lib->get_comment_ticket() == 1)
            {
                $invoice_type['invoice_number'] = NULL;
                $invoice_type['ticket_number']  = $this->Sale->get_next_sale_number(0,$location_id);
                $invoice_type['is_invoice']     = 0;
				$data['sale_number']            = $invoice_type['ticket_number'];
				$invoice_type['serie_number_invoice']=$serie_number;
            }
            else
            {
                $invoice_type['invoice_number'] = $this->Sale->get_next_sale_number(1,$location_id);
                $invoice_type['ticket_number']  = NULL;
                $invoice_type['is_invoice']     = 1;
				$data['sale_number']            = $invoice_type['invoice_number'];
				$invoice_type['serie_number_invoice']=$serie_number;
            }
		}

		$data['sale_type'] = ($this->carrito_lib->get_comment_ticket() == 1) ? lang('sales_ticket_on_receipt') : lang('sales_invoice');

        //Si existe descuento, disminuye la cantidad de productos en descuento (phppos_items -> quantity)
        foreach($data['cart'] as $cart){
            if( isset( $cart["item_id"] ) ){
                if($this->Sale->is_item_promo($cart["item_id"])){
                    $this->Sale->drecrease_promo_quantity($cart["quantity"],$cart["item_id"]);
                }
            }
		}
		if (!$this->_payments_cover_total($support_id))
		{
			echo"Debe pagar el total de la venta";
			//$this->_reload(array('error' => lang('sales_cannot_complete_sale_as_payments_do_not_cover_total')), false);
			return;
		}
		if ($this->config->item('track_cash') == 1) {

			$register_log_id = $this->Sale->get_current_register_log();

			if (!$register_log_id) {
				echo"¡La caja donde se quiere efectuar la operación esta cerrada!";
				//$this->_reload(array('error' => "¡La caja donde se quiere efectuar la operación esta cerrada!"), false);

				return;

			} elseif ($suspended_change_sale_id ) {

				$sale_data = $this->Sale->get_info($suspended_change_sale_id)->row();

				if ($sale_data->location_id != $this->Employee->get_logged_in_employee_current_location_id()) {
					echo"¡Esta no es la tienda donde se realizo la venta!";
					//$this->_reload(array('error' => "¡Esta no es la tienda donde se realizo la venta!"), false);

					return;

				} else {

					$cash        = $this->Sale->get_payment_cash($this->carrito_lib->getPagos());
					$amount_diff = $suspended_change_sale_id ? $this->Sale->get_cash_available($suspended_change_sale_id, $cash) : 0;

					if ($amount_diff < 0) {
						echo"¡La caja no tiene suficiente efectivo para realizar la operación!";
						//$this->_reload(array('error' => "¡La caja no tiene suficiente efectivo para realizar la operación!"), false);

						return;
					}
				}
			}
		}

		//sales_store_account
		/*$data['resultado'] = $this->carrito_lib->delete_item($line);
		$data['precio_total'] =  $this->carrito_lib->getPrecioTotal();
		$data['subtotal_total'] =  $this->carrito_lib->getPrecioSubtotal();
		$data['articulos_total'] =  $this->carrito_lib->getArticulosTotal();
		$data['articulos_iva'] = $this->carrito_lib->getIvaProductos();
		$data['carrito'] = $this->carrito_lib->getContenidoCarrito();
		
		
		$abonado= $this->support_payment->get_sum_payment($idSupport);
		$data["saldo_restante"]=$data['total']-$data['abonado'];	
		$data["a_pagar"]= $data["saldo_restante"]-$this->carrito_lib->get_pagos_agregados();
		*/
		$customer_id=$support_info->id_customer;

		$tier_id = $this->carrito_lib->get_selected_tier_id();
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name; 
		$data["serie_number"]=$serie_number;
		$data['register_name'] = $this->Register->get_register_name($this->Employee->get_logged_in_employee_current_register_id());
		$data['subtotal']=$this->carrito_lib->getPrecioSubtotal();
		$data['detailed_taxes']=$this->carrito_lib->get_detailed_taxes($customer_id);
		$data['detailed_taxes_total']=$this->carrito_lib->get_detailed_taxes_total($customer_id);
		$data['total']=$this->carrito_lib->getPrecioTotal();
		$data['receipt_title']=lang('sales_receipt');
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$sold_by_employee_id=$support_info->id_technical;
		$data['comment'] = $this->carrito_lib->get_comentario();
		$data['show_comment_on_receipt'] = false;
		$data['show_comment_ticket'] = $this->carrito_lib->get_comment_ticket();

		$emp_info=$this->Employee->get_info($employee_id);
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);
		$data['payments']=$this->carrito_lib->getPagos();
		$abonos= $this->support_payment->get_all_by_support($support_id);
		foreach ($abonos as $abono) {
			$data['payments'][]=array(
								"payment_type"=>lang("technical_supports_pay"),
								"payment_amount"=>$abono->payment,
								"payment_date"=>$abono->date,
								"truncated_card"=>"",
								"card_issuer"=>"");
		}
		$abonado= $this->support_payment->get_sum_payment($support_id);
		$data["amount_change"]=($data['total']-($abonado+$this->carrito_lib->get_pagos_agregados()))*-1;	


		$data['is_sale_cash_payment'] = $this->carrito_lib->is_sale_cash_payment();
		$data['balance']=$this->carrito_lib->get_payment_amount(lang('sales_store_account'));
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $employee_id ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		$data['discount_exists'] =false;// $this->_does_discount_exists($data['cart']);
	
		$data["show_number_item"]=$this->config->item('show_number_item');
		$data["show_return_policy_credit"]= ($this->config->item('show_return_policy_credit')==1 and $data['balance']!=0);
	
		if($this->config->item('system_point') && $data['mode'] == 'sale')
		{
			//$this->load->library('sale_lib');
			$total=$data['total'];
			$point_pucharse = $this->carrito_lib->get_point($this->config->item('value_point'),$total);
			$detail = 'Id venta #';
			$this->load->model('points');
			$this->points->save_point($point_pucharse,$customer_id,$detail);
		    $data['points']=$this->Customer->get_info_points($customer_id);
		}
		$data['change_sale_date'] =false;//$this->sale_lib->get_change_sale_date_enable() ?  $this->sale_lib->get_change_sale_date() : false;

		$old_date =false;// $this->sale_lib->get_change_sale_id()  ? $this->Sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
		$old_date=  $old_date ? date(get_date_format().' '.get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format().' '.get_time_format());
		$data['transaction_time']=$old_date;// $this->sale_lib->get_change_sale_date_enable() ?  date(get_date_format().' '.get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;
		$data["overwrite_tax"]=false;
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
			$data['customer_address_1'] = $cust_info->address_1;
			$data['customer_address_2'] = $cust_info->address_2;
			$data['customer_city'] = $cust_info->city;
			$data['customer_state'] = $cust_info->state;
			$data['customer_zip'] = $cust_info->zip;
			$data['customer_country'] = $cust_info->country;
			$data['customer_phone'] = $cust_info->phone_number;
			$data['customer_email'] = $cust_info->email;
		}
				//If we have a previous sale make sure we get the ref_no unless we already have it set
		if ($suspended_change_sale_id )
		{
			$sale_info = $this->Sale->get_info($suspended_change_sale_id)->row_array();
			
		}
		if ($suspended_change_sale_id && $this->config->item('change_sale_date_when_completing_suspended_sale'))
		{
			$data['change_sale_date'] = date('Y-m-d H:i:s');
		}
		$data['store_account_payment'] = $data["mode"] == 'store_account_payment' ? 1 : 0;
		//SAVE sale to database 
		if($data["mode"] == 'sale'  && isset($data['balance']) && $customer_id)
		{
			$add_customer=$this->Sale->add_petty_cash_customer($customer_id, $data['balance']);
		}		
		$deleted_taxes=array();//$this->sale_lib->get_deleted_taxes();

		if($this->config->item('subcategory_of_items') &&  false/*!$this->sale_lib->is_select_subcategory_items()*/){
			$sale_id_raw=-1;
		}
		else{
			$sale_id_raw = $this->Sale->save(
			$data['cart'], $customer_id, $employee_id, $sold_by_employee_id, 
			$data['comment'],$data['show_comment_on_receipt'],$data['payments'], 
			$suspended_change_sale_id, 0,"","", $data['change_sale_date'],
			$data['balance'], $data["mode"],$tier_id,$deleted_taxes,
			$data['store_account_payment'],$data['total'],$data['amount_change'],
			$invoice_type, null,null,null,	null,null,0,null,0,false,null,null,true,
			/*$support_id*/null);
		}	
		$data["sale_id_raw"]=$sale_id_raw;
		if($sale_id_raw>0){
			$this->load->View('technical_supports/receipt_final', $data);
		}
	}
	

#==================FIN CARRITO======================

	function ver_estadisticas_entregados()
	{
		$tipoNomb = $this->input->get('id_tipo');
		$listarTecnico = $this->technical_support->get_tecnicos($this->input->get('id_tipo'));
		$listarFallas = $this->technical_support->get_fallas($this->input->get('id_tipo'));
		$listarServ = $this->technical_support->get_servicio_tipo($this->input->get('id_tipo'));
		$listarTotal = $this->technical_support->get_tecnicos_total($this->input->get('id_tipo'));
		$this->load->View('technical_supports/estadisticas/v_entregados', compact("listarTecnico", "listarFallas", "tipoNomb", "listarTotal", "listarServ"));
	}

	function add_diagnostico_tecnico(){
		$this->check_action_permission('add_update');
		$id_support = $this->input->post('supprt_id');
		$diagnostico=array(			
			'diagnostico'    => $this->input->post('diagnostico') ,
			"fecha"=>date('Y-m-d H:i:s'),
			"id_support"=>$id_support
		 );		
		$resultado= $this->technical_support->add_diagnostico($id_support,$diagnostico);		
		if($resultado){
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_diagnostico_add"),"id"=>$resultado);
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);
	}
	function eliminar_diagnostico($idSupport,$id_diagnostico){
		$this->check_action_permission('add_update');
		$resultado = $this->technical_support->delete_diagnostico($id_diagnostico, $idSupport);
		$soporte = $this->technical_support->get_info_by_id($idSupport);
		$state= $soporte->state;
		if($resultado){
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_diagnostico_delet"),"state"=>$state);
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro","state"=>$state);
		}
		echo json_encode($respuesta);
	}
	public function modal_actualizar_diagnostico($id_sopport, $id_diagnostico)
	{
		$this->check_action_permission('add_update');		
		$dataDiagnostico = $this->technical_support->get_diagnosticos($id_sopport,  $id_diagnostico)->row();
		$this->load->view("technical_supports/tecnico/modal_modificar_diagnostico", compact("dataDiagnostico"));

	}
	function set_entregar(){
		$this->check_action_permission('add_update');
		$idSupport = $this->input->post('support_id');	
		$data=array(
			'retirado_por' => $this->input->post('retirado_por'),
			'date_entregado'=>date('Y-m-d H:i:s'),
			'state'=> lang("technical_supports_entregado")
		);
		$resultado= $this->technical_support->actualizarServicioCliente($idSupport, $data);
		
		if($resultado){
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_asig_estado").$data["state"]);
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);
	
	}
	function set_rechazar(){
		$this->check_action_permission('add_update');
		$id_support = $this->input->post('support_id');
		$state = lang("technical_supports_rechazado");
		$data['garantia'] = 0;
		$data['fecha_garantia'] = '';
		$data['comentarios'] = "";
		$data['costo'] = "0";			
		$resultado= $this->technical_support->updat_status_serv_tecnico($id_support, $state, $data);
		/*$support = $this->technical_support->get_info_by_id($id_support, false);
		$data_supporte["support"] = $support;
		 $this->load->view("technical_supports/tecnico/entrega_garantia",$data_supporte);
		*/
		if($resultado){
			$this->enviar_correo($id_support);
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_asig_estado").$state);
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);
	}
	function set_reparar(){
		$this->check_action_permission('add_update');
		$id_support = $this->input->post('support_id');
		$state = lang("technical_supports_reparado");
		$fecha_garantia= $this->input->post('fecha_garantia');	
		$do_have_guarantee= $this->input->post('do_have_guarantee');	
		$data['fecha_garantia'] = $fecha_garantia;
		$data['garantia'] = (int)$do_have_guarantee;
		$data['comentarios'] = $this->input->post('comentarios');
		$data['costo'] = (double)$this->input->post('costo');

		$resultado= $this->guardar_cart(0);
		$resultado= $resultado && $this->technical_support->updat_status_serv_tecnico($id_support, $state, $data);
		if($resultado){
			$this->enviar_correo($id_support);
			$respuesta=array("respuesta"=>true,"mensaje"=>lang("technical_supports_asig_estado").$state);
		}else{
			$respuesta=array("respuesta"=>false,"mensaje"=>"Errro");
		}
		echo json_encode($respuesta);
	}
	private function enviar_correo($support_id){
		$servicio = $this->technical_support->get_sev_tec_cliente($support_id, "1");
		$data["eDatoss"]=$servicio;
		if ($this->config->item("st_correo_of_items") == '1' and filter_var($servicio->email, FILTER_VALIDATE_EMAIL)) {
			$this->load->library('Email_send');
			$para = $servicio->email;
			$subject = "Servicio está {$servicio->state}";
			$name = "";
			$company = $this->config->item('company');
			$from = $this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
			$this->email_send->send_($para, $subject, $name,
			$this->load->view("technical_supports/correo/env_correo", $data, true), $from, $company);
		}		
	}
	function edit_item($line,$is_cart_reparar,$support_id=false)
	{
		$data= array("support_id"=>$support_id);
		
	//	$this->form_validation->set_rules('discount', 'lang:reports_discount', 'integer');

		$description = $this->input->post("description");
		$serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$custom1_subcategory = $this->input->post("custom1_subcategory");
		$custom2_subcategory = $this->input->post("custom2_subcategory");
		$error=false;
		if(($price!==false and !is_numeric($price) )|| ($quantity!==false and  !is_numeric($quantity))){
			$error=true;
		}
		
		/*$discount = $this->input->post("discount");
                $promo_quantity = isset($item_id) && !empty($item_id) ? $this->Sale->get_promo_quantity($item_id) : 0;
                $is_item_promo = isset($item_id) && !empty($item_id) ? $this->Sale->is_item_promo($item_id) : 0;

       */         //check if theres enough promo items for a sale.
        /*if( ( $promo_quantity  <  $this->input->post("quantity") ) && $is_item_promo ){
                    $data['error']=lang('sales_promo_quantity_less_than_zero');
                }
		*/
		/*if ($discount !== FALSE && $this->input->post("discount") == '')
		{*/
			$discount = 0;
	/*}
		*/
		if(!$error){			
			$this->carrito_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price,$custom1_subcategory,$custom2_subcategory);
		}
		else
		{			
			$data['error']=lang('sales_error_editing_item');
		}	
		
		if($is_cart_reparar){
			$this->reload_reparar($data,true,true);
		}else{
			$this->reload_cart($data,true);
		}	
		
		//$this->_reload($data);
	}
	function delete_spare_old($spare_id,$is_cart_reparar,$support_id)
	{
		$this->check_action_permission('delete');
		$this->CarritoModel->eleminar_respueto_por_id($spare_id,$support_id);
		if($is_cart_reparar){
			$this->reload_reparar(array("support_id"=>$support_id),true,true);
		}else{
			$this->reload_cart(array("support_id"=>$support_id),true);
		}
		
	}
	function delete_item($line,$is_cart_reparar,$support_id=false)
	{
		$this->carrito_lib->delete_item($line);
		if($is_cart_reparar){
			$this->reload_reparar(array("support_id"=>$support_id),true,true);
		}else{
			$this->reload_cart(array("support_id"=>$support_id),true);
		}
	}
	function nuevo($support_id=-1){
		$this->check_action_permission('add_update');
		$support_id= is_numeric($support_id)?$support_id:-1;
		$tiers = array();
		$tiers_result = $this->Tier->get_all()->result_array();
		
		$suppor_info= $this->technical_support->get_info_by_id($support_id);
		$customer_id=$suppor_info->id_customer!=""?$suppor_info->id_customer:-1;
		$support_id=$suppor_info->Id_support!=""?$suppor_info->Id_support:-1;
		$data['support_id'] = $support_id;
		$data["customer_id"]=$customer_id;
		if (count($tiers_result) > 0) {
			$tiers[0] = lang('items_none');
			foreach ($tiers_result as $tier) {
				$tiers[$tier['id']] = $tier['name'];
			}
		}
		$data['controller_name'] = strtolower(get_class());
		$data['tiers'] = $tiers;
		$data['person_info'] = $this->Customer->get_info($customer_id);
		$data['person_info_point'] = $this->Customer->get_info_points($customer_id);
		//$data['redirect_code'] = $redirect_code;
		$this->load->View('technical_supports/nuevo',$data);
	}
	function get_form_servicio($customer_id,$support_id=-1,$data=array()){
		$data["customer_id"]=$customer_id;
		$data["support_id"]=$support_id;
		$array_employees = array();
		$employees = $this->Employee->get_multiple_info_all()->result();
		foreach ($employees as $employee) {
			$array_employees["$employee->person_id"] = $employee->person_id . " - " . $employee->first_name . " " . $employee->last_name;
		}
		$data["employees"] = $array_employees;
		/*
		
		
		


		$data["Id_support"] = $id_support;
		
		

		 */
		$data["payment"] = $this->support_payment->get_sum_payment($support_id);

		$data["states"] = array(lang("technical_supports_recibido") => lang("technical_supports_recibido"), lang("technical_supports_diagnosticado") => lang("technical_supports_diagnosticado"),
			lang("technical_supports_aprobado") => lang("technical_supports_aprobado"), lang("technical_supports_rechazado") => lang("technical_supports_rechazado"),
			lang("technical_supports_reparado") => lang("technical_supports_reparado"), lang("technical_supports_retirado") => lang("technical_supports_retirado"));
		$data["id_employee_login"] = $this->Employee->get_logged_in_employee_info()->person_id;

		$ubicaciones=array(""=>lang('technical_supports_selec_napli'));
		foreach ($this->Appconfig->get_ubica_equipos()->result() as $ubicacion) {
			$ubicaciones[$ubicacion->ubicacion]=$ubicacion->ubicacion;
		}
		$tservices=array(""=>lang('technical_supports_selec'));
		foreach ($this->Appconfig->get_tipo_servicios()->result() as $tservice) {
			$tservices[$tservice->tservicios]=$tservice->tservicios;
		}
		$tfallas=array(""=>lang('technical_supports_selec'));
		foreach ($this->Appconfig->get_tipo_fallas()->result() as $tfalla) {
			$tfallas[$tfalla->tfallas]=$tfalla->tfallas;
				}
		$data['ubiequipos'] = $ubicaciones;
		$data['tservice'] = $tservices;
		$data['tfallas'] = $tfallas;
		$data["id_employee_login"] = $this->Employee->get_logged_in_employee_info()->person_id;

		$data["support"] = $this->technical_support->get_info_by_id($support_id, false);

		$data["customer"] = $this->Customer->get_info($customer_id);
		$this->load->View('technical_supports/form_nuevo_servicio',$data);
	}
	function get_info_cliente($customer_id,$support_id=-1){
		$data["cliente"]= $this->Customer->get_info($customer_id);
		$this->load->View('technical_supports/cliente/info_cliente',$data);
	}
	function set_comentario(){
		$support_id= $this->input->post('support_id');
		$comentario= $this->input->post('comment');	
		$this->carrito_lib->set_comentario($comentario);

	}
	
		
}


?>