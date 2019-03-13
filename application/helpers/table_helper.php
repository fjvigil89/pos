<?php
/*
Gets the html table to manage people.
*/

function get_model_tabla_detailed_sales($summary_data=array(),$headers=array(),$details_data=array()){
		
	
	
		// se muestran los datos
		$html = '';
		$html .= "<style type='text/css'>";
		$html .="table, td ,th{ border: 0.1px solid black; padding:5;}";      
		$html .= "</style>";
		$html .= '<table border="0" cellpadding="5" cellspacing="0" width="100%";>';
		$html .='<thead> <tr  align="center">';
		foreach ($headers['summary'] as $header)
		{           
			$html .= '<th > <strong>'.$header['data'].'</strong></th>';
		}

		$html .= "</tr></thead></table>";

		
	// se crear el cuerpo de la tabla
	foreach ($summary_data as $key=>$row) 
	{
		$html .= '<table border="0" cellpadding="5" cellspacing="0" width="100%";>';
		$html .= '<tr align="right">';		
		
		foreach ($row as $cell) 
		{ 
			$html .= '<th><strong>'.$cell['data'].'</strong></th>';
		} 
		$html .= "</tr></table>";
		$html .= '<table border="0" cellpadding="5" cellspacing="0" width="100%";>';
		$html .='<tr><td align="center">'.lang('reports_data_product').'</td>';
		$html .= "</tr></table>";

		$html .= '<table border="0" cellpadding="5" cellspacing="0" width="100%";>';
		$html .= '<tr>';
				
					foreach ($headers['details'] as $header)
					{           
						$html .= '<td  >'.$header['data'].'</td>';
					}
		$html .= "</tr></table>";
		
		$html .= '<table border="0" cellpadding="5" cellspacing="0" width="100%";>';
		foreach ($details_data[$key] as $row2) {
			
			$html .= '<tr style=" background-color:#d6d6d6;" >';
				foreach ($row2 as $cell)
				{
					$html .='<td  >'. $cell['data']. '</td>';
				}
			$html .= "</tr></table>";
		}
		$html .="<br><br>";						
		}           

	return $html;
}

