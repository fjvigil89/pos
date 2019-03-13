iniciarDB();

var objAppconfig = new AppConfig();
var objItem = new Item();
var objItem_location = new Item_location();
var objItem_kit = new Item_kit();
var objGifcard = new Gifcard();
var objItem_kit_item = new Item_kit_item();
var objItem_kit_location = new Item_kit_location();
var objEmployee = new Employee();
var objItems_helper = new Items_helper();
var objItem_taxes_finder = new Item_taxes_finder();
var objItem_location_taxes = new Item_location_taxes();
var objItem_kit_taxes_finder = new Item_kit_taxes_finder();
var objitem_kits_helper = new Item_kits_helper();
var objItem_kit_location_taxes = new Item_kit_location_taxes();
var objItem_kit_taxes = new Item_kit_taxes();
var objLocation = new Location();
var objItem_taxes = new Item_taxes();
var objCustomer = new Customer();
var objTier = new Tier();
var objItems_subcategory = new Items_subcategory();
var objSale = new Sale();
var objRegister= new Register();
var objPoints= new Points();
var objRegister_movement= new Register_movement();
var objCajas_empleados= new Cajas_empleados();
var array_inicial=null;


async function iniciar_register(){
    if(await objAppconfig.item('automatically_show_comments_on_receipt')==true)
	{
		set_comment_on_receipt(1);
    }    
	let location_id=await objEmployee.get_logged_in_employee_current_location_id();
    let register_count = await objRegister.count_all(location_id);
    if (register_count > 0)
	{
		if (register_count == 1)
		{
			let registers = await objRegister.get_all(location_id);
			register = registers[0];
			if (register['register_id']!=undefined)
			{
				await objEmployee.set_employee_current_register_id(register['register_id']);
			}
		}

		if (await objEmployee.get_logged_in_employee_current_register_id()==null)
		{
            location.href= BASE_URL+"index.php/sales/choose_register_offline";	
            clear_all();			
			return;
		}else if( await objEmployee.tiene_caja(await objEmployee.get_logged_in_employee_current_register_id(), await get_person_id())==false){
            location.href= BASE_URL+"index.php/sales/choose_register_offline";
            clear_all();
    		return;
				
		}
    }
    if (await objAppconfig.item('track_cash')==true)
	{
	    if (!await objSale.is_register_log_open())
        {	
            clear_all();	
            location.href= BASE_URL+"index.php/sales/opening_amount_offline";           		
            return;
         }
	} 

}

/**
 * se cargan los datos que no van a cambiar
 */

async function data_inicial(){
    if(array_inicial==null){   
      
        await cargar_lang();      
        array_inicial=Array();        
        array_inicial['customers_store_accounts']=  await objAppconfig.item('customers_store_accounts');
        array_inicial['show_sales_price_iva'] = await objAppconfig.item('show_sales_price_iva');
        array_inicial['show_sales_price_without_tax'] = await objAppconfig.item('show_sales_price_without_tax');
        array_inicial['show_sales_num_item'] = await objAppconfig.item('show_sales_num_item');
        array_inicial['show_sales_discount'] = await objAppconfig.item('show_sales_discount');
        array_inicial['show_sales_inventory'] = await objAppconfig.item('show_sales_inventory');
        array_inicial['show_sales_description'] = await objAppconfig.item('show_sales_description');
        let modes = Array();
        modes["sale"] = lang('sales_sale');
         modes["return"] = lang('sales_return');
        if (await objAppconfig.item('customers_store_accounts') == true) {
             modes['store_account_payment'] = lang('sales_store_account_payment');
         }   
        array_inicial['modes'] = modes;
        let tem_payment = [];
        tem_payment[lang('sales_cash')] = lang('sales_cash');
        tem_payment[lang('sales_check')] = lang('sales_check');
        // tem_payment[lang('sales_giftcard')] = lang('sales_giftcard');
        tem_payment[lang('sales_debit')] = lang('sales_debit');
        tem_payment[lang('sales_credit')] = lang('sales_credit');
        if (await objAppconfig.item('customers_store_accounts') && get_mode() != 'store_account_payment') {
            tem_payment[lang('sales_store_account')] = lang('sales_store_account');
        }
        array_inicial['payment_options'] = tem_payment;
    //}
    let payment_types = await objAppconfig.get_additional_payment_types();
    payment_types.forEach(function(payment_type) {
        array_inicial['payment_options'][payment_type] = payment_type;
    });
    var tiers = [];
    tiers[0] = lang('items_none');
    let aux_tier = await objTier.get_all();
    for (i in aux_tier) {
        tiers[aux_tier[i].id] = aux_tier[i].name;
    }
    array_inicial['tiers'] = tiers;
    }
    return array_inicial;
}
async function _reload() { 
    var data = await data_inicial();   

    data['is_tax_inclusive'] = await _is_tax_inclusive();
    if (data.is_tax_inclusive && Object.keys(get_deleted_taxes()).length > 0) {
        clear_deleted_taxes();
    }
    person_info = await objEmployee.get_logged_in_employee_info(); 
    data['cart'] = get_cart();
    data['mode'] = get_mode();
    data['items_in_cart'] = get_items_in_cart();
    data['subtotal'] = await get_subtotal();
    data['taxes'] = await get_taxes();
    data['total'] = await get_total();
    data['items_module_allowed'] = await objEmployee.has_module_permission('items', person_info.person_id);
    data['comment'] = get_comment();
    data['ntable'] = get_ntable();
    data['show_comment_on_receipt'] = get_comment_on_receipt();
    data['show_receipt'] = get_show_receipt();
    data['show_comment_ticket'] = get_comment_ticket();
    data['email_receipt'] = get_email_receipt();
    data['payments_total'] = await get_payments_totals_excluding_store_account();
    data['amount_due'] = await get_amount_due();
    data['payments'] = get_payments();
    data['change_sale_date_enable'] = get_change_sale_date_enable();
    data['change_sale_date'] = get_change_sale_date();
    data['selected_tier_id'] = get_selected_tier_id();
    data['is_over_credit_limit'] = false;  
    data['selected_sold_by_employee_id'] = await get_sold_by_employee_id();  
    let customer_id = await get_customer();
    if (customer_id != -1) {
        let info = await objCustomer.get_info(customer_id);
        data['customer'] = info.first_name + ' ' + info.last_name + (info.company_name == '' ? '' : ' (' + info.company_name + ')');
        data['customer_email'] = info.email;
        data['points'] = await objCustomer.get_info_points(customer_id);
        data['customer_balance'] = info.balance;
        data['customer_credit_limit'] = info.credit_limit;
        data['is_over_credit_limit'] = await is_over_credit_limit();
        data['customer_id'] = customer_id;
        data['customer_cc_token'] = info.cc_token;
        data['customer_cc_preview'] = info.cc_preview;
        data['save_credit_card_info'] = get_save_credit_card_info();
        data['use_saved_cc_info'] = get_use_saved_cc_info();
        data['avatar'] = ""; //can be changed to  base_url()."/img/avatar.png" if it is required


    }else if(customer_id == -1 && data['customer']!=undefined ){
       delete  data['customer'] ;
       delete data['customer_email'] ;
       delete data['points'] ;
       delete data['customer_balance'] ;
       delete data['customer_credit_limit'];
       delete data['is_over_credit_limit'] ;
       delete data['customer_id'] ;
       delete data['customer_cc_token'] ;
       delete data['customer_cc_preview'];
       delete data['save_credit_card_info'] ;
       delete data['use_saved_cc_info'] ;
       delete data['avatar'] ;
    }

    data['customer_required_check'] = (!Boolean(Number(await objAppconfig.item('require_customer_for_sale'))) || (Boolean(Number(await objAppconfig.item('require_customer_for_sale'))) && customer_id != undefined && customer_id != -1));

    data['payments_cover_total'] = await _payments_cover_total();
    //Value total + total_cash
    data['total_max'] = 0;
    return data;


}

async function _payments_cover_total() {
    let total_payments = 0;
    let payments = get_payments();
    let payment;
    for (let i in payments) {
        payment = payments[i];
        total_payments += + payment['payment_amount'];
    }
    let round_value = to_currency_no_money(Math.round(await get_total() - total_payments));
    let value = to_currency_no_money(await get_total() - total_payments);
    let value_compare = await objAppconfig.item('round_value') == 1 ? round_value : value;
    /* Changed the conditional to account for floating point rounding */
    if ((get_mode() == 'sale' || get_mode() == 'store_account_payment') && value_compare > 1e-6) {
        return false;
    }

    return true;
}
function get_use_saved_cc_info() {
    return localStorage.getItem('use_saved_cc_info') != null ? localStorage.getItem('use_saved_cc_info') : false;

}
function get_save_credit_card_info() {
    return localStorage.getItem('save_credit_card_info') != null ? localStorage.getItem('sold_by_employee_id') : false;
}
async function is_over_credit_limit() {
    let customer_id = await get_customer();
    if (customer_id != -1) {
        let cust_info = await objCustomer.get_info(customer_id);
        let current_sale_store_account_balance = get_payment_amount(lang('sales_store_account'));
        return cust_info.credit_limit != null && cust_info.balance + current_sale_store_account_balance > cust_info.credit_limit;
    }

    return false;
}
function get_payment_amount(payment_type) { 
    let payment_amount = 0;
    let payment_ids = get_payment_ids(payment_type);
    if (payment_ids !== false) {
        let payments = get_payments();

        for (i in payment_ids) {
            payment_amount += payments[payment_ids[i]]['payment_amount'];
        }
    }

    return payment_amount;
}
function get_payment_ids(payment_type) {
    let payment_ids = [];

    let payments = get_payments();

    for (k = 0; k < payments.length; k++) {
        if (payments[k]['payment_type'] == payment_type) {
            payment_ids.push(k);
        }
    }

    return payment_ids;
}

async function get_sold_by_employee_id() {
    if (await objAppconfig.item('default_sales_person') != 'not_set' && !Boolean(Number(localStorage.getItem('sold_by_employee_id')))) {
        let employee_ = await objEmployee.get_logged_in_employee_info();
        return employee_.person_id;
    }
    return localStorage.getItem('sold_by_employee_id') ? localStorage.getItem('sold_by_employee_id') : null;
}

