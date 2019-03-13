<?php
require_once("report.php");
class Detailed_daily_cut extends Report
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
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();
		
		$total = 0;
		
		$data = array();
		
		$sales_totals = array();
		$this->db->select('sale_id, sum(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->where('total >= 0');
		$this->db->group_by('sale_id');
			
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
		}
		$dbprefix_sales_payments= $this->db->dbprefix('sales_payments');
		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('date(sale_time) BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']));
		
		//We only want sales, we don't want negative transactions
		$this->db->where('payment_amount > 0');
		$this->db->where($dbprefix_sales_payments.'.payment_type != ',lang("sales_store_account"));
		$this->db->where('SUBSTRING('.$dbprefix_sales_payments.'.payment_type,1,'.strlen(lang("sales_giftcard")) .') != '.$this->db->escape(lang("sales_giftcard")));

		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		$this->db->where('location_id', $location_id);
		
		$this->db->order_by('sale_id, payment_type');
		$sales_payments = $this->db->get()->result_array();
		
		$payments_by_sale = array();
		
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = array();
		$total_sales=0;
		foreach($payments_by_sale as $sale_id => $payment_rows)
		{
			if(isset($sales_totals[$sale_id])){
				$total_sale_balance = $sales_totals[$sale_id];
			}
			
			foreach($payment_rows as $row)
			{
				$payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;
				
				if (!isset($payment_data[$row['payment_type']]))
				{
					$payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0 );
				}
				
				if ($total_sale_balance != 0)
				{
					$payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
				}
				$total_sales+=$payment_amount;
				$total_sale_balance-=$payment_amount;
			}
		}
				
		$data['sales_by_payments'] = $payment_data;
		$this->db->select('sum(payment_amount) as total_pago,'.$this->db->dbprefix('petty_cash_payments').'.payment_type', false);
		$this->db->from('petty_cash_payments');
		$this->db->join('petty_cash', 'petty_cash.petty_cash_id=petty_cash_payments.petty_cash_id',"left");
		$this->db->where('date(petty_cash_time) BETWEEN '. $this->db->escape($this->params['start_date']). ' and '. $this->db->escape($this->params['end_date']));
		$this->db->where($this->db->dbprefix('petty_cash').'.deleted',0);		
		$this->db->where($this->db->dbprefix('petty_cash').'.location_id',$location_id);	
		$this->db->where('SUBSTRING('.$this->db->dbprefix('petty_cash_payments').'.payment_type,1,'.strlen(lang("sales_giftcard")) .') != '.$this->db->escape(lang("sales_giftcard")));
	
		$this->db->group_by($this->db->dbprefix('petty_cash_payments').'.payment_type');
		$petty_cash_payments = $this->db->get()->result_array();
		
		$data["petty_cash_payments"]=$petty_cash_payments;		
		$total_petty_cash=0;
		foreach ($petty_cash_payments as $petty_cash) {
			$total_petty_cash+=$petty_cash["total_pago"];
		}	
		$data['total'] = 0;//333;//$total;
	
		
		
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		$this->db->where('type_movement', 1);
		$ret = $this->db->get()->row_array();
		$data["total_ingresos"]= $ret["suma"]==null? 0 : $ret["suma"];	
		
		$this->db->select(' SUM(mount) as suma');
		$this->db->from('movement_items_temp');
		$this->db->where('type_movement', 0);
		$ret = $this->db->get()->row_array();
		$data["total_egresos"]= $ret["suma"]==null? 0 : $ret["suma"];		 
		//$ganacias_neta= $data['profit'] -$data["total_egresos"];
		$data["ganacias_neta"]=0;//$ganacias_neta;	
		$data["total_sales"]=$total_sales;
		$data["total_petty_cash"]=$total_petty_cash;
		$data["sales_abonos"]=$total_sales+$total_petty_cash;
		
		return $data;
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>