<?php
require_once("report.php");
class Detailed_shop extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
                    array('data'=>lang('items_name'), 'align' => 'left'),
		    array('data'=>lang('items_quantity_stock'), 'align' => 'left'),
		    array('data'=>lang('items_quantity_warehouse'), 'align'=>'left'),
		    array('data'=>lang('items_quantity_defect'), 'align'=>'left'),
		    array('data'=>lang('items_quantity_total'), 'align'=>'left'));
			
	}
	
	public function getData($start = FALSE, $limit = FALSE)
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		               
        if($start !== FALSE && $limit !== FALSE)
        {
            $this->db->limit($limit,$start);
        }                
                
		$this->db->from('location_items');
		//$this->db->join('locations','locations.location_id=location_items.location_id');
		$this->db->join('items','items.item_id=location_items.item_id');
		$this->db->where('location_id', $this->params['id']);
		$this->db->where('items.deleted', 0);
		$this->db->where('location_items.quantity >', 0);
		//$this->db->where('location_items.quantity_warehouse ', 0);
		return $this->db->get()->result_array();
	}
	
	function getTotalRows()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->from('location_items');
		//$this->db->join('locations','locations.location_id=location_items.location_id');
		$this->db->join('items','items.item_id=location_items.item_id');
		$this->db->where('location_id', $this->params['id']);
		$this->db->where('items.deleted', 0);
		$this->db->where('location_items.quantity >', 0);
		//$this->db->where('location_items.quantity_warehouse ', 0);
	
		return $this->db->count_all_results();
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>