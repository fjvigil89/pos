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

	/*public function getItem($item)
	{
		return $this->CI->CarritoModel->obtenerItems("$item");
	}

	public function getItemsTaxes($item)
	{
		return $this->CI->CarritoModel->obtenerItemsTaxes("$item");
	}

	public function getItemsLocationTaxes($item, $location)
	{
		return $this->CI->CarritoModel->obtenerItemsLocationTaxes("$item", "$location");
	}

	public function getLocationPrincipal($location)
	{
		return $this->CI->CarritoModel->obtenerItemsLocationPrincipal("$location");
	}

	public function getConfig()
	{
		return $this->CI->CarritoModel->obtenerConfig();
	}*/
	function get_ivas($item_id){
		$ivas=$this->CI->Item_taxes_finder->get_info($item_id);
		foreach ($ivas as $key=> $iva) {
			unset($ivas[$key]["id"]);
			unset($ivas[$key]["item_id"]);
		}
		return 	$ivas;
	}
	/*public function getIvaConfig()
	{
		$data = [];
		$iva = [];
		$array_config = $this->getConfig();

		if ($array_config) {

			foreach ($array_config as $row) {
				$data += ["$row->key" => $row->value];
			}

			$iva[0] = ($data['default_tax_1_rate'] != "") ? $data['default_tax_1_rate'] : 0;
			$iva[1] = ($data['default_tax_2_rate'] != "") ? $data['default_tax_2_rate'] : 0;
			$iva[2] = ($data['default_tax_3_rate'] != "") ? $data['default_tax_3_rate'] : 0;
			$iva[3] = ($data['default_tax_4_rate'] != "") ? $data['default_tax_4_rate'] : 0;
			$iva[4] = ($data['default_tax_5_rate'] != "") ? $data['default_tax_5_rate'] : 0;

			return $iva;

		} else {

			return false;

		}

	}

	public function getIvaTiendaPrincipal($location)
	{
		$iva = [];

		$row_location_principal = $this->getLocationPrincipal("$location");

		if ($row_location_principal->default_tax_1_rate != "") {

			$iva[0] = ($row_location_principal->default_tax_1_rate != "") ? $row_location_principal->default_tax_1_rate : 0;
			$iva[1] = ($row_location_principal->default_tax_2_rate != "") ? $row_location_principal->default_tax_2_rate : 0;
			$iva[2] = ($row_location_principal->default_tax_3_rate != "") ? $row_location_principal->default_tax_3_rate : 0;
			$iva[3] = ($row_location_principal->default_tax_4_rate != "") ? $row_location_principal->default_tax_4_rate : 0;
			$iva[4] = ($row_location_principal->default_tax_5_rate != "") ? $row_location_principal->default_tax_5_rate : 0;

			return $iva;

		} else {

			return false;

		}

	}

	public function getIvaTiendita($item, $location)
	{
		$iva = [];
		$array_location_principal = $this->getItemsLocationTaxes("$item", "$location");

		if ($array_location_principal) {

			$iva[0] = 0;
			$iva[1] = 0;
			$iva[2] = 0;
			$iva[3] = 0;
			$iva[4] = 0;

			$i = 0;

			foreach ($array_location_principal as $row) {
				$iva[$i] = ($row->percent != "") ? $row->percent : 0;
				$i++;
			}

			return $iva;

		} else {

			return false;

		}

	}

	public function getIvaItem($item)
	{
		$iva = [];
		$array_item = $this->getItemsTaxes("$item");

		if ($array_item) {

			$iva[0] = 0;
			$iva[1] = 0;
			$iva[2] = 0;
			$iva[3] = 0;
			$iva[4] = 0;

			$i = 0;

			foreach ($array_item as $row) {
				$iva[$i] = ($row->percent != "") ? $row->percent : 0;
				$i++;
			}

			return $iva;

		} else {

			return false;

		}

	}*/

	/*public function agregarItem($item, $location, $carrito = 'carrito')
	{
		if (!isset($_SESSION["car$carrito"])) {
			$_SESSION["car$carrito"] = null;
			$this->carrito["precio_total"] = 0;
			$this->carrito["articulos_total"] = 0;
		}

		$this->carrito = $_SESSION["car$carrito"];

		$row_item = $this->getItem("$item");

		if ($this->getIvaConfig()) {
			$this->iva_total = 0;
			$iva = $this->getIvaConfig();
			$this->iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];
		}

		if ($this->getIvaTiendaPrincipal("$location")) {
			$this->iva_total = 0;
			$iva = $this->getIvaTiendaPrincipal("$location");
			$this->iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];
		}

		if ($this->getIvaTiendita("$item", "$location")) {
			$this->iva_total = 0;
			$iva = $this->getIvaTiendita("$item", "$location");
			$this->iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];
		}

		if ($this->getIvaItem("$item")) {
			$this->iva_total = 0;
			$iva = $this->getIvaItem("$item");
			$this->iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];
		}

		$articulo = [
			'id' => $row_item->item_id,
			'precio' => $row_item->unit_price,
			'nombre' => $row_item->name,
			'cantidad' => 1,
			'iva_uno' => $iva[0],
			'iva_dos' => $iva[1],
			'iva_tres' => $iva[2],
			'iva_cuatro' => $iva[3],
			'iva_cinco' => $iva[4],
		];

		$unique_id = md5($articulo["id"]);
		$articulo["unique_id"] = $unique_id;

		if (!empty($this->carrito)) {
			foreach ($this->carrito as $row) {
				if ($row["unique_id"] === $unique_id) {
					$articulo["cantidad"] = $row["cantidad"] + $articulo["cantidad"];
				}
			}
		}

		$articulo['precio_con_iva'] = $articulo['precio'] * (1 + ($this->iva_total / 100));

		$articulo["subtotal"] = $articulo["cantidad"] * $articulo['precio'];

		$articulo["total"] = $articulo["cantidad"] * $articulo['precio_con_iva'];

		$_SESSION["car$carrito"]["$unique_id"] = $articulo;

		$this->updateCarrito("$carrito");

		$this->updatePrecioCantidad("$carrito");

	}

	public function updateCarrito($carrito)
	{
		if (!isset($_SESSION["car$carrito"])) {
			$_SESSION["car$carrito"] = null;
			$this->carrito["subtotal_total"] = 0;
			$this->carrito["precio_total"] = 0;
			$this->carrito["articulos_total"] = 0;
		}

		$this->carrito = $_SESSION["car$carrito"];
	}

	public function updatePrecioCantidad($carrito)
	{
		$total = 0;
		$articulos = 0;
		$subtotal = 0;

		foreach ($this->carrito as $row) {
			$total += ($row['precio_con_iva'] * $row['cantidad']);
			$subtotal += ($row['precio'] * $row['cantidad']);
			$articulos += $row['cantidad'];
		}

		$_SESSION["car$carrito"]["subtotal_total"] = $subtotal;
		$_SESSION["car$carrito"]["precio_total"] = $total;
		$_SESSION["car$carrito"]["articulos_total"] = $articulos;

		$this->carrito = $_SESSION["car$carrito"];
	}

	public function getContenidoCarrito()
	{
		unset($this->carrito["articulos_total"]);
		unset($this->carrito["precio_total"]);
		unset($this->carrito["subtotal_total"]);
		return $this->carrito == null ? null : $this->carrito;
	}*/
	function suma_ivas($ivas){
		$iva_total=0;
		foreach ($ivas as $iva) {
			$iva_total += (double) $iva["percent"];
		}
		return $iva_total;
	}
	function precio_sin_ivas_por_item($price,$ivas,$tax_included){
			
		if($tax_included){
			$price = get_price_for_item_excluding_taxes_support($ivas, $price, $sale_id = FALSE);
		}
		return $price;		
	}
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null,  $line = FALSE,$custom1_subcategory=null,$custom2_subcategory=null,
	$id_tier=0,$ivas=null,$tax_included=false){
	
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
				)
			);
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($item_info->is_serialized ==0) )
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
		
		if( $this->CI->config->item('sales_stock_inventory') and $this->out_of_stock($item_id) )
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
	
	function get_a_apagar(){
		$total=0;
		$pagos_agregados= $this->getPagos();
		
		foreach ($pagos_agregados as $pago) {
			$total= $pago["payment_amount"];
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

	/*public function getRepuestos($id_support)
	{
		return $this->CI->CarritoModel->getRespuestos($id_support);
	}*/

	public function getPagos()
	{		
		return $this->CI->session->userdata('pagos_servicio')? $this->CI->session->userdata('pagos_servicio'):array();		
	}

	public function add_pago($payment_type, $payment_amount)
	{		
		if($payment_amount!=0){
			$payments_data = $this->getPagos();
			$payment = [
				'payment_type' => $payment_type,
				'payment_amount' => $payment_amount,
				'payment_date' => date('Y-m-d H:i:s'),
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

	/*public function destroy($carrito)
	{
		unset($_SESSION["car$carrito"]);
		$this->carrito = null;
		return true;
	}*/
	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE, $custom1_subcategory=false,$custom2_subcategory=false)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			/*if ($description !== FALSE ) {
				$items[$line]['description'] = $description;
			}*/
			if ($serialnumber !== FALSE ) {
				$items[$line]['serialnumber'] = $serialnumber;
			}
			if ($quantity !== FALSE ) {
				$items[$line]['quantity'] = $quantity;
			}
			/*if ($discount !== FALSE ) {
				$items[$line]['discount'] = $discount;
			}
			if ($price !== FALSE ) {
				$items[$line]['price'] = $price;
			}
			if ($custom1_subcategory !== FALSE ) {
				$items[$line]['custom1_subcategory'] = $custom1_subcategory;
				$items[$line]['custom2_subcategory'] = "";
			}
			if ($custom2_subcategory !== FALSE ) {
				$items[$line]['custom2_subcategory'] = $custom2_subcategory;
			}*/
			
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
	function limpiar_cometario(){
		$this->CI->session->unset_userdata('comentario');
	}
	function cargar_cart($support_id){
		$this->clear_all();
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
	}

}
?>