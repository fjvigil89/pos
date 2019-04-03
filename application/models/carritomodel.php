<?php

class CarritoModel extends CI_Model
{
	function get_respuesto_viejos($support_id){
		$this->db->from('spare_parts');	      
        $this->db->where('id_support',$support_id);
        $query=$this->db->get();
        return  $query->result();
	}
	function eleminar_respueto_por_id($respueto_id,$support_id){        
		$this->db->where('id', $respueto_id);    
		$this->db->where('id_support', $support_id);   
        return  $this->db->delete('spare_parts');   
    }
	/*function get_spare_part_by_support($id_support){
        $this->db->from('spare_parts');		
        $this->db->where('id_support',$id_support);
        $query=$this->db->get();
        return $query;
    }*/
	function get_respuestos($support_id){
		
		$this->db->from('support_cart');
		$this->db->where('id_support',$support_id);
		$this->db->where('deleted',0);
		$result = $this->db->get();
		return $result->result();
	}
	function get_impuestos($support_cart_id,$line){		
		$this->db->from('support_cart_taxes');
		$this->db->where('support_cart_id',$support_cart_id);
		$this->db->where('line',$line);
		$result = $this->db->get();
		return $result->result();
	}
	function agregar_respustos($datos_respustos,$support_id){
		$this->db->query("SET autocommit=0");
		// se hace un borrado lógico
		$this->eliminar_respuestos_por_soporte($support_id);
		$erro=false;
		foreach ($datos_respustos as $line=>$data) {
			$item=array(
				"id_support"=>$support_id,
				"line"=>$data["line"],
				"item_id"=>$data["item_id"],
				"tax_included"=>$data["tax_included"],
				"description"=>$data["description"],
				"serialnumber"=>$data["serialnumber"],
				"quantity"=>$data["quantity"],
				"discount"=>$data["discount"],
				"custom1_subcategory"=>$data["custom1_subcategory"],
				"custom2_subcategory"=>$data["custom2_subcategory"],
				"price"=>$data["price"],
				"id_tier"=>$data["id_tier"],
				"deleted"=>0
			);
			// se consula el id del respusto si exite
			$id_respusto= $this->exite_respuesto($support_id,  $line);
			$this->eliminar_impuestos_por_respuesto($id_respusto, $line);			
			if($id_respusto==0){
				// se guarda y se get el id del respuesto
				$id_respusto= $this->guardar_respusto($item);
				if($id_respusto<=0){
					$erro=true;
				}				
			}else{				
				if(!$this->actulizar_respusto($support_id,$line,$item)){
					$erro=true;
				}	   
			}
			if(!$this->agregar_impuestos($data["ivas"],$id_respusto,$line)){
				$erro=true;
			}
		}
		// se elimina por completos los respusto con borrado lógico
		$this->eliminar_eliminados();
		
		if($erro){
			$this->db->query("ROLLBACK");
		}else{
			$this->db->query("COMMIT");
		}
		$this->db->query("SET autocommit=1");
		return $erro;
        
	}
	function agregar_impuestos($impuestos,$id_respusto,$line){
		foreach ($impuestos as $impuesto) {
			$data=array(
				"support_cart_id"=>$id_respusto,
				"line"=>$line,
				"name"=>$impuesto["name"],
				"percent"=>$impuesto["percent"],
				"cumulative"=>$impuesto["cumulative"]

			);
			if(!$this->guardar_impuesto($data)){
				return false;
			}
		}
		return true;

	}
	function guardar_impuesto($data){
		return $this->db->insert('support_cart_taxes', $data); 
	}

	function actulizar_respusto($support_id,$line,$data){
		$this->db->where('id_support', $support_id);
		$this->db->where('line',$line);
        return $this->db->update('support_cart', $data);
	}
	function guardar_respusto($data){
		$id=0;
		if($this->db->insert('support_cart', $data)){
			$id=$this->db->insert_id();
		}
		return $id; 
	}
	function exite_respuesto($support_id,$line){
		$this->db->from('support_cart');		
        $this->db->where('id_support',$support_id);
		$this->db->where('line',$line);
	    $query=$this->db->get();
        if($query->num_rows() >0) {
            return $query->row()->id;
        }
        return 0;
	}
	
