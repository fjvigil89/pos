<?php
class Sale_lib
{
	var $CI;
	
	//This is used when we need to change the sale state and restore it before changing it (The case of showing a receipt in the middle of a sale)
	var $sale_state;
	function __construct()
	{
		$this->CI =& get_instance();
		$this->sale_state = array();
	}
	function get_point($value_point,$total)

	{
		$value_purchase =$total;

		if($value_purchase > $value_point && $value_point!=0)
		{
			$point_pucharse = floor($value_purchase/$value_point);
		}
		else
		{
			$point_pucharse = 0;
		}
		return $point_pucharse;
	}
	function set_id_tier_item($line,$id_tier)
	{
		$items=$this->get_cart();
		if(isset($items[$line])){
			$items[$line]["id_tier"]=$id_tier;
			$this->set_cart($items);
			return true;
		}
		return false;
	}
	function get_rate_sale(){

		if($this->CI->session->userdata('rate_sale')==false){
			$employee_info = $this->CI->Employee->get_logged_in_employee_info(); 
			
			$rate=$this->CI->Employee->get_rate($employee_info->id_rate);
			if($employee_info->id_rate==2){
				$rate->sale_rate = $rate->sale_rate+( $rate->sale_rate*($this->CI->config->item("ganancia_distribuidor")/100));
			}
			$this->set_rate_sale($rate->sale_rate);
		}
		return $this->CI->session->userdata('rate_sale');
	}
	function set_rate_sale($rate){
		$this->CI->session->set_userdata('rate_sale',$rate);
	}
	function set_new_tax($new_tax){
		$this->CI->session->set_userdata('new_tax',$new_tax);
	}
	function get_new_tax(){
		return $this->CI->session->userdata('new_tax');
	}
	function set_overwrite_tax($overwrite_tax){
		$this->CI->session->set_userdata('overwrite_tax',$overwrite_tax);
	}
	function get_overwrite_tax(){
		return $this->CI->session->userdata('overwrite_tax') == 1 ?true:false ;
	}
	function get_rate_price(){

		if($this->CI->session->userdata('rate_price')==false){
			$employee_info = $this->CI->Employee->get_logged_in_employee_info(); 
			$rate=$this->CI->Employee->get_rate($employee_info->id_rate);
			$this->set_rate_price($rate->sale_rate);
		}
		return $this->CI->session->userdata('rate_price');
	}
	function set_rate_price($rate){
		$this->CI->session->set_userdata('rate_price',$rate);
	}
	function get_rate_buy(){

		if($this->CI->session->userdata('rate_buy')==false){
			$employee_info = $this->CI->Employee->get_logged_in_employee_info();            
			$rate=$this->CI->Employee->get_rate($employee_info->id_rate);
			$this->set_rate_buy($rate->rate_buy);
		}
		return $this->CI->session->userdata('rate_buy');
	}
	function set_rate_buy($rate){
		$this->CI->session->set_userdata('rate_buy',$rate);
	}
	function set_rate_items($rate){
		$items = $this->get_cart();
		foreach ($items as $item){
			$items[$item["line"]]["tasa"]=$rate;

		}
		$this->set_cart($items);
	}
	function get_cart()
	{
		if($this->CI->session->userdata('cart') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cart');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart',$cart_data);
	}

	//Alain Multiple Payments
	function get_payments()
	{
		if($this->CI->session->userdata('payments') === false)
			$this->set_payments(array());

		return $this->CI->session->userdata('payments');
	}
	
