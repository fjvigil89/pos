<?php 
class Change_house extends CI_Model
{
    function actualiza_item($sale_id,$item_id,$line,$data){
        $this->db->where('sale_id', $sale_id);
        $this->db->where('item_id', $item_id);
		$this->db->where('line', $line);		
		$_return = $this->db->update('sales_items', $data);

		$this->db->query("COMMIT");
		
        return $_return ;
    }
    function get_info($sale_id,$item_id,$line,$employee_id=false){
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();

        $this->db->select("sales_items.*,sales.*,name");		
            $this->db->from('sales');
            $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
			$this->db->join('items', 'sales_items.item_id = items.item_id');

			$this->db->where('sales.deleted',0);           
            $this->db->where('sales.sale_id',$sale_id);
            $this->db->where('line',$line);
            $this->db->where('phppos_sales_items.item_id',$item_id);
            if($employee_id!=false){
                $this->db->where('sold_by_employee_id',$employee_id);
            }
            $query= $this->db->get();
            if($query->num_rows()==1)
		    {
			    return $query->row();
		    }
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$obj=new stdClass();
			
			//Get all the fields from employee table
            $fields = $this->db->list_fields('sales');
            $fields2 = $this->db->list_fields('sales_items');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$obj->$field='';
            }
            foreach ($fields2 as $field)
			{
                if($field=="item_cost_price"){
                    $obj->$field='0';
                }
               else if($field=="item_unit_price"){
                    $obj->$field='0';
                }
                else if($field=="tasa"){
                    $obj->$field='1';
                }
               
				$obj->$field='';
			}
			
