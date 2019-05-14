<?php
class Register_movement extends CI_Model
{

	private $current_location_id = null;

	private $CI = null;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->current_location_id = $this->CI->Employee->get_logged_in_employee_current_location_id();
	}

	function save($cash, $description=" ", $register_id = false, 
	$valid_greater_than_zero_cash = true, $categorias_gastos="",
	$id_employee=false,$entregado_a="",$retorna_id=false,$date=null,$register_log_id=null,$operation=null)
	{ 
        
		if($id_employee==false){
			$id_employee= $this->CI->Employee->get_logged_in_employee_info()->person_id;
		}
		if($register_log_id==null){
			$register_cash = $this->Sale->get_current_register_log($register_id); //Obtengo la caja actual abierta
		}else{
			$register_cash = $this->Sale->get_registers_log_by_id($register_log_id);
		}
		if ($register_cash) { 

			$type_movement        = 1;
			$current_cash_amount  = $register_cash->cash_sales_amount; // Monto efectivo caja actual
			$register_log_id      = $register_cash->register_log_id;
			$new_cash_amount      = $current_cash_amount + $cash; //Sumar o restar a la caja actual el efectivo


			if ($new_cash_amount < 0 ){

				$new_cash_amount = 0;
			}
			if($operation=="move_money"){
				$cash = abs($cash);
				$type_movement = 2;
			}
			else if ($cash < 0 ) { //Es una salida sino una entrada

				$cash = abs($cash);
				$type_movement = 0;
				
			}
          
			if ($cash > 0 && $valid_greater_than_zero_cash || !$valid_greater_than_zero_cash) {
				
				$register_movement = array(
					'register_log_id' => $register_log_id,
					'register_date'   => $date==null ? date('Y-m-d H:i:s'): $date,
					'mount'           => $cash,
					'description'     => $description,
					'type_movement'   => $type_movement,
					'mount_cash'      => $new_cash_amount,
					"categorias_gastos"=>$categorias_gastos,
					"id_employee"=>$id_employee,
					"delivered_to"=>$entregado_a
				);

				if ($this->db->insert('registers_movement', $register_movement)) {
				$id_tem= $this->db->insert_id();
					$data['cash_sales_amount'] = $new_cash_amount;
					$this->db->where('register_log_id', $register_log_id);
					$this->db->update('register_log', $data);
					if($retorna_id==true)
					return $id_tem;
					
				}

	
				return true;
			} 

			//die;
			return false;
		}
	}

	function get_all($register_id)
	{		
		$employee_id = $this->CI->Employee->get_logged_in_employee_info()->person_id;

		$permision=$this->CI->Employee->has_module_action_permission('registers_movement','see_cash_flows_uniqued', $employee_id);
		
		$this->db->select(
						'registers_movement.register_date, 
						 registers_movement.mount, 
						 registers_movement.description, 
						 registers_movement.type_movement, 
						 registers_movement.mount_cash')
				 
				 ->from('registers')
				 ->join('register_log', 'register_log.register_id=registers.register_id')
				 ->join('registers_movement', 'registers_movement.register_log_id=register_log.register_log_id')
				 ->where('registers.location_id', $this->current_location_id)
				 ->where('registers.register_id', $register_id);	

			
		return $this->db->get();
	}
	function get_info($register_movement_id)
	{	
		$this->db->select(
			'registers_movement.*, 
			 people.first_name,
			 people.last_name,  
			 registers.name as name_caja,
			 locations.name as name_tienda');
		$this->db->from('registers_movement');
		$this->db->join('employees', 'employees.person_id=registers_movement.id_employee');
		$this->db->join('people', 'people.person_id=employees.person_id');
		$this->db->join('register_log', 'register_log.register_log_id=registers_movement.register_log_id');
		$this->db->join('registers', 'registers.register_id=register_log.register_id');
		$this->db->join('locations', 'registers.location_id=registers.location_id');
		$this->db->where('register_movement_id ', $register_movement_id);
		$query=$this->db->get();
		if ($query->num_rows()==0)
		{
			//Get empty base parent object, as $item_id is NOT an item
			$obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('registers_movement');

			foreach ($fields as $field)
			{
				$obj->$field='';
			}

			return $obj;	
		}		
		return $query->row();
	}
	
	//function find by date
	//funcion que busca por fecha
	function get_by_date($register_id, $date_start, $date_end, $categoria="", $employee=false, $filter="", $search="",$open_box=0){
		if ($date_start == null){
			$datestring = '%Y-%m-%d';
			$date_start1 = now();
			$date_end1 = strtotime ( '-31 day' , strtotime ( mdate($datestring, $date_start) ) ) ;
			$date_end = mdate($datestring, $date_start1);
			$date_start = mdate($datestring, $date_end1);
		}	
		$employee_id = $this->CI->Employee->get_logged_in_employee_info()->person_id;
		$permision=$this->CI->Employee->has_module_action_permission('registers_movement','see_cash_flows_uniqued', $employee_id);
		
		$this->db->select(
						'registers_movement.*, people.first_name, people.last_name')
				 
				 ->from('registers')
				 ->join('register_log', 'register_log.register_id=registers.register_id')
				 ->join('registers_movement', 'registers_movement.register_log_id=register_log.register_log_id')
				 ->join('employees', 'employees.person_id=registers_movement.id_employee')
				 ->join('people', 'people.person_id=employees.person_id')
				 ->where('registers.location_id', $this->current_location_id)
				 ->where('registers.register_id', $register_id)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(register_date),"%Y-%m-%d") >=', $date_start)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(register_date),"%Y-%m-%d") <=', $date_end);
				 
				if($open_box){
					$this->db->where('register_log.employee_id_close', null);
				 }
				 if($categoria!=""){
					$this->db->where('registers_movement.categorias_gastos', $categoria);
				 }
				 if($permision and $employee!=false and $employee!="undefined" ){
					$this->db->where('registers_movement.id_employee', 	$employee );
				 }else if(!$permision ){
					$this->db->where('registers_movement.id_employee', $employee_id); 
				 }
				 if($filter != "" && $search != ""){
					$this->db->like('registers_movement.'.$filter, $search);
				 }

		return $this->db->get();
				

	}

	function save_operation($register_id, $cash, $description,$categorias_gastos,$entregado_a,$operation )
	{
		$register = $this->Sale->get_current_register_log($register_id);
		$error = "";
		$success = false;
		
		if (!$register) { //No esta la caja abierta

			$error = $this->lang->line("cash_flows_error_adding_updating"); // La caja no esta abierta
		
		} else {

			$amount_cash = ($register->cash_sales_amount + $cash);

			if ($this->config->item('value_max_cash_flow') <= $amount_cash && 
				$this->config->item('limit_cash_flow') == 1) {
				
				$error = lang('cash_flows_limit').to_currency($this->config->item('value_max_cash_flow'));
			
			} elseif ($amount_cash < 0){

				$error = "La caja no tiene suficiente efectivo para procesar esta solicitud";

			}else {
				$id_employee= $this->CI->Employee->get_logged_in_employee_info()->person_id;
				$success = $this->save($cash, $description, $register_id,true,$categorias_gastos,$id_employee,$entregado_a,true,null,null,$operation); //Registrar movimiento
				
			}
		}

		return compact('success', 'error');
	}

	function get_amount_register($start_date, $end_date)
	{
		$this->db->from('phppos_register_log')
				  ->where('shift_start >=', $start_date)
				  ->where('shift_end <=', $end_date);

		$result = $this->db->get();

		if ($result->num_rows() > 0) {

			return $result->row();
		}

		return null;
	}

	function report_register($register_log_id)
	{

		
		$register_log          = $this->Sale->get_register_log($register_log_id);
		$amount_register_open  = $register_log->open_amount;
		$amount_register_close = $register_log->close_amount;
		$start_date            = $register_log->shift_start;
		$end_date              = $register_log->shift_end;
		$id_register              = $register_log->register_id;
		$total_sales           = 0;
		$amount_sales          = 0;
		$difference= $register_log->close_amount-$register_log->cash_sales_amount;
		
		$sales          = $this->Sale->get_sales_totaled_by_id($start_date, $end_date, $id_register);
		
		$register       = $this->Register_movement->get_amount_register($start_date, $end_date, $id_register);
		$info_register  = $this->Register->get_info($id_register);
		$payments       = $this->Sale->get_sales_totaled_payments($start_date, $end_date, $id_register);
		$payments_petty_cash=$this->Sale->get_petty_cash_totaled_payments($start_date, $end_date, $id_register);
		$payments_types = array(lang('sales_cash'), lang('sales_check'), lang('sales_debit'),lang('sales_credit'), lang('sales_giftcard'),lang("sales_store_account"));
		$entrada= $this->entrada_salida($start_date,$end_date,$register_log_id,1);
		$salida= $this->entrada_salida($start_date,$end_date,$register_log_id,0);

		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			array_push($payments_types,$additional_payment_type);
		}

		if ($register != null) {

			$amount_register_open  = $register->open_amount;
			$amount_register_close = $register->close_amount;
		}

		$amount_sales = array_sum($sales);
		$total_sales  = count($sales);
		//$end_date     = strftime("%d de %b de %Y a las %l y %M %p", strtotime($end_date));

		$data         = compact(

						'info_register',
						'start_date',
						'end_date',
						'amount_register_open', 
						'amount_register_close', 
						'total_sales', 
						'amount_sales', 
						'payments_types', 
						'payments',
						'payments_petty_cash',
						"entrada",
						"salida",
						"difference"

					);

		return $data;				
	}	
	function entrada_salida($start_date,$end_date,$register_log,$type_movement){
		$this->db->select_sum('mount','suma');
		$this->db->from('registers_movement');
		//$this->db->join('register_log', 'registers_movement.register_log_id = register_log.register_log_id', 'left');		
		$this->db->where('registers_movement.register_date BETWEEN'.$this->db->escape($start_date).' and '.$this->db->escape($end_date));
		$this->db->where('registers_movement.register_log_id = '.$this->db->escape($register_log));
		$this->db->where('registers_movement.type_movement = '.$this->db->escape($type_movement));

		$data=$this->Appconfig->where_categoria();		
		foreach($data as $categoria){
			$this->db->where('registers_movement.categorias_gastos != '.$this->db->escape($categoria));
		}		
		$query= $this->db->get();
		if($query->num_rows()==1)	{
			$dato= $query->row_array();
           	return $dato["suma"]==NULL ? 0: $dato["suma"] ;
		}
		return 0;

	}
	public function create_movement_items_temp_table($params)
	{
		set_time_limit(0);
		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE register_date BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			' and '.$this->db->dbprefix('registers').'.location_id='.$this->db->escape($location_id);
		}
		if (isset($params['register_id']) && $params['register_id']!="all")
		{
			$where .= ' and '.$this->db->dbprefix('registers').'.register_id='.$this->db->escape($params["register_id"]);
		}
		if(isset($params['categoris']) && $params['categoris']!="all"){
			$where .= ' and '.$this->db->dbprefix('registers_movement').'.categorias_gastos='.$this->db->escape($params["categoris"]);

		}
		// consulta para reporte de traslado o por cada tipo de moviento -ingresos,gastos o traslado
		if(isset($params['type_movement']) && $params['type_movement']!="all"){
			$where .= ' and '.$this->db->dbprefix('registers_movement').'.type_movement='.$this->db->escape($params["type_movement"]);

		}
		if(isset($params['id_empleado']) && $params['id_empleado']!="all"){
			$where .= ' and '.$this->db->dbprefix('registers_movement').'.id_employee='.$this->db->escape($params["id_empleado"]);

		}
		$where .= $this->where_categoria();
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('movement_items_temp')."

		(SELECT ".$this->db->dbprefix('registers_movement').".register_movement_id as register_movement_id,".
		$this->db->dbprefix('registers_movement').".register_log_id as register_log_id,  register_date, 
		".$this->db->dbprefix('registers_movement').".mount, description,detailed_description, type_movement, 
		".$this->db->dbprefix('registers_movement').".mount_cash as mount_cash,
		".$this->db->dbprefix('registers_movement').".delivered_to as entregado_a,
		".$this->db->dbprefix('registers_movement').".categorias_gastos as categorias_gastos, 
		".$this->db->dbprefix('registers_movement').".id_employee as id_employee,
		".$this->db->dbprefix('registers').".name as name_caja,
		".$this->db->dbprefix('locations').".name as name_tienda,
		".$this->db->dbprefix('people').".first_name,".$this->db->dbprefix('people').".last_name
		FROM ".$this->db->dbprefix('registers_movement')."		
		JOIN ".$this->db->dbprefix('register_log')." ON  ".$this->db->dbprefix('register_log').'.register_log_id='.$this->db->dbprefix('registers_movement').'.register_log_id'."
		JOIN ".$this->db->dbprefix('registers')." ON  ".$this->db->dbprefix('registers').'.register_id='.$this->db->dbprefix('register_log').'.register_id'."
		JOIN ".$this->db->dbprefix('locations')." ON  ".$this->db->dbprefix('locations').'.location_id='.$this->db->dbprefix('registers').'.location_id'."
		JOIN ".$this->db->dbprefix('employees')." ON  ".$this->db->dbprefix('employees').'.person_id='.$this->db->dbprefix('registers_movement').'.id_employee'."
		JOIN ".$this->db->dbprefix('people')." ON  ".$this->db->dbprefix('people').'.person_id='.$this->db->dbprefix('employees').'.person_id'."   
		
		$where )");

	
	}

	public function create_movement_all_temp_table($params)
	{
		set_time_limit(0);
		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE register_date BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"'.
			' and '.$this->db->dbprefix('registers').'.location_id='.$this->db->escape($location_id);
		}
		if (isset($params['register_id']) && $params['register_id']!="all")
		{
			$where .= ' and '.$this->db->dbprefix('registers').'.register_id='.$this->db->escape($params["register_id"]);
		}

		if(isset($params['categoris']) && $params['categoris']!="all"){
			$where .= ' and '.$this->db->dbprefix('registers_movement').'.categorias_gastos='.$this->db->escape($params["categoris"]);

		}
		if(isset($params['empleado_id']) && $params['empleado_id']!="all"){
			$where .= ' and '.$this->db->dbprefix('registers_movement').'.id_employee='.$this->db->escape($params["empleado_id"]);

		}
		//$where .= $this->where_categoria();
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('movement_items_temp')."

		(SELECT ".$this->db->dbprefix('registers_movement').".register_movement_id as register_movement_id,".
		$this->db->dbprefix('registers_movement').".register_log_id as register_log_id,  register_date, 
		".$this->db->dbprefix('registers_movement').".mount, description,detailed_description, type_movement, 
		".$this->db->dbprefix('registers_movement').".mount_cash as mount_cash, 
		".$this->db->dbprefix('registers_movement').".mount as mount, "
		.$this->db->dbprefix('registers_movement').".categorias_gastos as categorias_gastos, ".$this->db->dbprefix('registers_movement').".id_employee as id_employee,".$this->db->dbprefix('people').".first_name,".$this->db->dbprefix('people').".last_name
		FROM ".$this->db->dbprefix('registers_movement')."		
		JOIN ".$this->db->dbprefix('register_log')." ON  ".$this->db->dbprefix('register_log').'.register_log_id='.$this->db->dbprefix('registers_movement').'.register_log_id'."
		JOIN ".$this->db->dbprefix('registers')." ON  ".$this->db->dbprefix('registers').'.register_id='.$this->db->dbprefix('register_log').'.register_id'."
		JOIN ".$this->db->dbprefix('employees')." ON  ".$this->db->dbprefix('employees').'.person_id='.$this->db->dbprefix('registers_movement').'.id_employee'." 
		JOIN ".$this->db->dbprefix('people')." ON  ".$this->db->dbprefix('people').'.person_id='.$this->db->dbprefix('employees').'.person_id'." 
		
		$where )");
		
	
	}

	function get_metodos_pago_no_efectivo_ventas($start_date, $end_date,$location_id)
	{
		$sql="SELECT sp.payment_type as payment_type,s.sale_id as id,em.person_id, sp.payment_amount as monto ,s.sale_time as fecha_pago, pe.first_name,pe.last_name  ,		
		(select sum(si.quantity_purchased) from ". $this->db->dbprefix('sales_items')." si 	where si.sale_id =s.sale_id )  as items_quantity,		
		(select sum(sik.quantity_purchased) from ". $this->db->dbprefix('sales_item_kits') ." sik where sik.sale_id =s.sale_id) as items_k_quantity		
		 FROM ". $this->db->dbprefix('sales')." s,". $this->db->dbprefix('people')." pe, ". $this->db->dbprefix('employees')." em, 
		 ". $this->db->dbprefix('sales_payments')." sp where sp.payment_type != ".$this->db->escape(lang('sales_cash'))."
		and s.deleted=0
		and sp.sale_id=s.sale_id
		and s.sold_by_employee_id = em.person_id 
		and em.person_id=pe.person_id
		and s.location_id =".$this->db->escape($location_id)."
		and s.sale_time>=".$this->db->escape($start_date)." and s.sale_time<="
		.$this->db-> escape($end_date)."";
		$query = $this->db->query($sql);
   
       return $query->result_array();
	}
	
	function get_metodos_pago_no_efectivo_credito($start_date, $end_date,$location_id)
	{
		$sql="SELECT pp.payment_type as payment_type, pc.petty_cash_id as id, pp.payment_amount as monto,
				pc.petty_cash_time as fecha_pago, pe.first_name,pe.last_name ,em.person_id 				
				FROM ". $this->db->dbprefix('petty_cash')." pc, ". $this->db->dbprefix('people'). " pe, ".
				$this->db->dbprefix('employees')." em, ". $this->db->dbprefix('petty_cash_payments')." pp 
				where pp.payment_type  != ".$this->db->escape(lang('sales_cash'))."
				and pc.deleted=0
				and pc.petty_cash_id=pp.petty_cash_id
				and pc.sold_by_employee_id = em.person_id 
				and em.person_id=pe.person_id
				and pc.location_id =".$this->db->escape($location_id)." and 
				pc.petty_cash_time>=".$this->db->escape($start_date)." and pc.petty_cash_time<=".$this->db-> escape($end_date)."";
			
		$query = $this->db->query($sql);
   
       return $query->result_array();
	}
	
	function where_categoria(){
		$where= "";
		$dbprefix=$this->db->dbprefix('registers_movement');
		$data=$this->Appconfig->where_categoria();		
		foreach($data as $categoria){
			$where.=" and ".$dbprefix.".categorias_gastos !=". $this->db->escape($categoria);			
		}
		return $where;
	}

}

