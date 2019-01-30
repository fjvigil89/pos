<?php 
    $is_integrated_credit_sale = is_sale_integrated_cc_processing();
?> 

<?php if (!empty($customer_email)) { ?>

<?php echo anchor('sales/email_receipt/'.$sale_id_raw, lang('sales_email_receipt'), array('id' => 'email_receipt','class' => 'btn btn-success btn-block hidden-print'));?>

<?php }?>					

<?php echo $this->config->item('company'); ?>

<?php if($this->config->item('company_logo')) {?>

    <?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?>

<?php } ?>

<?php echo lang('config_company_dni').'. '.$this->config->item('company_dni'); ?>
<?php echo nl2br($this->Location->get_info_for_key('address')); ?>
<?php echo $this->Location->get_info_for_key('phone'); ?>
<?php if($this->config->item('website')) { ?>
    <?php echo $this->config->item('website'); ?>
<?php } ?>
<?php  
    if(isset($mode) && $mode=='return') {
        echo lang('items_sale_mode').$sale_id;
    } 
    elseif(isset($mode) && $store_account_payment==1) {
        echo lang('sales_store_account_name').''.$sale_id;
    } 
    else
    {
        echo $sale_id;
    }  
?>

<?php echo $transaction_time ?>


<?php if(isset($customer)) { ?>
    <?php echo lang('customers_customer').": ".$customer; ?>
<?php if(!empty($customer_address_1)){ ?>
        <?php echo lang('common_address'); ?> : <?php echo $customer_address_1. ' '.$customer_address_2; ?>
<?php } ?>
<?php if (!empty($customer_city)) { 
    echo $customer_city.' '.$customer_state.', '.$customer_zip;
} ?>
<?php if (!empty($customer_country)) { 
    echo '<div>'.$customer_country.'</div>';
} ?>			
<?php if(!empty($customer_phone)){ ?>
        <?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?>
<?php } ?>
<?php if(!empty($customer_email)){ ?>
        <?php echo lang('common_email'); ?> : <?php echo $customer_email; ?>
<?php } ?>
<?php } ?>	

<?php if (isset($sale_type)) { ?>
    <?php echo $sale_type; ?>
<?php } ?>

<?php if ($register_name) { ?>
    <?php echo lang('locations_register_name').': '.$register_name; ?>
<?php } ?>
            
<?php if ($tier) { ?>
    <?php echo lang('items_tier_name').': '.$tier; ?>
<?php } ?>

<?php echo lang('employees_employee').": ".$employee; ?>

<?php if($this->Location->get_info_for_key('enable_credit_card_processing')){
    echo lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id');
} ?>				

<?php echo lang('items_item'); ?>
<?php echo lang('common_price'); ?>
<?php echo lang('sales_quantity_short'); ?>
<?php if($discount_exists) { ?>
    <?php echo lang('sales_discount_short'); ?>
<?php } ?>
<?php echo lang('sales_total'); ?>

