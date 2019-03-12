<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carrito_Lib
{
	protected $ci;
	public $carrito = array();
	protected $iva_total = 0;
	protected $subtotal = 0;
	protected $subtotal_item= 0;

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('CarritoModel');
	}

	public function getItem($item)
	{
		return $this->ci->CarritoModel->obtenerItems("$item");
	}

	public function getItemsTaxes($item)
	{
		return $this->ci->CarritoModel->obtenerItemsTaxes("$item");
	}

	public function getItemsLocationTaxes($item, $location)
	{
		return $this->ci->CarritoModel->obtenerItemsLocationTaxes("$item", "$location");
	}

	public function getLocationPrincipal($location)
	{
		return $this->ci->CarritoModel->obtenerItemsLocationPrincipal("$location");
	}

	public function getConfig()
	{
		return $this->ci->CarritoModel->obtenerConfig();
	}

	public function getIvaConfig()
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

	}

	public function agregarItem($item, $location, $carrito = 'carrito')
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
	}
	function add_item($item_id, $location){
		$store_account_item_id = $this->CI->Item->get_store_account_item_id();
		
		//Do NOT allow item to get added unless in store_account_payment mode
		if ( $store_account_item_id == $item_id)
		{
			return FALSE;
		}
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

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
			//Also, we have stored the key in the element itself so we can compare.
			// cuano en configuracion no esta la subcategoria activa custom1_subcategory custom2_subcategory serán null

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

	
		
		//array/cart records are identified by $insertkey and item_id is just another field.
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
				'price'=>$price!=null ? $price:$price_to_use,
				'has_subcategory'=>$item_info->subcategory,
				"id_tier"=>$id_tier,
				"numero_cuenta"=>$numero_cuenta,
				"numero_documento"=>$numero_documento,
				"titular_cuenta"=>$titular_cuenta,
				"tipo_documento"=> $tipo_documento,
				"tasa"=>$tasa,
				"transaction_status"=>$transaction_status,
				"comentarios"=>$comentarios,
				"fecha_estado"=>$fecha_estado,
				"tipo_cuenta"=>$tipo_cuenta,
				"observaciones"=>$observaciones,
				"celular"=>$celular
				)
			);
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($item_info->is_serialized ==0) && $this->CI->config->item('activar_casa_cambio')==0)
		{
			$items[$line === FALSE ? $updatekey : $line]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}
		
		if( $this->CI->config->item('sales_stock_inventory') and $this->out_of_stock($item_id) )
		{
			$items=$this->get_cart();
		}
		$this->set_cart($items);
		return true;
	}

	public function getPrecioSubtotal()
	{
		return $this->carrito["subtotal_total"] ? $this->carrito["subtotal_total"] : 0;
	}

	public function getPrecioTotal()
	{
		return $this->carrito["precio_total"] ? $this->carrito["precio_total"] : 0;
	}

	public function getArticulosTotal()
	{
		return $this->carrito["articulos_total"] ? $this->carrito["articulos_total"] : 0;
	}

	public function updateCantidad($item, $cantidad, $location, $carrito)
	{
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
			'cantidad' => $cantidad,
			'iva_uno' => $iva[0],
			'iva_dos' => $iva[1],
			'iva_tres' => $iva[2],
			'iva_cuatro' => $iva[3],
			'iva_cinco' => $iva[4],
		];

		$unique_id = md5($row_item->item_id);
		$articulo["unique_id"] = $unique_id;

		$articulo['precio_con_iva'] = $articulo['precio'] * (1 + ($this->iva_total / 100));

		$articulo["subtotal"] = $articulo["cantidad"] * $articulo['precio'];

		$articulo["total"] = $articulo["cantidad"] * $articulo['precio_con_iva'];

		$_SESSION["car$carrito"]["$unique_id"] = $articulo;

		$this->updateCarrito("$carrito");

		$this->updatePrecioCantidad("$carrito");
	}

	public function deleteItemCarrito($unique_id, $carrito)
	{
		if (!isset($_SESSION["car$carrito"]["$unique_id"])) {

			return false;

		} else {

			unset($_SESSION["car$carrito"]["$unique_id"]);

			$this->updateCarrito("$carrito");

			$this->updatePrecioCantidad("$carrito");

			return true;
		}

	}

	public function getIvaProductos()
	{

		$iva_total = 0;
		$location = $this->ci->Employee->get_logged_in_employee_current_location_id();

		$carrito = $this->getContenidoCarrito();

		if ($carrito != "") {

			$articulos = array();

			$i = 0;

			foreach ($carrito as $row) {

				$row_item = $this->getItem("{$row['id']}");

				if ($this->getIvaTiendita("{$row['id']}", "$location")) {

					$iva = $this->getIvaTiendita("{$row['id']}", "$location");
					$iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];

					foreach ($carrito as $value) {

						if ($row['id'] == $value['id']) {

							$subtotal = $value['subtotal'];

						}

					}

					$articulos[$i]['nombre'] = $row_item->name;
					$articulos[$i]['iva_uno'] = $iva[0];
					$articulos[$i]['iva_dos'] = $iva[1];
					$articulos[$i]['iva_tres'] = $iva[2];
					$articulos[$i]['iva_cuatro'] = $iva[3];
					$articulos[$i]['iva_cinco'] = $iva[4];
					$articulos[$i]['precio'] = $subtotal;
					$articulos[$i]['iva_total'] = $iva_total;
					$articulos[$i]['id'] =  $row_item->item_id;

					$i++;

				}

				if ($this->getIvaItem("{$row['id']}")) {

					$iva = $this->getIvaItem("{$row['id']}");
					$iva_total = $iva[0] + $iva[1] + $iva[2] + $iva[3] + $iva[4];


					foreach ($carrito as $value) {

						if ($row['id'] == $value['id']) {

							$subtotal = $value['subtotal'];

						}

					}

					$articulos[$i]['nombre'] = $row_item->name;
					$articulos[$i]['iva_uno'] = $iva[0];
					$articulos[$i]['iva_dos'] = $iva[1];
					$articulos[$i]['iva_tres'] = $iva[2];
					$articulos[$i]['iva_cuatro'] = $iva[3];
					$articulos[$i]['iva_cinco'] = $iva[4];
					$articulos[$i]['precio'] = $subtotal;
					$articulos[$i]['iva_total'] = $iva_total;
					$articulos[$i]['id'] = $row_item->item_id;

					$i++;

				}

			}
			return $articulos;

		} else {

			return null;

		}

	}

	public function getRepuestos($id_support)
	{
		return $this->ci->CarritoModel->getRespuestos($id_support);
	}

	public function agregarItemCarrito($carrito = 'carrito')
	{
		if (!isset($_SESSION["car$carrito"])) {
			$_SESSION["car$carrito"] = null;
			$this->carrito["precio_total"] = 0;
			$this->carrito["articulos_total"] = 0;
		}

		$this->carrito = $_SESSION["car$carrito"];

		$support_repuesto = $this->getRepuestos("$carrito");

		foreach ($support_repuesto as $row) {

			$articulo = [
				'id' => $row->repuesto_item,
				'precio' => $row->unit_price,
				'nombre' => $row->name,
				'cantidad' => $row->respuesto_cantidad,
				'iva_uno' => $row->repuesto_iva_uno,
				'iva_dos' => $row->repuesto_iva_dos,
				'iva_tres' => $row->repuesto_iva_tres,
				'iva_cuatro' => $row->repuesto_iva_cuatro,
				'iva_cinco' => $row->repuesto_iva_cinco,
				'subtotal' => $row->repuesto_subtotal,
				'total' => $row->repuesto_total,
			];

			$this->iva_total = $articulo['iva_uno'] + $articulo['iva_dos'] + $articulo['iva_tres'] + $articulo['iva_cuatro'] + $articulo['iva_cinco'];

			$articulo['precio_con_iva'] = $articulo['precio'] * (1 + ($this->iva_total / 100));

			$unique_id = md5($row->repuesto_item);
			$articulo["unique_id"] = $unique_id;

			$_SESSION["car$carrito"]["$unique_id"] = $articulo;

			$this->updateCarrito("$carrito");

			$this->updatePrecioCantidad("$carrito");

		}

	}

	public function getPagos()
	{
		if ($this->ci->session->userdata('pagos_servicio')) {
			return $this->ci->session->userdata('pagos_servicio');
		} else {
			$this->ci->session->set_userdata('pagos_servicio', null);			
		}
	}

	public function setPago($payment_type, $payment_amount)
	{
		$this->ci->session->userdata('pagos_servicio');

		$payments_data = $this->getPagos();

		$payment = [
			'payment_type' => $payment_type,
			'payment_amount' => $payment_amount,
			'payment_date' => date('Y-m-d H:i:s'),
			'truncated_card' => '',
			'card_issuer' => '',
		];

		$payments_data[] = $payment;
		
		$this->ci->session->set_userdata('pagos_servicio', $payments_data);

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
		$this->ci->session->set_userdata('pagos_servicio', array_values($payments));			
	}

	public function destroy($carrito)
	{
		unset($_SESSION["car$carrito"]);
		$this->carrito = null;
		return true;
	}


	/*
	public function getIvasGenerales($item)
	{
		$subtotal = 0;

		$carrito = $this->getContenidoCarrito();
		var_dump($carrito);

		if ($carrito != "") {

			if (!isset($_SESSION["subtotal_items"])) {
				$_SESSION["subtotal_items"] = null;
			}

			$articulos_iva_especial = $this->getIvaProductos();

			if ($articulos_iva_especial == null) {

				$_SESSION['subtotal_items'] = 0;

				foreach ($carrito as $row) {

					$_SESSION['subtotal_items'] += $row['subtotal'];

				}

				$data = [];
				$iva = [];

				$location = $this->ci->Employee->get_logged_in_employee_current_location_id();

				$row_location_principal = $this->getLocationPrincipal("$location");

				if ($row_location_principal->default_tax_1_rate != "") {

					$iva[0] = ($row_location_principal->default_tax_1_rate != "") ? $row_location_principal->default_tax_1_rate : 0;
					$iva[1] = ($row_location_principal->default_tax_2_rate != "") ? $row_location_principal->default_tax_2_rate : 0;
					$iva[2] = ($row_location_principal->default_tax_3_rate != "") ? $row_location_principal->default_tax_3_rate : 0;
					$iva[3] = ($row_location_principal->default_tax_4_rate != "") ? $row_location_principal->default_tax_4_rate : 0;
					$iva[4] = ($row_location_principal->default_tax_5_rate != "") ? $row_location_principal->default_tax_5_rate : 0;
					$iva['total'] = $_SESSION['subtotal_items'];

					return $iva;

				}

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
					$iva['total'] = $_SESSION['subtotal_items'];

					return $iva;

				}


			} else {

				$valor = array_search($item, array_column($articulos_iva_especial, 'id'));

				if (is_numeric($valor)) {

					if (empty(($_SESSION['subtotal_items']))) {

						$subtotales = 0;

					} else {

						$subtotales = $_SESSION['subtotal_items'];

					}

				} else {

					foreach ($carrito as $value) {

						if ($value['id'] == $item) {

							$_SESSION['subtotal_items'] += $value['precio'];

						}
					}

					$subtotales = $_SESSION['subtotal_items'];

				}

				$data = [];
				$iva = [];

				$location = $this->ci->Employee->get_logged_in_employee_current_location_id();

				$row_location_principal = $this->getLocationPrincipal("$location");

				if ($row_location_principal->default_tax_1_rate != "") {

					$iva[0] = ($row_location_principal->default_tax_1_rate != "") ? $row_location_principal->default_tax_1_rate : 0;
					$iva[1] = ($row_location_principal->default_tax_2_rate != "") ? $row_location_principal->default_tax_2_rate : 0;
					$iva[2] = ($row_location_principal->default_tax_3_rate != "") ? $row_location_principal->default_tax_3_rate : 0;
					$iva[3] = ($row_location_principal->default_tax_4_rate != "") ? $row_location_principal->default_tax_4_rate : 0;
					$iva[4] = ($row_location_principal->default_tax_5_rate != "") ? $row_location_principal->default_tax_5_rate : 0;
					$iva['total'] = $subtotales;

					return $iva;

				}

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
					$iva['total'] = $subtotales;

					return $iva;

				}

			}

		}

	}


	public function deleteIvaGeneral($item)
	{
		$subtotal = 0;
		$carrito = $this->getContenidoCarrito();

		$articulos_iva_especial = $this->getIvaProductos();

		$valor = array_search($item, array_column($articulos_iva_especial, 'id'));

		if (is_numeric($valor)) {

			return false;

		} else {

			echo "Entre donde me intereza";

			foreach ($carrito as $value) {

				if ($value['unique_id'] == $item) {

					$subtotal = $value['subtotal'];

				}
			}



			echo "Subtotal del item = " . $subtotal;
			echo  "subtotal acumulado = " . $_SESSION['subtotal_items'];

			$subtotales = $_SESSION['subtotal_items'] - $subtotal;

		}

		$data = [];
		$iva = [];

		$location = $this->ci->Employee->get_logged_in_employee_current_location_id();

		$row_location_principal = $this->getLocationPrincipal("$location");

		if ($row_location_principal->default_tax_1_rate != "") {

			$iva[0] = ($row_location_principal->default_tax_1_rate != "") ? $row_location_principal->default_tax_1_rate : 0;
			$iva[1] = ($row_location_principal->default_tax_2_rate != "") ? $row_location_principal->default_tax_2_rate : 0;
			$iva[2] = ($row_location_principal->default_tax_3_rate != "") ? $row_location_principal->default_tax_3_rate : 0;
			$iva[3] = ($row_location_principal->default_tax_4_rate != "") ? $row_location_principal->default_tax_4_rate : 0;
			$iva[4] = ($row_location_principal->default_tax_5_rate != "") ? $row_location_principal->default_tax_5_rate : 0;
			$iva['total'] = $subtotales;

			return $iva;

		}

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
			$iva['total'] = $subtotales;

			return $iva;

		}

	}
	*/

}