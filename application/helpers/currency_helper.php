<?php
function to_currency($number, $decimals = 2)
{
	$CI =& get_instance();
	$currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '$';
	$thousand_separator = $CI->config->item('thousand_separator') ? $CI->config->item('thousand_separator') : '.';
	$decimal_separator = $CI->config->item('decimal_separator') ? $CI->config->item('decimal_separator') : ',';
	$remove_decimals = $CI->config->item('remove_decimals') ? $CI->config->item('remove_decimals') : 0;
	if($remove_decimals=='1'){
		if($number >= 0)
		{
			$ret = $currency_symbol.number_format($number, 0, $decimal_separator, $thousand_separator);
	    }
	    else
	    {
	    	$ret = '&#8209;'.$currency_symbol.number_format(abs($number), 0, $decimal_separator, $thousand_separator);
	    }

		return $ret;
	}else{
		if($number >= 0)
		{
			$ret = $currency_symbol.number_format($number, $decimals, $decimal_separator, $thousand_separator);
	    }
	    else
	    {
	    	$ret = '&#8209;'.$currency_symbol.number_format(abs($number), $decimals, $decimal_separator, $thousand_separator);
	    }

		return preg_replace('/(?<=\d{2})0+$/', '', $ret);
	}	
}

function round_to_nearest_05($amount)
{
	return round($amount * 2, 1) / 2;
}

function to_currency_no_money($number, $decimals = 2)
{
	$ret = number_format($number, $decimals, '.', '');
	return preg_replace('/(?<=\d{2})0+$/', '', $ret);
}

function to_quantity($val, $show_not_set = TRUE)
{
	if ($val !== NULL)
	{
		return $val == (int)$val ? (int)$val : rtrim($val, '0');		
	}
	
	if ($show_not_set)
	{
		return lang('common_not_set');
	}
	
	return '';
	
}
?>