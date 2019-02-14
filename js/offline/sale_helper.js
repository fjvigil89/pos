async function is_sale_integrated_cc_processing()
{
	
	var cc_payment_amount = get_payment_amount(lang('sales_credit'));
	let query=await objLocation.get_info_for_key('enable_credit_card_processing')==1 && cc_payment_amount != 0;
	return query;
}