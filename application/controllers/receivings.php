<?php
require_once ("secure_area.php");
class Receivings extends Secure_area
{
	function __construct()
	{
		parent::__construct('receivings');
		$this->load->library('receiving_lib');
		$this->load->model('Register_movement');
	}

	function index()
	{
		$this->_reload(array(), false);
	}

	function item_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->get('term'),100));
		echo json_encode($suggestions);
	}

	function supplier_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_supplier()
	{
		$data = array();
		$supplier_id = $this->input->post("supplier");
		
		
		if ($this->Supplier->exists($supplier_id))
		{
			$this->receiving_lib->set_supplier($supplier_id);

		}
		else
		{
			$data['error']=lang('receivings_unable_to_add_supplier');
		}
		$this->_reload($data);
	}

	function location_search()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Location->get_locations_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function select_location()
	{
		$data = array();
		$location_id = $this->input->post("location");
		
		if ($this->Location->exists($location_id))
		{
			$this->receiving_lib->set_location($location_id);
		}
		else
		{
			$data['error']=lang('receivings_unable_to_add_location');
		}
		$this->_reload($data);
	}

	function delete_location()
	{
		$this->receiving_lib->delete_location();
		$this->_reload();
	}


	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->receiving_lib->set_mode($mode);
		
		if ($mode == 'store_account_payment')
		{
			$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
			$this->receiving_lib->empty_cart();
			
			$this->receiving_lib->add_item($store_account_payment_item_id,1);
		}

		$this->_reload();
	}
	
	function set_comment() 
	{
 	  $this->receiving_lib->set_comment($this->input->post('comment'));
	}

	function add()
	{
		$data=array();
		$mode = $this->receiving_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity = $mode=="receive" ? 1:1;

		if($this->receiving_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->receiving_lib->return_entire_receiving($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->receiving_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			if($this->Item_kit->get_info($item_id_or_number_or_item_kit_or_receipt)->deleted || $this->Item_kit->get_info($this->Item_kit->get_item_kit_id($item_id_or_number_or_item_kit_or_receipt))->deleted)
			{
				$data['error']=lang('sales_unable_to_add_item');			
			}
			else
			{
				$this->receiving_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt);
			}
		}
		elseif($this->Item->get_info($item_id_or_number_or_item_kit_or_receipt)->deleted || $this->Item->get_info($this->Item->get_item_id($item_id_or_number_or_item_kit_or_receipt))->deleted || !$this->receiving_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=lang('receivings_unable_to_add_item');
		}
		$this->_reload($data);
	}

	function edit_item($item_id)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'numeric');
		$this->form_validation->set_rules('cost_transport', 'lang:cost_transport', 'numeric');
		$this->form_validation->set_rules('unit_price','lang:unit_price', 'numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'numeric');
		$this->form_validation->set_rules('discount', 'lang:items_discount', 'integer');

    	$description = $this->input->post("description");
    	$serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$cost_transport = $this->input->post("cost_transport");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");
		$unit_price=$this->input->post("unit_price");
		$custom1_subcategory = $this->input->post("custom1_subcategory");
		$custom2_subcategory = $this->input->post("custom2_subcategory");
		$quantity_subcategory = $this->input->post("quantity_subcategory");
		$expiration_date = $this->input->post("expiration_date");

		$id = $this->input->post("id");
		$id_item=$this->receiving_lib->get_item_id($id);
		//var_dump($id);exit();
		if ($discount !== FALSE && $this->input->post("discount") == '')
		{
			$discount = 0;
		}

		if ($quantity !== FALSE && $this->input->post("quantity") == '')
		{
			$quantity = 0;
		}

		if ($cost_transport !== FALSE && $this->input->post("cost_transport") == '')
		{
			$cost_transport = 0;
		}

		if ($unit_price !== FALSE && $this->input->post("unit_price")!== '')
		{
			$data['unit_price']=$unit_price;
			$this->Item->save($data,$id_item);
		}

		if($this->receiving_lib->out_of_stock($id_item,$quantity) && $this->receiving_lib->get_mode()=='transfer')
		{    
            $this->delete_item($id);
            $data['error'] = lang('receivings_than_zero');
        }

		if ($this->form_validation->run() != FALSE)
		{
			$this->receiving_lib->edit_item($item_id,$description,$serialnumber,$quantity,$discount,$price, $cost_transport,$unit_price,$custom1_subcategory,$custom2_subcategory,$quantity_subcategory,$expiration_date);
		}
		else
		{
			$data['error']=lang('receivings_error_editing_item');
		}
		
		
		$this->validate($data);
		

		$this->_reload($data);
	}
	function validate(&$data){
		if($this->config->item("activate_pharmacy_mode"))
		{
			$items = $this->receiving_lib->get_cart();
			foreach ($items as $item) {
				if($item['has_subcategory'] == 1)
				{
					$expiration_date = $item["expiration_date"];

					if(empty($expiration_date))
						$data['error']="Fecha de vencimiento no válida- LOTE (".$item["custom2_subcategory"].")";
					else
					{				
						$expiration_date = strtotime($expiration_date);
						$now =	strtotime(date('Y-m-d H:i:s'));

						if($now >= $expiration_date)
							$data['error'] = "El lote que intenta registrar está vencido (".$item["custom2_subcategory"].")";
					}
				}
			}
		}
	}

	function delete_item($item_number)
	{
		$this->receiving_lib->delete_item($item_number);
		$this->_reload();
	}

	function delete_supplier()
	{
		$this->receiving_lib->delete_supplier();
		$this->receiving_lib->get_type_delete_supplier();
		$this->_reload();
	}

	function complete()
	{
		$data['cart']=$this->receiving_lib->get_cart();
		$error_message = "";
        
        //Convierte el campo cantidad a su valor absoluto
        foreach ($data['cart'] as $key=>$value)
        {
            $data['cart'][$key]['quantity'] = abs($data['cart'][$key]['quantity']);
        }
        
       	if (empty($data['cart']))
		{
			redirect('receivings');
		}
		$data['subtotal']=$this->receiving_lib->get_subtotal();
		$data['total_cost_transport']=$this->receiving_lib->get_total_cost_transport();
		$data['total']=$this->receiving_lib->get_total();
		$data['receipt_title']=lang('receivings_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$supplier_id=$this->receiving_lib->get_supplier();
		$location_id=$this->receiving_lib->get_location();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
		$data['payments']=$this->receiving_lib->get_payments();
		$data['payment_amount']=$this->receiving_lib->get_total();
		$data['mode']=$this->receiving_lib->get_mode();
		$data['balance']=$this->Receiving->total_balance($supplier_id);
		$data['comment'] = $this->receiving_lib->get_comment();
		/* $data['payments']=$this->receiving_lib->get_payments(); */
		$data['receiving_id'] = "RECV -1";
		
		
		if ($this->input->post('amount_tendered'))
		{
			$data['amount_tendered'] = $this->input->post('amount_tendered');
			$data['amount_change'] = to_currency($data['amount_tendered'] - round($data['total'], 2));
		}
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		
		if($supplier_id!=-1)
		{	
			$suppl_info=$this->Supplier->get_info($supplier_id);		
			$data['supplier']=$suppl_info->company_name;
			

			if ($suppl_info->first_name || $suppl_info->last_name)
			{
				$data['supplier'] .= ' ('.$suppl_info->first_name.' '.$suppl_info->last_name.')';

			}
			if($suppl_info->type=='Jurídico')
			{
				$this->receiving_lib->get_type_supplier('Jurídico');
				$data['taxes']=$this->receiving_lib->get_taxes();
				$data['type_supplier']=	$suppl_info->type;
			}	
			
		}
	
		if ($this->config->item('track_cash') == 1 && $payment_type == lang('receivings_cash')) { //Validar registro de movimientos

			$cash = $this->receiving_lib->get_total();			 
			$register_log_id = $this->Sale->get_current_register_log();
			
			if (!$register_log_id) {

				$error_message = "No existe una caja abierta";
				
			} else {

				$amount_register = $register_log_id->cash_sales_amount;

				if ($data['mode'] == "receive" && ($amount_register - $cash) < 0) {

					$error_message = "No hay suficiente efectivo para procesar esta operación";	
				}
			}
		}
			
		$data['store_account_payment'] = $this->receiving_lib->get_mode() == 'store_account_payment' ? 1 : 0;
		//SAVE receiving to database 
		$suppl_info=$this->Supplier->get_info($supplier_id);		
		$data['balance'] = $this->Receiving->total_balance($supplier_id);
		$total_venta=$this->receiving_lib->get_total();
		if($this->receiving_lib->get_mode() == 'receive'  && isset($data['balance']) && $supplier_id)
		{		
			$add_supplier=$this->Receiving->add_pay_cash_supplier($supplier_id, $data['balance'],$total_venta);
		}
		$this->receiving_lib->set_payments($data);
		if($data['store_account_payment']==1  && isset($data['balance']) && $supplier_id)
		{	
			$sold_by_employee_id=0;
			$comment='';
			$show_comment_on_receipt=0;
			$data['payments']=$this->receiving_lib->get_payments();
	
			$suspended_change_receiving_id=0;
			$ref_no='';
			$auth_code='';
			$change_receiving_date=0;
			$amount_change=0;
            $receiving_id_raw = $this->Receiving->save_pay_cash($data['cart'], $supplier_id, $employee_id, $sold_by_employee_id, $comment,$show_comment_on_receipt,$data['payments'], $suspended_change_receiving_id, 0,$ref_no,$auth_code, $change_receiving_date, $data['balance'], $data['store_account_payment'],$data['total'],$amount_change,-1);
			if($receiving_id_raw<0){
				$this->load->view("receivings/error_pagos",array("error_message"=>$error_message,"compra"=>1));
				return;
			} else {
				$data['receiving_id'] ='RECV '.$receiving_id_raw;
			}
		}
		
		if(!$this->receiving_lib->is_select_subcategory_items()){
			$error_message = "Debe ingresar los datos de la(s) subcategoría(s).";	

		}
		/* var_dump($data['receiving_id']); */
		if (empty($error_message) and $data['receiving_id']=="RECV -1") {
			//SAVE receiving to database
			$data['payments']=$this->receiving_lib->get_payments();
			$data['receiving_id'] ='RECV '.$this->Receiving->save($data['cart'], $supplier_id,$employee_id,$comment,$payment_type,$this->receiving_lib->get_suspended_receiving_id(),0,$data['mode'],$location_id,$data['payments']);
		} 
		
		$current_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		$current_location = $this->Location->get_info($current_location_id);
		$data['transfer_from_location'] = $current_location->name;
		
		if ($location_id > 0) {

			$transfer_to_location = $this->Location->get_info($location_id);
			$data['transfer_to_location'] = $transfer_to_location->name;
		}

		if (!empty($error_message) || $data['receiving_id'] == 'RECV -1') {

			$data['error_message'] = lang('receivings_transaction_failed')." <br> ".$error_message;
		}
		
		if($supplier_id != -1)
		{
			$cust_info=$this->Customer->get_info($supplier_id);
			$balance_total=$this->Receiving->total_balance($supplier_id);
			if ($balance_total !=0)
			{
				$data['supplier_balance_for_receiving'] = $balance_total;
			}
		}

		$this->load->view("receivings/receipt",$data);
		$this->receiving_lib->clear_all();
	}
	
	function suspend()
	{
		$data['cart']=$this->receiving_lib->get_cart();	
		$data['subtotal']=$this->receiving_lib->get_subtotal();
		$data['total']=$this->receiving_lib->get_total();
		$data['receipt_title']=lang('receivings_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$supplier_id=$this->receiving_lib->get_supplier();
		$location_id=$this->receiving_lib->get_location();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->receiving_lib->get_comment();
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = '';
		$data['payment_type']='';
		$data['payments']=$this->receiving_lib->get_payments();
		$data['mode']=$this->receiving_lib->get_mode();
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{	
			$suppl_info=$this->Supplier->get_info($supplier_id);		
			$data['supplier']=$suppl_info->company_name;
			if ($suppl_info->first_name || $suppl_info->last_name)
			{
				$data['supplier'] .= ' ('.$suppl_info->first_name.' '.$suppl_info->last_name.')';
			}
		}

		//SAVE receiving to database
		/* $payments=$this->receiving_lib->get_payments(); */
		$receiving_id = $this->Receiving->save($data['cart'], $supplier_id,$employee_id,$comment,$payment_type,$this->receiving_lib->get_suspended_receiving_id(), 1, $data['mode'],$location_id,$data['payments']);
		$data['receiving_id']='RECV '.$receiving_id;
		if ($data['receiving_id'] == 'RECV -1')
		{
			$data['error_message'] = lang('receivings_transaction_failed');
		}
		$this->receiving_lib->clear_all();
		
		if ($this->config->item('show_receipt_after_suspending_sale'))
		{
			redirect('receivings/receipt/'.$receiving_id);
		}
		else
		{
			$this->_reload(array('success' => lang('receivings_successfully_suspended_receiving')));
		}
		
	}
	
	function suspended()
	{
		$data = array();
		$data['suspended_receivings'] = $this->Receiving->get_all_suspended();
		$this->load->view('receivings/suspended', $data);
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
						$price = null;;
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
					
					if($this->receiving_lib->is_valid_item_kit($item_id))
					{
						if(!$this->receiving_lib->add_item_kit($item_id))
						{
							$this->receiving_lib->empty_cart();
							echo json_encode( array('success'=>false,'message'=>lang('batch_sales_error')));
							return;
						}
					}
					else{
						$custom1_subcategory=null;
						$custom2_subcategory=null;
						$quantity_subcategory=null;
						if ($this->config->item('subcategory_of_items') == 1 and $this->do_has_subcategory($sheet,$k,$item_id)){
							$increment = $this->config->item("activate_pharmacy_mode") ? 4: 3; // cantidad de datos de sucategría 
							$cantidad =(int) $this->config->item('quantity_subcategory_of_items') * $increment;
						   
                     	  	for($ij=0; $ij< $cantidad  ;$ij= $ij + $increment)
                       		{
								if($this->config->item('inhabilitar_subcategory1')==1){
									$custom1_subcategory ="»";
								}else{
									$custom1_subcategory = $sheet->getCellByColumnAndRow((4+$ij), $k)->getValue();
								}
								$custom2_subcategory = $sheet->getCellByColumnAndRow((4+$ij+1), $k)->getValue();
								$quantity_subcategory = $sheet->getCellByColumnAndRow((4+$ij+2 ), $k)->getValue();
								$date_subcategory = $sheet->getCellByColumnAndRow((4+$ij+3 ), $k)->getValue();
								
								if(!empty( $date_subcategory) and $this->config->item("activate_pharmacy_mode"))
								{
									$timestamp  = PHPExcel_Shared_Date::ExcelToPHP($date_subcategory + 1);
									$date_subcategory = date("Y-m-d", $timestamp);
								}
								else  
									$date_subcategory = null;

								if($custom1_subcategory!="" &&  $custom2_subcategory!="" && $quantity_subcategory!="" ) {
									if(!$this->config->item("activate_pharmacy_mode") and !$this->items_subcategory->exists($item_id, false , $custom1_subcategory,$custom2_subcategory)){
										$this->receiving_lib->empty_cart();
										echo json_encode( array('success'=>false,'message'=>lang('batch_receivings_error')));
										return;
									}
									elseif(!$this->receiving_lib->add_item($item_id,$quantity,$discount,$price,null,null,0,0,$custom1_subcategory,$custom2_subcategory,$quantity_subcategory,$date_subcategory))
									{	
										$this->receiving_lib->empty_cart();
										echo json_encode( array('success'=>false,'message'=>lang('batch_receivings_error')));
										return;
									}
									
								}
							}
						   
						}else{
							if(!$this->receiving_lib->add_item($item_id,$quantity,$discount,$price))
							{	
								$this->receiving_lib->empty_cart();
								echo json_encode( array('success'=>false,'message'=>lang('batch_receivings_error')));
								return;
							}	
						}		
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
		echo json_encode(array('success'=>true,'message'=>lang('receivings_import_successfull')));
		
	}
	function do_has_subcategory($sheet,$k,$item_id){
		$item_info= $this->Item->get_info($item_id);
		if($item_info->subcategory==0){
			return false;
		}
		$cantidad =(int)$this->config->item('quantity_subcategory_of_items')*3;

		for($ij=0;$ij<$cantidad  ;$ij=$ij+3)	{			
			$custom1_subcategory = $sheet->getCellByColumnAndRow((4+$ij) , $k)->getValue();
			$custom2_subcategory = $sheet->getCellByColumnAndRow((4+$ij+1), $k)->getValue();
			$cantidad_subcategory = $sheet->getCellByColumnAndRow((4+$ij+2 ), $k)->getValue();
			if($custom1_subcategory!="" ||  $custom2_subcategory!="" || $cantidad_subcategory!="" ) {
				return true;						
			}
		}
		return false;
	}
	
	function _excel_get_header_row()
	{
		$header=array(lang('item_id'),lang('cost_price'),lang('quantity'),lang('discount_percent'));
		if ($this->config->item('subcategory_of_items')==1){
            for($i=1;$i<=(int)$this->config->item('quantity_subcategory_of_items');$i++){
				$header[] = lang('items_subcategory').$i."_". ($this->config->item('custom_subcategory1_name') ?  $this->config->item('custom_subcategory1_name'):'Personalizado1');
                $header[] = lang('items_subcategory').$i."_". ($this->config->item('custom_subcategory2_name') ?  $this->config->item('custom_subcategory2_name'):'Personalizado2');
				$header[] = lang('items_subcategory').$i."_". lang('items_quantity');
				if($this->config->item('activate_pharmacy_mode')){
				$header[] = lang('items_expiration_date');
				}
            }
           
        }
		return $header;
	}
	
	function batch_receiving()
	{
		
		$this->load->view('receivings/batch');
	}
	
	function excel()
	{	
		$this->load->helper('report');
		$header_row = $this->_excel_get_header_row();
		
		$content = array_to_spreadsheet(array($header_row));
		force_download('batch_receiving_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
	}
	
	function unsuspend()
	{
		$receiving_id = $this->input->post('suspended_receiving_id');
		$this->receiving_lib->clear_all();
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$this->receiving_lib->set_suspended_receiving_id($receiving_id);		
    	$this->_reload(array(), false);
	}
	function _is_tax_inclusive()
	{
		$is_tax_inclusive = FALSE;
		foreach($this->receiving_lib->get_cart() as $item)
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
	function delete_suspended_receiving()
	{
		$suspended_recv_id = $this->input->post('suspended_receiving_id');
		if ($suspended_recv_id)
		{
			$this->receiving_lib->delete_suspended_receiving_id();
			$this->Receiving->delete($suspended_recv_id, false, false);
		}
    	redirect('receivings/suspended');
	}

	function receipt($receiving_id)
	{
		//Before changing the recv session data, we need to save our current state in case they were in the middle of a recv
		$this->receiving_lib->save_current_recv_state();
		
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();		
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$data['cart']=$this->receiving_lib->get_cart();
		$data['total']=$this->receiving_lib->get_total();
		$data['taxes']=$this->receiving_lib->get_taxes($receiving_id);
		$data['receipt_title']=lang('receivings_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($receiving_info['receiving_time']));
		$supplier_id=$this->receiving_lib->get_supplier();
		$emp_info=$this->Employee->get_info($receiving_info['employee_id']);
		$data['payment_type']=$receiving_info['payment_type'];
		$data['payments']=$this->receiving_lib->get_payments();

		$data['subtotal']=$this->receiving_lib->get_subtotal($receiving_id);
		$data['total_cost_transport']=$this->receiving_lib->get_total_cost_transport($receiving_id);

		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{
			$supplier_info=$this->Supplier->get_info($supplier_id);
						
			$data['supplier']=$supplier_info->company_name;
			if ($supplier_info->first_name || $supplier_info->last_name)
			{
				$data['supplier'] .= ' ('.$supplier_info->first_name.' '.$supplier_info->last_name.')';
			}
			if($supplier_info->type=='Jurídico')
			{
				$this->receiving_lib->get_type_supplier('Jurídico');
				$data['taxes']=$this->receiving_lib->get_taxes();
				$data['type_supplier']=	$supplier_info->type;
			}
			$data['supplier_id']=$supplier_id;
		}
		$data['receiving_id']='RECV '.$receiving_id;
		
		$current_location_id = $this->Employee->get_logged_in_employee_current_location_id();
		$current_location = $this->Location->get_info($receiving_info['location_id']);
		$data['transfer_from_location'] = $current_location->name;
		
		if ($receiving_info['transfer_to_location_id'] > 0)
		{
			$transfer_to_location = $this->Location->get_info($receiving_info['transfer_to_location_id']);
			$data['transfer_to_location'] = $transfer_to_location->name;
		}
		
		$this->load->view("receivings/receipt",$data);
		$this->receiving_lib->clear_all();
		
		//Restore previous state saved above
		$this->receiving_lib->restore_current_recv_state();
		
	}
	
	function edit($receiving_id)
	{
		$data = array();

		$data['suppliers'] = array('' => 'No Supplier');
		foreach ($this->Supplier->get_all()->result() as $supplier)
		{
			$data['suppliers'][$supplier->person_id] = $supplier->company_name.' ('.$supplier->first_name . ' '. $supplier->last_name.')';
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['receiving_info'] = $this->Receiving->get_info($receiving_id)->row_array();
		$this->load->view('receivings/edit', $data);
	}
	
	function delete($receiving_id)
	{
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$resul=$this->Receiving->get_es_devolucion($receiving_info['receiving_id'],0);
		$data = array();
		if ($this->Receiving->delete($receiving_id, false, $receiving_info['suspended'] == 0))
		{
			
			if(intval($resul['items_purchased'])>0){
				$this->Register_movement->save($receiving_info['mount'], "Compra retornada",false,true,"Compra retornada",false);
			}else if($resul['items_purchased']!=null){
				$this->Register_movement->save($receiving_info['mount'] * (-1), "Compra realizada",false,true,"Compra realizada",false);
			}
			
		
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('receivings/delete', $data);
		
	}
	
	function delete_receivings()
	{
		$data = array();
		if(!$this->Employee->has_module_action_permission('receivings', 'delete_receiving', $this->Employee->get_logged_in_employee_info()->person_id)){
			$data['error_access']=true;
			$this->load->view('receivings/delete', $data);
		}		
		else{
		$data['error_access'] = false;
		$receiving_id=$this->input->post("receiving_id");
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		
		if ($this->Receiving->delete($receiving_id, false, $receiving_info['suspended'] == 0))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		$this->load->view('receivings/delete', $data);
		}
		
		
		
	}
	
	function undelete($receiving_id)
	{
		$data = array();
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$resul=$this->Receiving->get_es_devolucion($receiving_info['receiving_id'],1);
		if ($this->Receiving->undelete($receiving_id))
		{
			if(intval($resul['items_purchased'])>0){
				$this->Register_movement->save($receiving_info['mount'] * (-1), "Compra realizada",false,true,"Compra realizada",false);
			}else if($resul['items_purchased']!=null){
				$this->Register_movement->save($receiving_info['mount'], "Compra retornada",false,true,"Compra retornada",false);
			}
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('receivings/undelete', $data);
		
	}
	
	function save($receiving_id)
	{
		$receiving_data = array(
			'receiving_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'supplier_id' => $this->input->post('supplier_id') ? $this->input->post('supplier_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment')
		);
		
		if ($this->Receiving->update($receiving_data, $receiving_id))
		{
			echo json_encode(array('success'=>true,'message'=>lang('receivings_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('receivings_unsuccessfully_updated')));
		}
	}

	function _reload($data=array(), $is_ajax = true)
	{		
		//Configuration values for showing/hiding fields in the receivings grid
		$data['show_receivings_cost_without_tax']=$this->config->item('show_receivings_cost_without_tax');
		$data['show_receivings_cost_iva']=$this->config->item('show_receivings_cost_iva');
		$data['show_receivings_discount']=$this->config->item('show_receivings_discount');
		$data['show_receivings_price_sales']=$this->config->item('show_receivings_price_sales');

		$data["subcategory_of_items"]=$this->config->item("subcategory_of_items");

		$data['show_receivings_inventory']=$this->config->item('show_receivings_inventory');
		$data['show_receivings_cost_transport']=$this->config->item('show_receivings_cost_transport');
		$data['show_receivings_description']=$this->config->item('show_receivings_description');
		$data['show_receivings_num_item']=$this->config->item('show_receivings_num_item');

		$data['is_tax_inclusive'] = $this->_is_tax_inclusive();

		if ($data['is_tax_inclusive'] && count($this->receiving_lib->get_deleted_taxes()) > 0)
		{
			$this->receiving_lib->clear_deleted_taxes();
		}
		
		$person_info = $this->Employee->get_logged_in_employee_info();
		$data['subtotal']=$this->receiving_lib->get_subtotal();
		$data['total_cost_transport']=$this->receiving_lib->get_total_cost_transport();
		$data['cart']=$this->receiving_lib->get_cart();
		$data['modes']=array();
		$data['comment'] = $this->receiving_lib->get_comment();

		// acciones modulo de compra, recibir, regresar, transferir
		if($this->Employee->has_module_action_permission('receivings', 'receivings_receiving', $person_info->person_id)){
			$data['modes']['receive']= lang('receivings_receiving');
		}
		if($this->Employee->has_module_action_permission('receivings', 'receivings_return', $person_info->person_id)){
			$data['modes']['return']= lang('receivings_return');
		}
		$data['modes']['store_account_payment']= lang('receivings_store_account_payment');
		
		if ($this->Location->count_all() > 1)
		{
			if($this->Employee->has_module_action_permission('receivings', 'receivings_transfer', $person_info->person_id)){
				$data['modes']['transfer']= lang('receivings_transfer');
			}
		}
		$data['mode']=$this->receiving_lib->get_mode();
			
		$data['items_in_cart'] = $this->receiving_lib->get_items_in_cart();
		$data['items_module_allowed'] = $this->Employee->has_module_permission('items', $person_info->person_id);
		$data['payment_options']=array(
			lang('receivings_cash') => lang('receivings_cash'),
			lang('receivings_check') => lang('receivings_check'),
			lang('receivings_debit') => lang('receivings_debit'),
			lang('receivings_credit') => lang('receivings_credit'),
		);
		
		if ($this->receiving_lib->get_mode() != 'store_account_payment')
			{
				$data['payment_options']=array(
				lang('receivings_cash') => lang('receivings_cash'),
				lang('receivings_check') => lang('receivings_check'),
				lang('receivings_debit') => lang('receivings_debit'),
				lang('receivings_credit') => lang('receivings_credit'),
				lang('receivings_supplier_credit') => lang('receivings_supplier_credit'));
				}
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}
		
		$supplier_id=$this->receiving_lib->get_supplier();
		$this->receiving_lib->get_type_delete_supplier();

		if($supplier_id!=-1)
		{
			$info=$this->Supplier->get_info($supplier_id);
			$data['supplier']=$info->company_name;
			/* $data['supplier_balance']=$info->balance;	 */
			$data['supplier_balance']=$this->Receiving->total_balance($supplier_id);
			if ($info->first_name || $info->last_name)
			{
				$data['supplier'] .= ' ('.$info->first_name.' '.$info->last_name.')';
			}
			if($info->type=='Jurídico')
			{
				$this->receiving_lib->get_type_supplier('Jurídico');
				$data['taxes']=$this->receiving_lib->get_taxes();
				$data['type_supplier']=	$info->type;
			}
			$data['supplier_id']=$supplier_id;
			$data['recent_receivings'] = $this->Receiving->get_recent_receivings_for_supplier($supplier_id);
		}
		$data['total']=$this->receiving_lib->get_total();	
		$location_id=$this->receiving_lib->get_location();
		if($location_id!=-1)
		{
			$info=$this->Location->get_info($location_id);
			$data['location']=$info->name;
			$data['location_id']=$location_id;
		}
		 
		if ($is_ajax)
		{
			$this->load->view("receivings/receiving",$data);
		}
		else
		{
			$this->load->view("receivings/receiving_initial",$data);
		}
	}
	function delete_tax($name)
	{
		//$this->check_action_permission('delete_taxes');
		$name = rawurldecode($name);
		$this->receiving_lib->add_deleted_tax($name);
		$this->_reload();
	}

    function cancel_receiving()
    {
    	$this->receiving_lib->clear_all();
    	$this->_reload();
    }
	
	function receiving_details_modal($id_supplier=0){
		
		$data["pay_cash"]=$this->Receiving->get_pay_cash($id_supplier,false,100);
		$data["supplier"] = $this->Supplier->get_info($id_supplier);
		$data["balance_total"]=$this->Receiving->total_balance($id_supplier);
		$this->load->view("receivings/receiving_details_pay_modal",$data);
	}
	
	function receiving_purchases_modal($id_supplier=0){
		
		$data["receivings"]=$this->Receiving->get_receiving($id_supplier,false,100);
		$data["supplier"] = $this->Supplier->get_info($id_supplier);
		$data["balance_total"]=$this->Receiving->total_balance($id_supplier);
		$this->load->view("receivings/receiving_purchases_pay_modal",$data);
	}

	function get_monton_pendiente($id_persona=0){
		if(! is_numeric($id_persona) ||$id_persona==0 ){
			echo 0;

		}else{
			$supplier = $this->Supplier->get_info($id_persona);
			echo to_currency_no_money($supplier->balance);
		}
	}
	
	function delete_pay_cash()
	{
		if(!$this->Employee->has_module_action_permission('receivings', 'delete_payments_to_credit', $this->Employee->get_logged_in_employee_info()->person_id)){
			$data['error']="No tiene permiso para eliminar el registro";
		}		
		else{
			$pay_cash_id=$this->input->post("pay_cash_id");
			$data=array();
			$result=$this->Receiving->delete_pay_cash($pay_cash_id);
			if(!$result){
				$data['error']="No pude eliminar el registro";
			}
		}
		$this->_reload($data);

	}
	


}
?>