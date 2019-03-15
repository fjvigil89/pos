<?php

class CarritoModel extends CI_Model
{
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
	}

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
	}

}