<?php foreach(array_reverse($cart, true) as $line=>$item) { ?>

    							
        <?php echo character_limiter(H($item['name']), $this->config->item('show_fullname_item') ? 200 : 25); ?>
        
        <?php if (isset($item['size'])){ ?> 
            <?php echo $item['size']; ?>
        <?php } ?>
        <?php if (isset($item['colour'])){ ?> 
            <?php echo $item['colour']; ?>
        <?php } ?>
        <?php if (isset($item['model'])){ ?> 
            <?php echo $item['model']; ?>
        <?php } ?>
        <?php if (isset($item['marca'])){ ?> 
            <?php echo $item['marca']; ?>
        <?php } ?>
    
    
        <?php echo $this->config->item('round_value')==1 ? to_currency(round($item['price'])) :to_currency($item['price']);?>
    
        <?php echo to_quantity($item['quantity']); ?>
        <?php
            if (isset($item['model'])){ 
                echo ($item['unit']);} ?>
    
    <?php if($discount_exists) { ?>
            <?php echo $item['discount'].'%'; ?>
    <?php } ?>

    <?php
        if (isset($item['item_id']) && $item['name']!=lang('sales_giftcard')) 
        {
            $tax_info = $this->Item_taxes_finder->get_info($item['item_id']);
            $i=0;
            foreach($tax_info as $key=>$tax)
            {
                $prev_tax[$item['item_id']][$i]=$tax['percent']/100;
                $i++;
            }						

            if (isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard')) 
            {						
                $sum_tax=array_sum($prev_tax[$item['item_id']]);
                $value_tax=$item['price']*$sum_tax;										
                $price_with_tax=$item['price']+$value_tax;	
            }	
            elseif (!isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
            {																				
                $sum_tax=0;
                $value_tax=$item['price']*$sum_tax;										
                $price_with_tax=$item['price']+$value_tax;	
            }	
        }

        if (isset($item['item_kit_id']) && $item['name']!=lang('sales_giftcard')) 
        {
            $tax_kit_info = $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
            $i=0;									
            foreach($tax_kit_info as $key=>$tax)
            {
                $prev_tax[$item['item_kit_id']][$i]=$tax['percent']/100;
                $i++;
            }
            if (isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
            {
                $sum_tax=array_sum($prev_tax[$item['item_kit_id']]);
                $value_tax=$item['price']*$sum_tax;									
                $price_with_tax=$item['price']+$value_tax;
            }
            elseif (!isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
            {
                $sum_tax=0;
                $value_tax=$item['price']*$sum_tax;									
                $price_with_tax=$item['price']+$value_tax;
            }
        }																											
    ?>

    <?php if ($item['name']==lang('sales_giftcard')) { ?>
            <?php
                $Total=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
                echo $this->config->item('round_value')==1 ? to_currency(round($Total)) :to_currency($Total); 								 	
            ?>
    <?php }
    else
    {?>
            <?php
                $Total=$price_with_tax*$item['quantity']-$price_with_tax*$item['quantity']*$item['discount']/100;
                echo $this->config->item('round_value')==1 ? to_currency(round($Total)) :to_currency($Total); 								 	
            ?>
    <?php } ?>

    <?php if($this->config->item('hide_description')=='0'){ ?>
            <?php echo $item['description']; ?>
    <?php } ?>

        <?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?>
<?php } ?>					

    <?php echo lang('sales_sub_total'); ?>: 
    
    <?php echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ; ?>

    <?php echo lang('saleS_total_invoice'); ?>:
    
    <?php echo $this->config->item('round_cash_on_sales') && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($total)) : to_currency($total) || $this->config->item('round_value')==1 ? to_currency(round($total)) :to_currency($total) ; ?>

<?php foreach($payments as $payment_id=>$payment) { ?>
        <?php echo lang('sales_value_cancel'); ?>:
        <?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  to_currency(round_to_nearest_05($payment['payment_amount'])) : to_currency($payment['payment_amount']) || $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])) :to_currency($payment['payment_amount']);?>  
<?php } ?>

<?php echo lang('sales_details_tax'); ?>
						
<?php echo lang('sales_type_tax'); ?>
<?php echo lang('sales_base_tax'); ?>
<?php echo lang('sales_price_tax'); ?>						
<?php echo lang('sales_value_receiving'); ?>

<?php if ($this->config->item('group_all_taxes_on_receipt')) { ?>
<?php 
    $total_tax = 0;
    foreach($detailed_taxes as $name=>$value) 
    {
        $total_tax+=$value['total_tax'];
    }
?>	
        <?php echo lang('reports_tax'); ?>:
        <?php echo $value['base']; ?>
        <?php echo to_currency_no_money(round($value['total_tax']),1); ?>
        <?php echo $this->config->item('round_value')==1 ? to_currency(round($total_tax)) :to_currency($total_tax) ; ?>
<?php } 
else {

foreach($detailed_taxes as $name=>$value) { 							
    //if(!($value=='0.00')) { ?>
                <?php echo $name; ?>:
                <?php echo $value['base']; ?>
                <?php echo to_currency_no_money(round($value['total_tax']),1); ?>
                <?php echo $this->config->item('round_value')==1 ? to_currency(round($value['total'])) :to_currency($value['total']) ; ?>
    <?php //}
}
} ?>
<?php echo lang('sales_total').'='; ?>
<?php echo to_currency($detailed_taxes_total['total_base_sum']); ?>
<?php echo to_currency($detailed_taxes_total['total_tax_sum']); ?>
<?php echo $this->config->item('round_value')==1 ? to_currency($detailed_taxes_total['total_sum']) :to_currency($detailed_taxes_total['total_sum']) ; ?>