function get_change_sale_date() {
    return localStorage.getItem('change_sale_date') ? localStorage.getItem('change_sale_date') : '';
}
function get_change_sale_date_enable() {
    return localStorage.getItem('change_sale_date_enable') ? localStorage.getItem('change_sale_date_enable') : '';
}
function get_payments_totals() {
    var subtotal = 0;
    var payments = get_payments();
    for (i in payments) {
        subtotal += +payments[i]['payment_amount'];
    }

    return to_currency_no_money(subtotal);
}
async function get_amount_due(sale_id = false) {
    var amount_due = 0;
    var payment_total = get_payments_totals();
    var sales_total = await get_total(sale_id);
    var amount_due = to_currency_no_money(sales_total - payment_total);
    return amount_due;
}
async function get_payments_totals_excluding_store_account() {
    var subtotal = 0;
    var payments = get_payments();
    for (i in payments) {
        if (payments[i]['payment_type'] != lang('sales_store_account')) {
            subtotal += payments[i]['payment_amount'];
        }
    }
    return to_currency_no_money(subtotal);
}
function get_email_receipt() {
    return localStorage.getItem('email_receipt') ? localStorage.getItem('email_receipt') : false;
}
function get_comment_ticket() {
    return localStorage.getItem('show_comment_ticket') ? localStorage.getItem('show_comment_ticket') : '';
}
function get_show_receipt() {
    return localStorage.getItem('show_receipt') ? localStorage.getItem('show_receipt') : '';
}
function get_comment_on_receipt() {
    return localStorage.getItem('show_comment_on_receipt') ? localStorage.getItem('show_comment_on_receipt') : '';
}
function get_ntable() {
    return localStorage.getItem('ntable') ? localStorage.getItem('ntable') : '';
}
function get_comment() {
    return localStorage.getItem('comment') ? localStorage.getItem('comment') : '';
}
async function get_total(sale_id = false) {
    var total = 0;
    var items = get_cart();
    var item =null;
    for (i in items) {
         item = items[i];
        var price_to_use = await _get_price_for_item_in_cart(item, sale_id);
        total += (price_to_use * item.quantity - price_to_use * item.quantity * item.discount / 100);
    }

    var taxes = await get_taxes(sale_id);
    for (i in taxes) {
        total += taxes[i];
    }

    total = Boolean(Number(await objAppconfig.item('round_cash_on_sales'))) && await is_sale_cash_payment() ? round_to_nearest_05(total) : total;
    return to_currency_no_money(total);
}

function get_payments() {
    var payments = localStorage.getItem("payments");
    if (payments == null)
        set_payments([]);

    return JSON.parse(localStorage.getItem("payments"));


}
function set_payments(payments_data) {
    localStorage.setItem("payments", JSON.stringify(payments_data));
}
async function is_sale_cash_payment() {
    var payments = get_payments();
    for (i in payments) {
        if (payments[i]['payment_type'] == lang('sales_cash')) {
            return true;
        }
    }

    return false;
}
async function get_customer() {
    var customer = localStorage.getItem("customer");
    if (customer == null)
        await set_customer(-1, false);

    return localStorage.getItem("customer");
}
async function set_customer(customer_id, change_price_ = true) {
    if ($.isNumeric(customer_id)) {
        localStorage.setItem('customer', customer_id);

        if (change_price_ == true) {
            await change_price();
        }
    }
}
async function change_price() {
    var items = get_cart();
    for (i in items) {
        var item = items[i];
        if (item.item_id != undefined) {
            var line = item.line;
            var price = item.price;
            var item_id = item.item_id;
            var item_info = await objItem.get_info(item_id);
            var item_location_info = await objItem_location.get_info(item_id);
            var previous_price = false;
            var previous_tier_id = get_previous_tier_id()
            if (Boolean(Number(previous_tier_id))) {
                previous_price = await get_precio_por_item(item_id, previous_tier_id);
            }
            previous_price = to_currency_no_money(previous_price, 10);
            price = to_currency_no_money(price, 10);

            if (price == item_info.unit_price || price == item_location_info.unit_price || price == previous_price) {
                items[line].price = await get_precio_por_item(item_id);
            }
        }
        else if (item.item_kit_id != undefined) {
            var line = item.line;
            var price = item.price;
            var item_kit_id = item.item_kit_id;
            var item_kit_info = await objItem_kit.get_info(item_kit_id);
            var item_kit_location_info = await objItem_kit_location.get_info(item_kit_id);
            var previous_price = false;
            var previous_tier_id = get_previous_tier_id()

            if (Boolean(Number(previous_tier_id))) {
                previous_price = await get_price_for_item_kit(item_kit_id, previous_tier_id);
            }

            previous_price = to_currency_no_money(previous_price, 10);
            price = to_currency_no_money(price, 10);

            if (price == item_kit_info.unit_price || price == item_kit_location_info.unit_price || price == previous_price) {
                items[line].price = await get_price_for_item_kit(item_kit_id);
            }
        }
    }
    set_cart(items);
}
function get_previous_tier_id() {
    return Boolean(Number(localStorage.getItem("previous_tier_id"))) ? localStorage.getItem('previous_tier_id') : false;
}
async function get_taxes(sale_id = false) {
    var taxes = [];

    //if (sale_id) {
        /*
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
        */
   // }
   // else {
        var customer_id = await get_customer();
        var customer = await objCustomer.get_info(customer_id);

        //Do not charge sales tax if we have a customer that is not taxable
        if (!Boolean(Number(customer.taxable)) && customer_id != -1) {
            return [];
        }
        var tax_amount = "";
        var items = get_cart();
        var price_to_use=0;
        for (i in items) {
            var item = items[i];
             price_to_use = await _get_price_for_item_in_cart(item);

            var tax_info = item.item_id != undefined ? await objItem_taxes_finder.get_info(item.item_id) : await objItem_kit_taxes_finder.get_info(item.item_kit_id);
            for (i in tax_info) {
                var tax = tax_info[i];
                var name = tax['percent'] + '% ' + tax['name'];

                if (tax['cumulative'] == 1) {
                    var prev_tax = (price_to_use * item.quantity - price_to_use * item.quantity * item.discount / 100) * ((tax_info[parseInt(i) - 1]['percent']) / 100);
                    tax_amount = ((price_to_use * item.quantity - price_to_use * item.quantity * item.discount / 100) + prev_tax) * ((tax['percent']) / 100);
                }
                else {
                    tax_amount = (price_to_use * item.quantity - price_to_use * item.quantity * item.discount / 100) * ((tax.percent) / 100);
                }
                var query = $.inArray(name, get_deleted_taxes());
                if (query == -1) {
                    if (taxes[name] == undefined) {
                        taxes[name] = 0;
                    }

                    taxes[name] += tax_amount;
                }
            }
        }
    //}
    return taxes;
}
async function _get_price_for_item_in_cart(item, sale_id = false) {
    price_to_use = item.price;

    if (item.item_id != undefined) {
        item_info = await objItem.get_info(item.item_id);
        if (item_info.tax_included == 1) {
            if (sale_id) {
                price_to_use = await objItems_helper.get_price_for_item_excluding_taxes(item.line, item.price, sale_id);
            }
            else {
                price_to_use = await objItems_helper.get_price_for_item_excluding_taxes(item.item_id, item.price);
            }
        }
    }

    else if (item.item_kit_id != undefined) {
        var item_kit_info = await objItem_kit.get_info(item.item_kit_id);

        if (item_kit_info.tax_included == 1) {
            if (sale_id) {
                price_to_use = await objitem_kits_helper.get_price_for_item_kit_excluding_taxes(item.line, item.price, sale_id);
            }
            else {
                price_to_use = await objitem_kits_helper.get_price_for_item_kit_excluding_taxes(item.item_kit_id, item.price);
            }
        }

    }
    return price_to_use;
}
async function get_subtotal(sale_id = false) {
    let subtotal = 0;
    var items = get_cart();
    for (i in items) {
        var item = items[i];
        let price_to_use = await _get_price_for_item_in_cart(item, sale_id);
        subtotal += (price_to_use * item.quantity - price_to_use * item.quantity * item.discount / 100);
    }
    let tem = to_currency_no_money(subtotal);
    return tem;
}
function clear_deleted_taxes() {
    localStorage.removeItem("deleted_taxes")

}

function get_items_in_cart() {
    items_in_cart = 0;
    var items = get_cart();
    for (i in items) {
        items_in_cart += items[i].quantity;
    }

    return items_in_cart;
}
async function add(item_id_or_number_or_item_kit_or_receipt) {
    var quantity = 1;
    no_valida_por_id=  $( "input[name='no_valida_por_id']" ).val();
    try {
        if (await is_valid_item_kit(item_id_or_number_or_item_kit_or_receipt)) {
            var info_item_kit = await objItem_kit.get_info(item_id_or_number_or_item_kit_or_receipt);
            if (info_item_kit.deleted == 1 ||
                await objItem_kit.get_info(await objItem_kit.get_item_kit_id(item_id_or_number_or_item_kit_or_receipt)).deleted == 1) {
                toastr.error('No pude agregar el artículo a la venta');
            }
            else {
                await add_item_kit(item_id_or_number_or_item_kit_or_receipt, quantity);
                let item_kit_id = await get_valid_item_kit_id(item_id_or_number_or_item_kit_or_receipt);
                if (await out_of_stock_kit(item_kit_id)) {
                    toastr.warning("Advertencia. La cantidad deseada es insuficiente. Puedes procesar la venta pero revisa tu inventario.")
                }
            }
        }
        else if (! await objItem.get_info(item_id_or_number_or_item_kit_or_receipt).description == ""
            && Boolean(Number(await objGifcard.get_giftcard_id(await objItem.get_info(item_id_or_number_or_item_kit_or_receipt).description, true)))) {
            toastr.error('No pude agregar el artículo a la venta');
        }
        else if (await objItem.get_info(item_id_or_number_or_item_kit_or_receipt).deleted == 1 ||
            await objItem.get_info(await objItem.get_item_id(item_id_or_number_or_item_kit_or_receipt)).deleted == 1 ||

            !await add_item(item_id_or_number_or_item_kit_or_receipt, quantity, 0, null, null, null, false, false, null, null, no_valida_por_id)) {
            toastr.error('No pude agregar el artículo a la venta');
        }
        if (await objAppconfig.item('sales_stock_inventory') == false && await out_of_stock(item_id_or_number_or_item_kit_or_receipt, no_valida_por_id) ) {
            toastr.warning("Advertencia. La cantidad deseada es insuficiente. Puedes procesar la venta pero revisa tu inventario.")
        }
       else if ( await objAppconfig.item('sales_stock_inventory') == true && await out_of_stock(item_id_or_number_or_item_kit_or_receipt, no_valida_por_id)) {
            toastr.error('No se puede agregar el producto si el inventario se encuentra escaso');
        }
        if (await _is_tax_inclusive() && Object.keys(get_deleted_taxes()).length > 0) {
            toastr.warning('No se puede eliminar impuestos si la venta de elemento integrador de impuestos');
        }
        await armar_vista();

    } catch (e) {
        console.error("Error: " + e);
    }
}
async function set_opciones(data){
    $("#topo_factura").html(await objAppconfig.item('sale_prefix'));
    $("#topo_boleta").html(lang('sales_ticket_on_receipt'));
    
    $('#show_comment_on_receipt').attr("checked",Boolean(Number(data["show_comment_on_receipt"])));
    $('#show_receipt').attr("checked",Boolean(Number(data["show_receipt"])));
    if(data["show_comment_on_receipt"] == 1)
    {
        $("#container_comment").removeClass('hidden');
    }else{
    	$("#container_comment").addClass('hidden');
    }
    $("#comment").val(data["comment"]);
    let hide_ticket= await objAppconfig.item('hide_ticket');
    if( (data["mode"]=='sale' || data["mode"]=='return') && hide_ticket ){
        $("#hidde_show_comment_ticket").show();
        let tem = localStorage.getItem("show_comment_ticket")==1?true:false;
        $('#show_comment_ticket').attr("checked",tem);
        tem =localStorage.getItem("show_comment_ticket") == 0 ? true : false;
        $('#show_comment_invoicecked').attr("checked",tem);
                                
    }else{
        $("#hidde_show_comment_ticket").hide();
    }
}
async function add_customer(data){
    let html="";is_over_credit_limit

    if(data["customer"]!=undefined){
        html+='<div class="customer-box">';
        html+='<div class="avatar">'
        html+='<img src="img/avatar.jpg" alt="Customer" class="img-thumbnail"></div>';
		html+='<div class="information info-yes-email">';
        html+='<a class="name" href="" data-toggle="modal" data-target="#myModal">'+escapeHtml(data["customer"]);
         if (data['customers_store_accounts']==1 && data["customer_balance"]!=undefined) {
             cls= is_over_credit_limit==false ? 'credit_limit_warning' : 'credit_limit_ok'; 
            html+='<span class="'+cls+'">';
            html+="("+lang('customers_balance')+': '+await to_currency(data["customer_balance"])+')</span>';
        } 
        html+='</a>';
        html+='<span class="email">'+data["customer_email"];
        /*if(data["customer_email"]!=null && $.trim(data["customer_email"])!="") {
            html+='<div class="md-checkbox-inline">';
            html+='<div class="md-checkbox">';
            html+='<input type="checkbox" name="email_receipt" value="1" id="email_receipt" class="email_receipt_checkbox">	<label for="email_receipt">';
            html+='<span></span>';
            html+='<span class="check"></span>';
            html+='<span class="box"></span>';
            html+='Enviar recibo al e-mail	</label>';
            html+='</div></div>';
        }*/
        html+=' </span></div></div>';
        html+='<div class="customer-buttons-no-email">';
		html+='<div class="btn-group btn-group-justified btn-group-xs">';
        html+='<a href="javascrit:void(0)"  onclick="delete_customer_onclick(this);" id="delete_customer" class="btn yellow-gold">Quitar</a>';	
        html+='</div></div>';
         }else{
            html +='<div class="customer-search">';
            html +='<div class="input-group">';
            html +='<span class="input-group-btn">';
            html +='<a href="javascrit:void(0)" class="btn btn-success tooltips" data-original-title="Cliente">';
            html +='<i class="icon-user-follow hidden-lg"></i>';
            html +='<span class="visible-lg">Cliente </span> </a> </span>';
            html +='<form action="#" autocomplete="off" method="post" accept-charset="utf-8" id="select_customer_form"  >';
            html +='<input type="text" name="customer" ';
            html +='id="customer" class="form-control form-inps-sale ui-autocomplete-input"';
            html +='size="30" placeholder="Empieza a escribir el nombre del cliente..." accesskey="c"';
            html +=' autocomplete="off"> </form>';
            html +='</div>';
            html +='</div>';
         }
    $("#data-customer").html(html);

}
async function select_customer(customer_id)
	{
        let existe =await objCustomer.account_number_exists(customer_id);
		if(existe)
		{
			customer_id =await objCustomer.account_number_exists(customer_id);
		}
		if(await objCustomer.exists(customer_id))
		{
			let customer_info= await objCustomer.get_info(customer_id);
			if (customer_info.tier_id)
			{
				await set_selected_tier_id(customer_info.tier_id);
			}
			await set_customer(customer_id);
			//if($this->config->item('automatically_email_receipt'))
			//{
				//$this->sale_lib->set_email_receipt(1);
            //}
            let data_ = await _reload();
            await add_resumen_venta(data_);
            await add_customer(data_);
            controlador_eventos();
		}
		else
		{
            toastr.error(lang('sales_unable_to_add_customer'));
		}
		
	}

