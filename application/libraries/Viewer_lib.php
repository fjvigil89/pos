<?php
class Viewer_lib
{
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();		
    }

    function update_viewer_cart($employee_id, $data = false, $is_cart = 1,$payments, $overwrite_tax = 0,$new_tax)
    {
        $this->CI->Viewer->update_viewer($employee_id,
            array(
                    "cart_data" => json_encode( $data ),
                    "is_cart" => $is_cart,
                    "updated" => date('Y-m-d H:i:s'),
                    "overwrite_tax" => $overwrite_tax,
                    "new_tax" => json_encode($new_tax),
                    "payments" => json_encode($payments)
                )
            );    
    }
    function get_total_tax_and_subtotal($cart_new)
    {
        $total = 0;
        $subtotal = 0;

        foreach ($cart_new as $key => $item) 
        {
            $total += $item["total_tax"];
            $subtotal += $item["price"];
        }

        return array(
                    "total" => $total,
                    "subtotal" => $subtotal
                );

    }
    function get_payments_totals($payments = array())
	{
		$subtotal = 0;
		foreach($payments as $payments)
		{
			$subtotal += $payments['payment_amount'];
		}

		return $subtotal;
	}
    
    function create_cart($data)
    {
       $cart_new = array(); 
       $new_tax = null;

       if($data["overwrite_tax"] == 1)
            $new_tax = json_decode($data["new_tax"],true);     

       foreach (json_decode($data["cart_data"],true)as $key => $item) 
       {
            $cart_new[$key] = array(               
                "line" => $item["line"],
                "name" => character_limiter(H($item['name']), 18),                
                "unit" => isset($item["item_id"]) ? $item["unit"]: "",
                "serialnumber" => isset($item["item_id"]) ? $item["serialnumber"] : $item["item_kit_number"] ,               
                "discount" => to_currency_no_money($item["discount"],1),
                "price" => ($this->get_price_item($item)),
                "quantity" => $item["quantity"],
                "total_tax" => ($this->get_price_tax($item,$data["overwrite_tax"],$new_tax ))
            );
        }

        return $cart_new;
    }
    function get_price_item($item)
    {
        if (isset($item['item_id']))
        {
            $item_info = $this->CI->Item->get_info($item['item_id']);

            if($item['tax_included'])
            {
                return get_price_for_item_excluding_taxes($item['item_id'],$item['price']);
            }
        }
        elseif (isset($item['item_kit_id']))
        {
            if($item['tax_included'])
            {
                return  get_price_for_item_kit_excluding_taxes($item['item_kit_id'], $item['price']);                    
            }
        }

        return $item["price"];
    }
    function get_price_tax($item, $overwrite_tax = false,$new_tax = array())
    {
       
        if (isset($item['item_id']) && $item['name'] != lang('sales_giftcard')) 
        {
            $tax_info = $this->CI->Item_taxes_finder->get_info($item['item_id']);
            $i = 0;
            $prev_tax = array();

            foreach($tax_info as $key=>$tax)
            {
                $prev_tax[$item['item_id']][$i] = $tax['percent'] / 100;
                $i++;
            }						

            if (isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard')) 
            {									
                if(!$overwrite_tax)
                {
                    $sum_tax = array_sum($prev_tax[$item['item_id']]);
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax=$item['price'] + $value_tax;                                                                
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
                }	
            }	
            elseif (!isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
            {									
                if(!$overwrite_tax)
                {
                    $sum_tax = 0;
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax = $item['price'] + $value_tax;
                    
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }	
            }	
        }

        else if (isset($item['item_kit_id']) && $item['name']!=lang('sales_giftcard')) 
        {
            $tax_kit_info = $this->CI->Item_kit_taxes_finder->get_info($item['item_kit_id']);
            $i=0;

            foreach($tax_kit_info as $key=>$tax)
            {
                $prev_tax[$item['item_kit_id']][$i] = $tax['percent'] / 100;
                $i++;
            }
            if (isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
            {										
                if(!$overwrite_tax)
                {
                    $sum_tax = array_sum($prev_tax[$item['item_kit_id']]);
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax = $item['price'] + $value_tax;
                    
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }
            }
            elseif (!isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
            {										
                if(!$overwrite_tax)
                {
                    $sum_tax = 0;
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax=$item['price'] + $value_tax;
                    
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }
            }
        }
        
        if ($item['tax_included'])
        {
               $Total=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
        }
        else if ($item['name'] == lang('giftcards_giftcard'))
        {
            $Total=$item['price']*$item['quantity'] - $item['price']*$item['quantity']*$item['discount']/100;
        }
        else
        {
            $Total = $price_with_tax*$item['quantity'] - $price_with_tax*$item['quantity']*$item['discount']/100;
        }
        /*if($item['name'] == lang('sales_giftcard'))
            $Total = $item['price']*$item['quantity'];        
        else        
            $Total=$price_with_tax*$item['quantity']-$price_with_tax*$item['quantity']*$item['discount']/100;
        */
        
        return  $Total; 	
    }
}
?>