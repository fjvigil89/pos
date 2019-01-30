<?php
require_once("report.php");
class Defective_item_audit extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		$columns = array();

		$columns[] = array('data'=>lang('items_name'), 'align'=> 'left');
		$columns[] = array('data'=>lang('reports_defective_quantity'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_defective_description'), 'align'=> 'right');
        $columns[] = array('data'=>lang('reports_defective_date'), 'align'=> 'right');
		$columns[] = array('data'=>lang('reports_defective_user'), 'align'=> 'right');

		return $columns;
	}

	public function getData()
	{
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$this->db->select('items.name, defective_audit.quantity, defective_audit.description, defective_audit.timestamp, employees.username');
		$this->db->from('defective_audit');
        $this->db->join('items', 'items.item_id = defective_audit.item_id', 'left');
        $this->db->join('employees', 'employees.id = defective_audit.user_id', 'left');
        $this->db->where('timestamp BETWEEN '.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
        $this->db->where('location_id', $location_id);
        $this->db->limit($this->report_limit);
        $this->db->offset($this->params['offset']);

		return $this->db->get()->result_array();
	}


	function getTotalRows()
	{
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();

        $this->db->select('items.name, defective_audit.quantity, defective_audit.description, defective_audit.timestamp, employees.username');
		$this->db->from('defective_audit');
        $this->db->join('items', 'items.item_id = defective_audit.item_id', 'left');
        $this->db->join('employees', 'employees.id = defective_audit.user_id', 'left');
        $this->db->where('timestamp BETWEEN '.$this->db->escape($this->params['start_date']).' and '.$this->db->escape($this->params['end_date']));
        $this->db->where('location_id', $location_id);
        return $this->db->count_all_results();
	}


	public function getSummaryData()
	{
		return array();
	}

}
?>
