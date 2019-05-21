<?php 
class Carrito_lib
{
	protected $CI; 
	
	function __construct()
	{
		$this->CI =& get_instance();
		
	}
	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode_support'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode_support');
	}
	function limpiar_mode()
	{
		$this->CI->session->unset_userdata('sale_mode_support');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode_support',$mode);
	}

	
	function get_ivas($item_id)
	{
		$ivas=$this->CI->Item_taxes_finder->get_info($item_id);
		foreach ($ivas as $key=> $iva) 
		{
			unset($ivas[$key]["id"]);
			unset($ivas[$key]["item_id"]);
		}
		return 	$ivas;
	}
	
	public function get_payment_amount($payment_type)
	{
		$payment_amount = 0;
		if (($payment_ids = $this->get_payment_ids($payment_type)) !== FALSE)
		{
			$payments=$this->getPagos();
			
			foreach($payment_ids as $payment_id)
			{
				$payment_amount += $payments[$payment_id]['payment_amount'];
			}
		}
		
		return $payment_amount;
	}
	public function get_payment_ids($payment_type)
	{
		$payment_ids = array();
		
		$payments=$this->getPagos();
		
		for($k = 0; $k < count($payments); $k ++)
		{
			if ($payments[$k]['payment_type'] == $payment_type)
			{
				$payment_ids[] = $k;
			}
		}
		
		return $payment_ids;
	}
	function suma_ivas($ivas){
		$iva_total = 0;

		foreach ($ivas as $iva) 
			$iva_total += (double) $iva["percent"];
		
		return $iva_total;
	}
	function precio_sin_ivas_por_item($price,$ivas,$tax_included){
			
		if($tax_included)
			$price = get_price_for_item_excluding_taxes_support($ivas, $price, $sale_id = FALSE);
		
		return $price;		
	}
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null,  $line = FALSE,$custom1_subcategory=null,$custom2_subcategory=null,
						$id_tier=0,$ivas=null,$tax_included=false)
	{
	
		$store_account_item_id = $this->CI->Item->get_store_account_item_id();
		if ( $store_account_item_id == $item_id)
		{
			return FALSE;
		}			
		
		$items = $this->get_cart();
        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}
			
			if(isset($item['item_id']) && $item['item_id']==$item_id &&  $item['custom1_subcategory']== $custom1_subcategory && $item['custom2_subcategory']== $custom2_subcategory )
			{				
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
				
			}			
		}
		$insertkey=$maxkey+1;
		if($this->CI->config->item("subcategory_of_items")==1 and $this->CI->config->item("inhabilitar_subcategory1")==1 and $custom1_subcategory==null){

			$custom1_subcategory="»";
		}
		$item_info = $this->CI->Item->get_info($item_id);
		$ivas = $ivas!==null?$ivas:$this->get_ivas($item_id);
		$tax_included= $tax_included ==1 ? $tax_included : $item_info->tax_included;
		$precio_usar = $price!=null ? $price: $this->get_price_for_item($item_id,$id_tier);
		
		$item = array(($line === FALSE ? $insertkey : $line)=>
			array(
				'item_id'=>$item_id,
				'line'=>$line === FALSE ? $insertkey : $line,
				'name'=>$item_info->name,
				'size' => $item_info->size,
				'model'=>$item_info->model,
				'colour'=>$item_info->colour,
				'marca'=>$item_info->marca,
				'unit'=>$item_info->unit,
				'is_service'=>$item_info->is_service,
				'tax_included'=> $tax_included,
				'product_id' => $item_info->product_id,
				'description'=>$description!=null ? $description: $item_info->description,
				'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
				'allow_alt_description'=>$item_info->allow_alt_description,
				'is_serialized'=>$item_info->is_serialized,
				'quantity'=>$quantity,
				'item_number'=>$item_info->item_number,
				'discount'=>$discount,
				'custom1_subcategory'=>$custom1_subcategory,
				'custom2_subcategory'=>$custom2_subcategory,
				'price'=>$precio_usar,//precio establecido por la tienda, incluyendo  promo
				'has_subcategory'=>$item_info->subcategory,
				"id_tier"=>$id_tier,
				"numero_cuenta"=>null,
				"numero_documento"=>null,
				"titular_cuenta"=>null,
				"tipo_documento"=> null,
				"tasa"=>null,
				"transaction_status"=>null,
				"comentarios"=>null,
				"fecha_estado"=>null,
				"tipo_cuenta"=>null,
				"observaciones"=>null,
				"celular"=>null,
				'ivas' =>$ivas,
				"precio_e_iva"=>0,	
				//datos de unidades de ventas
				"has_sales_units" => $item_info->has_sales_units,
				"name_unit"=>null,
				"has_selected_unit" => 0,
				"unit_quantity_presentation" => null,// cantidad de unidad de la presentacion del producto
				"unit_quantity_item" => null,//$unit_quantity_item != null ? $unit_quantity_item : $item_info->quantity_unit_sale,// canidad maxima de unidad  que tien el producto, esto solo se modifica por inventario
				"unit_quantity"	=>null,	//cantidad a vender de la presentacion	
				"price_presentation" => null
				)
			);
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($item_info->is_serialized ==0) and $item_info->is_service==0 )
		{	
			$_line= ($line === FALSE ? $updatekey : $line);
			$new_quantity= $items[$_line]['quantity']+=$quantity;		
			$items[$_line]['quantity']=$new_quantity;
			$items[$_line]['precio_e_iva']=$this->precio_con_iva($items[$_line]);
		}
		else
		{
			//add to existing array
			$_lin= ($line === FALSE ? $insertkey : $line);
			$item[$_lin]['precio_e_iva']=$this->precio_con_iva($item[$_lin]);			
			$items+=$item;
		}
		
		if( $this->CI->config->item('sales_stock_inventory')/* and $this->out_of_stock($item_id) */)
		{
			$items=$this->get_cart();
		}
		$this->set_cart($items);
		return true;
	}
	function get_cart()
	{
		if($this->CI->session->userdata('cart_servicio') == false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cart_servicio');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart_servicio',$cart_data);
		
	}
	public function getPrecioSubtotal()
	{
		$subtotal_total=0;
		foreach ($this->get_cart() as $item) {
			$subtotal_total+=$this->subtotal_por_item($item);
		}
		return $subtotal_total;		
	}
	function valor_ivas($item,$price_to_use){
		$valor=array();
		$tax_info=$item["ivas"];
		foreach($tax_info as $key=>$tax)
		{
			//$name = $tax['percent'].'% ' . $tax['name'];
			if ($tax['cumulative'])
			{
				$prev_tax = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
				$tax_amount=(($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
			}
			else
			{
				$tax_amount=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
			}
			$valor[]=array("valor"=>$tax_amount,"name"=>$tax['name'],"percent"=>$tax['percent']);
			/*if (!isset($taxes[$name]))
			{
				$taxes[$name] = 0;
			}*/
			//$taxes[$name] += $tax_amount;
		}
		return $valor;
	}
	function precio_con_iva($item){
		$precio = $this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);
		$ivas_valor= $this->valor_ivas($item,$precio);		
		foreach ($ivas_valor as  $iva) {
			$precio+= $iva["valor"];
		}					
		return $precio;
	}
	
	function get_pagos_agregados(){
		$total=0;
		$pagos_agregados= $this->getPagos();
		
		foreach ($pagos_agregados as $pago) {
			$total+= $pago["payment_amount"];
		}
		return $total;
	}
	function total_por_item($item){
		$precio = $this->precio_con_iva($item);
		return $precio*(double) $item["quantity"];
	}
	function subtotal_por_item($item){
		$precio = $this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);
		return $precio* $item["quantity"];
	}
	function is_sale_cash_payment()
	{
		foreach($this->getPagos() as $payment)
		{
			if($payment['payment_type'] ==  lang('sales_cash'))
			{
				return true;
			}
		}
		
		return false;
	}
	function set_edit_support_id($suspended_sale_id)
	{
		$this->CI->session->set_userdata('edit_support_id',$suspended_sale_id);
	}
	
	function limpiar_edit_support_id()
	{
		$this->CI->session->unset_userdata('edit_support_id');
	}

	function get_edit_support_id()
	{
		return $this->CI->session->userdata('edit_support_id');
	}
	public function getPrecioTotal()
	{		
		$precio_total=0;
		foreach ($this->get_cart() as $item) {
			$precio_total+=(double)$this->total_por_item($item);
		}
		return $precio_total;;
	}

	public function getArticulosTotal()
	{
		$articulos_total=0;
		foreach ($this->get_cart() as $item) {
			$articulos_total+=$item["quantity"];
		}
		return $articulos_total;		
	}
	
	function delete_item($line)
	{
		$items=$this->get_cart();		
		unset($items[$line]);
		$this->set_cart($items);
	}
 //Obtener detalladamente valores de los impuestos
 function get_detailed_taxes($customer_id =-1 ,$sale_id = false)
 {        
	 $taxes = array();

	 if ($sale_id) 
	 {
		 $taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
		 foreach($taxes_from_sale as $key=>$tax_item)
		 {
			 $name = $tax_item['name'].' '.$tax_item['percent'].'%';
			 
			 $tax_base   = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100);
			 $tax_amount = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
			 $tax_total  = $tax_base + $tax_amount;

			 if (!isset($taxes[$name]))
			 {
				 $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
			 }

			 $taxes[$name]['base'] += $tax_base;
			 $taxes[$name]['total_tax'] += $tax_amount;
			 $taxes[$name]['total'] += $tax_total;
		 }
	 }
	 else
	 {            
		
		 $customer = $this->CI->Customer->get_info($customer_id);

		 //Do not charge sales tax if we have a customer that is not taxable
		 if (!$customer->taxable and $customer_id!=-1)
		 {
			 return array();
		 }
		 if($this->get_overwrite_tax()==false){
			 foreach($this->get_cart() as $line=>$item)
			 {
				$price_to_use =$this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);      
				 
				 $tax_info = $item['ivas'] ;
				 foreach($tax_info as $key=>$tax)
				 {
					 
					 $name = $tax['percent'].'% ' . $tax['name'];
					 $tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
					 $tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					 $tax_total  = $tax_base + $tax_amount;					 

					 if (!in_array($name, $this->get_deleted_taxes()))
					 {
						 if (!isset($taxes[$name]))
						 {
							 $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
						 }
						 
						 $taxes[$name]['base'] += $tax_base;
						 $taxes[$name]['total_tax'] += $tax_amount;
						 $taxes[$name]['total'] += $tax_total;
					 }
				 }
			 }
		 }
		 /** Para cuando se agrega un nuevo impuesto */
		 else{
			 foreach($this->get_cart() as $line=>$item)
			 {
				 $price_to_use =$this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);      
				 
				 $tax_info =array("0"=>$this->get_new_tax());
				 foreach($tax_info as $key=>$tax)
				 {
					 
					 $name = $tax['percent'].'% ' . $tax['name'];
					 $tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
					 $tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					 $tax_total  = $tax_base + $tax_amount;					

					if (!isset($taxes[$name]))
					{
						$taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
					}						 
					 $taxes[$name]['base'] += $tax_base;
					 $taxes[$name]['total_tax'] += $tax_amount;
					 $taxes[$name]['total'] += $tax_total;
					 
				 }
			 }
		 }
	 }
	 
	 return $taxes;
 }
  //Obtener los valores totales de los impuestos
  function get_detailed_taxes_total($customer_id=-1,$sale_id = false)
  {
	  $taxes = array();

	  if ($sale_id) 
	  {
		  $total_base_sum = 0;
		  $total_tax_sum  = 0;
		  $total_sum = 0;
		  
		  $taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
		  foreach($taxes_from_sale as $key=>$tax_item)
		  {
			  $name = $tax_item['name'].' '.$tax_item['percent'].'%';
			  
			  $tax_base   = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100);
			  $tax_amount = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
			  $tax_total  = $tax_base + $tax_amount;

			  if (!isset($taxes[$name]))
			  {
				  $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
			  }

			  $total_base_sum = $tax_base + $total_base_sum;
			  $total_tax_sum = $tax_amount + $total_tax_sum;
			  $total_sum = $tax_total + $total_sum;
		  }
		  
		  $taxes['total_base_sum'] = $total_base_sum;
		  $taxes['total_tax_sum'] = $total_tax_sum;
		  $taxes['total_sum'] = $total_sum;
	  }
	  else
	  {            
		  $total_base_sum = 0;
		  $total_tax_sum  = 0;
		  $total_sum = 0;

		  
		  $customer = $this->CI->Customer->get_info($customer_id);

		  //Do not charge sales tax if we have a customer that is not taxable
		  if (!$customer->taxable and $customer_id!=-1)
		  {
			  return array();
		  }
		  if($this->get_overwrite_tax()==false){
			  foreach($this->get_cart() as $line=>$item)
			  {  
				  $price_to_use = $this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);       
				  
				  $tax_info = $item['ivas'];
				  foreach($tax_info as $key=>$tax)
				  {
					  $name = $tax['percent'].'% ' . $tax['name'];

					  $tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
					  $tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					  $tax_total  = $tax_base + $tax_amount;
					  
					  if (!in_array($name, $this->get_deleted_taxes()))
					  {
						  if (!isset($taxes[$name]))
						  {
							  $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
						  }
						  
						  $total_base_sum = $tax_base + $total_base_sum;
						  $total_tax_sum = $tax_amount + $total_tax_sum;
						  $total_sum = $tax_total + $total_sum;
					  }
				  }
			  }
		  }else{
			  foreach($this->get_cart() as $line=>$item)
			  {  
				  $price_to_use =$this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);       
				  
				  $tax_info = $tax_info =array("0"=>$this->get_new_tax());
				  foreach($tax_info as $key=>$tax)
				  {
					  $name = $tax['percent'].'% ' . $tax['name'];

					  $tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
					  $tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					  $tax_total  = $tax_base + $tax_amount;
					  
					  
					  if (!isset($taxes[$name]))
					  {
						  $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
					  }
					  $total_base_sum = $tax_base + $total_base_sum;
					  $total_tax_sum = $tax_amount + $total_tax_sum;
					  $total_sum = $tax_total + $total_sum;
					  
				  }
			  }
		  }

		  $taxes['total_base_sum'] = $total_base_sum;
		  $taxes['total_tax_sum'] = $total_tax_sum;
		  $taxes['total_sum'] = $total_sum;		    	    
	  }
	  return $taxes;
  }
 function get_new_tax(){
	return $this->CI->session->userdata('new_tax_support');
}
 function get_deleted_taxes() 
	{
		$deleted_taxes = $this->CI->session->userdata('deleted_taxes_support') ? $this->CI->session->userdata('deleted_taxes_support') : array();
		return $deleted_taxes;
	}

 function get_overwrite_tax(){
	return $this->CI->session->userdata('overwrite_tax_support') == 1 ?true:false ;
}
	public function getIvaProductos()
	{
		$carrito = $this->get_cart();
		$articulos = array();
		foreach ($carrito as $item) {			
			$precio = $this->precio_sin_ivas_por_item($item["price"],$item["ivas"],$item["tax_included"]);
			$ivas_valor= $this->valor_ivas($item,$precio);	
			$articulo= array(
				'nombre'=> $item["name"],
				'ivas'=>$ivas_valor,
				'item_id' => $item["item_id"],
			);
			$articulos[]=$articulo;
		}
		return $articulos;
	}

	public function getPagos()
	{		
		return $this->CI->session->userdata('pagos_servicio')? $this->CI->session->userdata('pagos_servicio'):array();		
	}

	public function add_pago($payment_type, $payment_amount,$date=null)
	{		
		if($payment_amount!=0){
			$payments_data = $this->getPagos();
			$payment = [
				'payment_type' => $payment_type,
				'payment_amount' => $payment_amount,
				'payment_date' => $date==null?date('Y-m-d H:i:s'):$date,
				'truncated_card' => '',
				'card_issuer' => '',
			];
			$payments_data[] = $payment;		
			$this->CI->session->set_userdata('pagos_servicio', $payments_data);
		}
		return true;
	}

	public function deletePago($payment_ids)
	{
		$payments = $this->getPagos();
		if (is_array($payment_ids)) {
			foreach($payment_ids as $payment_id)
			{
				unset($payments[$payment_id]);
			}
		} else {
			unset($payments[$payment_ids]);			
		}
		$this->CI->session->set_userdata('pagos_servicio', array_values($payments));			
	}

	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE, $custom1_subcategory=false,$custom2_subcategory=false)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			if ($description !== FALSE ) {
				$items[$line]['description'] = $description;
			}
			if ($serialnumber !== FALSE ) {
				$items[$line]['serialnumber'] = $serialnumber;
			}
			if ($quantity !== FALSE ) {
				$items[$line]['quantity'] = $quantity;
			}
			
			if ($price !== FALSE ) {
				$items[$line]['price'] = $price;
				$items[$line]['precio_e_iva']=$this->precio_con_iva($items[$line]);
			}
			
			$this->set_cart($items);
			
			return true;
		}
		return false;
	}
	function get_selected_tier_id(){
		return 0;
	}
	function get_price_for_item($item_id, $tier_id = FALSE)
	{
		if ($tier_id === FALSE )
		{
			$tier_id = 0 ;//$this->get_selected_tier_id();
		}		
		$item_info = $this->CI->Item->get_info($item_id);
		$item_location_info = $this->CI->Item_location->get_info($item_id);		
		$item_tier_row = $this->CI->Item->get_tier_price_row($tier_id, $item_id);
		$item_location_tier_row = $this->CI->Item_location->get_tier_price_row($tier_id, $item_id, $this->CI->Employee->get_logged_in_employee_current_location_id());
		
		if (!empty($item_location_tier_row) && $item_location_tier_row->unit_price)
		{
			return to_currency_no_money($item_location_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_location_tier_row) && $item_location_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_location_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->unit_price)
		{
			return to_currency_no_money($item_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		else
		{
			$today =  strtotime(date('Y-m-d'));
			$is_item_location_promo = ($item_location_info->start_date !== NULL && $item_location_info->end_date !== NULL) && (strtotime($item_location_info->start_date) <= $today && strtotime($item_location_info->end_date) >= $today);
			$is_item_promo = $this->CI->Sale->get_promo_quantity($item_id) > 0  && ($item_info->start_date !== NULL && $item_info->end_date !== NULL) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today);
			
			if ($is_item_location_promo)
			{
				return to_currency_no_money($item_location_info->promo_price, 10);
			}
			elseif ($is_item_promo)
			{
				return to_currency_no_money($item_info->promo_price, 10);
			}
			else
			{
				$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
				return to_currency_no_money($item_unit_price, 10);
			}
		}					
	}
	function get_point($value_point,$total)
	{
		$value_purchase =$total;

		if($value_purchase > $value_point && $value_point!=0)
		{
			$point_pucharse = floor($value_purchase/$value_point);
		}
		else
		{
			$point_pucharse = 0;
		}
		return $point_pucharse;
	}
	function limpiar_serie_number(){
		$this->CI->session->unset_userdata('serie_number_2');
	}
	function get_serie_number(){
		if($this->CI->session->userdata('serie_number_2')==false){
			$location = $this->CI->Employee->get_current_location_info();
			$this->set_serie_number($location->serie_number!="" ? $location->serie_number:1);
		}
		return $this->CI->session->userdata('serie_number_2');
	}
	function set_serie_number($serie_number){
		$this->CI->session->set_userdata('serie_number_2',$serie_number);		
	}
	function get_comentario(){		

		return $this->CI->session->userdata('comentario');
	}
	function set_comentario($comentario){
		$this->CI->session->set_userdata('comentario',$comentario);
	}
	function get_retira(){		

		return $this->CI->session->userdata('retirado_por');
	}
	function set_retira($retirado_por){
		$this->CI->session->set_userdata('retirado_por',$retirado_por);
	}
	
	function limpiar_cometario(){
		$this->CI->session->unset_userdata('comentario');
	}
	function limpiar_retira(){
		$this->CI->session->unset_userdata('retirado_por');
	}
	function is_over_credit_limit($cust_info)
	{	
		$current_sale_store_account_balance = $this->get_payment_amount(lang('sales_store_account'));
		return $cust_info->credit_limit !== NULL && $cust_info->balance + $current_sale_store_account_balance > $cust_info->credit_limit;
		
	}
	function cargar_cart($support_id)
	{		
		$support = $this->get_edit_support_id();
		$this->clear_all();
		$this->set_edit_support_id($support);
		$respuestos= $this->CI->CarritoModel->get_respuestos($support_id);
		foreach ($respuestos as $item) {
			$ivas= $this->cargar_ivas($item->id,$item->line);
			$this->add_item(
				$item->item_id,$item->quantity,$item->discount,$item->price,
				$item->description,$item->serialnumber, $item->line ,$item->custom1_subcategory,
				$item->custom2_subcategory,$item->id_tier=0,$ivas,$item->tax_included);
		}
	}
	function cargar_ivas($support_cart_id,$line){
		$_ivas=array();
		$ivas= $this->CI->CarritoModel->get_impuestos($support_cart_id,$line);
		foreach ($ivas as $iva) {
			$_ivas[]=array(
				"name"=>$iva->name,
				"percent"=>(double)$iva->percent,
				"cumulative"=>(int)$iva->cumulative,
			);
		}
		return $_ivas;
	}
	function get_comment_ticket() 
	{
		return $this->CI->session->userdata('show_comment_ticket_2') ? $this->CI->session->userdata('show_comment_ticket') : '';
	}
	function set_comment_ticket($comment_ticket) 
	{
		$this->CI->session->set_userdata('show_comment_ticket_2', $comment_ticket);
	}

	function limpiar_comment_ticket(){
		$this->CI->session->unset_userdata('show_comment_ticket_2');
	}

	function limpiar_cart(){
		$this->CI->session->unset_userdata('cart_servicio');
	}

	function limpiar_pagos(){
		$this->CI->session->unset_userdata('pagos_servicio');
	}
	
	function clear_all(){
		$this->limpiar_cart();
		$this->limpiar_pagos();
		$this->limpiar_cometario();
		$this->limpiar_serie_number();
		$this->limpiar_comment_ticket();
		$this->limpiar_mode();
		$this->limpiar_edit_support_id();
		$this->limpiar_retira();
	}
}
?>