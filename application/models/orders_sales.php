<?php
class Orders_sales extends CI_Model
{
    function update($order_sale_id,$data){
        $this->db->where('order_sale_id',$order_sale_id);
        return $this->db->update('orders_sales', $data);

    }
   
    function save($order_sale_id=false, $sale_id=false,$data){
        $this->db->query("SET autocommit=0");
        $exito=true;
        //Lock tables invovled in sale transaction so we don't have deadlock 
       // $this->db->query('LOCK TABLES ' . $this->db->dbprefix('orders_sales') . ' WRITE');

        if(!$order_sale_id){ 
            $data["sale_id"]=$sale_id;
            $data["location_id"]=$this->Employee->get_logged_in_employee_current_location_id();
            $data["number"]=$this->get_next_number($data["location_id"]);
            $data["date"]=date('Y-m-d H:i:s');
            if( ! $this->exists_by_sale_id($sale_id) and $this->db->insert('orders_sales',$data))
			{		  
				$order_sale_id=$this->db->insert_id();		
			}else{
                $this->db->query("ROLLBACK");
                $exito= false;
            }
        }
        $this->db->query("COMMIT");
        //$this->db->query('UNLOCK TABLES');
        return  $exito;

    }
    function get_all($limit=10000, $offset=0,$col='state',$order='',$start_date="",$end_date="")
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->select('orders_sales.*, SUM(quantity_purchased) as items');		
        $this->db->from('orders_sales');        
        $this->db->join('sales', 'sales.sale_id = orders_sales.sale_id  ');
        $this->db->join('sales_items', 'sales.sale_id = sales_items.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
		$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") >=', $start_date)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") <=', $end_date);
		
