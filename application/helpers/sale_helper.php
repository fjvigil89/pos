<?php
function is_sale_integrated_cc_processing()
{
	$CI =& get_instance();
	$cc_payment_amount = $CI->sale_lib->get_payment_amount(lang('sales_credit'));
	return $CI->Location->get_info_for_key('enable_credit_card_processing') && $cc_payment_amount != 0;
}

function sale_has_partial_credit_card_payment() 
{
	$CI =& get_instance();
	$cc_partial_payment_amount = $CI->sale_lib->get_payment_amount(lang('sales_partial_credit'));
	return $cc_partial_payment_amount != 0;
}

function get_precio_con_nuevo_iva($ivas=array(),$price){	
	$total=$price+get_nuevo_iva($ivas,$price);
	return $total;
}
function get_nuevo_iva($ivas=array(),$price){
	$sum_tax=$ivas["percent"]/100.0;
	$value_tax=$price*$sum_tax;
	return $value_tax;
}
?>