class Sale {
    constructor() {

    }
    async  update_register_log(data,_register_id=false)
    {
        delete data["register_log_id"];
        data["synchronized"]=0;
         let register_id = _register_id != false ? String(_register_id): await objEmployee.get_logged_in_employee_current_register_id();
        let query = await db1.register_log.where({ "shift_end": '0000-00-00 00:00:00', "register_id":register_id }).modify(data);
         return query>0 ? true : false;
    }
    async  update_register_by_id(data,register_log_id)
    {
        delete data["register_log_id"];       
        let query = await db1.register_log.where({ "register_log_id": register_log_id }).modify(data);
         return query>0 ? true : false;
    }
    async get_register_log(){
        const result = await db1.register_log.toArray();
        return result;
    }
    async get_register_log_by_id(register_log_id){
        const result = await db1.register_log.where({ "register_log_id": register_log_id }).first();
        return result;
    }
    async get_register_log_open_offline(){
        let data={"synchronized":0,"created_online":0,"shift_end": '0000-00-00 00:00:00'}        
        const result = db1.register_log.where(data).toArray();
        return result;
    }
    async get_register_log_no_synchronized(){
        let data={synchronized:0}
        const result = await db1.register_log.where(data).toArray();
        return result;
    }
    
    async  insert_register(data){
        const result = await db1.register_log.where(
            {
                register_id: ""+data.register_id,
                shift_end: '0000-00-00 00:00:00',
            }
        ).first();
        if(result==undefined){
            data["created_online"]=0; 
            data["synchronized"]=0;
            data["closed_manual"]=0;
            data["id_synchronization"]= await get_data_por_key("id_synchronization");
            data["token"]= await get_data_por_key("token")
            let result= await db1.register_log.add(data);
         return result?result:false;
        }else{
            return true;
        }       
    }
    async actualizar_estado(sale_id, mensaje, estado) {
        let data={
            mensaje:mensaje,
            synchronized: estado
        };
        await db1.sales_offline.where({ sale_id: Number(sale_id)}).modify(data);

    }
    async get_info_offline(sale_id) {
        const sales = await db1.sales_offline.get(sale_id);
        return sales;
    }
    async get_sales_all(location_id = false) {
        if (location == false) {
            location = await objEmployee.get_logged_in_employee_current_location_id();
        }
        const sales = await db1.sales_offline.where({ location_id: location_id }).toArray();
        return sales;

    }
    async get_sales_no_synchronized() {
        let data={synchronized:0, deleted:0,}
        data["store"]= "store_"+await get_data_por_key("store");
        const sales = await db1.sales_offline.where(data).toArray();
        return sales;

    }
    async actualizar_dato_imprimir(sale_id, data) {
        await db1.sales_offline.where({ sale_id: sale_id }).modify({ dato_imprimir: data });

    }
    async eliminar_sale(sale_id){
        if( ! await objEmployee.has_module_action_permission('offline', 'delete_sale_offline',
         (await objEmployee.get_logged_in_employee_info()).person_id)){
            return false;
        }
        let data_sale =(await this.get_info_offline(Number(sale_id)));
        let items=  data_sale.sales_items;
        let cur_item_info=null;
        let cur_item_location_info=null;
        let item =null;
        for(let i in items){
            item = items[i];
            cur_item_info = await objItem.get_info(item['item_id']);
            cur_item_location_info = await objItem_location.get_info(item['item_id']);

            if (cur_item_info.is_service == false) {
                cur_item_location_info.quantity = (cur_item_location_info.quantity !== null ? cur_item_location_info.quantity : 0);

                if (!await objItem_location.save_quantity(cur_item_location_info.quantity + item['quantity_purchased'], item['item_id'])) {
                    return false;
                }
                if (await objAppconfig.item('subcategory_of_items') == 1 && cur_item_info.subcategory == 1) {
                    let subcategory = await objItems_subcategory.get_info(item['item_id'], false, item['custom1_subcategory'], item['custom2_subcategory']);
                    let quantity_subcategory = subcategory.quantity;
                    if (! await objItems_subcategory.save_quantity((quantity_subcategory + item['quantity_purchased']),
                        item['item_id'], false, item['custom1_subcategory']!=null?item['custom1_subcategory']:"", item['custom2_subcategory']!=null?item['custom2_subcategory']:"")) {
                        return false;
                    }
                }
                if (cur_item_info['is_serialized'] == 1) {

                    /*if (!await objAdditional_item_seriales -> add(item['item_id'], item["serialnumber"])) {
                        
                        return -1;
                    }*/

                }
            }
        }
        
        let items_kit_sale= data_sale.sales_item_kits;
        for(let i in items_kit_sale){
            let item_kit_sale = items_kit_sale[i];
            let kits_tem = await objItem_kit_item.get_info(item_kit_sale['item_kit_id']);
            for (let key in kits_tem) {
                var item_kit_item = kits_tem[key];
                cur_item_info = await objItem.get_info(item_kit_item.item_id);
                cur_item_location_info = await objItem_location.get_info(item_kit_item.item_id);            
                if (cur_item_info.is_service == false) {
                    cur_item_location_info.quantity = cur_item_location_info.quantity !== null ? cur_item_location_info.quantity : 0;
                    if (!await objItem_location.save_quantity(cur_item_location_info.quantity + (item_kit_sale['quantity_purchased'] * item_kit_item.quantity), item_kit_item.item_id)) {
                        return false;
                    }
                }
            }
        }
        if(data_sale.customer_id!=null && data_sale.customer_id>0){
            let balance =this.get_payment_credit(data_sale.sales_payments);
            if(balance!=0){
            await  this.add_petty_cash_customer(data_sale.customer_id, -balance) ;
            }
        }
        let cash =this.get_payment_cash(data_sale.sales_payments);
        await objRegister_movement.save(-cash, "Venta eliminada",false,true,"Venta",false); //Registrar movimiento
        await db1.sales_offline.where({"sale_id":Number(data_sale.sale_id)}).delete();       
    }
    async save_sale(items, customer_id, employee_id, sold_by_employee_id, comment,
        show_comment_on_receipt, payments, sale_id = false, suspended = 0, cc_ref_no = '',
        auth_code = '', change_sale_date = false, balance = 0, mode = "sale", tier_id = false,
        deleted_taxes = array(), store_account_payment = 0,
        total = 0, amount_change, invoice_type, ntbale = null, divisa = null,
        opcion_sale = null, transaction_rate = null, transaction_cost = null,store_account_transaction={},id_synchronization, all_data = {}) 
        {
        if (items.length == 0) {
            return -1;
        }
        let customer = await objCustomer.get_info(customer_id);
        //---------variables para db---------------
        let sales_data;
        let payments_data = {};// guarda todo los pagos de la venta 
        let items_data = {};
        let item_kits_data = {};
        let items_taxes = {};
        let item_kits_taxes = {};
        let inventario_data={};
        let movement_register={};
        

        let location_id_current = await objEmployee.get_logged_in_employee_current_location_id()
        // let sale_remarks = $this -> config -> item('sale_prefix'). ' '.$sale_id;
        //-----------------------------------------

        let payment_types = '';
        for (let payment_id in payments) {
            let payment = payments[payment_id];
            payment_types = payment_types + payment['payment_type'] + ': ' + (await to_currency(payment['payment_amount'])) + '<br/>';

        }
        if (tier_id == 0) {
            tier_id = null;
        }
        sales_data = {
            'customer_id': customer_id > 0 ? customer_id : null,
            'employee_id': employee_id,
            'sold_by_employee_id': sold_by_employee_id,
            'payment_type': payment_types,
            'comment': comment,
            'show_comment_on_receipt': show_comment_on_receipt ? show_comment_on_receipt : 0,
            'suspended': suspended,
            'deleted': 0,
            'deleted_by': null,
            'register_id': await objEmployee.get_logged_in_employee_current_register_id(),
            'cc_ref_no': cc_ref_no,
            'auth_code': auth_code,
            'store_account_payment': store_account_payment,
            'tier_id': tier_id ? tier_id : null,
            'invoice_number': invoice_type['invoice_number'],
            'ticket_number': invoice_type['ticket_number'],
            'is_invoice': invoice_type['is_invoice'],
            'ntable': ntbale,
            "divisa": divisa,
            "opcion_sale": opcion_sale,
            "transaction_rate": transaction_rate, // tasa puesta por la casa de cambio 
            "synchronized": 0,
            "balance":balance,
            "mode":mode,
            "total":total

        };
        sales_data['sale_time'] = moment().format("YYYY-MM-DD HH:mm:ss");
        sales_data['location_id'] = Number(await objEmployee.get_logged_in_employee_current_location_id());

        if (await objAppconfig.item('track_cash') == 1 ) {

            var cash = this.get_payment_cash(payments); //Obtener pagos en efectivo de la venta/devolucion actual

            if (mode== 'sale') {
                var description = "Venta";
                var categorias_gastos="Venta";
            } else if (mode == 'return') {
                var description = "Devolución";
                var categorias_gastos="Devolución";
                 cash = cash * (-1);
            }
            /*if ($sale_id) { //Si la operacion es editar o una devolucion
                $cash = $this->get_diff_sale_cash($sale_id, $cash);
            }*/

            if (amount_change > 0) {
                cash = cash - amount_change;
            }
            
			if (cash != 0 ) {
                let register = await objSale.get_current_register_log(sales_data["register_id"]);

                // estos son los datos q se registran en movimiento de caja 				
                movement_register[0] = {
					'register_log_id' : register.created_online==0 ? register.register_log_id : register.register_log_id_origen,
					'register_date'   :  moment().format("YYYY-MM-DD HH:mm:ss"),
					'mount'           :cash,
					'description'     :description,
					'type_movement'   : null,
                    'mount_cash'      : null, 
                    "id_synchronization": register.id_synchronization,
					"categorias_gastos": categorias_gastos,
                    "id_employee": sales_data["employee_id"],
                    "register_id":sales_data["register_id"],
                    "register_log_created_online":register.created_online,

                };
            }
            // se registran los movimiento de la caja
            await objRegister_movement.save(cash, description,false,true,categorias_gastos,false); //Registrar movimiento
        }

        let total_giftcard_payments = 0;
        let amount_change_aux = amount_change;
        for (let payment_id in payments) {

            let payment = payments[payment_id];
            //Only update giftcard payments if we are NOT an estimate (suspended = 2)
            /* if ($suspended != 2) {
                 if (substr($payment['payment_type'], 0, strlen(lang('sales_giftcard'))) == lang('sales_giftcard')) {
                     // We have a gift card and we have to deduct the used value from the total value of the card. 
                     splitpayment = explode(':',payment['payment_type']);
                     cur_giftcard_value = objGiftcard.get_giftcard_value(splitpayment[1]);
 
                     await objGiftcard.update_giftcard_value(splitpayment[1], cur_giftcard_value - payment['payment_amount']);
                     total_giftcard_payments += payment['payment_amount'];
                 }
             }*/
            let sales_payments_data = {

                'sale_id': null,
                'payment_type': payment['payment_type'],
                'payment_amount': payment['payment_amount'],
                'payment_date': payment['payment_date'],
                'truncated_card': payment['truncated_card'],
                'card_issuer': payment['card_issuer'],
            };
            // si se permite solo $amount_change en efectivo se coloca el if, de lo contrario no
            if (payment['payment_type'] == lang('sales_cash')) {

                if (payment['payment_amount'] <= amount_change_aux) {

                    amount_change_aux = amount_change_aux - payment['payment_amount'];
                    sales_payments_data['payment_amount'] = 0;

                } else if (payment['payment_amount'] > amount_change_aux) {

                    sales_payments_data["payment_amount"] = sales_payments_data["payment_amount"] - amount_change_aux;
                    amount_change_aux = 0;
                }
            }
            payments_data[payment_id] = sales_payments_data;

        }
        let has_added_giftcard_value_to_cost_price = total_giftcard_payments > 0 ? false : true;
        let store_account_item_id = await objItem.get_store_account_item_id(null);
        let cost_price = 0;
        let ii = 0;
        let ii2 = 0;
        let ii3 = 0;
        let ii4 = 0;
        let ii5 = 0;
       // let ii6 = 0;
        var cur_item_info;
        var cur_item_location_info;
        for (line in items) {
            var item = items[line];
            if (item['item_id'] != undefined) {
                cur_item_info = await objItem.get_info(item['item_id']);
                cur_item_location_info = await objItem_location.get_info(item['item_id']);

                item['quantity'] = mode == 'return' ? -item['quantity'] : item['quantity'];

                if (item['item_id'] != store_account_item_id) {
                    cost_price = (cur_item_location_info != undefined && Boolean(cur_item_location_info.cost_price)) ? cur_item_location_info.cost_price : cur_item_info.cost_price;
                } else { // Set cost price = price so we have no profit
                    cost_price = item['price'];
                }

                if (await objAppconfig.item('disable_subtraction_of_giftcard_amount_from_sales') == false) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!has_added_giftcard_value_to_cost_price) {
                        cost_price += total_giftcard_payments / item['quantity'];
                        has_added_giftcard_value_to_cost_price = true;
                    }
                }
                var reorder_level = (cur_item_location_info != undefined && Boolean(cur_item_location_info.reorder_level)) ? cur_item_location_info.reorder_level : cur_item_info.reorder_level;

                if (cur_item_info.tax_included == 1) {
                    item['price'] = await objItems_helper.get_price_for_item_excluding_taxes(item['item_id'], item['price']);
                }
                let sales_items_data = {

                    'sale_id': null,
                    'item_id': item['item_id'],
                    'line': item['line'],
                    'description': item['description'],
                    'serialnumber': item['serialnumber'],
                    'quantity_purchased': item['quantity'],
                    'discount_percent': item['discount'],
                    'item_cost_price': to_currency_no_money(cost_price, 10),
                    'item_unit_price': item['price'],
                    'commission': await objItems_helper.get_commission_for_item(item['item_id'], item['price'], item['quantity'], item['discount']),
                    'custom1_subcategory': item['custom1_subcategory'],
                    'custom2_subcategory': item['custom2_subcategory'],
                    'numero_cuenta': item['numero_cuenta'],
                    'numero_documento': item['numero_documento'],
                    'titular_cuenta': item['titular_cuenta'],
                    "tasa": item["tasa"],
                    "tipo_documento": item["tipo_documento"],
                    "id_tier": item["id_tier"],
                    "transaction_status": item["transaction_status"],
                    "fecha_estado": item["fecha_estado"],
                    "comentarios": item["comentarios"],
                    "tipo_cuenta": item["tipo_cuenta"],
                    "is_promo": item["is_promo"],
                    "is_serialized":item['is_serialized']

                };
                items_data[ii] = sales_items_data;
                ii++;



                //Only update giftcard payments if we are NOT an estimate (suspended = 2)
                /* if ($suspended != 2) {
                     //create giftcard from sales
                     if ($item['name'] == lang('sales_giftcard') && !$this -> Giftcard -> get_giftcard_id($item['description'])) {
                         $giftcard_data = array(
                             'giftcard_number' => $item['description'],
                             'value' => $item['price'],
                             'customer_id' => $customer_id > 0 ? $customer_id : null,
                         );
 
                         if (!$this -> Giftcard -> save($giftcard_data)) {
                             $this -> db -> query("ROLLBACK");
                             $this -> db -> query('UNLOCK TABLES');
                             return -1;
                         }
                     }
                 }*/

                //Only do stock check + inventory update if we are NOT an estimate
                if (suspended != 2) {
                    let stock_recorder_check = false;
                    let out_of_stock_check = false;
                    let email = false;
                    let message = '';

                    //checks if the quantity is greater than reorder level
                    if (cur_item_info.is_service == false && cur_item_location_info.quantity > reorder_level) {
                        stock_recorder_check = true;
                    }

                    //checks if the quantity is greater than 0
                    if (cur_item_info.is_service == false && cur_item_location_info.quantity > 0) {
                        out_of_stock_check = true;
                    }

                    //Update stock quantity IF not a service
                    if (cur_item_info.is_service == false) {
                        cur_item_location_info.quantity = (cur_item_location_info.quantity !== null ? cur_item_location_info.quantity : 0);

                        if (!await objItem_location.save_quantity(cur_item_location_info.quantity - item['quantity'], item['item_id'])) {
                            return -1;
                        }
                        if (await objAppconfig.item('subcategory_of_items') == 1 && item['has_subcategory'] == 1) {
                            let subcategory = await objItems_subcategory.get_info(item['item_id'], false, item['custom1_subcategory'], item['custom2_subcategory']);
                            let quantity_subcategory = subcategory.quantity;
                            if (! await objItems_subcategory.save_quantity((quantity_subcategory - item['quantity']),
                                item['item_id'], false, item['custom1_subcategory'], item['custom2_subcategory'])) {
                                return -1;
                            }

                        }

                        if (item['is_serialized'] == 1) {

                            /*if (!await objAdditional_item_seriales -> delete_serial(item['item_id'], item["serialnumber"])) {
                                
                                return -1;
                            }*/

                        }
                    }

                    //Re-init $cur_item_location_info after updating quantity
                    cur_item_location_info = objItem_location.get_info(item['item_id']);

                    //checks if the quantity is out of stock
                    /* if (out_of_stock_check && cur_item_location_info.quantity <= 0) {
                         message = cur_item_info.name+' '+lang('sales_is_out_stock')+ ' '+to_quantity(cur_item_location_info. quantity);
                         email = true;
                     }
                     //checks if the quantity hits reorder level
                     else if ($stock_recorder_check && ($cur_item_location_info -> quantity <= $reorder_level)) {
                         $message = $cur_item_info -> name. ' '.lang('sales_hits_reorder_level'). ' '.to_quantity($cur_item_location_info -> quantity);
                         $email = true;
                     }*/
                     if (cur_item_info.is_service==false) {
                        let qty_buy = -item['quantity'];
                        let sale_remarks = await objAppconfig.item('sale_prefix');
                        let inv_data = 
                            {
                            'trans_date': moment().format("YYYY-MM-DD HH:mm:ss"),
                            'trans_items' : item['item_id'],
                            'trans_user' : employee_id,
                            'trans_comment' : sale_remarks,
                            'trans_inventory' : qty_buy,
                            'location_id' : await objEmployee.get_logged_in_employee_current_location_id(),
                            };
                            inventario_data[ii5]= inv_data;
                            ii5++;                        
                    }
                }
            } else {//  para los kits
                var cur_item_kit_info = await objItem_kit.get_info(item['item_kit_id']);
                var cur_item_kit_location_info = await objItem_kit_location.get_info(item['item_kit_id']);

                cost_price = (cur_item_kit_location_info != undefined && Boolean(cur_item_kit_location_info.cost_price)) ? cur_item_kit_location_info.cost_price : cur_item_kit_info.cost_price;

                if (await objAppconfig.item('disable_subtraction_of_giftcard_amount_from_sales') == false) {
                    //Add to the cost price if we are using a giftcard as we have already recorded profit for sale of giftcard
                    if (!has_added_giftcard_value_to_cost_price) {
                        cost_price += total_giftcard_payments / item['quantity'];
                        has_added_giftcard_value_to_cost_price = true;
                    }
                }
                if (cur_item_kit_info.tax_included == 1) {
                    item['price'] = await objitem_kits_helper.get_price_for_item_kit_excluding_taxes(item['item_kit_id'], item['price']);
                }
                var sales_item_kits_data = {
                    'sale_id': null,
                    'item_kit_id': item['item_kit_id'],
                    'line': item['line'],
                    'description': item['description'],
                    'quantity_purchased': item['quantity'],
                    'discount_percent': item['discount'],
                    'item_kit_cost_price': cost_price === null ? 0.00 : to_currency_no_money(cost_price, 10),
                    'item_kit_unit_price': item['price'],
                    'commission': await objitem_kits_helper.get_commission_for_item_kit(item['item_kit_id'], item['price'], item['quantity'], item['discount']),
                    "id_tier": item['id_tier']
                };

                item_kits_data[ii2] = sales_item_kits_data;
                ii2++;

                let kits_tem = await objItem_kit_item.get_info(item['item_kit_id']);
                for (let key in kits_tem) {
                    var item_kit_item = kits_tem[key];
                    cur_item_info = await objItem.get_info(item_kit_item.item_id);
                    cur_item_location_info = await objItem_location.get_info(item_kit_item.item_id);

                    var reorder_level = (cur_item_location_info != undefined && cur_item_location_info.reorder_level !== null) ? cur_item_location_info.reorder_level : cur_item_info.reorder_level;

                    //Only do stock check + inventory update if we are NOT an estimate
                    if (suspended != 2) {
                        let stock_recorder_check = false;
                        let out_of_stock_check = false;
                        let email = false;
                        let message = '';

                        //checks if the quantity is greater than reorder level
                        if (cur_item_info.is_service == false && cur_item_location_info.quantity > reorder_level) {
                            stock_recorder_check = true;
                        }

                        //checks if the quantity is greater than 0
                        if (cur_item_info.is_service == false && cur_item_location_info.quantity > 0) {
                            out_of_stock_check = true;
                        }

                        //Update stock quantity IF not a service item and the quantity for item is NOT NULL
                        if (cur_item_info.is_service == false) {
                            cur_item_location_info.quantity = cur_item_location_info.quantity !== null ? cur_item_location_info.quantity : 0;

                            if (!await objItem_location.save_quantity(cur_item_location_info.quantity - (item['quantity'] * item_kit_item.quantity), item_kit_item.item_id)) {

                                return -1;
                            }

                        }

                        //Re-init $cur_item_location_info after updating quantity
                        //y cur_item_location_info = await objItem_location.get_info(item_kit_item.item_id);

                        if (cur_item_info.is_service==false) {
                            let qty_buy = -item['quantity'] * item_kit_item.quantity;
                            let sale_remarks = await objAppconfig.item('sale_prefix');
                            let inv_data = 
                            {
                            'trans_date': moment().format("YYYY-MM-DD HH:mm:ss"),
                            'trans_items' : item_kit_item.item_id,
                            'trans_user' : employee_id,
                            'trans_comment' : sale_remarks,
                            'trans_inventory' : qty_buy,
                            'location_id' : await objEmployee.get_logged_in_employee_current_location_id(),
                            };
                            inventario_data[ii5]= inv_data;
                            ii5++;                          
                            
                        }
                    }
                }
            }



