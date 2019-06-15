<?php
class Item extends CI_Model
{
	function get_tier_price_all($date="0000-00-00 00:00:00")
	{
		$this->db->select('items_tier_prices.*');
		$this->db->from('items_tier_prices');
		$this->db->join('items', 'items.item_id = items_tier_prices.item_id');		
		if($date=="0000-00-00 00:00:00"){
			$this->db->where($this->db->dbprefix('items').'.deleted', 0);
		}else{
			$this->db->where('update_items_tier_price  > ',$date);
			$this->db->or_where('update_item > ',$date);
		}
		$result= 	$this->db->get();	
		return $result;
	}
	function get_items_activate_range(){
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->where('activate_range',1);
		$result = $this->db->get();
		if($result) {
			return $result->result();	
		} else {
			return false;
		}
	}
	function get_items_range($register_log_id){
		$this->db->select('items.name,items.item_id,range_id,final_range,extra_charge,start_range');
		$this->db->from('item_range');
		$this->db->join('items','items.item_id=item_range.item_id  ','left');
		$this->db->where('register_log_id',$register_log_id);
		return $this->db->get()->result();
	}
	function get_range_by_register_log_id($register_log_id){
		$this->db->from('item_range');
		$this->db->where('register_log_id',$register_log_id);
		return  $this->db->get()->result_array();
	}
	function save_ranges($register_log_id,$item_ids,$start_range=null,$final_range=null){
		
		foreach ($item_ids as $key=>$item_id) {
			if($start_range!=null){
				$data=array(
					"item_id"=>$item_id,
					"start_range"=>$start_range[$key],
					"register_log_id"=>$register_log_id
				);
				$this->db->insert('item_range',$data);
			}else{
				$data=array(					
					"final_range"=>(double)$final_range[$key]					
				);
				$this->db->where('register_log_id',$register_log_id);
				$this->db->where('item_id',$item_id);
				$this->db->update('item_range',$data);
			}
			
		}

	}
	function add_balance($register_log_id,$item_id,$balance){
		
		$this->db->where('register_log_id',$register_log_id);
		$this->db->where('item_id',$item_id);
		$this->db->set('extra_charge', 'extra_charge + ' . $balance, false);
		$this->db->set('employee_id_recharge', $this->session->userdata('person_id'), false);
		return $this->db->update('item_range');
	}
	function get_all_for_indexedDb($limit=100000, $offset=0,$date)
	{
		$this->db->from('items');		
		if($date=="0000-00-00 00:00:00"){
			$this->db->where('deleted',0);
		}else{
			$this->db->where('update_item > ',$date);
		}
		//$this->db->limit($limit);
		//$this->db->offset($offset);
		return $this->db->get();
	}
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	function get_id($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
	    return $this->db->get();	
	}

	/*
	Returns all the items
	*/ 
	function get_all($limit=10000, $offset=0,$col='item_id',$order='')
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->select('items.*,
		location_items.quantity as quantity, 
		location_items.quantity_warehouse as quantity_warehouse, 
		location_items.cost_price as location_cost_price,
		location_items.unit_price as location_unit_price');
		
		$this->db->from('items');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->where('items.deleted',0);
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	function get_all_service()
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		$this->db->select('items.*');		
		$this->db->from('items');
		$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
		$this->db->where('items.deleted',0);
		$this->db->where('items.is_service',1);
		$this->db->where('items.name !=',lang('sales_store_account'));
		$this->db->where('items.name !=',lang('sales_store_account_payment'));

		

		
		return $this->db->get();
	}
	function get_all_id($item_id, $location_id = false)
	{
		if (!$location_id)
		{
			$location_id=$this->Employee->get_logged_in_employee_current_location_id();
		}
		$this->db->from('location_items');
		$this->db->where('item_id',$item_id);
		$this->db->where('location_id',$location_id);
		return $this->db->get();		
	}
	function get_all_alarm($location_id = false)
	{
		
		$this->db->select('item_id,expiration_date', 'expiration_day');
		$this->db->from('items');
		return $this->db->get()->result();		
	}