			return $obj;
		}
    }
	function search($search, $estado = false, $limit=20,$offset=0,$column='sale_time',
	$orderby='asc',$employee_id=false,$start_date="",$end_date="")
	{
		$CI =& get_instance();	
		
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
			//$search_terms_array=explode(" ", $this->db->escape_like_str($search));
	
			//to keep track of which search term of the array we're looking at now	
			$search_name_criteria_counter=0;
			//$sql_search_name_criteria = '';
			//loop through array of search terms
			
			// se arma la condicion ----------------------------------------
			$cadena="( 
			numero_documento LIKE '%".$this->db->escape_like_str($search)."%' or ".
			"titular_cuenta LIKE '%".$this->db->escape_like_str($search)."%' or ";
			
            $cadena=$cadena."numero_cuenta LIKE '%".$this->db->escape_like_str($search)."%' or ";
            $cadena=$cadena."transaction_status LIKE '%".$this->db->escape_like_str($search)."%'  ) ";

			//----------------------------------------------------------------

            $this->db->select('invoice_number,sale_time,first_name,last_name,
            opcion_sale,divisa,quantity_purchased ,'.$this->db->dbprefix('sales_items').'.file_id,
             quantity_purchased, item_unit_price,item_cost_price,
             numero_cuenta,numero_documento,titular_cuenta,
             tasa,tipo_documento,transaction_status, sales.sale_id,'.$this->db->dbprefix('sales_items').
			 '.item_id,line,name,observaciones,celular');		
            $this->db->from('sales');
            $this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
			$this->db->join('items', 'sales_items.item_id = items.item_id');
			$this->db->join('employees', 'employees.person_id = sales.sold_by_employee_id');
			$this->db->join('people', 'people.person_id = employees.person_id');
			$this->db->where('sales.deleted',0);
			$this->db->where('sales.suspended',0);
			$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") >=', $start_date)
			->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") <=', $end_date);
            if($employee_id!=false){
                $this->db->where('sold_by_employee_id',$employee_id);
            }
            $this->db->where($cadena);
			
			if ($estado)
			{
				$this->db->where('transaction_status', $estado);
			}
				
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}
	
    function search_count_all($search, $estado = FALSE, $employee_id=false,$start_date="",$end_date="")
	{
       

        $current_location=$this->Employee->get_logged_in_employee_current_location_id();
       		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
	   
		$this->db->where('sales.deleted',0);
        $this->db->where('sales.suspended',0);			
		$this->db->where("(numero_cuenta LIKE '%".$this->db->escape_like_str($search)."%' or 
            numero_documento LIKE '%".$this->db->escape_like_str($search)."%' or
            titular_cuenta LIKE '%".$this->db->escape_like_str($search)."%' or 
			transaction_status LIKE '%".$this->db->escape_like_str($search)."%') ");
			
			$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") >=', $start_date)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") <=', $end_date);
		if ($estado)
		{
			$this->db->where('transaction_status', $estado);
        }
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
		$result=$this->db->get();				
		return $result->num_rows();
	}
    function get_search_suggestions($search,$limit=25, $employee_id=false)
	{
    
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->select('invoice_number');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
        $this->db->where('sales.deleted',0);
        $this->db->where('sales.suspended',0);
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
        $this->db->like('invoice_number', $search);        
		$suggestions = array();
        $this->db->limit($limit);
		$by_invoice_number = $this->db->get();
		$temp_suggestions = array();
		foreach($by_invoice_number->result() as $row)
		{
			$temp_suggestions[] = $row->invoice_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		$this->db->select('numero_cuenta');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
        $this->db->where('sales.deleted',0);
        $this->db->where('sales.suspended',0);
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
        $this->db->like('numero_cuenta', $search);        
		$suggestions = array();
        $this->db->limit($limit);
		$by_numero_cuenta = $this->db->get();
		foreach($by_numero_cuenta->result() as $row)
		{
			$temp_suggestions[] =$row->numero_cuenta;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
        
        $this->db->select('numero_documento');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
        $this->db->where('sales.deleted',0);
        $this->db->where('sales.suspended',0);
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
        $this->db->like('numero_documento', $search);        
		$suggestions = array();
        $this->db->limit($limit);
		$by_numero_documento = $this->db->get();
		foreach($by_numero_documento->result() as $row)
		{
			$temp_suggestions[] =$row->numero_documento;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
        
        $this->db->select('titular_cuenta');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
        $this->db->where('sales.deleted',0);
        $this->db->where('sales.suspended',0);
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
        $this->db->like('titular_cuenta', $search);        
		$suggestions = array();
        $this->db->limit($limit);
		$by_titular_cuenta = $this->db->get();
		foreach($by_titular_cuenta->result() as $row)
		{
			$temp_suggestions[] =$row->titular_cuenta;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		
		
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	function last_orders($limit=10000, $offset=0,$col='transaction_status',$order='',$employee_id=false,$start_date="")
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
		$this->db->select('sale_time,invoice_number,
			opcion_sale,divisa,quantity_purchased ,'.$this->db->dbprefix('sales_items').'.file_id,
			quantity_purchased, item_unit_price,item_cost_price,
			numero_cuenta,numero_documento,titular_cuenta,
			tasa,tipo_documento,transaction_status,sales.sale_id,'.$this->db->dbprefix('sales_items').
			'.item_id,line,name,observaciones,celular,first_name,last_name');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
		$this->db->join('items', 'sales_items.item_id = items.item_id');
		$this->db->join('employees', 'employees.person_id = sales.sold_by_employee_id');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('sales.deleted',0);
		$this->db->where('sales.suspended',0);
		$this->db->where('sale_time >=', $start_date);

        if($employee_id != false){
            $this->db->where('sold_by_employee_id',$employee_id);
		}
		
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

    function get_all($limit=10000, $offset=0,$col='transaction_status',$order='',$employee_id=false,$start_date="",$end_date="")
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->select('sale_time,invoice_number,
        opcion_sale,divisa,quantity_purchased ,'.$this->db->dbprefix('sales_items').'.file_id,
         quantity_purchased, item_unit_price,item_cost_price,
         numero_cuenta,numero_documento,titular_cuenta,
		 tasa,tipo_documento,transaction_status,sales.sale_id,'.$this->db->dbprefix('sales_items').
		 '.item_id,line,name,observaciones,celular,first_name,last_name');		
		$this->db->from('sales');
		$this->db->join('sales_items', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
		$this->db->join('items', 'sales_items.item_id = items.item_id');
		$this->db->join('employees', 'employees.person_id = sales.sold_by_employee_id');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('sales.deleted',0);
		$this->db->where('sales.suspended',0);
		$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") >=', $start_date)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") <=', $end_date);
		
        if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
        }
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	

    function count_all($employee_id=false,$start_date="",$end_date="")
	{
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->from('sales_items');
        $this->db->join('sales', 'sales_items.sale_id = sales.sale_id and location_id = '.$current_location);
		$this->db->where('deleted',0);
		if($employee_id!=false){
            $this->db->where('sold_by_employee_id',$employee_id);
		}
		$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") >=', $start_date)
			->where('FROM_unixtime(UNIX_TIMESTAMP(sale_time),"%Y-%m-%d") <=', $end_date);
		return $this->db->count_all_results();
	}
	public function get_rate_all(){

		$this->db->from('rates');
		$this->db->where('deleted',0);
		$query = $this->db->get();
		return $query;
		
	}
	public function get_rate_by_id($id_rate){
		$this->db->from('rates');
		$this->db->where('id_rate',$id_rate);
		$this->db->where('deleted',0);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
		
			$obj=new stdClass();
			$fields = $this->db->list_fields('rates');
			
			
			foreach ($fields as $field)
			{
				$obj->$field='1';
			}
			
			return $obj;
		}
		
	}
	public function update_by_id($id_rate,$data){
		
		$this->db->where('id_rate',$id_rate);
		return $this->db->update('rates', $data);
		
		
	}
}
?>