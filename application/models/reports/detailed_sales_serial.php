<?php
require_once("report.php");
class Detailed_sales_serial extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		$return = array();
		
	
		$return['summary'] = array();
		$return['summary'][] = array('data'=>lang('reports_sale_id'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_invoice_type'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_invoice_number'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_date'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_register'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_items_purchased'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_by'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_to'), 'align'=> 'left');		
		$return['summary'][] = array('data'=>lang('reports_subtotal'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_total'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_tax'), 'align'=> 'right');
				
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['summary'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');
		}
		$return['summary'][] = array('data'=>lang('reports_payment_type'), 'align'=> 'right');
		$return['summary'][] = array('data'=>lang('reports_comments'), 'align'=> 'right');

		$return['details'] = array();
		$return['details'][] = array('data'=>lang('reports_item_number'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('items_product_id'), 'align'=> 'left');
		if($this->params['export_excel']==0)
		{
		$this->config->item('show_image')==1?$return['details'][] = array('data'=>lang('reports_imagen'), 'align'=> 'left'):false;
		}
    	$return['details'][] = array('data'=>lang('reports_name'), 'align'=> 'left');
	   	$return['details'][] = array('data'=>lang('reports_category'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('items_size'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_serial_number'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_description'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_subtotal'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('reports_total'), 'align'=> 'right');
		$return['details'][] = array('data'=>lang('reports_tax'), 'align'=> 'right');
		if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			$return['details'][] = array('data'=>lang('reports_profit'), 'align'=> 'right');			
		}
		$return['details'][] = array('data'=>lang('reports_discount'), 'align'=> 'right');			
		
		return $return;
	}
	
public function getData($person_id=-1,$flag=FALSE)
	{
	 
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();

			
		$this->db->select('sale_id, sale_time, sale_date, register_name, sum(quantity_purchased) as items_purchased, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(employee.first_name," ",employee.last_name) as employee_name, customer.person_id as customer_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, is_invoice,ticket_number,invoice_number,comment', false);
		$this->db->from('sales_items_temp');
          
		$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
		
        if($flag==TRUE)
        {
		$this->db->join('people as sold_by_employee', 'sales_items_temp.sold_by_employee_id = sold_by_employee.person_id', 'left');

        }
        else
        {
        		$this->db->join('people as sold_by_employee', 'sales_items_temp.sold_by_employee_id = sold_by_employee.person_id', 'left')->where('sold_by_employee.person_id',$person_id);
        }
		$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}

		if ($this->params['sale_ticket'] == '1')
		{
			$this->db->where('is_invoice', 1);
		}
		elseif($this->params['sale_ticket'] == '0')
		{
			$this->db->where('is_invoice', 0);
		}
		
		if(isset($this->params['number_serial'])){
			if($this->params['number_serial']!=="all" ){
				$this->db->where('serialnumber', $this->params['number_serial']);

			}else{
			  
			   $this->db->where('serialnumber !=', '');

			}
	   } 
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		$this->db->order_by('sale_time');
		
		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}		
		
		foreach($this->db->get()->result_array() as $sale_summary_row)
		{
			$data['summary'][$sale_summary_row['sale_id']] = $sale_summary_row; 
		}
		
		$sale_ids = array();
		
		foreach($data['summary'] as $sale_row)
		{
			$sale_ids[] = $sale_row['sale_id'];
		}
		
		$this->db->select('sale_id, sale_time, sale_date, item_number, items.product_id as item_product_id,item_kits.product_id as item_kit_product_id, item_kit_number, items.name as item_name, items.image_id as item_image_id,item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent, items.size, ', false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
		$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
		if(isset($this->params['number_serial'])){
			if($this->params['number_serial']!=="all" ){
				$this->db->where('serialnumber', $this->params['number_serial']);

			}else{
			  
			   $this->db->where('serialnumber !=' , '');

			}
	   } 
		if (!empty($sale_ids))
		{
			$this->db->where_in('sale_id', $sale_ids);
		}
		else
		{
			$this->db->where('1', '2', FALSE);		
		}
		
		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			$data['details'][$sale_item_row['sale_id']][] = $sale_item_row;
		}
		
		return $data;
	}
	
	public function getTotalRows()
	{
		$this->db->select("COUNT(DISTINCT(sale_id)) as sale_count");
		$this->db->from('sales_items_temp');
		if(isset($this->params['number_serial'])){
			if($this->params['number_serial']!=="all" ){
				$this->db->where('serialnumber', $this->params['number_serial']);

			}else{
			  
			   $this->db->where('serialnumber !=', '');

			}
	   } 
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('sales_items_temp.deleted', 0);

		$ret = $this->db->get()->row_array();
		return $ret['sale_count'];
	}
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit', false);
		$this->db->from('sales_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		if ($this->config->item('hide_store_account_payments_from_report_totals'))
		{
			$this->db->where('store_account_payment', 0);
		}
		if(isset($this->params['number_serial'])){
			if($this->params['number_serial']!=="all" ){
				$this->db->where('serialnumber', $this->params['number_serial']);

			}else{
			  
			   $this->db->where('serialnumber !=', '');

			}
	   } 
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		
		$return = array(
			'subtotal' => 0,
			'total' => 0,
			'tax' => 0,
			'profit' => 0,
		);
		
		foreach($this->db->get()->result_array() as $row)
		{
			$return['subtotal'] += to_currency_no_money($row['subtotal'],2);
			$return['total'] += to_currency_no_money($row['total'],2);
			$return['tax'] += to_currency_no_money($row['tax'],2);
			$return['profit'] += to_currency_no_money($row['profit'],2);
		}
		
		if(!$this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			unset($return['profit']);
		}
		return $return;
	}
}
?>