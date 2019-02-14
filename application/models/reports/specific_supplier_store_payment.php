<?php
require_once("report.php");
class Specific_supplier_store_payment extends Report
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
		$this->db->select("receiving_id as num,mount as monto,comment,receiving_time as fecha");
		$this->db->from('receivings');
		$this->db->where('supplier_id',$this->params['supplier_id']);
		$this->db->where('deleted',0);
		$this->db->where('credit',1);
		$query1 = $this->db->get()->result();
		
		$this->db->select("sno as num,(transaction_amount*-1) as monto,comment,date as fecha");
		$this->db->from('store_payments');
		$this->db->where('supplier_id',$this->params['supplier_id']);
		$this->db->where('abono',1);
		$query2 = $this->db->get()->result();

		
		
		/* if ($this->params['start_date'] != null && $this->params['end_date'] != null)	
			$this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"'); */
			
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		/* if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		} */
		$result = array_merge($query1, $query2);
/* var_export ($query); */
		/* $result = $this->db->get()->result_array(); */
		/* var_dump($this->params['supplier_id']); */
		
		return $result;
		
		/* for ($k=0;$k<count($result);$k++)
		{
			$item_names = array();
			$pay_cash_id= $result[$k]['pay_cash_id'];
			$this->db->select('*');
			$this->db->from('pay_cash');
			$this->db->join('pay_cash_id', 'pay_cash.pay_cash_id = Supplier.supplier_id');
			$this->db->where('pay_cash_id', $pay_cash_id);	
			
		}
	return $item_names; */
		
	}
	
	public function getTotalRows()
	{
		$this->db->from('store_payments');
		$this->db->where('supplier_id',$this->params['supplier_id']);
		/* $this->db->where('date BETWEEN "'.$this->params['start_date'].'" and "'.$this->params['end_date'].'"'); */
		return $this->db->count_all_results();
	}
	
	
	public function getSummaryData()
	{

		$summary_data=array(lang('reports_balance_to_pay')=>$this->Supplier->get_info($this->params['supplier_id'])->balance);
		return $summary_data;
	}
}
?>