<?php echo lang('sales_details_payments'); ?>						

<?php echo lang('sales_payment_date'); ?>
<?php echo lang('sales_payment_type'); ?>											
<?php echo lang('sales_payment_value'); ?>

<?php foreach($payments as $payment_id=>$payment) { ?>
        <?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?>

    <?php if ($is_integrated_credit_sale || sale_has_partial_credit_card_payment()) { ?>
            <?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?>: <?php echo $payment['card_issuer']. ' '.$payment['truncated_card']; ?>
    <?php } 
    else { ?>
            <?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> 
            <?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  to_currency(round_to_nearest_05($payment['payment_amount'])) : to_currency($payment['payment_amount']) || $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])) :to_currency($payment['payment_amount']);?>  
    <?php } ?>							
<?php } ?>	

<?php if ($amount_change >= 0) { ?>
        <?php echo lang('sales_change_due'); ?>:
        <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change)) : to_currency($amount_change) || $this->config->item('round_value')==1 ? to_currency(round($amount_change)) :to_currency($amount_change); ?> 
<?php }
else { ?>
        <?php echo lang('sales_amount_due'); ?>:
        <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change * -1)) : to_currency($amount_change * -1) || $this->config->item('round_value')==1 ? to_currency(round($amount_change)) :to_currency($amount_change); ?> 
<?php } ?>

<?php foreach($payments as $payment) { ?>
<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) { ?>
        <?php echo lang('sales_giftcard_balance').$payment['payment_type'];?> 
        <?php $giftcard_payment_row = explode(':', $payment['payment_type']); ?>
        <?php echo to_currency($this->Giftcard->get_giftcard_value(end($giftcard_payment_row))); ?>
<?php }?>
<?php }?>


<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE && !$this->config->item('hide_balance_receipt_payment')) {?>
        <?php echo lang('sales_customer_account_balance'); ?>:
        <?php echo to_currency($customer_balance_for_sale); ?> 
<?php } ?>

<?php if (isset($points) && $this->config->item('show_point')==1 && $this->config->item('system_point')==1) { ?>
        <?php echo lang('config_value_point_accumulated'); ?>:
        <?php echo $points; ?> 
<?php } ?>
<?php if ($ref_no) { ?>
        <?php echo lang('sales_ref_no'); ?>
        <?php echo $ref_no; ?>
<?php }
if (isset($auth_code) && $auth_code) { ?>
        <?php echo lang('sales_auth_code'); ?>
        <?php echo $auth_code; ?>
<?php } ?>

    <?php if($show_comment_on_receipt==1)
        {
            echo $comment ;
        }
    ?>

<?php if (!$store_account_payment==1) { ?>
<?php echo nl2br($this->config->item('company_regimen')); ?>
<?php echo nl2br($this->config->item('resolution')); ?>
<?php echo nl2br($this->config->item('return_policy')); ?>
<?php } ?>

<?php if (!$this->config->item('hide_barcode_on_sales_and_recv_receipt') && !$store_account_payment==1) { ?>
<?php } ?>
<?php if(!$this->config->item('hide_signature')) { ?>			
<?php foreach($payments as $payment) {?>
    <?php if (strpos($payment['payment_type'], lang('sales_credit'))!== FALSE) { ?>
        <?php echo lang('sales_signature'); ?> --------------------------------- <br />	
        <?php 
            echo lang('sales_card_statement');
            break;
        ?>
    <?php }?>
<?php }?>				
<?php } ?>


