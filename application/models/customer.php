<?php
class Customer extends Person
{	
	function get_all_points($date=null){
			
		$this->db->select('points.*');		
		$this->db->from('points');
		if($date!=null){
			$this->db->where('update_points_customer  > ',$date);
		}else{
			$this->db->where('deleted',0);	
		}
		$this->db->join('customers', 'customers.person_id = points.customer_id ');
		
		return $this->db->get();
	}
	function get_all_by_indexd($date){
		$people=$this->db->dbprefix('people');
		$customers=$this->db->dbprefix('customers');
		$sql="SELECT * 
		FROM ".$people."
		STRAIGHT_JOIN ".$customers." ON 										                       
		".$people.".person_id = ".$customers.".person_id ";
	
		if($date=="0000-00-00 00:00:00"){
			$sql.=" WHERE deleted =0 ";
		}else{
			$sql.=" WHERE update_customer >  ".$this->db->escape($date)." or update_people > ".$this->db->escape($date);
		}

		$data=$this->db->query($sql);		
						
		return $data;

	}
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

	function save_multiple_offline($curtomers)
	{
		
		$this->db->query("SET autocommit=0");

		$save = true;
		$new_customer = array();

		foreach ($curtomers as $customer)
		{
			$account_number = $customer["account_number"];

			if( $this->account_number_exists($account_number))
			{
				$info = $this->get_info_by_account_number($account_number,true);  

				$new_customer[] = $info;
			}
			else
			{
				$person_data = array(
					'first_name'=>$customer['first_name'],
					'last_name'=>$customer['last_name'],
					'email'=>$customer['email'],
					'phone_number'=>$customer['phone_number'],
					'address_1'=>$customer['address_1'],
					'address_2'=>"",
					'city'=>$customer['city'],
					'state'=>$customer['state'],
					'zip'=>"",
					'country'=>$customer['country'],
					'comments'=>"",					
				);

				$customer_data=array(
					'credit_limit'=> NULL,
					'balance' => 0,
					'cc_token' => NULL,
					'cc_preview' => NULL,
					'card_issuer' => NULL,
					'company_name' => "",
					'tier_id' => $customer['tier_id'] ? $customer['tier_id'] : NULL,
					'account_number'=>$customer['account_number']=='' ? null : $customer['account_number'],
					'taxable'=>$customer['taxable']=='' ? 0 : 1,
				);
				if($customer['account_number'] == ""){
					$save= false;
					break;
				}	
					
				$result = $this->save($person_data,$customer_data,-1);

				if($result)
				{
					$info = $this->get_info_by_account_number($account_number,true);
					$new_customer[] = $info;					
				}
				else
				{
					$save= false;
					break;
				}						
			}		
		}

		if(!$save)
		{		
			$this->db->query("ROLLBACK");
			$this->db->query("SET autocommit=1");

			return false;
		}

		$this->db->query("COMMIT");
		$this->db->query("SET autocommit=1");

		return $new_customer;
	}

