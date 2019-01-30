<?php
require_once("report.php");
class Store_account_statements extends Report
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
		
		$customer_ids_for_report = array();
		$customer_id = $this->params['customer_id'];
		
		if ($customer_id == -1)
		{
			$this->db->distinct();
			$this->db->select('store_accounts.customer_id');
			$this->db->from('store_accounts');
			$this->db->join('customers', 'customers.person_id = store_accounts.customer_id');
			$this->db->join('petty_cash', 'petty_cash.petty_cash_id = store_accounts.petty_cash_id');

			$this->db->where('customers.balance !=', 0);
			$this->db->where('customers.deleted',0);
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
			$result = $this->db->get()->result_array();
			
			foreach($result as $row)
			{
				$customer_ids_for_report[] = $row['customer_id'];
			}
		}
		else
		{
			$this->db->select('person_id');
			$this->db->from('customers');
			$this->db->where('balance !=', 0);
			$this->db->where('person_id', $customer_id);
			$this->db->where('deleted',0);
			
			$result = $this->db->get()->row_array();
			
			if (!empty($result))
			{
				$customer_ids_for_report[] = $result['person_id'];
			}
		}
				
		foreach($customer_ids_for_report as $customer_id)
		{
			$this->db->from('store_accounts');
			$this->db->where('store_accounts.customer_id', $customer_id);
			$this->db->join('petty_cash', 'petty_cash.petty_cash_id = store_accounts.petty_cash_id');
			
			if ($this->params['pull_payments_by'] == 'payment_date')
			{
				$this->db->where('date >=', $this->params['start_date']);
				$this->db->where('date <=', $this->params['end_date']. '23:59:59');				
				$this->db->order_by('date');
			}
			else
			{
				$this->db->where('petty_cash_time >=', $this->params['start_date']);
				$this->db->where('petty_cash_time <=', $this->params['end_date']. '23:59:59');
				$this->db->order_by('petty_cash_time');
			}
			
			
			$result = $this->db->get()->result_array();
			

			//If we don't have results from this month, pull the last store account entry we have
			if (count($result) == 0)
			{
				$this->db->from('store_accounts');
				$this->db->where('store_accounts.customer_id', $customer_id);
				$this->db->join('petty_cash', 'petty_cash.petty_cash_id = store_accounts.petty_cash_id');
				$this->db->limit(1);
				if ($this->params['pull_payments_by'] == 'payment_date')
				{
					$this->db->order_by('date', 'DESC');
				}
				else
				{
					$this->db->order_by('petty_cash', 'DESC');
				}
			
				$this->db->limit(1); 	
				$result = $this->db->get()->result_array();
				
			}
			
		
			$return[]= array('customer_info' => $this->Customer->get_info($customer_id),'store_account_transactions' => $result);
		}
		
		return $return;
	}
	
	public function getTotalRows()
	{
		$customer_id = $this->params['customer_id'];
		
		if ($customer_id == -1)
		{
			$this->db->distinct();
			$this->db->select('store_accounts.customer_id');
			$this->db->from('store_accounts');
			$this->db->join('petty_cash', 'petty_cash.petty_cash_id = store_accounts.petty_cash_id');
			$this->db->where('balance !=', 0);
		}
		else
		{
			$this->db->distinct();
			$this->db->select('store_accounts.customer_id');
			$this->db->from('store_accounts');
			$this->db->join('petty_cash', 'petty_cash.petty_cash_id = store_accounts.petty_cash_id');
			$this->db->where('store_accounts.customer_id', $customer_id);
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