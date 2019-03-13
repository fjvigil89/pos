<?php
require_once("report.php");
class Specific_customer_store_account extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_id'), 'align'=>'left'),
		array('data'=>lang('reports_time'), 'align'=> 'left'),
		array('data'=>lang('reports_debit'), 'align'=> 'left'),
		array('data'=>lang('reports_credit'), 'align'=> 'left'),
		array('data'=>lang('reports_balance'), 'align'=> 'left'));
		
	}
	
	public function getData()
	{
		$this->db->from('store_accounts');
		if ($this->params['customer_id'] != null && $this->params['customer_id'] != "-1")
			$this->db->where('customer_id',$this->params['customer_id']);
		if ($this->params['start_date'] != null && $this->params['end_date'] != null)	
			$this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
			
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}

		$result = $this->db->get()->result_array();
		
		return $result;
		
		for ($k=0;$k<count($result);$k++)
		{
			$item_names = array();
			$petty_cash_id= $result[$k]['petty_cash_id'];
			$this->db->select('*');
			$this->db->from('petty_cash');
			$this->db->join('petty_cash_id', 'petty_cash.petty_cash_id = Customer.customer_id');
			$this->db->where('petty_cash_id', $petty_cash_id);	
			
		}
	return $item_names;
		
	}
	
	public function getTotalRows()
	{
		$this->db->from('store_accounts');
		$this->db->where('customer_id',$this->params['customer_id']);
		$this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"');
		return $this->db->count_all_results();
	}
	
	
	public function getSummaryData()
	{

		$summary_data=array(lang('reports_balance_to_pay')=>$this->Customer->get_info($this->params['customer_id'])->balance);
		return $summary_data;
	}
}
?>