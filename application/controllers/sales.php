<?php
require_once ("secure_area.php");
class Sales extends Secure_area
{
	function __construct()
	{
		parent::__construct('sales');

		$this->load->library('sale_lib');
		$this->load->model('Denomination_currency');
		$this->load->model('Register_movement');
		$this->load->model('appconfig');
		$this->load->model('Cajas_empleados');
		$this->load->model('movement_balance');
	
	}

    //Provicional: requerimiento de autocomplete en teclado virtual
    public function prueba() {
		
        $this->load->view('sales/prueba');
	}
	function add_items_cart()
	{
		$items = $this->input->post('items');
		foreach ($items as $item) {
			$this->sale_lib->add_item( $item["item_id"],$item["quantity"]);
		}
	}
	function items_sales_search(){
		session_write_close();		
		$suggestions = $this->Item->get_items_sales_search_suggestions($this->input->get('term'),30);
		echo json_encode($suggestions);
	}
	function offline(){
		$this->load->view("partial/cache_control"); 
		$this->load->view("sales/offline");
	}
	
	//--- Listar Ventas Modo Offline
	function list_sales(){
		 $this->load->view("partial/cache_control"); 
		$this->load->view("sales/list_sale");
	}
	//--- Cerrar caja Modo Offline
	function closing_amount_offline(){
		$this->load->view("partial/cache_control");
		$this->load->view("sales/closing_amount_offline");
	}
	//--- cantidad caja apertura
	function opening_amount_offline(){
		$this->load->view("partial/cache_control");
		$this->load->view("sales/opening_amount_offline");
	}
	//--- seleccionar caja apertura
	function choose_register_offline(){
		$this->load->view("partial/cache_control");
		$this->load->view("sales/choose_register_offline");
	}
	function closing_all(){
		$this->load->view("partial/cache_control");
		$this->load->view("sales/closing_all");
	}


	function get_item_cart($line){
		$cart=$this->sale_lib->get_cart();
		if(isset($cart[$line])){
			echo json_encode($cart[$line]);
		}
		else{
			return;
		}

	}
	function search_ticket(){
		$number = $this->input->post('number_ticket');
		$sale_id=0;
		//$number_aux = explode("-",trim ($number));		
		$is_offline =false;// (count($number_aux)>0 and is_numeric($number_aux[0]))?true:false;		
		if($is_offline){
			//buscar venta offline
		}else{
			if(is_numeric($number)){
				$sale_id=(int)$number;
			}else{
				$number_aux = explode(" ",trim ($number));
				if((count($number_aux)>0 and is_numeric($number_aux[1]))){
					$sale_id=(int) $number_aux[1];
				}
			}
		}		
		$data=array();		
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();		
		if($this->Sale-> is_deleted_sale($sale_id)){
			$data["respuesta"]=false;
			$data["mensaje"]="La venta que intenta editar está eliminada.";
		}else{				
			if($this->Sale->exists($sale_id, $location_id)){
				$data["respuesta"]=true;
				$data["sale_id"]=$sale_id;
			}else{
				$data["respuesta"]=false;
				$data["mensaje"]="Venta no encontrada.";				
			}
		}		
		echo json_encode($data);		
	}
	function sale_seriales_modal(){
		$this->load->view("sales/sale_seriales_modal");
	}
	function sale_seriales_offline_modal(){
		echo $this->load->view("sales/sale_seriales_offline_modal",array(),true);
	}
	function sale_return_modal(){
		$this->load->view("sales/sale_return_modal");
	}
	public function save_range(){
		$item_final_ranges= $this->input->post('item_final_range');
		$item_ids= $this->input->post('item_id');
		$register_log=$this->Sale->get_current_register_log();
		$this->Item->save_ranges($register_log->register_log_id,$item_ids,null,$item_final_ranges);
		$this->sale_lib->clear_all();
		$register_log=$this->Sale->get_current_register_log();
		$item_ranges_tem= $this->Item->get_range_by_register_log_id($register_log->register_log_id);
		$item_ranges=array();
		foreach ($item_ranges_tem as $item_range) {
			$item_ranges[$item_range["item_id"]]=$item_range;
		}
		foreach ($item_ids as $key=>$item_id) {
			if(isset($item_ranges[$item_id])){
				$quantity=  abs((double)$item_final_ranges[$item_id]-($item_ranges[$item_id]["start_range"]+$item_ranges[$item_id]["extra_charge"]));
				if($quantity!=0){
				$this->sale_lib->add_item($item_id,$quantity);
				}
			}
		}
		echo "ok";
	}
	function add_cash(){
		
		$item_id= $this->input->post('item_id');
		$cash= $this->input->post('cash');
		if(is_numeric($cash)){
			$register_log=$this->Sale->get_current_register_log();
			$this->Item->add_balance($register_log->register_log_id,$item_id,$cash);
		}

	}
	function add_balance(){
		$register_log=$this->Sale->get_current_register_log();
		$items_ranges= $this->Item->get_items_range($register_log->register_log_id);
		$items=array();
		foreach ($items_ranges as $item) {
			$items["$item->item_id"]=$item->name."  -  ".(double)$item->extra_charge;
		}
		$data=array("items"=>$items);
		$this->load->view("sales/sale_add_balance_modal",$data);
	}
	function modal_range(){
		$_items= $this->Item->get_items_activate_range();
		$register_log=$this->Sale->get_current_register_log();
		$item_ranges_tem= $this->Item->get_range_by_register_log_id($register_log->register_log_id);
		$item_ranges=array();
		foreach ($item_ranges_tem as $item_range) {
			$item_ranges[$item_range["item_id"]]=$item_range;
		}
		$this->load->view("sales/sale_range_modal",array("items"=>$_items,"item_ranges"=>$item_ranges));
	}
	
	function modal_cart(){
		$data=array();
		$data["opcion_sale"]=$this->sale_lib->get_opcion_sale();
		$data["cart"]= $this->sale_lib->get_cart();
		$data["divisa"]=$this->sale_lib->get_divisa();
		$this->load->view("changes_house/cart_modal", $data);
	}
	
	public function change($data=array(),$ajax=false,$resumen_venta=false)
    {		
        $items_info = $this->Item->get_all_service();
		$items = array(null => "Seleccione banco");
		$employee_logueado= $data["employee_logueado"];
				
		$balance=$employee_logueado->account_balance;
		$data["type_employee"]=$employee_logueado->type;
			
		$data["balance"]=$balance;
	
        foreach ($items_info->result() as $item) {
            $items[$item->item_id] = $item->name;
		}		
		$data["items"] = $items;
		$data['opcion_sale']=$this->sale_lib->get_opcion_sale();
		$data["tasa"]= $data['opcion_sale']=="venta"? $this->sale_lib->get_rate_sale() : $this->sale_lib->get_rate_buy();
		$data["total_divisa"]=$this->config->item('activar_casa_cambio')==1?$this->sale_lib->get_total_divisa():0;
		$data["divisa"]=$this->sale_lib->get_divisa();
		$data['valido_dato_casa_cambio']=$this->sale_lib->es_valido_dato_casa_cambio();
		$data["rate_price"]=$this->sale_lib->get_rate_price();
		$data["total_transaccion"]=$this->sale_lib->get_total_price_transaction();
		$data["utilidad"]=$this->config->item('activar_casa_cambio')==1? $this->sale_lib->get_utilidad():0;
		unset($data['payment_options'][lang('sales_giftcard')],$data['payment_options'][lang('sales_store_account')]);
		
		
		if($ajax && !$resumen_venta){
			$this->load->view("changes_house/form", $data);
		}elseif($resumen_venta){
			$this->load->view("changes_house/resumen_venta", $data);
		}
		else{
			$this->load->view("changes_house/inicial", $data);
		}
        
    }
	function index()
	{
		if($this->config->item('automatically_show_comments_on_receipt'))
		{
			$this->sale_lib->set_comment_on_receipt(1);
		}

		$location_id=$this->Employee->get_logged_in_employee_current_location_id();

		$register_count = $this->Register->count_all($location_id);

		if ($register_count > 0)
		{
			if ($register_count == 1)
			{
				$registers = $this->Register->get_all($location_id);
				$register = $registers->row_array();

				if (isset($register['register_id']))
				{
					$this->Employee->set_employee_current_register_id($register['register_id']);
				}
			}
 
			if (!$this->Employee->get_logged_in_employee_current_register_id())
			{
				$this->load->view('sales/choose_register');
				return;
			}else if(!$this->Employee->tiene_caja($this->Employee->get_logged_in_employee_current_register_id(), $this->session->userdata('person_id'))){
				$this->load->view('sales/choose_register');
				return;
				
			}
		}

		if ($this->config->item('track_cash'))
		{
			$is_register_log_open=$this->Sale->is_register_log_open();
			if ($this->input->post('opening_amount') != '' and !$is_register_log_open)
			{
				$now = date('Y-m-d H:i:s');				
				$cash_register = new stdClass();
				$cash_register->register_id = $this->Employee->get_logged_in_employee_current_register_id();
				$cash_register->employee_id_open = $this->session->userdata('person_id');
				$cash_register->shift_start = $now;
				$cash_register->open_amount = $this->input->post('opening_amount');
				$cash_register->close_amount = 0;
				$cash_register->cash_sales_amount = 0;
				$register_log_id= $this->Sale->insert_register($cash_register);
				$this->Register_movement->save($cash_register->open_amount, "Apertura de caja", false, false,"Apertura de caja");								
				$item_ids= $this->input->post('item_id');
				if($item_ids){
					$start_range=  $this->input->post('item_rango');
					$this->Item->save_ranges($register_log_id,$item_ids,$start_range,null);
				}
				echo json_encode( array('success'=>true) );
			} 
			else if ($is_register_log_open)
			{				
				$this->_reload(array(), false);			
			}
			else
			{
				$currency = $this->Denomination_currency->get_all();
				$_items= $this->Item->get_items_activate_range();
				$this->load->view('sales/opening_amount',array('currency'=>$currency,"items"=>$_items ));
			}
		} else {
			/*if($this->config->item('activar_casa_cambio')==true){
				$this->change();
				//redirect(site_url('sales/change'));

			}else{*/
				$this->_reload(array(), false);
			//}
		
		}
	}

	function choose_register($register_id)
	{
	
		if($this->Register->count_all($this->Employee->get_logged_in_employee_current_location_id())> 1 &&
		 !$this->Employee->tiene_caja($register_id, $this->session->userdata('person_id'))){
			echo "No tiene permisos para abrir esta caja";
			exit();
		}
		if ($this->Register->exists($register_id))
		{
			$this->Employee->set_employee_current_register_id($register_id);
		}

		redirect(site_url('sales'));
		return;
	}

	function clear_register()
	{
		//Clear out logged in register when we switch locations
		$this->Employee->set_employee_current_register_id(false);

		redirect(site_url('sales'));
		return;
	}
	function clear_register_offline()
	{
		//Clear out logged in register when we switch locations offline
		$this->Employee->set_employee_current_register_id(false);

		redirect(site_url('sales/choose_register_offline'));
		return;
	}

