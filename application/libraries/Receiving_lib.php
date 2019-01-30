<?php
class Receiving_lib
{
	var $CI;
	
	//This is used when we need to change the recv state and restore it before changing it (The case of showing a receipt in the middle of a recv)
	var $recv_state;

  	function __construct()
	{
		$this->CI =& get_instance();
	}

	function get_cart()
	{
		if($this->CI->session->userdata('cartRecv') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cartRecv');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cartRecv',$cart_data);
	}

	function get_supplier()
	{
		if(!$this->CI->session->userdata('supplier'))
			$this->set_supplier(-1);

		return $this->CI->session->userdata('supplier');
	}

	function set_supplier($supplier_id)
	{
		if (is_numeric($supplier_id))
		{
			$this->CI->session->set_userdata('supplier',$supplier_id);
		}
	}
	

	function get_location()
	{
		if(!$this->CI->session->userdata('location'))
			$this->set_location(-1);

		return $this->CI->session->userdata('location');
	}

	function set_location($location_id)
	{
		if (is_numeric($location_id))
		{
			$this->CI->session->set_userdata('location',$location_id);
		}
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('recv_mode'))
			$this->set_mode('receive');

		return $this->CI->session->userdata('recv_mode');
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
	
	function set_mode($mode)
	{
		$this->CI->session->set_userdata('recv_mode',$mode);
	}	
	
	function set_comment($comment)
	{
		$this->CI->session->set_userdata('recv_comment',$comment);
	}
	
	function get_comment()
	{
		return $this->CI->session->userdata('recv_comment') ? $this->CI->session->userdata('recv_comment') : '';
	}
	
	function set_suspended_receiving_id($suspended_receiving_id)
	{
		$this->CI->session->set_userdata('suspended_recv_id',$suspended_receiving_id);
	}
	
