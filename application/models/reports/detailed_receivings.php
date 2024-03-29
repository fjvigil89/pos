<?php
require_once("report.php");
class Detailed_receivings extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array('summary' => array(array('data'=>lang('reports_receiving_id'), 'align'=>'left'), array('data'=>lang('reports_date'), 'align'=>'left'), array('data'=>lang('reports_items_received'), 'align'=>'left'), array('data'=>lang('reports_received_by'), 'align'=>'left'), array('data'=>lang('reports_supplied_by'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'right'), array('data'=>lang('reports_payment_type'), 'align'=>'left'), array('data'=>lang('reports_comments'), 'align'=>'left')),
					'details' => array(array('data'=>lang('reports_name'), 'align'=>'left'),array('data'=>lang('items_product_id'), 'align'=> 'left'), array('data'=>lang('reports_category'), 'align'=>'left'),array('data'=>lang('items_size'), 'align'=>'left'),array('data'=>lang('reports_items_received'), 'align'=>'left'), array('data'=>lang('reports_subtotales'), 'align'=>'right'), array('data'=>lang('reports_items_taxes'),'align'=>'left'),array('data'=>lang('reports_total'),'align'=>'left'),array('data'=>lang('reports_discount'), 'align'=>'left'))
		);		
	}
	
	public function getData()
	{
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();
	
		$this->db->select('receiving_id, receiving_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(supplier.company_name, " (",people.first_name," ",people.last_name, ")") as supplier_name, sum(total) as total, sum(profit) as profit, payment_type, comment', false);
		$this->db->from('receivings_items_temp');
		$this->db->join('people as employee', 'receivings_items_temp.employee_id = employee.person_id');
		$this->db->join('suppliers as supplier', 'receivings_items_temp.supplier_id = supplier.person_id', 'left');
		$this->db->join('people as people', 'people.person_id = supplier.person_id', 'left');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		if( (int)$this->params['supplier_id'] > 0)
		{
			$this->db->where('supplier_id',  $this->params['supplier_id']);
		}
		$this->db->where('receivings_items_temp.deleted', 0);
		$this->db->group_by('receiving_id');
		$this->db->order_by('receiving_date');

		//If we are exporting NOT exporting to excel make sure to use offset and limit
		if (isset($this->params['export_excel']) && !$this->params['export_excel'])
		{
			$this->db->limit($this->report_limit);
			$this->db->offset($this->params['offset']);
		}		
		
		foreach($this->db->get()->result_array() as $receiving_summary_row)
		{
			$data['summary'][$receiving_summary_row['receiving_id']] = $receiving_summary_row; 
		}
		
		$receiving_ids = array();
		
		foreach($data['summary'] as $receiving_row)
		{
			$receiving_ids[] = $receiving_row['receiving_id'];
		}

		$this->db->select('name, receiving_id, receiving_date, items.item_id,items.category, quantity_purchased, serialnumber,total, discount_percent,items.product_id, items.size', false);
		$this->db->from('receivings_items_temp');
		$this->db->join('items', 'receivings_items_temp.item_id = items.item_id');

		if (!empty($receiving_ids))
		{
			$this->db->where_in('receiving_id', $receiving_ids);
		}
		else
		{
			$this->db->where('1', '2', FALSE);		
		}

		foreach($this->db->get()->result_array() as $receiving_item_row)
		{
			$data['details'][$receiving_item_row['receiving_id']][] = $receiving_item_row;
		}

		return $data;
	}
	
	public function getTotalRows()
	{		
		$this->db->select("COUNT(DISTINCT(receiving_id)) as receiving_count");
		$this->db->from('receivings_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		if( (int)$this->params['supplier_id'] > 0)
		{
			$this->db->where('supplier_id',  $this->params['supplier_id']);
		}
		$this->db->where('receivings_items_temp.deleted', 0);
		$ret = $this->db->get()->row_array();
		return $ret['receiving_count'];

	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(total) as total', false);
		$this->db->from('receivings_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}

 public function getTaxesForItems($receiving_id, $item_id)
	{
		$query = $this->db->query("SELECT name, percent, cumulative, item_unit_price, item_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('receivings_items_taxes').' 
		JOIN '.$this->db->dbprefix('receivings_items'). ' USING(receiving_id, item_id) WHERE '.
		$this->db->dbprefix('receivings_items').'.receiving_id = '.$receiving_id.' and '.
		$this->db->dbprefix('receivings_items').'.item_id = '.$item_id.'');
		
		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['name'].$row['percent']]))
			{
				$taxes_data[$item_id] = array('name' => $row['name'].' ('.$row['percent'] . '%)', 'tax' => 0, 'subtotal' => 0, 'total' => 0);
			}
						
			$taxes_data[$item_id]['subtotal'] += ($subtotal);
			$taxes_data[$item_id]['tax'] += ($tax);
			$taxes_data[$item_id]['total'] += ($subtotal+ $tax);
			return $taxes_data;
			
		}
	
		
	}
}

?>