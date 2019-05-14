<?php

class Quote extends CI_Model
{

function save_quote($items,$customer_id,$employee_id, $sold_by_employee_id, $comment,$show_comment_on_receipt,$payments,$sale_id=false, $suspended = 0, $cc_ref_no = '', $auth_code = '', $change_sale_date=false,$balance=0, $store_account_payment = 0,
$overwrite_tax=false,$new_tax=null)
	{
		$this->load->library('sale_lib');
			
		if(count($items)==0)
			return -1;

		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).'<br />';
		}
		
		$tier_id = $this->sale_lib->get_selected_tier_id();
		
		if (!$tier_id)
		{
			$tier_id = NULL;
		}
		
		$sales_data = array(
			'customer_id'=> $customer_id > 0 ? $customer_id : null,
			'employee_id'=>$employee_id,
			'sold_by_employee_id' => $sold_by_employee_id,
			'payment_type'=>$payment_types,
			'comment'=>$comment,
			'show_comment_on_receipt'=> $show_comment_on_receipt ?  $show_comment_on_receipt : 0,
			'suspended'=>$suspended,
			'deleted' => 0,
			'deleted_by' => NULL,
			'cc_ref_no' => $cc_ref_no,
			'auth_code' => $auth_code,
			'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
			'register_id' => $this->Employee->get_logged_in_employee_current_register_id(),
			'store_account_payment' => $store_account_payment,
			'tier_id' => $tier_id ? $tier_id : NULL,
			"overwrite_tax"=>$overwrite_tax
		);
			
		if($sale_id)
		{
			$old_date=$this->Sale->get_info($sale_id)->row_array();
			$sales_data['sale_time']=date('Y-m-d H:i:s');
			
			
			
		}
		else
		{
			$sales_data['sale_time'] = date('Y-m-d H:i:s');
		}

		$this->db->query("SET autocommit=0");
		//Lock tables invovled in sale transaction so we don't have deadlock
		$this->db->query('LOCK TABLES '.$this->db->dbprefix('customers').' WRITE, '.$this->db->dbprefix('quotes').' WRITE, 
     	'.$this->db->dbprefix('quotes_items').' WRITE, '.$this->db->dbprefix('quotes_items').' WRITE, 
		'.$this->db->dbprefix('inventory').' WRITE, '.$this->db->dbprefix('sales_items_taxes').' WRITE,
		'.$this->db->dbprefix('sales_item_kits').' WRITE, '.$this->db->dbprefix('sales_item_kits_taxes').' WRITE,'.$this->db->dbprefix('people').' READ,'.$this->db->dbprefix('items').' READ
		,'.$this->db->dbprefix('employees_locations').' READ,'.$this->db->dbprefix('locations').' READ, '.$this->db->dbprefix('items_tier_prices').' READ');
			$store_account_payment_amount = 0;
		
		if ($store_account_payment)
		{
			$store_account_payment_amount = $this->sale_lib->get_total();
		}
		
		//Only update balance + store account payments if we are NOT an estimate (suspended = 2)
		
		 $previous_store_account_amount = 0;

		 if ($sale_id !== FALSE)
		 {
			 $previous_store_account_amount = $this->Sale->get_store_account_payment_total($sale_id);
		 }
		 
		if ($sale_id)
		{
			//Delete previoulsy sale so we can overwrite data
			if (!$this->Sale->delete($sale_id, true))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}

			if (!$this->db->insert('quotes',$sales_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$sale_id = $this->db->insert_id();
		}
		else
		{

			if (!$this->db->insert('quotes',$sales_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
			$sale_id = $this->db->insert_id();
		}
		
		$total_giftcard_payments = 0;

		foreach($payments as $payment_id=>$payment)
		{
			//Only update giftcard payments if we are NOT an estimate (suspended = 2)			

			$sales_payments_data = array
			(
				'quote_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount'],
				'payment_date' => $payment['payment_date'],
				'truncated_card' => $payment['truncated_card'],
				'card_issuer' => $payment['card_issuer'],
			);
			if (!$this->db->insert('quotes_payments',$sales_payments_data))
			{
				$this->db->query("ROLLBACK");
				$this->db->query('UNLOCK TABLES');
				return -1;
			}
		}
		//Only update store account payments if we are NOT an estimate (suspended = 2)		
		 
		$total_giftcard_payments = 0;
	
		$has_added_giftcard_value_to_cost_price = $total_giftcard_payments > 0 ? false : true;
		$store_account_item_id = $this->Item->get_store_account_item_id();
	
            
		foreach($items as $line=>$item)
		{
			if (isset($item['item_id']))
			{
				$cur_item_info = $this->Item->get_info($item['item_id']);
				$cur_item_location_info = $this->Item_location->get_info($item['item_id']);
				
				if ($item['item_id'] != $store_account_item_id)
				{
					$cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;
				}
				else // Set cost price = price so we have no profit
				{
					$cost_price = $item['price'];
				}
				
				
				if (!$this->config->item('disable_subtraction_of_giftcard_amount_from_sales'))
				{
					//Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
					if (!$has_added_giftcard_value_to_cost_price)
					{
						$cost_price+= $total_giftcard_payments / $item['quantity'];
						$has_added_giftcard_value_to_cost_price = true;
					}
				}
				$reorder_level = ($cur_item_location_info && $cur_item_location_info->reorder_level) ? $cur_item_location_info->reorder_level : $cur_item_info->reorder_level;
				
				if ($cur_item_info->tax_included)
				{
					$item['price'] = get_price_for_item_excluding_taxes($item['item_id'], $item['price']);
				}
				
				$sales_items_data = array
				(
					'quote_id'=>$sale_id,
					'item_id'=>$item['item_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'serialnumber'=>$item['serialnumber'],
					'quantity_purchased'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_cost_price' =>  to_currency_no_money($cost_price,10),
					'item_unit_price'=>$item['price'],
					'commission' => get_commission_for_item($item['item_id'],$item['price'],$item['quantity'], $item['discount']),
				);

				if (!$this->db->insert('quotes_items',$sales_items_data))
				{

					$this->db->query("ROLLBACK");
					$this->db->query('UNLOCK TABLES');
					return -1;
				}
				
			
				
		
				}
			}
			
			
		
		$customer = $this->Customer->get_info($customer_id); 
 			if ($customer_id == -1 or $customer->taxable)
 			{
				 
				foreach($items as $line=>$item)
				{
					
					if($overwrite_tax==1){
                        $query_result = $this->db->insert('quotes_items_taxes', array(
							'quote_id' 	=>$sale_id,
							'item_id' 	=>$item['item_id'],
							'line'      =>$item['line'],
							'name'		=>$new_tax['name'],
							'percent' 	=>$new_tax['percent'],
							'cumulative'=>$new_tax['cumulative']
						));
                        if (!$query_result) {
                            $this->db->query("ROLLBACK");
                            $this->db->query('UNLOCK TABLES');
                            return -1;
                        }                        
    
                    }else{
						foreach($this->Item_taxes_finder->get_info($item['item_id']) as $row)
						{
							$tax_name = $row['percent'].'% ' . $row['name'];
					
							//Only save sale if the tax has NOT been deleted
							if (!in_array($tax_name, $this->sale_lib->get_deleted_taxes()))
							{	
								$query_result = $this->db->insert('quotes_items_taxes', array(
									'quote_id' 	=>$sale_id,
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
			}
		$this->db->query("COMMIT");			
		$this->db->query('UNLOCK TABLES');	
	
		return $sale_id;				
	}
	
}
function receipt($sale_id)
	{
		//Before changing the sale session data, we need to save our current state in case they were in the middle of a sale
		$this->sale_lib->save_current_sale_state();
		
		$data['is_sale'] = FALSE;
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id, true);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['is_sale_cash_payment'] = $this->sale_lib->is_sale_cash_payment();
		$data['show_payment_times'] = TRUE;
		
		
		$tier_id = $sale_info['tier_id'];
		$tier_info = $this->Tier->get_info($tier_id);
		$data['tier'] = $tier_info->name;
		$data['register_name'] = $this->Register->get_register_name($sale_info['register_id']);

		$data['subtotal']=$this->sale_lib->get_subtotal($sale_id);
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['comment'] = $this->Sale->get_comment($sale_id);
		$data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt($sale_id);
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$sold_by_employee_id=$sale_info['sold_by_employee_id'];
		$sale_emp_info=$this->Employee->get_info($sold_by_employee_id);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name.($sold_by_employee_id && $sold_by_employee_id != $sale_info['employee_id'] ? '/'. $sale_emp_info->first_name.' '.$sale_emp_info->last_name: '');
		$data['ref_no'] = $sale_info['cc_ref_no'];
		$data['auth_code'] = $sale_info['auth_code'];
		$data['discount_exists'] = $this->_does_discount_exists($data['cart']);
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' - '.$cust_info->company_name).($cust_info->account_number==''  ? '' :' - '.$cust_info->account_number);
			$data['customer_address_1'] = $cust_info->address_1;
			$data['customer_address_2'] = $cust_info->address_2;
			$data['customer_city'] = $cust_info->city;
			$data['customer_state'] = $cust_info->state;
			$data['customer_zip'] = $cust_info->zip;
			$data['customer_country'] = $cust_info->country;
			$data['customer_phone'] = $cust_info->phone_number;
			$data['customer_email'] = $cust_info->email;
			
			if ($cust_info->balance !=0)
			{
				$data['customer_balance_for_sale'] = $cust_info->balance;
			}
		}		
		$data['sale_id']=$this->config->item('sale_prefix').' '.$sale_id;
		$data['sale_id_raw']=$sale_id;
		$data['store_account_payment'] = FALSE;
		
		foreach($data['cart'] as $item)
		{
			if ($item['name'] == lang('sales_store_account_payment'))
			{
				$data['store_account_payment'] = TRUE;
				break;
			}
		}
		
		if ($sale_info['suspended'] > 0)
		{
			if ($sale_info['suspended'] == 1)
			{
				$data['sale_type'] = lang('sales_layaway');
			}
			elseif ($sale_info['suspended'] == 2)
			{
				$data['sale_type'] = lang('sales_estimate');				
			}
		}
		
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();
		
		//Restore previous state saved above
		$this->sale_lib->restore_current_sale_state();
	}