	function get_suspended_receiving_id()
	{
		return $this->CI->session->userdata('suspended_recv_id');
	}
	
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null, $cost_transport=0,$unit_price=0,$custom1_subcategory=null,$custom2_subcategory=null,$quantity_subcategory=null)
	{
		//make sure item exists in database.
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}

		//Get items in the receiving so far.
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the list. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.
            //There is an array function to get the associated key for an element, but I like it better
            //like that!

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			} 

			if($item['item_id']==$item_id and $item['custom1_subcategory']== $custom1_subcategory && $item['custom2_subcategory']== $custom2_subcategory )
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
			}
		}

		$insertkey=$maxkey+1;
		if($this->CI->config->item("subcategory_of_items")==1 and $this->CI->config->item("inhabilitar_subcategory1")==1 and $custom1_subcategory==null){

			$custom1_subcategory="»";
		}
		$cur_item_info = $this->CI->Item->get_info($item_id);

		$cur_item_location_info = $this->CI->Item_location->get_info($item_id);
		
		$default_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;
		$item_info= $this->CI->Item->get_info($item_id);
		//array records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$item_info->name,
			'size' => $item_info->size,
			'item_number'=>$item_info->item_number,
			'product_id' => $item_info->product_id,
			'description'=>$description!=null ? $description: $item_info->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$item_info->allow_alt_description,
			'is_serialized'=>$item_info->is_serialized,
			'quantity'=>$quantity,
         	'discount'=>$discount,
         	'unit_price'=>$item_info->unit_price,
			'price'=>$price!=null ? $price: $default_cost_price,
			'cost_price_preview' => $this->calculate_average_cost_price_preview($item_id, $price!=null ? $price: $default_cost_price, $quantity,$discount,$cost_transport),
			'cost_transport' => $cost_transport,
			"custom1_subcategory"=>$custom1_subcategory,
			"custom2_subcategory"=>$custom2_subcategory,
			'has_subcategory'=>$item_info->subcategory,
			"quantity_subcategory"=>$quantity_subcategory
			)
		);

		
		
		//Item already exists
		if($itemalreadyinsale)
		{
			$items[$updatekey]['quantity']+=$quantity;
			$items[$updatekey]['cost_price_preview']=$this->calculate_average_cost_price_preview($item_id, $price!=null ? $price: $default_cost_price, $quantity,$discount,$cost_transport);
		}
		else
		{
			//add to existing array
			$items+=$item;
		}

		$this->set_cart($items);
		return true;

	}

	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE,$cost_transport = FALSE,$unit_price=false ,$custom1_subcategory=FALSE,$custom2_subcategory=FALSE,$quantity_subcategory=FALSE)
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
			if ($cost_transport !== FALSE ) {
				$items[$line]['cost_transport'] = $cost_transport;
			}
			if ($unit_price !== FALSE) {

				$items[$line]['unit_price'] = $unit_price;
			}
			if ($custom1_subcategory !== FALSE ) {
				$items[$line]['custom1_subcategory'] = $custom1_subcategory;
				$items[$line]['custom2_subcategory'] = "";
			}
			if ($custom2_subcategory !== FALSE ) {
				$items[$line]['custom2_subcategory'] = $custom2_subcategory;
			}
			if ($quantity_subcategory !== FALSE ) {
				$items[$line]['quantity_subcategory'] = $quantity_subcategory;
			}
			
			
			$items[$line]['cost_price_preview']=$this->calculate_average_cost_price_preview($items[$line]['item_id'], $items[$line]['price'], $items[$line]['quantity'],$items[$line]['discount'],$items[$line]['cost_transport']);
						 
			$this->set_cart($items);
			
			return true;
		}

		return false;
	}

	function is_valid_receipt($receipt_receiving_id)
	{
		//RECV #
		$pieces = explode(' ',$receipt_receiving_id);

		if(count($pieces)==2 && $pieces[0] == 'RECV')
		{
			return $this->CI->Receiving->exists($pieces[1]);
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

	function return_entire_receiving($receipt_receiving_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_receiving_id);
		$receiving_id = $pieces[1];

		$this->empty_cart();
		$this->delete_supplier();

		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber,$row->item_cost_transport);
		}
		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
	}
	
	function add_item_kit($external_item_kit_id_or_item_number)
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
		
		foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
		{
			$this->add_item($item_kit_item->item_id, $item_kit_item->quantity);
		}
		
		return TRUE;
	}

	function copy_entire_receiving($receiving_id)
	{
		$this->empty_cart();
		$this->delete_supplier();

		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
		{

			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber,$row->item_cost_transport,0,$row->custom1_subcategory,$row->custom2_subcategory,$row->quantity_subcategory
		);
		}
		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
		
		$recv_info = $this->CI->Receiving->get_info($receiving_id)->row_array();
		$this->set_comment($recv_info['comment']);

	}
	function is_select_subcategory_items(){
		if($this->CI->config->item('subcategory_of_items')){
			$items = $this->get_cart();
			foreach ($items as $item )
			{
				if (isset($item['item_id']))
				{
					if ( $item['has_subcategory']==1) {
						if($item['custom1_subcategory']=="" || $item['custom1_subcategory']==null || $item['custom2_subcategory']=="" ||
						 $item['custom2_subcategory']==null || !is_numeric($item['quantity_subcategory'])){
							 return false;
						 }
					}
				}
			}
		}
		return true;
		
	}

	function delete_item($line)
	{
		$items=$this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cartRecv');
	}

	function delete_supplier()
	{
		$this->CI->session->unset_userdata('supplier');
	}

	function delete_location()
	{
		$this->CI->session->unset_userdata('location');
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('recv_mode');
	}
	
	function delete_comment()
	{
		$this->CI->session->unset_userdata('recv_comment');
	}
	
	function delete_suspended_receiving_id()
	{
		$this->CI->session->unset_userdata('suspended_recv_id');	
	}
	
	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->delete_supplier();
		$this->delete_location();
		$this->delete_comment();
		$this->delete_suspended_receiving_id();
	}
	
	function save_current_recv_state()
	{
		$this->recv_state = array(
			'mode' => $this->get_mode(),
			'cart' => $this->get_cart(),
			'comment' => $this->get_comment(),
			'supplier' => $this->get_supplier(),
			'location' => $this->get_location(),
			'suspended_recv_id' => $this->get_suspended_receiving_id(),
		);
	}
	
	function restore_current_recv_state()
	{
		if (isset($this->recv_state))
		{
			$this->set_mode($this->recv_state['mode']);
			$this->set_cart($this->recv_state['cart']);
			$this->set_comment($this->recv_state['comment']);
			$this->set_supplier($this->recv_state['supplier']);
			$this->set_location($this->recv_state['location']);
			$this->set_suspended_receiving_id($this->recv_state['suspended_recv_id']);
		}
	}
	function get_type_supplier($supplier=false)
	{	
		$this->CI->session->set_userdata('supplie',$supplier);
	}
	function get_supplier_session()
	{
		return $this->CI->session->userdata('supplie') ? $this->CI->session->userdata('supplie') : '';
	}
	function get_type_delete_supplier() 	
	{
		$this->CI->session->unset_userdata('supplie');
	}

	function get_total($receiving_id = false)
	{
	
		$total = 0;

		foreach($this->get_cart() as $item)
		{
			$total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100 + $item['cost_transport']);
		}

	   	if($this->get_supplier_session()=='Jurídico')
		{
			foreach($this->get_taxes($receiving_id) as $tax)
			{
				$total+=$tax;
			}
		}
		
		return to_currency_no_money($total);
	}
	
	function calculate_average_cost_price_preview($item_id, $price, $additional_quantity,$discount_percent,$cost_transport)
	{
		if ($this->CI->config->item('calculate_average_cost_price_from_receivings'))
		{
			return $this->CI->Receiving->calculate_cost_price_preview($item_id, $price, $additional_quantity, $discount_percent,$cost_transport);
		}
		return false;
	}

	//-----
	function get_taxes($receiving_id = false)
	{
		$taxes = array();
		
		if ($receiving_id)
		{
			$taxes_from_receivings = array_merge($this->CI->Receiving->get_receiving_items_taxes($receiving_id));
			foreach($taxes_from_receivings as $key=>$tax_item)
			{
				$name = $tax_item['percent'].'% ' . $tax_item['name'];
				
				if ($tax_item['cumulative'])
				{
					$prev_tax = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($taxes_from_receivings[$key-1]['percent'])/100);
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
			$supplier_id = $this->get_supplier();
			$supplier = $this->CI->Supplier->get_info($supplier_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (($supplier->type == lang('suppliers_natural')) and $supplier_id!=-1)
			{
				return array();
			}
				
			foreach($this->get_cart() as $line=>$item)
			{
				$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes_finder->get_info($item['item_id']) : $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
				
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];
					
					if ($tax['cumulative'])
					{
						$prev_tax = ($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
						$tax_amount=(($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
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

	function get_deleted_taxes() 
	{
		$deleted_taxes = $this->CI->session->userdata('deleted_taxes') ? $this->CI->session->userdata('deleted_taxes') : array();
		return $deleted_taxes;
	}	

	//
	function out_of_stock($item_id,$quantity)
	{
        //make sure item exists
        if(!$this->CI->Item->exists($item_id))
        {
            //try to get item id given an item_number
            $item_id = $this->CI->Item->get_item_id($item_id);

            if(!$item_id)
                return false;
        }


        $item_location_quantity = $this->CI->Item_location->get_location_quantity($item_id);

        $quanity_added = $quantity;
        $diferencia=$item_location_quantity - abs($quantity);
        //If $item_location_quantity is NULL we don't track quantity
        if ($item_location_quantity !== NULL &&  $diferencia <= 0)
        {
                        return true;
        }

        return false;
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
        return $quanity_already_added;
    }

    //
	function get_subtotal($receiving_id = FALSE)
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{

			$subtotal+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}
		
		return to_currency_no_money($subtotal);
	}

	//Función para obtener el total del costo del transporte

	function get_total_cost_transport($receiving_id = FALSE)
	{
		$total_cost_transport = 0;
		foreach($this->get_cart() as $item)
		{
			$total_cost_transport+=$item['cost_transport'];
		}
		
		return to_currency_no_money($total_cost_transport);
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
	function clear_deleted_taxes() 	
	{
		$this->CI->session->unset_userdata('deleted_taxes');
	}
}
?>