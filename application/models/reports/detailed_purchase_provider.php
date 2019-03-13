<?php
require_once("report.php");
class Detailed_purchase_provider extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		$return = array();
		
	
		$return['summary'] = array();
		//$return['summary'][] = array('data'=>lang('reports_sale_id'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_date'), 'align'=> 'left');

		$return['summary'][] = array('data'=>lang('reports_invoice_type'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_invoice_number'), 'align'=> 'left');
		$return['summary'][] = array('data'=>"# orden de pedido", 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_items_purchased'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_by'), 'align'=> 'left');
		$return['summary'][] = array('data'=>lang('reports_sold_to'), 'align'=> 'left');		
		$return['summary'][] = array('data'=>"Costo de proveedor", 'align'=> 'right');
		$return['summary'][] = array('data'=>"Precio factura", 'align'=> 'right');
		
		$return['details'] = array();
		$return['details'][] = array('data'=>lang('reports_item_number'), 'align'=> 'left');
		
    	$return['details'][] = array('data'=>lang('reports_name'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left');
		$return['details'][] = array('data'=>lang('items_supplier'), 'align'=> 'left');
		$return['details'][] = array('data'=>"Costo de proveedor", 'align'=> 'right');
		$return['details'][] = array('data'=>"Precio factura", 'align'=> 'right');
		
		
		return $return;
	}
	
	//--- ventas delladas 2 encabezado ---
	

public function getData($person_id=-1,$flag=FALSE)
	{
	 
		$data = array();
		$data['summary'] = array();
		$data['details'] = array();

			
		$this->db->select('sale_id, sale_time, sale_date, register_name, sum(quantity_purchased) as items_purchased, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(sold_by_employee.first_name," ",sold_by_employee.last_name) as sold_by_employee, CONCAT(employee.first_name," ",employee.last_name) as employee_name, customer.person_id as customer_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, is_invoice,ticket_number,invoice_number,comment,total,sum(item_cost_price) as item_cost_price ,sum(item_unit_price) as item_unit_price', false);
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
		$this->db->select('order_sale_id,number,sale_id', false);
		$this->db->from('orders_sales');
		if (!empty($sale_ids))
		{
			$this->db->where_in('sale_id', $sale_ids);
		}
		else
		{
			$this->db->where('1', '2', FALSE);		
		}
		$ordenes = $this->db->get()->result_array();
		$ordenes_por_sales=array();
		foreach($ordenes as $orden=>$value){
			$ordenes_por_sales[$value["sale_id"]]=$value;
		}

		foreach($data['summary'] as $summary_id=>$value)
		{
			if(isset($ordenes_por_sales[$value["sale_id"]])){
				$data['summary'][$summary_id]["number_orden"]=$ordenes_por_sales[$value["sale_id"]]["number"] ; 
			}else{
				$data['summary'][$summary_id]["number_orden"]="No aplicado" ; 

			}
		}

		$this->db->select('sales_items_temp.item_id,sale_id, sale_time, sale_date, item_number, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, serialnumber, total,item_cost_price ,item_unit_price', false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
		$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');		
		
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
		$this->db->distinct();
		$this->db->select('sales_items_temp.item_id', false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');

		$items_tem = $this->db->get()->result_array();
		$item_ids=array();
		
		foreach($items_tem as $item_row)
		{
			$item_ids[] = $item_row['item_id'];
		}	
		$this->db->select('company_name,'.$this->db->dbprefix('items').'.item_id', false);
		$this->db->from('suppliers');
		$this->db->join('items_suppliers', 'suppliers.person_id = items_suppliers.supplier_id', 'left');
		$this->db->join('items', 'items_suppliers.item_id = items.item_id', 'left');
		$this->db->group_by($this->db->dbprefix('items').".item_id");		
		
		if (!empty($sale_ids))
		{
			$this->db->where_in($this->db->dbprefix('items').".item_id", $item_ids);
		}
		else
		{
			$this->db->where('1', '2', FALSE);		
		}
		$proveedores = $this->db->get()->result_array();
		$proveedores_por_items=array();
		foreach($proveedores as $proveedore=>$value){
			$proveedores_por_items[$value["item_id"]]=$value;
		}
		foreach($data['details'] as $details_id=>$value)
		{
			foreach($value as $key=> $item){
				if( isset($item["item_id"]) and isset($proveedores_por_items[$item["item_id"]])){
					$data['details'][$details_id][$key]["company_name_proveedor"]=$proveedores_por_items[$item["item_id"]]["company_name"] ; 
				}else{
					$data['details'][$details_id][$key]["company_name_proveedor"]=""; 

				}
			}
		}

		
		return $data;
	}
	
	public function getTotalRows()
	{
		$this->db->select("COUNT(DISTINCT(sale_id)) as sale_count");
		$this->db->from('sales_items_temp');
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
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit,sum(item_cost_price) as item_cost_price ,sum(item_unit_price) as item_unit_price', false);
		$this->db->from('sales_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		
		
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		
		$return = array(
			'costo_proveedor' => 0,
			'precio_factura' => 0,
			
		);
		
		foreach($this->db->get()->result_array() as $row)
		{
			$return['costo_proveedor'] += to_currency_no_money($row['item_cost_price'],2);
			$return['precio_factura'] += to_currency_no_money($row['item_unit_price'],2);
			
		}
		
		if(!$this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id))
		{
			unset($return['profit']);
		}
		return $return;
	}
}
?>