async function armar_vista() {
    let data = await _reload();
    add_customer(data);
    add_resumen_venta(data);
    add_cabeza_tabla(data);
    set_opciones(data);
    await add_filas_tabla(data);
    // se actualizan  de los elementos los eventos 
    $( "input[name='no_valida_por_id']" ).val( await objAppconfig.item('add_cart_by_id_item'));

    controlador_eventos();

}

async function delete_customer_sale(){
 await delete_customer();
 await set_selected_tier_id(0);
 let data_ = await _reload();
 await add_resumen_venta(data_);
 await add_customer(data_);
 controlador_eventos();
}
async function add_resumen_venta(data) {
    let round_value = await objAppconfig.item('round_value');
    var subtotalHtml = round_value == 1 ? await to_currency(Math.round(data["subtotal"])) : await to_currency(data["subtotal"]);
    $("#subtotal").html("<strong>" + subtotalHtml + "</strong>");
    $("#n-articulos").html(data["items_in_cart"]);

    let totalHtml = round_value == 1 ? await to_currency(Math.round(data["total"])) : await to_currency(data["total"]);
    $("#total-amount").html(totalHtml);

    let apagarlHtml = round_value == 1 ? await to_currency(Math.round(data["amount_due"])) : await to_currency(data["amount_due"]);
    $("#amount-due").html(apagarlHtml);
    if (data["payments_cover_total"]) {
        $("#amount-due").removeClass("text-danger").addClass("font-green-jungle");
    }
    else {
        $("#amount-due").removeClass("font-green-jungle").addClass("text-danger");
    }
    await crea_impuestos(data);

    //----agregamos el select de los tiers-----
    if (data["tiers"].length > 1 && data["mode"] != "store_account_payment") {
        $("#tiers").show();
        var tierHtml = "";
        let employee = await objEmployee.get_logged_in_employee_info();
        if (await objEmployee.has_module_action_permission('sales', 'edit_sale_price', employee.person_id)) {
            tierHtml += '<span >';
            tierHtml += crea_select(data["tiers"], data["selected_tier_id"], "tier_id",'',"onchange='cambia_tier(this)'");
            tierHtml += "</span >";
        }
        else {
            tierHtml += '<span class="pull-right">';
            var tier_select = data["tiers"][data["selected_tier_id"]];
            tierHtml += "<div class='item_tier_no_edit text-info'>: " + escapeHtml(tier_select) + "</div>";
            tierHtml += '</span>';
        }

        $("#tiers-body").html(tierHtml);
    }

    else { $("#tiers").hide(); }
    // agregamos select de  metodos de pagos -----------------    
    let paymetHtml = '<span >';
    paymetHtml += crea_select(data["payment_options"], await objAppconfig.item('default_payment_type'), "payment_types");
    paymetHtml += "</span >";
    $("#payment_body").html(paymetHtml);
    //$("#payment_types").addClass( "form-control bs-select" ).addClass( "bs-select" )
    //--------------se agregan los pagos  agregados -----
    crear_payments(data);
    //---------input de ingregar cantidad a pagar----------------

    let value = round_value == 1 ? Math.round(data["amount_due"]) : await to_currency_no_money(data["amount_due"]);
    $("#amount_tendered").val(value);
    $("#add_payment_button").val(lang('sales_add_payment'));

    //--- boton finalizar venta 
    $("#finish_sale_button").hide();
    let query = await is_sale_integrated_cc_processing()
    if (data["payments"].length > 0 && !query) {
        $("#finish_sale_button").val(lang('sales_complete_sale'));
        if (data["payments_cover_total"] && data["customer_required_check"]) {
            $("#finish_sale_button").show();
        } else {
            $("#finish_sale_button").hide();
        }
    } else if (data["payments"].length > 0) {

        $("#finish_sale").html("<h4><strong>Procesamiento de tarjeta no  soprotado</strong></h4>")
    }
    //-----agregar select mesas  
    if (await objAppconfig.item('enabled_for_Restaurant') == '1') {
        $("#mesas-2-body").show();
        let cantmensa = await objAppconfig.item('table_acount');
        let data_mesa = Array("Seleccione Mesa");
        for (i = 1; i <= cantmensa; i++) {
            data_mesa.push("Mesa # " + i);
        }
        $("#body-select").html(crea_select(data_mesa, data["ntable"], "ntable"));

    } else {
        $("#mesas-2-body").hide();
    }

    if ($.isEmptyObject(data["cart"])) {
        $(".add-payment").hide();
        $(".comment-sale").hide();
        $("#n-articulos").removeClass("badge-success").addClass("badge-warning");
    }
    else {
        $(".add-payment").show();
        $(".comment-sale").show();
        $("#n-articulos").removeClass("badge-warning").addClass("badge-success");
    }

}

async function elimina_pago(id_pago) {
    $("#ajax-loader").show();
    delete_payment(id_pago);

    let data_ = await _reload();
    await add_resumen_venta(data_);;

    $("#ajax-loader").hide();
}

