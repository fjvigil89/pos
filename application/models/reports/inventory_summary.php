<?php
require_once("report.php");
class Inventory_summary extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		
		$columns = array();
		
		$columns[] = array('data'=>lang('reports_item_name'), 'align'=> 'left');
		$columns[] = array('data'=>lang('items_category'), 'align'=> 'right');
		$columns[] = array('data'=>lang('suppliers_supplier'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_item_number'), 'align'=> 'right');
		$columns[] = array('data'=>lang('items_product_id'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_description'), 'align'=> 'right');

		if($this->Employee->has_module_action_permission('reports','show_cost_price',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$columns[] = array('data'=>lang('items_cost_price'), 'align'=> 'right');
		}

		$columns[] = array('data'=>lang('items_unit_price'), 'align'=> 'left');
		$columns[] = array('data'=>lang('reports_count'), 'align'=> 'left');
		$columns[] = array('data'=>lang('reports_reorder_level'), 'align'=> 'left');
		
		return $columns;
	}
	
	public function getData()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->select('name, category, company_name, item_number, product_id, 
		IFNULL('.$this->db->dbprefix('location_items').'.cost_price, '.$this->db->dbprefix('items').'.cost_price) as cost_price, 
		IFNULL('.$this->db->dbprefix('location_items').'.unit_price, '.$this->db->dbprefix('items').'.unit_price) as unit_price,
		quantity, 
		IFNULL('.$this->db->dbprefix('location_items').'.reorder_level, '.$this->db->dbprefix('items').'.reorder_level) as reorder_level, 
		description', FALSE);
		$this->db->from('items');
		$this->db->join('items_suppliers', 'items.item_id = items_suppliers.item_id', 'left outer');
		$this->db->join('suppliers', 'items_suppliers.supplier_id = suppliers.person_id', 'left outer');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->where('items.deleted', 0);
		
		if ($this->params['supplier'] != -1)
		{
			$this->db->where('suppliers.person_id', $this->params['supplier']);
		}
		$this->db->where('is_service !=', 1);
		$this->db->order_by('name');
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}
		
		return $this->db->get()->result_array();

	}
	
	function getTotalRows()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->select('name, category, company_name, item_number, product_id, 
		IFNULL('.$this->db->dbprefix('location_items').'.cost_price, '.$this->db->dbprefix('items').'.cost_price) as cost_price, 
		IFNULL('.$this->db->dbprefix('location_items').'.unit_price, '.$this->db->dbprefix('items').'.unit_price) as unit_price,
		quantity, 
		IFNULL('.$this->db->dbprefix('location_items').'.reorder_level, '.$this->db->dbprefix('items').'.reorder_level) as reorder_level, 
		description', FALSE);
		$this->db->from('items');
		$this->db->join('items_suppliers', 'items.item_id = items_suppliers.item_id', 'left outer');
		$this->db->join('suppliers', 'items_suppliers.supplier_id = suppliers.person_id', 'left outer');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->where('items.deleted', 0);
		
		if ($this->params['supplier'] != -1)
		{
			$this->db->where('suppliers.person_id', $this->params['supplier']);
		}
		$this->db->where('is_service !=', 1);
		$this->db->order_by('name');
		
		return $this->db->count_all_results();
	}
	
	public function getSummaryData()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('sum(IFNULL('.$this->db->dbprefix('location_items').'.cost_price, '.$this->db->dbprefix('items').'.cost_price) * quantity) as inventory_total,
		sum(IFNULL('.$this->db->dbprefix('location_items').'.unit_price, '.$this->db->dbprefix('items').'.unit_price) * quantity) as inventory_sale_total', FALSE);
		$this->db->from('items');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->join('items_suppliers', 'items.item_id = items_suppliers.item_id', 'left outer');
		$this->db->join('suppliers', 'items_suppliers.supplier_id = suppliers.person_id', 'left outer');
		
		$this->db->where('is_service !=', 1);
		$this->db->where('items.deleted', 0);
		
		if ($this->params['supplier'] != -1)
		{
			$this->db->where('suppliers.person_id', $this->params['supplier']);
		}
		return $this->db->get()->row_array();
	}
}
?>