	function closeregister()
	{

		if (!$this->Sale->is_register_log_open()) {

			redirect(site_url('home'));
			return;
		}

		$cash_register = $this->Sale->get_current_register_log();
		$continueUrl = $this->input->get('continue');

		if ($this->input->post('closing_amount') != '') {

			$now = date('Y-m-d H:i:s');
			$cash_register->register_id = $this->Employee->get_logged_in_employee_current_register_id();
			$cash_register->employee_id_close = $this->session->userdata('person_id');
			$cash_register->shift_end = $now; 
			$cash_register->closed_manual = 1; 
			$cash_register->close_amount = $this->input->post('closing_amount');
			$this->Register_movement->save($cash_register->close_amount * (-1), "Cierre de caja",false, false,"Cierre de caja");
			$this->Sale->update_register_log($cash_register);

			if ($this->config->item('show_report_register_close') == 1) {

				$data = $this->Register_movement->report_register($cash_register->register_log_id);
				$data["credito_tienda"]=$this->appconfig->get("customers_store_accounts");
				$data["items_range"]=$this->Item->get_items_range($cash_register->register_log_id);
				
				$this->load->view("registers_movement/report_register",$data);

			} else {

				redirect(site_url('home'));
			}

		} else {

			$currency = $this->Denomination_currency->get_all();

			$this->load->view('sales/closing_amount', array(

				'currency' => $currency,
				'continue' => $continueUrl ? "?continue=$continueUrl" : '',
				'closeout' => $cash_register->cash_sales_amount
			));
		}

	}