async function crear_payments(data) {
    if (data["payments"].length > 0) {
        let payments = data["payments"];

        let html = "";
        //payments_list
        let round_value = await objAppconfig.item('round_value');
        for (payment_id in payments) {
            let payment = payments[payment_id];
            html += ' <li class="list-group-item no-border-top">';
            html += '<span class="name-item-summary">'
            html += '<a href="javascript:elimina_pago(' + payment_id + ');" data-id="' + payment_id + '" class="delete_payment">';
            html += '<i class="fa fa-times-circle fa-lg font-red"></i></a>' +
                payment['payment_type'] + '</span>';

            html += '</span>';
            html += '<span class="name-item-summary pull-right">';
            html += '<strong>';
            html += round_value == 1 ? await to_currency(Math.round(payment['payment_amount'])) : await to_currency(payment['payment_amount'])
            html += '</strong>';
            html += '</span>';
            html += '</li> ';
        }
        html += "<ul></ul>";
        $("#payments_list").html(html);
        $("#payments").show();


    } else {
        $("#payments").hide();
    }
}
async function delete_tax(name) {
    //$this->check_action_permission('delete_taxes');
    // name = rawurldecode(name);
    add_deleted_tax(name);
    let data = await _reload();
    await add_resumen_venta(data);

    //		$this->_reload();
}
function add_deleted_tax(name) {

    let deleted_taxes = localStorage.getItem('deleted_taxes') ? JSON.parse(localStorage.getItem("deleted_taxes")) : Array();
    var query = $.inArray(name, deleted_taxes);
    if (query == -1) {
        deleted_taxes.push(name);
    }
    localStorage.setItem('deleted_taxes', JSON.stringify(deleted_taxes));
}
async function crea_impuestos(data) {
    let taxes = data["taxes"];
    let taxesHtml = "";
    let employee = await objEmployee.get_logged_in_employee_info();
    let permission = await objEmployee.has_module_action_permission('sales', 'delete_taxes', employee.person_id);
    let round_value = await objAppconfig.item('round_value');
    for (key in taxes) {
        var tax = taxes[key];
        if (!(tax == '0.00')) {
            taxesHtml += '<li class="list-group-item">';
            taxesHtml += '<span class="name-item-summary">';

            if (data['is_tax_inclusive'] == false && permission) {
                taxesHtml += '<a href="';
                taxesHtml += "javascript:delete_tax('" + key + "')";
                taxesHtml += '" class="delete_tax">';
                taxesHtml += '<i class="fa fa-times-circle fa-lg font-red"></i>';
                taxesHtml += '</a>';
            }
            taxesHtml += key;
            taxesHtml += '</span>';
            taxesHtml += '<span class="name-item-summary pull-right">';
            taxesHtml += ' <strong>'
            taxesHtml += round_value == 1 ? await to_currency(Math.round(tax)) : await to_currency(tax);
            taxesHtml += '</strong>';
            taxesHtml += ' </span>';
            taxesHtml += ' </li>';
        }
    }

    $("#ivas").html(taxesHtml);;

}
function crea_select(data, select_id, name, clases = "", otros = "") {
    let Html = '<select name="' + name + '" id="' + name + '" class="form-control ' +
        clases + '" ' + otros + ' > ';

    for (i in data) {
        Html += '<option value="' + i + '"';
        if (select_id == i) {
            Html += ' selected="selected "';
        }
        Html += ">" + data[i] + '</option>';
    }
    Html += "</select>";
    return Html;

}
async function add_item(item_id, cantidad = 1, discount = 0, precio = null, description = null, serialnumber = null, force_add = false, line = false, custom1_subcategory = null, custom2_subcategory = null, no_valida_por_id = false) {
    var store_account_item_id = await objItem.get_store_account_item_id("Abono a línea de crédito");

    //Do NOT allow item to get added unless in store_account_payment mode
    if (!force_add && get_mode() !== 'store_account_payment' && store_account_item_id == item_id) {
        return false;
    }
    //make sure item exists
    if (! await objItem.existe(!isNaN(item_id) ? item_id : -1) || no_valida_por_id == true) {
        //try to get item id given an item_number
        item_id = await objItem.get_item_id(item_id);

        if (!item_id)
            return false;
    }
    else {
        item_id = parseInt(item_id);
    }
    let items = get_cart();
    var maxkey = 0;
    var existe = false;
    var insertkey = 0;
    var updatekey = 0;
    var item_info = await (objItem.get_info(item_id));
    for (i in items) {
        let item = items[i];
        if (maxkey <= item.line) {
            maxkey = item.line;
        }
        if (undefined != item.item_id && item.item_id == item_id && item.custom1_subcategory == custom1_subcategory && item.custom2_subcategory == custom2_subcategory) {
            existe = true;
            updatekey = item.line;

            if (item_info.description == items[updatekey].description && item_info.name == "Tarjeta de Regalo") {
                return false;
            }
        }
    };
    insertkey = maxkey + 1;
    if (await objAppconfig.item("subcategory_of_items")==1 &&   await objAppconfig.item("inhabilitar_subcategory1")==1 && custom1_subcategory==null){

        custom1_subcategory="»";
    }
    let price_to_use = await get_precio_por_item(item_id);

    var clave = (line == false ? insertkey : line);
    item = {
        'item_id': item_id,
        'line': line == false ? insertkey : line,
        'name': item_info.name,
        'size': item_info.size,
        'model': item_info.model,
        'colour': item_info.colour,
        'marca': item_info.marca,
        'unit': item_info.unit,
        'item_number': item_info.item_number,
        'product_id': item_info.product_id,
        'description': (description != null ? description : item_info.description),
        'serialnumber': (serialnumber != null ? serialnumber : ''),
        'allow_alt_description': item_info.allow_alt_description,
        'is_serialized': item_info.is_serialized,
        'quantity': cantidad,
        'discount': discount,
        "has_subcategory":item_info.subcategory,
        'custom1_subcategory': custom1_subcategory,
        'custom2_subcategory': custom2_subcategory,
        "tax_included":item_info.tax_included,
        'price': (precio != null ? precio : price_to_use),
        "id_tier":0,
        "is_promo":0,

    };
    if (existe && (item_info.is_serialized == 0)) {
        items[line == false ? updatekey : line]["quantity"] += cantidad;
    }
    else {
        items[clave] = item;
    }
    if (await objAppconfig.item('sales_stock_inventory') == true && await out_of_stock(item_id)) {
        items = get_cart();
    }
    set_cart(items);
    return true;
}
async function out_of_stock(item_id, no_valida_por_id = false) {
    //make sure item exists
    if (! await objItem.existe(item_id) || no_valida_por_id == true) {
        //try to get item id given an item_number
        item_id = await objItem.get_item_id(item_id);

        if (!item_id) 
            return false;
    }
    var item_location_quantity = await objItem_location.get_location_quantity(item_id, await get_ubicacio_id());
    var quanity_added = await get_quantity_already_added(item_id);
    //If $item_location_quantity is NULL we don't track quantity
    if (item_location_quantity != null && item_location_quantity - quanity_added <= 0) {
        return true;
    }
    return false;
}

async function add_cabeza_tabla(data) {
    var cabeza = '<tr class="size-row" style="height: 36px;">';
    cabeza += ' <th class="floatThead-col" style="height: 36px;"></th>';
    cabeza += ' <th class="item_name_heading" >' + lang('sales_item_name'); '</th>';

    if (await objAppconfig.item('subcategory_of_items') == 1) {
        cabeza += '<th >' + await objAppconfig.item("custom_subcategory1_name"); +'</th>';
        cabeza += '<th >' + await objAppconfig.item("custom_subcategory2_name"); +'</th>';
    }
    if (data["show_sales_num_item"] == 1) {
        cabeza += '  <th class="sales_item sales_items_number">';

        switch (await objAppconfig.item('id_to_show_on_sale_interface')) {
            case 'number':
                cabeza += lang('sales_item_number');
                break;

            case 'product_id':
                cabeza += lang('items_product_id');
                break;

            case 'id':
                cabeza += lang('items_item_id');
                break;

            default:
                cabeza += lang('sales_item_number');
                break;
        }
        cabeza += '</th>';
    }
    if (data["show_sales_inventory"] == 1) {
        cabeza += '<th class="sales_stock">' + lang('sales_stock') + '</th>';
    }
    let name = 0;
    let items = data["cart"];
    // como no podemos inertir por que javascrit ordena de nuevo el array primero get la key invertidas
    const key = Object.keys(items).reverse();
    for (i in key) {
        name = items[key[i]]['name'];
    }
   /* if (name == lang('sales_giftcard')) {
        if (data["show_sales_price_without_tax"] == true) {
            cabeza += '<th class="sales_price">' + lang('sales_price_iva') + '</th>';
        }
        if (data["show_sales_price_iva"] == true) {
            cabeza += '<th id="reg_item_tax">' + lang('receivings_item_tax'); '</th>';
        }
    } else {*/
        if (data["show_sales_price_without_tax"] == 1) {
            cabeza += '<th class="sales_price_without_tax">' + lang('sales_price_without_tax') + '</th>';
        }
        if (data["show_sales_price_iva"] == 1) {
            cabeza += '<th id="sales_price_tax">' + lang('sales_price_tax') + '</th>';
        }
//}
    cabeza += ' <th class="sales_price">' + lang('sales_price') + '</th>';
    cabeza += ' <th class="sales_quality">' + lang('sales_quantity') + '</th>';
    if (data["show_sales_discount"] == 1) {
        cabeza += '<th class="sales_discount">' + lang('sales_discount') + '</th>';
    }
    cabeza += '<th class="sales_total">' + lang('sales_total') + '</th>';

    cabeza += "</tr>";
    $("#cabeza").html(cabeza);

}

