<?php
class Order extends Person
{	
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	

	/*
	Returns all the customers
	*/
	function get_all($limit=10000, $offset=0,$col='order_id',$order='asc')
	{

		$this->db->select('*');	
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('orders.deleted',0);
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		$data = $this->db->get();


						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('orders');
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('orders.deleted',0);
		$this->db->where('orders.id',$customer_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from customer table
			$fields = $this->db->list_fields('orders');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}

	
	
	/*
	Gets information about multiple customers
	*/
	function get_multiple_info($customer_ids)
	{
		$this->db->from('orders');
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');		
		$this->db->where_in('orders.id',$customer_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a customer
	*/


	function save_sale_order($order_id,$sale_id)
	{
		$customer_data = array('sale_id' => $sale_id,'processed' => 1);
		$this->db->where('order_id', $order_id);
		return $this->db->update('orders',$customer_data);
	}


 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		
		$this->db->where("(".$this->db->dbprefix('orders').".order_id LIKE '%".$this->db->escape_like_str($search)."%' or 
		first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->order_id;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}


		
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('deleted',0);		
		$this->db->like("email",$search);
		$this->db->limit($limit);
		$by_email = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_email->result() as $row)
		{
			$temp_suggestions[] = $row->email;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('deleted',0);		
		$this->db->like("phone",$search);
		$this->db->limit($limit);	
		$by_phone = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_phone->result() as $row)
		{
			$temp_suggestions[]=$row->phone_number;		
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
	
	/*
	Get search suggestions to find customers
	*/
	function get_customer_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or
		CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");			
		
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->last_name.', '.$row->first_name;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');
		$this->db->where('deleted',0);		
		$this->db->like("account_number",$search);
		$this->db->limit($limit);
		$by_account_number = $this->db->get();
		$temp_suggestions = array();
		
		foreach($by_account_number->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->account_number;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');
		$this->db->where('deleted',0);		
		$this->db->like("email",$search);
		$this->db->limit($limit);
		$by_email = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_email->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->email;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
			
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("phone_number",$search);
		$this->db->limit($limit);
		$by_phone_number = $this->db->get();
		
		
		$temp_suggestions = array();
		
		foreach($by_phone_number->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->phone_number;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("company_name",$search);
		$this->db->limit($limit);
		$by_company_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_company_name->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->company_name;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		for($k=count($suggestions)-1;$k>=0;$k--)
		{
			if (!$suggestions[$k]['label'])
			{
				unset($suggestions[$k]);
			}
		}

		
		$suggestions = array_values($suggestions);
		
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	/*
	Preform a search on customers
	*/
	function search($search, $limit=20,$offset=0,$column='order_id',$orderby='asc')
	{
			$this->db->from('orders');	
			$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
			$this->db->where("(
			order_id LIKE '%".$this->db->escape_like_str($search)."%' or 
			first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column,$orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}
	
	function search_count_all($search, $limit=10000)
	{
			$this->db->from('orders');	
			$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
			$this->db->where("(
			order_id LIKE '%".$this->db->escape_like_str($search)."%' or 
			first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}
		

    function get_invoices_orders($orders_id)
    {
		$this->db->from('orders');	
		$this->db->join('orders_clients', 'orders_clients.for_id = orders.id');
		$this->db->where('orders.order_id',$orders_id);
        return $this->db->get();
    }



	
}
?>
