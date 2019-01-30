<?php
$this->load->model('Reports/report');
class detailed_shop extends Report
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	public function getDataColumns()
	{
		return array(
			array('data'=>lang('reports_date'), 'align' => 'left'),
		    $this->config->item('show_image')==1 ? array('data'=>lang('reports_imagen'), 'align' => 'left'):false,
		    array('data'=>lang('reports_item_name'), 'align' => 'left'),
		    array('data'=>lang('items_category'), 'align'=>'left')); 
			
	}
	
	public function getData()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->from('locations');
		$this->db->where('location_id', 1);
		return $this->db->get()->result_array();
	}
	
	function getTotalRows()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->from('locations');
	
		return $this->db->count_all_results();
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>