	//Alain Multiple Payments
	function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('payments',$payments_data);
	}
	function set_opcion_sale($opcion_sale)
	{
		$this->CI->session->set_userdata('opcion_sale',$opcion_sale);
	}
	function set_divisa($divisa)
	{
		$this->CI->session->set_userdata('divisa',$divisa);
	}
	function get_divisa()
	{
		return $this->CI->session->userdata('divisa')? $this->CI->session->userdata('divisa'):$this->CI->config->item("divisa");
	}
	function clear_divisa()
	{
		$this->CI->session->unset_userdata('divisa');
	}
	function clear_pagar_otra_moneda(){
		$this->CI->session->unset_userdata('pagar_otra_moneda');

	}
	function clear_overwrite_tax()
	{
		$this->CI->session->unset_userdata('overwrite_tax');
	}

	function clear_new_tax()
	{
		$this->CI->session->unset_userdata('new_tax');
	}
	function clear_rate_price()
	{
		$this->CI->session->unset_userdata('rate_price');
	}
	function clear_rate()
	{
		$this->CI->session->unset_userdata('rate_sale');
		$this->CI->session->unset_userdata('rate_buy');
	}
	function get_opcion_sale()
	{
		return $this->CI->session->userdata('opcion_sale')? $this->CI->session->userdata('opcion_sale'):"venta";
	}
	
	function clear_opcion_sale()
	{
		$this->CI->session->unset_userdata('opcion_sale');
	}
	function change_credit_card_payments_to_partial()
	{
		$payments=$this->get_payments();
		
		foreach($payments as $payment_id=>$payment)
		{
			//If we have a credit payment, change it to partial credit card so we can process again
			if ($payment['payment_type'] == lang('sales_credit'))
			{
				$payments[$payment_id] =  array(
					'payment_type'=>lang('sales_partial_credit'),
					'payment_amount'=>$payment['payment_amount'],
					'payment_date' => $payment['payment_date'] !== FALSE ? $payment['payment_date'] : date('Y-m-d H:i:s'),
					'truncated_card' => $payment['truncated_card'],
					'card_issuer' => $payment['card_issuer'],
					);
			}
		}
		
		$this->set_payments($payments);
	}
	
	function get_change_sale_date() 
	{
		return $this->CI->session->userdata('change_sale_date') ? $this->CI->session->userdata('change_sale_date') : '';
	}
	function clear_change_sale_date() 	
	{
		$this->CI->session->unset_userdata('change_sale_date');
		
	}
	function clear_change_sale_date_enable() 	
	{
		$this->CI->session->unset_userdata('change_sale_date_enable');
	}
	function set_change_sale_date_enable($change_sale_date_enable)
	{
		$this->CI->session->set_userdata('change_sale_date_enable',$change_sale_date_enable);
	}
	
	function get_change_sale_date_enable() 
	{
		return $this->CI->session->userdata('change_sale_date_enable') ? $this->CI->session->userdata('change_sale_date_enable') : '';
	}
	
	function set_change_sale_date($change_sale_date)
	{
		$this->CI->session->set_userdata('change_sale_date',$change_sale_date);
	}
	
	function get_comment() 
	{
		return $this->CI->session->userdata('comment') ? $this->CI->session->userdata('comment') : '';
	}
	function get_ntable() 
	{
		return $this->CI->session->userdata('ntable') ? $this->CI->session->userdata('ntable') : '';
	}
	function get_comment_ticket() 
	{
		return $this->CI->session->userdata('show_comment_ticket') ? $this->CI->session->userdata('show_comment_ticket') : '';
	}

	
	function set_sale_id($sale_id)
	{
		$this->CI->session->set_userdata('sale_id',$sale_id);
	}
	function get_comment_on_receipt() 
	{
		return $this->CI->session->userdata('show_comment_on_receipt') ? $this->CI->session->userdata('show_comment_on_receipt') : '';
	}
    
	function get_show_receipt() 
	{
		return $this->CI->session->userdata('show_receipt') ? $this->CI->session->userdata('show_receipt') : '';
	}

	function set_comment($comment) 
	{
		$this->CI->session->set_userdata('comment', $comment);
	}
	
	function set_ntable($ntable) 
	{
		$this->CI->session->set_userdata('ntable', $ntable);
	}
	
	function get_selected_tier_id() 
	{
		return $this->CI->session->userdata('selected_tier_id') ? $this->CI->session->userdata('selected_tier_id') : FALSE;
	}

	function get_previous_tier_id() 
	{
		return $this->CI->session->userdata('previous_tier_id') ? $this->CI->session->userdata('previous_tier_id') : FALSE;
	}

	function set_selected_tier_id($tier_id, $change_price = true) 
	{
		$this->CI->session->set_userdata('previous_tier_id', $this->get_selected_tier_id());
		$this->CI->session->set_userdata('selected_tier_id', $tier_id);
		
		if ($change_price == true)
		{
			$this->change_price();
		}
	}
	
	function clear_selected_tier_id()	
	{
		$this->CI->session->unset_userdata('previous_tier_id');
		$this->CI->session->unset_userdata('selected_tier_id');
	}
	
	function set_comment_on_receipt($comment_on_receipt) 
	{
		$this->CI->session->set_userdata('show_comment_on_receipt', $comment_on_receipt);
	}
    
	function set_show_receipt($show_receipt) 
	{
		$this->CI->session->set_userdata('show_receipt', $show_receipt);
	}
    
	function set_comment_ticket($comment_ticket) 
	{
		$this->CI->session->set_userdata('show_comment_ticket', $comment_ticket);
	}

	function clear_comment() 	
	{
		$this->CI->session->unset_userdata('comment');
		
	}
	
	
	function clear_ntable(){
		$this->CI->session->unset_userdata('ntable');
	}
	
	function clear_show_comment_on_receipt() 	
	{
		$this->CI->session->unset_userdata('show_comment_on_receipt');
		
	}
	function clear_show_receipt() 	
	{
		$this->CI->session->unset_userdata('show_receipt');
	}
	function clear_show_comment_ticket() 	
	{
		$this->CI->session->unset_userdata('show_comment_ticket');
		
	}
	
	function get_email_receipt() 
	{
		return $this->CI->session->userdata('email_receipt');
	}

	function set_email_receipt($email_receipt) 
	{
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() 	
	{
		$this->CI->session->unset_userdata('email_receipt');
	}
	
	function get_deleted_taxes() 
	{
		$deleted_taxes = $this->CI->session->userdata('deleted_taxes') ? $this->CI->session->userdata('deleted_taxes') : array();
		return $deleted_taxes;
	}

	function add_deleted_tax($name) 
	{
		$deleted_taxes = $this->CI->session->userdata('deleted_taxes') ? $this->CI->session->userdata('deleted_taxes') : array();
		
		if (!in_array($name, $deleted_taxes))
		{
			$deleted_taxes[] = $name;
		}
		$this->CI->session->set_userdata('deleted_taxes', $deleted_taxes);
	}
	
	function set_deleted_taxes($deleted_taxes)
	{
		$this->CI->session->set_userdata('deleted_taxes', $deleted_taxes);		
	}

	function clear_deleted_taxes() 	
	{
		$this->CI->session->unset_userdata('deleted_taxes');
	}	
	
	function get_save_credit_card_info() 
	{
		return $this->CI->session->userdata('save_credit_card_info');
	}

	function set_save_credit_card_info($save_credit_card_info) 
	{
		$this->CI->session->set_userdata('save_credit_card_info', $save_credit_card_info);
	}

	function clear_save_credit_card_info() 	
	{
		$this->CI->session->unset_userdata('save_credit_card_info');
	}
	
	function get_use_saved_cc_info() 
	{
		return $this->CI->session->userdata('use_saved_cc_info');
	}

	function set_use_saved_cc_info($use_saved_cc_info) 
	{
		$this->CI->session->set_userdata('use_saved_cc_info', $use_saved_cc_info);
	}

	function clear_use_saved_cc_info() 	
	{
		$this->CI->session->unset_userdata('use_saved_cc_info');
	}
	
	function get_partial_transactions()
	{
		return $this->CI->session->userdata('partial_transactions');
	}
	
	function set_partial_transactions($partial_transactions)
	{
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}
	
	function add_partial_transaction($partial_transaction)
	{
		$partial_transactions = $this->CI->session->userdata('partial_transactions');
		$partial_transactions[] = $partial_transaction;
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}
	
	function delete_partial_transactions()
	{
		$this->CI->session->unset_userdata('partial_transactions');
	}
	
	
	function get_sold_by_employee_id() 
	{
		if ($this->CI->config->item('default_sales_person') != 'not_set' && !$this->CI->session->userdata('sold_by_employee_id'))
		{
			$employee_id=$this->CI->Employee->get_logged_in_employee_info()->person_id;
			return $employee_id;
		}
		return $this->CI->session->userdata('sold_by_employee_id') ? $this->CI->session->userdata('sold_by_employee_id') : NULL;
	}

	function set_sold_by_employee_id($sold_by_employee_id) 
	{
		$this->CI->session->set_userdata('sold_by_employee_id', $sold_by_employee_id);
	}

	function clear_sold_by_employee_id() 	
	{
		$this->CI->session->unset_userdata('sold_by_employee_id');
	}

	function add_payment($payment_type,$payment_amount,$payment_date = false, $truncated_card = '', $card_issuer = '')
	{
		$payments=$this->get_payments();
		$payment = array(
			'payment_type'=>$payment_type,
			'payment_amount'=>$payment_amount,
			'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s'),
			'truncated_card' => $truncated_card,
			'card_issuer' => $card_issuer,
			);
		
		$payments[]=$payment;
		$this->set_payments($payments);
		return true;
	}
	
	function edit_payment($payment_id, $payment_type, $payment_amount,$payment_date = false, $truncated_card = '', $card_issuer = '')
	{
		$payments=$this->get_payments();
		$payment = array(
			'payment_type'=>$payment_type,
			'payment_amount'=>$payment_amount,
			'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s'),
			'truncated_card' => $truncated_card,
			'card_issuer' => $card_issuer,
			);
		
		$payments[$payment_id]=$payment;
		$this->set_payments($payments);
		return true;
	}
	
	public function get_payment_ids($payment_type)
	{
		$payment_ids = array();
		
		$payments=$this->get_payments();
		
		for($k=0;$k<count($payments);$k++)
		{
			if ($payments[$k]['payment_type'] == $payment_type)
			{
				$payment_ids[] = $k;
			}
		}
		
		return $payment_ids;
	}
	
	public function get_payment_amount($payment_type)
	{
		$payment_amount = 0;
		if (($payment_ids = $this->get_payment_ids($payment_type)) !== FALSE)
		{
			$payments=$this->get_payments();
			
			foreach($payment_ids as $payment_id)
			{
				$payment_amount += $payments[$payment_id]['payment_amount'];
			}
		}
		
		return $payment_amount;
	}
	
	//Alain Multiple Payments
	function delete_payment($payment_ids)
	{
		$payments=$this->get_payments();
		if (is_array($payment_ids))
		{
			foreach($payment_ids as $payment_id)
			{
				unset($payments[$payment_id]);
			}
		}
		else
		{
			unset($payments[$payment_ids]);			
		}
		$this->set_payments(array_values($payments));
	}
	
	function get_price_for_item($item_id, $tier_id = FALSE)
	{
		if ($tier_id === FALSE )
		{
			$tier_id = $this->get_selected_tier_id();
		}
		
		$item_info = $this->CI->Item->get_info($item_id);
		$item_location_info = $this->CI->Item_location->get_info($item_id);
		
		$item_tier_row = $this->CI->Item->get_tier_price_row($tier_id, $item_id);
		$item_location_tier_row = $this->CI->Item_location->get_tier_price_row($tier_id, $item_id, $this->CI->Employee->get_logged_in_employee_current_location_id());
		
		if (!empty($item_location_tier_row) && $item_location_tier_row->unit_price)
		{
			return to_currency_no_money($item_location_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_location_tier_row) && $item_location_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_location_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->unit_price)
		{
			return to_currency_no_money($item_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_tier_row) && $item_tier_row->percent_off)
		{
			$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
			return to_currency_no_money($item_unit_price *(1-($item_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		else
		{
			$today =  strtotime(date('Y-m-d'));
			$is_item_location_promo = ($item_location_info->start_date !== NULL && $item_location_info->end_date !== NULL) && (strtotime($item_location_info->start_date) <= $today && strtotime($item_location_info->end_date) >= $today);
			$is_item_promo = $this->CI->Sale->get_promo_quantity($item_id) > 0  && ($item_info->start_date !== NULL && $item_info->end_date !== NULL) && (strtotime($item_info->start_date) <= $today && strtotime($item_info->end_date) >= $today);
			
			if ($is_item_location_promo)
			{
				return to_currency_no_money($item_location_info->promo_price, 10);
			}
			elseif ($is_item_promo)
			{
				return to_currency_no_money($item_info->promo_price, 10);
			}
			else
			{
				$item_unit_price = $item_location_info->unit_price ? $item_location_info->unit_price : $item_info->unit_price;
				return to_currency_no_money($item_unit_price, 10);
			}
		}			
		
	}
	
	function get_price_for_item_kit($item_kit_id, $tier_id = FALSE)
	{
		if ($tier_id === FALSE)
		{
			$tier_id = $this->get_selected_tier_id();
		}
		
		$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
		$item_kit_location_info = $this->CI->Item_kit_location->get_info($item_kit_id);
		
		$item_kit_tier_row = $this->CI->Item_kit->get_tier_price_row($tier_id, $item_kit_id);
		$item_kit_location_tier_row = $this->CI->Item_kit_location->get_tier_price_row($tier_id, $item_kit_id, $this->CI->Employee->get_logged_in_employee_current_location_id());
		
		if (!empty($item_kit_location_tier_row) && $item_kit_location_tier_row->unit_price)
		{
			return to_currency_no_money($item_kit_location_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_kit_location_tier_row) && $item_kit_location_tier_row->percent_off)
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price *(1-($item_kit_location_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_kit_tier_row) && $item_kit_tier_row->unit_price)
		{
			return to_currency_no_money($item_kit_tier_row->unit_price, $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		elseif (!empty($item_kit_tier_row) && $item_kit_tier_row->percent_off)
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price *(1-($item_kit_tier_row->percent_off/100)), $this->CI->config->item('round_tier_prices_to_2_decimals') ? 2 : 10);
		}
		else
		{
			$item_kit_unit_price = $item_kit_location_info->unit_price ? $item_kit_location_info->unit_price : $item_kit_info->unit_price;
			return to_currency_no_money($item_kit_unit_price, 10);
		}		
	}	
	
	function empty_payments()
	{
		$this->CI->session->unset_userdata('payments');
	}

	//Alain Multiple Payments
	function get_payments_totals_excluding_store_account()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
			if($payments['payment_type'] != lang('sales_store_account'))
			{
				$subtotal+=$payments['payment_amount'];
			}	
		}
		return to_currency_no_money($subtotal);
	}

	function get_payments_totals()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
			$subtotal+=$payments['payment_amount'];
		}

		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total=$this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_amount_due_round($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total= $this->CI->config->item('round_cash_on_sales') ?  round_to_nearest_05($this->get_total($sale_id)) : $this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer()
	{
		if(!$this->CI->session->userdata('customer'))
			$this->set_customer(-1, false);

		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id, $change_price = true)
	{
		if (is_numeric($customer_id))
		{
			$this->CI->session->set_userdata('customer',$customer_id);
			
			if ($change_price == true)
			{
				$this->change_price();
			}
		}
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode',$mode);
	}
	
	/*
	* This function is called when a customer added or tier changed
	* It scans item and item kits to see if there price is at a default value
	* If a price is at a default value, it is changed to match the tier
	*/
	function change_price()
	{
		$items = $this->get_cart();
		foreach ($items as $item )
		{
			if (isset($item['item_id']))
			{
				$line=$item['line'];
				$price=$item['price'];
				$item_id=$item['item_id'];
				$item_info = $this->CI->Item->get_info($item_id);
				$item_location_info = $this->CI->Item_location->get_info($item_id);
				$previous_price = FALSE;
				
				if ($previous_tier_id = $this->get_previous_tier_id())
				{
					$previous_price = $this->get_price_for_item($item_id, $previous_tier_id);
				}
				$previous_price = to_currency_no_money($previous_price, 10);
				$price = to_currency_no_money($price, 10);
				
				if($price==$item_info->unit_price || $price == $item_location_info->unit_price || $price == $previous_price )
				{	
					$items[$line]['price']= $this->get_price_for_item($item_id);		
				}
			}
			elseif(isset($item['item_kit_id']))
			{
				$line=$item['line'];
				$price=$item['price'];
				$item_kit_id=$item['item_kit_id'];
				$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
				$item_kit_location_info = $this->CI->Item_kit_location->get_info($item_kit_id);
				$previous_price = FALSE;
				
				if ($previous_tier_id = $this->get_previous_tier_id())
				{
					$previous_price = $this->get_price_for_item_kit($item_kit_id, $previous_tier_id);
				}
				
				$previous_price = to_currency_no_money($previous_price, 10);
				$price = to_currency_no_money($price, 10);
				
				if($price==$item_kit_info->unit_price || $price == $item_kit_location_info->unit_price || $price == $previous_price )
				{
					$items[$line]['price']= $this->get_price_for_item_kit($item_kit_id);		
				}
			}
		}
		$this->set_cart($items);
	}
	function set_pagar_otra_moneda($value){
		$this->CI->session->set_userdata('pagar_otra_moneda',$value);

	}
	function get_pagar_otra_moneda(){
		return $this->CI->session->userdata('pagar_otra_moneda') ? $this->CI->session->userdata('pagar_otra_moneda') : 0;
	}
	function change_price_item($line,$id_tier)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{ $item=$items[$line];
			if (isset($item['item_id']))
			{			
				$item_id=$item['item_id'];			
				if ($id_tier)
				{
					$items[$line]['price'] = $this->get_price_for_item($item_id, $id_tier);
				} else{
					$items[$line]['price']= $this->get_price_for_item($item_id);
				}
				
			}
			elseif(isset($item['item_kit_id']))
			{			
				$item_kit_id=$item['item_kit_id'];				
				if ($id_tier)
				{
					$items[$line]['price'] = $this->get_price_for_item_kit($item_kit_id, $id_tier);
				}			
				else{
					$items[$line]['price']= $this->get_price_for_item_kit($item_kit_id);		
				}
			}
		}
		$this->set_cart($items);
	}
	function get_price_by_tier_and_item($id_item_or_kit,$id_tier)
	{
		if($this->CI->Item->exists($id_item_or_kit ))	
		{
			$tax_info = $this->CI->Item_taxes_finder->get_info($id_item_or_kit) ;
			$sum_tax=0;
			$price=$this->get_price_for_item($id_item_or_kit, $id_tier);
			foreach($tax_info as $key=>$tax)
			{
				$sum_tax+=$tax['percent']/100;
			}
			$value_tax=$price*$sum_tax;
			$price_with_tax=$price+$value_tax;
			return $price_with_tax;	
			
		}
		elseif( $this->CI->Item_kit->exists($id_item_or_kit))			
		{
			$tax_kit_info = $this->CI->Item_kit_taxes_finder->get_info($id_item_or_kit) ;
			$sum_tax=0;
			$price=$this->get_price_for_item_kit($id_item_or_kit, $id_tier);

			foreach($tax_kit_info as $key=>$tax)
			{
			  $sum_tax+=$tax['percent']/100;
			}
			$value_tax=$price*$sum_tax;
			$price_with_tax=$price+$value_tax;
			return $price_with_tax;					
		}
		
	}
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null, $force_add = FALSE, $line = FALSE,$custom1_subcategory=null,$custom2_subcategory=null,$no_valida_por_id=false,
	$numero_cuenta=null,$numero_documento=null,$titular_cuenta=null,$tasa=null,$tipo_documento=null,$id_tier=0,$transaction_status=null,$comentarios=null,
	$fecha_estado=null,$tipo_cuenta=null,$observaciones=null,$celular=null)
	{
		$store_account_item_id = $this->CI->Item->get_store_account_item_id();
		
		//Do NOT allow item to get added unless in store_account_payment mode
		if (!$force_add && $this->get_mode() !=='store_account_payment' && $store_account_item_id == $item_id)
		{
			return FALSE;
		}
		
		//make sure item exists
		 
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1) ||  $no_valida_por_id )	
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		else
		{
			$item_id = (int)$item_id;
		}
		
		$item_info = $this->CI->Item->get_info($item_id);
		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
			//Also, we have stored the key in the element itself so we can compare.
			// cuano en configuracion no esta la subcategoria activa custom1_subcategory custom2_subcategory serán null

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}
			
			if(isset($item['item_id']) && $item['item_id']==$item_id &&  $item['custom1_subcategory']== $custom1_subcategory && $item['custom2_subcategory']== $custom2_subcategory )
			{
				
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
				
				if($item_info->description==$items[$updatekey]['description'] && $item_info->name==lang('sales_giftcard'))
				{
					return false;
				}
			
			}
			
		}

		$insertkey=$maxkey+1;
		if($this->CI->config->item("subcategory_of_items")==1 and $this->CI->config->item("inhabilitar_subcategory1")==1 and $custom1_subcategory==null){

			$custom1_subcategory="»";
		}

		$today =  strtotime(date('Y-m-d'));
		$price_to_use= $this->get_price_for_item($item_id);		
		
		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($line === FALSE ? $insertkey : $line)=>
			array(
				'item_id'=>$item_id,
				'line'=>$line === FALSE ? $insertkey : $line,
				'name'=>$item_info->name,
				'size' => $item_info->size,
				'model'=>$item_info->model,
				'colour'=>$item_info->colour,
				'marca'=>$item_info->marca,
				'unit'=>$item_info->unit,
				'product_id' => $item_info->product_id,
				'description'=>$description!=null ? $description: $item_info->description,
				'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
				'allow_alt_description'=>$item_info->allow_alt_description,
				'is_serialized'=>$item_info->is_serialized,
				'quantity'=>$quantity,
				'item_number'=>$item_info->item_number,
				'discount'=>$discount,
				'custom1_subcategory'=>$custom1_subcategory,
				'custom2_subcategory'=>$custom2_subcategory,
				'price'=>$price!=null ? $price:$price_to_use,
				'has_subcategory'=>$item_info->subcategory,
				"id_tier"=>$id_tier,
				"numero_cuenta"=>$numero_cuenta,
				"numero_documento"=>$numero_documento,
				"titular_cuenta"=>$titular_cuenta,
				"tipo_documento"=> $tipo_documento,
				"tasa"=>$tasa,
				"transaction_status"=>$transaction_status,
				"comentarios"=>$comentarios,
				"fecha_estado"=>$fecha_estado,
				"tipo_cuenta"=>$tipo_cuenta,
				"observaciones"=>$observaciones,
				"celular"=>$celular
				)
			);
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($item_info->is_serialized ==0) && $this->CI->config->item('activar_casa_cambio')==0)
		{
			$items[$line === FALSE ? $updatekey : $line]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}
		
		if( $this->CI->config->item('sales_stock_inventory') and $this->out_of_stock($item_id) )
		{
			$items=$this->get_cart();
		}
		$this->set_cart($items);
		return true;

	}
	
	function add_item_kit($external_item_kit_id_or_item_number,$quantity=1,$discount=0,$price=null,$description=null, $line=FALSE,$id_tier=0)
	{
		if (strpos(strtolower($external_item_kit_id_or_item_number), 'kit') !== FALSE)
		{
			//KIT #
			$pieces = explode(' ',$external_item_kit_id_or_item_number);
			$item_kit_id = (int)$pieces[1];	
		}
		else
		{
			$item_kit_id = $this->CI->Item_kit->get_item_kit_id($external_item_kit_id_or_item_number);
		}
		
		
		//make sure item exists
		if(!$this->CI->Item_kit->exists($item_kit_id))	
		{
			return false;
		}

		$item_kit_info = $this->CI->Item_kit->get_info($item_kit_id);
		
		if ( $item_kit_info->unit_price == null)
		{
			foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
			{
				for($k=0;$k<$item_kit_item->quantity;$k++)
				{
					$this->add_item($item_kit_item->item_id, $quantity);
				}
			}
			
			return true;
		}
		else
		{
			$items = $this->get_cart();

	        //We need to loop through all items in the cart.
	        //If the item is already there, get it's key($updatekey).
	        //We also need to get the next key that we are going to use in case we need to add the
	        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

	        $maxkey=0;                       //Highest key so far
	        $itemalreadyinsale=FALSE;        //We did not find the item yet.
			$insertkey=0;                    //Key to use for new entry.
			$updatekey=0;                    //Key to use to update(quantity)

			foreach ($items as $item)
			{
	            //We primed the loop so maxkey is 0 the first time.
	            //Also, we have stored the key in the element itself so we can compare.

				if($maxkey <= $item['line'])
				{
					$maxkey = $item['line'];
				}

				if(isset($item['item_kit_id']) && $item['item_kit_id']==$item_kit_id)
				{
					$itemalreadyinsale=TRUE;
					$updatekey=$item['line'];
				}
			}

			$insertkey=$maxkey+1;
			
			$price_to_use=$this->get_price_for_item_kit($item_kit_id);

			//array/cart records are identified by $insertkey and item_id is just another field.
			$item = array(($line === FALSE ? $insertkey : $line)=>
				array(
					'item_kit_id'=>$item_kit_id,
					'line'=>$line === FALSE ? $insertkey : $line,
					'item_kit_number'=>$item_kit_info->item_kit_number,
					'product_id'=>$item_kit_info->product_id,
					'name'=>$item_kit_info->name,
					'size' => '',
					'description'=>$description!=null ? $description: $item_kit_info->description,
					'quantity'=>$quantity,
					'discount'=>$discount,
					'price'=>$price!=null ? $price: $price_to_use,
					"id_tier"=>$id_tier,
					"has_subcategory"=>0
					)
				);

			//Item already exists and is not serialized, add to quantity
			if($itemalreadyinsale)
			{
				$items[$line === FALSE ? $updatekey : $line]['quantity']+=$quantity;
			}
			else
			{
				//add to existing array
				$items+=$item;
			}

			$this->set_cart($items);
			return true;
		}
	}
	
	function discount_all($percent_discount)
	{
		$items = $this->get_cart();
		
		foreach(array_keys($items) as $key)
		{
			$items[$key]['discount'] = $percent_discount;
		}
		$this->set_cart($items);
		return true;
	}
	
	function out_of_stock($item_id,$no_valida_por_id=false)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id) || $no_valida_por_id)
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item_location_quantity = $this->CI->Item_location->get_location_quantity($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);
		
		//If $item_location_quantity is NULL we don't track quantity
		if ($item_location_quantity !== NULL && $item_location_quantity - $quanity_added <= 0)
		{
			return true;
		}
		
		return false;
	}
	function out_of_stock_subcategory($item_id,$custom1,$custom2, $quantity_added)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item_location_quantity_subcategory = $this->CI->items_subcategory->get_quantity($item_id, false, $custom1,$custom2);
		//$quanity_added = $this->get_quantity_already_added($item_id);
		
		//If $item_location_quantity_subcategory is NULL we don't track quantity
		if ($item_location_quantity_subcategory !== NULL && $item_location_quantity_subcategory - $quantity_added <0)
		{
			return true;
		}
		
		return false;
	}
	
	function out_of_stock_kit($kit_id)
	{
	    //Make sure Item kit exist
		if(!$this->CI->Item_kit->exists($kit_id)) return FALSE;

	    //Get All Items for Kit
		$kit_items = $this->CI->Item_kit_items->get_info($kit_id);

	    //Check each item
		foreach ($kit_items as $item)
		{
			$item_location_quantity = $this->CI->Item_location->get_location_quantity($item->item_id);
			$item_already_added = $this->get_quantity_already_added($item->item_id);

			if ($item_location_quantity - $item_already_added < 0)
			{
				return true;
			}	
		}
		return false;
	}

	function get_quantity_already_added($item_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}
		
		//Check Item Kist for this item
		$all_kits = $this->CI->Item_kit_items->get_kits_have_item($item_id);

		foreach($all_kits as $kits)
		{
			$kit_quantity = $this->get_kit_quantity_already_added($kits['item_kit_id']);
			if($kit_quantity > 0)
			{
				$quanity_already_added += ($kit_quantity * $kits['quantity']);
			}
		}
		return $quanity_already_added;
	}
	
	function get_kit_quantity_already_added($kit_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['item_kit_id']) && $item['item_kit_id']==$kit_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}

		return $quanity_already_added;
	}

	function get_item_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['item_id']) ? $item['item_id'] : -1;
			}
		}
		
		return -1;
	}

	function get_kit_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['item_kit_id']) ? $item['item_kit_id'] : -1;
			}
		}
		return -1;
	}

	function is_kit_or_item($line_to_get)
	{
		$items = $this->get_cart();
		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				if(isset($item['item_id']))
				{
					return 'item';
				}
				elseif ($item['item_kit_id'])
				{
					return 'kit';
				}
			}
		}
		return -1;
	}

	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE, $custom1_subcategory=false,$custom2_subcategory=false,$numero_cuenta=false,
	$numero_documento=FAlSE,$titular_cuenta=FALSE,$tipo_documento=FALSE,$tipo_cuenta=FALSE,$observaciones=FALSE,$celular=FALSE)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			if ($description !== FALSE ) {
				$items[$line]['description'] = $description;
			}
			if ($serialnumber !== FALSE ) {
				$items[$line]['serialnumber'] = $serialnumber;
			}
			if ($quantity !== FALSE ) {
				$items[$line]['quantity'] = $quantity;
			}
			if ($discount !== FALSE ) {
				$items[$line]['discount'] = $discount;
			}
			if ($price !== FALSE ) {
				$items[$line]['price'] = $price;
			}
			if ($custom1_subcategory !== FALSE ) {
				$items[$line]['custom1_subcategory'] = $custom1_subcategory;
				$items[$line]['custom2_subcategory'] = "";
			}
			if ($custom2_subcategory !== FALSE ) {
				$items[$line]['custom2_subcategory'] = $custom2_subcategory;
			}
			if($numero_cuenta!==false){
				$items[$line]['numero_cuenta'] = $numero_cuenta;
			}
			if($numero_documento!==FALSE){
				$items[$line]['numero_documento'] = $numero_documento;
			}
			if($titular_cuenta!==FALSE){
				$items[$line]['titular_cuenta'] = $titular_cuenta;
			}
			if($tipo_documento!==FALSE){
				$items[$line]['tipo_documento'] = $tipo_documento;
			}
			if($tipo_cuenta!==FALSE){
				$items[$line]['tipo_cuenta'] = $tipo_cuenta;
			}
			if($observaciones!==FALSE){
				$items[$line]['observaciones'] = $observaciones;
			}
			if($celular!==FALSE){
				$items[$line]['celular'] = $celular;
			}
			
			$this->set_cart($items);
			
			return true;
		}

		return false;
	}

	function is_valid_receipt($receipt_sale_id)
	{		
		//Valid receipt syntax
		if(strpos(strtolower($receipt_sale_id), strtolower($this->CI->config->item('sale_prefix')).' ') !== FALSE)
		{
			//Extract the id
			$sale_id = substr(strtolower($receipt_sale_id), strpos(strtolower($receipt_sale_id),$this->CI->config->item('sale_prefix').' ') + strlen(strtolower($this->CI->config->item('sale_prefix')).' '));
			return $this->CI->Sale->exists($sale_id);
		}

		return false;
	}
	
	function is_valid_item_kit($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && strtolower($pieces[0]) == 'kit')
		{
			return $this->CI->Item_kit->exists($pieces[1]);
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id) !== FALSE;
		}
	}

	function get_valid_item_kit_id($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && strtolower($pieces[0]) == 'kit')
		{
			return $pieces[1];
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id);
		}
	}

	function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$sale_id = substr(strtolower($receipt_sale_id), strpos(strtolower($receipt_sale_id),$this->CI->config->item('sale_prefix').' ') + strlen(strtolower($this->CI->config->item('sale_prefix')).' '));

		$this->empty_cart();
		$this->delete_customer(false);
		$sale_taxes = $this->get_taxes($sale_id);
		
		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$item_info = $this->CI->Item->get_info($row->item_id);
			$price_to_use = $row->item_unit_price;			
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_info->tax_included && empty($sale_taxes))
			{
				$price_to_use = get_price_for_item_including_taxes($row->item_id, $row->item_unit_price);
			}
			elseif($item_info->tax_included)
			{
				$price_to_use = get_price_for_item_including_taxes($row->line, $row->item_unit_price,$sale_id);				
			}
			
			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description,$row->serialnumber, TRUE, $row->line);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$item_kit_info = $this->CI->Item_kit->get_info($row->item_kit_id);
			$price_to_use = $row->item_kit_unit_price;
			
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_kit_info->tax_included && empty($sale_taxes))
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->item_kit_id, $row->item_kit_unit_price);
			}
			elseif ($item_kit_info->tax_included)
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->line, $row->item_kit_unit_price,$sale_id);
			}
			
			$this->add_item_kit('KIT '.$row->item_kit_id,-$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description, $row->line);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id, false);
	}
	
	function copy_entire_sale($sale_id, $is_receipt = false)
	{
		$this->empty_cart();
		$this->delete_customer(false);
		$sale_taxes = $this->get_taxes($sale_id);
		$rate=0;
		$sale_info= $this->CI->Sale->get_info($sale_id)->row();
		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$item_info = $this->CI->Item->get_info($row->item_id);
			$price_to_use = $row->item_unit_price;
			$rate=  $row->tasa;
			
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_info->tax_included && empty($sale_taxes) && !$is_receipt)
			{
				$price_to_use = get_price_for_item_including_taxes($row->item_id, $row->item_unit_price);
			}
			elseif($item_info->tax_included)
			{
				$price_to_use = get_price_for_item_including_taxes($row->line, $row->item_unit_price,$sale_id);				
			}
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description,$row->serialnumber, TRUE, $row->line,
			$row->custom1_subcategory,$row->custom2_subcategory,false,$row->numero_cuenta,$row->numero_documento,$row->titular_cuenta,$row->tasa,
			$row->tipo_documento, $row->id_tier,$row->transaction_status,$row->comentarios,$row->fecha_estado,$row->tipo_cuenta,$row->observaciones,$row->celular
			);
			
		}
		
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$item_kit_info = $this->CI->Item_kit->get_info($row->item_kit_id);
			$price_to_use = $row->item_kit_unit_price;
			
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_kit_info->tax_included && empty($sale_taxes) && !$is_receipt)
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->item_kit_id, $row->item_kit_unit_price);
			}
			elseif ($item_kit_info->tax_included)
			{
				$price_to_use = get_price_for_item_kit_including_taxes($row->line, $row->item_kit_unit_price,$sale_id);
			}
			
			$this->add_item_kit('KIT '.$row->item_kit_id,$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description, $row->line,$row->id_tier);
		}
		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount, $row->payment_date, $row->truncated_card, $row->card_issuer);
		}
		$customer_info = $this->CI->Sale->get_customer($sale_id);
		$this->set_customer($customer_info->person_id, false);
		
		$this->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));

		$this->set_sold_by_employee_id($this->CI->Sale->get_sold_by_employee_id($sale_id));
		$this->set_rate_price($this->CI->Sale->get_transaction_rate($sale_id));
		$this->set_rate_sale($rate);
		$this->set_ntable($this->CI->Sale->get_table($sale_id));
		$this->set_divisa($this->CI->Sale->get_divisa($sale_id));
		$this->set_opcion_sale($this->CI->Sale->get_opcion_sale($sale_id));
		$this->set_pagar_otra_moneda($this->CI->Sale->get_otra_moneda($sale_id));
		$this->set_serie_number($this->CI->Sale->get_serie_number($sale_id));
		$this->set_overwrite_tax($sale_info->overwrite_tax); 
		$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
		if(count($taxes_from_sale)>0){
			$this->set_new_tax(
				array("name"=> $taxes_from_sale[0]["name"],"percent"=>(double)$taxes_from_sale[0]["percent"],"cumulative"=>0));
		}else{
			$this->set_overwrite_tax(0); 

		}
		

		

	}
	function copy_entire_sale_quotes($quote_id, $is_receipt = false)
	{
		$this->empty_cart();
		$this->delete_customer(false);
		$sale_taxes = $this->get_taxes_quotes($quote_id);


		foreach($this->CI->Sale->get_sale_items_quotes($quote_id)->result() as $row)
		{
			$item_info = $this->CI->Item->get_info($row->item_id);
			$price_to_use = $row->item_unit_price;
		
			//If we have tax included, but we don't have any taxes for sale, pretend that we do have taxes so the right price shows up
			if ($item_info->tax_included && empty($sale_taxes) && !$is_receipt)
			{
				$price_to_use = get_price_for_item_including_taxes_quotes($row->item_id, $row->item_unit_price);
       
			}
			elseif($item_info->tax_included)
			{
				$price_to_use = get_price_for_item_including_taxes_quotes($row->line, $row->item_unit_price,$quote_id);				
				 
			}
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$price_to_use,$row->description,$row->serialnumber, TRUE, $row->line);
		}
		
		
		foreach($this->CI->Sale->get_sale_payments_quotes($quote_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount, $row->payment_date, $row->truncated_card, $row->card_issuer);
		}
		$customer_info = $this->CI->Sale->get_customer_quotes($quote_id);
		$this->set_customer($customer_info->person_id, false);
		
		$this->set_comment($this->CI->Sale->get_comment_quotes($quote_id));
		$this->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt_quotes($quote_id));

		$this->set_sold_by_employee_id($this->CI->Sale->get_sold_by_employee_id_quotes($quote_id));

	}

	function get_suspended_sale_id()
	{
		return $this->CI->session->userdata('suspended_sale_id');
	}
	function get_serie_number(){
		if($this->CI->session->userdata('serie_number')==false){
			$location = $this->CI->Employee->get_current_location_info();
			$this->set_serie_number($location->serie_number!="" ? $location->serie_number:1);
		}
		return $this->CI->session->userdata('serie_number');
	}
	function set_serie_number($serie_number){
		$this->CI->session->set_userdata('serie_number',$serie_number);		
	}
	
	function set_suspended_sale_id($suspended_sale_id)
	{
		$this->CI->session->set_userdata('suspended_sale_id',$suspended_sale_id);
	}
	
	function delete_suspended_sale_id()
	{
		$this->CI->session->unset_userdata('suspended_sale_id');
	}
	
	function get_change_sale_id()
	{
		return $this->CI->session->userdata('change_sale_id');
	}
	
	function set_change_sale_id($change_sale_id)
	{
		$this->CI->session->set_userdata('change_sale_id',$change_sale_id);
	}
	
	function delete_change_sale_id()
	{
		$this->CI->session->unset_userdata('change_sale_id');
	}
	function delete_item($line)
	{
		$items=$this->get_cart();
		$item_id=$this->get_item_id($line);
		if($this->CI->Giftcard->get_giftcard_id($this->CI->Item->get_info($item_id)->description))
		{
			$this->CI->Giftcard->delete_completely($this->CI->Item->get_info($item_id)->description);
		}
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer($change_price = true)
	{
		$this->CI->session->unset_userdata('customer');
		
		if ($change_price == true)
		{
			$this->change_price();
		}
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('sale_mode');
	}
	function clear_serie_number(){
		$this->CI->session->unset_userdata('serie_number');
	}
	
	function clear_cc_info()
	{
		$this->CI->session->unset_userdata('ref_no');
		$this->CI->session->unset_userdata('auth_code');
		$this->CI->session->unset_userdata('masked_account');
		$this->CI->session->unset_userdata('card_issuer');
	}

	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_ntable();
		$this->clear_show_comment_on_receipt();
		$this->clear_show_receipt();
		$this->clear_show_comment_ticket();
		$this->clear_change_sale_date();
		$this->clear_change_sale_date_enable();
		$this->clear_email_receipt();
		$this->empty_payments();
		$this->delete_customer(false);
		$this->delete_suspended_sale_id();
		$this->delete_change_sale_id();
		$this->delete_partial_transactions();
		$this->clear_save_credit_card_info();
		$this->clear_use_saved_cc_info();
		$this->clear_selected_tier_id();
		$this->clear_deleted_taxes();
		$this->clear_cc_info();
		$this->clear_sold_by_employee_id();
		$this->clear_opcion_sale();
		$this->clear_divisa();
		$this->clear_rate();
		$this->clear_rate_price();
		$this->clear_total_price_transaction_previous();
		$this->clear_pagar_otra_moneda();
		$this->clear_serie_number();
		$this->clear_overwrite_tax();
		$this->clear_new_tax();
		
	}
	
	function save_current_sale_state()
	{
		$this->sale_state = array(
			'mode' => $this->get_mode(),
			'cart' => $this->get_cart(),
			'comment' => $this->get_comment(),
			'show_comment_on_receipt' => $this->get_comment_on_receipt(),
			'change_sale_date' => $this->get_change_sale_date(),
			'change_sale_date_enable' => $this->get_change_sale_date_enable(),
			'email_receipt' => $this->get_email_receipt(),
			'payments' => $this->get_payments(),
			'customer' => $this->get_customer(),
			'suspended_sale_id' => $this->get_suspended_sale_id(),
			'change_sale_id' => $this->get_change_sale_id(),
			'partial_transactions' => $this->get_partial_transactions(),
			'save_credit_card_info' => $this->get_save_credit_card_info(),
			'use_saved_cc_info' => $this->get_use_saved_cc_info(),
			'selected_tier_id' => $this->get_selected_tier_id(),
			'deleted_taxes' => $this->get_deleted_taxes(),
			'sold_by_employee_id' => $this->get_sold_by_employee_id(),
			'rate_sale'=>$this->get_rate_sale(),
			'rate_price'=>$this->get_rate_price(),
			'rate_buy'=>$this->get_rate_buy(),
			'opcion_sale'=>$this->get_opcion_sale()
			);	
	}
	
	function restore_current_sale_state()
	{
		if (isset($this->sale_state))
		{
			$this->set_mode($this->sale_state['mode']);
			$this->set_cart($this->sale_state['cart']);
			$this->set_comment($this->sale_state['comment']);
			$this->set_comment_on_receipt($this->sale_state['show_comment_on_receipt']);
			$this->set_change_sale_date($this->sale_state['change_sale_date']);
			$this->set_change_sale_date_enable($this->sale_state['change_sale_date_enable']);
			$this->set_email_receipt($this->sale_state['email_receipt']);
			$this->set_payments($this->sale_state['payments']);
			$this->set_customer($this->sale_state['customer'], false);
			$this->set_suspended_sale_id($this->sale_state['suspended_sale_id']);
			$this->set_change_sale_id($this->sale_state['change_sale_id']);
			$this->set_partial_transactions($this->sale_state['partial_transactions']);
			$this->set_save_credit_card_info($this->sale_state['save_credit_card_info']);
			$this->set_use_saved_cc_info($this->sale_state['use_saved_cc_info']);
			$this->set_selected_tier_id($this->sale_state['selected_tier_id'], false);
			$this->set_deleted_taxes($this->sale_state['deleted_taxes']);
			$this->set_sold_by_employee_id($this->sale_state['sold_by_employee_id']);			
			$this->set_rate_sale($this->sale_state['rate_sale']);
			$this->set_rate_price($this->sale_state['rate_price']);
			$this->set_rate_buy($this->sale_state['rate_buy']);
			$this->set_opcion_sale($this->sale_state['opcion_sale']);
		}
	}
    
    //Obtener detalladamente valores de los impuestos
    function get_detailed_taxes($sale_id = false)
    {        
    	$taxes = array();

    	if ($sale_id) 
    	{
    		$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
	        foreach($taxes_from_sale as $key=>$tax_item)
	        {
	            $name = $tax_item['name'].' '.$tax_item['percent'].'%';
	            
	            $tax_base   = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100);
	            $tax_amount = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
	            $tax_total  = $tax_base + $tax_amount;

	            if (!isset($taxes[$name]))
	            {
	                $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
	            }

	            $taxes[$name]['base'] += $tax_base;
	            $taxes[$name]['total_tax'] += $tax_amount;
	            $taxes[$name]['total'] += $tax_total;
	        }
    	}
        else
        {            
		    $customer_id = $this->get_customer();
		    $customer = $this->CI->Customer->get_info($customer_id);

		    //Do not charge sales tax if we have a customer that is not taxable
		    if (!$customer->taxable and $customer_id!=-1)
		    {
		        return array();
		    }
			if($this->get_overwrite_tax()==false){
				foreach($this->get_cart() as $line=>$item)
				{
					$price_to_use = $this->_get_price_for_item_in_cart($item);      
					
					$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
					foreach($tax_info as $key=>$tax)
					{
						//echo $key;
						//echo '<pre>'.print_r($tax,true).'</pre>';
						$name = $tax['percent'].'% ' . $tax['name'];
						$tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
						$tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						$tax_total  = $tax_base + $tax_amount;
						

						if (!in_array($name, $this->get_deleted_taxes()))
						{
							if (!isset($taxes[$name]))
							{
								$taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
							}
							
							$taxes[$name]['base'] += $tax_base;
							$taxes[$name]['total_tax'] += $tax_amount;
							$taxes[$name]['total'] += $tax_total;
						}
					}
				}
			}
			/** Para cuando se agrega un nuevo impuesto */
			else{
				foreach($this->get_cart() as $line=>$item)
				{
					$price_to_use = $this->_get_price_for_item_in_cart($item);      
					
					$tax_info =array("0"=>$this->get_new_tax());
					foreach($tax_info as $key=>$tax)
					{
						
						$name = $tax['percent'].'% ' . $tax['name'];
						$tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
						$tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						$tax_total  = $tax_base + $tax_amount;						

						if (!isset($taxes[$name]))
							{
								$taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
							}
							
						$taxes[$name]['base'] += $tax_base;
						$taxes[$name]['total_tax'] += $tax_amount;
						$taxes[$name]['total'] += $tax_total;
						
					}
				}
			}
		}
        
        return $taxes;
    }

    //Obtener los valores totales de los impuestos
    function get_detailed_taxes_total($sale_id = false)
    {
    	$taxes = array();

    	if ($sale_id) 
    	{
	        $total_base_sum = 0;
	        $total_tax_sum  = 0;
	        $total_sum = 0;
	        
	        $taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
	        foreach($taxes_from_sale as $key=>$tax_item)
	        {
	            $name = $tax_item['name'].' '.$tax_item['percent'].'%';
	            
	            $tax_base   = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100);
	            $tax_amount = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
	            $tax_total  = $tax_base + $tax_amount;

	            if (!isset($taxes[$name]))
	            {
	                $taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
	            }

	            $total_base_sum = $tax_base + $total_base_sum;
	            $total_tax_sum = $tax_amount + $total_tax_sum;
	            $total_sum = $tax_total + $total_sum;
	        }
	        
	        $taxes['total_base_sum'] = $total_base_sum;
	        $taxes['total_tax_sum'] = $total_tax_sum;
	        $taxes['total_sum'] = $total_sum;
        }
        else
        {            
        	$total_base_sum = 0;
	        $total_tax_sum  = 0;
	        $total_sum = 0;

		    $customer_id = $this->get_customer();
		    $customer = $this->CI->Customer->get_info($customer_id);

		    //Do not charge sales tax if we have a customer that is not taxable
		    if (!$customer->taxable and $customer_id!=-1)
		    {
		        return array();
		    }
			if($this->get_overwrite_tax()==false){
				foreach($this->get_cart() as $line=>$item)
				{  
					$price_to_use = $this->_get_price_for_item_in_cart($item);      
					
					$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
					foreach($tax_info as $key=>$tax)
					{
						$name = $tax['percent'].'% ' . $tax['name'];

						$tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
						$tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						$tax_total  = $tax_base + $tax_amount;
						
						if (!in_array($name, $this->get_deleted_taxes()))
						{
							if (!isset($taxes[$name]))
							{
								$taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
							}
							
							$total_base_sum = $tax_base + $total_base_sum;
							$total_tax_sum = $tax_amount + $total_tax_sum;
							$total_sum = $tax_total + $total_sum;
						}
					}
				}
			}else{
				foreach($this->get_cart() as $line=>$item)
				{  
					$price_to_use = $this->_get_price_for_item_in_cart($item);      
					
					$tax_info = $tax_info =array("0"=>$this->get_new_tax());
					foreach($tax_info as $key=>$tax)
					{
						$name = $tax['percent'].'% ' . $tax['name'];

						$tax_base  = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
						$tax_amount= ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						$tax_total  = $tax_base + $tax_amount;
						
						
						if (!isset($taxes[$name]))
						{
							$taxes[$name] = array('base'=>0,'total_tax'=>0,'total'=>0);
						}
						$total_base_sum = $tax_base + $total_base_sum;
						$total_tax_sum = $tax_amount + $total_tax_sum;
						$total_sum = $tax_total + $total_sum;
						
					}
				}
			}

		    $taxes['total_base_sum'] = $total_base_sum;
	        $taxes['total_tax_sum'] = $total_tax_sum;
	        $taxes['total_sum'] = $total_sum;		    	    
		}
        return $taxes;
    }

	function get_taxes($sale_id = false)
	{
		$taxes = array();		
		if ($sale_id)
		{
			$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
			foreach($taxes_from_sale as $key=>$tax_item)
			{
				$name = $tax_item['name'].' '.$tax_item['percent'].'%';
				
				if ($tax_item['cumulative'])
				{
					$prev_tax = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($taxes_from_sale[$key-1]['percent'])/100);
					$tax_amount=(($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100) + $prev_tax)*(($tax_item['percent'])/100);					
				}
				else
				{
					$tax_amount=($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
				}

				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;				
			}
		}
		else
		{
			$customer_id = $this->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
				return array();
			}
			if($this->get_overwrite_tax()==false){ 
				foreach($this->get_cart() as $line=>$item)
				{
					
					$price_to_use = $this->_get_price_for_item_in_cart($item);		
					$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
					foreach($tax_info as $key=>$tax)
					{
						$name = $tax['percent'].'% ' . $tax['name'];
						if ($tax['cumulative'])
						{
							$prev_tax = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
							$tax_amount=(($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
						}
						else
						{
							$tax_amount=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						}
						if (!in_array($name, $this->get_deleted_taxes()))
						{
							if (!isset($taxes[$name]))
							{
								$taxes[$name] = 0;
							}
							$taxes[$name] += $tax_amount;
						}
					}
				}
			}else{				
				foreach($this->get_cart() as $line=>$item)
				{
					$price_to_use = $this->_get_price_for_item_in_cart($item);		
					$tax_info =array("0"=>$this->get_new_tax());
					foreach($tax_info as $key=>$tax)
					{
						$name = $tax['percent'].'% ' . $tax['name'];
						$tax_amount=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
						if (!isset($taxes[$name]))
						{
							$taxes[$name] = 0;
						}								
						$taxes[$name] += $tax_amount;						
					}					
				}
			}
		}		
		return $taxes;
	}
	function get_taxes_quotes($quote_id = false)
	{
		$taxes = array();
		
		if ($quote_id)
		{
			$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes_quotes($quote_id));
			foreach($taxes_from_sale as $key=>$tax_item)
			{
				$name = $tax_item['percent'].'% ' . $tax_item['name'];
				
				if ($tax_item['cumulative'])
				{
					$prev_tax = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($taxes_from_sale[$key-1]['percent'])/100);
					$tax_amount=(($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100) + $prev_tax)*(($tax_item['percent'])/100);					
				}
				else
				{
					$tax_amount=($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
				}

				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		}
		else
		{
			$customer_id = $this->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
				return array();
			}

			foreach($this->get_cart() as $line=>$item)
			{
				$price_to_use = $this->_get_price_for_item_in_cart($item);		
				
				$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];
					
					if ($tax['cumulative'])
					{
						$prev_tax = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
						$tax_amount=(($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					}

					if (!in_array($name, $this->get_deleted_taxes()))
					{
						if (!isset($taxes[$name]))
						{
							$taxes[$name] = 0;
						}
						
						$taxes[$name] += $tax_amount;
					}
				}
			}
		}		
		return $taxes;
	}
	
	function get_items_in_cart()
	{
		$items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
			$items_in_cart+=$item['quantity'];
		}
		
		return $items_in_cart;
	}
	
	function get_subtotal($sale_id = FALSE)
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{
			$price_to_use = $this->_get_price_for_item_in_cart($item, $sale_id);
			$subtotal+=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
		}
		
		return to_currency_no_money($subtotal);
	}
	
	function _get_price_for_item_in_cart($item, $sale_id = FALSE)
	{
		$price_to_use = $item['price'];
		
		if (isset($item['item_id']))
		{
			$item_info = $this->CI->Item->get_info($item['item_id']);
			if($item_info->tax_included)
			{
				if ($sale_id)
				{
					$price_to_use = get_price_for_item_excluding_taxes($item['line'], $item['price'], $sale_id);
				}
				else
				{
					$price_to_use = get_price_for_item_excluding_taxes($item['item_id'], $item['price']);
				}
			}
		}
		elseif (isset($item['item_kit_id']))
		{
			$item_kit_info = $this->CI->Item_kit->get_info($item['item_kit_id']);
			if($item_kit_info->tax_included)
			{
				if ($sale_id)
				{
					$price_to_use = get_price_for_item_kit_excluding_taxes($item['line'], $item['price'], $sale_id);
				}
				else
				{
					$price_to_use = get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price']);
				}
			}
		}
		
		return $price_to_use;
	}

	function get_total($sale_id = false)
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
			$price_to_use = $this->_get_price_for_item_in_cart($item, $sale_id);
			$total+=($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100);
		}

		foreach($this->get_taxes($sale_id) as $tax)
		{
			$total+=$tax;
		}
		
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total) : $total;
		return to_currency_no_money($total); 
	}
	function get_total_divisa()
	{
		$total = 0;
		$opcion_sale=$this->get_opcion_sale();		
		foreach($this->get_cart() as $item)
		{		
			$Total_por_item=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
			$tasa=(($item["tasa"]!=0|| $item["tasa"]!=null)?$item["tasa"]:1);
			if($opcion_sale=="venta"){
				$total+=$Total_por_item/$tasa;
			}else{
				$total+=$Total_por_item*$tasa;
			}			
					
			
		}

		
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total) : $total;
		return to_currency_no_money($total,4);
	}
	function get_utilidad(){
		$total=0;
		$total_divisa=$this->get_total_divisa();
		$total_divisa_transaccion=$this->get_total_price_transaction_divisa();
		$total = $total_divisa_transaccion-$total_divisa;
		$opcion_sale=$this->get_opcion_sale();
		$tasa=$this->get_rate_price();
		
		if($opcion_sale=="venta"){
			$total=$total*$tasa;
		}else{
			$total=$total/$tasa;
		}		
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total) : $total;
		return to_currency_no_money($total,4);

	}
	function get_total_price_transaction_divisa()
	{
		$total = 0;
		$opcion_sale=$this->get_opcion_sale();		
		$tasa=(double)$this->get_rate_price();
		foreach($this->get_cart() as $item)
		{		
			$Total_por_item=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
			//$tasa=(($item["tasa"]!=0|| $item["tasa"]!=null)?$item["tasa"]:1);
			if($opcion_sale=="venta"){
				$total+=$Total_por_item/$tasa;
			}else{
				$total+=$Total_por_item*$tasa;
			}					
			
		}	
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total) : $total;
		return to_currency_no_money($total,4);
	}
	function get_total_price_transaction_previous()
	{		
		return $this->CI->session->userdata('total_price_previous')===false? FALSE :
		 $this->CI->session->userdata('total_price_previous');
	}
	function set_total_price_transaction_previous($total_price_previous)
	{
		$this->CI->session->set_userdata('total_price_previous',$total_price_previous);

	}
	function clear_total_price_transaction_previous()
	{
		//$this->CI->session->unset_userdata('total_price_previous');
		$this->set_total_price_transaction_previous(FALSE);
	}
	function get_total_price_transaction()
	{
		$total = 0;
		$opcion_sale=$this->get_opcion_sale();		
		$tasa=$this->get_rate_price();
		foreach($this->get_cart() as $item)
		{		
			$Total_por_item=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
			//$tasa=(($item["tasa"]!=0|| $item["tasa"]!=null)?$item["tasa"]:1);
			if($opcion_sale=="venta"){
				$total+=$Total_por_item*$tasa;
			}else{
				$total+=$Total_por_item/$tasa;
			}					
			
		}
		
		$total_costo= 0;
		if($opcion_sale=="venta"){
			$total_costo=$total/$tasa;
		}else{
			$opcion_sale=$total*$tasa;
		}
		$utilidad= $this->get_utilidad();
		$total_costo= $total_costo-$utilidad; 
		$total = $this->CI->config->item('round_cash_on_sales') && $this->is_sale_cash_payment() ?  round_to_nearest_05($total_costo) : $total_costo;
		return to_currency_no_money($total_costo,4);
	}
	
	function is_sale_cash_payment()
	{
		foreach($this->get_payments() as $payment)
		{
			if($payment['payment_type'] ==  lang('sales_cash'))
			{
				return true;
			}
		}
		
		return false;
	}
	
	function is_over_credit_limit()
	{
		$customer_id=$this->get_customer();
		if($customer_id!=-1)
		{
			$cust_info=$this->CI->Customer->get_info($customer_id);
			$current_sale_store_account_balance = $this->get_payment_amount(lang('sales_store_account'));
			return $cust_info->credit_limit !== NULL && $cust_info->balance + $current_sale_store_account_balance > $cust_info->credit_limit;
		}
		
		return FALSE;
	}
	
	/**
	 * se verifica si los items tienen asiganada una subcategoria 
	 */
	function is_select_subcategory_items(){
		if($this->CI->config->item('subcategory_of_items')){
			$items = $this->get_cart();
			foreach ($items as $item )
			{
				if (isset($item['item_id']))
				{
					if ( $item['has_subcategory']==1) {
						if($item['custom1_subcategory']=="" || $item['custom1_subcategory']==null || $item['custom2_subcategory']=="" ||
						 $item['custom2_subcategory']==null){
							 return false;
						 }
					}
				}
			}
		}
		return true;
		
	}
	function es_valido_dato_casa_cambio($cantidad_caracteres=20){
		$valido_dato=true;

		foreach($this->get_cart() as $item){
			
				if($item['numero_cuenta']==null || strlen($item['numero_cuenta'])!=$cantidad_caracteres){
					return false;
				}
				else if($item['titular_cuenta']==null || strlen($item['titular_cuenta'])==0){
					return false;
				}
				else if($item['numero_documento']==null || strlen($item['numero_documento'])==0){
					return false;
				}
			
		}
		return $valido_dato;
	}
}
 
?>