<?php
require_once "secure_area.php";
class Changes_house extends Secure_area
{
    public function __construct()
    {
        parent::__construct("changes_house");
        $this->load->library('sale_lib');
        $this->load->model('Change_house'); 
        $this->load->model('movement_balance');
        $this->load->helper('report');        

    }
    public function last_orders($start_date){
        $start_date = rawurldecode($start_date);

        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve',$this->session->userdata('person_id'))) {
            $employee_id = $this->session->userdata('person_id');
        } 
        else {
            $employee_id = false;
        }
        $table_data = $this->Change_house->last_orders(10000, 0,  'sale_time',  'DESC', $employee_id,$start_date);
        $manage_table = get_chanche_home_manage_table_data_rows($table_data, $this,false);
        echo json_encode(array("data"=>$manage_table, "rows"=>$table_data->num_rows(),"date"=>date('Y-m-d H:i:s')));

    }
    public function get_total_divisa()
    {
        $cantidad_peso = $this->input->post('cantidad_peso');
        $opcion_sale = $this->sale_lib->get_opcion_sale(); //compra o venta

        $this->form_validation->set_rules('cantidad_peso', '', 'required|numeric');
        
        if ($this->form_validation->run() != false) {           
            if ($opcion_sale == "venta") {
                $tasa = $this->sale_lib->get_rate_sale() ;
            } else {
                $tasa =   $this->sale_lib->get_rate_buy();
            }           
            $total = $cantidad_peso / $tasa;
            echo json_encode(array("respuesta" => true, "data" => to_currency_no_money($total, 4)));

        } else {
            echo json_encode(array("respuesta" => false, "data" => "Error datos erroneos."));
        }
    } 

    public function get_total_peso()
    {
        $cantidad_peso = $this->input->post('cantidad_peso');
        $opcion_sale = $this->sale_lib->get_opcion_sale(); //compra o venta

        $this->form_validation->set_rules('cantidad_peso', '', 'required|numeric');
        
        if ($this->form_validation->run() != false) {
            $tasa = 1;
            $total = 0;
        
            if ($opcion_sale == "venta") {
                $tasa = $this->sale_lib->get_rate_sale() ;
            } else {
                $tasa =   $this->sale_lib->get_rate_buy();
            }

            $total = $cantidad_peso * $tasa;

            echo json_encode(array("respuesta" => true, "data" => to_currency_no_money($total, 4)));

        } else {
            echo json_encode(array("respuesta" => false, "data" => "Error datos erroneos."));
        }

    }
    function set_rate_all(){
        $rate = $this->input->post('tasa');
        $data= array( );

        if(!$this->Employee->has_module_action_permission('changes_house', 'edit_rate_transition', $this->Employee->get_logged_in_employee_info()->person_id))
		{
            $data= array(
                'success' => false,
                'message' => "No tiene permiso para editar la tasa de transacción");
		}else{
            if( !is_numeric($rate) || $rate<=0){
                $data= array(
                    'success' => false,
                    'message' => "El valor de la tasa no es válido, ingrese un valor mayor que 0");
            }else{
                $rate =abs($rate);

                if($this->sale_lib->get_opcion_sale()=="venta"){
                    $this->sale_lib->set_rate_sale($rate) ;
                }
                else{
                    $this->sale_lib->set_rate_buy($rate) ;
                }

                $this->sale_lib->set_rate_items($rate);
                $data= array(
                    'success' => true,
                    'message' => "");
            }
        }
        echo   json_encode($data);
    }
    public function saldo($operation)
    {
        $this->check_action_permission('edit_balance_employee');

        if ($operation == "add" || $operation == "restar") {
            $data = array("operation" => $operation);
            $empleados = array("" => "Empleados");
            foreach ($this->Employee->get_all()->result() as $empleado) {
                $empleados[$empleado->person_id] = $empleado->first_name . " " . $empleado->last_name . " - " . $empleado->type . " - " . to_currency($empleado->account_balance);
            }
            $data["empleados"] = $empleados;
            $this->load->view("changes_house/form_saldo", $data);
        } else {
            redirect('changes_house');
        }
    }
   
    public function save_rate()
    {
        $this->check_action_permission('edit_rate');
        $tasa_compra = $this->input->post('tasa_compra');
        $tasa_venta = $this->input->post('tasa_venta');
        $override_rate = $this->input->post('override_rate');
        $this->form_validation->set_rules('tasa_venta', '', 'required|numeric');
        $this->form_validation->set_rules('tasa_compra', '', 'required|numeric');

        if ($this->form_validation->run() != false && $tasa_compra != 0 && $tasa_venta != 0) {
            $data = array(
                "override_rate" => (int) $override_rate,
                "tasa_compra" => abs($tasa_compra),
                "tasa_venta" => abs($tasa_venta),
            );

            $resultado = $this->Employee->update_rate($data);

            if ($resultado) {
                echo json_encode(
                    array(
                        'success' => true,
                        'message' => "Tasas actulizadas")
                );
            } 
            else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => "No pude actualizar")
                );
            }

        } 
        else {
            echo json_encode(
                array(
                    'success' => false,
                    'message' => "Datos incorrecots " . ($tasa_compra == 0 || $tasa_venta == 0) ? "la tasa de venta o compra no puede ser 0" : "")
            );
        }

    }
    public function save_balance()
    {
        $this->check_action_permission('edit_balance_employee');
        $operation = $this->input->post('operation');
        if ($operation == "add" || $operation == "restar") {

            $id_person = $this->input->post('empleado');
            $description = $this->input->post('description');
            $cash = abs($this->input->post('cash'));
            $type_movement = 0; // 0 add saldo y 1 restar saldo
            $category="Ingreso";
            if ($operation == "restar") {

                $cash = $cash * (-1);
                $type_movement = 1;
                $category="Retiro";
            }
            $status = $this->movement_balance->save($cash, $description, $id_person, $type_movement,$category);
            if ($status) {
                echo json_encode(
                    array(
                        'success' => true,
                        'message' => "Cantidad registrada")
                );
            } else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => "No pude  registrar la cantidad ")
                );
            }
        }
    }

    public function save($sale_id, $item_id, $line)
    {
        $this->check_action_permission('refuse_approve');
        $cometario = $this->input->post('comentario');
        $estado = $this->input->post('estado');
        $data = array(
            "comentarios" => $cometario,
            "transaction_status" => $estado,
            "fecha_estado" => date('Y-m-d H:i:s'),
        );
        $image_file_id = -1;
        $resultado =false;

        if(!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK)
		{
            $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
            $extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));
            if (in_array($extension, $allowed_extensions))
            {
                $config['image_library'] = 'gd2';
                $config['source_image']	= $_FILES["image_id"]["tmp_name"];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['width']	 = 400;
                $config['height']	= 300;
                $this->load->library('image_lib', $config); 
                $this->image_lib->resize();
                $image_file_id = $this->Appfile->save_transfer($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
            }            
        }
    
        if( $image_file_id > 0)
            $data["file_id"]=$image_file_id;
        
        $resultado = $this->Change_house->actualiza_item($sale_id, $item_id, $line, $data);
    
        echo json_encode(array("success" => $resultado));

    }
    public function view_modal($sale_id, $item_id, $line)
    {
        $employee_id = false;
        $employee_info = $this->Employee->get_logged_in_employee_info();
        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $employee_info->person_id)) {
            $employee_id = $employee_info->person_id;
        }
        $item_info = $this->Change_house->get_info($sale_id, $item_id, $line, $employee_id);
        $data = array();
        $sold_by_employee_info = $this->Employee->get_info($item_info->sold_by_employee_id);
        $data["item_info"] = $item_info;
        $data["sold_by_employee_info"] = $sold_by_employee_info;
        //$sold_by_employee_info = $this->Employee->get_info($item_info->sold_by_employee_id);
        $data["item_info"] = $item_info;
         // muestra informacion del vendedor modal
        //$data["sold_by_employee_info"] = $sold_by_employee_info;
        $data["options"] = array("Pendiente" => "Pendiente","Procesando" => "Procesando", "Aprobada" => "Aprobada", "Rechazada" => "Rechazada","Entregado"=>"Entregado");

        $this->load->view("changes_house/items_modal", $data);

    }
    public function sorting()
    {

        $search = $this->input->post('search') ? $this->input->post('search') : "";
        $userdata = $this->session->userdata('change_house_search_data') ? $userdata = $this->session->userdata('change_house_search_data') : array();
        $estado = isset($userdata["estado"]) ? $userdata["estado"] : false;
        $start_date =  isset($userdata["start_date"]) ? $userdata["start_date"]:date('Y-m-d');
        $end_date = isset($userdata["end_date"]) ? $userdata["end_date"]:date('Y-m-d');
        $employee_info = $this->Employee->get_logged_in_employee_info();
        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $employee_info->person_id)) {
            $employee_id = $employee_info->person_id;
        } else {
            $employee_id = $userdata["employee_id"];
        }
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'sale_time';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC';

        $search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, "estado" => $estado, "search" => $search,"employee_id"=>$employee_id,"start_date"=>$start_date,"end_date"=>$end_date);
        $this->session->set_userdata("change_house_search_data", $search_data);
        if ($search || $estado) {
            $config['total_rows'] = $this->Change_house->search_count_all($search, $estado, $employee_id,$start_date , $end_date);
            $table_data = $this->Change_house->search($search, $estado, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'sale_time', $this->input->post('order_dir') ? $this->input->post('order_dir') : ' DESC',
             $employee_id,$start_date,$end_date);
        } else {
            $config['total_rows'] = $this->Change_house->count_all($employee_id,$start_date , $end_date);
            $table_data = $this->Change_house->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'sale_time', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC', 
            $employee_id,$start_date,$end_date);
        }

        $config['per_page'] = $per_page;
        $config['base_url'] = site_url('changes_house/sorting');
        $config['total_rows'] = $this->Change_house->count_all($employee_id,$start_date , $end_date);
        //$table_data = $this->Change_house->get_all($per_page, $offset, $order_col, $order_dir, $employee_id);
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_chanche_home_manage_table_data_rows($table_data, $this);

        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }
    public function index($offset = 0) 
    {
        $data = array();
        $params = $this->session->userdata('change_house_search_data') ? $this->session->userdata('change_house_search_data') : array('offset' => 0, 'order_col' => 'sale_time', 'order_dir' => 'DESC', 'search' => false, 'estado' => false, "employee_id" => false,
            "start_date"=>date('Y-m-d'),"end_date"=>date('Y-m-d'));
        if ($offset != $params['offset']) {
            redirect('changes_house/index/' . $params['offset']);
        }

        $employee_info = $this->Employee->get_logged_in_employee_info();
        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $employee_info->person_id)) {
            $employee_id = $employee_info->person_id;
        } else {
            $employee_id = $params["employee_id"];
        }
        $data["start_date"]=$params["start_date"]; 
        $data["end_date"]=$params["end_date"];
        $fechas =get_simple_date_ranges(false);
        $fechas["0000-00-00/0000-00-00"]="Rango de fecha";
       
        $data["report_date_range_simple"]=$fechas;
        $data['controller_name'] = strtolower(get_class());
        $data["options"] = array(null => "", "Pendiente" => "Pendiente","Procesando" => "Procesando", "Aprobada" => "Aprobada", "Rechazada" => "Rechazada","Entregado"=>"Entregado");
        $data['estado'] = (isset($params['estado']) && $params['estado'] != false) ? $params['estado'] : "";
        $config['base_url'] = site_url('changes_house/sorting');
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $data['controller_name'] = strtolower(get_class());
        $data['search'] = $params['search'] ? $params['search'] : "";
        $data['per_page'] = $config['per_page'];

        if ($data['search'] || $data['estado']) {

            $config['total_rows'] = $this->Change_house->search_count_all($data['search'], $data['estado'], $employee_id,$params["start_date"],$params["end_date"]);
            $table_data = $this->Change_house->search($data['search'], $data['estado'], $data['per_page'], $params['offset'], $params['order_col'], $params['order_dir'], $employee_id,$params["start_date"],$params["end_date"]);
        } else {
            $config['total_rows'] = $this->Change_house->count_all($employee_id,$params["start_date"],$params["end_date"]);
            $table_data = $this->Change_house->get_all($data['per_page'], $params['offset'], $params['order_col'], $params['order_dir'],$employee_id,$params["start_date"],$params["end_date"]);
        }

        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['order_col'] = $params['order_col'];
        $data['order_dir'] = $params['order_dir'];
        $data['manage_table'] = get_chanche_home_manage_table($table_data, $this);
        $empleados = array("0" => "Empleados");
        foreach ($this->Employee->get_all()->result() as $empleado) {
            $empleados[$empleado->person_id] = $empleado->first_name . " " . $empleado->last_name;
        }
        $data["employee_id"] = $employee_id;
        $data["empleados"] = $empleados;

        $this->load->View('changes_house/manage.php', $data);

    }
    public function suggest()
    {
        //allow parallel searchs to improve performance.
        $employee_id = false;
        $employee_info = $this->Employee->get_logged_in_employee_info();
        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $employee_info->person_id)) {
            $employee_id = $employee_info->person_id;
        }
        session_write_close();
        $suggestions = $this->Change_house->get_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }
    public function search()
    {

        $userdata = $this->session->userdata('change_house_search_data');
        $search = $this->input->post('search');
        $estado = $this->input->post('estado');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
       
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;

        $employee_info = $this->Employee->get_logged_in_employee_info();
        if (!$this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $employee_info->person_id)) {
            $employee_id = $employee_info->person_id;
        } else {
            $employee_id = $this->input->post('empleado_id') ? $this->input->post('empleado_id') : false;
        }

        if (empty($userdata['order_col'])) {
            $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'sale_time';
        } else {
            $order_col = 'sale_time';
        }
        if (isset($_POST['search_flag']) and !empty($_POST['search_flag'])) {
            $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC';
            $order_col = 'sale_time';
        } else {
            $order_dir = $userdata['order_dir'];
        }

        $search_data_ = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'estado' => $estado, "employee_id" => $employee_id,"start_date"=> $start_date,"end_date"=>$end_date);
        $this->session->set_userdata("change_house_search_data", $search_data_);
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Change_house->search($search, $estado, $per_page, $offset, $order_col, $order_dir,
        $employee_id,$start_date,$end_date);
        $config['base_url'] = site_url('changes_house/sorting');

        $config['total_rows'] = $this->Change_house->search_count_all($search, $estado, $employee_id,$start_date , $end_date);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_chanche_home_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }
    public function clear_state()
    {
        $this->session->unset_userdata('change_house_search_data');
        redirect('changes_house');
    }
}
