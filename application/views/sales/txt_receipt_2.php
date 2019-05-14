<?php   

function complete_string($string ,$canti, $p = "")
{
    while(strlen($string) < $canti)
    {        
        $string .= " ";    
    }

   return character_limiter($string, $canti, $p);

}
        $space2 ="  ";
        $space1 =" ";
        $str_table = 12;
        $lines = 30;
        $type_line ="-";

        echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('name') : $this->config->item('company') ; 
        echo"\n";
        echo lang('config_company_dni').'. ' .($this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('company_dni') : $this->config->item('company_dni')); 
        echo"\n";
        echo nl2br($this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('address'): $this->config->item('address'));           
        echo"\n";
        echo $this->Location->get_info_for_key('phone'); 
        echo"\n";

        if(($this->Location->get_info_for_key('overwrite_data')==1 and  $this->Location->get_info_for_key('website') ) or $this->config->item('website')) 
        {
            echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('website') : $this->config->item('website') ;
            echo"\n";
        } 
        if(!empty($this->Location->get_info_for_key('codigo')))
        {                 
            echo lang("locations_code").". ".  $this->Location->get_info_for_key('codigo') ;
            echo"\n";
        }           
        if(isset($mode) && $mode == 'return') 
        {
            echo lang('items_sale_mode').$sale_type." ".$sale_number;
            echo "\n"; 
            if($this->Location->get_info_for_key('show_serie') == 1)
            {									
                echo"\nSerie: ".$serie_number;	
                echo "\n"; 								
            }		                 	
        } 
        elseif(isset($mode) && $store_account_payment == 1) 
        {
            echo lang('sales_store_account_name').' '.$sale_id;
            echo "\n"; 
        } 
        elseif($show_comment_ticket==1)
        {
            echo 'BOLETA '.$sale_number;
            echo "\n"; 
            if($this->Location->get_info_for_key('show_serie') == 1)
            {
                echo "Serie: ".$serie_number;
                echo "\n"; 
            }			                 	
        } 
        else
        {
            echo $this->config->item('sale_prefix').' '.$sale_number;
            echo "\n"; 
            if($this->Location->get_info_for_key('show_serie')==1){
                echo "Serie: ".$serie_number;
                echo "\n"; 									
            }
        } 
        
        echo "ID : ".$sale_id_raw."\n";
        
        echo $transaction_time;
        echo "\n";
        
        if(isset($customer))
        { 
            echo lang('customers_customer').": ".$customer."\n";
        }               
        if(!empty($customer_address_1))
        {                 
            echo lang('common_address').": ". $customer_address_1. ' '.$customer_address_2."\n";
        }
        if (!empty($customer_city)) 
        { 
            echo $customer_city.' '.$customer_state.', '.$customer_zip."\n";
        } 
        if (!empty($customer_country)) 
        { 
            echo $customer_country."\n";
        }            
        if(!empty($customer_phone))
        {                
            echo lang('common_phone_number')." : ". $customer_phone."\n";                 
        } 
        if(!empty($customer_email))
        {                
            echo lang('common_email')." : ". $customer_email."\n"; 
        } 
        /*if (isset($sale_type)) 
        {                 
            echo $sale_type."\n";               
        }*/
        if ($register_name) 
        {
            echo lang('locations_register_name').' : '.$register_name."\n";
        }
        if ($tier) 
        { 
            echo lang('items_tier_name').': '.$tier."\n";                 
        }
        echo lang('employees_employee').": ".$employee."\n";

        echo"\n";

        for ($i = 0; $i < $lines ; $i++) { 
            echo $type_line;
        }      
         
        echo"\n";
        // se crea la tabla se productos

        echo lang('items_item').  $space2.$space2.$space2; 

        /*if($this->config->item('subcategory_of_items') )
        {                    
            echo ($this->config->item('inhabilitar_subcategory1')==1 ? "":($this->config->item("custom_subcategory1_name")."/")). $this->config->item("custom_subcategory2_name");
            echo  $space2;                   
        }
        if($show_number_item)
        {                   
            echo "UPC";
            echo  $space2;
        }*/
        //echo lang('common_price'); 
        //echo  $space2;  

        echo lang('sales_quantity_short');
        echo  $space1;
        echo  $space2;
        echo  $space2;
        /*if($discount_exists)
        { 
            echo lang('sales_discount_short');
            echo  $space2;  
        }*/
        echo lang('sales_total');
          
    
        echo"\n";

        for ($i = 0; $i < $lines ; $i++) { 
            echo $type_line;
        }      
         
        echo"\n";
