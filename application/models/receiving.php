<?php
class Receiving extends CI_Model
{
	public function get_info($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function exists($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	function update($receiving_data, $receiving_id)
	{
		$this->db->where('receiving_id', $receiving_id);
		$success = $this->db->update('receivings',$receiving_data);
		
		return $success;
	}


	function save ($items,$supplier_id,$employee_id,$comment,$payment_type,$receiving_id=false, $suspended = 0, $mode,$location_id=-1,$payments)
	{
		var_dump($mode);
		if(count($items)==0)
			return -1;
		$credit=0;
		$compra_credito=lang("receivings_supplier_credit");
		if ($payment_type == $compra_credito){
			$credit=1;
		};
		
		$receivings_data = array(
		'receiving_time' => date('Y-m-d H:i:s'),
		'supplier_id'=> $supplier_id > 0 ? $supplier_id : null,
		'employee_id'=>$employee_id,
		'payment_type'=>$payment_type,
		'comment'=>$comment,
		'suspended' => $suspended,
		'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
		'transfer_to_location_id' => $location_id > 0 ? $location_id : NULL,
		'deleted' => 0,
		'deleted_by' => NULL,
		'credit' => $credit,
		'mount' =>  $this->receiving_lib->get_total()
		);

		
		if ($suspended != 1 && $this->config->item('track_cash') == 1 && $payment_type == "Efectivo") {

			$cash = $this->receiving_lib->get_total();
			if ($mode == "receive") {
				$this->Register_movement->save($cash * (-1), "Compra realizada",false,true,"Compra realizada",false);
			} elseif($mode == "return") {
				$this->Register_movement->save($cash, "Compra retornada",false,true,"Compra retornada",false);
			}
		}
		
		
		$this->db->query("SET autocommit=0");	
		//Lock tables invovled in sale transaction so we don't have deadlock
		$this->db->query('LOCK TABLES '.$this->db->dbprefix('customers').' WRITE, '.$this->db->dbprefix('receivings').' WRITE, 
		'.$this->db->dbprefix('store_accounts').' WRITE, '.$this->db->dbprefix('receivings_items').' WRITE, 
		'.$this->db->dbprefix('giftcards').' WRITE, '.$this->db->dbprefix('location_items').' WRITE, 
		'.$this->db->dbprefix('inventory').' WRITE, '.$this->db->dbprefix('receivings_items_taxes').' WRITE,
		'.$this->db->dbprefix('items_suppliers').' WRITE, '.$this->db->dbprefix('items_suppliers').' READ, 
		'.$this->db->dbprefix('people').' READ,'.$this->db->dbprefix('items').' WRITE
		,'.$this->db->dbprefix('employees_locations').' READ,'.$this->db->dbprefix('locations').' READ, '.$this->db->dbprefix('items_tier_prices').' READ
		, '.$this->db->dbprefix('location_items_tier_prices').' READ, '.$this->db->dbprefix('items_taxes').' READ, '.$this->db->dbprefix('item_kits').' READ
		, '.$this->db->dbprefix('location_item_kits').' READ, '.$this->db->dbprefix('item_kit_items').' READ, '.$this->db->dbprefix('employees').' READ , '.$this->db->dbprefix('item_kits_tier_prices').' READ
		, '.$this->db->dbprefix('location_item_kits_tier_prices').' READ, '.$this->db->dbprefix('suppliers').' READ, '.$this->db->dbprefix('location_items_taxes').' READ
		, '.$this->db->dbprefix('location_item_kits_taxes'). ' READ, '.$this->db->dbprefix('item_kits_taxes'). ' READ, '.$this->db->dbprefix('items_subcategory'). ' READ');

		if ($receiving_id)
		{
			//Delete previoulsy receving so we can overwrite data
			if (!$this->delete($receiving_id, true, false))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;				
			}
			
			$this->db->where('receiving_id', $receiving_id);
			if (!$this->db->update('receivings', $receivings_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
		}
		else
		{
			if (!$this->db->insert('receivings',$receivings_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$receiving_id = $this->db->insert_id();
		}
		
		if ($suspended != 1 && $payment_type == $compra_credito) {
			$store_account_payment_amount  = $this->receiving_lib->get_total(); 
			/* $this->actualiza_balance_proveedor($supplier_id); */
			/* var_dump($store_account_payment_amount);			 */
		}	
		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);
			$cur_item_location_info = $this->Item_location->get_info($item['item_id']);
			$cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $item['cost_price_preview'];

			$receivings_items_data = array
			(
				'receiving_id'=>$receiving_id, 
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cost_price,
				'item_unit_price'=>$item['price'],
				'item_cost_transport'=>$item['cost_transport'],			
				"custom1_subcategory"=>$item['custom1_subcategory'],
				"custom2_subcategory"=>$item['custom2_subcategory'],			
				"quantity_subcategory"=>$item['quantity_subcategory']
			);

			if (!$this->db->insert('receivings_items',$receivings_items_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			
			if ($suspended == 0)
			{
				if ($this->config->item('calculate_average_cost_price_from_receivings'))
				{
					$this->calculate_and_update_average_cost_price_for_item($item['item_id'], $receivings_items_data);
				}
			}
			
			//Update stock quantity IF not a service item
			if ($suspended == 0 && !$cur_item_info->is_service)
			{
				//If we have a null quanity set it to 0, otherwise use the value
				$cur_item_location_info->quantity = $cur_item_location_info->quantity !== NULL ? $cur_item_location_info->quantity : 0;
				
				if($suspended == 0 && $mode=='transfer' && $location_id && $cur_item_location_info->quantity !== NULL && !$cur_item_info->is_service)
				{
					if (!$this->Item_location->save_quantity($cur_item_location_info->quantity + $item['quantity']*-1, $item['item_id']))
					{
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}
				}
				elseif ($mode == 'return') 
				{
					if (!$this->Item_location->save_quantity($cur_item_location_info->quantity + $item['quantity']*-1, $item['item_id']))
					{
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}
					if ($this->config->item('subcategory_of_items') && $item['has_subcategory']==1) {
						$subcategory = $this->items_subcategory->get_info($item['item_id'], false, $item['custom1_subcategory'], $item['custom2_subcategory']);
						$quantity_subcategory = $subcategory->quantity;
						if(!$this->items_subcategory->save_quantity(($quantity_subcategory-$item['quantity_subcategory']), 
						$item['item_id'], false, $item['custom1_subcategory'],$item['custom2_subcategory'])){
							$this->db->query("ROLLBACK");
							$this->db->query('UNLOCK TABLES');
							return -1;
						}

					}
				}
				else
				{					
					if (!$this->Item_location->save_quantity($cur_item_location_info->quantity + $item['quantity'], $item['item_id']))
					{
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}	
					if ($this->config->item('subcategory_of_items') && $item['has_subcategory']==1) {
						$subcategory = $this->items_subcategory->get_info($item['item_id'], false, $item['custom1_subcategory'], $item['custom2_subcategory']);
						$quantity_subcategory = $subcategory->quantity;
						if(!$this->items_subcategory->save_quantity(($quantity_subcategory+$item['quantity_subcategory']), 
						$item['item_id'], false, $item['custom1_subcategory'],$item['custom2_subcategory'])){
							$this->db->query("ROLLBACK");
							$this->db->query('UNLOCK TABLES');
							return -1;
						}

					}				
				}
				
				$qty_recv = $item['quantity'];
				$recv_remarks ='RECV '.$receiving_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item['item_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$recv_remarks,
					'trans_inventory'=>$qty_recv,
					'location_id'=>$this->Employee->get_logged_in_employee_current_location_id()
				);
				if (!$this->Inventory->insert($inv_data))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
			}

			if($suspended  == 0 && $mode=='transfer' && $location_id && $cur_item_location_info->quantity !== NULL && !$cur_item_info->is_service)
			{				
				if (!$this->Item_location->save_quantity($this->Item_location->get_location_quantity($item['item_id'],$location_id) + ($item['quantity']),$item['item_id'],$location_id))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
				if ($this->config->item('subcategory_of_items') && $item['has_subcategory']==1) {
					if(!$this->items_subcategory->exists($item['item_id'], false , $item['custom1_subcategory'],$item['custom2_subcategory'])){
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}
					$subcategory = $this->items_subcategory->get_info($item['item_id'], false, $item['custom1_subcategory'], $item['custom2_subcategory']);
					$quantity_subcategory = $subcategory->quantity;
					if(!$this->items_subcategory->save_quantity(($quantity_subcategory-$item['quantity_subcategory']), 
					$item['item_id'], false, $item['custom1_subcategory'],$item['custom2_subcategory'])){
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}
					// se crea la nueva subcategoria en la tienda si no existe
					$new_subcategory= array(
						'item_id' => $item['item_id'],
						"location_id"=>$location_id,
						"custom1"=>strtoupper($item['custom1_subcategory']),
						"custom2"=> strtoupper ($item['custom2_subcategory']),
						"deleted"=>0,
						"quantity"=>0

					 );
					if(!$this->items_subcategory->save_one($new_subcategory)){
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}
					$subcategory = $this->items_subcategory->get_info($item['item_id'], $location_id, $item['custom1_subcategory'], $item['custom2_subcategory']);
					$quantity_subcategory = $subcategory->quantity;
					if(!$this->items_subcategory->save_quantity(($quantity_subcategory+$item['quantity_subcategory']), 
						$item['item_id'], $location_id, $item['custom1_subcategory'],$item['custom2_subcategory'])){
						$this->db->query("ROLLBACK");
						$this->db->query('UNLOCK TABLES');
						return -1;
					}

				}
				
				
				//Change values from $inv_data above and insert
				$inv_data['trans_inventory']=$qty_recv * -1;
				$inv_data['location_id']=$location_id;
				if (!$this->Inventory->insert($inv_data))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}			
			}		

			//Save suppliers and new price
			$averaging_method = $this->config->item('averaging_method');

			if ($supplier_id!=-1  && $averaging_method!=NULL)
				{
		
				
				$item_data_supliers=array(
					'supplier_id'=>$supplier_id,
			 		'item_id'=>$item['item_id'],
			 	  	'price_suppliers'=>$item['price']			 	
		 		);
			   
		 		if(!$this->Supplier->save_suppliers($item_data_supliers,$supplier_id))	
		 		{
		 			$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
		 		}
		 		$suppl_info=$this->Supplier->get_info($supplier_id);
		 		if (isset($item['item_id']) && $suppl_info->type=='Jurídico')
				{
					
					foreach($this->Item_taxes_finder->get_info($item['item_id']) as $row)
					{
						$tax_name = $row['percent'].'% ' . $row['name'];
				
						//Only save sale if the tax has NOT been deleted
						if (!in_array($tax_name, $this->receiving_lib->get_deleted_taxes()))
						{	
							

							$query_result = $this->db->insert('receivings_items_taxes', array(
								'receiving_id' 	=>$receiving_id,
								'item_id' 	=>$item['item_id'],
								'line'      =>$item['line'],
								'name'		=>$row['name'],
								'percent' 	=>$row['percent'],
								'cumulative'=>$row['cumulative']
							));
							
							if (!$query_result)
							{
								$this->db->query("ROLLBACK");
								$this->db->query('UNLOCK TABLES');
								return -1;
							}
						}
					}
				}

			}
			//
		}
		
		$this->db->query("COMMIT");			
		$this->db->query('UNLOCK TABLES');	
		
		$amount_change=0;
		$amount_change_aux = $amount_change;
       /*  foreach ($payments as $payment_id => $payment) { */
            //Only update giftcard payments if we are NOT an estimate (suspended = 2)
            /* if ($suspended != 2) {
                if (substr($payment['payment_type'], 0, strlen(lang('sales_giftcard'))) == lang('sales_giftcard')) {
                  
                    $splitpayment = explode(':', $payment['payment_type']);
                    $cur_giftcard_value = $this->Giftcard->get_giftcard_value($splitpayment[1]);

                    $this->Giftcard->update_giftcard_value($splitpayment[1], $cur_giftcard_value - $payment['payment_amount']);
                    $total_giftcard_payments += $payment['payment_amount'];
                }
            } */
			$payment_types = '';
			$monton = $this->receiving_lib->get_total();
                $payment_types = lang('receivings_cash') . ': ' . to_currency($monton) . '<br />';
 
            $receiving_payments_data = array
                (
                'receiving_id' => $receiving_id,
                'payment_type' => $payment_types,
                'payment_amount' =>$monton,
                 'payment_date' => date('Y-m-d H:i:s'),
            );
            // si se permite solo $amount_change en efectivo se coloca el if, de lo contrario no
            if ($payments['payment_type'] == lang('receivings_cash')) {

                if ($payments['payment_amount'] <= $amount_change_aux) {

                    $amount_change_aux = $amount_change_aux - $payments['payment_amount'];
                    $receiving_payments_data['payment_amount'] = 0;

                } else if ($payments['payment_amount'] > $amount_change_aux) {

                    $receiving_payments_data["payment_amount"] = $receiving_payments_data["payment_amount"] - $amount_change_aux;
                    $amount_change_aux = 0;
                }
            }

            if (!$this->db->insert('receivings_payments', $receiving_payments_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }

        /* } */

		return $receiving_id;
	}
	
	function delete($receiving_id, $all_data = false, $update_quantity = true)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
		$this->db->select('receivings.location_id, quantity_subcategory, custom1_subcategory,custom2_subcategory,item_id, quantity_purchased, transfer_to_location_id');
		$this->db->from('receivings_items');
		$this->db->join('receivings', 'receivings.receiving_id = receivings_items.receiving_id');
		$this->db->where('receivings.receiving_id', $receiving_id);
		
		foreach($this->db->get()->result_array() as $receiving_item_row)
		{
			$receiving_location_id = $receiving_item_row['location_id'];
			$cur_item_info = $this->Item->get_info($receiving_item_row['item_id']);	
			$cur_item_location_info = $this->Item_location->get_info($receiving_item_row['item_id']);
			
			if ($update_quantity)
			{
				$this->Item_location->save_quantity($cur_item_location_info->quantity - $receiving_item_row['quantity_purchased'],$receiving_item_row['item_id']);

				$subcategory = $this->items_subcategory->get_info($receiving_item_row['item_id'], $receiving_location_id, $receiving_item_row['custom1'], $receiving_item_row['custom2']);
				$quantity_subcategory = $subcategory->quantity;
				$this->items_subcategory->save_quantity(($quantity_subcategory-$receiving_item_row['quantity_subcategory']), 
						$receiving_item_row['item_id'], $receiving_location_id, $receiving_item_row['custom1_subcategory'],$receiving_item_row['custom2_subcategory']);
						
				$sale_remarks ='RECV '.$receiving_id;
				$inv_data = array
					(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$receiving_item_row['item_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$receiving_item_row['quantity_purchased'] * -1,
					'location_id'=>$receiving_location_id
					);
					$this->Inventory->insert($inv_data);
					
					if ($receiving_item_row['transfer_to_location_id'])
					{
						$cur_item_location_transfer_info = $this->Item_location->get_info($receiving_item_row['item_id'], $receiving_item_row['transfer_to_location_id']);
						
						$this->Item_location->save_quantity($cur_item_location_transfer_info->quantity + $receiving_item_row['quantity_purchased'],$receiving_item_row['item_id'], $receiving_item_row['transfer_to_location_id']);
				
						$sale_remarks ='RECV '.$receiving_id;
						$inv_data = array
							(
							'trans_date'=>date('Y-m-d H:i:s'),
							'trans_items'=>$receiving_item_row['item_id'],
							'trans_user'=>$employee_id,
							'trans_comment'=>$sale_remarks,
							'trans_inventory'=>$receiving_item_row['quantity_purchased'] * 1,
							'location_id'=>$receiving_item_row['transfer_to_location_id']
							);
							$this->Inventory->insert($inv_data);
					}
			 }
		}
		
		if ($all_data)
		{
			$this->db->delete('receivings_items', array('receiving_id' => $receiving_id));
		}
		
		$this->db->where('receiving_id', $receiving_id);
		return $this->db->update('receivings', array('deleted' => 1,'deleted_by'=>$employee_id));
	}
	
	/* This function is not visible accessible easily from php pos. 
	If we ever make it visible we should make sure quantities are added back
	*/ 
	function undelete($receiving_id)
	{
		$this->db->where('receiving_id', $receiving_id);
		return $this->db->update('receivings', array('deleted' => 0,'deleted_by'=>NULL));
	}

	function get_receiving_items($receiving_id)
	{
		$this->db->from('receivings_items');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function get_supplier($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
	}

	function get_receiving_items_taxes($receiving_id, $line = FALSE)
	{
		$item_where = '';
		
		if ($line)
		{
			$item_where = 'and '.$this->db->dbprefix('receivings_items').'.line = '.$line;
		}

		$query = $this->db->query('SELECT name, percent, cumulative, item_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('receivings_items_taxes'). ' JOIN '.
		$this->db->dbprefix('receivings_items'). ' USING (receiving_id, item_id, line) '.
		'WHERE '.$this->db->dbprefix('receivings_items_taxes').".receiving_id = $receiving_id".' '.$item_where.' '.
		'ORDER BY '.$this->db->dbprefix('receivings_items').'.line,'.$this->db->dbprefix('receivings_items').'.item_id,cumulative,name,percent');
		return $query->result_array();
	}
	
	//We create a temp table that allows us to do easy report/receiving queries
	public function create_receivings_items_temp_table($params)
	{
		set_time_limit(0); 
		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();

		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();

		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{


			$where = 'WHERE receiving_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"';
			if(isset($params['listar_contado']) and $params['listar_contado']==true){
				$where .= ' and credit=0';

			}
			$where .= ' and '.$this->db->dbprefix('receivings_items').'.item_id not like '.$store_account_payment_item_id;



			if(!isset($params['store_id'])){
				$where .=' and '.$this->db->dbprefix('receivings').'.location_id= '.$this->db->escape($location_id);
			}else{
				if ($params['store_id'] != 'all'){
					$where .=' and '.$this->db->dbprefix('receivings').'.location_id='.$this->db->escape($params['store_id']);
				}
			}
		}
		else
		{
			//If we don't pass in a date range, we don't need data from the temp table
			$where = 'WHERE location_id='.$this->db->escape($location_id);
		}
		/* $this->db->not_like('receivings_items.item_id', $store_account_payment_item_id); */
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('receivings_items_temp')."
		(SELECT ".$this->db->dbprefix('receivings').".location_id as location_id,"
		.$this->db->dbprefix('receivings').".transfer_to_location_id as transfer_to_location_id,".
		$this->db->dbprefix('receivings').".deleted as deleted,".$this->db->dbprefix('receivings').".deleted_by as deleted_by, date(receiving_time) as receiving_date, ".$this->db->dbprefix('receivings_items').".receiving_id, comment,payment_type, employee_id, 
		".$this->db->dbprefix('items').".item_id, ".$this->db->dbprefix('receivings').".supplier_id, quantity_purchased, item_cost_price, item_unit_price,category,
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('receivings_items').".line as line, serialnumber, ".$this->db->dbprefix('receivings_items').".description as description,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as total,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('receivings_items')."
		INNER JOIN ".$this->db->dbprefix('receivings')." ON  ".$this->db->dbprefix('receivings_items').'.receiving_id='.$this->db->dbprefix('receivings').'.receiving_id'."
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('receivings_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."	$where	GROUP BY receiving_id, item_id, line)"); 
		
	}
	
	public function create_store_payments_temp_table($params)
	{
		set_time_limit(0);
		
	
		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE date BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"';
			$where .= ' and abono=1';
		}
		
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('store_payments_temp')."
		(SELECT ".$this->db->dbprefix('store_payments').".transaction_amount as total
		FROM ".$this->db->dbprefix('store_payments')." $where	GROUP BY pay_cash_id)");
			/* var_dump($where); */
	}

	function calculate_and_update_average_cost_price_for_item($item_id,$current_receivings_items_data)
	{
		//Dont calculate averages unless we receive quanitity > 0
		if ($current_receivings_items_data['quantity_purchased'] > 0)
		{
			
			$cost_price_avg = false;
			$averaging_method = $this->config->item('averaging_method');
		
			$cur_item_info = $this->Item->get_info($item_id);
			$cur_item_location_info = $this->Item_location->get_info($item_id);

			// Price average
			if ($averaging_method == 'price_average')
			{
				$cost_price_avg = $current_receivings_items_data['item_unit_price'];
			}
			//

			if ($averaging_method == 'moving_average')
			{
				$current_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;			
				$current_quantity = $cur_item_location_info->quantity;
				$current_inventory_value = $current_cost_price * $current_quantity;
			
				$received_cost_price = $current_receivings_items_data['item_unit_price'] * (1 - ($current_receivings_items_data['discount_percent']/100));
				$received_quantity = $current_receivings_items_data['quantity_purchased'];
				$new_inventory_value = $received_cost_price * $received_quantity;
			
				$cost_price_avg = ($current_inventory_value + $new_inventory_value) / ($current_quantity + $received_quantity);
			
			}
			elseif ($averaging_method == 'historical_average')
			{
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$location_id = $this->Employee->get_logged_in_employee_current_location_id();
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)) / SUM(quantity_purchased),10) as cost_price_average 
					FROM ".$this->db->dbprefix('receivings_items').' '.
					'JOIN '.$this->db->dbprefix('receivings').' ON '.$this->db->dbprefix('receivings').'.receiving_id = '.$this->db->dbprefix('receivings_items').'.receiving_id '.
					'WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id).' and location_id = '.$this->db->escape($location_id))->result();
				}
				else
				{
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)) / SUM(quantity_purchased),10) as cost_price_average 
					FROM ".$this->db->dbprefix('receivings_items'). '
					WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id))->result();				
				}
			
				$cost_price_avg = $result[0]->cost_price_average;
			}
		
			if ($cost_price_avg !== FALSE)
			{
				$cost_price_avg = to_currency_no_money($cost_price_avg, 2);
				//If we have a location cost price, update that value
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$item_location_data = array('cost_price' => $cost_price_avg);
					$this->Item_location->save($item_location_data,$item_id);
				}
				else
				{
					//Update cost price
					$item_data = array('cost_price'=>$cost_price_avg);
					$this->Item->save($item_data,$item_id);
				}
			}
		}
	}

	function calculate_cost_price_preview($item_id,$price, $additional_quantity, $discount_percent,$cost_transport)
	{
		if ($additional_quantity > 0)
		{
			$cost_price_avg = false;
			$averaging_method = $this->config->item('averaging_method');
		
			$cur_item_info = $this->Item->get_info($item_id);
			$cur_item_location_info = $this->Item_location->get_info($item_id);
				
			//Price average		
			if ($averaging_method == 'price_average')
			{
				$cost_price_avg = $price;
			}
			//

			elseif ($averaging_method == 'moving_average')
			{
				$current_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;			
				$current_quantity = $cur_item_location_info->quantity;
				$current_inventory_value = $current_cost_price  * $current_quantity;
				$received_cost_price = $price * (1 - ($discount_percent/100));
				$received_quantity = $additional_quantity;	
				$new_inventory_value = ($received_cost_price+$cost_transport) * $received_quantity;
				$cost_price_avg = ($current_inventory_value + $new_inventory_value ) / ($current_quantity + $received_quantity);
			
			}
			elseif ($averaging_method == 'historical_average')
			{
				if ($cur_item_location_info && $cur_item_location_info->cost_price)
				{
					$location_id = $this->Employee->get_logged_in_employee_current_location_id();
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)),10) as cost_price_sum,  SUM(quantity_purchased) as cost_price_quantity_sum
					FROM ".$this->db->dbprefix('receivings_items').' '.
					'JOIN '.$this->db->dbprefix('receivings').' ON '.$this->db->dbprefix('receivings').'.receiving_id = '.$this->db->dbprefix('receivings_items').'.receiving_id '.
					'WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id).' and location_id = '.$this->db->escape($location_id))->result();
				}
				else
				{
					$result = $this->db->query("SELECT ROUND((SUM(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)),10) as cost_price_sum,  SUM(quantity_purchased) as cost_price_quantity_sum
					FROM ".$this->db->dbprefix('receivings_items'). '
					WHERE quantity_purchased > 0 and item_id='.$this->db->escape($item_id))->result();				
				}
				
				$cost_price_sum = $result[0]->cost_price_sum + ($price*$additional_quantity-$price*$additional_quantity*$discount_percent/100);
				$cost_price_quantity_sum = $result[0]->cost_price_quantity_sum + $additional_quantity;
				
				$cost_price_avg = $cost_price_sum/$cost_price_quantity_sum;
			}
		
			return to_currency($cost_price_avg,2);
		}
	
		return FALSE;
	}
	
	function get_all_suspended()
	{		
		$location_id = $this->Employee->get_logged_in_employee_current_location_id();		
		
		$this->db->from('receivings');
		$this->db->join('suppliers', 'receivings.supplier_id = suppliers.person_id', 'left');
		$this->db->join('people', 'suppliers.person_id = people.person_id', 'left');
		$this->db->where('receivings.deleted', 0);
		$this->db->where('receivings.suspended', 1);
		$this->db->where('location_id', $location_id);
		$this->db->order_by('receiving_id');
		$receivings = $this->db->get()->result_array();

		for($k=0;$k<count($receivings);$k++)
		{
			$item_names = array();
			$this->db->select('name');
			$this->db->from('items');
			$this->db->join('receivings_items', 'receivings_items.item_id = items.item_id');
			$this->db->where('receiving_id', $receivings[$k]['receiving_id']);
		
			foreach($this->db->get()->result_array() as $row)
			{
				$item_names[] = $row['name'];
			}
			
			$receivings[$k]['items'] = implode(', ', $item_names);
		}
		
		return $receivings;
	}
	
	function get_suspended_receivings_for_item($item_id)
	{
		$this->db->from('receivings');
		$this->db->join('receivings_items', 'receivings.receiving_id = receivings_items.receiving_id');
		$this->db->where('receivings.suspended', '1');
		$this->db->where('receivings_items.item_id', $item_id);
		
		return $this->db->get()->result_array();
	}
	
	public function get_recent_receivings_for_supplier($supplier_id)
    {
        $return = array();

        $this->db->select('receivings.*, SUM(quantity_purchased) as items_purchased');
        $this->db->from('receivings');
        $this->db->join('receivings_items', 'receivings.receiving_id = receivings_items.receiving_id');
        $this->db->where('supplier_id', $supplier_id);
        $this->db->where('deleted', 0);
        $this->db->order_by('receiving_time DESC');
        $this->db->group_by('receivings.receiving_id');
        $this->db->limit(10);

        foreach ($this->db->get()->result_array() as $row) {
            $return[] = $row;
        }

        return $return;
    }
	
	 public function get_pay_cash($supplier_id,$location_id=false,$limit=100)
    {
       if($location_id==false){
            $location_id= $this->Employee->get_logged_in_employee_current_location_id();
       }
        $this->db->from('pay_cash');
        $this->db->where('supplier_id', $supplier_id);
        $this->db->where('location_id', $location_id);        
        $this->db->where('deleted', 0);
        $this->db->order_by('pay_cash_id DESC');
        $this->db->limit($limit);
        $query = $this->db->get();

        return $query->result();
    }
	
	 public function get_receiving($supplier_id,$location_id=false,$limit=100)
    {
		$store_account_payment_item_id = $this->Item->create_or_update_store_account_item();
		/* var_dump($store_account_payment_item_id); */
       if($location_id==false){
            $location_id= $this->Employee->get_logged_in_employee_current_location_id();
       }
	    $this->db->select('a.*');
        $this->db->from('receivings a');
		$this->db->join('receivings_items b', ' b.receiving_id = a.receiving_id','left');
        $this->db->where('a.supplier_id', $supplier_id);
        $this->db->where('a.location_id', $location_id);        
        $this->db->where('a.deleted', 0);
		$this->db->not_like('b.item_id', $store_account_payment_item_id);		
        $this->db->order_by('a.receiving_time DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
		
		if($query->num_rows() > 0)
               return $query->result();
    }
	
    function delete_pay_cash($pay_cash_id,$delte_all=false){
        $data=array(
            "deleted"=>1,
            "deleted_by"=>$this->Employee->get_logged_in_employee_info()->person_id
         );
         $this->db->where('pay_cash_id', $pay_cash_id);
         $result = $this->db->update('pay_cash', $data);
         $this->update_store_account_by_pay_cash($pay_cash_id); 
        /*  $this->update_giftcard_balance_by_pay_cash($pay_cash_id);  */
         return $result;
    }
	
	public function get_pay_cash_by_id($pay_cash_id)
    {
        $this->db->from('pay_cash');
        $this->db->where('pay_cash_id', $pay_cash_id);
        return $this->db->get()->row();
    }
	
	public function get_supplier_by_pay_cash($pay_cash_id)
    {
        $this->db->from('pay_cash');
        $this->db->where('pay_cash_id', $pay_cash_id);
        return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
    }
	
	public function update_store_account_by_pay_cash($pay_cash_id, $undelete = 0)
    {
        $pay_cash_info=$this->get_pay_cash_by_id($pay_cash_id);
        if($pay_cash_info->monton_total){
            //update if Store account payment exists
            $this->db->from('pay_cash_payments');       
            $this->db->where('pay_cash_id', $pay_cash_id);
        
            $to_be_paid_result = $this->db->get();

            $supplier_id = $this->get_supplier_by_pay_cash($pay_cash_id)->person_id;

            if ($to_be_paid_result->num_rows >= 1) {
                foreach ($to_be_paid_result->result() as $to_be_paid) {
                    if($to_be_paid->payment_type==lang('receivings_cash')){
                        if ($to_be_paid->payment_amount) {
                    
                            if ($undelete == 0) {
                                $description ="Abono  de crédito eliminado";
                                $categorias_gastos="Abono  de crédito eliminado";
                                $result=  $this->Register_movement->save($to_be_paid->payment_amount, $description,false,true,$categorias_gastos,false);
                                
                        
                            } else {
                                $description ="Abono  de crédito restaurado";
                                $categorias_gastos="Abono  de crédito restaurado";
                                $result=  $this->Register_movement->save(-$to_be_paid->payment_amount, $description,false,true,$categorias_gastos,false);
                                
                            }
                        
                        }
                    }   
                }
            }
			$total_balance=$this->total_balance($supplier_id);
            $store_account_payment_amount=$pay_cash_info->monton_total;
            $movement_type=0;
            $category="";
            if ($undelete == 0) {
                $this->db->set('balance', $total_balance+$store_account_payment_amount, false);
                //$store_account_payment_amount=-$store_account_payment_amount;
                    $movement_type=0;
                    $category="Abono  de crédito eliminado";
                    
            }else{
                $this->db->set('balance', $total_balance-$store_account_payment_amount, false);
                $movement_type=1;
                $category="Abono  de crédito restaurado";
            }
            $this->db->where('person_id', $supplier_id);
            $result=  $this->db->update('suppliers');

            $store_payments_transaction = array(
                'supplier_id'=>$supplier_id,
                'pay_cash_id'=>$pay_cash_id,
                'comment'=>"",
                'transaction_amount'=>$store_account_payment_amount*-1,
                'balance'=>$total_balance,
                'date' => date('Y-m-d H:i:s'),
                "movement_type"=>$movement_type,// 0 agregar al la cuenta 1 restar de la cuenta
				"abono"=>1,
                "category"=>$category
            );   
		
           $result= $this->db->insert('store_payments',$store_payments_transaction);            
        }        
    }  
	
	public function add_pay_cash_supplier($supplier_id, $balance, $total_venta,$suspended = 0)
    {
        //we need to check the sale library for deleted taxes during sale
        $this->load->library('receiving_lib');
        $this->db->query("SET autocommit=0");
        //Lock tables invovled in sale transaction so we don't have deadlock
        /* $this->db->query('LOCK TABLES ' . $this->db->dbprefix('suppliers') . ' WRITE, ' . $this->db->dbprefix('suppliers') . 'Read'); */
        //Update supplier store account balance
        if ($supplier_id > 0 ) {
			$balance=$this->total_balance($supplier_id);
            $this->db->set('balance', $balance+$total_venta,false);
            $this->db->where('person_id', $supplier_id);
            if (!$this->db->update('suppliers')) {

                $this->db->query("ROLLBACK");
                /* $this->db->query('UNLOCK TABLES'); */
                return -1;
            }
		}
		$balance=$this->total_balance($supplier_id);
            $store_account_transaction = array( 
                'supplier_id'=>$supplier_id,            
                'comment'=>"",
                'transaction_amount'=> $total_venta,
                'balance'=>$balance,
                'date' => date('Y-m-d H:i:s'),
                "movement_type"=>0, //0 add al saldo 
                "category"=>lang("receivings_receiving")
              );
              if (!$this->db->insert('store_payments',$store_account_transaction))
              {
                  $this->db->query("ROLLBACK");
                  $this->db->query('UNLOCK TABLES');
                  return -1;
              }	
        
        
        $this->db->query("COMMIT");
    // $this->db->query('UNLOCK TABLES');
        return true;
    }
	
	    /* receiving_id_abono id de la compra que se le desea a gregar un abaono
     */
    public function save_pay_cash($items, $supplier_id, $employee_id, $sold_by_employee_id, $comment, $show_comment_on_receipt, $payments, $receiving_id = false, $suspended = 0, $cc_ref_no = '', $auth_code = '', $change_receiving_date = false, $balance = 0, $store_account_payment = 0, $total = 0, $amount_change, $id_receiving = -1)
    {
      
      
        $this->load->library('receiving_lib');

        $payment_types = '';
        // cuanto pago
        $monton = $this->receiving_lib->get_total();
			
        $payment_types = $payment_types .$payments['payment_type'] . ': ' . to_currency($payments['payment_amount']) . '<br />';

        $receivings_data = array(
            "receiving_id" => null,
            'supplier_id' => $supplier_id > 0 ? $supplier_id : null,
            'employee_id' => $employee_id,
            'sold_by_employee_id' => $sold_by_employee_id,
            'payment_type' => $payment_types,
            'comment' => $comment,
            'show_comment_on_receipt' => $show_comment_on_receipt ? $show_comment_on_receipt : 0,
            'suspended' => $suspended,
            'deleted' => 0,
            'deleted_by' => null,
            'cc_ref_no' => $cc_ref_no,
            'auth_code' => $auth_code,
            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
            'register_id' => $this->Employee->get_logged_in_employee_current_register_id(),
            'store_account_payment' => $store_account_payment,
            "monton_total" => $monton,
        );
        $receivings_data['pay_cash_time'] = date('Y-m-d H:i:s');
        $supplier_info=$this->Supplier->get_info($supplier_id);
        $balence_previos=$supplier_info->balance;
		$balance=$this->Receiving->total_balance($supplier_id);
		$value=0;
		$this->db->query("SET autocommit=0");
		//Lock tables invovled in receiving transaction so we don't have deadlock
        $this->db->query('LOCK TABLES '.$this->db->dbprefix('suppliers').' WRITE, '.$this->db->dbprefix('store_accounts').
        ' WRITE, '.$this->db->dbprefix('pay_cash_payments').' WRITE, '.$this->db->dbprefix('pay_cash'));
			
		$store_account_payment_amount  = $this->receiving_lib->get_total();

	
		//Only update balance + store account payments if we are NOT an estimate (suspended = 2)
		
		     //Update supplier store account if payment made
			if($supplier_id > 0 && $store_account_payment_amount)
			{
				$this->db->set('balance',$balance,false);
				$this->db->where('person_id', $supplier_id);
				if (!$this->db->update('suppliers'))
				{
				
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
			 }
			
			if (!$this->db->insert('pay_cash',$receivings_data))
			{
	
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$receiving_id = $this->db->insert_id();
	
			 //insert store account transaction 

			if($supplier_id > 0 && $store_account_payment_amount)
			{
			 	

				//if($store_account_payment_amount>$balence_previos)
				//{
				//	$store_account_payment_amount=-$store_account_payment_amount;
				//}
				$balance=$this->total_balance($supplier_id);
			 	$store_payment_transaction = array( 
			      'supplier_id'=>$supplier_id,
			      'pay_cash_id'=>$receiving_id,
				  'comment'=>$comment,
			      'transaction_amount'=>$store_account_payment_amount,
				  'balance'=>$balance,
                  'date' => date('Y-m-d H:i:s'),
                  "movement_type"=>1,// restal del saldo
				  "abono" =>1,
                  "category"=>lang("receivings_store_account_payment")
				);
					 
				if (!$this->db->insert('store_payments',$store_payment_transaction))
				{
					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}			
			 }	 		 
         
          // se actualiza la caja y los registros
         /*  foreach ($payments as $payment_id => $payment) {  */
        /*  $payment_types = $payment_types .$payments['payment_type'] . ': ' . to_currency($payments['payment_amount']) . '<br />'; */
            $pay_cash_payments = array
                (
                'pay_cash_id' => $receiving_id,
                'payment_type' => $payments['payment_type'],
                'payment_amount' =>$monton,
                'payment_date' => date('Y-m-d H:i:s'),              
                );
				
                if (!$this->db->insert('pay_cash_payments', $pay_cash_payments)) {
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }
       
		 
		  $receiving_payments_data = array
                (
                'receiving_id' => $receiving_id,
                'payment_type' => $payment_types,
                'payment_amount' => $monton,
				'payment_date' =>date('Y-m-d H:i:s')                
            );
            // si se permite solo $amount_change en efectivo se coloca el if, de lo contrario no
            if ($payments['payment_type'] == lang('receiving_cash')) {

                if ($payment['payment_amount'] <= $amount_change_aux) {

                    $amount_change_aux = $amount_change_aux - $payment['payment_amount'];
                    $receiving_payments_data['payment_amount'] = 0;

                } else if ($payment['payment_amount'] > $amount_change_aux) {

                    $receiving_payments_data["payment_amount"] = $receiving_payments_data["payment_amount"] - $amount_change_aux;
                    $amount_change_aux = 0;
                }
            }

            if (!$this->db->insert('receivings_payments', $receiving_payments_data)) {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;
            }
/* 		} */
		if ($payments['payment_type']==lang('receivings_cash')){
          $description =lang("receivings_store_account_payment");
          $categorias_gastos=lang("receivings_store_account_payment");
          $cash = $monton*-1;
          if ($amount_change > 0) {
              $cash = $cash - $amount_change;
          }
          if ($cash < 0 && !$this->Register_movement->save($cash, $description,false,true,$categorias_gastos,false)) {
              $this->db->query("ROLLBACK");
              $this->db->query('UNLOCK TABLES');
              return -1;
          }	
		}  	
		
		$this->db->query("COMMIT");			
		$this->db->query('UNLOCK TABLES');	
	
		return $receiving_id;
    }
	
	public function get_payment_cash($payments)
    {
        $total_cash = 0;

        foreach ($payments as $value) {

            if ($value['payment_type'] == lang('receivings_cash')) {

                $total_cash += (float) $value['payment_amount'];
            }
        }

        return $total_cash;
    }
	
	function total_debitos($supplier_id){
		$this->db->select("sum(mount) as debitos");
		$this->db->from("receivings");
		$this->db->where('supplier_id', $supplier_id);
		$this->db->where('deleted', 0);
		$this->db->where('credit', 1);
		$consulta = $this->db->get();
		if($consulta->num_rows() == 1)
		{
	        $row = $consulta->row();
	        return $row->debitos;
		}
	}
	
	function total_creditos($supplier_id){
		$this->db->select("sum(transaction_amount) as creditos");
		$this->db->from("store_payments");
		$this->db->where('store_payments.supplier_id', $supplier_id);
	/* 	$this->db->where('movement_type', 1); */
		$this->db->where('abono', 1);
		$consulta = $this->db->get();
		if($consulta->num_rows() == 1)
		{
	        $row = $consulta->row();
			/* var_dump($row->creditos); */
	        return $row->creditos;
		}
	}
	
	function total_balance($supplier_id){
		$debitos=$this->total_debitos($supplier_id);
		$creditos=$this->total_creditos($supplier_id);
        return $debitos-$creditos;
	}
	
	function actualiza_balance_proveedor($supplier_id){
		$total_balance= $this->total_balance($supplier_id);
		$this->db->set('balance', $total_balance);
		$this->db->where('person_id', $supplier_id);
        $result=$this->db->update('suppliers');
	}
/*
	function delete_receipt()
	{
		$error = "";
    	$success = false;
    	$sale_info = $this->get_info($sale_id)->row(); //obtener informacion de la venta

    	$receiving_info = $this->Receiving->get_info($receiving_id)->row();

    	if ($this->config->item('track_cash') == 1 && $receiving_info->suspended != 2) { //Si el registro de movimiento de caja esta activo
    		
    		$register_log_id = $this->get_current_register_log($sale_info->register_id);//Obtener la session de la caja donde se facturo la venta

	    	if (!$register_log_id) {

	    		$error = "La caja de esta venta no esta abierta";
	    	
	    	} elseif ($this->subtract_payments_to_register($sale_id, $register_log_id->register_id) < 0) { //Obtener la diferencia del monto de la caja

	    		$error = "No tienes suficiente efectivo en caja para eliminar la venta";
	    		
	    	} elseif ($this->delete($sale_id)) {//Eliminar venta

	    			$cash = $this->get_previous_payments_cash($sale_id); //Obtener monto efectivo de la venta
					$this->Register_movement->save($cash * (-1),"Venta eliminada", $register_log_id->register_id); //Registar movimiento
					$success = true;
			}
    	
    	} else {

			if ($this->delete($sale_id)) { //Eliminar venta

				$success = true;
			}
    	}	

    	return compact('success', 'error');		


	}
*/


}
?>