	function eliminar_respuestos_por_soporte($support_id){
		$this->db->where('id_support', $support_id);
		return $this->db->update('support_cart',  array("deleted"=>1));
	}
	function eliminar_eliminados(){
		return $this->db->delete('support_cart',array("deleted"=>1));
					
	}
	function eliminar_impuestos_por_respuesto($support_cart_id){
		return $this->db->delete('support_cart_taxes',array("support_cart_id"=>$support_cart_id));
					
	}
	public function buscarItems($buscar)
	{
		$where = "";
		$buscar = strtoupper("$buscar");
		$arr = explode(' ', $buscar);
		if (is_array($arr)) {
			foreach ($arr as $var) {
				$where .= " AND (UPPER(i.name) Like '%$var%' OR i.item_number Like '%$var%' OR i.product_id Like '%$var%') ";
			}
		} else {
			$where = " AND (UPPER(i.name) Like '%$buscar%' OR i.item_number Like '%$buscar%' OR i.product_id Like '%$buscar%')";
		}
		$res = $this->db->query("SELECT DISTINCT(i.category), i.name, i.item_id, i.cost_price, i.unit_price, i.items_discount, i.costo_tax, i.promo_price, i.promo_quantity FROM phppos_items i WHERE 1=1 $where and i.deleted = 0  ORDER BY i.name LIMIT 5");
		return $res;
	}/*

	public function obtenerItems($item)
	{
		$this->db->select('i.item_id, i.name, i.cost_price, i.unit_price, i.items_discount, i.costo_tax, i.promo_price,  i.promo_quantity');
		$this->db->from('items i');
		$this->db->where('i.item_id', $item);
		$this->db->where('i.deleted', 0);
		$res = $this->db->get();
		return $res->row();
	}

	public function obtenerItemsTaxes($item)
	{
		$this->db->select('t.name as nombreiva, percent, cumulative');
		$this->db->from('items_taxes t');
		$this->db->join('items i', 'i.item_id = t.item_id');
		$this->db->where('t.item_id', $item);
		$res = $this->db->get();
		return $res->result();
	}

	public function obtenerItemsLocationTaxes($item, $tienda)
	{
		$this->db->select('*');
		$this->db->from('location_items_taxes t');
		$this->db->join('location_items i', 'i.item_id = t.item_id');
		$this->db->where('t.item_id', $item);
		$this->db->where('t.location_id', $tienda);
		$res = $this->db->get();
		return $res->result();
	}

	public function obtenerItemsLocationPrincipal($tienda)
	{
		$this->db->select('*');
		$this->db->from('locations t');
		$this->db->where('t.location_id', $tienda);
		$res = $this->db->get();
		return $res->row();
	}

	public function obtenerConfig()
	{
		$this->db->select('*');
		$this->db->from('app_config');
		$res = $this->db->get();
		return $res->result();
	}

	public function guardarCarrito($data)
	{
		return  $this->db->insert('technical_support_repuestos_persona', $data);
	}

	public function getCarritoServcio()
	{
		$this->db->select('*');
		$this->db->from('technical_support_repuestos_persona t');
		$this->db->join('location_items i', 'i.item_id = t.item_id');
		$this->db->where('t.item_id', $item);
		$this->db->where('t.location_id', $tienda);
		$res = $this->db->get();
		return $res->result();
	}

	public function getResp($item)
	{       
		$this->db->select('*');
		$this->db->from('technical_support_repuestos_persona resp');
		$this->db->join('items it', 'it.item_id = resp.repuesto_item');
		$this->db->where('resp.repuesto_item', $item);
		$res = $this->db->get();
		return $res->result();
	}

	public function getRespuestos($id_support)
	{       
		$this->db->select('*');
		$this->db->from('technical_support_repuestos_persona resp');
		$this->db->join('items it', 'it.item_id = resp.repuesto_item');
		$this->db->where('resp.repuesto_support', $id_support);
		$res = $this->db->get();
		return $res->result();
	}

	public function guardarSalesFactura($data_support)
	{       
		if (!$this->db->insert('sales', $data_support)) {             
			return -1;            
		}
		$id_support = $this->db->insert_id();
	}

	public function guardarPaymentsFactura($data_support)
	{       
		if (!$this->db->insert('sales_payments', $data_support)) {             
			return -1;            
		}
		$id_support = $this->db->insert_id();
	}*/

}