?>
<?php
    foreach(array_reverse($cart, true) as $line=>$item) 
    {

        echo complete_string($item['name'], $str_table,"");

       /* if (isset($item['size']) and !empty($item['size']))
        { 
            echo "-".$item['size']; 
        } 
        if (isset($item['colour']) and !empty($item['colour']))
        {
            echo  "-".$item['colour']; 
        } 
        if (isset($item['model']) and !empty($item['model']))
        {
            echo  "-".$item['model']; 
        } 
        if (isset($item['marca']) and !empty($item['marca']))
        {
            echo  "-".$item['marca'];
        } */
        echo  $space2; 
        /*if($this->config->item('subcategory_of_items') )
        { 
                    
            if (isset($item['item_id']))
            {        
                echo(
                    ($this->config->item('inhabilitar_subcategory1')==1?"":($item['custom1_subcategory']?$item['custom1_subcategory']:"--")."/").
                    ($item['custom2_subcategory']?$item['custom2_subcategory']:"--")
                );
                echo  $space2; 
            }
            else
            {
                echo"--/--";
                echo  $space2; 
            }

        }
        if($show_number_item)
        {
            if (isset($item['item_number']))
            { 
                echo $item['item_number']." ";
                echo  $space2; 
            }
            else if (isset($item['item_kit_number']))
            {
                echo $item['item_kit_number']." ";
                echo  $space2; 
            }
        }*/
        if (isset($item['item_id']) && $item['name'] != lang('sales_giftcard')) 
        {
            $tax_info = $this->Item_taxes_finder->get_info($item['item_id']);
            $i = 0;
            $prev_tax = array();
            
            foreach($tax_info as $key => $tax)
            {
                $prev_tax[$item['item_id']][$i] = $tax['percent'] / 100;
                $i ++;
            }						
            if (isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard')) 
            {									
                if(!$overwrite_tax)
                {
                    $sum_tax = array_sum($prev_tax[$item['item_id']]);
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax = $item['price'] + $value_tax;
                                                                                            
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }	
            }	
            elseif (!isset($prev_tax[$item['item_id']]) && $item['name'] != lang('sales_giftcard'))
            {									
                if(!$overwrite_tax)
                {											
                    $sum_tax = 0;
                    $value_tax=$item['price'] * $sum_tax;										
                    $price_with_tax = $item['price'] + $value_tax;
                                                
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }	
            }	
        }

        if (isset($item['item_kit_id']) && $item['name'] != lang('sales_giftcard')) 
        {
            $tax_kit_info = $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
            $i = 0;									
            
            foreach($tax_kit_info as $key => $tax)
            {
                $prev_tax[$item['item_kit_id']][$i] = $tax['percent'] / 100;
                $i ++;
            }
            if (isset($prev_tax[$item['item_kit_id']]) && $item['name']!= lang('sales_giftcard')) 
            {										
                if(!$overwrite_tax)
                {
                    $sum_tax = array_sum($prev_tax[$item['item_id']]);
                    $value_tax = $item['price'] * $sum_tax;										
                    $price_with_tax = $item['price'] + $value_tax;											
                }
                else
                {
                    $value_tax = get_nuevo_iva($new_tax,$item['price']);
                    $price_with_tax = get_precio_con_nuevo_iva($new_tax,$item['price']);
                }
            }
            elseif (!isset($prev_tax[$item['item_kit_id']]) && $item['name'] != lang('sales_giftcard')) 
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
       // echo $this->config->item('round_value')==1 ? to_currency(round($item['price'])) :to_currency($item['price']);
       // echo  $space2;

        echo complete_string(to_quantity($item['quantity']),6);
        /*if (isset($item['model']))
        { 
            echo ($item['unit']);
        } */
        /*echo  $space1;

        if($discount_exists) 
        { 
            echo $item['discount'].'%';
            echo  $space2;
        } */
        echo $space1;
        if ($item['name'] == lang('sales_giftcard')) 
        {								
            $Total = $item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100;
        }	
        else
        {
            $Total=$price_with_tax*$item['quantity']-$price_with_tax*$item['quantity']*$item['discount']/100;
        } 
        echo complete_string($this->config->item('round_value' )== 1 ? to_currency(round($Total)) :to_currency($Total),11); 								 	
       

        /*if($item['description'] != "" and $this->config->item('hide_description') == 0)
        { 
            echo "\n" . $item['description']; 
        }
        if( isset($item['serialnumber']) and $item["serialnumber"]!=null )
        {
            echo "\n" . "Serial: ".$item['serialnumber'];
        }*/
        if($this->config->item('activar_casa_cambio') )
        {
            
            echo "\n" . lang("sales_titular_cuenta");
            echo "\n" .  $item["titular_cuenta"];
            echo "\n" . lang("sales_docuemento");
            echo "\n" . $item["numero_documento"];
            echo "\n" . lang("sales_numero_cuenta");
            echo "\n" . $item["numero_cuenta"];
            echo "\nTotal : ";
            
            $total2 = 0;
            $Total_por_item = $item['price'] * $item['quantity'];
            $tasa = (($item["tasa"]!= 0|| $item["tasa"]!= null)? $item["tasa"]: 1 );
            
            if($opcion_sale == "venta")
            {
                $total2 += $Total_por_item / $tasa;
            }
            else
            {
                $total2 += $Total_por_item * $tasa;
            }	
            echo to_currency($total2,3,lang("sales_".$divisa)." ");
        }
        echo"\n";
    } 
    

    for ($i = 0; $i < $lines ; $i++) { 
        echo $type_line;
    }      
     
    echo"\n";
    echo lang('sales_sub_total')." ";
	echo $this->config->item('round_value')== 1 ? to_currency(round($subtotal)) : to_currency($subtotal) ;
    echo "\n";
    echo $this->config->item('activar_casa_cambio') == 0 ? lang('saleS_total_invoice') : "Total ".$this->config->item('sale_prefix'); 
    
    $subtotal_ = 0;

    if( $this->config->item('round_cash_on_sales') == 1 && $is_sale_cash_payment )
    {
		$subtotal_ = to_currency(round_to_nearest_05($total));
	} 
    else if($this->config->item('round_value') == 1  )
    {
		$subtotal_= to_currency(round($total));
    }
    else
    {
		$subtotal_ = to_currency($total);
	}
	echo " ".$subtotal_;
    echo "\n";

    if(isset($another_currency) and $another_currency == 1)
    { 
        echo "Total en ". get_currency_symbol($currency)." ";
        echo  to_currency_no_money($total_other_currency,4); 
        echo "\n";
    }
   
    if($this->config->item('activar_casa_cambio') == true)
    {
        echo $this->config->item('activar_casa_cambio') == 0 ? lang('saleS_total_invoice'):"Total ".lang("sales_".$divisa)." ";
        echo to_currency($total_divisa,3,lang("sales_".$divisa)." ");
    }   

    for ($i = 0; $i < $lines ; $i++) { 
        echo $type_line;
    }      
     
    echo"\n";
    // fin tabla producto 
    
    foreach($payments as $payment_id=>$payment) 
    { 
    
        echo $payment["payment_type"] != lang('sales_store_account')?((isset($another_currency) and $another_currency == 1) ? lang('sales_value_cancel')." ".get_currency_symbol($currency) : lang('sales_value_cancel')) : lang('sales_crdit')." : ";
                    
        $payment_amount= 0;
        $payment_amount_value= (isset($another_currency) and $another_currency==1)?
                ($payment['payment_amount']/$value_other_currency):$payment['payment_amount'];
        if( $this->config->item('round_cash_on_sales')==1 && $payment['payment_type'] == lang('sales_cash') ){
            
            $payment_amount=(round_to_nearest_05($payment_amount_value));
        } 
        else if($this->config->item('round_value')==1  ){
            $payment_amount= (round($payment_amount_value));
        }else{
            $payment_amount= $payment_amount_value;
        }
        if(isset($another_currency) and $another_currency==1){
            echo " ".  to_currency_no_money($payment_amount,4);
        }else{
            echo " ".to_currency($payment_amount);
        }
        echo "\n";
    }

    for ($i = 0; $i < $lines ; $i++) { 
        echo $type_line;
    } 
    

    if(isset($show_comment_ticket) && ($show_comment_ticket == 0 && $this->config->item('hide_invoice_taxes_details') == 0)  || ($this->config->item('hide_ticket_taxes')==0 && $show_comment_ticket == 1))
    {
        echo"\n";
        echo"      ".lang('sales_details_tax'); 
        echo"\n";
        echo lang('sales_type_tax')."     "; 
        echo lang('sales_base_tax')."     "; 
        echo lang('sales_price_tax'); 
        //echo lang('sales_value_receiving')." "; 
        echo  "\n";

        if ($this->config->item('group_all_taxes_on_receipt')) 
        { 
        	$total_tax = 0;
			foreach($detailed_taxes as $name=>$value) 
			{
				$total_tax+=$value['total_tax'];
			}
		    echo complete_string(lang('reports_tax').": ",8)." ";
        	echo complete_string($value['base'],11)." "; 	
		    echo complete_string(to_currency_no_money(round($value['total_tax']),1),12);
            //echo $this->config->item('round_value')==1 ? to_currency(round($total_tax)) :to_currency($total_tax) ;
            echo "\n";
        } 
        else
        {						
            foreach($detailed_taxes as $name=>$value) 
            {               
               echo  complete_string($name.": ",8,"")." ";                   
               echo  complete_string(to_currency($value['base'] ),11,"")." ";
               echo  complete_string(to_currency(round($value['total_tax']),1),12,"");	
               //echo $this->config->item('round_value')==1 ? to_currency(round($value['total'])) :to_currency($value['total']);
               echo "\n";    
            }
		}
        /*echo lang('sales_total').'= ';
        echo to_currency($detailed_taxes_total['total_base_sum']); 
        echo to_currency($detailed_taxes_total['total_tax_sum']); 
        echo $this->config->item('round_value')==1 ? to_currency($detailed_taxes_total['total_sum']) :to_currency($detailed_taxes_total['total_sum']) ;
        echo "\n";*/
        for ($i = 0; $i < $lines ; $i++) { 
            echo $type_line;
        } 
    }
     

   if($this->config->item('ocultar_forma_pago') == 0)
   {
        echo "\n     " .lang('sales_details_payments'); 
        echo"\n";
        
        echo complete_string(lang('sales_payment_date'),11); 
        echo complete_string(lang('sales_payment_type'),9); 
        echo lang('sales_payment_value')." ";
        echo "\n";

        foreach($payments as $payment_id=>$payment) 
        {
                
            echo complete_string(date(get_date_format(), strtotime($payment['payment_date'])) ,11);
            
            $splitpayment=explode(':',$payment['payment_type']); 
            
            echo complete_string($splitpayment[0],9); 

            $payment_amount= 0;
            $payment_amount_otro= (isset($another_currency) and $another_currency==1)?
                    ($payment['payment_amount']/$value_other_currency):$payment['payment_amount'];
            if( $this->config->item('round_cash_on_sales')==1 && $payment['payment_type'] == lang('sales_cash') )
                $payment_amount=(round_to_nearest_05($payment_amount_otro));
            
            else if($this->config->item('round_value')==1  )
                $payment_amount= (round($payment_amount_otro));
            else
                $payment_amount= ($payment_amount_otro);            
            if(isset($mode) && $mode=='return') 
                $payment_amount=$payment_amount*(-1);                            
            if(isset($another_currency) and $another_currency==1)
                echo   complete_string(to_currency_no_money($payment_amount,4),11);
            else
                echo  complete_string(to_currency($payment_amount),11);            

            echo"\n";   
        } 
        for ($i = 0; $i < $lines ; $i++) { 
            echo $type_line;
        } 
    }  


if ($amount_change >= 0) 
{
    echo"\n". lang('sales_change_due').((isset($another_currency) and $another_currency==1)?" ".get_currency_symbol($currency):"").": ";
    
    $amount_change_= 0;
    $amount_change_otro= (isset($another_currency) and $another_currency==1)?
                ($amount_change/$value_other_currency):$amount_change;
    if( $this->config->item('round_cash_on_sales')==1 && $is_sale_cash_payment ){
        $amount_change_=(round_to_nearest_05($amount_change_otro));
    } 
    else if($this->config->item('round_value')==1  ){
        $amount_change_= (round($amount_change_otro));
    }else{
        $amount_change_= ($amount_change_otro);
    }
    if(isset($another_currency) and $another_currency==1){
        echo  to_currency_no_money($amount_change_,4);
    }else{
        echo to_currency($amount_change_);
    }
    
}
else 
{ echo"\n";  
    echo lang('sales_amount_due'); ": "; 
    echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change * -1)) : to_currency($amount_change * -1) || $this->config->item('round_value')==1 ? to_currency(round($amount_change)) :to_currency($amount_change);
     
}
if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE && !$this->config->item('hide_balance_receipt_payment')) 
{ 
    echo"\n";  
    echo lang('sales_customer_account_balance').": ";
    echo to_currency($customer_balance_for_sale); 
    
} 
if (isset($points) && $this->config->item('show_point')==1 && $this->config->item('system_point')==1)
{ 
    echo"\n"; 
    echo lang('config_value_point_accumulated')." ";
    echo $points; 
   
} 
if (!$store_account_payment==1)
{ 
    echo"\n";  
   echo nl2br( $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('company_regimen') : $this->config->item('company_regimen') ); 
   echo"\n";  

   $str = $this->config->item('resolution');
   $order   = array("<strng>", "</strng>", "<br>","</br>");        
   $str = str_replace( $order,"",$str);

   echo nl2br($str); 
   echo"\n"; 

    if($show_return_policy_credit ==1)
    {
        $str = $this->config->item('return_policy_credit');
        $order   = array("<strng>", "</strng>", "<br>","</br>");        
        $str = str_replace( $order,"",$str);
        echo nl2br($str);
        echo"\n";
    }
    else
    {
        $str = $this->config->item('return_policy');
        $order   = array("<strng>", "</strng>", "<br>","</br>");        
        $str = str_replace( $order,"",$str);
        echo nl2br($str);
        echo"\n";
    } 
    if($this->Location->get_info_for_key('show_rango')==1)
    {        
        echo nl2br("Rango autorizado: ".$this->Location->get_info_for_key('serie_number')." ".
								$this->Location->get_info_for_key('start_range')." a la ".
								$this->Location->get_info_for_key('final_range')); 
         
    }
} 