	function account_number_exists($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function customer_id_from_account_number($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		if ($query->num_rows()==1)
		{
			return $query->row()->person_id;
		}
		
		return false;
	}
	
	/*
	Returns all the customers
	*/
	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
	{
		$people=$this->db->dbprefix('people');
		$customers=$this->db->dbprefix('customers');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$customers." ON 										                       
						".$people.".person_id = ".$customers.".person_id
						WHERE deleted =0 ORDER BY ".$col." ". $order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('customers');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$customer_id);
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
			$fields = $this->db->list_fields('customers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	function get_info_by_account_number($account_number, $to_array = FALSE)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.account_number',$account_number);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $to_array == FALSE ? $query->row() :  $query->row_array();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from customer table
			$fields = $this->db->list_fields('customers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	function get_info_points($customer_id)
	{

		$this->db->from('points');	
		$this->db->where('points.customer_id',$customer_id);
		$query = $this->db->get();
		if($query->num_rows()==1)
		{

			return $query->row()->points;
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			return 0;
		}
	}
	
	
	/*
	Gets information about multiple customers
	*/
	function get_multiple_info($customer_ids)
	{
		$this->db->from('customers');
		$this->db->join('people', 'people.person_id = customers.person_id');		
		$this->db->where_in('customers.person_id',$customer_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a customer
	*/
	function save(&$person_data, &$customer_data, $customer_id = false)
	{
		$success = false;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		
		if(parent::save($person_data,$customer_id))
		{
			if ($customer_id && $this->exists($customer_id))
			{
				$cust_info = $this->get_info($customer_id);
				
				$current_balance = $cust_info->balance;
				
				//Insert store balance transaction when manually editing
				if (isset($customer_data['balance']) && $customer_data['balance'] != $current_balance)
				{
		 			$store_account_transaction = array(
		   		'customer_id'=>$customer_id,
		   		'sale_id'=>NULL,
					'comment'=>lang('customers_manual_edit_of_balance'),
		      	'transaction_amount'=>$customer_data['balance'] - $current_balance,
					'balance'=>$customer_data['balance'],
					'date' => date('Y-m-d H:i:s')
					);
					
					$this->db->insert('store_accounts',$store_account_transaction);
				}
			}
						
			if (!$customer_id or !$this->exists($customer_id))
			{
				$customer_data['person_id'] = $person_data['person_id'];
				$success = $this->db->insert('customers',$customer_data);				
			}
			else
			{
				$this->db->where('person_id', $customer_id);
				$success = $this->db->update('customers',$customer_data);
			}			
		}
		
		return $success;
	}
	
	/*
	Deletes one customer
	*/
	function delete($customer_id)
	{
		$customer_info = $this->Customer->get_info($customer_id);
	
		if ($customer_info->image_id !== NULL)
		{
			$this->Person->update_image(NULL,$customer_id);
			$this->Appfile->delete($customer_info->image_id);			
		}			
		
		$this->db->where('person_id', $customer_id);
		return $this->db->update('customers', array('deleted' => 1));
	}
	
	/*
	Deletes a list of customers
	*/
	function delete_list($customer_ids)
	{
		foreach($customer_ids as $customer_id)
		{
			$customer_info = $this->Customer->get_info($customer_id);
		
			if ($customer_info->image_id !== NULL)
			{
				$this->Person->update_image(NULL,$customer_id);
				$this->Appfile->delete($customer_info->image_id);			
			}			
		}
		
		$this->db->where_in('person_id',$customer_ids);
		return $this->db->update('customers', array('deleted' => 1));
 	}
	
	function check_duplicate($term)
	{
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$query = $this->db->where("CONCAT(first_name,' ',last_name) = ".$this->db->escape($term));
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return true;
		}
		
		return false;
	}
 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
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
			$temp_suggestions[] = $row->last_name.', '.$row->first_name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
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
			$temp_suggestions[] = $row->email;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("phone_number",$search);
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
		
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("account_number",$search);
		$this->db->limit($limit);
		$by_account_number = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_account_number->result() as $row)
		{
			$temp_suggestions[]=$row->account_number;		
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
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
			$temp_suggestions[]= $row->company_name;	
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
		CONCAT(`last_name`,' ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%' or
		CONCAT(`other`,' ',`last_name`,' ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");			
		
		$this->db->limit($limit);	
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[$row->person_id] = $row->last_name.', '.$row->first_name.', '.$row->other;
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
			$temp_suggestions[$row->person_id] = $row->account_number.', '.$row->last_name.', '.$row->first_name;
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
			$temp_suggestions[$row->person_id] = $row->phone_number.', '.$row->last_name.', '.$row->first_name;
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
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
			$this->db->from('customers');
			$this->db->join('people','customers.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`last_name`,', ',`first_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column,$orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}
	
	function search_count_all($search, $limit=10000)
	{
			$this->db->from('customers');
			$this->db->join('people','customers.person_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}
		
	function cleanup()
	{
		$customer_data = array('account_number' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('customers',$customer_data);
	}
}
?>