async function add_filas_tabla(data) {
    // volmemos a cargar los productos 
    let prev_tax = Array();
    var filas = '';
    let price_with_tax = 0;
    let id_to_show_on_sale_interface= await objAppconfig.item('id_to_show_on_sale_interface');
    let subcategory_of_items=await objAppconfig.item('subcategory_of_items');
    let round_value =await objAppconfig.item('round_value');
    let sales_giftcard = lang('sales_giftcard');
   // $("#register tbody tr ").remove();
    var items = data["cart"];
    if ($.isEmptyObject(items)) {
        $("#register tbody tr ").remove();
        filas = '<tr class="cart_content_area"><td colspan="8"><div class="text-center text-danger">	<h3>No hay artículos en el carrito</h3></div></td></tr>';
        $("#register tbody ").html(filas);
        $("#sale-buttons").hide();

    } else {
        $("#sale-buttons").show();
    }
    if (data["mode"] != 'store_account_payment') {
        $("#btn-sales").show();
    } else {
        $("#btn-sales").hide();
    }
    const key = Object.keys(items).reverse();
    let empleado_ = await objEmployee.get_logged_in_employee_info();
    let edit_sale_price = await objEmployee.has_module_action_permission('sales', 'edit_sale_price', empleado_.person_id);

    for (var i in key) {
        let item = items[key[i]];
        let cur_item_location_info = item.item_id != undefined ? await objItem_location.get_info(item.item_id) : await objItem_kit_location.get_info(item.item_kit_id);
        let item_info = item.item_id != undefined ? await objItem.get_info(item.item_id) : await objItem_kit.get_info(item.item_kit_id);
        let itemId = item_info.item_id != undefined ? item_info.item_id : item_info.item_kit_id;
        filas += '<tr  class="register-item-details" style="font-size:85%;">';

        filas += '<td class="text-center">  <a href="javascrit:void(0)"  onclick="delete_item_onclick(this,'+item.line+')" class="delete_item"><i class="fa fa-trash-o fa fa-2x font-red"></i> </a>  </td>';
        filas += '<td class="text text-success" > <a href="" data-toggle="modal" data-target="#myModal">' + escapeHtml(item.name) + ' </a> </td>';


        if (subcategory_of_items == true) {
            //let item_info = await objItem.get_info(item.item_id);

            filas += '<td width="3%" class="text text-center">';


            if (item.item_id != undefined && item_info.subcategory == true) {
                filas += '<form action="' + item.line + '" method="post" accept-charset="utf-8" class="line_item_form"  onsubmit="return editar_producto_cart(this)" autocomplete="off">';

                let custom1 = await objItems_subcategory.get_custom1(item.item_id, false);
                let data_custom1_subcategory = Array();
                data_custom1_subcategory[""] = "---------";
                for (i in custom1) {
                    data_custom1_subcategory[custom1[i]] = (custom1[i]);
                }
                if (item.custom1_subcategory != null && data_custom1_subcategory[item.custom1_subcategory] == undefined) {
                    data_custom1_subcategory[item.custom1_subcategory] = item.custom1_subcategory + "(No disponible)";
                }
                filas += crea_select(data_custom1_subcategory, item.custom1_subcategory, "custom1_subcategory", "select_custom_subcategory", " style='width: 90px;' onchange='cambia_subcategoria(this)'");
                filas += '</form >';
            }

            filas += '</td>';

            filas += ' <td  width="3%"class="text text-success">';

            if (item.item_id != undefined && item_info.subcategory == true) {
                filas += '<form action="' + item.line + '" method="post" accept-charset="utf-8" class="line_item_form"  onsubmit="return editar_producto_cart(this)" autocomplete="off">';
                let custom2 = await objItems_subcategory.get_custom2(item.item_id, false, item['custom1_subcategory']);

                let data_custom2_subcategory = Array();
                data_custom2_subcategory[""] = "---------";
                for (i in custom2) {
                    data_custom2_subcategory[custom2[i].custom2] = custom2[i].custom2 + " (" + parseFloat(custom2[i].quantity) + ")";
                }
                if (item['custom2_subcategory'] != null && data_custom2_subcategory[item.custom2_subcategory] == undefined) {
                    data_custom2_subcategory[item.custom2_subcategory] = item.custom2_subcategory + "(No disponible)";
                }
                filas += crea_select(data_custom2_subcategory, item.custom2_subcategory, "custom2_subcategory", "select_custom_subcategory", " style='width: 90px;' onchange='cambia_subcategoria(this)'");
                //echo form_dropdown('custom2_subcategory',$data_custom2_subcategory,$item['custom2_subcategory'],'id="custom2_subcategory" style="width: 90px;" class="bs-select form-control select_custom_subcategory"');
                filas += '</form >';
            }

            filas += '</td>';
        }
        if (data["show_sales_num_item"] == true) {
            filas += '<td class="text-center text-info sales_item" id="reg_item_number">';
            switch (id_to_show_on_sale_interface) {
                case 'number':
                    filas += item.item_number != undefined ? escapeHtml(item.item_number) : escapeHtml(item.item_kit_number);
                    break;

                case 'product_id':
                    filas += item.product_id != undefined ? escapeHtml(item.product_id) : lang('items_none');
                    break;

                case 'id':
                    filas += item.item_id != undefined ? escapeHtml(item.item_id) : 'KIT '.escapeHtml(item.item_kit_id);
                    break;

                default:
                    filas += item.item_number != undefined ? escapeHtml(item.item_number) : escapeHtml(item.item_kit_number);
                    break;
            }
            filas += '</td>';
        }
        if (data["show_sales_inventory"] == 1) {
            filas += '<td class="text-center font-yellow-gold sales_stock" id="reg_item_stock" >';
            filas += ('quantity' in cur_item_location_info ? to_quantity(cur_item_location_info.quantity== "" ? 0:cur_item_location_info.quantity) : '');
            filas += '</td>';
        }


       
        if (item_info.tax_included == true) {
            if (item.item_id != undefined) {
                let tax_info = item.item_id != undefined ? await objItem_taxes_finder.get_info(item.item_id) : await objItem_kit_taxes_finder.get_info(item.item_kit_id);
                let i = 0;

                for (let key in tax_info) {
                    var tax = tax_info[key];
                    if (prev_tax[item.item_id] == undefined) {
                        let aux = Array();
                        aux[i] = tax['percent'] / 100;
                        prev_tax[item.item_id] = aux;
                    } else {

                        prev_tax[item.item_id][i] = tax['percent'] / 100;

                    }
                    i++;
                }

                if (prev_tax[item.item_id] != undefined && item.name != sales_giftcard) {
                    let sum_tax = array_sum(prev_tax[item.item_id]);
                    let price_without_tax = item.price / (1 + sum_tax);
                    let value_tax = price_without_tax * sum_tax;

                    if (data["show_sales_price_without_tax"] == 1) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_without_tax, 2);
                        filas += '</td>';
                    }
                    if (data["show_sales_price_iva"] == 1) {
                        filas += ' <td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                }
                else if (prev_tax[item.item_id] == undefined && item.name != sales_giftcard) {
                    var sum_tax = 0;
                    var price_without_tax = item.price / (1 + sum_tax);
                    var value_tax = price_without_tax * sum_tax;

                    if (data["show_sales_price_without_tax"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_without_tax, 2);
                        filas += ' </td>';
                    }
                    if (data["show_sales_price_iva"]==1) {
                        filas += ' <td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                }
                else {
                    filas += '<td></td> <td></td>';
                }
            }
            if (item.item_kit_id != undefined) {
                let tax_kit_info = item.item_kit_id != undefined ? await objItem_kit_taxes_finder.get_info(item.item_kit_id) : await objItem_kit_taxes_finder.get_info(item.item_id);
                let i = 0;
                for (let key in tax_kit_info) {
                    var tax = tax_kit_info[key];
                    if (prev_tax[item.item_kit_id] == undefined) {
                        let aux = Array();
                        aux[i] = tax['percent'] / 100;
                        prev_tax[item.item_kit_id] = aux;
                    } else {
                        prev_tax[item.item_kit_id][i] = tax['percent'] / 100;
                    }
                    i++;
                }

                if (prev_tax[item.item_kit_id] != undefined && item.name != sales_giftcard) {
                    let sum_tax = array_sum(prev_tax[item.item_kit_id]);
                    let price_without_tax = item.price / (1 + sum_tax);
                    let value_tax = price_without_tax * sum_tax;

                    if (data["show_sales_price_without_tax"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_without_tax, 2);
                        filas += '</td>';
                    }
                    if (data["show_sales_price_iva"] == 1) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                }
                else if (prev_tax[item.item_kit_id] == undefined && item.name != sales_giftcard) {
                    let sum_tax = 0;
                    let price_without_tax = item.price / (1 + sum_tax);
                    let value_tax = price_without_tax * sum_tax;

                    if (data["show_sales_price_without_tax"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_without_tax, 2);
                        filas += '</td>';
                    }
                    if (data["show_sales_price_iva"] == 1) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                }
                else {
                    filas += '<td></td> <td></td>';
                }
            }

            if (edit_sale_price) {
                filas += '<td class="text-center">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)"  autocomplete="off">';
                filas += '<input type="text" name="price" value="' + Number(to_currency_no_money(item.price, 10)) + '" class="form-control form-inps-sale text-center input-small text-center " id="price_"' + item.line + '/>';

                filas += '</form>';
                filas += ' </td >';
            }
            else {
                filas += '<td class="text-center">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)" autocomplete="off">';

                filas += parseFloat(item.price);
                filas += '<input type="hidden" name="price" value="' + item.price + '"/>';

                filas += '</form>';
                filas += '</td >';
            }

        }
        else {
            if (data["show_sales_price_without_tax"] == true) {

                if (edit_sale_price) {

                    filas += '<td class="text-center">';
                    filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)"  autocomplete="off">';
                    filas += '<input type="text" name="price" value="' + Number(to_currency_no_money(item.price, 10)) + '" class="form-control form-inps-sale text-center input-small text-center " id="price_"' + item.line + '/>';

                    filas += '</form>';
                    filas += ' </td >';
                }
                else {
                    filas += '<td class="text-center">';
                    filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)" autocomplete="off">';

                    filas += parseFloat(item.price);
                    filas += '<input type="hidden" name="price" value="' + item.price + '"/>';

                    filas += '</form>';
                    filas += '</td >';
                }
            }

            if (item.item_id != undefined) {
                let tax_info = item.item_id != undefined ? await objItem_taxes_finder.get_info(item.item_id) : await objItem_kit_taxes_finder.get_info(item.item_kit_id);
                let i = 0;

                for (let key in tax_info) {
                    var tax = tax_info[key];
                    if (prev_tax[item.item_id] == undefined) {
                        let aux = Array();
                        aux[i] = tax['percent'] / 100;
                        prev_tax[item.item_id] = aux;
                    } else {
                        prev_tax[item.item_id][i] = tax['percent'] / 100;
                    }
                    i++;
                }
                if (prev_tax[item.item_id] != undefined && item.name != sales_giftcard) {
                    let sum_tax = array_sum(prev_tax[item.item_id]);
                    let value_tax = item.price * sum_tax;
                    price_with_tax = parseFloat(item.price) + value_tax;


                    if (data["show_sales_price_iva"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                    filas += '<td class="text-center">';
                    filas += await to_currency(price_with_tax, 2);
                    filas += '</td>';

                }
                else if (prev_tax[item.item_id] == undefined && item.name != sales_giftcard) {
                    let sum_tax = 0;
                    let value_tax = item.price * sum_tax;
                    price_with_tax = parseFloat(item.price) + value_tax;

                    if (data["show_sales_price_iva"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                    filas += '<td class="text-center">';
                    filas += await to_currency(price_with_tax, 2);
                    filas += '</td>';

                }
                else {
                    if (data["show_sales_price_iva"] == true) {
                        filas += '<td></td>';
                    }

                    filas += '<td></td>';

                }
            }
            if (item.item_kit_id != undefined) {
                let tax_kit_info = item.item_kit_id != undefined ? await objItem_kit_taxes_finder.get_info(item.item_kit_id) : await objItem_kit_taxes_finder.get_info(item.item_id);
                let i = 0;
                for (let key in tax_kit_info) {
                    var tax = tax_kit_info[key];
                    if (prev_tax[item.item_kit_id] == undefined) {
                        let aux = Array();
                        aux[i] = tax['percent'] / 100;
                        prev_tax[item.item_kit_id] = aux;
                    } else {
                        prev_tax[item.item_kit_id][i] = tax['percent'] / 100;
                    }
                    i++;
                }

                if (prev_tax[item.item_kit_id] != undefined && item.name != sales_giftcard) {
                    let sum_tax = array_sum(prev_tax[item.item_kit_id]);
                    let value_tax = item.price * sum_tax;
                    price_with_tax = parseFloat(item.price) + value_tax;

                    if (data["show_sales_price_iva"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                    if (data["show_sales_price_without_tax"]) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_with_tax, 2)
                        filas += '</td>';
                    }
                    if (data["show_sales_num_item"] == false && data["show_sales_inventory"] == false &&
                        data["show_sales_price_iva"] == false && data["show_sales_price_without_tax"] == false &&
                        data["show_sales_discount"] == false) {
                        filas += '<td></td>';
                    }
                    else if (data["show_sales_price_without_tax"] == false) {
                        filas += '<td></td>';
                    }
                }

                else if (prev_tax[item.item_kit_id] == undefined && item.name != sales_giftcard) {
                    let sum_tax = 0;
                    let value_tax = item.price * sum_tax;
                    price_with_tax = parseFloat(item.price) + value_tax;

                    if (data["show_sales_price_iva"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(value_tax, 2);
                        filas += '</td>';
                    }
                    if (data["show_sales_price_without_tax"] == true) {
                        filas += '<td class="text-center">';
                        filas += await to_currency(price_with_tax, 2);
                        filas += '</td>';
                    }
                    if (data["how_sales_num_item"] == false && data["show_sales_inventory"] == false &&
                        data["show_sales_price_iva"] == false && data["show_sales_price_without_tax"] == false &&
                        data["show_sales_discount"] == false) {
                        filas += '<td></td>';
                    }
                    else if (data["show_sales_price_without_tax"] == false) {
                        filas += ' <td></td>';
                    }
                }
                else {
                    filas += '<td></td> <td></td>';
                }

            }
        }
        filas += '<td width="3%" class="text-center">';
        filas += '<form action="' + item.line + '/' + itemId + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)" autocomplete="off">';

        if (item.is_serialized != undefined && item.is_serialized == 1) {
            filas += to_quantity(item.quantity);
            filas += '<input type="hidden" name="quantity" value="' + to_quantity(item.quantity) + '"/>';
        }
        else {
            filas += '<input type="text" name="quantity" class="form-control form-inps-sale text-center" id="quantity_' + item.line + '" tabindex="2" value="' + to_quantity(item.quantity) + '"/>';
        }
        filas += '</form>';
        filas += '</td>';

        if (data["show_sales_discount"] == true) {

            if (edit_sale_price) {
                filas += '<td width="">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return  editar_producto_cart(this)"  autocomplete="off">';
                filas += '<input type="text" class="form-control form-inps-sale input-small text-center" name="discount" id="discount_' + item.line + '" tabindex="2" value="' + item.discount + '"/>';

                filas += '</form>';
                filas += '</td>';
            }
            else {
                filas += '<td width="3%">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)"  autocomplete="off">';
                filas += item['discount'];
                filas += '<input type="hidden" name="discount" value="' + item.discount + '"/>';


                filas += '</form>';
                filas += '</td>';
            }
        }
        let Total = 0;
        if (item_info.tax_included == true) {
            Total = item.price * item.quantity - item.price * item.quantity * item.discount / 100;
        }
        else if (item_info.category == lang('giftcards_giftcard')) {
            Total = item.price * item.quantity - item.price * item.quantity * item.discount / 100;
        }
        else {
            Total = price_with_tax * item.quantity - price_with_tax * item.quantity * item.discount / 100;
        }

        filas += '<td class="text-center">';
        filas +=round_value == 1 ? await to_currency(Math.round(Total)) : await to_currency(Total);
        filas += '</td>';
        filas += '</tr>';


        if (item.is_serialized != undefined && item.is_serialized == 1 && item.name != sales_giftcard || data["show_sales_description"]==1) {
            filas += '<tr id="reg_item_bottom" class="register-item-bottom">';
            if (data["show_sales_description"] == true) {
                filas += '<td >';
                filas += '<strong>' + lang('sales_description_abbrv') + ':</strong>';
                filas += '</td>';
                filas += '<td colspan="2" class="edit_description">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form" onsubmit="return editar_producto_cart(this)" autocomplete="off">';

                if (item.allow_alt_description = !undefined && item.allow_alt_description == 1) {
                    filas += '<input type="text" size="20" class="form-control form-inps-sale input-small text-center" name ="description" id="description_"' + item.lane + ' value="' + item.description + '" maxlength="2250"/>';
                }
                else {
                    if (item.description != '') {
                        filas += item.description;
                        filas += '<input type="hidden" name="description" value="' + item.description + '"/>';
                    }
                    else {
                        filas += 'None';
                        filas += '<input type="hidden" name="description" value=""/>';
                    }
                }
                filas += '</form>';
                filas += '</td>';
            }

            filas += '<td>';
            if (item.is_serialized != undefined && item.is_serialized == 1 && item.name != sales_giftcard) {
                filas += "<strong>";
                filas += lang('sales_serial') + ':';
                filas += "</strong>";
            }
            filas += '</td>';
            if (item.is_serialized != undefined && item.is_serialized == 1 && item.name != sales_giftcard) {
                filas += '<td colspan="1" class="edit_serialnumber">';
                filas += '<form action="' + item.line + '" method="post"  class="line_item_form"  onsubmit="return editar_producto_cart(this)"  autocomplete="off">';

                if (item.is_serialized && item.is_serialized == 1 && item.name != sales_giftcard) {
                    filas += '<input type="text" size="20" class="form-control form-inps-sale serial_item" name ="serialnumber" id="serialnumber_"' + item.lane + ' value="' + item.serialnumber + '" maxlength="2250"/>';
                }
                else {
                    filas += '<input type="hidden" name="serialnumber" value=""/>';

                }
                filas += '</form>';
                filas += '</td>';
            }
            filas += '</tr>';
        
        }
    }
    append(filas);
}
async function append(filas){
 $("#register tbody tr ").remove(); 
$("#register tbody ").append(filas);
}
function get_cart() {
    items = JSON.parse(localStorage.getItem("cart"));
    return items != null ? items : {};
}
function set_cart(items) {
    localStorage.setItem('cart', JSON.stringify(items));
}
function get_mode() {
    mode = localStorage.getItem("sale_mode");
    if (mode == null || mode == undefined) {
        set_mode('sale');
    }
    return localStorage.getItem("sale_mode");
}
function set_mode(mode) {
    localStorage.setItem('sale_mode', mode);
}
/**function get_ubicacio_id() {
    var ubicacion = -1;
    if ($.isNumeric(localStorage.getItem("ubicacion_id"))) {
        ubicacion = parseInt(localStorage.getItem("ubicacion_id"));
    } else {
        alert("Debe se seleccionar una tienda")
        location.reload();
    }
    return ubicacion;
}
function set_ubicacion_id(ubicacion_id) {
    localStorage.setItem('ubicacion_id', ubicacion_id);
}
*/
async function get_precio_por_item(item_id, tier_id = false) {
    if (tier_id == false) {
        tier_id = get_selected_tier_id();
    }
    var item_info = await objItem.get_info(item_id);
    var item_location_info = await objItem_location.get_info(item_id);
    var item_tier_row = await objItem.get_tier_price_row(tier_id, item_id);
    var item_location_tier_row = await objItem_location.get_tier_price_row(tier_id, item_id, await get_ubicacio_id());

    if (!$.isEmptyObject(item_location_tier_row) && item_location_tier_row.unit_price) {
        return to_currency_no_money(item_location_tier_row.unit_price, (await objAppconfig.item('round_tier_prices_to_2_decimals') == 1) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_location_tier_row) && item_location_tier_row.percent_off) {
        let item_unit_price = item_location_info.unit_price ? item_location_info.unit_price : item_info.unit_price;
        return to_currency_no_money(item_unit_price * (1 - (item_location_tier_row.percent_off / 100)), (await objAppconfig.item('round_tier_prices_to_2_decimals') == 1) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_tier_row) && item_tier_row.unit_price) {
        return to_currency_no_money(item_tier_row.unit_price, (await objAppconfig.item('round_tier_prices_to_2_decimals') == 1) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_tier_row) && item_tier_row.percent_off) {
        let item_unit_price = item_location_info.unit_price ? item_location_info.unit_price : item_info.unit_price;
        return to_currency_no_money(item_unit_price * (1 - (item_tier_row.percent_off / 100)), (await objAppconfig.item('round_tier_prices_to_2_decimals') == 1) ? 2 : 10);
    }
    else {
        var today = strtotime(moment().format("YYYY-MM-DD"));
        //$today =  strtotime(date('Y-m-d'));
        var is_item_location_promo = (item_location_info.start_date != null && item_location_info.end_date != null) && (strtotime(item_location_info.start_date) <= today && strtotime(item_location_info.end_date) >= today);
        var is_item_promo = (await objItem.get_promo_quantity(item_id)) > 0 && (item_info.start_date != null && item_info.end_date != null) && (strtotime(item_info.start_date) <= today && strtotime(item_info.end_date) >= today);

        if (is_item_location_promo) {
            return to_currency_no_money(item_location_info.promo_price, 10);
        }
        else if (is_item_promo) {
            return to_currency_no_money(item_info.promo_price, 10);
        }
        else {
            item_unit_price = item_location_info.unit_price ? item_location_info.unit_price : item_info.unit_price;
            return to_currency_no_money(item_unit_price, 10);
        }
    }
}
async function set_tier_id(id) {
    await set_selected_tier_id(id);
}
async function set_selected_tier_id(tier_id, change_price_ = true) {
    localStorage.setItem('previous_tier_id', get_selected_tier_id());
    localStorage.setItem('selected_tier_id', tier_id);
    if (change_price_ == true) {
        await change_price();
    }
}
function get_selected_tier_id() {
    tier_id = localStorage.getItem("selected_tier_id");
    return tier_id != null ? tier_id : 0;
}
async function get_quantity_already_added(item_id) {
    var items = get_cart();
    var quanity_already_added = 0;
    for (i in items) {
        var item = items[i];
        if (undefined != item.item_id && item.item_id == item_id) {
            quanity_already_added += item.quantity;
        }
    }
    //Check Item Kist for this item
    var all_kits = await objItem_kit_item.get_kits_have_item(item_id);
    for (i in all_kits) {
        var kits = all_kits[i];
        kit_quantity = get_kit_quantity_already_added(kits.item_kit_id);
        if (kit_quantity > 0) {
            quanity_already_added += (kit_quantity * kits.quantity);
        }
    }
    return quanity_already_added;
}
function get_kit_quantity_already_added(kit_id) {
    items = get_cart();
    quanity_already_added = 0;
    for (var i in items) {
        var item = items[i];
        if (item.item_kit_id != undefined && item.item_kit_id == kit_id) {
            quanity_already_added += item.quantity;
        }
    }
    return quanity_already_added;
}
async function is_valid_item_kit(item_kit_id) {
    //KIT #
    pieces = item_kit_id.split(' ');
    if (pieces.length == 2 && pieces[0].toLowerCase() == 'kit') {
        return await objItem_kit.exists(pieces[1]);
    }
    else {
        return await objItem_kit.get_item_kit_id(item_kit_id) != false;
    }
}
async function add_item_kit(external_item_kit_id_or_item_number, quantity = 1, discount = 0, price = null, description = null, line = false) {
    if (external_item_kit_id_or_item_number.toLowerCase().indexOf('kit') != -1) {
        //KIT #
        pieces = external_item_kit_id_or_item_number.split(' ');
        item_kit_id = parseInt(pieces[1]);
    }
    else {
        item_kit_id = await objItem_kit.get_item_kit_id(external_item_kit_id_or_item_number);
    }
    //make sure item exists
    if (!await objItem_kit.exists(item_kit_id)) {
        return false;
    }
    item_kit_info = await objItem_kit.get_info(item_kit_id);
    if (item_kit_info.unit_price == null) {
        var items_kit = await objItem_kit_item.get_info(item_kit_id);
        for (i in items_kit) {
            var item_kit_item = items_kit[i];

            for (k = 0; k < item_kit_item.quantity; k++) {
                await add_item(item_kit_item.item_id, quantity);
            }
        }
        return true;
    }
    else {
        items = get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.
        maxkey = 0;                       //Highest key so far
        existe = false;        //We did not find the item yet.
        insertkey = 0;                    //Key to use for new entry.
        updatekey = 0;                    //Key to use to update(quantity)

        for (i in items) {
            var item = items[i];
            if (maxkey <= item.line) {
                maxkey = item.line;
            }
            if (item.item_kit_id != undefined && item.item_kit_id == item_kit_id) {
                existe = true;
                updatekey = item.line;
            }
        }
        insertkey = maxkey + 1;
        price_to_use = await get_price_for_item_kit(item_kit_id);
        //array/cart records are identified by $insertkey and item_id is just another field.
        var clave = (line == false ? insertkey : line);
        item = {
            'item_kit_id': item_kit_id,
            'line': line == false ? insertkey : line,
            'item_kit_number': item_kit_info.item_kit_number,
            'product_id': item_kit_info.product_id,
            'name': item_kit_info.name,
            'size': '',
            'description': description != null ? description : item_kit_info.description,
            'quantity': quantity,
            'discount': discount,
            "tax_included":item_kit_info.tax_included,
            "has_subcategory":0,
            'price': price != null ? price : price_to_use
        }
        //Item already exists and is not serialized, add to quantity
        if (existe) {
            items[line == false ? updatekey : line].quantity += quantity;
        }
        else {
            //add to existing array
            items[clave] = item;
        }
        set_cart(items);
        return true;
    }
}

async function is_select_subcategory_items(){
    if(await objAppconfig.item('subcategory_of_items')){
        let items = get_cart();
       for (line in items )
        {
            let item= items[line];
            if (item['item_id']!=undefined && item.has_subcategory==1)
            {               
                if(item['custom1_subcategory']=="" || item['custom1_subcategory']==null || item['custom2_subcategory']=="" ||
                    item['custom2_subcategory']==null){
                    return false;
                }                
            }
        }
    }
    return true;
    
}
async function get_price_for_item_kit(item_kit_id, tier_id = false) {
    if (tier_id == false) {
        tier_id = await get_selected_tier_id();
    }
    var item_kit_info = await objItem_kit.get_info(item_kit_id);
    var item_kit_location_info = await objItem_kit_location.get_info(item_kit_id);
    var item_kit_tier_row = await objItem_kit.get_tier_price_row(tier_id, item_kit_id);
    var item_kit_location_tier_row = await objItem_kit_location.get_tier_price_row(tier_id, item_kit_id, await get_ubicacio_id());

    if (!$.isEmptyObject(item_kit_location_tier_row) && item_kit_location_tier_row.unit_price) {
        return to_currency_no_money(item_kit_location_tier_row.unit_price, (Boolean(Number(await objAppconfig.item('round_tier_prices_to_2_decimals')))) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_kit_location_tier_row) && item_kit_location_tier_row.percent_off) {
        item_kit_unit_price = item_kit_location_info.unit_price ? item_kit_location_info.unit_price : item_kit_info.unit_price;
        return to_currency_no_money(item_kit_unit_price * (1 - (item_kit_location_tier_row.percent_off / 100)), (Boolean(Number(await objAppconfig.item('round_tier_prices_to_2_decimals')))) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_kit_tier_row) && item_kit_tier_row.unit_price) {
        return to_currency_no_money(item_kit_tier_row.unit_price, (Boolean(Number(await objAppconfig.item('round_tier_prices_to_2_decimals')))) ? 2 : 10);
    }
    else if (!$.isEmptyObject(item_kit_tier_row) && item_kit_tier_row.percent_off) {
        item_kit_unit_price = item_kit_location_info.unit_price ? item_kit_location_info.unit_price : item_kit_info.unit_price;
        return to_currency_no_money(item_kit_unit_price * (1 - (item_kit_tier_row.percent_off / 100)), (await objAppconfig.item('round_tier_prices_to_2_decimals')) ? 2 : 10);
    }
    else {
        item_kit_unit_price = item_kit_location_info.unit_price ? item_kit_location_info.unit_price : item_kit_info.unit_price;
        return to_currency_no_money(item_kit_unit_price, 10);
    }
}
async function get_valid_item_kit_id(item_kit_id) {
    //KIT #		
    pieces = item_kit_id.split(' ');
    if (pieces.length == 2 && pieces[0].toLowerCase() == 'kit') {
        return parseInt(pieces[1])
    }
    else {
        return await objItem_kit.get_item_kit_id(item_kit_id);
    }
}
async function out_of_stock_kit(kit_id) {
    //Make sure Item kit exist
    if (! await objItem_kit.exists(kit_id)) return false;
    //Get All Items for Kit
    var kit_items = await objItem_kit_item.get_info(kit_id);
    //Check each item
    for (i in kit_items) {
        var item = kit_items[i];
        var item_location_quantity = await objItem_location.get_location_quantity(item.item_id);
        var item_already_added = await get_quantity_already_added(item.item_id);

        if (item_location_quantity - item_already_added < 0) {
            return true;
        }
    }
    return false;
}
async function _is_tax_inclusive() {
    var is_tax_inclusive = false;
    var items = get_cart();
    for (i in items) {
        var item = items[i];
        if (item.item_id != undefined) {
            cur_item_info = await objItem.get_info(item.item_id);
            if (cur_item_info.tax_included == 1) {
                is_tax_inclusive = true;
                break;
            }
        }
        else //item kit
        {
            cur_item_kit_info = await objItem_kit.get_info(item.item_kit_id);
            if (cur_item_kit_info.tax_included == 1) {
                is_tax_inclusive = true;
                break;
            }
        }
    }
    return is_tax_inclusive;
}
function get_deleted_taxes() {
    var tem_deleted_taxes = JSON.parse(localStorage.getItem("deleted_taxes"))
    deleted_taxes = tem_deleted_taxes ? tem_deleted_taxes : []
    return deleted_taxes;
}
async function delete_item(line) {
    var items = get_cart();
    item_id = get_item_id(line);
    var info_item = await objItem.get_info(item_id);
    if (Boolean(Number(await objGifcard.get_giftcard_id(info_item.description)))) {
        await objGifcard.delete_completely(await info_item.description);
    }
    delete items[line];
    set_cart(items);
   await armar_vista();


}

function get_item_id(line_to_get) {
    items = get_cart();

    //for (i in items) {
        var item = items[line_to_get];
        //if (item.line == line_to_get) {
            return item.item_id != undefined ? item.item_id : -1;
        //}
    //}

    //return -1;
}

async function _add_payment(post) {
    let data = Array();
    //$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');
    if (!$.isNumeric(post["amount_tendered"])) {
        toastr.error(lang('sales_must_enter_numeric'));

        let sales_giftcard = lang('sales_giftcard');
        if (post['payment_type'] == sales_giftcard)
            toastr.error(lang('sales_must_enter_numeric_giftcard'));
        else
            toastr.error(lang('sales_must_enter_numeric'));
        return 0;
    }

    let sales_store_account = lang('sales_store_account');
    let customer = await get_customer();
    if ((post['payment_type'] == sales_store_account && customer == -1) ||
        (get_mode() == 'store_account_payment' && customer == -1)) {
        toastr.error(lang('sales_customer_required_store_account'));
        return 0;
    }

    let select_sales_person_during_sale = await objAppconfig.item('select_sales_person_during_sale');
    if (select_sales_person_during_sale == true && !await get_sold_by_employee_id()) {
        toastr.error(lang('sales_must_select_sales_person'));
        return 0;
    }


    let payment_type = post['payment_type'];
    let payment_amount = 0;
    let cur_giftcard_value = 0;
    let current_payments_with_giftcard = 0;
    let total = await get_total();


    if (payment_type == lang('sales_giftcard')) {
        if (!objGifcard.exists(await objGiftcard.get_giftcard_id(post['amount_tendered']))) {
            toastr.error(lang('sales_giftcard_does_not_exist'));

            return 0;
        }

        payment_type = post['payment_type'] + ':' + post['amount_tendered'];
        current_payments_with_giftcard = get_payment_amount(payment_type);
        cur_giftcard_value = await objGiftcard.get_giftcard_value(post['amount_tendered']) - current_payments_with_giftcard;
        if (cur_giftcard_value <= 0 && total > 0) {
            let tem = await Giftcard.get_giftcard_value(post['amount_tendered'])
            toastr.error(lang('sales_giftcard_balance_is') + ' ' + to_currency(tem) + ' !');
            armar_vista();
            return 0;
        }
        else if ((await objGiftcard.get_giftcard_value(post['amount_tendered']) - total) > 0) {
            toastr.error(lang('sales_giftcard_balance_is') + ' ' + to_currency(await objGiftcard.get_giftcard_value(post('amount_tendered')) - total) + ' !');
        }
        payment_amount = Math.min(await get_amount_due(), await objGiftcard.get_giftcard_value(post('amount_tendered')));
    }
    else {
        payment_amount = post['amount_tendered'];
    }
    if (!add_payment(payment_type, payment_amount)) {
        toastr.error(lang('sales_unable_to_add_payment'));
    }
    let data_ = await _reload();
    await add_resumen_venta(data_);;
}

function add_payment(payment_type, payment_amount, payment_date = false, truncated_card = '', card_issuer = '') {
    let payments = get_payments();
    let payment = {};
    payment['payment_type'] = payment_type;
    payment['payment_amount'] = payment_amount;
    payment['payment_date'] = payment_date != false ? payment_date : date();
    payment['truncated_card'] = truncated_card;
    payment['card_issuer'] = card_issuer;

    payments.push(payment);
    set_payments(payments);
    return true;
}
function delete_payment(payment_ids) {
    let payments = get_payments();
    if (Array.isArray(payment_ids)) {
        let payment_id;
        for (let i in payment_ids) {
            payment_id = payment_ids[i];
            delete payments[payment_id];
        }
    }
    else {
        delete payments[payment_ids];
    }
    set_payments(array_values(payments));
}
function get_point(value_point,total)

{
    let value_purchase =total;
    let point_pucharse=0;

    if(value_purchase > value_point && value_point!=0)
    {
        point_pucharse = Math.floor(value_purchase/value_point);
    }
    else
    {
        point_pucharse = 0;
    }
    return point_pucharse;
}
function date() {
    now = new Date();
    year = "" + now.getFullYear();
    month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
    day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
    hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
    minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
    second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
    return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
}
function array_values(array) {

    let tmpArr = []
    let key = ''
    for (let key in array) {
        tmpArr[tmpArr.length] = array[key]
    }
    return tmpArr
}
async function editar_producto(line, item_id = false, input) {
    let data = Array();
    input = input[0];
     
    let description = input.name == "description" ? input.value : false;
    let serialnumber = input.name == "serialnumber" ? input.value : false;
    let price = input.name == "price" ? input.value : false;
    let quantity = input.name == "quantity" ? Number(input.value) : false;
    let custom1_subcategory = input.name == "custom1_subcategory" ? input.value : false;
    let custom2_subcategory = input.name == "custom2_subcategory" ? input.value : false;
    let discount = input.name == "discount" ? input.value : false;
    let promo_quantity = item_id != undefined && item_id != false ? await objSale.get_promo_quantity(item_id) : 0;
    let is_item_promo = item_id != undefined && item_id != false ? await objSale.is_item_promo(item_id) : 0;
    let error=false;
    if((input.name == "price" && !$.isNumeric( price))  ||
    (input.name == "quantity" && !$.isNumeric( quantity)) ){
        error=true; 
    }
    //check if theres enough promo items for a sale.
    if ((promo_quantity < quantity) && is_item_promo) {
        toastr.error(lang('sales_promo_quantity_less_than_zero'));
    }
    if (discount != false && !$.isNumeric( discount)) {
        discount = 0;
    }
    if (!error) {
        edit_item(line, description, serialnumber, quantity, discount, price, custom1_subcategory, custom2_subcategory);
        //$this->sale_lib->set_sale_id($this->input->post('id_credito_2'));

    } else {
        toastr.error(lang('sales_error_editing_item'));

    }
    if (await is_kit_or_item(line) == 'item') {
        let out_of_stock_ = await out_of_stock(get_item_id(line));
        if (out_of_stock_ && await objAppconfig.item('sales_stock_inventory') == 1) {
            await delete_item_stock(line);
            toastr.error(lang('sales_quantity_stock_less_than_zero'));
        }
        else if (out_of_stock_) {
            toastr.warning(lang('sales_quantity_less_than_zero'));
        }
    }
    else if (await is_kit_or_item(line) == 'kit') {
        let out_of_stock_ = await out_of_stock_kit(get_kit_id(line));
        if (out_of_stock_ && await objAppconfig.item('sales_stock_inventory') == 1) {
            await delete_item_stock(line);
            toastr.error(lang('sales_quantity_stock_less_than_zero'));
        }
        else if (out_of_stock_) {
            toastr.warning(lang('sales_quantity_less_than_zero'));
        }
    }
    if (await objAppconfig.item('subcategory_of_items') == 1) {
        var items = {};
        var items_ = get_cart();
        for (let l in items_) {
            let item = items_[l];
            let line = existe(items, item);
            if (line == -1) {
                items[item["line"]] = item;
            } else {
                items[line]['quantity'] += Number(item['quantity']);
                toastr.warning("Producto(s) funcionados verifique el precio  o descuento.");
            }
        }

        set_cart(items);
        //	se elemina los item de  con stock bajo en las subcategory
        items = {};
        items_ = get_cart();
        for (let l in items_) {
            let item = items_[l];
            if (is_kit_or_item(item["line"]) == 'item') {
                let sales_stock_inventory_ = await objAppconfig.item('sales_stock_inventory');
                let out_of_stock_subcategory_ = await out_of_stock_subcategory(item["item_id"], item["custom1_subcategory"], item["custom2_subcategory"], item["quantity"]);
                if (out_of_stock_subcategory_ && sales_stock_inventory_ == 1 && item["custom1_subcategory"] != "" && item["custom1_subcategory"] != null && item["custom2_subcategory"] != "" && item["custom2_subcategory"] != null) {
                    toastr.error(lang('sales_quantity_stock_less_than_zero') + "(Subcategoría)");
                } else {
                    items[item["line"]] = item;
                }
                if (sales_stock_inventory_ == 0 && out_of_stock_subcategory_ && item["custom1_subcategory"] != "" && item["custom1_subcategory"] != null && item["custom2_subcategory"] != "" && item["custom2_subcategory"] != null) {
                    toastr.warning(lang('sales_quantity_less_than_zero') + "(Subcategoría)");
                }
            } else {
                items[item["line"]] = item;
            }
        }
        set_cart(items);


    }
    await armar_vista();

}
async function get_detailed_taxes(sale_id = false)
{        
    let taxes = Array();

   /* if (sale_id) 
    {
        
    }
    else
    { */           
        let customer_id = await get_customer();
        let customer = await objCustomer.get_info(customer_id);

        //Do not charge sales tax if we have a customer that is not taxable
        if (customer.taxable==0 && customer_id!=-1)
        {
            return Array();
        }
        let items = get_cart();
        for(line in items)
        {
            let item= items[line];
            let price_to_use =await _get_price_for_item_in_cart(item);      
            
            let tax_info = item.item_id!=undefined ? await objItem_taxes_finder.get_info(item.item_id) : await objItem_kit_taxes_finder.get_info(item.item_kit_id);
            for(key in tax_info)          
            {   
                let tax=   tax_info[key];         
                let name = tax.percent+'% ' + tax.name;
                let tax_base  = (price_to_use*item.quantity-price_to_use*item.quantity*item.discount/100);
                let tax_amount= (price_to_use*item.quantity-price_to_use*item.quantity*item.discount/100)*((tax.percent)/100);
                let tax_total  = tax_base + tax_amount;                
                let query = $.inArray(name, get_deleted_taxes());
                if (query == -1)              
                {
                    if (taxes[name]==undefined)
                    {
                        let tem=Array();
                        tem["base"]=0;
                        tem["total_tax"] = 0;
                        tem["total"] = 0;
                        taxes[name] = tem;
                       
                    }                    
                    taxes[name]['base'] += tax_base;
                    taxes[name]['total_tax'] += tax_amount;
                    taxes[name]['total'] += tax_total;
                }
            }
        }
        //}
    
    return taxes;
}
async function get_detailed_taxes_total(sale_id = false)
{
    let taxes = Array();

    /*if ($sale_id) 
    {     
            
    }
    else
    { */           
        let total_base_sum = 0;
        let total_tax_sum  = 0;
        let total_sum = 0;

        let customer_id = await get_customer();
        let customer = await objCustomer.get_info(customer_id);

        //Do not charge sales tax if we have a customer that is not taxable
        if (customer.taxable==0 && customer_id!=-1)
        {
            return Array();
        }

        let items = get_cart();
        for(line in items)
        {
            let item= items[line];
            let price_to_use =await _get_price_for_item_in_cart(item);      
             
            let tax_info = item.item_id!=undefined ?await objItem_taxes_finder.get_info(item.item_id) : await objItem_kit_taxes_finder.get_info(item.item_kit_id);
            for(key in tax_info)          
            {   
                let tax=   tax_info[key];  
                let name = tax.percent+'% ' + tax.name;

                let tax_base  = (price_to_use*item.quantity-price_to_use*item.quantity*item.discount/100);
                let tax_amount= (price_to_use*item.quantity-price_to_use*item.quantity*item.discount/100)*((tax.percent)/100);
                let tax_total  =tax_base + tax_amount;
                let query = $.inArray(name, get_deleted_taxes());
                if (query == -1)              
                {                
                    if (taxes[name]==undefined)
                    {
                        let tem=Array();
                        tem["base"]=0;
                        tem["total_tax"] = 0;
                        tem["total"] = 0;
                        taxes[name] = tem;
                     
                    }
                    
                    total_base_sum = tax_base + total_base_sum;
                    total_tax_sum = tax_amount + total_tax_sum;
                    total_sum = tax_total + total_sum;
                }
            }
        }

        taxes['total_base_sum'] = total_base_sum;
        taxes['total_tax_sum'] = total_tax_sum;
        taxes['total_sum'] = total_sum;		    	    
   // }
    return taxes;
}
function existe(items, item_tem) {
    if (item_tem['item_id'] != undefined) {
        for (line in items) {
            let item = items[line];
            if (item['item_id'] != undefined) {
                if (item['item_id'] == item_tem['item_id'] && item['custom1_subcategory'] == item_tem['custom1_subcategory'] &&
                    item['custom2_subcategory'] == item_tem['custom2_subcategory']) {
                    return item['line'];
                }
            }
        }
    }
    return -1;
}
async function delete_item_stock(item_number) {
    await delete_item(item_number);
}


/*function get_item_id(line_to_get) {
    let items = get_cart();
    let item = null;
    for (let line in items) {
        let item = items[line];
        if (line == line_to_get) {
            return item['item_id'] != undefined ? item['item_id'] : -1;
        }
    }
    return -1;
}*/
function get_kit_id(line_to_get) {
    let items = get_cart();
    let item = null;
    for (let line in items) {
        item = items[line];
        if (line == line_to_get) {
            return item['item_kit_id'] != undefined ? item['item_kit_id'] : -1;
        }
    }
    return -1;
}
function edit_item(line, description = false, serialnumber = false, quantity = false, discount = false, price = false, custom1_subcategory = false, custom2_subcategory = false) {
    let items = get_cart();
    if (items[line] != undefined) {
        if (description !== false) {
            items[line]['description'] = description;
        }
        if (serialnumber !== false) {
            items[line]['serialnumber'] = serialnumber;
        }
        if (quantity !== false) {
            items[line]['quantity'] = quantity;
        }
        if (discount !== false) {
            items[line]['discount'] = discount;
        }
        if (price !== false) {
            items[line]['price'] = price;
        }
        if (custom1_subcategory !== false) {
            items[line]['custom1_subcategory'] = custom1_subcategory;
            items[line]['custom2_subcategory'] = "";
        }
        if (custom2_subcategory !== false) {
            items[line]['custom2_subcategory'] = custom2_subcategory;
        }

        set_cart(items);

        return true;
    }

    return false;
}
function is_kit_or_item(line_to_get) {
    let items = get_cart();
    let item = null;
   // for (let line in items) {
       // item = items[line];
        item = items[line_to_get];
        //if (line == line_to_get) {
        //if (line == line_to_get) {
            if (item['item_id'] != undefined) {
                return 'item';
            }
            else if (item['item_kit_id']) {
                return 'kit';
            }
       // }
    //}
    return -1;
}
async function out_of_stock_subcategory(item_id, custom1, custom2, quantity_added) {
    if (await objItem.existe(item_id) == false) {
         item_id = await objItem.get_item_id(item_id);
        if (!item_id) {
            return false;
        }
    }
    let item_location_quantity_subcategory = await objItems_subcategory.get_quantity(item_id, false, custom1, custom2);
    if (item_location_quantity_subcategory != null && item_location_quantity_subcategory - quantity_added < 0) {
        return true;
    }
    return false;
}
function clear_mode() {
    localStorage.removeItem("sale_mode");
}
function empty_cart() {
    localStorage.removeItem("cart");
}
function clear_comment() {
    localStorage.removeItem("comment");

}
function clear_sale_id() {
    localStorage.removeItem("sale_id");
}
function clear_ntable() {
    localStorage.removeItem("ntable");
}

function clear_show_comment_on_receipt() {
    localStorage.removeItem("show_comment_on_receipt");
}
function clear_show_receipt() {
    localStorage.removeItem("show_receipt");
}
function clear_show_comment_ticket() {
    localStorage.removeItem("show_comment_ticket");
}

function clear_email_receipt() {
    localStorage.removeItem("email_receipt");
}
function clear_change_sale_date_enable() {
    localStorage.removeItem("change_sale_date_enable");
}
function empty_payments() {
    localStorage.removeItem("payments");
}
async function delete_customer(change_price_ = true) {
    localStorage.removeItem("customer");
    if (change_price_ == true) {
        await change_price();
    }
}
function clear_change_sale_date() 	
	{
        localStorage.removeItem("change_sale_date");		
	}
function delete_suspended_sale_id() {
    localStorage.removeItem("suspended_sale_id");
}
function delete_change_sale_id() {
    localStorage.removeItem("change_sale_id");
}
function delete_partial_transactions() {
    localStorage.removeItem("partial_transactions");
}
function clear_save_credit_card_info() {
    localStorage.removeItem("save_credit_card_info");
}
function clear_use_saved_cc_info() {
    localStorage.removeItem("use_saved_cc_info");
}
function clear_selected_tier_id() {
    localStorage.removeItem("previous_tier_id");
    localStorage.removeItem("selected_tier_id");
}
function clear_cc_info() {
    localStorage.removeItem("ref_no");
    localStorage.removeItem("auth_code");
    localStorage.removeItem("masked_account");
    localStorage.removeItem("card_issuer");
}
function clear_sold_by_employee_id() {
    localStorage.removeItem("sold_by_employee_id");
}
function set_sale_id(sale_id) {
    localStorage.setItem('sale_id', sale_id);
}
function clear_all() {
    clear_mode();
    empty_cart();
    clear_comment();
    clear_ntable();
    clear_show_comment_on_receipt();
    clear_show_receipt();
    clear_show_comment_ticket();
    clear_change_sale_date();
    clear_change_sale_date_enable();
    clear_email_receipt();
    empty_payments();
    delete_customer(false);
    delete_suspended_sale_id();
    delete_change_sale_id();
    delete_partial_transactions();
    clear_save_credit_card_info();
    clear_use_saved_cc_info();
    clear_selected_tier_id();
    clear_deleted_taxes();
    clear_cc_info();
    clear_sold_by_employee_id();
    set_sale_id("-");
    objAppconfig.item('default_sales_type') ?  localStorage.getItem('show_comment_ticket',1)  : localStorage.getItem('show_comment_ticket',0);

}
function _does_discount_exists(cart){
	for(line in cart){
        let item = cart[line];		
        if( (item.discount !=undefined && item.discount>0 ) || 
        (item.discount_percent!=undefined  && item.discount_percent>0 ) )
	    {
			return true;
		}
	}
	return false;
}
function discount_all(percent_discount)
{
    let items = get_cart();
    
    for (key  in items)
    {
        items[key]['discount'] = percent_discount;
    }
    set_cart(items);
    return true;
}
function set_comment(comment) 
	{
        localStorage.setItem("comment",comment);
    }
    function set_comment_on_receipt(comment_on_receipt) 
	{
        localStorage.setItem("show_comment_on_receipt",comment_on_receipt);
    }
    function set_show_receipt(show_receipt)
	{
        localStorage.setItem('show_receipt', show_receipt);
    }
    function set_comment_ticket(comment_ticket) 
	{
        localStorage.setItem('show_comment_ticket', comment_ticket);
    }
    