function get_people_manage_table($people,$controller)
{
	$CI =& get_instance();
	$table='<table class="table table-bordered table-hover" cellspacing="0" width="100%" id="sortable_table">';	
	$controller_name=strtolower(get_class($CI));

	if ($controller_name=='customers' && $CI->config->item('system_point') && $CI->config->item('customers_store_accounts')) 			
	{	
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('customers_balance'),
		lang('common_credit'),
		lang('common_actions'),
		lang('common_redeempoints'),
		lang('customers_image'));
	}	
	else if ($controller_name=='customers' && $CI->config->item('system_point'))
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('common_actions'),
		lang('common_redeempoints'),
		lang('customers_image'));		
	}
	else if($controller_name=='customers' &&	$CI->config->item('customers_store_accounts'))
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('customers_balance'),		
		lang('common_credit'),
		lang('common_actions'),
		lang('customers_image'));	
	}
	else
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('common_actions'),
		lang('customers_image'));
	}

	if( $controller_name=='campaigns')
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		lang('common_person_id'),
		lang('common_last_name'),
		lang('common_first_name'),
		lang('common_email'),
		lang('common_phone_number'),
		lang('customers_image'));
	}
	

	$table.='<thead><tr>';
	
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_people_manage_table_data_rows($people,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the people.
*/
function get_people_manage_table_data_rows($people,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($people->result() as $person)
	{
		$table_data_rows.=get_person_data_row($person,$controller);
	}
	
	if($people->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_person_data_row($person,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$avatar_url=$person->image_id ?  site_url('app_files/view/'.$person->image_id) : false;
	$start_of_time =  date('Y-m-d', 0);
	$today = date('Y-m-d').' 23:59:59';	
	$link = site_url('reports/specific_'.($controller_name == 'customers' ? 'customer' : 'employee').'/'.$start_of_time.'/'.$today.'/'.$person->person_id.'/all/0');
	$table_data_row='<tr>';	
	
	$table_data_row.="<td style='width: 1%'><input type='checkbox' class='css-checkbox' id='person_$person->person_id' value='".$person->person_id."'/><label for='".$person->person_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td>'.$person->person_id.'</td>';
	$table_data_row.='<td ><a href="'.$link.'" class="underline">'.H($person->last_name).'</a></td>';
	$table_data_row.='<td><a href="'.$link.'" class="underline">'.H($person->first_name).'</a></td>';
	$table_data_row.='<td>'.mailto(H($person->email),H($person->email), array('class' => 'underline')).'</td>';
	$table_data_row.='<td>'.H($person->phone_number).'</td>';	
	if($controller_name=='customers' && $CI->config->item('customers_store_accounts'))
	{	
		$table_data_row.='<td width="15%">'.to_currency($person->balance).'</td>';		
		$table_data_row.='<td width="5%">'.anchor($controller_name."/pay_now/$person->person_id", "<i class='fa fa-money'></i>".lang('customers_pay'),array('class'=>'btn btn-xs default btn-editable', 'title'=>lang('customers_pay'))).'</td>';
	}

	if($controller_name=='customers' || $controller_name=='employees')
	{
		$table_data_row.='<td>'.anchor($controller_name."/view/$person->person_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'),array('class'=>'btn btn-xs btn-block default btn-editable update-person', 'title'=>lang($controller_name.'_update'))).'</td>';
	}

	if($CI->config->item('system_point') && $controller_name=='customers') 
    {
		$table_data_row.='<td>'.anchor($controller_name."/redeempoints/$person->person_id/2", "<i class='fa fa-exchange'></i>".lang('common_redeempoints'),array('class'=>'btn btn-xs btn-block default update-person','title'=>lang($controller_name.'_redeempoints'))).'</td>';
    }	

	if ($avatar_url)
	{		
		$table_data_row.="<td align='center'>
							<a href='$avatar_url' class='fancybox'>
								<img id='avatar' src='$avatar_url' width='40' height='30' class='img-polaroid'/>
							</a>
						</td>";
	}
	else
	{
		$table_data_row.="<td align='center'>
							<a href='javascript:;' class=''>
								<img id='avatar' src='".base_url('img/no-image.png')."' width='40' height='30' class='img-polaroid'/>
							</a>
						</td>";
	}
	$table_data_row.='</tr>';
	
	return $table_data_row;
}

/*
Gets the html table to manage suppliers.
*/
function get_supplier_manage_table($suppliers,$controller)
{
	$CI =& get_instance();
	$table='<table class="table table-bordered table-striped table-hover" id="sortable_table">';	
	$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
	lang('suppliers_company_name'),
	lang('common_last_name'),
	lang('common_first_name'),
	lang('common_email'),
	lang('common_phone_number'),
	lang('suppliers_balance'),		
	lang('common_credit'),
	lang('common_actions'),
	lang('customers_image'));

	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	
	$table.='</tr></thead><tbody>';
	$table.=get_supplier_manage_table_data_rows($suppliers,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the supplier.
*/
function get_supplier_manage_table_data_rows($suppliers,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($suppliers->result() as $supplier)
	{
		$table_data_rows.=get_supplier_data_row($supplier,$controller);
	}
	
	if($suppliers->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='8'><span class='col-md-12 text-center text-warning' >".lang('common_no_persons_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_supplier_data_row($supplier,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$avatar_url=$supplier->image_id ?  site_url('app_files/view/'.$supplier->image_id) : (base_url().'img/no-image.png');
	$balance_total=$CI->Receiving->total_balance($supplier->person_id);
	$table_data_row='<tr>';
	$table_data_row.="<td align='center'><input type='checkbox' class='css-checkbox' id='person_$supplier->person_id' value='".$supplier->person_id."'/><label for='".$supplier->person_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td >'.H($supplier->company_name).'</td>';
	$table_data_row.='<td >'.H($supplier->last_name).'</td>';
	$table_data_row.='<td >'.H($supplier->first_name).'</td>';
	$table_data_row.='<td >'.mailto(H($supplier->email),H($supplier->email)).'</td>';
	$table_data_row.='<td >'.H($supplier->phone_number).'</td>';	
	$table_data_row.='<td width="15%" class="text-right">'.to_currency($balance_total).'</td>';		
	$table_data_row.='<td width="5%">'.anchor($controller_name."/pay_now/$supplier->person_id", "<i class='fa fa-money'></i>".lang('suppliers_pay'),array('class'=>'btn btn-xs default btn-editable', 'title'=>lang('suppliers_pay'))).'</td>';	
	$table_data_row.='<td class="rightmost">'.anchor($controller_name."/view/$supplier->person_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'), array('class'=>'btn btn-xs btn-block default btn-editable update-supplier', 'title'=>lang($controller_name.'_update'))).'</td>';			
	if ($avatar_url)
	{
		$table_data_row.="<td width='55px' align='center'>
							<a href='$avatar_url' class='fancybox'>
								<img id='avatar' src='".$avatar_url."' class='img-polaroid' width='40' height='30' />
							</a>
						</td>";
	}
	$table_data_row.='</tr>';
	return $table_data_row;
}
function get_warehouse_home_manage_table($data,$controller)
{
	$CI =& get_instance();
	$table='<table  class="table table-bordered " cellspacing="0" width="100%" id="sortable_table">';	

	$headers=array(
		"",
		//lang("warehouse_ID"),
		lang("warehouse_numero"),
		lang("warehouse_fecha"),
		lang("warehouse_cantidad_item"),
		lang("warehouse_estado"),
		""		

	);
		
	$table.='<thead><tr>';

	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}	
	$table.='</tr></thead><tbody>';
	$table.=get_warehouse_home_manage_table_data_rows($data,$controller);
	$table.='</tbody></table>';
	return $table;
}
function get_warehouse_home_manage_table_data_rows($data,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($data->result() as $item)
	{
		$table_data_rows.=get_warehouse_home_data_row($item,$controller);
	}
	return $table_data_rows;
}
function get_warehouse_home_data_row($item,$controller)
{
	$CI =& get_instance();	
	$controller_name=strtolower(get_class($CI));
	$class_='class="pendiente"';		
	 if($item->state==lang("warehouse_rechazado")){
		$class_='class="rechazada"';
	}
	else if($item->state==lang("warehouse_entregado")){
		$class_='class="entregado"';
	}
	
	$table_data_row='<tr '. $class_.'>';
	$table_data_row.='<td width="2%"  align="center">'.anchor('warehouse/receipt/' .$item->order_sale_id, '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')).'</td>';
	//$table_data_row.='<td width="5%"  align="center">'.$item->order_sale_id.'</td>';
	$table_data_row.='<td width="5%" align="center">'.$item->number.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$item->date.'</td>';
	$table_data_row.='<td width="5%" align="center">'.to_quantity($item->items).'</td>';
	$table_data_row.='<td width="10%" align="center"><strong>'.$item->state.'</strong></td>';
	$table_data_row.='<td width="3%" class="rightmost">'.anchor($controller_name."/view_modal/"
	.$item->order_sale_id, "<i class='fa fa-edit'></i>".lang("warehouse_detalles"),array("data-toggle"=>"modal", "data-target"=>"#myModal",'class'=>'btn btn-xs btn-block default btn-clon','title'=>lang("warehouse_detalles"))).'</td>';			
	$table_data_row.='</tr>';
	return $table_data_row;
}
function get_chanche_home_manage_table($data,$controller)
{
	$CI =& get_instance();
	$table='<table  class="table table-bordered " cellspacing="0" width="100%" id="sortable_table">';	

	$headers=array(
		"Orden",
		"Nombre del Titular",
		"Tipo de Documento",
		"Número de Documento",
		"Cuenta bancararia",
		"Banco",
		"Divisa",
		"Total",
		"Total transfererido",
		""
	);
		
	$table.='<thead><tr>';

	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}

	
	$table.='</tr></thead><tbody>';
	$table.=get_chanche_home_manage_table_data_rows($data,$controller);
	$table.='</tbody></table>';
	return $table;
}
function get_chanche_home_manage_table_data_rows($data,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	if($CI->config->item('activar_casa_cambio')==true){
		foreach($data->result() as $item)
		{
			$table_data_rows.=get_change_home_data_row($item,$controller);
		}
	}
	
	if($data->num_rows()==0 || $CI->config->item('activar_casa_cambio')==0)
	{
		$table_data_rows.="<tr><td colspan='16'><span class='col-md-12 text-center text-warning' >".lang('technical_supports_falla_no_support').($CI->config->item('activar_casa_cambio')==0? ". Debe activar la casa de cambio":"")."</span></tr>";
	}
	
	return $table_data_rows;
}
function get_change_home_data_row($item,$controller)
{
	$CI =& get_instance();	
	$controller_name=strtolower(get_class($CI));

	$class_='class="pendiente"';
	if($item->transaction_status=="Procesando"){
		$class_='class="procesando"';
	}	
	else if($item->transaction_status=="Rechazada"){
		$class_='class="rechazada"';
	}
	else if($item->transaction_status=="Entregado"){
		$class_='class="entregado"';
	}
	else if($item->transaction_status=="Aprobada"){
		$class_='class="aprobada"';
	}
	$table_data_row='<tr '. $class_.'>';
	$table_data_row.='<td width="8%"  align="center">'.$item->invoice_number.'</td>';
	$table_data_row.='<td width="20%" align="center">'.$item->titular_cuenta.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$item->tipo_documento.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$item->numero_documento.'</td>';
	$table_data_row.='<td width="20%" align="center">'.$item->numero_cuenta.'</td>';
	$table_data_row.='<td width="20%" align="center">'.$item->name.'</td>';
	$table_data_row.='<td width="5%" align="center">'.lang("sales_".$item->divisa).'</td>';
	$total=0;
	$tasa=($item->tasa==0|| $item->tasa==null)?1 :$item->tasa;
	$total= ($item->quantity_purchased*$item->item_unit_price);	
	if($item->opcion_sale=="venta"){
		$total=$total/$tasa;
	}else{
		$total=$total*$tasa;	
	}
	
		
		
	
	
	$table_data_row.='<td width="10%" align="center">'.to_currency_no_money($total).'</td>';
	$table_data_row.='<td width="10%" align="center">'.to_currency($item->quantity_purchased*$item->item_unit_price).'</td>';

	$table_data_row.='<td width="3%" class="rightmost">'.anchor($controller_name."/view_modal/"
	.$item->sale_id."/".$item->item_id."/".$item->line, "<i class='fa fa-edit'></i>Detalles",array("data-toggle"=>"modal", "data-target"=>"#myModal",'class'=>'btn btn-xs btn-block default btn-clon','title'=>"Detalles")).'</td>';			




	


	
	
	
	$table_data_row.='</tr>';
	return $table_data_row;
}
function get_support_manage_table($supports,$controller)
{
	$CI =& get_instance();
	$table='<table id="table-suppor" class="table table-bordered table-hover no_margin_bottom" cellspacing="0" width="100%" id="sortable_table">';	

	$headers=array(
		lang("technical_supports_order_n"),
		lang("technical_supports_customer"),
		lang("technical_supports_model"),
		lang("technical_supports_color"),
		$CI->config->item("custom1_support_name")!=""? $CI->config->item("custom1_support_name"):"Personalizado 1",
		$CI->config->item("custom2_support_name")!=""? $CI->config->item("custom2_support_name"):"Personalizado 2",
		lang("technical_supports_type"),
		lang("technical_supports_estado"),
		lang("technical_supports_edit"),
		lang("technical_supports_option")
		

	);
		
	$table.='<thead><tr>';

	foreach($headers as $header)
	{		
		if ($header != '') 
		{				
			$table.="<th>$header</th>";				
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_support_manage_table_data_rows($supports,$controller);
	$table.='</tbody></table>';
	return $table;
}
function get_support_manage_table_data_rows($supports,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($supports->result() as $support)
	{
		$table_data_rows.=get_suppoet_data_row($support,$controller);
	}
	
	if($supports->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='16'><span class='col-md-12 text-center text-warning' >".lang('technical_supports_falla_no_support')."</span></tr>";
	}
	
	return $table_data_rows;
}
function get_suppoet_data_row($support,$controller)
{
	$CI =& get_instance();	
	$controller_name=strtolower(get_class($CI));
	//$avatar_url=$item->image_id ?  site_url('app_files/view/'.$item->image_id) :(base_url().'img/no-image.png');
	//var_dump(site_url('img/icons/16/tag.png')); exit();
	//$customer_data=$CI->Customer-> get_info($support->id_customer);

	$table_data_row='<tr>';
	$table_data_row.='<td width="8%"  align="center">'.$support->order_support.'</td>';
	$table_data_row.='<td width="20%" align="center">'.($support->first_name.' '.$support->last_name).'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->model.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->color.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->custom1_support_name.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->custom2_support_name.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->type_team.'</td>';
	$table_data_row.='<td width="10%" align="center">'.$support->state.'</td>';
	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/view/$support->Id_support", "<i class='fa fa-edit'></i>".lang('technical_supports_edit'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_update'))).'</td>';			


	
	if($support->state==lang("technical_supports_recibido")){
		$table_data_row.='<td width="3%" class="rightmost">'.anchor($controller_name."/repair/$support->Id_support", "<i class='fa fa-edit'></i>".lang('technical_supports_diagnose'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_diagnose'))).'</td>';			
	}
	else if($support->state==lang("technical_supports_diagnosticado")){
		$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/repair/$support->Id_support", "<i class='fa fa-edit'></i>".lang('technical_supports_aprobar_rechazar'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_aprobar_rechazar'))).'</td>';			
	}
	else if($support->state==lang("technical_supports_aprobado")){
		$table_data_row.='<td width="2%" class="rightmost">'.anchor($controller_name."/repair/$support->Id_support", "<i class='fa fa-edit'></i>".lang('technical_supports_reparar'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_reparar'))).'</td>';			
	}
	else if($support->state==lang("technical_supports_reparado") ||$support->state==lang("technical_supports_rechazado")){
		$table_data_row.='<td width="2%" class="rightmost">'.anchor($controller_name."/repair/$support->Id_support", "<i class='fa fa-edit'></i>".lang('technical_supports_entregar'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_entregar'))).'</td>';			
	}else{
		$table_data_row.='<td width="2%" class="rightmost"></td>';
	}


	
	
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage items.
*/
function get_items_manage_table($items,$controller)
{ 
	$CI =& get_instance();
	$has_cost_price_permission = $CI->Employee->has_module_action_permission('items','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
	$table='<table class="table table-bordered table-hover no_margin_bottom" cellspacing="0" width="100%" id="sortable_table">';	
	//Configuration values for showing/hiding fields in the items grid
	$show_inventory_isbn=$CI->config->item('show_inventory_isbn');
	$show_inventory_image=$CI->config->item('show_inventory_image');
	$show_inventory_size=$CI->config->item('show_inventory_size');
	$show_inventory_model=$CI->config->item('show_inventory_model');
	$show_inventory_colour=$CI->config->item('show_inventory_colour');
	$show_inventory_brand=$CI->config->item('show_inventory_brand');

	if ($has_cost_price_permission)
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		$CI->lang->line('items_item_id'),
		$show_inventory_isbn ? $CI->lang->line('items_item_number') : NULL,
		$show_inventory_image ? $CI->lang->line('reports_imagen'): NULL,
		$CI->lang->line('items_name'),
		$CI->lang->line('items_category'),
		$show_inventory_size ? $CI->lang->line('items_size') : NULL,
	    $show_inventory_model ? $CI->lang->line('items_model') : NULL,
	    $show_inventory_colour ? $CI->lang->line('items_colour') : NULL,
	    $show_inventory_brand ? $CI->lang->line('items_brand') : NULL,
		$CI->lang->line('items_cost_price'),
		$CI->lang->line('items_unit_price'),
		$CI->lang->line('items_quantity'),
		$CI->lang->line('items_quantity_warehouse'),
		$CI->lang->line('items_inventory'),
		$CI->lang->line('items_clone'),
		$CI->lang->line('common_edit'),
	
		);
	}
	else 
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>', 
		$CI->lang->line('items_item_id'),
		$show_inventory_isbn ? $CI->lang->line('items_item_number') : NULL,
		$show_inventory_image ? $CI->lang->line('reports_imagen'): NULL,
		$CI->lang->line('items_name'),
		$CI->lang->line('items_category'),
		$show_inventory_size ? $CI->lang->line('items_size') : NULL,
	    $show_inventory_model ? $CI->lang->line('items_model') : NULL,
	    $show_inventory_colour ? $CI->lang->line('items_colour') : NULL,
	    $show_inventory_brand ? $CI->lang->line('items_brand') : NULL,
		$CI->lang->line('items_unit_price'),
		$CI->lang->line('items_quantity'),
		$CI->lang->line('items_inventory'),
		$CI->lang->line('items_quantity_warehouse'),
		$CI->lang->line('items_clone'),
		$CI->lang->line('common_edit'),
	
		);
		
	}
		
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		if ($header != '') 
		{
			if ($count == 1)
			{
				$table.="<th class='leftmost'>$header</th>";
			}
			elseif ($count == count($headers))
			{
				$table.="<th class='rightmost'>$header</th>";
			}
			else
			{
				$table.="<th>$header</th>";		
			}
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_items_manage_table_data_rows($items,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_items_manage_table_data_rows($items,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($items->result() as $item)
	{
		$table_data_rows.=get_item_data_row($item,$controller);
	}
	
	if($items->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='16'><span class='col-md-12 text-center text-warning' >".lang('items_no_items_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_item_data_row($item,$controller)
{
	$CI =& get_instance();
	static $has_cost_price_permission;	
	$item_location_info = $CI->Item_location->get_info($item->item_id);

	//Configuration values for showing/hiding fields in the items grid
	$show_inventory_isbn=$CI->config->item('show_inventory_isbn');	
	$show_inventory_image=$CI->config->item('show_inventory_image');
	$show_inventory_size=$CI->config->item('show_inventory_size');
	$show_inventory_model=$CI->config->item('show_inventory_model');
	$show_inventory_colour=$CI->config->item('show_inventory_colour');
	$show_inventory_brand=$CI->config->item('show_inventory_brand');
	//echo '<pre>'.print_r($item_location_info,true).'</pre>'; exit();
	if (!$has_cost_price_permission)
	{
		$has_cost_price_permission = $CI->Employee->has_module_action_permission('items','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
	}
	
	$controller_name=strtolower(get_class($CI));
	$avatar_url=$item->image_id ?  site_url('app_files/view/'.$item->image_id) :(base_url().'img/no-image.png');
	//var_dump(site_url('img/icons/16/tag.png')); exit();
	$table_data_row='<tr>';
	$table_data_row.="<td width='1%'><input type='checkbox' class='css-checkbox' id='item_$item->item_id' value='".$item->item_id."'/><label for='".$item->item_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td width="10%">'.$item->item_id.'</td>';
	if ($show_inventory_isbn) 
	{
		$table_data_row.='<td width="13%">'.H($item->item_number).'</td>';
	}
	
	if ($avatar_url && $show_inventory_image)
	{	
		$table_data_row.="<td width='55px' align='center'>
							<a href='$avatar_url' class='fancybox rollover'>
								<img id='avatar' src='".$avatar_url."' width='40' height='35' class='img-polaroid'/>
							</a>
						</td>";
	}
	$table_data_row.='<td width="13%"><a href="'.site_url('home/view_item_modal').'/'.$item->item_id.'" data-toggle="modal" data-target="#myModal">'.H($item->name).'</a></td>';
	$table_data_row.='<td width="9%">'.H($item->category).'</td>';
	
	if ($show_inventory_size) 
	{
		$table_data_row.='<td width="9%">'.$item->size.'</td>';
	}	
	if ($show_inventory_model)
	{
		$table_data_row.='<td width="9%">'.$item->model.'</td>';	
	}
	if ($show_inventory_colour)
	{
		$table_data_row.='<td width="9%">'.$item->colour.'</td>';	
	}
	if ($show_inventory_brand) 
	{
		$table_data_row.='<td width="9%">'.$item->marca.'</td>';
	}
	
	if($has_cost_price_permission)
	{
		$table_data_row.='<td width="9%" align="right">'.to_currency($item->location_cost_price ? $item->location_cost_price: $item->cost_price, 10).'</td>';
	}
	$table_data_row.='<td width="9%" align="right">'.to_currency($item->location_unit_price ? $item->location_unit_price : $item->unit_price, 10).'</td>';
	$table_data_row.='<td width="9%" align="center">'.to_quantity($item->quantity).'</td>';
    
    if ( $CI->Employee->has_module_action_permission('items','see_quantity_defect', $CI->Employee->get_logged_in_employee_info()->person_id) ) { 
        $table_data_row.='<td width="9%" align="center">'.to_quantity($item_location_info->quantity_warehouse).'&nbsp;&nbsp;'.anchor($controller_name."/defective_item/$item->item_id", "<i class='fa fa-warning font-red'></i>", array('data-toggle'=>'modal','data-target'=>'#myModal', 'class'=>'tooltips', 'data-original-title'=>'Dar baja de productos')).'</td>';
    }else{
        $table_data_row.='<td width="9%" align="center">'.to_quantity($item_location_info->quantity_warehouse).'</td>';
    }
	
	if (!$item->is_service)
	{
		$table_data_row.='<td width="12%">'.anchor($controller_name."/inventory/$item->item_id/", "<i class='fa fa-cubes'></i>".lang('common_inv'),array('class'=>'btn btn-xs btn-block default btn-inventory','title'=>lang($controller_name.'_count'))).'</td>';//inventory details	
	
	}
	else
	{
		$table_data_row.='<td width="12%">&nbsp;</td>';
		
	}

	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/clone_item/$item->item_id", "<i class='fa fa-copy'></i>".lang('items_clone'),array('class'=>'btn btn-xs btn-block default btn-clon','title'=>lang($controller_name.'_update'))).'</td>';			
	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/view/$item->item_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'),array('class'=>'btn btn-xs btn-block default update-items','title'=>lang($controller_name.'_update'))).'</td>';		
	

	
	$table_data_row.='</tr>';
	return $table_data_row;
}


/*
Gets the html table to manage items.
*/
function get_locations_manage_table($locations,$controller)
{
	$CI =& get_instance();
	$table='<table class="table table-bordered table-striped table-hover" id="sortable_table">';	

		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>',
		$CI->lang->line('locations_location_id'),
		$CI->lang->line('locations_name'),
		$CI->lang->line('locations_address'),
		$CI->lang->line('locations_phone'),
		$CI->lang->line('locations_email'),
		lang('common_actions'),
		);
		

		
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_locations_manage_table_data_rows($locations,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_locations_manage_table_data_rows($locations,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($locations->result() as $location)
	{
		$table_data_rows.=get_location_data_row($location,$controller);
	}
	
	if($locations->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('locations_no_locations_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_location_data_row($location,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%' align='center'><input type='checkbox' class='css-checkbox' id='location_$location->location_id' value='".$location->location_id."'/><label for='".$location->location_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td width="10%">'.$location->location_id.'</td>';
	$table_data_row.='<td width="15%">'.H($location->name).'</td>';
	$table_data_row.='<td width="15%">'.H($location->address).'</td>';
	$table_data_row.='<td width="11%">'.H($location->phone).'</td>';
	$table_data_row.='<td width="11%">'.H($location->email).'</td>';
	$table_data_row.='<td width="4%" class="rightmost">'.anchor($controller_name."/view/$location->location_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'),array('class'=>'btn btn-xs btn-block default update-locations','title'=>lang($controller_name.'_update'))).'</td>';		
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage giftcards.
*/
function get_giftcards_manage_table( $giftcards, $controller )
{
	$CI =& get_instance();
	
	$table='<table class="table table-bordered table-striped table-hover" id="sortable_table">';
	
	$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>',
	lang('giftcards_giftcard_number'),
	lang('giftcards_card_value'),
	lang('giftcards_customer_name'),
    "",
	lang('common_actions'),
	);
	
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_giftcards_manage_table_data_rows( $giftcards, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the giftcard.
*/
function get_giftcards_manage_table_data_rows( $giftcards, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($giftcards->result() as $giftcard)
	{
		$table_data_rows.=get_giftcard_data_row( $giftcard, $controller );
	}
	
	if($giftcards->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('giftcards_no_giftcards_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_giftcard_data_row($giftcard,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$link = site_url('reports/detailed_'.$controller_name.'/'.$giftcard->customer_id.'/0');
	$cust_info = $CI->Customer->get_info($giftcard->customer_id);
	
	$table_data_row='<tr>';
	$table_data_row.="<td width='3%' align='center'><input type='checkbox' class='css-checkbox' id='giftcard_$giftcard->giftcard_id' value='".$giftcard->giftcard_id."'/><label for='".$giftcard->giftcard_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td width="20%">'.H($giftcard->giftcard_number).'</td>';
	$table_data_row.='<td width="25%">'.to_currency(H($giftcard->value), 10).'</td>';
	$table_data_row.='<td width="20%"><a class="underline" href="'.$link.'">'.H($cust_info->first_name). ' '.H($cust_info->last_name).'</a></td>';
	$table_data_row.='<td width="5%" class="rightmost">'.anchor($controller_name."/giftcard_print/$giftcard->giftcard_id", "<i class='fa fa-print'></i>".lang('sales_print'), array('class'=>'btn btn-xs btn-block default update-giftcards','title'=>lang($controller_name.'_update'))).'</td>';				
	
		$table_data_row.='<td width="5%" class="rightmost">'.anchor($controller_name."/view/$giftcard->giftcard_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'), array('class'=>'btn btn-xs btn-block default update-giftcards','title'=>lang($controller_name.'_update'))).'</td>';	
	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage item kits.
*/
function get_item_kits_manage_table( $item_kits, $controller )
{
	$CI =& get_instance();
	
	$table='<table class="table table-bordered table-hover" cellspacing="0" width="100%" id="sortable_table">';
	
	$has_cost_price_permission = $CI->Employee->has_module_action_permission('item_kits','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
	
	if ($has_cost_price_permission)
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>',
		lang('items_item_number'),
		lang('item_kits_name'),
		lang('item_kits_description'),
		lang('items_cost_price'),
		lang('items_unit_price'),
		lang('common_actions'),
		);
	}
	else
	{
		$headers = array('<input type="checkbox" id="select_all" class="css-checkbox"/><label for="select_all" class="css-label cb0"></label>',
		lang('items_item_number'),
		lang('item_kits_name'),
		lang('item_kits_description'),
		lang('items_unit_price'),
		lang('common_actions'), 
		);
	}
	$table.='<thead><tr>';
	$count = 0;
	foreach($headers as $header)
	{
		$count++;
		
		if ($count == 1)
		{
			$table.="<th class='leftmost'>$header</th>";
		}
		elseif ($count == count($headers))
		{
			$table.="<th class='rightmost'>$header</th>";
		}
		else
		{
			$table.="<th>$header</th>";		
		}
	}
	$table.='</tr></thead><tbody>';
	$table.=get_item_kits_manage_table_data_rows( $item_kits, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the item kits.
*/
function get_item_kits_manage_table_data_rows( $item_kits, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';
	
	foreach($item_kits->result() as $item_kit)
	{
		$table_data_rows.=get_item_kit_data_row( $item_kit, $controller );
	}
	
	if($item_kits->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><span class='col-md-12 text-center text-warning' >".lang('item_kits_no_item_kits_to_display')."</span></tr>";
	}
	
	return $table_data_rows;
}

function get_item_kit_data_row($item_kit,$controller)
{

	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	
	$has_cost_price_permission = $CI->Employee->has_module_action_permission('item_kits','see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
		
	$table_data_row='<tr>';
	$table_data_row.="<td width='3%' align='center'><input type='checkbox' class='css-checkbox' id='item_kit_$item_kit->item_kit_id' value='".$item_kit->item_kit_id."'/><label for='".$item_kit->item_kit_id."' class='css-label cb0'></label></td>";
	$table_data_row.='<td width="15%">'.H($item_kit->item_kit_number).'</td>';
	$table_data_row.='<td width="15%">'.H($item_kit->name).'</td>';
	$table_data_row.='<td width="20%">'.H($item_kit->description).'</td>';
	if ($has_cost_price_permission)
	{
		$table_data_row.='<td width="20%" align="right">'.(!is_null($item_kit->cost_price) ? to_currency(($item_kit->location_cost_price ? $item_kit->location_cost_price : $item_kit->cost_price), 10) : '').'</td>';
	}
	
	$table_data_row.='<td width="20%" align="right">'.(!is_null($item_kit->unit_price) ? to_currency(($item_kit->location_unit_price ? $item_kit->location_unit_price : $item_kit->unit_price), 10) : '').'</td>';
	$table_data_row.='<td width="5%" class="rightmost">'.anchor($controller_name."/view/$item_kit->item_kit_id/2", "<i class='fa fa-pencil'></i>".lang('common_edit'),array('class'=>'btn btn-xs btn-block default update-kits','title'=>lang($controller_name.'_update'))).'</td>';
	$table_data_row.='</tr>';
	return $table_data_row;
}

function get_cash_flow_data_row($cash_flow,$controller)
{

	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$width = $controller->get_form_width();
	$table_data_row='<tr>';
	//$table_data_row.="<td width='3%'><input type='checkbox' id='cash_flow_$cash_flow->register_log_id' value='".$cash_flow->register_log_id."'/></td>";
	$table_data_row.='<td width="15%">'.$cash_flow->register_date.'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($cash_flow->description, 25).'</td>';
	if($cash_flow->type_movement == 1){
		$table_data_row.='<td width="15%">'.to_currency($cash_flow->mount).'</td>';
		$table_data_row.='<td width="15%"></td>';
	}else{
		$table_data_row.='<td width="15%"></td>';
		$table_data_row.='<td width="15%">'.to_currency($cash_flow->mount).'</td>';
	}	
	$total=	
	$table_data_row.='<td width="15%">'.to_currency($cash_flow->mount_cash).'</td>';	
	
	$table_data_row.='</tr>';
	return $table_data_row;
}

?>