	function item_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->get('term'),100));
		echo json_encode($suggestions);
	}

	function customer_search() 
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_customer()
	{
		$data = array();
		$customer_id = $this->input->post("customer");


		if ($this->Customer->account_number_exists($customer_id))
		{
			$customer_id = $this->Customer->customer_id_from_account_number($customer_id);

		}

		if ($this->Customer->exists($customer_id))
		{
			$customer_info=$this->Customer->get_info($customer_id);


			if ($customer_info->tier_id)
			{
				$this->sale_lib->set_selected_tier_id($customer_info->tier_id);
			}

			$this->sale_lib->set_customer($customer_id);
			if($this->config->item('automatically_email_receipt'))
			{
				$this->sale_lib->set_email_receipt(1);
			}
		}
		else
		{
			$data['error']=lang('sales_unable_to_add_customer');
		}
		$this->_reload($data);
	}

	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->sale_lib->set_mode($mode);

		if ($mode == 'store_account_payment')
		{
			$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
			$this->sale_lib->empty_cart();
			
			$this->sale_lib->add_item($store_account_payment_item_id,1);
		}

		$this->_reload();
	}
	

	function set_comment()
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}

	function set_ntable(){
		$this->sale_lib->set_ntable($this->input->post('comment'));
	}

	function set_change_sale_date()
	{
 	  $this->sale_lib->set_change_sale_date($this->input->post('change_sale_date'));
	}

	function set_change_sale_date_enable()
	{
 	  $this->sale_lib->set_change_sale_date_enable($this->input->post('change_sale_date_enable'));
	  if (!$this->sale_lib->get_change_sale_date())
	  {
	 	  $this->sale_lib->set_change_sale_date(date(get_date_format()));
	  }
	}

	function set_comment_on_receipt()
	{
 	  $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
	}
	function set_generate_txt(){
		$this->sale_lib->set_generate_txt($this->input->post('generate_txt'));
	}
	function set_show_receipt()
	{
 	  $this->sale_lib->set_show_receipt($this->input->post('show_receipt'));
	}
	function set_without_policy()
	{
 	  $this->sale_lib->set_without_policy($this->input->post('without_policy'));
	}

	function set_comment_ticket()
	{
 	  $this->sale_lib->set_comment_ticket($this->input->post('show_comment_ticket'));
	}

	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	function set_save_credit_card_info()
	{
 	  $this->sale_lib->set_save_credit_card_info($this->input->post('save_credit_card_info'));
	}

	function set_use_saved_cc_info()
	{
 	  $this->sale_lib->set_use_saved_cc_info($this->input->post('use_saved_cc_info'));
	}

	function set_tier_id()
	{
 	  $this->sale_lib->set_selected_tier_id($this->input->post('tier_id'));
	}
	
	function set_opcion_sale()
	{
 	  $this->sale_lib->set_opcion_sale($this->input->post('opcion_sale'));
	}

	function set_sold_by_employee_id()
	{
 	  $this->sale_lib->set_sold_by_employee_id($this->input->post('sold_by_employee_id') ? $this->input->post('sold_by_employee_id') : NULL);
	}


	//Alain Multiple Payments
	function add_payment()
	{
		$data=array();
		
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == lang('sales_giftcard') )
				$data['error']=lang('sales_must_enter_numeric_giftcard');
			else
				$data['error']=lang('sales_must_enter_numeric');

				$this->_reload($data,true,true);
				$this->update_viewer(2);
				
 			return;
		}


		if (($this->input->post('payment_type') == lang('sales_store_account') && $this->sale_lib->get_customer() == -1) ||
			($this->sale_lib->get_mode() == 'store_account_payment' && $this->sale_lib->get_customer() == -1)
			)
		{
				$data['error']=lang('sales_customer_required_store_account');
				$this->_reload($data,true,true);
				$this->update_viewer(2);
				return;
		}

		if ($this->config->item('select_sales_person_during_sale') && !$this->sale_lib->get_sold_by_employee_id())
		{
			$data['error']=lang('sales_must_select_sales_person');
			$this->_reload($data,true,true);
			$this->update_viewer(2);
			return;
		}


		$payment_type=$this->input->post('payment_type');


		if ( $payment_type == lang('sales_giftcard') )
		{
			if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($this->input->post('amount_tendered'))))
			{
				$data['error']=lang('sales_giftcard_does_not_exist');
				$this->_reload($data,true,true);
				$this->update_viewer(2);
				return;
			}

			$payment_type=$this->input->post('payment_type').':'.$this->input->post('amount_tendered');
			$current_payments_with_giftcard = $this->sale_lib->get_payment_amount($payment_type);
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value <= 0 && $this->sale_lib->get_total() > 0)
			{
				$data['error']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->_reload($data,true,true);
				$this->update_viewer(2);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			$payment_amount=min( $this->sale_lib->get_amount_due(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}
		else
		{
			$payment_amount=$this->input->post('amount_tendered');
		}

		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount))
		{
			$data['error']=lang('sales_unable_to_add_payment');
		}
		
		$this->_reload($data,true,true);
		$this->update_viewer(2);
		

	}
	function balance_modal()
	{
		$this->load->model('Categories');
		$this->load->model('Viewer');
		$path_img =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
		$this->Viewer->update_viewer($this->Employee->get_logged_in_employee_info()->person_id,
		array(				
				"is_cart" => 1,
				"updated" => date('Y-m-d H:i:s'),				
				"is_scale" => 1,
				"data_scale" => json_encode([])
			)
		);
		$categories = json_encode($this->Categories->get_all());
		$this->load->view("sales/balance_modal",array("categories"=>$categories,"path_img"=>$path_img));
	}
	
	//Alain Multiple Payments
	function delete_payment($payment_id)
	{
		$this->sale_lib->delete_payment($payment_id);		
		$this->_reload(array(),true,true);
		$payment = $this->sale_lib->get_payments();
		if(is_array($payment) and count($payment) > 0 )		
			$this->update_viewer(2);
		else
			$this->update_viewer(1);
	}
	function add_transaction(){
		$item_id = $this->input->post('item');
        $numero_cuenta = $this->input->post('numero_cuenta');
        $tipo_cuenta = $this->input->post('tipo_cuenta');
        $documento = $this->input->post('docuemento');
        $tipo_documento = $this->input->post('tipo_documento');
        $titular_cuenta = $this->input->post('titular_cuenta');
		$cantidad_peso = $this->input->post('cantidad_peso');
		$celular = $this->input->post('celular')?$this->input->post('celular'):null;
		$observaciones = $this->input->post('observaciones')? $this->input->post('observaciones'):null;
		$line = $this->input->post('line') ;
		
		$this->load->model("Change_house");	
		
		$data = array();
		$diferencia=0;
		$total_peso_viejo=0;
		$cantidad_peso_suma=0;
		
		$item_cart= array();
		$line= ($line ==null || $line==0 || !is_numeric($line)) ? FALSE : (int) $line;
		if($line==FALSE){
			$this->form_validation->set_rules('item', 'Item', 'integer|required');
		}else{
			$item_cart=$this->sale_lib->get_cart()[$line];
			$item_id=$item_cart["item_id"];
			$total_peso_viejo= $item_cart["quantity"]*$item_cart["price"];
		}
        $this->form_validation->set_rules('numero_cuenta', '', 'min_length[20]|max_length[20]');
       	$this->form_validation->set_rules('tipo_documento', '', 'required');
        $this->form_validation->set_rules('docuemento', '', 'required');
        $this->form_validation->set_rules('tipo_cuenta', '', 'required');
        $this->form_validation->set_rules('titular_cuenta', '', 'required');
		$this->form_validation->set_rules('cantidad_peso', '', 'required|numeric');
		if ($this->form_validation->run() != false) {
						
			
			$sold_by_employee_id=$this->sale_lib->get_sold_by_employee_id();			

			$employee=$sold_by_employee_id ? $this->Employee->get_info($sold_by_employee_id) :$this->Employee->get_logged_in_employee_info();
			$balance=$employee->account_balance;
			$price_item=$this->sale_lib->get_price_for_item($item_id, 0);
			if($line!=FALSE){
				$diferencia=$total_peso_viejo-abs($cantidad_peso);

			}else{
				$cantidad_peso_suma=$cantidad_peso;
			}	
			

			if($employee->type=="credito" && $balance < (abs($cantidad_peso_suma)+$this->sale_lib->get_total()-$diferencia)){
				$data['error']="¡Saldo insuficiente!";				
			}
			else if($line!=FALSE){
				$this->sale_lib->edit_item($line,FALSE,FALSE,($cantidad_peso/$price_item),FALSE,FALSE,FALSE,FALSE,
				$numero_cuenta,$documento,$titular_cuenta,$tipo_documento,$tipo_cuenta,$observaciones,$celular);
				$data['success']="Producto actualizado";
				$data['clear']=true;
			}
			else{
				//$rate= $this->Change_house->get_rate_by_id($employee->id_rate);
				$tasa= $this->sale_lib->get_opcion_sale()=="venta" ? $this->sale_lib->get_rate_sale() : $this->sale_lib->get_rate_buy();
				if($this->Item->get_info($item_id)->deleted  || $this->Item->get_info($this->Item->get_item_id($item_id))->deleted || 
				!$this->sale_lib->add_item( $item_id, ($cantidad_peso/$price_item),0,null,null,null, FALSE, FALSE,null,null,FALSE,	$numero_cuenta,$documento,$titular_cuenta,
				$tasa,$tipo_documento,0,"Pendiente",null,null,$tipo_cuenta,$observaciones,$celular))
				{
					$data['error']=lang('sales_unable_to_add_item');
				} else{
					$data["success"]=lang("items_successful_adding");
					$data['clear']=true;
				}
			}
		}else{
			$data['error']="Datos incorrectos";
		}		
		$this->_reload($data,true,true);
	}

	function add_by_serial(){
		$data=array();
		$item_serial = $this->input->post("item_serial");
		$item_id = $this->input->post("item_id") ? $this->input->post("item_id"): false;
		$items =$item_id == FALSE ?  $this->Item->get_items_by_serial($item_serial) : array();
		if(!$item_id and sizeof($items) > 1){
			$data_2=array(
				"resultado"=>true,
				"data"=>$items
			);
			echo json_encode($data_2);
			return;
		}
		else{
			$item_id = $item_id == FALSE ? $this->Additional_item_seriales->get_item_id($item_serial): $item_id;
			if($item_id == false || $this->Item->get_info($item_id)->deleted  ||  
			!$this->sale_lib->add_item($item_id,1,0,null,null,$item_serial,  FALSE, FALSE,null,null,false,
			null,null,null,null,null,0,null,null,null,null))		
			{
				$data['error']=lang('sales_unable_to_add_item');
			} 

			if($item_id!=false and  !$this->config->item('sales_stock_inventory') and  $this->sale_lib->out_of_stock($item_id,false ) )
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
			else if($item_id!=false and  $this->config->item('sales_stock_inventory') and $this->sale_lib->out_of_stock($item_id,false ))
			{
				$data['error'] = lang('sales_quantity_stock_less_than_zero');
			}
			 $this->_reload($data);
			
			return;
		}
	}

	
	function add()
	{
		$data=array();
		$mode = $this->sale_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity =1;/* $mode=="sale" ? 1:1;*/ 
		$no_valida_por_id = $this->input->post("no_valida_por_id");
		$item_tem =null;

		if($mode=='return' and $this->sale_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) )
		{
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			if($this->Item_kit->get_info($item_id_or_number_or_item_kit_or_receipt)->deleted || $this->Item_kit->get_info($this->Item_kit->get_item_kit_id($item_id_or_number_or_item_kit_or_receipt))->deleted)
			{
				$data['error']=lang('sales_unable_to_add_item');
			}
			else
			{
				$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);

				//As surely a Kit item , do out of stock check
				$item_kit_id = $this->sale_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);

				if($this->sale_lib->out_of_stock_kit($item_kit_id))
				{
					$data['warning'] = lang('sales_quantity_less_than_zero');
				}
			}
		}else{
			$item_tem= $this->Item->get_info($item_id_or_number_or_item_kit_or_receipt);
			if(!$item_tem->description=="" && $this->Giftcard->get_giftcard_id($item_tem->description,true))
			{
				$data['error']=lang('sales_unable_to_add_item');
			}
			elseif(( $item_tem->deleted && $no_valida_por_id==false) || $this->Item->get_info($this->Item->get_item_id($item_id_or_number_or_item_kit_or_receipt))->deleted || 
			!$this->sale_lib->add_item( $item_id_or_number_or_item_kit_or_receipt,$quantity,0,null,null,null,false,false,null,null,$no_valida_por_id))
			{
				$data['error']=lang('sales_unable_to_add_item');
			} 
			$is_sales_stock_inventory=$this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt,$no_valida_por_id );
			if($is_sales_stock_inventory and  !$this->config->item('sales_stock_inventory'))
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
			elseif($is_sales_stock_inventory and $this->config->item('sales_stock_inventory'))
			{
				$data['error'] = lang('sales_quantity_stock_less_than_zero');
			}

			if ($this->_is_tax_inclusive() && count($this->sale_lib->get_deleted_taxes()) > 0)
			{
				$data['warning'] = lang('sales_cannot_delete_taxes_if_using_tax_inclusive_items');
			}
		}

		$this->_reload($data);
	}

	function _is_tax_inclusive()
	{
		$is_tax_inclusive = FALSE;
		foreach($this->sale_lib->get_cart() as $item)
		{
			if (isset($item['item_id']))
			{
				$cur_item_info = $this->Item->get_info($item['item_id']);
				if ($cur_item_info->tax_included)
				{
					$is_tax_inclusive = TRUE;
					break;
				}
			}
			else //item kit
			{
				$cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id']);

				if ($cur_item_kit_info->tax_included)
				{
					$is_tax_inclusive = TRUE;
					break;
				}

			}
		}

		return $is_tax_inclusive;
	}

	function edit_item($line,$item_id=false)
	{
		$data= array();


		$this->form_validation->set_rules('price', 'lang:items_price', 'numeric');
		//$this->form_validation->set_rules('id_credito_2','', 'numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'integer');
		//$this->form_validation->set_rules('numero_cuenta', '', 'min_length[20]|max_length[20]');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'greater_than[0]');

		$this->form_validation->set_rules('discount', 'lang:reports_discount', 'integer');

		$description = $this->input->post("description");
		$serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$custom1_subcategory = $this->input->post("custom1_subcategory");
		$custom2_subcategory = $this->input->post("custom2_subcategory");
		$numero_cuenta=$this->input->post("numero_cuenta");
		$numero_documento=$this->input->post("numero_documento");
		$titular_cuenta=$this->input->post("titular_cuenta");
		$tipo_documento=$this->input->post("tipo_documento");
		$quantity_unit = $this->input->post("quantity_unit");
		$price_presentation = $this->input->post("price_presentation");
		
		if($quantity_unit === "" || $price_presentation === "" )
			$data['error']=lang('sales_error_editing_item');
		
		$discount = $this->input->post("discount");
                $promo_quantity = isset($item_id) && !empty($item_id) ? $this->Sale->get_promo_quantity($item_id) : 0;
                $is_item_promo = isset($item_id) && !empty($item_id) ? $this->Sale->is_item_promo($item_id) : 0;

                //check if theres enough promo items for a sale.
                if( ( $promo_quantity  <  $this->input->post("quantity") ) && $is_item_promo ){
                    $data['error']=lang('sales_promo_quantity_less_than_zero');
                }

		if ($discount !== FALSE && $this->input->post("discount") == '')
		{
			$discount = 0;
		}
		
		if($this->config->item('activar_casa_cambio')==1){
			if($numero_cuenta!==false && strlen($numero_cuenta)!=20){
				$data['error']="El número de cuenta debe tener 20 digitos";
			}
		}
		
		if ($this->form_validation->run() != FALSE)
		{
			
			$this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price,$custom1_subcategory,$custom2_subcategory,
			$numero_cuenta,$numero_documento,$titular_cuenta,$tipo_documento,FALSE,FALSE,FALSE,$quantity_unit,$price_presentation);
			
			
		}
		else
		{			
			$data['error']=lang('sales_error_editing_item');
		}
		if($this->sale_lib->is_kit_or_item($line) == 'item')
		{
			$is_out_of_stock= $this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line));
			if($is_out_of_stock && $this->config->item('sales_stock_inventory'))
			{
				$this->delete_item_stock($line);
				$data['error'] = lang('sales_quantity_stock_less_than_zero');
			}
			elseif($is_out_of_stock)
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
		}
		elseif($this->sale_lib->is_kit_or_item($line) == 'kit')
		{
			$is_out_of_stock_kit= $this->sale_lib->out_of_stock_kit($this->sale_lib->get_kit_id($line));
			if($is_out_of_stock_kit && $this->config->item('sales_stock_inventory'))
		    {
			    $this->delete_item_stock($line);
			    $data['error'] = lang('sales_quantity_stock_less_than_zero');
		    }
		    elseif($is_out_of_stock_kit)
		    {
			    $data['warning'] = lang('sales_quantity_less_than_zero');
		    }
		}
		// si los item existen, se eliminan los dobles, dejando uno solo con la suma de todas la cantidades 
		if($this->config->item('subcategory_of_items')){
			$items=array();
			foreach($this->sale_lib->get_cart() as $item){
				$line=$this->existe($items,$item);
				if($line==-1){
					$items[$item["line"]]=$item;
				}else{
					$items[$line]['quantity']+=$item['quantity'];
					$data['warning'] = "Producto(s) funcionados verifique el precio  o descuento.";
				}
			}
			
			$this->sale_lib->set_cart($items);
		//	se elemina los item de  con stock bajo en las subcategory
			$items=array();
			foreach($this->sale_lib->get_cart() as $item){
				if($this->sale_lib->is_kit_or_item($item["line"]) == 'item')
				{
					if($this->sale_lib-> out_of_stock_subcategory($item["item_id"],$item["custom1_subcategory"],$item["custom2_subcategory"], $item["quantity"]) &&
					 $this->config->item('sales_stock_inventory') && $item["custom1_subcategory"] !="" && $item["custom1_subcategory"] !=null
					  && $item["custom2_subcategory"] !="" && $item["custom2_subcategory"] !=null){					
						$data['error'] = lang('sales_quantity_stock_less_than_zero')."(Subcategoría)";
					}else{
						$items[$item["line"]]=$item;					}
					if(!$this->config->item('sales_stock_inventory') && $this->sale_lib->out_of_stock_subcategory($item["item_id"],$item["custom1_subcategory"],$item["custom2_subcategory"], $item["quantity"]) && 
					 $item["custom1_subcategory"] !="" && $item["custom1_subcategory"] !=null
					&& $item["custom2_subcategory"] !="" && $item["custom2_subcategory"] !=null)
					{
						$data['warning'] = lang('sales_quantity_less_than_zero')."(Subcategoría)";
					}
				}else{
					$items[$item["line"]]=$item;
				}
			}
			$this->sale_lib->set_cart($items);


		}
		
		
		$this->_reload($data);
	}
	function existe($items,$item_tem){
		if(isset($item_tem['item_id'])){
			foreach($items as $item){			
				if(isset($item['item_id']) and $item['is_serialized'] == false){
					if($item['item_id']==$item_tem['item_id'] &&  $item['custom1_subcategory']== $item_tem['custom1_subcategory'] &&
					$item['custom2_subcategory']==  $item_tem['custom2_subcategory'] ){				
						return $item['line'];
					}
				}
			}
		}
		return -1;
	}
	//Only if the quantity is more inventory
	function delete_item_stock($item_number)
	{
		$this->sale_lib->delete_item($item_number);
	}

	function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);
		$this->_reload(array(),true,true);
	}

	function delete_customer()
	{
		$this->sale_lib->delete_customer();
   	  	$this->sale_lib->set_selected_tier_id(0);
		$this->_reload();
	}

	function start_cc_processing()
	{
		require_once(APPPATH.'libraries/MercuryProcessor.php');
		$credit_card_processor = new MercuryProcessor($this);
		$credit_card_processor->start_cc_processing();

	}

	function finish_cc_processing()
	{
		require_once(APPPATH.'libraries/MercuryProcessor.php');
		$credit_card_processor = new MercuryProcessor($this);
		$credit_card_processor->finish_cc_processing();
	}

	function cancel_cc_processing()
	{
		require_once(APPPATH.'libraries/MercuryProcessor.php');
		$credit_card_processor = new MercuryProcessor($this);
		$credit_card_processor->cancel_cc_processing();
	}

	function complete()
	{ 
        
		$data['is_sale'] = TRUE;
		$data['cart'] = $this->sale_lib->get_cart();
        $suspended_change_sale_id = $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id();
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
	
		$serie_number =$this->sale_lib->get_serie_number(); 
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
            if($this->sale_lib->get_comment_ticket() == 1)
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

        $data['sale_type'] = ($this->sale_lib->get_comment_ticket() == 1) ? lang('sales_ticket_on_receipt') : lang('sales_invoice');

        //Si existe descuento, disminuye la cantidad de productos en descuento (phppos_items -> quantity)
        foreach($data['cart'] as $cart){
            if( isset( $cart["item_id"] ) ){
                if($this->Sale->is_item_promo($cart["item_id"])){
                    $this->Sale->drecrease_promo_quantity($cart["quantity"],$cart["item_id"]);
                }
            }
		}
		//fin

		if (empty($data['cart']))
		{
			redirect('sales');
		}

		if (!$this->_payments_cover_total())
		{
			$this->_reload(array('error' => lang('sales_cannot_complete_sale_as_payments_do_not_cover_total')), false);
			return;
		}

		if ($this->config->item('track_cash') == 1) {

			$register_log_id = $this->Sale->get_current_register_log();

			if (!$register_log_id) {

				$this->_reload(array('error' => "¡La caja donde se quiere efectuar la operación esta cerrada!"), false);

				return;

			} elseif ($suspended_change_sale_id ) {

				$sale_data = $this->Sale->get_info($suspended_change_sale_id)->row();

				if ($sale_data->location_id != $this->Employee->get_logged_in_employee_current_location_id()) {

					$this->_reload(array('error' => "¡Esta no es la tienda donde se realizo la venta!"), false);

					return;

				} else {

					$cash        = $this->Sale->get_payment_cash($this->sale_lib->get_payments());
					$amount_diff = $suspended_change_sale_id ? $this->Sale->get_cash_available($suspended_change_sale_id, $cash) : 0;

					if ($amount_diff < 0)
					{
						$this->_reload(array('error' => "¡La caja no tiene suficiente efectivo para realizar la operación!"), false);

						return;
					}
				}
			}
		}

		//sales_store_account
		$tier_id = $this->sale_lib->get_selected_tier_id();
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name; 
		$data["serie_number"]=$serie_number;
		$data['register_name'] = $this->Register->get_register_name($this->Employee->get_logged_in_employee_current_register_id());
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['discount']=$this->sale_lib->get_discount();
		//$data['taxes']=$this->sale_lib->get_taxes();
		$data['detailed_taxes']=$this->sale_lib->get_detailed_taxes();
		$data['detailed_taxes_total']=$this->sale_lib->get_detailed_taxes_total();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$sold_by_employee_id=$this->sale_lib->get_sold_by_employee_id();
		$data['comment'] = $this->sale_lib->get_comment();
		$data['show_comment_on_receipt'] = $this->config->item('activar_casa_cambio')==1 ? true :$this->sale_lib->get_comment_on_receipt();
		$data['show_comment_ticket'] = $this->sale_lib->get_comment_ticket();

		$emp_info=$this->Employee->get_info($employee_id);
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);
		$data['payments'] = $this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['amount_change']=$this->sale_lib->get_amount_due() * -1;
		$data['balance'] = $this->sale_lib->get_payment_amount(lang('sales_store_account'));
		$data['employee'] = $emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $employee_id ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		$data['ref_no'] = $this->session->userdata('ref_no') ? $this->session->userdata('ref_no') : '';
		$data['auth_code'] = $this->session->userdata('auth_code') ? $this->session->userdata('auth_code') : '';
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
		$masked_account = $this->session->userdata('masked_account') ? $this->session->userdata('masked_account') : '';
		$card_issuer = $this->session->userdata('card_issuer') ? $this->session->userdata('card_issuer') : '';
		$data['mode']= $this->sale_lib->get_mode();
		$activar_casa_cambio= $this->config->item('activar_casa_cambio');
		$data['opcion_sale']= $activar_casa_cambio==1? $this->sale_lib->get_opcion_sale():null;
		$data['divisa']=$activar_casa_cambio==1? $this->sale_lib->get_divisa():null;
		$data["total_divisa"]=$activar_casa_cambio==1?$this->sale_lib->get_total_divisa():null;
		$data["transaction_rate"]= $activar_casa_cambio==1? $this->sale_lib->get_rate_price():null;
		$data["show_number_item"]=$this->config->item('show_number_item');
		$data["transaction_cost"]= $this->config->item('activar_casa_cambio')==1?$this->sale_lib->get_total_price_transaction():0;
		$data["show_return_policy_credit"]= ($this->config->item('show_return_policy_credit')==1 and $data['balance']!=0);
		$data["another_currency"]= $this->sale_lib->get_pagar_otra_moneda();
		$data["currency"]=$data["another_currency"]==0?null:$this->sale_lib->get_currency();
		$data["total_other_currency"]=$data["another_currency"]==0?null:((double)$data['total']/(double) $this->sale_lib->get_equivalencia_divisa());
		$overwrite_tax= $this->sale_lib->get_overwrite_tax();
		$new_tax= $this->sale_lib->get_new_tax();
		$data["overwrite_tax"]= $overwrite_tax; 
		$data["new_tax"]= $new_tax;
		$data["value_other_currency"]=$this->sale_lib->get_equivalencia_divisa();
		$data["generate_txt"] = $this->sale_lib->get_generate_txt();
		if($this->config->item('system_point') && $this->sale_lib->get_mode() == 'sale')
		{
			$total=$data['total'];
			$point_pucharse = $this->sale_lib->get_point($this->config->item('value_point'),$total);
			$detail = 'Id venta #';
			$this->load->model('points');
			$this->points->save_point($point_pucharse,$customer_id,$detail);
		    $data['points']=$this->Customer->get_info_points($customer_id);
		}
		if ($masked_account)
		{
			$cc_payment_id = current($this->sale_lib->get_payment_ids(lang('sales_credit')));
			$cc_payment = $data['payments'][$cc_payment_id];
			$this->sale_lib->edit_payment($cc_payment_id, $cc_payment['payment_type'], $cc_payment['payment_amount'],$cc_payment['payment_date'], $masked_account, $card_issuer);

			//Make sure our payments has the latest change to masked_account
			$data['payments'] = $this->sale_lib->get_payments();
		}


		$data['change_sale_date'] =$this->sale_lib->get_change_sale_date_enable() ?  $this->sale_lib->get_change_sale_date() : false;

		$old_date = $this->sale_lib->get_change_sale_id()  ? $this->Sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
		$old_date=  $old_date ? date(get_date_format().' '.get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format().' '.get_time_format());
		$data['transaction_time']= $this->sale_lib->get_change_sale_date_enable() ?  date(get_date_format().' '.get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;

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
		if ($suspended_change_sale_id && !$data['ref_no'])
		{
			$sale_info = $this->Sale->get_info($suspended_change_sale_id)->row_array();
			$data['ref_no'] = $sale_info['cc_ref_no'];
		}

		//If we have a previous sale make sure we get the auth_code unless we already have it set
		if ($suspended_change_sale_id && !$data['auth_code'])
		{
			$sale_info = $this->Sale->get_info($suspended_change_sale_id)->row_array();
			$data['auth_code'] = $sale_info['auth_code'];
		}

		//If we have a suspended sale, update the date for the sale
		if ($this->sale_lib->get_suspended_sale_id() && $this->config->item('change_sale_date_when_completing_suspended_sale'))
		{
			$data['change_sale_date'] = date('Y-m-d H:i:s');
		}
		$data['store_account_payment'] = $this->sale_lib->get_mode() == 'store_account_payment' ? 1 : 0;
		//SAVE sale to database 
		if($this->sale_lib->get_mode() == 'sale'  && isset($data['balance']) && $customer_id)
		{
			$add_customer=$this->Sale->add_petty_cash_customer($customer_id, $data['balance']);
		}

		if($data['store_account_payment']==1  && isset($data['balance']) && $customer_id)
		{				
            $sale_id_raw = $this->Sale->save_petty_cash($data['cart'], $customer_id, $employee_id, $sold_by_employee_id, $data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,$data['ref_no'],$data['auth_code'], $data['change_sale_date'], $data['balance'], $data['store_account_payment'],$data['total'],$data['amount_change'],-1);
			if($sale_id_raw<0){
				$this->load->view("sales/error_pagos");
				return;
			}
		}
		else
		{
			$mode=$this->sale_lib->get_mode() ;
			$tier_id = $this->sale_lib->get_selected_tier_id();
			$deleted_taxes=$this->sale_lib->get_deleted_taxes();

			if($this->config->item('subcategory_of_items') && !$this->sale_lib->is_select_subcategory_items()){
				$sale_id_raw=-1;

			}
			else{
				$sale_id_raw = $this->Sale->save($data['cart'], $customer_id, $employee_id, $sold_by_employee_id, 
				$data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,
				$data['ref_no'],$data['auth_code'], $data['change_sale_date'], $data['balance'], $mode,$tier_id,
				$deleted_taxes,
				$data['store_account_payment'],$data['total'],$data['amount_change'],$invoice_type, null,$data["divisa"],$data["opcion_sale"],
				$data["transaction_rate"],$data["transaction_cost"],$data["another_currency"],$data["currency"],$data["total_other_currency"],
				$overwrite_tax,$new_tax,$data["value_other_currency"]);				
           
			}
		}
		
		if($data['store_account_payment']==1)
		{
			$data['sale_id']=$sale_id_raw;
		}
		else
		{
		   $data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id_raw;
		}

		$data['sale_id_raw']=$sale_id_raw;

		if($customer_id != -1)
		{
			$cust_info=$this->Customer->get_info($customer_id);

			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}

		//If we don't have any taxes, run a check for items so we don't show the price including tax on receipt
		if (empty($data['taxes']))
		{
			foreach(array_keys($data['cart']) as $key)
			{
				if (isset($data['cart'][$key]['item_id']))
				{
					$item_info = $this->Item->get_info($data['cart'][$key]['item_id']);
					if($item_info->tax_included)
					{
						$_price = $data['cart'][$key]["has_selected_unit"] == 0 ? $data['cart'][$key]['price'] : $data['cart'][$key]['price_presentation'];

						$price_to_use = get_price_for_item_excluding_taxes($data['cart'][$key]['item_id'], $_price);
						$data['cart'][$key]['price'] = $price_to_use;
					}
				}
				elseif (isset($data['cart'][$key]['item_kit_id']))
				{
					$item_info = $this->Item_kit->get_info($data['cart'][$key]['item_kit_id']);
					if($item_info->tax_included)
					{
						$price_to_use = get_price_for_item_kit_excluding_taxes($data['cart'][$key]['item_kit_id'], $data['cart'][$key]['price']);
						$data['cart'][$key]['price'] = $price_to_use;
					}
				}

			}

		}
		if( $data['mode']=='return'){
			foreach($data['cart'] as $key=> $cart){
			$data['cart'][$key]['quantity']=$data['cart'][$key]['quantity']*-1;
		    }
		}
		
		if ($data['sale_id'] == $this->config->item('sale_prefix').' -1')
		{
			$data['error_message'] = '';
			/*if (is_sale_integrated_cc_processing())
			{
				$data['error_message'].=lang('sales_credit_card_transaction_completed_successfully').'. ';
			}*/
			if($this->config->item('activar_casa_cambio')==true){
				$sale_emp_info;
				$balance=$sale_emp_info->account_balance;
				if($sale_emp_info->type=="credito" && $balance<abs($this->sale_lib->get_total())){
					//echo json_encode(array('respuesta' =>false,"mensaje"=> "¡Saldo insuficiente!"));
					$data['error_message'] .= " <strong>¡Saldo insuficiente!</strong>  ";				
					
				}
			}
			
				$data['error_message'] .= lang('sales_transaction_failed');
			
		}
		else
		{
			if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
			{
				/*$this->load->library('email');
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
				$this->email->to($cust_info->email); 

				$this->email->subject(lang('sales_receipt'));
				$this->email->message($this->load->view("sales/receipt_email",$data, true));
				$this->email->send();*/

				$data['taxes']=$this->sale_lib->get_taxes();
				$this->load->library('Email_send');
				$para=$cust_info->email;
				$subject=lang('sales_receipt');
				$name="";
				$company=$this->config->item('company');
				$from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
				$this->email_send->send_($para, $subject, $name, 
				$this->load->view("sales/receipt_email",$data, true),$from,$company) ;

				
			}
		}

        if( $this->config->item('send_txt_invoice') )
        {
            $this->load->library('ftp');
            $this->load->helper('file');

            $txt_receipt_file  = $data['sale_id'].'.txt';
            $txt_receipt_route = 'tmp/'.$txt_receipt_file;

            $config['hostname'] = $this->config->item('ftp_hostname');
            $config['username'] = $this->config->item('ftp_username');
            $config['password'] = $this->config->item('ftp_password');
            $config['route']    = $this->config->item('ftp_route');
            $config['debug']    = TRUE;

            //saves receipts in a temporary folder and then sends them with ftp
            if ( write_file($txt_receipt_route, $this->load->view("sales/txt_receipt",$data, true)))
            {
                $this->ftp->connect($config);
                $this->ftp->upload($txt_receipt_route, $config['route'].$txt_receipt_file, 'ascii', 0775);
                $this->ftp->close();
                unlink($txt_receipt_route);
            }
		}
		
		if($customer_id!=-1 and  $data['store_account_payment'] == 1 and $this->config->item('show_payments_ticket') == 1  ){
			$data['payments_petty_cashes']=$this->Sale->get_petty_cash($customer_id,false,5);			
		}
		if($data["generate_txt"])
		{
			$txt_receipt_file  = $data['sale_id']."-T".'.txt';
			$data_txt = $this->load->view("sales/txt_receipt_2",$data, true);
			$this->load->view("sales/download_txt",array("name"=>$txt_receipt_file,"data_txt"=>$data_txt));	
			$this->sale_lib->clear_all();	
			$this->update_viewer(3);
			return 0;
		}
      
        if($this->sale_lib->get_show_receipt())
        {
			$this->sale_lib->clear_all();
			$this->update_viewer(3);
            redirect('sales'); 
        }
        else
        {	
			$data['without_policy']=$this->sale_lib->get_without_policy();
            $this->load->view("sales/receipt",$data);
        }
		$this->sale_lib->clear_all();
		$this->update_viewer(3);

	}

	function open_money_drawer(){
		$this->load->view("sales/receipt_clear_open_money_drawer",array("offline"=>false));
	}
	function open_money_drawer_offline(){
		$this->load->view("sales/receipt_clear_open_money_drawer",array("offline"=>true));
	}

	function email_receipt($sale_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();

		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->copy_entire_sale($sale_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$tier_id = $sale_info['tier_id'];
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name;
		$data['register_name'] = $this->Register->get_register_name($sale_info['register_id']);
		$data['subtotal']=$this->sale_lib->get_subtotal($sale_id);
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$sold_by_employee_id=$sale_info['sold_by_employee_id'];
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);

		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due_round($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $sale_info['employee_id'] ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		
		

		$data['ref_no'] = $sale_info['cc_ref_no'];
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

			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}

		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['sale_id_raw']=$sale_id;
		$data['store_account_payment'] = FALSE;

		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}

		if ($sale_info['suspended'] > 0)
		{
			if ($sale_info['suspended'] == 1)
			{
				$data['sale_type'] = lang('sales_layaway');
			}
			elseif ($sale_info['suspended'] == 2)
			{
				$data['sale_type'] = lang('sales_estimate');
			}
		}

		if (!empty($cust_info->email))
		{
			/*$this->load->library('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
			$this->email->to($cust_info->email);

			$this->email->subject(lang('sales_receipt'));
			$this->email->message($this->load->view("sales/receipt_email",$data, true));
			$this->email->send();*/
			
			$data['taxes']=$this->sale_lib->get_taxes();
				$this->load->library('Email_send');
				$para=$cust_info->email;
				$subject=lang('sales_receipt');
				$name="";
				$company=$this->config->item('company');
				$from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
				$this->email_send->send_($para, $subject, $name, 
				$this->load->view("sales/receipt_email",$data, true),$from,$company) ;
		}

		$this->sale_lib->clear_all();

		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
	}
	function email_receipt_quotes($quote_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();

		$sale_info = $this->Sale->get_info_quotes($quote_id)->row_array();
		$this->sale_lib->copy_entire_sale_quotes($quote_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$tier_id = $sale_info['tier_id'];
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name;
		$data['register_name'] = $this->Register->get_register_name($sale_info['register_id']);
		$data['subtotal']=$this->sale_lib->get_subtotal($quote_id);
		$data['taxes']=$this->sale_lib->get_taxes($quote_id);
		$data['total']=$this->sale_lib->get_total($quote_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$sold_by_employee_id=$sale_info['sold_by_employee_id'];
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);

		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due_round($quote_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $sale_info['employee_id'] ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');

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

			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}

		$data['quote_id']=$this->config->item('quote_prefix').' '.$quote_id;

		$data['sale_id_raw']=$quote_id;
		$data['store_account_payment'] = FALSE;

		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}

		if (!empty($cust_info->email))
		{
			
		/*	$this->load->library('email');
			$config['mailtype'] = 'html';
			$config['charset']  = 'utf-8';
            $config['newline']  = "\r\n";
            $config['mailtype'] = 'html';
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'server.hostingoptimo.com';
            $config['smtp_port'] = '25';
            $config['smtp_user'] = 'no-reply@ingeniandoweb.com';
            $config['smtp_pass'] = 'o&=c53#m4gf~';
            $config['validation'] = true;
			$this->email->initialize($config);
			$this->email->from($this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@ingeniandoweb.com', $this->config->item('company'));
			$this->email->to($cust_info->email);
			$this->email->subject(lang('sales_receipt'));
			$this->email->message($this->load->view("sales/receipt_email_quotes",$data, true));
			$this->email->send();*/
			
				$this->load->library('Email_send');
				$para=$cust_info->email;
				$subject=lang('sales_receipt');
				$name="";
				$company=$this->config->item('company');
				$from=$this->Location->get_info_for_key('email') ? $this->Location->get_info_for_key('email') : 'no-reply@FacilPos.com';
				$this->email_send->send_($para, $subject, $name, 
				$this->load->view("sales/receipt_email_quotes",$data, true),$from,$company) ;

			
		}

		$this->sale_lib->clear_all();

		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
	}

	function imprimir(){
		$this->load->view("sales/receipt_offline");
	}
	function receipt_comanda($ntabe)
	{
		
		$data=array();
      
		$items=  $this->sale_lib->get_cart();
      
        $data = array();

        
		$data["items"]   =$items;  
		$data["ntabe"]=$ntabe;
		$data["description"]= $this->sale_lib->get_comment();
		
		$retu=$this->suspend(1,1);
        $this->load->view("sales/receipt_comanda", $data);
	}

	function receipt($sale_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();


		$data['is_sale'] = FALSE;
        $sale_numeration = $this->Sale->get_sale_numeration($sale_id);
		$data['sale_number'] = ($sale_numeration->is_invoice == 1) ? $sale_numeration->invoice_number : $sale_numeration->ticket_number;
		$data['sale_type'] = ($sale_numeration->is_invoice == 1) ? lang('sales_invoice') : lang('sales_ticket_on_receipt');
        $data['show_comment_ticket'] = ($sale_numeration->is_invoice) ? 0 : 1;
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['show_payment_times'] = TRUE;
		$data["overwrite_tax"]=false;
		$tier_id = $sale_info['tier_id'];
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name;
		$data['register_name'] = $this->Register->get_register_name($sale_info['register_id']);

		$data['subtotal']=$this->sale_lib->get_subtotal($sale_id);
		$data['discount']=$this->sale_lib->get_discount($sale_id);
		//$data['taxes']=$this->sale_lib->get_taxes($sale_id);
        $data['detailed_taxes']=$this->sale_lib->get_detailed_taxes($sale_id);
        $data['detailed_taxes_total']=$this->sale_lib->get_detailed_taxes_total($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['comment'] = $this->Sale->get_comment($sale_id);
		$data['ntable'] = $this->Sale->get_table($sale_id);
		$data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt($sale_id);
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();

		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$sold_by_employee_id=$sale_info['sold_by_employee_id'];
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $sale_info['employee_id'] ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		$data['ref_no'] = $sale_info['cc_ref_no']; 
		$data['auth_code'] = $sale_info['auth_code'];
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
		$data['opcion_sale']=$this->sale_lib->get_opcion_sale();
		$data['divisa']=$this->sale_lib->get_divisa();
		$data["show_return_policy_credit"]= ($this->config->item('show_return_policy_credit')==1 and $this->sale_lib->get_payment_amount(lang('sales_store_account'))!=0);

		$activar_casa_cambio= $this->config->item('activar_casa_cambio');
		$data["total_divisa"]=$activar_casa_cambio==1?$this->sale_lib->get_total_divisa():null;		
		$data["show_number_item"]=$this->config->item('show_number_item');
		$data["another_currency"]= $this->sale_lib->get_pagar_otra_moneda();
		$data["currency"]=$data["another_currency"]==0?null:$sale_info["currency"];
		$data["total_other_currency"]=$data["another_currency"]==0?null:$sale_info["total_other_currency"];
		$data["value_other_currency"]=$this->sale_lib->get_equivalencia_divisa();
		$data["serie_number"] =$this->sale_lib->get_serie_number(); 
		$data["mode"]=$this->Sale->is_return($sale_id)? "return" : "sale";
		
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

			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['sale_id_raw']=$sale_id;
		$data['store_account_payment'] = FALSE;

		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}

		//If we don't have any taxes, run a check for items so we don't show the price including tax on receipt
		if (empty($data['taxes']))
		{
			foreach(array_keys($data['cart']) as $key)
			{
				if (isset($data['cart'][$key]['item_id']))
				{
					$item_info = $this->Item->get_info($data['cart'][$key]['item_id']);
					if($item_info->tax_included)
					{
						$price_to_use = get_price_for_item_excluding_taxes($data['cart'][$key]['item_id'], $data['cart'][$key]['price']);
						$data['cart'][$key]['price'] = $price_to_use;
					}
				}
				elseif (isset($data['cart'][$key]['item_kit_id']))
				{
					$item_info = $this->Item_kit->get_info($data['cart'][$key]['item_kit_id']);
					if($item_info->tax_included)
					{
						$price_to_use = get_price_for_item_kit_excluding_taxes($data['cart'][$key]['item_kit_id'], $data['cart'][$key]['price']);
						$data['cart'][$key]['price'] = $price_to_use;
					}
				}

			}

		}

		if ($sale_info['suspended'] > 0)
		{
			if ($sale_info['suspended'] == 1)
			{
				$data['sale_type'] = lang('sales_layaway');
			}
			elseif ($sale_info['suspended'] == 2)
			{
				$data['sale_type'] = lang('sales_estimate');
			}
		}
		$data['without_policy']=false;

		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();

		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
	}

	function fulfillment($sale_id)
	{
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$data['comment'] = $this->Sale->get_comment($sale_id);
		$data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt($sale_id);

		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$sale_info['customer_id'];

		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
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
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['sale_id_raw']=$sale_id;
		$data['sales_items'] = $this->Sale->get_sale_items_ordered_by_category($sale_id)->result_array();
		$data['sales_item_kits'] = $this->Sale->get_sale_item_kits_ordered_by_category($sale_id)->result_array();
		$data['discount_exists'] = $this->_does_discount_exists($data['sales_items']) || $this->_does_discount_exists($data['sales_item_kits']);
		$this->load->view("sales/fulfillment",$data);
	}

	function _does_discount_exists($cart)
	{
		if (isset($cart))
		{

		}
		foreach($cart as $line=>$item)
		{
			if( (isset($item['discount']) && $item['discount']>0 ) || (isset($item['discount_percent']) && $item['discount_percent']>0 ) )
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	function edit($sale_id)
	{
		if(!$this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}

		$data = array();
        $data['sale_numeration'] = $this->Sale->get_sale_numeration($sale_id);

		$data['customers'] = array('' => 'No Customer');


		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();

		$data['store_account_payment'] = FALSE;

		foreach($this->Sale->get_sale_items($sale_id)->result_array() as $row)
		{
			$item_info = $this->Item->get_info($row['item_id']);

			if ($item_info->name == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}

		$this->load->view('sales/edit', $data);
	}
	function delete_petty_cash()
	{
		if(!$this->Employee->has_module_action_permission('sales', 'delete_payments_to_credit', $this->Employee->get_logged_in_employee_info()->person_id)){
			$data['error']="No tiene permiso para eliminar el registro";
		}		
		else{
			$petty_cash_id=$this->input->post("petty_cash_id");
			$data=array();
			$this->check_action_permission('delete_sale');
			$result=$this->Sale->delete_petty_cash($petty_cash_id);
			if(!$result){
				$data['error']="No pude eliminar el registro";
			}
		}
		$this->_reload($data);

	}
	function delete($sale_id)
	{
		$this->check_action_permission('delete_sale');
		$status =array();
		if($this->Sale-> is_deleted_sale($sale_id)){
			$status["success"]=false;	
			$status["error"]= "<strong>La venta ya está eliminada.</strong>";
		}
		else{
			//$this->sale_lib->save_current_sale_state();		
			$this->sale_lib->clear_all();
			$this->sale_lib->copy_entire_sale($sale_id, true);
			$status = $this->Sale->delete_sale($sale_id);
			$this->sale_lib->clear_all();
			//$this->sale_lib->restore_current_sale_state();
		}
		$this->load->view('sales/delete', $status);
	}

	function undelete($sale_id)
	{
		$status =array();
		if(($this->config->item('activar_casa_cambio')==true)){
			$status["success"]=false;	
			$status["error"]= "<strong>La venta no se puede restaurar.</strong>";
		}
		else{
			$status = $this->Sale->undelete_sale($sale_id);
		}

		$this->load->view('sales/undelete', $status);
	}

	function save_($sale_id)
	{
		$sale_data = array(
			'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'ntable' => $this->input->post('ntable'),
			'show_comment_on_receipt' => $this->input->post('show_comment_on_receipt') ? 1 : 0
		);

		$sale_info = $this->Sale->get_info($sale_id)->row_array();

		if (date('Y-m-d', strtotime($this->input->post('date')))== date('Y-m-d', strtotime($sale_info['sale_time'])))
		{
			unset($sale_data['sale_time']);
		}

		if ($this->Sale->update($sale_data, $sale_id))
		{
			echo json_encode(array('success'=>true,'message'=>lang('sales_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('sales_unsuccessfully_updated')));
		}
	}
	function set_otra_moneda(){
		$numero=$this->input->post('moneda_numero');
		$this->sale_lib->set_moneda_numero($numero);
		$this->sale_lib->set_currency($this->config->item("moneda".$numero));
		$this->sale_lib->set_equivalencia_divisa($this->config->item("equivalencia".$numero));
		$this->sale_lib->set_pagar_otra_moneda($this->input->post('otra_moneda'));
		echo json_encode($this->config->item("equivalencia".$numero));
	}

	function _payments_cover_total()
	{
		$total_payments = 0;

		foreach($this->sale_lib->get_payments() as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}
		$round_value=to_currency_no_money(round( $this->sale_lib->get_total() - $total_payments));
        $value=to_currency_no_money( $this->sale_lib->get_total() - $total_payments );
        $value_compare=$this->config->item('round_value')==1 ? $round_value : $value;
		/* Changed the conditional to account for floating point rounding */
		if ( ( $this->sale_lib->get_mode() == 'sale' || $this->sale_lib->get_mode() == 'store_account_payment' ) && $value_compare > 1e-6  )
		{
			return false;
		}

		return true;
	}
	function reload()
	{
		$this->_reload();
	}
	function reload_change($resumen_venta=true){
		$this->_reload(array(),  true,$resumen_venta);
	}

	function _reload($data=array(), $is_ajax = true,$resumen_venta=false)
	{
        //Configuration values for showing/hiding fields in the sales grid
        $data['show_sales_price_iva'] = $this->config->item('show_sales_price_iva');
        $data['show_sales_price_without_tax'] = $this->config->item('show_sales_price_without_tax');
        $data['show_sales_num_item'] = $this->config->item('show_sales_num_item');
        $data['show_sales_discount'] = $this->config->item('show_sales_discount');
        $data['show_sales_inventory'] = $this->config->item('show_sales_inventory');
		$data['show_sales_description'] = $this->config->item('show_sales_description');
		$data['subcategory_of_items']=$this->config->item('subcategory_of_items');
		
		$data['is_tax_inclusive'] = $this->_is_tax_inclusive();
		if ($data['is_tax_inclusive'] && count($this->sale_lib->get_deleted_taxes()) > 0)
		{
			$this->sale_lib->clear_deleted_taxes();
		}
		
		$person_info = $this->Employee->get_logged_in_employee_info();

		$data["employee_logueado"]=$person_info;
		$data["logged_in_employee_id"]=$person_info->person_id;

		$modes = array('sale'=>lang('sales_sale'));		
		if ($this->Employee->has_module_action_permission('sales','return_item', $person_info->person_id) ) { 
			$modes['return']=lang('sales_return'). " ".lang("sales_without")." ".strtolower ($this->config->item('sale_prefix'));
		}
		if ($this->Employee->has_module_action_permission('sales','return_item_with_invoice', $person_info->person_id) ) { 
			$modes['return_ticket']=lang('sales_return')." ".lang("sales_with")." ".strtolower ($this->config->item('sale_prefix'));
		}
		if($this->config->item('customers_store_accounts'))
		{
			$modes['store_account_payment'] = lang('sales_store_account_payment');
		}		
		$data["cancelar_despues_desuspender"]=(!$this->sale_lib->get_suspended_sale_id() || !$this->Employee->has_module_action_permission('sales', 'cancel_sale_suspend', $person_info->person_id));

		$data['cart']=$this->sale_lib->get_cart();
		$data["sold_by_employee_id"]=$this->sale_lib->get_sold_by_employee_id();
		$data['modes']= $modes;
		$data['mode']=$this->sale_lib->get_mode();
		$data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_module_permission('items', $person_info->person_id);

		$data['comment'] = $this->sale_lib->get_comment();
		$data['ntable'] = $this->sale_lib->get_ntable();
		$data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
		$data['show_receipt'] = $this->sale_lib->get_show_receipt();
		$data['without_policy'] = $this->sale_lib->get_without_policy();
		$data['show_comment_ticket'] = $this->sale_lib->get_comment_ticket();
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_totals_excluding_store_account();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		$data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
		$data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
		$data['selected_tier_id'] = $this->sale_lib->get_selected_tier_id();
		$data['is_over_credit_limit'] = false;
		$data['add_cart_by_id_item'] = $this->config->item('add_cart_by_id_item');
		$data["edit_tiers"]=$this->Employee->has_module_action_permission('sales', 'edit_tier', $person_info->person_id);
		$data["overwrite_tax"]= $this->sale_lib->get_overwrite_tax();
		$data["new_tax"]= $this->sale_lib->get_new_tax();
		$data["pagar_otra_moneda"]=   $this->sale_lib->get_pagar_otra_moneda();
		$data["moneda_numero"]=   $this->sale_lib->get_moneda_numero();
		$data["equivalencia"]= $this->sale_lib->get_equivalencia_divisa();
		$data["currency"]=$this->sale_lib->get_currency();
		$data["select_seller_during_sale"]=$this->Employee->has_module_action_permission('sales', 'select_seller_during_sale', $person_info->person_id) ;
		$data["generate_txt"] = $this->sale_lib->get_generate_txt();
		$data['selected_sold_by_employee_id'] = $this->sale_lib->get_sold_by_employee_id();
		$tiers = array();

		$tiers[0] = lang('items_none');
		foreach($this->Tier->get_all()->result() as $tier)
		{
			$tiers[$tier->id]=$tier->name;
		}

		$data['tiers'] = $tiers;

			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit')
			);

			if($this->config->item('customers_store_accounts') && $this->sale_lib->get_mode() != 'store_account_payment')
			{
				$data['payment_options']=array_merge($data['payment_options'],	array(lang('sales_store_account') => lang('sales_store_account')
				));
			}

		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}

		$customer_id=$this->sale_lib->get_customer();
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name.($info->company_name==''  ? '' :' ('.$info->company_name.')');
			$data['customer_email']=$info->email;
			$data['points']=$this->Customer->get_info_points($customer_id);
			$data['customer_balance'] = $info->balance;
			$data['customer_credit_limit'] = $info->credit_limit;
			$data['is_over_credit_limit'] = $this->sale_lib->is_over_credit_limit();
			$data['customer_id']=$customer_id;
			$data['customer_cc_token'] = $info->cc_token;
			$data['customer_cc_preview'] = $info->cc_preview;
			//$data['save_credit_card_info'] = $this->sale_lib->get_save_credit_card_info();
			$data['use_saved_cc_info'] = $this->sale_lib->get_use_saved_cc_info();
			$data['avatar']=$info->image_id ?  site_url('app_files/view/'.$info->image_id) : ""; //can be changed to  base_url()."/img/avatar.png" if it is required

			if (!$this->config->item('hide_customer_recent_sales'))
			{
				$data['recent_sales'] = $this->Sale->get_recent_sales_for_customer($customer_id);
			}
		}

		$data['customer_required_check'] = (!$this->config->item('require_customer_for_sale') || ($this->config->item('require_customer_for_sale') && isset($customer_id) && $customer_id!=-1));

		$data['payments_cover_total'] = $this->_payments_cover_total();

		//Value total + total_cash
		$data['total_max'] = 0;

		if ($is_ajax)
		{
			if($this->config->item('activar_casa_cambio') == 1)
			{
				$this->change($data,$is_ajax,$resumen_venta);
			}
			else
			{
				$this->load->view("sales/register",$data);
			}
			
			$this->update_viewer(1);
		}
		else
		{
			$sale_id =  $this->sale_lib->get_change_sale_id();
			$data['sale_data'] = $this->Sale->get_info($sale_id)->row_array();			
			$this->sale_lib->set_comment_ticket($this->config->item('default_sales_type'));
			if($this->config->item('activar_casa_cambio') == 1)
			{
				$data["pagar_otra_moneda"] = $this->sale_lib->get_pagar_otra_moneda();
				$this->change($data);
			}
			else
			{
				$employees = array('' => lang('common_not_set'));

				foreach($this->Employee->get_all()->result() as $employee)
				{
					if ($this->Employee->is_employee_authenticated($employee->person_id, $this->Employee->get_logged_in_employee_current_location_id()))
					{
						$employees[$employee->person_id] = $employee->first_name.' '.$employee->last_name;
					}
				}
				$data['employees'] = $employees;
				$this->load->view("sales/register_initial",$data);
			}
		}
	}
	function edit_item_tier($line,$item_id = false){
		$data= array();
		$tier_id = $this->input->post("tier_id_item");
		$this->sale_lib->set_id_tier_item($line,$tier_id);
		$this->sale_lib->change_price_item($line,$tier_id);
		$this->_reload($data);
	}
	function view_unit_modal($line,$item_id)
	{
		$this->load->model("Item_unit_sell");
		$data = array();

		$data['item_info'] = $this->Item->get_info($item_id);
		$data["unit_item"] = $this->Item_unit_sell->get_all_by_item($item_id)->result();
		$items = $this->sale_lib->get_cart();
		$data["name_unit"] = $items[$line]["name_unit"];
		$data["line"] = $line;
		$this->load->view("sales/items_modal_unit",$data);
		
	}
	function edit_unit($line,$item_id, $unit_id, $type = 1)
	{
		// $type: quitar 0, seleccionar 1
		$this->sale_lib-> edit_unit($line,$item_id,$unit_id, $type );

	}
    function cancel_sale()
    {
		if ($this->Location->get_info_for_key('enable_credit_card_processing'))
		{
 			require_once(APPPATH.'libraries/MercuryProcessor.php');
 			$credit_card_processor = new MercuryProcessor($this);

			if (method_exists($credit_card_processor, 'void_partial_transactions'))
			{
				if (!$credit_card_processor->void_partial_transactions())
				{
		     		$this->sale_lib->clear_all();
					$this->_reload(array('error' => lang('sales_attempted_to_reverse_partial_transactions_failed_please_contact_support')), true);
					return;
				}
   		 	}
		}

     	$this->sale_lib->clear_all();
        $this->config->item('default_sales_type') ? $_SESSION['show_comment_ticket'] = 1 : $_SESSION['show_comment_ticket'] = 0;

     	$this->_reload(array(),true,true);
	}

	function suspend($suspend_type = 1, $print_comanda=false)
	{
		$this->load->model('quote');
		$this->load->model('Orders_sales');
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$sold_by_employee_id=$this->sale_lib->get_sold_by_employee_id();
		$comment = $this->sale_lib->get_comment();
		$comment = $this->sale_lib->get_comment();
		$show_comment_on_receipt = $this->sale_lib->get_comment_on_receipt();
		$emp_info=$this->Employee->get_info($employee_id);
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=$this->sale_lib->get_amount_due() * -1;
		$data['balance']=$this->sale_lib->get_payment_amount(lang('sales_store_account'));
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data["another_currency"]= $this->sale_lib->get_pagar_otra_moneda();
		$data["currency"]=$data["another_currency"]==0?null:$this->sale_lib->get_currency();
		$data["total_other_currency"]=$data["another_currency"]==0?null:((double)$data['total']/(double) $this->sale_lib->get_equivalencia_divisa());
		$data["overwrite_tax"]= $this->sale_lib->get_overwrite_tax();
		$data["new_tax"]= $this->sale_lib->get_new_tax();
		$data["value_other_currency"]=$this->sale_lib->get_equivalencia_divisa();

		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
		}

		$total_payments = 0;

		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}

		$sale_id = $this->sale_lib->get_suspended_sale_id();
		$serie_number =$this->sale_lib->get_serie_number(); 
		
        if( $sale_id == false )
        {
            if($this->sale_lib->get_comment_ticket() == 1)
            {
                $invoice_type['invoice_number'] = NULL;
                $invoice_type['ticket_number']  = $this->Sale->get_next_sale_number(0,$location_id);
				$invoice_type['is_invoice']     = 0;
				$invoice_type['serie_number_invoice']=$serie_number;
            }
            else
            {
                $invoice_type['invoice_number'] = $this->Sale->get_next_sale_number(1,$location_id);
                $invoice_type['ticket_number']  = NULL;
				$invoice_type['is_invoice']     = 1;
				$invoice_type['serie_number_invoice']=$serie_number;
            }
        }
        else
        {
            $sale_numeration = $this->Sale->get_sale_numeration($sale_id);

            if($this->sale_lib->get_comment_ticket() == 1)
            {
                $invoice_type['invoice_number'] = NULL;
                $invoice_type['ticket_number']  = $sale_numeration->ticket_number;
				$invoice_type['is_invoice']     = $sale_numeration->is_invoice;
				$invoice_type['serie_number_invoice']=$serie_number;
            }
            else
            {
                $invoice_type['invoice_number'] = $sale_numeration->invoice_number;
                $invoice_type['ticket_number']  = NULL;
				$invoice_type['is_invoice']     = $sale_numeration->is_invoice;
				$invoice_type['serie_number_invoice']=$serie_number;
            }
        }
	 
		if ($suspend_type ==3)
		{
			$sale_id = $this->quote->save_quote($data['cart'], $customer_id,$employee_id, $sold_by_employee_id, $comment,$show_comment_on_receipt,$data['payments'], $sale_id, $suspend_type,'','',$this->config->item('change_sale_date_when_suspending') ? date('Y-m-d H:i:s') : FALSE, $data['balance'],0,
			$data["overwrite_tax"],$data["new_tax"]);
		}

		if ($suspend_type ==2 || $suspend_type ==1)
		{
			if($this->appconfig->get('enabled_for_Restaurant') == '1'){
				$ntbale =  $this->sale_lib->get_ntable();
			}else{
				$ntbale = null;
			}
			$mode=$this->sale_lib->get_mode() ;
			$tier_id = $this->sale_lib->get_selected_tier_id();
			$deleted_taxes=$this->sale_lib->get_deleted_taxes();
			$sale_id = $this->Sale->save($data['cart'], $customer_id,$employee_id, $sold_by_employee_id, $comment,$show_comment_on_receipt,$data['payments'], $sale_id, $suspend_type,'',''
			,$this->config->item('change_sale_date_when_suspending') ? date('Y-m-d H:i:s') : FALSE,
			 $data['balance'],$mode,$tier_id ,$deleted_taxes,0,0,0,$invoice_type, $ntbale,null, 
			 null, null, null, $data["another_currency"],$data["currency"],$data["total_other_currency"] ,$data["overwrite_tax"],$data["new_tax"],$data["value_other_currency"]);
			 if($sale_id<0||( !$this->sale_lib->is_select_subcategory_items())){
				echo("No se puede suspender la venta, Comprueba los producto del carrito, si el producto tiene subcategoría no olvide agregarla ");
					return;
				} 
		}
		if( $suspend_type ==1 and $this->config->item('Generate_simplified_order')==1){
			$data_order=array(
				"employee_id"=>$employee_id,
				"state"=>lang("warehouse_pendiente")
			);
			$this->Orders_sales->save(false,$sale_id,$data_order);

		}

		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		if ($data['sale_id'] == $this->config->item('sale_prefix').' -1')
		{
			$data['error_message'] = lang('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		if(!$print_comanda){
			if ($this->config->item('show_receipt_after_suspending_sale'))
			{
				if ($suspend_type==2 || $suspend_type ==1 )
				{
					redirect('sales/receipt/'.$sale_id);
				}
				if ($suspend_type==3)
				{
					redirect('sales/quotes_receipt/'.$sale_id);
				}
			}

			elseif($suspend_type!=3)
			{
				$this->_reload(array('success' => lang('sales_successfully_suspended_sale')));
			}
			else
			{
				$this->_reload(array('success' => lang('sales_successfully_quote_sale')));
			}
		}
		
	}
	private function update_viewer($type = 1)
	{
		if($this->config->item("show_viewer"))
		{
			
			$this->load->library('viewer_lib');
			$this->load->model('Viewer');
			$this->viewer_lib->update_viewer_cart($this->Employee->person_id_logged_in(),
					$this->sale_lib->get_cart(),$type,$this->sale_lib->get_payments(),
					$this->sale_lib->get_overwrite_tax(),$this->sale_lib->get_new_tax());
		}
	}
	function batch_sale()
	{
		$this->load->view("sales/batch");
	}

	function _excel_get_header_row()
	{
		return array(lang('item_id'),lang('unit_price'),lang('quantity'),lang('discount_percent'));
	}

	function excel()
	{
		$this->load->helper('report');
		$header_row = $this->_excel_get_header_row();

		$content = array_to_spreadsheet(array($header_row));
		force_download('batch_sale_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
	}

	function do_excel_import()
	{
		if (is_on_demo_host())
		{
			$msg = lang('items_excel_import_disabled_on_demo');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}

		set_time_limit(0);
		//$this->check_action_permission('add_update');
		$this->db->trans_start();

		$msg = 'do_excel_import';
		$failCodes = array();

		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = lang('suppliers_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				$objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
				$sheet = $objPHPExcel->getActiveSheet();
				$num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

				//Loop through rows, skip header row
				for($k = 2;$k<=$num_rows; $k++)
				{

					$item_id = $sheet->getCellByColumnAndRow(0, $k)->getValue();
					if (!$item_id)
					{
						$item_id = '';
					}

					$price = $sheet->getCellByColumnAndRow(1, $k)->getValue();
					if (!$price)
					{
						$price = null;
					}

					$quantity = $sheet->getCellByColumnAndRow(2, $k)->getValue();
					if (!$quantity)
					{
						$quantity = 1;
					}

					$discount = $sheet->getCellByColumnAndRow(3, $k)->getValue();
					if (!$discount)
					{
						$discount = 0;
					}

					if($this->sale_lib->is_valid_item_kit($item_id))
					{
						if(!$this->sale_lib->add_item_kit($item_id,$quantity,$discount,$price))
						{
							$this->sale_lib->empty_cart();
							echo json_encode( array('success'=>false,'message'=>lang('batch_sales_error')));
							return;
						}
					}
					elseif(!$this->sale_lib->add_item($item_id,$quantity,$discount,$price))
					{
						$this->sale_lib->empty_cart();
						echo json_encode( array('success'=>false,'message'=>lang('batch_sales_error')));
						return;
					}
				}
			}
			else
			{
				echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
				return;
			}
		}
		$this->db->trans_complete();
		echo json_encode(array('success'=>true,'message'=>lang('sales_import_successfull')));

	}


	function new_giftcard()
	{
		if (!$this->Employee->has_module_action_permission('giftcards', 'add_update', $this->Employee->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}

		$data = array();
		$data['item_id']=$this->Item->get_item_id(lang('sales_giftcard'));
		$this->load->view("sales/giftcard_form",$data);
	}

	function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale->get_all_suspended();
		$this->load->view('sales/suspended', $data);
	}
	function quoteses()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale->get_all_quotoses();
		$this->load->view('sales/quoteses', $data);
	}
	function change_sale_with_ticket($sale_id)
	{
		$this->check_action_permission('return_item_with_invoice');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_change_sale_id($sale_id);		
		//para casa de cambio// se guarda el saldo  se la venta antes de editar   
		$this->sale_lib->set_total_price_transaction_previous($this->sale_lib->get_total_price_transaction());
    	$this->_reload(array(), false);
	}
	function change_sale($sale_id)
	{
		$this->check_action_permission('edit_sale');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_change_sale_id($sale_id);

		if ($this->Location->get_info_for_key('enable_credit_card_processing'))		
			$this->sale_lib->change_credit_card_payments_to_partial();
		
		//para casa de cambio// se guarda el saldo  se la venta antes de editar 
		if($this->config->item('activar_casa_cambio') == true)
			$this->sale_lib->set_total_price_transaction_previous($this->sale_lib->get_total_price_transaction());
		
    	$this->_reload(array(), false);
	}

	function unsuspend($sale_id = 0)
	{
		if($this->appconfig->get('enabled_for_Restaurant') == '1'){
			$sale_id =  $this->Sale->get_table_by_id($sale_id);
			if ($sale_id == false){
				$this->sale_lib->clear_all();
				redirect(site_url('sales'));
			}
		}
		$sale_id = $this->input->post('suspended_sale_id') ? $this->input->post('suspended_sale_id') : $sale_id;
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_suspended_sale_id($sale_id);


		if ($this->sale_lib->get_customer())
		{
			$customer_info=$this->Customer->get_info($this->sale_lib->get_customer());

			if ($customer_info->tier_id)
			{
				$this->sale_lib->set_selected_tier_id($customer_info->tier_id);
			}
		}

    	$this->_reload(array(), false);
	}



	function delete_suspended_sale()
	{
		$this->check_action_permission('delete_suspended_sale');
		$suspended_sale_id = $this->input->post('suspended_sale_id');
		if ($suspended_sale_id)
		{
			$this->sale_lib->delete_suspended_sale_id();
			$this->Sale->delete($suspended_sale_id);
		}
    	redirect('sales/suspended');
	}
		function delete_suspended_sale_quotes()
	{
		$this->check_action_permission('delete_suspended_sale');
		$suspended_quote_id= $this->input->post('suspended_quote_id');

		if ($suspended_quote_id)
		{
			$this->sale_lib->delete_suspended_sale_id();
			$this->Sale->delete_quotes($suspended_quote_id);
		}
    	redirect('sales/quoteses');
	}

	function discount_all()
	{
		$discount_all_percent = (int)$this->input->post('discount_all_percent');
		$this->sale_lib->discount_all($discount_all_percent);
		$this->_reload();
	}
	function categories_new($offset = 0)
	{
		$this->load->model('Categories');
		$data = $this->Categories->get_all();
		echo json_encode($data);
	}
	
	function categories($offset = 0)
	{
		$categories = array();		
		$item_categories = array();

		$this->load->model("Categories");
		$item_categories_items_result = $this->Categories->get_all(); //$this->Item->get_all_categories()->result();

		foreach($item_categories_items_result as $category)
		{			
			if ($category["name"] != lang('sales_giftcard') && $category["name"] != lang('sales_store_account_payment'))
			{
				$item_categories[] = $category["name"];
			}

		}

		$item_kit_categories = array();
		$item_kit_categories_items_result = $this->Item_kit->get_all_categories()->result();

		foreach($item_kit_categories_items_result as $category)
		{
			$item_kit_categories[] = $category->category;
		}

		$categories = array_unique(array_merge($item_categories, $item_kit_categories));
		sort($categories);

		$categories_count = count($categories);
		$config['base_url'] = site_url('sales/categories');
		$config['total_rows'] = $categories_count;
		$config['per_page'] = 15;
		$this->pagination->initialize($config);

		$categories = array_slice($categories, $offset, $config['per_page']);

		$data = array();
		$data['categories'] = $categories;
		$data['pagination'] = $this->pagination->create_links();

		echo json_encode($data);
	}
	function item()
	{
		$item_id = $this->input->get('item_id');	
		$id_tem = $this->Item->get_item_id($item_id);		
		$item = $this->Item->get_info($id_tem == false ? $item_id : $id_tem);	
		$img_src = "";

		if ($item->image_id != 'no_image' && trim($item->image_id) != '') {
			$img_src = site_url('app_files/view/'.$item->image_id);
		}

		$item = array(
			'id' => $item->item_id,
			'name' => character_limiter($item->name, 58),
			'image_src' => 	$img_src,
			"price_tax"=>is_numeric($item->item_id) ? $this->sale_lib->precio_con_iva($item->item_id): 0,
			"unit"=>$item->unit
		);
	
		$data = array();
		$data['item'] = $item;	
		echo json_encode($data);
	}

	function items($offset = 0, $is_vue = false)
	{
		$category = $this->input->post('category');
		
		if($category === false)
			$category = $this->input->get('category');

		$items = array();
		$items_result = $this->Item->get_all_by_category($category, $offset)->result();

		foreach($items_result as $item)
		{
			$img_src = "";
			//echo $item->image_id ;
			if ($item->image_id != 'no_image' && trim($item->image_id) != '') {
				$img_src = site_url('app_files/view/'.$item->image_id);
			}

			$items[] = array(
				'id' => $item->item_id,
				'name' => character_limiter($item->name, 58),
				'image_src' => 	$img_src,
				"price_tax"=> $is_vue  == false ? "": $this->sale_lib->precio_con_iva($item->item_id),
				"unit"=>$item->unit
			);
		}
		$items_count = $this->Item->count_all_by_category($category);

		$config['base_url'] = site_url('sales/items');
		$config['total_rows'] = $items_count;
		$config['per_page'] = 14;
		$this->pagination->initialize($config);

		$data = array();
		$data['items'] = $items;
		$data['pagination'] = $this->pagination->create_links();

		echo json_encode($data);
	}

	function delete_tax($name)
	{
		$this->check_action_permission('delete_taxes');
		$name = rawurldecode($name);
		$this->sale_lib->add_deleted_tax($name);
		$this->_reload(array(),true,true);
	}
	function quotes_receipt($quote_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();

		$data['is_sale'] = FALSE;
		$sale_info = $this->Sale->get_info_quotes($quote_id)->row_array();

		$this->sale_lib->clear_all();
        $this->sale_lib->copy_entire_sale_quotes($quote_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['show_payment_times'] = TRUE;

		$tier_id = $sale_info['tier_id'];
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name;
		$data['register_name'] = $this->Register->get_register_name($sale_info['register_id']);

		$data['subtotal']=$this->sale_lib->get_subtotal($quote_id);
		$data['taxes']=$this->sale_lib->get_taxes_quotes($quote_id);
		$data['total']=$this->sale_lib->get_total_quotes($quote_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['comment'] = $this->Sale->get_comment_quotes($quote_id);
		$data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt_quotes($quote_id);
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();

		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$sold_by_employee_id=$sale_info['sold_by_employee_id'];
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due($quote_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $sale_info['employee_id'] ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		$data['ref_no'] = $sale_info['cc_ref_no'];
		$data['auth_code'] = $sale_info['auth_code'];
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
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

			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}
		$data['quote_id']=lang('sales_estimate').' '.$quote_id;
		$data['sale_id_raw']=$quote_id;
		$data['store_account_payment'] = FALSE;

		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}


		$this->load->view("sales/receipt_quote",$data);
		$this->sale_lib->clear_all();

		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
	}

	function view_shortcuts()
	{
		$this->load->view("sales/shortcuts_sales");
	}

	function sale_details_modal($id_customer=0){
		
		$data["petty_cash"]=$this->Sale->get_petty_cash($id_customer,false,20);
		$data["customer"] = $this->Customer->get_info($id_customer);
		$this->load->view("sales/sale_details_petty_modal",$data);
	}

	function get_monton_pendiente($id_persona=0){
		if(! is_numeric($id_persona) ||$id_persona==0 ){
			echo 0;

		}else{
			$customer = $this->Customer->get_info($id_persona);
			echo to_currency_no_money($customer->balance);
		}
	}
	function set_new_tax()
	{
		$this->check_action_permission('overwrite_tax');
		$new_tax =  $this->input->post('new_tax');

		if(is_numeric($new_tax) and $new_tax>0){			
			$this->sale_lib->set_overwrite_tax(1);
			$this->sale_lib->set_new_tax(
				array("name"=> $this->config->item('name_new_tax'),"percent"=>(double)$new_tax,"cumulative"=>0));
			$this->sale_lib->clear_deleted_taxes();
		}
		else
		{
			$this->sale_lib->set_overwrite_tax(0);
			$this->sale_lib->clear_new_tax();
		}
		$this->update_viewer(1);
		$this->_reload();
	}
}
?>
