<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
		$this->load->model('Denomination_currency');
		$this->load->model('Change_house'); 
		$this->load->model('Categories');
	}
	function get_url_video($name)
	{
		$name = rawurldecode($name);
		echo json_encode ($this->Appconfig->get_video($name));
	}
	function get_category($id)
	{
		$info = $this->Categories->get_info($id);
		$data = array("existe"=>is_numeric($info->id), "data"=>$info);

		echo json_encode($data);
	}
	function delete_category($id)
	{
		$path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
		$this->Categories->delete($id,$path_long);
	}
	function save_catebory($id = -1)
	{
		if (true) {   
            $response = array("success" => true, "message"=>"");
            $is_guarded = true;
                    
            $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
            $category_data = $this->Categories->get_info($id);
            $name_file = $category_data->img;
            $original_name = $category_data->name_img_original;  
            $name = $this->input->post('name') ? $this->input->post('name'): null;
            
            if($id <= 0 and empty($_FILES["image"]))
            {
                    echo json_encode( array("success" => false, "message"=>"Imagen es requerida"));
                    return;
            }

            if(!file_exists( $path_long))
            {        
                    if(!mkdir($path_long, 0777, true))
                    {               
                        $response = array("success" => false, "message"=>"Error al crear directorio");
                    }
            }
            
            if(!empty($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK)
                {
                    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                    $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));            
                    
                    if($id != -1)           
                        unlink($path_long."/". $category_data->img);

                    if (in_array($extension, $allowed_extensions))
                    {
                        $original_name = $_FILES["image"]["name"];
                        $rand = rand(1, 2000);
                        $name_file = time()."-". $rand.".".$extension;
                        
                        while(file_exists( $path_long."/". $name_file))
                        {
                            $name_file = time()."-". $rand.".".$extension;
                            $rand = rand(1, 1000);
                        }                          

                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['upload_path'] = $path_long;
                        $config['file_name'] = $name_file;
                        $config['max_size'] = "5120";
                        $config['max_width'] = "2000";
                        $config['max_height'] = "2000";
                
                        $this->load->library('upload', $config);
                        
                        if (!$this->upload->do_upload("image")) 
                        {
                            $response = array(
                                "success" => false,
                                "message" =>"Error al subir la imagen");
                            $is_guarded = false;
                        }                                
                    }
                    else
                    {
                        $response = array(
                            "success" => false,
                            "message" =>"El tipo de imagen no es válido.");
                        $is_guarded = false;
                    }           
                }
                elseif($id <= 0) 
                {
                    $response = array(
                        "success" => false,
                        "message" =>"No se pudo cargar la imagen.");
                    $is_guarded = false;
                }
                if($is_guarded)
                {
                    $data = array(  
                        "name" => $name,
                        "img" => $name_file,
                        "name_img_original" => $original_name,
                    );
                    $is_new = $id ==- 1;
					if(($id = $this->Categories->save($id,$data)) === false){
							unlink($path_long."/". $name_file);
							$response = array(
								"success" => false,
								"message" =>"No se pudo guardar los datos.");
					}
					else
					{
						$data = array("id"=>$id,
							"img"=>$name_file,
							"name"=>$name,
							"is_new" => $is_new
						);

						$response["data"]=$data;

					}
					
                }

            
            }
            else
                $response = array(
                    "success" => false,
                    "message" =>"No tiene permisos");

        echo json_encode($response);
	}
	function categories_modal()
	{
		
		$path_img =  PATH_RECUSE."/".$this->Employee->get_store()."/img/categories";
		$data["path_img"] = $path_img;
		$data["categories"] = $this->Categories->get_all();
		$this->load->view("categories_modal",$data);
	}
	function index()
	{	
		$data['controller_name']=strtolower(get_class());
		$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit'),
				lang('sales_store_account') => lang('sales_store_account'),
				lang('sales_supplier_credit') => lang('sales_supplier_credit')
		);
		
		$data['receipt_text_size_options']=array(
			'mm58' => "58 mm",
			'small' => lang('config_small')."(80mm)",
			'medium' => lang('config_medium')."(80mm)",
			'large' => lang('config_large')."(80mm)",
			'extra_large' => lang('config_extra_large')."(80mm)"
			
		);

		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}
		
		$expense_category = $this->Appconfig->get_expense_category();
		$data["expense_category"] = $expense_category;
	
		//$data['array']=$this->lang->load('common', 'spanish');
		
		$units = $this->Appconfig->get_units();
		$data["units"] = $units;

		$data['tiers'] = $this->Tier->get_all();

		$data['currency'] = $this->Denomination_currency->get_all();
		$data['rates']=$this->Change_house->get_rate_all()->result_array();
                $data['tservice'] = $this->Appconfig->get_tipo_servicios();
                $data['tfallas'] = $this->Appconfig->get_tipo_fallas();
				$data['tubi'] = $this->Appconfig->get_ubica_equipos();
				
		$abreviaturas_divisa=array(
			"USD"=>"DOLAR(USD)",
			"EUR"=>"EURO(EUR)",
			"MXN"=>"PESOS MEXICANO(MXN)",
			"JPY"=>"YEN(JPY)",
			"BOB"=>"BOLIVIANO(BS)",
			"VEF"=>"BOÍVAR VENEZOLANO(VEF)",
			"ANG"=>" GUILDER ANTILLANO(NAƒ)",
			"CLP"=>"PESO CHILENO(CLP)",
			"NIO"=>"CÓRDOBA NICARAGÜENSE(NIO)",
			"COP"=>"PESO COLOMBIANO(COP)",
			"CAD"=>"DÓLAR CANADIENSE(CAD)",
			"SVC"=>"EL COLON DEL SALVADOR(SVC)",
			"GTQ"=>"QUETZAL DE GUATEMALA(GTQ)",
			"HNL"=>"LEMPIRA HONDUREÑA(HNL)",
			"PEN"=>"NUEVO SOL PERUANO(PEN[S/.])",
			"CRC"=>"COLÓN COSTARRICENSE(CRC)",
			"PAB"=>"BALBOA PANAMEÑO(PAB)",
			"PYG"=>"GUARANÍ PARAGUAYO(PYG)",
			"BRL"=>"REAL BRASILEÑO(BRL)",
			"DOP"=>"PESO DOMINICANO(DOP)",
			"ARS"=>"PESO ARGENTINO(ARS)",
			"UYU"=>"PESO URUGUAYO(UYU)"
		);
		$data['abreviaturas_divisa'] =$abreviaturas_divisa;

		$this->load->view("config", $data);
	}
	
		
	function save()
	{
		if(!empty($_FILES["company_logo"]) && $_FILES["company_logo"]["error"] == UPLOAD_ERR_OK && !is_on_demo_host() && !$this->Employee->es_demo())
		{
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
			$extension = strtolower(pathinfo($_FILES["company_logo"]["name"], PATHINFO_EXTENSION));
			
			if (in_array($extension, $allowed_extensions))
			{
				$config['image_library'] = 'gd2';
				$config['source_image']	= $_FILES["company_logo"]["tmp_name"];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']	 = 170;
				$config['height']	= 60;
				$this->load->library('image_lib', $config); 
				$this->image_lib->resize();
				$company_logo = $this->Appfile->save($_FILES["company_logo"]["name"], file_get_contents($_FILES["company_logo"]["tmp_name"]), $this->config->item('company_logo'));
			}
		}
		elseif($this->input->post('delete_logo'))
		{
			$this->Appfile->delete($this->config->item('company_logo'));
		}
		
		
		$this->load->helper('directory');
		$valid_languages = directory_map(APPPATH.'language/', 1);
	
		//var_dump($this->input->post('show_receivings_description')); exit();
		//$yyyy=in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english';
		$batch_save_data=array(
			'company'=>$this->input->post('company'),
			'company_dni'=>$this->input->post('company_dni'),
			'company_regimen'=>$this->input->post('company_regimen'),
			'sale_prefix'=>$this->input->post('sale_prefix') ? $this->input->post('sale_prefix') : 'POS',
            'receipt_copies'=>$this->input->post('receipt_copies') ? $this->input->post('receipt_copies') : '1',
			'website'=>$this->input->post('website'),
			'prices_include_tax' => $this->input->post('prices_include_tax') ? 1 : 0,
			'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
			'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
			'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
			'default_tax_2_name'=>$this->input->post('default_tax_2_name'),
			'default_tax_2_cumulative' => $this->input->post('default_tax_2_cumulative') ? 1 : 0,
			'default_tax_3_rate'=>$this->input->post('default_tax_3_rate'),	
			'default_tax_3_name'=>$this->input->post('default_tax_3_name'),
			'default_tax_4_rate'=>$this->input->post('default_tax_4_rate'),	
			'default_tax_4_name'=>$this->input->post('default_tax_4_name'),
			'default_tax_5_rate'=>$this->input->post('default_tax_5_rate'),	
			'default_tax_5_name'=>$this->input->post('default_tax_5_name'),
			'currency_symbol'=>$this->input->post('currency_symbol'),
			'language'=>in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english',
			'date_format'=>$this->input->post('date_format'),
			'time_format'=>$this->input->post('time_format'),
			'print_after_sale'=>$this->input->post('print_after_sale') ? 1 : 0,
			'print_after_receiving'=>$this->input->post('print_after_receiving') ? 1 : 0,
			'round_cash_on_sales'=>$this->input->post('round_cash_on_sales') ? 1 : 0,
			'automatically_email_receipt'=>$this->input->post('automatically_email_receipt') ? 1 : 0,
			'automatically_show_comments_on_receipt' => $this->input->post('automatically_show_comments_on_receipt') ? 1 : 0,
			'id_to_show_on_sale_interface' => $this->input->post('id_to_show_on_sale_interface'),
			'auto_focus_on_item_after_sale_and_receiving' => $this->input->post('auto_focus_on_item_after_sale_and_receiving') ? 1 : 0,
			'barcode_price_include_tax'=>$this->input->post('barcode_price_include_tax') ? 1 : 0,
			'hide_signature'=>$this->input->post('hide_signature') ? 1 : 0,
			'hide_ticket'=>$this->input->post('hide_ticket') ? 1 : 0,			
			'hide_ticket_taxes'=>$this->input->post('hide_ticket_taxes') ? 1 : 0,
			'hide_invoice_taxes_details'=>$this->input->post('hide_invoice_taxes_details') ? 1 : 0,			
			'hide_customer_recent_sales'=>$this->input->post('hide_customer_recent_sales') ? 1 : 0,
			'hide_support_chat'=>$this->input->post('hide_support_chat') ? 1 : 0,
			'active_keyboard'=>$this->input->post('active_keyboard') ? 1 : 0,
			'hide_description'=>$this->input->post('hide_description') ? 1 : 0,
			'disable_confirmation_sale'=>$this->input->post('disable_confirmation_sale') ? 1 : 0,
			'hide_balance_receipt_payment'=>$this->input->post('hide_balance_receipt_payment') ? 1 : 0,
			'system_point' => $this->input->post('system_point')? 1 : 0,
			'limit_cash_flow' => $this->input->post('limit_cash_flow')? 1 : 0,
			'value_max_cash_flow' => $this->input->post('value_max_cash_flow'),
			'show_point' => $this->input->post('show_point')? 1 : 0,
			'show_image' => $this->input->post('show_image')? 1 : 0,
			'round_value' => $this->input->post('round_value')? 1 : 0,
			'value_point' => $this->input->post('value_point'),
			'percent_point' => $this->input->post('percent_point'),
			'thousand_separator' => $this->input->post('thousand_separator'),
			'remove_decimals' => $this->input->post('remove_decimals') ? 1 : 0,
			'decimal_separator' => $this->input->post('decimal_separator'),
			'track_cash' => $this->input->post('track_cash') ? 1 : 0,
			'show_report_register_close' => $this->input->post('show_report_register_close') ? 1 : 0,
			'in_suppliers' => $this->input->post('in_suppliers') ? 1 : 0,
			'number_of_items_per_page'=>$this->input->post('number_of_items_per_page'),
			'additional_payment_types' => $this->input->post('additional_payment_types'),
			'hide_layaways_sales_in_reports' => $this->input->post('hide_layaways_sales_in_reports') ? 1 : 0,
			'hide_store_account_payments_in_reports' => $this->input->post('hide_store_account_payments_in_reports') ? 1 : 0,
			'change_sale_date_when_suspending' => $this->input->post('change_sale_date_when_suspending') ? 1 : 0,
			'change_sale_date_when_completing_suspended_sale' => $this->input->post('change_sale_date_when_completing_suspended_sale') ? 1 : 0,
			'show_receipt_after_suspending_sale' => $this->input->post('show_receipt_after_suspending_sale') ? 1 : 0,
			'customers_store_accounts' => $this->input->post('customers_store_accounts') ? 1 : 0,
			'calculate_average_cost_price_from_receivings' => $this->input->post('calculate_average_cost_price_from_receivings') ? 1 : 0,
			'averaging_method' => $this->input->post('averaging_method'),
			'hide_dashboard_statistics' => $this->input->post('hide_dashboard_statistics'),
			'disable_giftcard_detection' => $this->input->post('disable_giftcard_detection'),
			'disable_subtraction_of_giftcard_amount_from_sales' => $this->input->post('disable_subtraction_of_giftcard_amount_from_sales'),
			'always_show_item_grid' => $this->input->post('always_show_item_grid'),
			'default_payment_type'=> $this->input->post('default_payment_type'),
			'resolution'=>$this->input->post('resolution'),
			'sip_account'=>$this->input->post('sip_account'),
			'sip_password'=>$this->input->post('sip_password'),
			'return_policy'=>$this->input->post('return_policy'),
			'spreadsheet_format' => $this->input->post('spreadsheet_format'),
			'legacy_detailed_report_export' => $this->input->post('legacy_detailed_report_export'),
			'hide_barcode_on_sales_and_recv_receipt' => $this->input->post('hide_barcode_on_sales_and_recv_receipt'),
			'round_tier_prices_to_2_decimals' => $this->input->post('round_tier_prices_to_2_decimals'),
			'group_all_taxes_on_receipt' => $this->input->post('group_all_taxes_on_receipt'),
			'receipt_text_size' => $this->input->post('receipt_text_size'),
			'select_sales_person_during_sale' => $this->input->post('select_sales_person_during_sale'),
			'default_sales_person' => $this->input->post('default_sales_person'),
            'default_sales_type'=>$this->input->post('default_sales_type'),
			'sales_stock_inventory' => $this->input->post('sales_stock_inventory') ? 1 :0,
			'require_customer_for_sale' => $this->input->post('require_customer_for_sale'),
			'commission_default_rate' => (float)$this->input->post('commission_default_rate'),
			'hide_store_account_payments_from_report_totals' => $this->input->post('hide_store_account_payments_from_report_totals'),
			'disable_sale_notifications' => $this->input->post('disable_sale_notifications'),
            'show_sales_price_without_tax' => (int)$this->input->post('show_sales_price_without_tax'),
			'show_sales_price_iva' => (int)$this->input->post('show_sales_price_iva'),
			'show_sales_num_item' => (int)$this->input->post('show_sales_num_item'),
			'show_sales_discount' => (int)$this->input->post('show_sales_discount'),
			'show_sales_inventory' => (int)$this->input->post('show_sales_inventory'),
			'show_sales_description' => (int)$this->input->post('show_sales_description'),
			'show_receivings_cost_without_tax' => (int)$this->input->post('show_receivings_cost_without_tax'),
			'show_receivings_cost_iva' => (int)$this->input->post('show_receivings_cost_iva'),
			'show_receivings_price_sales'=>(int)$this->input->post('show_receivings_price_sales'),
			'show_receivings_discount' => (int)$this->input->post('show_receivings_discount'),
			'show_receivings_inventory' => (int)$this->input->post('show_receivings_inventory'),
			'show_receivings_cost_transport' => (int)$this->input->post('show_receivings_cost_transport'),
			'show_receivings_description' => (int)$this->input->post('show_receivings_description'),
			'show_receivings_num_item' => (int)$this->input->post('show_receivings_num_item'),
			'show_inventory_isbn' => (int)$this->input->post('show_inventory_isbn'),
			'show_inventory_image' => (int)$this->input->post('show_inventory_image'),
			'show_inventory_size' => (int)$this->input->post('show_inventory_size'),
			'show_inventory_model' => (int)$this->input->post('show_inventory_model'),
			"show_number_item"=>(int)$this->input->post('show_number_item'),
			'show_inventory_colour' => (int)$this->input->post('show_inventory_colour'),
			'show_inventory_brand' => (int)$this->input->post('show_inventory_brand'),
			'show_fullname_item' => $this->input->post('show_fullname_item') ? 1 : 0,
			'show_option_policy_returns_sales' => $this->input->post('show_option_policy_returns_sales') ? 1 : 0,
            'send_txt_invoice' => $this->input->post('send_txt_invoice')? 1 : 0,
            'ftp_hostname'     => $this->input->post('value_ftp_hostname') ? $this->input->post('value_ftp_hostname') : 0,
            'ftp_username'     => $this->input->post('value_ftp_username') ? $this->input->post('value_ftp_username') : 0,
            'ftp_password'     => $this->input->post('value_ftp_password') ? $this->input->post('value_ftp_password') : 0,
            'ftp_route'        => $this->input->post('value_ftp_route')    ? $this->input->post('value_ftp_route')    : 0,
            'due_date_alarm'   => $this->input->post('due_date_alarm')     ? $this->input->post('due_date_alarm')     : 0,
			'custom1_name'=>$this->input->post('custom1_name') ? $this->input->post('custom1_name') : '',
			'custom2_name'=>$this->input->post('custom2_name') ? $this->input->post('custom2_name') : '',
			'custom3_name'=>$this->input->post('custom3_name') ? $this->input->post('custom3_name') : '',
			'custom_subcategory1_name'=>$this->input->post('custom_subcategory2_name') ? $this->input->post('custom_subcategory1_name') : '',
			'custom_subcategory2_name'=>$this->input->post('custom_subcategory2_name') ? $this->input->post('custom_subcategory2_name') : '',
			'custom2_support_name'=>$this->input->post('custom2_support_name') ? $this->input->post('custom2_support_name') : '',
			'custom1_support_name'=>$this->input->post('custom1_support_name') ? $this->input->post('custom1_support_name') : '',
			'enabled_for_Restaurant'=>$this->input->post('enabled_for_Restaurant') ? 1 : 0,
			'table_acount' => $this->input->post('table_acount') ? $this->input->post('table_acount') : 0,
			'subcategory_of_items'=>$this->input->post('subcategory_of_items') ? $this->input->post('subcategory_of_items') : 0,
			"order_star"=>$this->input->post('order_star') ? $this->input->post('order_star') : 0,
			"return_policy_support"=>$this->input->post('return_policy_support') ? $this->input->post('return_policy_support') : "",
			"activate_sale_by_serial"=>(int) $this->input->post('activate_sale_by_serial'),
			"add_cart_by_id_item"=>(int) $this->input->post('add_cart_by_id_item'),
			"activar_casa_cambio"=> (int) $this->input->post('activar_casa_cambio'),
			"activar_control_access_employee"=>$this->input->post('activar_control_access_employee')? 1 : 0,
			"reproducrir_sonido_orden"=> (int) $this->input->post('reproducrir_sonido_orden'),
			"show_return_policy_credit"=>(int) $this->input->post('show_return_policy_credit'),
			"return_policy_credit"=> $this->input->post('return_policy_credit'),
			"show_payments_ticket"=> $this->input->post('show_payments_ticket'),
			"Generate_simplified_order"=>(int) $this->input->post('Generate_simplified_order'),
			"offline_sales"=> (int) $this->input->post('offline_sales'),
			"activar_pago_segunda_moneda"=>(int) $this->input->post('activar_pago_segunda_moneda'),
			"moneda1"=>$this->input->post('moneda1'),
			"moneda2"=>$this->input->post('moneda2'),
			"moneda3"=>$this->input->post('moneda3'),
			"equivalencia1"=>(double)$this->input->post('equivalencia1'),
			"equivalencia2"=>(double)$this->input->post('equivalencia2'),
			"equivalencia3"=>(double)$this->input->post('equivalencia3'),
			"offline_sales"=> (int) $this->input->post('offline_sales'),
			"divisa"=>$this->input->post('divisa') ,
			"address"=>$this->input->post('address'),
			"ganancia_distribuidor"=>$this->input->post('ganancia_distribuidor'),
			"phone"=>$this->input->post('phone'),
			"padding_ticket"=>(int)$this->input->post('padding_ticket'),
			"company_giros"=>$this->input->post('company_giros')?$this->input->post('company_giros'):""  ,
			"quantity_subcategory_of_items"=> is_numeric($this->input->post('quantity_subcategory_of_items'))? (int) $this->input->post('quantity_subcategory_of_items'):5,
			"inhabilitar_subcategory1"=>(int)$this->input->post('inhabilitar_subcategory1'),
			"ocultar_forma_pago"=>$this->input->post('ocultar_forma_pago')?$this->input->post('ocultar_forma_pago'):0,
            'st_correo_of_items'=>$this->input->post('st_correo_of_items') ? $this->input->post('st_correo_of_items') : 0,
			"categoria_gastos"=>$this->input->post('categoria_gastos')?$this->input->post('categoria_gastos'):"",
			"monitor_product_rank"=>(int)$this->input->post('monitor_product_rank'),
			"name_new_tax"=>$this->input->post('name_new_tax'),
			"no_print_return_policy" =>(int) $this->input->post('no_print_return_policy'),
			"units_measurement" =>  $this->input->post('units_measurement'),
			"activate_sales_with_balance"=> (int) $this->input->post('activate_sales_with_balance')
		);
		
	//	language
		if (isset($company_logo))
		{
			$batch_save_data['company_logo'] = $company_logo;
		}
		elseif($this->input->post('delete_logo'))
		{
			$batch_save_data['company_logo'] = 0;
		}
		
		if (is_on_demo_host())
		{
			$batch_save_data['language'] = 'english';
			$batch_save_data['currency_symbol'] = '$';
			$batch_save_data['company_logo'] = 0;
			$batch_save_data['company'] = 'POS Ingeniando Web, Inc';
		}
		$nombres_tasa=$this->input->post("nombre_tasa");
		$tasas_venta=$this->input->post("tasa_venta");
		$tasas_compras=$this->input->post("tasa_compra");
		$guardado=true;
		for($i=0; $i< 3; $i++){
			$data=array(
				"name"=>$nombres_tasa[$i],
				"sale_rate"=>(is_numeric($tasas_venta[$i])==true && $tasas_venta[$i]!=0)?$tasas_venta[$i]:1,
				"rate_buy"=>(is_numeric($tasas_compras[$i])==true && $tasas_compras[$i]!=0)?$tasas_compras[$i]:1,
				"deleted"=>0
			);
			if(!$this->Change_house->update_by_id(($i+1),$data)){
				$guardado=false;
			}
		}
		
        if($guardado && $this->Appconfig->batch_save($batch_save_data) && $this->save_currencys($this->input->post('currency_to_edit'), $this->input->post('currency_to_add'), $this->input->post('currency_to_delete'),$this->input->post('type_currenc')) && $this->save_tiers($this->input->post('tiers_to_edit'), $this->input->post('tiers_to_add'), $this->input->post('tiers_to_delete')))
		{
			echo json_encode(array('success'=>true,'message'=>lang('config_saved_successfully')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('config_saved_unsuccessfully')));
		}
	}
	


    function show_hide_video_help()
    {
		$module_id =  $this->input->post('module_id');
		$hide = $this->input->post('show_hide_video');

		if($hide == 1)		
			$this->Tutorial->hide_video($module_id,$this->Employee->person_id_logged_in());
		else
			$this->Tutorial->show_video($module_id,$this->Employee->person_id_logged_in());
         
     //    $batch_save_data//
     //    $this->Appconfig->batch_save()
      
       /*
       switch ($_POST) 
       {

	       	case $this->input->post('video1')=='hide_video_stack1':
	       	$value = $this->input->post('show_hide_video1');
	       	$key = $this->input->post('video1');
	       	break;
	       	
	       	case $this->input->post('video2')=='hide_video_stack2':
	       	$value = $this->input->post('show_hide_video2');
	       	$key = $this->input->post('video2');
	       	break;
	       case $this->input->post('video3')=='hide_video_stack3':
	       	$value = $this->input->post('show_hide_video3');
	       	$key = $this->input->post('video3');
	       	break;
	       	case $this->input->post('video4')=='hide_video_stack4':
	       	$value = $this->input->post('show_hide_video4');
	       	$key = $this->input->post('video4');
	       	break;
	       	case $this->input->post('video5')=='hide_video_stack5':
	       	$value = $this->input->post('show_hide_video5');
	       	$key = $this->input->post('video5');
	       	break;
	       	case $this->input->post('video6')=='hide_video_stack6':
	       	$value = $this->input->post('show_hide_video6');
	       	$key = $this->input->post('video6');
	       	break;
	       	case $this->input->post('video7')=='hide_video_stack7':
	       	$value = $this->input->post('show_hide_video7');
	       	$key = $this->input->post('video7');
	       	break;
	       	case $this->input->post('video8')=='hide_video_stack8':
	       	$value = $this->input->post('show_hide_video8');
	       	$key = $this->input->post('video8');
	       	break;
	    }       
	    
       $batch_save_data[$key] = $value;
       $this->Appconfig->batch_save($batch_save_data);*/


    }
	function save_tiers($tiers_to_edit, $tiers_to_add, $tiers_to_delete)
	{
		if ($tiers_to_edit)
		{
			foreach($tiers_to_edit as $tier_id => $name)
			{
				if ($name)
				{
					$tier_data = array('name' => $name);
					$this->Tier->save($tier_data, $tier_id);
				}
			}
		}
		
		if ($tiers_to_add)
		{
			foreach($tiers_to_add as $name)
			{
				if ($name)
				{
					$tier_data = array('name' => $name);
	
					$this->Tier->save($tier_data);
				}

			}
		}
		
		if ($tiers_to_delete)
		{
			foreach($tiers_to_delete as $tier_id)
			{
				$this->Tier->delete($tier_id);
			}
		}
		return TRUE;
	}
	
	function save_currencys($currency_to_edit, $currency_to_add, $currency_to_delete,$type_currency)
	{
		
        $type_currenc=$this->input->post('type_currency');
		if ($currency_to_edit)
		{
			foreach($currency_to_edit as $currency_id => $name)
			{
				if ($name)
				{

					$dat['name'] = $name;
					
				}
				
		     $this->Denomination_currency->save($dat, $currency_id);
			

			
			}
			foreach($type_currenc as $currency_id => $type)
			{
				if ($type)
				{

					$da['type_currency'] = $type;
					
				}
				
		     $this->Denomination_currency->save($da, $currency_id);
			

			
			}
			
		}
		
	   if ($currency_to_add)
		{

            $i=0;
			foreach($currency_to_add as $name)
			{
			
				if ($name)
				{

					$item_data= array('name'=>$name);
				    $item_data['type_currency'] =  $type_currency[$i];
				}
		
                    $this->Denomination_currency->save($item_data);
					$i++;
			}
			
		}

	   	 
	
		if ($currency_to_delete)
		{
			foreach($currency_to_delete as $currency_id)
			{
				$this->Denomination_currency->delete($currency_id);
			}
		}
	
		return TRUE;

	}

	function backup()
	{
		$this->load->view("backup_overview");
	}
	
	function do_backup()
	{
		set_time_limit(0);
		$this->load->dbutil();
		$prefs = array(
			'format'      => 'txt',             // gzip, zip, txt
			'add_drop'    => FALSE,              // Whether to add DROP TABLE statements to backup file
			'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
			'newline'     => "\n"               // Newline character used in backup file
    	);
		$backup =&$this->dbutil->backup($prefs);
		$backup = 'SET FOREIGN_KEY_CHECKS = 0;'."\n".$backup."\n".'SET FOREIGN_KEY_CHECKS = 1;';
		force_download('php_point_of_sale.sql', $backup);
	}
	
	function do_mysqldump_backup()
	{
		set_time_limit(0);
		
		$mysqldump_paths = array();
		
	    // 1st: use mysqldump location from `which` command.
	    $mysqldump = `which mysqldump`;
		
	    if (is_executable($mysqldump))
		{
			array_unshift($mysqldump_paths, $mysqldump);
		}
		else
		{
		    // 2nd: try to detect the path using `which` for `mysql` command.
		    $mysqldump = dirname(`which mysql`) . "/mysqldump";
		    if (is_executable($mysqldump))
			{
				array_unshift($mysqldump_paths, $mysqldump);			
			}
		}
		
		// 3rd: Default paths
		$mysqldump_paths[] = 'C:\Program Files\PHP Point of Sale Stack\mysql\bin\mysqldump.exe';  //Windows
		$mysqldump_paths[] = 'C:\PHPPOS\mysql\bin\mysqldump.exe';  //Windows
		$mysqldump_paths[] = '/Applications/phppos/mysql/bin/mysqldump';  //Mac
		$mysqldump_paths[] = '/opt/phppos/mysql/bin/mysqldump';  //Linux
		$mysqldump_paths[] = '/usr/bin/mysqldump';  //Linux
		$mysqldump_paths[] = '/usr/local/mysql/bin/mysqldump'; //Mac
		$mysqldump_paths[] = '/usr/local/bin/mysqldump'; //Linux
		$mysqldump_paths[] = '/usr/mysql/bin/mysqldump'; //Linux


		$database = escapeshellarg($this->db->database);
		$db_hostname = escapeshellarg($this->db->hostname);
		$db_username= escapeshellarg($this->db->username);
		$db_password = escapeshellarg($this->db->password);
	
		$success = FALSE;
		foreach($mysqldump_paths as $mysqldump)
		{
			
			if (is_executable($mysqldump))
			{
				$backup_command = "\"$mysqldump\" --host=$db_hostname --user=$db_username --password=$db_password $database";

				// set appropriate headers for download ...  
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="php_point_of_sale.sql"');
				header('Content-Transfer-Encoding: binary');
				header('Connection: Keep-Alive');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				
				$status = false; 
				passthru($backup_command, $status);
				$success = $status == 0;
				break;
			}
		}
		
		if (!$success)
		{
			header('Content-Description: Error message');
			header('Content-Type: text/plain');
			header('Content-Disposition: inline');
			header('Content-Transfer-Encoding: base64');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			die(lang('config_mysqldump_failed'));	
		}
	}
	
	function optimize()
	{
		$this->load->dbutil();
		$this->dbutil->optimize_database();
		echo json_encode(array('success'=>true,'message'=>lang('config_database_optimize_successfully')));
	}
        function tservicios()
	{
//            $this->check_action_permission('add_update');
            $nomb="";  
            $param['nomb']    =  $this->input->get('nomb'); 
            $param['elim']    =  $this->input->get('eld'); 
            if($param['nomb']!='') {  
                $this->Appconfig->add_tipo_servicios($param);
            }
            if($param['elim']!='') {  
                $this->Appconfig->delet_tipo_servicios($param);
            }
            $dataServ['tservice'] = $this->Appconfig->get_tipo_servicios();
            $this->load->View('technical_supports/tservicios.php',$dataServ);
	}
        function tvservicios()
	{
//            $this->check_action_permission('add_update');
            $dataServ['tservice'] = $this->Appconfig->get_tipo_servicios();
            $this->load->View('technical_supports/tcomboservicios.php',$dataServ);
	}
        function tfallas()
	{
//            $this->check_action_permission('add_update');
            $nomb="";  
            $param['nomb']    =  $this->input->get('nomb');
            $param['elim']    =  $this->input->get('eld');
            if($param['nomb']!='') {  
                $this->Appconfig->add_tipo_fallas($param);
            }
            if($param['elim']!='') {  
                $this->Appconfig->delet_tipo_fallas($param);
            }
            $dataServ['tservice'] = $this->Appconfig->get_tipo_fallas();
            $this->load->View('technical_supports/tfallas.php',$dataServ);
	}
        function tvfallas()
	{
//            $this->check_action_permission('add_update'); 
            $dataServ['tfallas'] = $this->Appconfig->get_tipo_fallas();
            $this->load->View('technical_supports/tcombofallas.php',$dataServ);
	}
        function ubica_equipo()
	{
//            $this->check_action_permission('add_update');
            $nomb="";  
            $param['nomb']    =  $this->input->get('nomb');
            $param['elim']    =  $this->input->get('eld');
            if($param['nomb']!='') {  
                $this->Appconfig->add_ubica_equipos($param);
            }
            if($param['elim']!='') {  
                $this->Appconfig->delet_ubica_equipos($param);
            }
            $dataServ['tubi'] = $this->Appconfig->get_ubica_equipos();
            $this->load->View('technical_supports/conf_ubi_equipo',$dataServ);
	}
        function comb_ubica_equipo()
	{
//            $this->check_action_permission('add_update'); 
            $dataServ['ubicacione'] = $this->Appconfig->get_ubica_equipos();
            $this->load->View('technical_supports/conf_ubi_equipo_combo',$dataServ);
	}
}
?>