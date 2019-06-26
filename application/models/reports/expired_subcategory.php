<?php
require_once("report.php");
class Expired_subcategory extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			array('data'=>lang("items_name"), 'align'=> 'left'), 
			array('data'=>lang("items_expiration_date"), 'align'=> 'left'), 
			array('data'=>$this->config->item("custom_subcategory2_name"), 'align'=> 'left'), 
			array('data'=>lang("items_quantity"), 'align'=> 'left'), 
			array('data'=>lang("locations_shop_name"), 'align'=>'left'),
			
		);		
	}
	
	public function getData()
	{
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		
		$between = 'between ' . $this->db->escape($this->params['start_date'] . ' 00:00:00').' and ' . $this->db->escape($this->params['end_date'] . ' 23:59:59');
		//$this->db->select("registers.name as register_name, open_person.first_name as open_first_name, open_person.last_name as open_last_name, close_person.first_name as close_first_name, close_person.last_name as close_last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");

		$this->db->select("phppos_items.item_id, phppos_items.name, phppos_items_subcategory.expiration_date,
		phppos_items_subcategory.custom1, phppos_items_subcategory.custom2  , phppos_items_subcategory.quantity,
		phppos_items_subcategory.location_id,phppos_locations.name as name_location");

		$this->db->from('items_subcategory');
		$this->db->join('location_items', 'location_items.item_id = items_subcategory.item_id');
		$this->db->join('items', 'items.item_id =location_items.item_id', 'left');
		$this->db->join('locations', 'locations.location_id =  phppos_location_items.location_id ');
		$this->db->where('phppos_items_subcategory.expiration_date ' . $between);
		$this->db->where('phppos_items_subcategory.deleted ', 0);
		$this->db->where('phppos_locations.location_id', $location_id);
		$this->db->where('phppos_items_subcategory.of_low', 0);
		$this->db->where('phppos_items.deleted', 0);
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function getTotalRows()
	{
		$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		
		$between = 'between ' . $this->db->escape($this->params['start_date'] . ' 00:00:00').' and ' . $this->db->escape($this->params['end_date'] . ' 23:59:59');
		//$this->db->select("registers.name as register_name, open_person.first_name as open_first_name, open_person.last_name as open_last_name, close_person.first_name as close_first_name, close_person.last_name as close_last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");

		$this->db->select("phppos_items.item_id, phppos_items.name, phppos_items_subcategory.expiration_date,
		phppos_items_subcategory.custom1, phppos_items_subcategory.custom2  , phppos_items_subcategory.quantity,
		phppos_items_subcategory.location_id,phppos_locations.name as name_location");

		$this->db->from('items_subcategory');
		$this->db->join('location_items', 'location_items.item_id = items_subcategory.item_id');
		$this->db->join('items', 'items.item_id =location_items.item_id', 'left');
		$this->db->join('locations', 'locations.location_id =  phppos_location_items.location_id ');
		$this->db->where('phppos_items_subcategory.expiration_date ' . $between);
		$this->db->where('phppos_items_subcategory.deleted ', 0);
		$this->db->where('phppos_locations.location_id', $location_id);
		$this->db->where('phppos_items_subcategory.of_low', 0);
		$this->db->where('phppos_items.deleted', 0);
		
		
		return $this->db->count_all_results();
	}
	
	
	public function getSummaryData() 
	{	 
		return array();
	}
		
	
}
?>