	function get_all_by_category($category, $offset=0, $limit = 14)
	{
		$items_table = $this->db->dbprefix('items');
		$item_kits_table = $this->db->dbprefix('item_kits');
		
		$result = $this->db->query("(SELECT item_id, name, image_id,unit FROM $items_table 
		WHERE deleted = 0 and category = ".$this->db->escape($category). " ORDER BY name) UNION ALL (SELECT CONCAT('KIT ',item_kit_id), name, 'no_image' as image_id ,'' FROM $item_kits_table 
		WHERE deleted = 0 and category = ".$this->db->escape($category). " ORDER BY name) ORDER BY name LIMIT $offset, $limit");
		return $result;
	}
	
	function count_all_by_category($category)
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->where('category',$category);
		$items_count = $this->db->count_all_results();

		$this->db->from('item_kits');
		$this->db->where('deleted',0);
		$this->db->where('category',$category);
		$item_kits_count = $this->db->count_all_results();
		
		return $items_count + $item_kits_count;

	}
	function count_all_by_category_item($category)
	{
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();

		$this->db->select('SUM(quantity) as units');
		$this->db->from('items');
		$this->db->join('location_items','location_items.item_id=items.item_id');
		$this->db->where('deleted',0);
		$this->db->where('location_id',$current_location);
		$this->db->where('category',$category);
		
		return $this->db->get()->row();

	}
	
	function get_all_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");
		return $this->db->get();
	}
	
	function get_next_id($item_id)
	{
		$items_table = $this->db->dbprefix('items');
		$result = $this->db->query("SELECT item_id FROM $items_table WHERE item_id = (select min(item_id) from $items_table where deleted = 0 and item_id > ".$this->db->escape($item_id).")");
		
		if($result->num_rows() > 0)
		{
			$row = $result->result();
			return $row[0]->item_id;
		}
		
		return FALSE;
	}
	
	function get_prev_id($item_id)
	{
		$items_table = $this->db->dbprefix('items');
		$result = $this->db->query("SELECT item_id FROM $items_table WHERE item_id = (select max(item_id) from $items_table where deleted = 0 and item_id <".$this->db->escape($item_id).")");
		
		if($result->num_rows() > 0)
		{
			$row = $result->result();
			return $row[0]->item_id;
		}
		
		return FALSE;
	}
	
	function get_tier_price_row($tier_id,$item_id)
	{
		$this->db->from('items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id ',$item_id);
		return $this->db->get()->row();
	}
		
	function delete_tier_price($tier_id, $item_id)
	{
		
		$this->db->where('tier_id', $tier_id);
		$this->db->where('item_id', $item_id);
		$this->db->delete('items_tier_prices');
	}
	
	function tier_exists($tier_id, $item_id)
	{
		$this->db->from('items_tier_prices');
		$this->db->where('tier_id',$tier_id);
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()>=1);
		
	}
	
	function save_item_tiers($tier_data,$item_id)
	{
	    $this->Item->item_round(array($tier_data['unit_price']));
		$round=$this->Item->item_round(array($tier_data['unit_price']));
	   	$tier_data['unit_price']=$round;
		if($this->tier_exists($tier_data['tier_id'],$item_id))
		{


			$this->db->where('tier_id', $tier_data['tier_id']);
			$this->db->where('item_id', $item_id);
			return $this->db->update('items_tier_prices',$tier_data);
			
		}

		return $this->db->insert('items_tier_prices',$tier_data);	
	}


	function account_number_exists($item_number)
	{
		$this->db->from('items');	
		$this->db->where('item_number',$item_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}

	function product_id_exists($product_id)
	{
		$this->db->from('items');	
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		//If we are NOT an int return empty item
		if (!is_numeric($item_id))
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;	
		}
		
		$this->db->from('items');
		$this->db->where('item_id',$item_id);
		
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number or product_id or additional item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number',$item_number);
		$this->db->or_where('product_id', $item_number); 

		$query = $this->db->get();

		if($query->num_rows() >= 1)
		{
			return $query->row()->item_id;
		}
		
		if ($additional_item_id = $this->Additional_item_numbers->get_item_id($item_number))
		{
			return $additional_item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->select('unit_price');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by("item_id", "asc");	
		return $this->db->get();;
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
		if (!$item_id or !$this->exists($item_id))
		{
			$unit_price=$this->Item->item_round(array($item_data['unit_price']));
            $item_data['unit_price']=$unit_price;
			//log_message('error',print_r($item_data,true));
			if($this->db->insert('items',$item_data))
			{		  
				$item_data['item_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}
	    if (isset($item_data['unit_price']))
	    {
	    $unit_price=$this->Item->item_round(array($item_data['unit_price']));
        $item_data['unit_price']=$unit_price;
	    }

		$this->db->where('item_id', $item_id);
		return $this->db->update('items',$item_data);
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids,$select_inventory=0)
	{
        if(isset($item_data['unit_price']))
        {	
          	$unit_price=$this->Item->item_round(array($item_data['unit_price']));
          	$item_data['unit_price']=$unit_price;
        }

		if(!$select_inventory)
		{
		  	$this->db->where_in('item_id',$item_ids);
		}
		
		//var_dump(to_currency_no_money($item_data['price_suppliers']));exit();
		if(isset($item_data['supplier_id']) && $item_ids)
		{	
			$value=to_currency_no_money($item_data['price_suppliers']);
			foreach ($item_ids as $item_id) 
			{	
			 	$item_data_suppliers=array(
				 	'price_suppliers'=>$value,
				 	'supplier_id'=>$item_data['supplier_id'],
				 	'item_id'=>$item_id
			 	);
			 	 
			 	$this->Supplier->save_suppliers($item_data_suppliers);	
		    }	  		
		}
		return $this->db->update('items',$item_data);
	}

	function update($item_data,$item_ids)
	{
        $number = 0;
        foreach ($item_data as $item) 
        {

        	$data = array(
               'unit_price' => $item
           );
        	$this->Item->item_round(array($item));
        
            $this->db->where('item_id', $item_ids[$number]);
            $number++;
            $this->db->update('items', $data);
            
        } 
        return;
	}
	function get_items_sales_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('numero_cuenta,celular,name ,'.$this->db->dbprefix("sales_items").'.item_id, tipo_cuenta, numero_documento,tipo_documento, titular_cuenta');
		$this->db->from('sales_items');
		$this->db->join('items','items.item_id=sales_items.item_id');	
		$this->db->where("(numero_cuenta LIKE '%".$this->db->escape_like_str($search)."%' or 
		numero_documento LIKE '%".$this->db->escape_like_str($search)."%' or
		titular_cuenta LIKE '%".$this->db->escape_like_str($search)."%' )");					
		$this->db->group_by('numero_cuenta,name');
		$this->db->limit($limit);	
		$select = $this->db->get();
		
		foreach($select->result() as $row)
		{
			$suggestions[] = array(
			"numero_cuenta"=> $row->numero_cuenta,
			"value"=>$row->numero_documento,
			"item_id"=>$row->item_id,
			"banco"=> $row->name,
			"tipo_cuenta"=>$row->tipo_cuenta,
			"tipo_documento"=>$row->tipo_documento,
			"titular_cuenta"=>$row->titular_cuenta,
			"celular"=>$row->celular);
		}
		
		return $suggestions;

	}
	
	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$item_info = $this->Item->get_info($item_id);
	
		if ($item_info->image_id !== NULL)
		{
			$this->Item->update_image(NULL,$item_id);
			$this->Appfile->delete($item_info->image_id);			
		}			
		
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids,$select_inventory)
	{
		foreach($item_ids as $item_id)
		{
			$item_info = $this->Item->get_info($item_id);
		
			if ($item_info->image_id !== NULL)
			{
				$this->Item->update_image(NULL,$item_id);
				$this->Appfile->delete($item_info->image_id);			
			}			
		}
		
		if(!$select_inventory)
		{
			$this->db->where_in('item_id',$item_ids);
		}
		return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('category', $search);
		$this->db->limit($limit);
		$by_category = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_category->result() as $row)
		{
		$temp_suggestions[] = $row->category;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		

		$this->db->from('items');
		$this->db->like('item_number', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_number = $this->db->get();
		
		$temp_suggestions = array();
		foreach($by_item_number->result() as $row)
		{
			$temp_suggestions[] = $row->item_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('items');
		$this->db->like('product_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_product_id = $this->db->get();
		$temp_suggestions = array();
		foreach($by_product_id->result() as $row)
		{
			$temp_suggestions[] = $row->product_id;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		
		$this->db->from('items');
		$this->db->where('item_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_item_id = $this->db->get();
		$temp_suggestions = array();
		foreach($by_item_id->result() as $row)
		{
			$temp_suggestions[] = $row->item_id;
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

	function check_duplicate($term)
	{
		$this->db->from('items');
		$this->db->where('deleted',0);		
		$query = $this->db->where("name = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
	}
	
	function get_item_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('name', $search);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_name->result() as $row)
		{
			if ($row->category && $row->size)
			{
				$temp_suggestions[$row->item_id] =  $row->name . ' ('.$row->category.', '.$row->size.')';
			}
			elseif ($row->category)
			{
				$temp_suggestions[$row->item_id] =  $row->name . ' ('.$row->category.')';
			}
			elseif ($row->size)
			{
				$temp_suggestions[$row->item_id] =  $row->name . ' ('.$row->size.')';
			}
			else
			{
				$temp_suggestions[$row->item_id] = $row->name;				
			}
			
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
		
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('item_number', $search);
		$this->db->limit($limit);
		$by_item_number = $this->db->get();
		
		$temp_suggestions = array();
		
		foreach($by_item_number->result() as $row)
		{
			$temp_suggestions[$row->item_id] = $row->item_number;
		}
		
		asort($temp_suggestions);
		
		foreach($temp_suggestions as $key => $value)
		{
			$suggestions[]=array('value'=> $key, 'label' => $value);		
		}
				
		$this->db->from('items');
		$this->db->like('product_id', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_product_id = $this->db->get();

		$temp_suggestions = array();
		
		foreach($by_product_id->result() as $row)
		{
			$temp_suggestions[$row->item_id] = $row->product_id;
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

	function get_category_suggestions($search)
	{
		$suggestions = array();
		/*$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->limit(25);
		$by_category = $this->db->get();
		
		foreach($by_category->result() as $row)
		{
			$suggestions[]=array('label' => $row->category);
		}*/
		$this->load->model('Categories');
		
		foreach($this->Categories->get_all() as $row)
		{
			$suggestions[]=array('label' => $row["name"]);
		}
		return $suggestions;
	}

	/*
	Preform a search on items
	*/
	
	function search($search, $category = false, $limit=20,$offset=0,$column='name',$orderby='asc')
	{
		$CI =& get_instance();	
		
		$show_inventory_isbn=$CI->config->item('show_inventory_isbn');		
		$show_inventory_size=$CI->config->item('show_inventory_size');
		$show_inventory_model=$CI->config->item('show_inventory_model');
		$show_inventory_colour=$CI->config->item('show_inventory_colour');
		$show_inventory_brand=$CI->config->item('show_inventory_brand');
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
			$search_terms_array=explode(" ", $this->db->escape_like_str($search));
	
			//to keep track of which search term of the array we're looking at now	
			$search_name_criteria_counter=0;
			$sql_search_name_criteria = '';
			//loop through array of search terms
			foreach ($search_terms_array as $x)
			{
				$sql_search_name_criteria.=
				($search_name_criteria_counter > 0 ? " AND " : "").
				"name LIKE '%".$this->db->escape_like_str($x)."%'";
				$search_name_criteria_counter++;
			}
			// se arma la condicion ----------------------------------------
			$cadena="((".
			$sql_search_name_criteria. ") or 
			item_number LIKE '%".$this->db->escape_like_str($search)."%' or ".
			"product_id LIKE '%".$this->db->escape_like_str($search)."%' or ";
			if($show_inventory_colour)
			$cadena=$cadena."colour LIKE '%".$this->db->escape_like_str($search)."%' or ";
			if($show_inventory_model)
				$cadena=$cadena."model LIKE '%".$this->db->escape_like_str($search)."%' or ";
			if($show_inventory_size)
				$cadena=$cadena."size LIKE '".$this->db->escape_like_str($search)."%' or ";
			if($show_inventory_brand)
				$cadena=$cadena."marca LIKE '%".$this->db->escape_like_str($search)."%' or ";
			if($show_inventory_isbn)
				$cadena=$cadena."item_number LIKE '%".$this->db->escape_like_str($search)."%' or ";
			$cadena=$cadena.
			$this->db->dbprefix('items').".item_id LIKE '%".$this->db->escape_like_str($search)."%' or 
			category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0";

			//----------------------------------------------------------------

			$this->db->select('items.*,
			location_items.quantity as quantity, 
			location_items.cost_price as location_cost_price,
			location_items.unit_price as location_unit_price');
			$this->db->from('items');
			$this->db->join('location_items', 'location_items.item_id = items.item_id and location_id = '.$current_location, 'left');
			$this->db->where($cadena);
			
			if ($category)
			{
				$this->db->where('items.category', $category);
			}
				
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
	}

	function search_count_all($search, $category = FALSE, $limit=10000)
	{
			$this->db->from('items');
			$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
			item_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
			
			if ($category)
			{
				$this->db->where('items.category', $category);
			}
			
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();
	}

	
	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get(); 
	}
	
	function cleanup()
	{
		$item_data = array('item_number' => null, 'product_id' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('items',$item_data);
	}
	
	function update_image($file_id,$item_id)
	{
		$this->db->set('image_id',$file_id);
	    $this->db->where('item_id',$item_id);
	    
		return $this->db->update('items');
	}
	
	function create_or_update_store_account_item()
	{
		$item_id = FALSE;
		
		$this->db->from('items');
		$this->db->where('name', lang('sales_store_account_payment'));
		$this->db->where('deleted', 0);

		$result=$this->db->get();				
		if ($result->num_rows() > 0)
		{
			$query_result = $result->result();
			$item_id = $query_result[0]->item_id;
		}
		
		$item_data = array(
			'name'			=>	lang('sales_store_account_payment'),
			'description'	=>	'',
			'item_number'	=> NULL,
			'category'		=>	lang('sales_store_account_payment'),
			'size'			=> '',
			'cost_price'	=>	0,
			'unit_price'	=>	0,
			'tax_included' => 0,
			'reorder_level'	=>	NULL,
			'allow_alt_description'=> 0,
			'is_serialized'=> 0,
			'is_service'=> 1,
			'override_default_tax' => 1,
			'item_id' => 1
		);
		
		$this->save($item_data, $item_id);
			
		if ($item_id)
		{
			return $item_id;
		}
		else
		{
			return $item_data['item_id'];
		}
	}
	
	function get_store_account_item_id()
	{
		$this->db->from('items');
		$this->db->where('name', lang('sales_store_account_payment'));
		$this->db->where('deleted', 0);

		$result=$this->db->get();				
		if ($result->num_rows() > 0)
		{
			$query_result = $result->result();
			return $query_result[0]->item_id;
		}
		
		return FALSE;
	}
	public function get_items_by_serial($serial){
		$table_item = $this->db->dbprefix('items');
		$table_item_seriales = $this->db->dbprefix('additional_item_seriales');
		$this->db->select('name,item_number,'.$table_item.'.item_id,item_serial');		
		$this->db->from('items');
		$this->db->join('additional_item_seriales', 'additional_item_seriales.item_id = items.item_id ', 'left');
		$this->db->where($table_item_seriales.'.item_serial',$serial);
		$this->db->where('deleted',0);
		return $this->db->get()->result_array();	


		
    }


	function item_round($var)
	{
		if($this->config->item('round_value')==1)
		{
            foreach ($var as $value ) 
            {
                $round=round(strval($value));
            	return $round;
            }
		} 
           	if($this->config->item('round_value')==0)
            {		
             	foreach ($var as $value ) 
             	{
             		return $value;
             	}
          	}
		}
	}
	
?>
