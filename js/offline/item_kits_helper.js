class Item_kits_helper {
    constructor() {

    }
    async  get_price_for_item_kit_excluding_taxes(item_kit_id_or_line, item_kit_price_including_tax, sale_id = false) {
        var return_ = false;
        var tax_info = null;

       // if (sale_id != false) {
            //tax_info = $CI->Sale->get_sale_item_kits_taxes($sale_id,$item_kit_id_or_line);
        //}
        //else {
            tax_info = await objItem_kit_taxes_finder.get_info(item_kit_id_or_line);
        //}

        if (tax_info.length == 2 && tax_info[1].cumulative == 1) {
            return_ = item_kit_price_including_tax / (1 + (tax_info[0].percent / 100) + (tax_info[1].percent / 100) + ((tax_info[0].percent / 100) * ((tax_info[1].percent / 100))));
        }
        else //0 or more taxes NOT cumulative
        {
            var total_tax_percent = 0;

            for (i in tax_info) {
                var tax = tax_info[i];
                total_tax_percent += Number(tax.percent);
            }

            return_ = item_kit_price_including_tax / (1 + (total_tax_percent / 100));
        }

        if (return_!= false) {
            return accounting.formatNumber(return_, 10, "");
        }

        return false;
    }
    async  get_commission_for_item_kit(item_kit_id, price, quantity, discount)
{

    let employee_id=await get_sold_by_employee_id();
	let sales_person_info = await objEmployee.get_info(employee_id);
	employee_id= await objEmployee.get_logged_in_employee_info();
	employee_id= employee_id.person_id;
    let logged_in_employee_info = await objEmployee.get_info(employee_id);
    
	
	
	let item_kit_info = await objItem_kit.get_info(item_kit_id);
	
	if (item_kit_info.commission_fixed > 0)
	{
		return quantity*item_kit_info.commission_fixed;
	}
	else if(item_kit_info.commission_percent > 0)
	{
		return to_currency_no_money((price*quantity-price*quantity*discount/100)*(item_kit_info.commission_percent/100));
	}
	else if(await objAppconfig.item('select_sales_person_during_sale')==1)
	{
		if(sales_person_info.commission_percent > 0)
		{
			return to_currency_no_money((price*quantity-price*quantity*discount/100)*(Number(sales_person_info.commission_percent)/100));
		}
		return to_currency_no_money((price*quantity-price*quantity*discount/100)*(Number(await objAppconfig.item('commission_default_rate'))/100));
	}
	else if(logged_in_employee_info.commission_percent > 0)
	{
		return to_currency_no_money((price*quantity-price*quantity*discount/100)*(Number(logged_in_employee_info.commission_percent)/100));
	}
	else
	{
		return to_currency_no_money((price*quantity-price*quantity*discount/100)*(Number(await objAppconfig.item('commission_default_rate'))/100));
	}
}
}