        $this->db->group_by('order_sale_id'); 
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
    }
    function get_all2($limit=10000, $offset=0,$col='state',$order='',$start_date="",$end_date="")
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->select('orders_sales.*, SUM(quantity_purchased) as items');		
        $this->db->from('orders_sales');        
        $this->db->join('sales', 'sales.sale_id = orders_sales.sale_id  ');
        $this->db->join('sales_items', 'sales.sale_id = sales_items.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        $this->db->where('date BETWEEN "'. $start_date. '" and "'. $end_date.'"');
        $this->db->group_by('order_sale_id'); 
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
    }
    function get_search_suggestions($search,$limit=25)
	{
    
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->select('number');		
		$this->db->from('orders_sales');
		$this->db->join('sales', 'orders_sales.sale_id = sales.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        
        $this->db->like('number', $search);        
		$suggestions = array();
        $this->db->limit($limit);
		$by_number = $this->db->get();
		$temp_suggestions = array();
		foreach($by_number->result() as $row)
		{
			$temp_suggestions[] = $row->number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		$this->db->select('order_sale_id');		
		$this->db->from('orders_sales');
		$this->db->join('sales', 'orders_sales.sale_id = sales.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        
        $this->db->like('order_sale_id', $search);        
		$suggestions = array();
        $this->db->limit($limit);
        $by_id = $this->db->get();
        
		foreach($by_id->result() as $row)
		{
			$temp_suggestions[] =$row->order_sale_id;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
               
        $this->db->select('invoice_number');		
		$this->db->from('orders_sales');
		$this->db->join('sales', 'orders_sales.sale_id = sales.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        
        $this->db->like('invoice_number', $search);        
		$suggestions = array();
        $this->db->limit($limit);
        $by_invoice_number= $this->db->get();

		foreach($by_invoice_number->result() as $row)
		{
			$temp_suggestions[] =$row->invoice_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
        
        $this->db->select('ticket_number');		
		$this->db->from('orders_sales');
		$this->db->join('sales', 'orders_sales.sale_id = sales.sale_id ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        
        $this->db->like('ticket_number', $search);        
		$suggestions = array();
        $this->db->limit($limit);
        $by_ticket_number= $this->db->get();
		foreach($by_ticket_number->result() as $row)
		{
			$temp_suggestions[] =$row->ticket_number;
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
    function count_all($start_date="",$end_date="")
	{
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();
        $this->db->from('orders_sales');
        $this->db->join('sales', 'sales.sale_id = orders_sales.sale_id  ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
		$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") >=', $start_date)
			->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") <=', $end_date);
		return $this->db->count_all_results();
	}
    function search_count_all($search, $estado = FALSE,$start_date="",$end_date="",$current_location=false)
	{
       
        if(!$current_location){
         $current_location=$this->Employee->get_logged_in_employee_current_location_id();
        }
       		
		$this->db->from('orders_sales');
		$this->db->join('sales', 'sales.sale_id = orders_sales.sale_id  ');
        $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
        $this->db->where('orders_sales.deleted',0);
        $this->db->where($this->db->dbprefix('sales').".deleted",0);
        
        	
		$this->db->where("(number LIKE '%".$this->db->escape_like_str($search)."%' or 
            order_sale_id LIKE '%".$this->db->escape_like_str($search)."%' or 
            ticket_number LIKE '%".$this->db->escape_like_str($search)."%' or 
            invoice_number LIKE '%".$this->db->escape_like_str($search)."%') ");			
			$this->db->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") >=', $start_date)
				 ->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") <=', $end_date);
		if ($estado)
		{
			$this->db->where('orders_sales.state', $estado);
        }
        
		$result=$this->db->get();				
		return $result->num_rows();
	}
    public function get_next_number( $location_id)
    {
        
        $this->db->select_max('number');
        $this->db->from('orders_sales');
        $this->db->where('location_id', $location_id);
        $row = $this->db->get()->row()->number;
        $row++;
        return $row;
    }
    function get_items($sale_id){
        $this->db->from('sales_items');
		$this->db->where('sale_id',$sale_id);
        $result = $this->db->get();        
		return $result->result();
    }
    function exists_by_sale_id($sale_id=false){
        $this->db->from('orders_sales');
		$this->db->where('sale_id',$sale_id);
		$query = $this->db->get();
		return ($query->num_rows()>=1);
    }
    function get_info($order_sale_id=null){
        $this->db->from('orders_sales');
		$this->db->where('order_sale_id',$order_sale_id);
        $result = $this->db->get();        
		return $result->row();
    }
    function search($search, $estado = false, $limit=20,$offset=0,$column='number',
	$orderby='asc',$start_date="",$end_date="")
	{
		$CI =& get_instance();	
		
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
			//$search_terms_array=explode(" ", $this->db->escape_like_str($search));
	
			//to keep track of which search term of the array we're looking at now	
			$search_name_criteria_counter=0;
			//$sql_search_name_criteria = '';
			//loop through array of search terms
			
            // se arma la condicion ----------------------------------------
            
            $current_location=$this->Employee->get_logged_in_employee_current_location_id();
       
        
			$cadena="( 
			number LIKE '%".$this->db->escape_like_str($search)."%' or ".
			"order_sale_id LIKE '%".$this->db->escape_like_str($search)."%' or ";
			
            $cadena=$cadena."ticket_number LIKE '%".$this->db->escape_like_str($search)."%' or ";
            $cadena=$cadena."invoice_number LIKE '%".$this->db->escape_like_str($search)."%'  ) ";
           
			//----------------------------------------------------------------

            $this->db->select('orders_sales.*, SUM(quantity_purchased) as items');		
            $this->db->from('orders_sales');        
            $this->db->join('sales', 'sales.sale_id = orders_sales.sale_id  ');
            $this->db->join('sales_items', 'sales.sale_id = sales_items.sale_id ');
            $this->db->where($this->db->dbprefix('orders_sales').'.location_id',$current_location);
            $this->db->where('orders_sales.deleted',0);
            $this->db->where($this->db->dbprefix('sales').".deleted",0);
            $this->db->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") >=', $start_date)
                     ->where('FROM_unixtime(UNIX_TIMESTAMP(date),"%Y-%m-%d") <=', $end_date);
            $this->db->where($cadena);
			
			if ($estado)
			{
				$this->db->where('state', $estado);
			}
				
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
            $this->db->offset($offset);
            $this->db->group_by('order_sale_id'); 

			return $this->db->get();
	}
}
?>