            if (customer_id == -1 || customer.taxable == true) {
                if (item['item_id'] != undefined) {
                    var taxes_finder_aux = await objItem_taxes_finder.get_info(item['item_id']);
                    for (let key in taxes_finder_aux) {
                        let row = taxes_finder_aux[key];
                        let tax_name = row['percent'] + '% ' + row['name'];
                        //Only save sale if the tax has NOT been deleted
                        var query = $.inArray(tax_name, deleted_taxes);
                        if (query == -1) {
                            let sales_items_taxes = {
                                'sale_id': null,
                                'item_id': item['item_id'],
                                'line': item['line'],
                                'name': row['name'],
                                'percent': row['percent'],
                                'cumulative': row['cumulative'],
                            };
                            items_taxes[ii3] = sales_items_taxes;
                            ii3++;
                        }
                    }
                } else {
                    let taxes_finder_aux = await objItem_kit_taxes_finder.get_info(item['item_kit_id']);
                    for (key in taxes_finder_aux) {
                        let row = taxes_finder_aux[key];
                        let tax_name = row['percent'] + '% ' + row['name'];

                        //Only save sale if the tax has NOT been deleted
                        var query = $.inArray(tax_name, deleted_taxes);
                        if (query == -1) {
                            let sales_item_kits_taxes = {
                                'sale_id': null,
                                'item_kit_id': item['item_kit_id'],
                                'line': item['line'],
                                'name': row['name'],
                                'percent': row['percent'],
                                'cumulative': row['cumulative'],
                            };

                            item_kits_taxes[ii4] = sales_item_kits_taxes;
                            ii4++;
                        }
                    }
                }
            }
        }
        // se valida para evitar relizar ventas en otra db
        if (localStorage.getItem('store_login') == null || localStorage.getItem('store_login')!="store_"+ await get_data_por_key("store")) {
            return -1;
        }
        sales_data["sales_items"] = items_data;
        sales_data["sale_items_taxes"] = items_taxes;
        sales_data["sales_item_kits"] = item_kits_data;
        sales_data["sales_item_kits_taxes"] = item_kits_taxes;
        sales_data["sales_payments"] = payments_data;
        sales_data["inv_data"]=inventario_data;
        sales_data["movement_register"]=movement_register;
        sales_data["store_account_transaction"]=store_account_transaction;
        sales_data["store"] = localStorage.getItem('store_login');
        sales_data["id_synchronization"]= id_synchronization;
        sales_data["token"]= await get_data_por_key("token");


        let id_sala;
        try {
            id_sala = await db1.sales_offline.add(sales_data);
        } catch (e) {
            console.log("error : " + e)
            return -1;

        }

        return id_sala > 0 ? id_sala : -1;
    }
    get_payment_cash(payments)
    {
        let total_cash = 0;
        for  (let i in payments) {
            let value = payments[i];
            if (value['payment_type'] == lang('sales_cash')) {
                total_cash += Number(value['payment_amount']);
            }
        }
        return total_cash;
    }
    get_payment_credit(payments)
    {
        let total_cash = 0;
        for  (let i in payments) {
            let value = payments[i];
            if (value['payment_type'] == lang('sales_store_account')) {
                total_cash += Number(value['payment_amount']);
            }
        }
        return total_cash;
    }
    async  add_petty_cash_customer(customer_id, _balance, suspended = 0) {
        let store_account_transaction =false;
        if (customer_id > 0 && _balance!=0) {
            try {
                let aux_balance= _balance;
                let cust_info = await objCustomer.get_info(customer_id);
                _balance = Number(_balance) + Number(cust_info.balance);
                let query = await db1.customers.where({ person_id: customer_id }).modify({ balance: _balance });
                let customer = await objCustomer.get_info(customer_id);
                store_account_transaction = { 
                    'customer_id':customer_id,            
                    'comment':"Offline",
                    'transaction_amount':aux_balance,
                    'balance': customer.balance,
                    'date' :moment().format("YYYY-MM-DD HH:mm:ss"),
                    "movement_type":0, //0 add al saldo 
                    "category":"Venta"
                };
            } catch (e) {
                return false;
            }
        }
        return store_account_transaction;
    }
    async is_item_promo(itemId) {
        let today = strtotime(moment().format("YYYY-MM-DD"));
        let item_info = await objItem.get_info(item_id);
        let is_item_promo_ = (await this.get_promo_quantity(itemId) > 0 && (item_info.start_date !== null && item_info.end_date !== null) && (strtotime(item_info.start_date) <= today && strtotime(item_info.end_date) >= today));
        if (is_item_promo_) {
            return true;
        }

        return false;
    }
    async drecrease_promo_quantity(quantity, item_id) {

        try {
            let item_info = await objItem.get_info(item_id);
            quantity = Number(item_info.promo_quantity) - Number(quantity);
            let query = await db1.items.where({ item_id: "" + item_id }).modify({ promo_quantity: quantity });

        } catch (e) {
            return false;
        }

        return false;
    }
    async  get_current_register_log_person(session_id) {/*
        let register_id = await objEmployee.get_logged_in_employee_current_register_id();
        const data = await db1.register_log.where(
            { 
                register_id: ""+register_id,
                employee_id_open:""+session_id,
                shift_end:'0000-00-00 00:00:00',
            }
        ).first();
        //$this->db->order_by('register_log_id', 'Desc');       
        if (data!=undefined) {
            return data;
        } 
        return false; */

    }
    async get_current_register_log(register_id = false) {
        if (!register_id) {
            register_id = await objEmployee.get_logged_in_employee_current_register_id();
        }
        const data = await db1.register_log.where(
            {
                register_id: "" + register_id,
                shift_end: '0000-00-00 00:00:00',
            }
        ).first();
        if (data != undefined) {
            return data;
        }
        return false;
    }
     maxi_number(sale_type=0, sales) {
        let maxi=1;
        if(sale_type==1){
            for(i in sales) {
                if(sales[i].invoice_number>maxi){
                    maxi=sales[i].invoice_number;
                }
            };
        }else{
            for(i in sales) {
                if(sales[i].ticket_number>maxi){
                    maxi=sales[i].ticket_number;
                }
            };
        }
        return maxi;
    }
    async get_next_sale_number(sale_type = 0, location_id) {
        let number = 0;
        let last_number = await get_data_por_key("last_invoice");
        
        if (sale_type == 1) {
            if(undefined== last_number){
                last_number=0; 
            }else{
                last_number = last_number[location_id]==undefined? 0 :last_number[location_id]["invoice_number"];
            }
            //const sales = await db1.sales_offline.where({ synchronized: 0, location_id: Number(location_id) }).toArray();
            const sales = await db1.sales_offline.where({ location_id: Number(location_id) }).toArray();

            if (sales.length > 0) {
                let aux_number =   this.maxi_number(1, sales);
                number =aux_number>= last_number?  aux_number: last_number;
                number++;
            } else{
                number=last_number;
                number++;
            }

        } else {
            if(undefined== last_number){
                last_number=0; 
            }else{
                last_number = last_number[location_id]==undefined? 0 :last_number[location_id]["ticket_number"];
            }
           // const sales = await db1.sales_offline.where({ synchronized: 0, location_id: Number(location_id) }).toArray();
           const sales = await db1.sales_offline.where({ location_id: Number(location_id) }).toArray();
            let aux_number =   this.maxi_number(1, sales);
            if (sales.length > 0) {
                number = aux_number >= last_number ?  aux_number : last_number;
                number++;
            } else{
                number=last_number;
                number++;
            }         
        }

        return number;

    }
    async  get_promo_quantity(item_id) {
        const transaction = db2.transaction(['items'], 'readonly');
        const objectStore = transaction.objectStore('items');
        const item = await objectStore.get(String(item_id));

        if (item != undefined) {
            return Number(item.promo_quantity);
        }
        return false;
    }
    async   is_item_promo(itemId) {
        let today = strtotime(moment().format("YYYY-MM-DD"));
        let item_info = await objItem.get_info(itemId);
        let promo_quantity = await this.get_promo_quantity(itemId);
        let is_item_promo = (promo_quantity > 0 && (item_info.start_date !== null && item_info.end_date !== null) &&
            (strtotime(item_info.start_date) <= today && strtotime(item_info.end_date) >= today)
        );

        if (is_item_promo) {
            return true;
        }
        return false;
    }
    async is_register_log_open(register_id=false)
    {
        register_id = register_id != false? register_id: await objEmployee.get_logged_in_employee_current_register_id();
        let result = await db1.register_log.where({shift_end: '0000-00-00 00:00:00', register_id:register_id }).first();       
        return result!=undefined ? true:false;
      
    }
    async close_register(cash_register,closing_amount,_register_id=false){
        let now = moment().format("YYYY-MM-DD HH:mm:ss");//date('Y-m-d H:i:s');
	     cash_register.register_id = _register_id != false ? String(_register_id): await objEmployee.get_logged_in_employee_current_register_id();
			cash_register.employee_id_close = await get_person_id();
			cash_register.shift_end = now; 
			cash_register.closed_manual = 1; 
			cash_register.close_amount = closing_amount;
			await objRegister_movement.save(cash_register.close_amount * (-1), "Cierre de caja",false, false,"Cierre de caja");
			await this.update_register_log(cash_register,_register_id);
    }

}