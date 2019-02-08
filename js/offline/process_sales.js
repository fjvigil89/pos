async function complete_sale(){
    let data={};
    data['is_sale'] = true;
	data['cart'] = get_cart();
    let suspended_change_sale_id =false;// $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id();
	let location_id = await objEmployee.get_logged_in_employee_current_location_id();
	await cargar_lang(); 
    let invoice_type=Array();
    if(suspended_change_sale_id)
        {
           
        }
        else
        {
            
            if(get_comment_ticket() == 1)
            {
                invoice_type['invoice_number'] = null;
                invoice_type['ticket_number']  = await objSale.get_next_sale_number(0,location_id);
                invoice_type['is_invoice']     = 0;
                data['sale_number']            = invoice_type['ticket_number'];
            }
            else
            {
                invoice_type['invoice_number'] = await objSale.get_next_sale_number(1,location_id);
                invoice_type['ticket_number']  = null;
                invoice_type['is_invoice']     = 1;
                data['sale_number']            = invoice_type['invoice_number'];
            }
        }
        data['sale_type'] = (get_comment_ticket() == 1) ? lang('sales_ticket_on_receipt') : lang('sales_invoice');

        //Si existe descuento, disminuye la cantidad de productos en descuento (phppos_items -> quantity)
        for (let i in data['cart'] ){
			let item = data['cart'][i];						
            if(item["item_id"]!=undefined){
            	if(await objSale.is_item_promo(item["item_id"])){
					data['cart'][i]["is_promo"]=1;
					await objSale.drecrease_promo_quantity(cart["quantity"],cart["item_id"]);                
				}
            } 
        }
        if (data['cart'].length<=0)
		{
            window.location= window.location;			
        }
        if (!await _payments_cover_total())
		{
            toastr.error(lang('sales_cannot_complete_sale_as_payments_do_not_cover_total'));
            window.location= window.location;
			return 0;
        }        
		if (await objAppconfig.item('track_cash') == 1) {
			let register_log_id = await objSale.get_current_register_log();
			if (!register_log_id) {
                toastr.error("¡La caja donde se quiere efectuar la operación esta cerrada!");
				return 0;
            } 
            
        }
        //sales_store_account
		let tier_id = get_selected_tier_id();
		let tier_info =await objTier.get_info(tier_id);
        data['tier'] = tier_info.name;
		data['register_name'] = await objRegister.get_register_name(objEmployee.get_logged_in_employee_current_register_id());
		data['subtotal']=await get_subtotal();
		data['taxes']=Array();
	    data['detailed_taxes']=await get_detailed_taxes();
		data['detailed_taxes_total']=await get_detailed_taxes_total();
		data['total']=await get_total();
		data['receipt_title']=lang('sales_receipt');
		let customer_id=await get_customer();
		let employee_id=await objEmployee.get_logged_in_employee_info();
		 employee_id=employee_id.person_id;
		let sold_by_employee_id=await get_sold_by_employee_id();
		data['comment'] = get_comment();
		data['show_comment_on_receipt'] = await objAppconfig.item('activar_casa_cambio')==1 ? true : get_comment_on_receipt();
		data['show_comment_ticket'] = get_comment_ticket();

		let emp_info=await objEmployee.get_info(employee_id);
		let sale_emp_info=await objEmployee.get_info(sold_by_employee_id);
		data['payments']=get_payments();
		data['is_sale_cash_payment'] = await is_sale_cash_payment();
		data['amount_change']=await get_amount_due() * -1;
		data['balance']=get_payment_amount(lang('sales_store_account'));
		data['employee']=emp_info.first_name+' '+emp_info.last_name+(sold_by_employee_id && sold_by_employee_id != employee_id ? '/'+ sale_emp_info.first_name+' '+sale_emp_info.last_name: '');
		data['ref_no'] =localStorage.getItem("ref_no")!=null ?localStorage.getItem("ref_no") : '';
		data['auth_code'] = localStorage.getItem("auth_code")!=null ? localStorage.getItem("auth_code") : '';
		data['discount_exists'] = _does_discount_exists(data['cart']);
		let masked_account =localStorage.getItem("masked_account")!=null ? localStorage.getItem("masked_account"): '';
		let card_issuer = localStorage.getItem("card_issuer")!=null  ? localStorage.getItem("card_issuer") : '';
		data['mode']= get_mode();
		//activar_casa_cambio= await objAppconfig.item('activar_casa_cambio');
		data['opcion_sale']= null;
		data['divisa']=null;
		data["total_divisa"]=null;
		data["transaction_rate"]= null;
		data["show_number_item"]=await objAppconfig.item('show_number_item');
		data["transaction_cost"]= 0;
		data["store_account_transaction"]={};

		data["id_synchronization"] = await get_data_por_key("id_synchronization");
        if(await objAppconfig.item('system_point') ==1 && get_mode() == 'sale')
		{
			let total=data['total'];
			let point_pucharse = get_point(await objAppconfig.item('value_point'),total);
			let detail = 'Id venta #';
			if(customer_id!=-1)		{	
				await   objPoints.save_point(point_pucharse,customer_id,detail);
			}
		    data['points']=customer_id!=-1? await objCustomer.get_info_points(customer_id):0;
        }
       
        data['change_sale_date'] = false;
        let now= moment().format("YYYY-MM-DD HH:mm:ss");
		//let old_date=  now;
		data['transaction_time']= now;
		let cust_info=await objCustomer.get_info(customer_id);
        if(customer_id!=-1)
		{			
			data['customer']=cust_info.first_name+' '+cust_info.last_name+(cust_info.company_name==''  ? '' :' - '+cust_info.company_name)+(cust_info.account_number==''  ? '' :' - '+cust_info.account_number);
			data['customer_address_1'] = cust_info.address_1;
		    data['customer_address_2'] = cust_info.address_2;
			data['customer_city'] = cust_info.city;
			data['customer_state'] = cust_info.state;
			data['customer_zip'] = cust_info.zip;
			data['customer_country'] = cust_info.country;
			data['customer_phone'] = cust_info.phone_number;
			data['customer_email'] = cust_info.email;
        }
        let sale_id_raw =0;
		data['change_sale_date'] = now;		
		data['store_account_payment'] = get_mode() == 'store_account_payment' ? 1 : 0;
		//SAVE sale to database 
		if(get_mode() == 'sale' && customer_id>0 && data['balance']!=undefined && data['balance']!=0 )
		{
			add_customer=await objSale.add_petty_cash_customer(customer_id, data['balance']);
			if(add_customer===false){
				alert("Ocurrió un error al procesar el pago.");
				location.reload(true);
				return 0;
			}else{
				data["store_account_transaction"]= add_customer;
			}

        }
        if(data['store_account_payment']==1  && data['balance']!=undefined && customer_id)
		{		
            sale_id_raw =0;// $this->Sale->save_petty_cash($data['cart'], $customer_id, $employee_id, $sold_by_employee_id, $data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,$data['ref_no'],$data['auth_code'], $data['change_sale_date'], $data['balance'], $data['store_account_payment'],$data['total'],$data['amount_change'],-1);			
			if(sale_id_raw<0){
                $.confirm({
                    title: 'Error!',
                    content: 'Ocurrió un error al procesar el pago',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Ok',
                            btnClass: 'btn-red',
                            action: function(){
                                location.reload(true);
                            }
                        }
                    }
                });
				return 0;
			}	  
        }
        else
		{
			let mode=get_mode() ;
			let tier_id = get_selected_tier_id();
			let deleted_taxes=get_deleted_taxes();
			if(! await is_select_subcategory_items()){
				sale_id_raw=-1;
			}
			else{
				let clon_data =JSON.parse(JSON.stringify( Object.assign({}, data)));
				sale_id_raw = await objSale.save_sale(clon_data["cart"], customer_id, employee_id, sold_by_employee_id, 
				clon_data['comment'],clon_data['show_comment_on_receipt'],clon_data['payments'], suspended_change_sale_id, 0,
				clon_data['ref_no'],clon_data['auth_code'], clon_data['change_sale_date'], clon_data['balance'], mode,tier_id,
				deleted_taxes,clon_data['store_account_payment'],clon_data['total'],clon_data['amount_change'],invoice_type, 
				null,clon_data["divisa"],clon_data["opcion_sale"],clon_data["transaction_rate"],clon_data["transaction_cost"], clon_data["store_account_transaction"],clon_data["id_synchronization"],clon_data);
				if(sale_id_raw==-1){
					$.confirm({
						title: 'Error!',
						content: 'Ocurrió un error al procesar la venta',
						type: 'red',
						typeAnimated: true,
						buttons: {
							tryAgain: {
								text: 'Ok',
								btnClass: 'btn-red',
								action: function(){
									location.reload(true);
								}
							}
						}
					});
					return 0;
				}
			}

		}
		if(data['store_account_payment']==1)
		{
			data['sale_id']=data["id_synchronization"]+"."+sale_id_raw;
		}
		else
		{
		   data['sale_id']=data["id_synchronization"]+"."+sale_id_raw;;//await objAppconfig.item('sale_prefix')+' '+sale_id_raw;
		}
		data['sale_id_raw']=data["id_synchronization"]+"."+sale_id_raw;
 
		if(customer_id != -1)
		{
			
			cust_info=await objCustomer.get_info(customer_id);
			if (cust_info.balance !=0)
			{
				data['customer_balance_for_sale'] = cust_info.balance;
			}
		}
		if (data['taxes'].length==0)
		{
			for(key in data['cart']){
			
				if (data['cart'][key]['item_id']!=undefined)
				{
					//$item_info = $this->Item->get_info($data['cart'][$key]['item_id']);
					if(data['cart'][key]["tax_included"]==1)
					{
						let price_to_use = await objItems_helper.get_price_for_item_excluding_taxes(data['cart'][key]['item_id'], data['cart'][key]['price']);
						data['cart'][key]['price'] = price_to_use;
					}
				}
				else if (data['cart'][key]['item_kit_id']!=undefined)
				{
					//$item_info = $this->Item_kit->get_info($data['cart'][$key]['item_kit_id']);
					if(data['cart'][key]["tax_included"]==1)
					{
						let price_to_use = await objitem_kits_helper.get_price_for_item_kit_excluding_taxes(data['cart'][key]['item_kit_id'], data['cart'][key]['price']);
						data['cart'][key]['price'] = price_to_use;
					}
				}

			}

		}
		
		if(sale_id_raw>0){
			await objSale.actualizar_dato_imprimir(sale_id_raw,data);
		}
        if( get_show_receipt() )
        {
            clear_all();
			location.reload(true);
			return 0;
        }
        else
        {
			localStorage.setItem("data_imprimir", sale_id_raw);
			clear_all();
			window.location= window.location=BASE_URL+'index.php/sales/imprimir';
            //se imprime la factura
		}
      
    //alert("Venta completda...");
    clear_all();
    


}


