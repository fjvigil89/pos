<?php
require_once("report.php");
class Store_payment_statements extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array();	
	}
	
	public function getData()
	{
		$return = array();
		
		$supplier_ids_for_report = array();
		$supplier_id = $this->params['supplier_id'];
		
		if ($supplier_id == -1)
		{
			$this->db->distinct();
			$this->db->select('store_payments.supplier_id');
			$this->db->from('store_payments');
			$this->db->join('suppliers', 'suppliers.person_id = store_payments.supplier_id');
			$this->db->join('pay_cash', 'pay_cash.pay_cash_id = store_payments.pay_cash_id');

			$this->db->where('suppliers.balance !=', 0);
			$this->db->where('suppliers.deleted',0);
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
			$result = $this->db->get()->result_array();
			
			foreach($result as $row)
			{
				$supplier_ids_for_report[] = $row['supplier_id'];
			}
		}
		else
		{
			$this->db->select('person_id');
			$this->db->from('suppliers');
			$this->db->where('balance !=', 0);
			$this->db->where('person_id', $supplier_id);
			$this->db->where('deleted',0);
			
			$result = $this->db->get()->row_array();
			
			if (!empty($result))
			{
				$supplier_ids_for_report[] = $result['person_id'];
			}
		}
				
		foreach($supplier_ids_for_report as $supplier_id)
		{
			$this->db->from('store_payments');
			$this->db->where('store_payments.supplier_id', $supplier_id);
			$this->db->join('pay_cash', 'pay_cash.pay_cash_id = store_payments.pay_cash_id');
			
			if ($this->params['pull_payments_by'] == 'payment_date')
			{
				$this->db->where('date >=', $this->params['start_date']);
				$this->db->where('date <=', $this->params['end_date']. '23:59:59');				
				$this->db->order_by('date');
			}
			else
			{
				$this->db->where('pay_cash_time >=', $this->params['start_date']);
				$this->db->where('pay_cash_time <=', $this->params['end_date']. '23:59:59');
				$this->db->order_by('pay_cash_time');
			}
			
			
			$result = $this->db->get()->result_array();
			

			//If we don't have results from this month, pull the last store payment entry we have
			if (count($result) == 0)
			{
				$this->db->from('store_payments');
				$this->db->where('store_payments.supplier_id', $supplier_id);
				$this->db->join('pay_cash', 'pay_cash.pay_cash_id = store_payments.pay_cash_id');
				$this->db->limit(1);
				if ($this->params['pull_payments_by'] == 'payment_date')
				{
					$this->db->order_by('date', 'DESC');
				}
				else
				{
					$this->db->order_by('pay_cash', 'DESC');
				}
			
				$this->db->limit(1); 	
				$result = $this->db->get()->result_array();
				
			}
			
		
			$return[]= array('supplier_info' => $this->Supplier->get_info($supplier_id),'store_payment_transactions' => $result);
		}
		
		return $return;
	}
	
	public function getTotalRows()
	{
		$supplier_id = $this->params['supplier_id'];
		
		if ($supplier_id == -1)
		{
			$this->db->distinct();
			$this->db->select('store_payments.supplier_id');
			$this->db->from('store_payments');
			$this->db->join('pay_cash', 'pay_cash.pay_cash_id = store_payments.pay_cash_id');
			$this->db->where('balance !=', 0);
		}
		else
		{
			$this->db->distinct();
			$this->db->select('store_payments.supplier_id');
			$this->db->from('store_payments');
			$this->db->join('pay_cash', 'pay_cash.pay_cash_id = store_payments.pay_cash_id');
			$this->db->where('store_payments.supplier_id', $supplier_id);
			$this->db->where('balance !=', 0);
		}
		
		return $this->db->get()->num_rows();
	}
	
	
	public function getSummaryData()
	{
		return array();
	}
}
?>