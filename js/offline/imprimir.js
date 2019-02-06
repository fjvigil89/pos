async function cargar_datos_compania(data){
   let  overwrite_data= await objLocation.get_info_for_key('overwrite_data');
   $("#company_name").html( overwrite_data==1 ? await objLocation.get_info_for_key('name') : await objAppconfig.item('company') );           

   $("#company_dni").html(lang('config_company_dni')+"."+
        (overwrite_data==1? await objLocation.get_info_for_key('company_dni'): await objAppconfig.item("company_dni")) );
    $("#company_address").html((overwrite_data==1? await objLocation.get_info_for_key('address'):  await objAppconfig.item("address")) );
    $("#company_phone").html( overwrite_data==1 ? await objLocation.get_info_for_key('phone') : await objAppconfig.item('phone') );           
       
    if((overwrite_data==1 && await objLocation.get_info_for_key('website') ) || (overwrite_data==0 && await objAppconfig.item('website') )) {            
        $("#website").html( overwrite_data==1 ? await objLocation.get_info_for_key('website') : await objAppconfig.item('website') );           
    } else{
        $("#website").hide();
    }

}
async function cargar_datos_factura(data){
    let html="<strong>";
	if(data.mode!=undefined && data.mode=='return') {
        html+= lang('items_sale_mode').data.sale_type+" "+data.sale_number;
	} 
	else if(data.mode != undefined && data.store_account_payment == 1) {
        html+=  lang('sales_store_account_name')+''+data.sale_id;
	}
	else if(data.show_comment_ticket == 1) {
		html+='BOLETA '+data.sale_number;
	} 
	else{
		html+= await objAppconfig.item('sale_prefix')+' '+data.sale_number;
	}  
	html+="</strong>";		 		
			 	
    $("#sale_receipt").html(html);
    $("#sale_time").html(data.transaction_time);
    
}
async function cagar_datos_general(data){
     let html="";
    if(data.customer!=undefined) {
        html +="<div>"+ lang('customers_customer')+": "+data.customer +"</div>";  
        if(data.customer_address_1!=""){
            html +="<div>"+lang('common_address')+":"+data.customer_address_1+' '+data.customer_address_2; 
        }
        if(data.customer_city!=""){
            html +="<div>"+data.customer_city+" "+data.customer_state+', '+data.customer_zip+"</div>"; 
        }
        if(data.customer_country!=""){
            html +="<div>"+data.customer_country+"</div>"; 
        }
        if(data.customer_phone!=""){
            html +="<div>"+lang('common_phone_number')+": "+data.customer_phone+"</div>"; 
        }
        if(data.customer_email!=""){
            html +="<div>"+lang('common_email')+": "+data.customer_email+"</div>"; 
        }	
        if(data.sale_type!=undefined){
            html +="<div id='sale_type'>"+data.sale_type+"</div>"; 
        }
         if (data.register_name) { 
            html +='<div id="sale_register_used">';
            html += lang('locations_register_name')+': '+data.register_name; 
            html +=' </div>	';	
        }                        
        if (data.tier) { 
            html +='<div id="tier">';
            html += lang('items_tier_name')+': '+data.tier; 
            html +='</div>';	
         }         
         html +='<div id="employee">';
         html += lang('employees_employee')+": "+data.employee; 
         html +='</div>';
        
       	
    }
    $("#receipt_general_info").html(html);
   
}
async function cargar_tabla_producto(data){
   let html='<th class="left_text_align"';

    html+= 'style="width:'+(data.discount_exists ==1? "33%" : "55%" )+'"';
    html+='colspan="'+(data.discount_exists==1 ? 3 : 1 )+'">';
    html+=lang('items_item')+"</th>";
    if(await objAppconfig.item('subcategory_of_items')==1 ){ 
        html+='<th class="gift_receipt_element left_text_align" style="width:30%;">';
        html+= await objAppconfig.item('inhabilitar_subcategory1')==1?"":(await objAppconfig.item("custom_subcategory1_name")+"/") ;
        html+= await objAppconfig.item("custom_subcategory2_name")+"</th>"; 
    }       
    if(data.show_number_item==1){
        html+=' <th class="gift_receipt_element text-center" style="width:30%;">UPC</th>';
    }
    html+='<th class="gift_receipt_element left_text_align" style="width:20%;">'+ lang('common_price')+"</th>";
    html+='<th class="text-center" style="width:15%;" colspan="'+(data.discount_exists==1 ? 1 : 2) +'">';
    html+=lang('sales_quantity_short')+'</th>';
	if(data.discount_exists==1) { 
	    html+='<th class="gift_receipt_element text-center" style="width:16%;">'+lang('sales_discount_short')+'</th>';
	} 
    html+='<th class="gift_receipt_element right_align" style="width:5%;" colspan="3">'+ lang('sales_total')+'</th>';
    $("#cabeta-tabla-producto").html( html);
        //-------------cuerpo---------------
    let items =data.cart;
    const key = Object.keys(items).reverse();
    //---productos
    for (var i in key) {
        let item = items[key[i]];
        html="<tr"+ (await objAppconfig.item('activar_casa_cambio')==1 ? 'style="border-top: 1px dashed #000000;"':"")+'>';
      
        html+='<td class="left_text_align" colspan="'+(data.discount_exists==1 ? 3 : 1 )+'">';								
        //character_limiter(H($item['name']), $this->config->item('show_fullname_item') ? 200 : 25); ?>
        html+=character_limiter(escapeHtml(item['name']),await objAppconfig.item("show_fullname_item")==1?200:25)+"&nbsp";					
		if (item['size']!=undefined){ 
            html+='<span class="hidden-print">'+ item['size']+'</span>';
		 } 
		if (item['colour']!=undefined){ 
            html+='<span class="hidden-print">'+item['colour']+'</span>';
		} 
		if (item['model'] !=undefined){  
            html+='<span class="hidden-print">'+item['model']+'</span>';
		} 
		 if (item['marca']!=undefined){ 
			html+='<span class="hidden-print">'+item['marca']+'</span>';
		} 
        html+='</td>';
        if(await objAppconfig.item('subcategory_of_items')==1 ){ 
            html+='<td class="gift_receipt_element left_text_align">';
            if (item['item_id']!=undefined){ 
                html+=(
                    (await objAppconfig.item('inhabilitar_subcategory1')==1?"":(item['custom1_subcategory']?item['custom1_subcategory']:"--")+"/")+
                    (item['custom2_subcategory']?item['custom2_subcategory']:"--")
                );
            }else{
                 html+="--/--";
            }
            html+=' </td>';        
        }
        if(data.show_number_item==1){
			html+='<td class=" gift_receipt_element text-center" colspan="1" >';
			if (item['item_number']!=undefined){ 
				html+=item['item_number']+" ";
			}else if (item['item_kit_number']!=undefined){
				html+=item['item_kit_number']+" ";
			}			
            html+='</td>';
            
        }
        html+=' <td class="gift_receipt_element left_text_align">';
        html+=(await objAppconfig.item('round_value')==1 ?
         await to_currency(Math.round(item['price'])) :await to_currency(item['price']));
        html+='</td>';
        
        html+='<td class="text-center" colspan="'+(data.discount_exists==1 ? 1 : 2)+'">';
        html+= to_quantity(item['quantity']);
		if (item['model']!=undefined){ 
            html+=" "+item['unit'];
        }
        html+= '</td>';
        if(data.discount_exists==1) { 
            html+='<td class="gift_receipt_element text-center">';
            html+=item['discount']+'%'; 
            html+='</td>';
        } 
        let tax_info;        
        let sum_tax;
        let value_tax;
        let price_with_tax;
        let prev_tax = Array();
         if (item['item_id']!=undefined && item['name']!=lang('sales_giftcard')) 
        {
            tax_info = await objItem_taxes_finder.get_info(item['item_id']);
            let i=0;
            for(let key in tax_info){
                let tax= tax_info[key];
                if (prev_tax[item.item_id] == undefined) {
                    let aux = Array();
                    aux[i] = tax['percent'] / 100;
                    prev_tax[item.item_id] = aux;
                } else {
                    prev_tax[item.item_id][i] = tax['percent'] / 100;
                }
               
                i++;
            }			
            if (prev_tax[item['item_id']]!=undefined && item['name']!=lang('sales_giftcard')) 
            {						
                sum_tax=array_sum(prev_tax[item['item_id']]);
                value_tax=item['price']*sum_tax;										
                price_with_tax =Number(item['price'])+Number(value_tax);	
            }
            else if (prev_tax[item['item_id']]==undefined && item['name']!=lang('sales_giftcard'))
            {																				
                sum_tax=0;
                value_tax=item['price']*sum_tax;										
                price_with_tax=Number(item['price'])+Number(value_tax);	
            }	
        }
        let tax_kit_info;
        if (item['item_kit_id']!=undefined && item['name']!=lang('sales_giftcard')) 
        {
            tax_kit_info = await objItem_kit_taxes_finder.get_info(item['item_kit_id']);
            let i=0;
            for(let key in tax_kit_info){
                let tax= tax_kit_info[key];	
                if (prev_tax[item.item_kit_id] == undefined) {
                    let aux = Array();
                    aux[i] = tax['percent'] / 100;
                    prev_tax[item.item_kit_id] = aux;
                } else {
                    prev_tax[item.item_kit_id][i] = tax['percent'] / 100;
                }              
                i++;
            }
            if (prev_tax[item['item_kit_id']]!=undefined && item['name']!=lang('sales_giftcard')) 
            {
                sum_tax=array_sum(prev_tax[item['item_kit_id']]);
                value_tax=item['price']*sum_tax;									
                price_with_tax=Number(item['price'])+Number(value_tax);
            }
            else if (prev_tax[item['item_kit_id']]==undefined && item['name']!=lang('sales_giftcard')) 
            {
                sum_tax=0;
                value_tax=item['price']*sum_tax;									
                price_with_tax=Number(item['price'])+Number(value_tax);
            }
        }	
        if (item['name']==lang('sales_giftcard')) { 
            html+='<td class="gift_receipt_element right_text_align" colspan="2">';
            let Total=item['price']*item['quantity']-item['price']*item['quantity']*item['discount']/100;
            html+= (await objAppconfig.item('round_value')==1 ? await to_currency(Math.round(Total)) :await to_currency(Total)); 								 	
               
            html+="</td>";
         }
         else{
            html+='<td class="gift_receipt_element right_text_align" colspan="2">';
			let Total=price_with_tax*item['quantity']-price_with_tax*item['quantity']*item['discount']/100;
            html+= (await objAppconfig.item('round_value')==1 ? await  to_currency(Math.round(Total)) :await to_currency(Total)); 								 	
            html+="</td>";
        }
        html+="<tr>";
        if(await objAppconfig.item('hide_description')=='0'){
            html+='<td colspan="3" align="left">';
            html+=item['description']; 
            html+="</td>";
        } 
        html+="</tr>";
        html+='<td colspan="1" >';
        html+= (item['serialnumber']!=undefined ? item['serialnumber'] : '');
        html+='</td>';
    
        if(await objAppconfig.item('activar_casa_cambio') ==1){
            html+='<tr>';						
            html+='<td colspan="5" >';
            html+='<strong>'+lang("sales_titular_cuenta")+'</strong><br>'+item["titular_cuenta"];
            html+='</td>';                                   
            html+='</tr>';
            html+='<tr>';					
            html+='<td colspan="4" >';
            html+='<strong>'+lang("sales_docuemento")+'</strong><br>'+item["numero_documento"];
            html+='</td>';							
            html+='</tr>';
            html+='<tr>';							
        
            html+='<td colspan="5" >';
            html+='<strong>'+ lang("sales_numero_cuenta")+'</strong><br>'+item["numero_cuenta"];
            html+='</td>';                                        
            html+='</tr>';
            html+='<tr>';							
        
            html+='<td colspan="5" >';
            html+='<strong>Total '+lang("sales_".data.divisa)+": "+'</strong>';
            let total2=0;
            let Total_por_item=item['price']*item['quantity'];
            let tasa=((item["tasa"]!=0|| item["tasa"]!=null)?item["tasa"]:1);
            if(opcion_sale=="venta"){
                total2+=Total_por_item/tasa;
            }else{
                total2+=Total_por_item*tasa;
            }	
            html+= to_currency_no_money(total2,4);             
            html+='</td>';
            html+='</tr>';
        }    

        html+="</tr>";
        $("#receipt_items").append(html);
    }
   //--------------fin dato productos

   //---------------precion-------
    html='<tr class="gift_receipt_element">';
    html+='<td class="right_text_align" colspan="'+(data.discount_exists ==1? '6' : '4')+'" style="border-top:1px solid #000000;">';
    html+= lang('sales_sub_total')+':</td>';
	html+='<td class="right_text_align" colspan="2" style="border-top:1px solid #000000;">';
	html+=await objAppconfig.item('round_value')==1 ? await to_currency(Math.round(data.subtotal)) :await to_currency(data.subtotal) ; 
    html+="</td></tr>";
    
    html+=' <tr class="gift_receipt_element">';
    html+='<td class="right_text_align "colspan="'+(data.discount_exists ==1? '6' : '4')+'">';
    html+="<strong>"+ (await objAppconfig.item('activar_casa_cambio') ==0? lang('saleS_total_invoice'):("Total "+await objAppconfig.item('sale_prefix')))+":</strong>";
    html+="</td>";
    html+='<td class="right_text_align" colspan="2" >';
    let subtotal_= 0;
	if(await objAppconfig.item('round_cash_on_sales')==1 && data.is_sale_cash_payment ==true){
			subtotal_=await to_currency(round_to_nearest_05(data.total));
	} 
	else if(await objAppconfig.item('round_value')==1  ){
		subtotal_= await to_currency(Math.round(data.total));
	}else{
		subtotal_= await to_currency(data.total);
	}
	html+=subtotal_;
    html+='</td></tr>';
    if(await objAppconfig.item('activar_casa_cambio')==true){
        html+='<tr class="gift_receipt_element">';
        html+='<td class="right_text_align "colspan="'+(data.discount_exists==true ? '6' : '4')+'">';
        html+='<strong>'+(await objAppconfig.item('activar_casa_cambio') ==0? lang('saleS_total_invoice'):"Total "+lang("sales_"+data.divisa))+":</strong>";
        html+='</td>';
        html+='<td class="right_text_align" colspan="2" >';
        html+=Number(data.total_divisa );
        html+='</td></tr>';
    }
    
    $("#receipt_items").append(html); 
    //----------------------------------------
    html="";
    let payments= data.payments;
    let payment;
   for( let payment_id in payments) {
        payment= payments[payment_id];
        html+='<tr class="gift_receipt_element">';
        html+='<td class="right_text_align" colspan="'+(data.discount_exists ? '6' : '4')+'">';
        html+=(payment["payment_type"]!=lang('sales_store_account')? lang('sales_value_cancel') : lang('sales_crdit') )+":";
        html+='</td>';
        html+='<td class="right_text_align" colspan="2">';
        let payment_amount= 0;
        if(await objAppconfig.item('round_cash_on_sales')==1 && payment['payment_type'] == lang('sales_cash') ){
            payment_amount=await to_currency(round_to_nearest_05(payment['payment_amount']));
        } 
        else if(await objAppconfig.item('round_value')==1  ){
            payment_amount= await to_currency(Math.round(payment['payment_amount']));
        }else{
            payment_amount=await to_currency(payment['payment_amount']);
        }
        html+= payment_amount;
        html+='</td> </tr>';
    } 
    $("#receipt_items").append(html);    
}
async function cargar_tabla_impuesto(data){
    let html="";
    if(data.show_comment_ticket!= undefined && (data.show_comment_ticket == 0 && await objAppconfig.item('hide_invoice_taxes_details') == 0)  || (await objAppconfig.item('hide_ticket_taxes')==0 && data.show_comment_ticket == 1)){ 

        /*html+='<tr>';
        html+='<th class="text-center" style="width:100%" colspan="6">'+ lang('sales_details_tax')+'</th>';						
        html+='</tr>';
        html+='<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">';
        html+='<th class="text-center" colspan="2">'+ lang('sales_type_tax')+'</th>';
        html+='<th class="right_text_align" style="width:20%">'+lang('sales_base_tax')+'</th>';
        html+='<th class="right_text_align" style="width:25%">'+ lang('sales_price_tax')+'</th>';						
        html+='<th class="right_text_align" style="width:18%" colspan="2">'+ lang('sales_value_receiving')+'</th>';
        html+='</tr>';*/

        if (await objAppconfig.item('group_all_taxes_on_receipt')==true) { 
        
                let total_tax = 0;
                for(let name in data.detailed_taxes ) 
                {
                    let value=data.detailed_taxes[name];
                    total_tax+=value['total_tax'];
                }
                html+='<tr class="gift_receipt_element">';
                html+='<td class="left_text_align" colspan="2">';
                html+=lang('reports_tax')+':';
                html+=' </td>';
                html+=' <td class="right_text_align">';
                html+= value['base']; 
                html+='</td>';
                html+='<td class="right_text_align">';
                html+= await to_currency_no_money(Math.round(value['total_tax']),1);
                html+='</td>';
                html+='<td class="right_text_align" colspan="2">';
                html+= (await objAppconfig.item('round_value')==1 ? await to_currency(Math.round(total_tax)) :await to_currency(total_tax) ); 
                html+='</td>';
                html+='</tr>';
            }else{
                let detailed_taxes= data.detailed_taxes;
                   for(let name in detailed_taxes ) 
                {
                    let value=detailed_taxes[name];
                    html+='<tr class="gift_receipt_element">';
                    html+='<td class="left_text_align" colspan="2">';
                    html+=name+":";
                    html+='</td>';
                    html+='<td class="right_text_align">';
                    html+=value['base']; 
                    html+='</td>';
                    html+='<td class="right_text_align">';
                    html+=await to_currency_no_money(Math.round(value['total_tax']),1); 
                    html+='</td>';
                    html+='<td class="right_text_align" colspan="2">';
                    html+=(await objAppconfig.item('round_value')==1 ?await to_currency(Math.round(value['total'])) : await to_currency(value['total'])) ;
                    html+='</td>';
                    html+='</tr>';
                  
                }
            }
            html+='<tr style="border-top: 1px dashed #000000;">';
            html+='<th class="text-center" colspan="2">'+ lang('sales_total')+'='+'</th>';
            html+='<th class="right_text_align" style="width:15%">'+await to_currency(data.detailed_taxes_total['total_base_sum'])+'</th>';
            html+='<th class="right_text_align" style="width:25%">'+await to_currency(data.detailed_taxes_total['total_tax_sum'])+'</th>';
            html+='<th class="right_text_align" style="width:25%" colspan="2">'+(await objAppconfig.item('round_value')==1 ? await to_currency(data.detailed_taxes_total['total_sum']) :await to_currency(data.detailed_taxes_total['total_sum'])+"</th>");
            html+='</tr>';
    }else{
        $("#details_taxes").hide();
    }
    $("#details_taxes").append(html);

}
async function cargar_tabla_forma_pago(data){
    let html="";
    let splitpayment;
    if(await objAppconfig.item('ocultar_forma_pago')==0){
        let payments = data.payments;
        for(let payment_id in payments ){     
            let payment=payments[payment_id];  
            html+='<tr class="gift_receipt_element">';
            html+='<td class="left_text_align" colspan="3">'
            html+=(data.show_payment_times==1 && data.show_payment_times==1) ?  payment['payment_date'] : lang('sales_payment'); 
            html+='</td>';				
                
            html+='<td class="right_text_align" colspan="3">';
            splitpayment= payment['payment_type'].split(":");
            html+=splitpayment[0]; 
            html+='</td>';
            html+='<td class="right_text_align" colspan="2">';
            let payment_amount= 0;
            if( await objAppconfig.item('round_cash_on_sales')==1 && payment['payment_type'] == lang('sales_cash') ){
                    payment_amount=await to_currency(round_to_nearest_05(payment['payment_amount']));
            } 
            else if(await objAppconfig.item('round_value')==1  ){
                payment_amount= await to_currency(Math.round(payment['payment_amount']));
            }else{
                payment_amount= await to_currency(payment['payment_amount']);
            }
            html+=payment_amount;                    
            html+='</td>';										
                                        
            html+='</tr>';

            if(data.amount_change >= 0) { 
                html+='<tr class="gift_receipt_element" >';
                html+='<td class="right_text_align" colspan="'+(data.discount_exists ? '6' : '6' )+'">';
                html+=lang('sales_change_due')+":";
                html+='</td>';
                html+='<td class="right_text_align" colspan="2">';
                let amount_change_= 0;
                    if( await objAppconfig.item('round_cash_on_sales')==1 && data.is_sale_cash_payment==true ){
                        amount_change_=await to_currency(round_to_nearest_05(data.amount_change));
                    } 
                    else if(await objAppconfig.item('round_value')==1  ){
                        amount_change_=await  to_currency(Math.round(data.amount_change));
                    }else{
                        amount_change_= await to_currency(data.amount_change);
                    }
                    html+= amount_change_;
                    
                    html+='</td>';
                    html+='</tr>';
            }else{
               
                html+='<tr>';
                html+='<td class="right_text_align" colspan="'+(data.discount_exists ? '6' : '6')+'">';
                html+=lang('sales_amount_due')+":";
                html+='</td>';
                html+='<td class="right_text_align" colspan="2">';
                html+= (await objAppconfig.item('round_cash_on_sales')  && data.is_sale_cash_payment ) ? 
                 await to_currency(round_to_nearest_05(data.amount_change * -1)) :( 
                 (await to_currency(data.amount_change * -1) || await objAppconfig.item('round_value')==1 )? 
                 await to_currency(round(data.amount_change)) :await to_currency(data.amount_change)); 
                 html+='</td>';
                 html+='</tr>';
                
            }
       } 

    }else{
        $("#details_payments").hide();
    }
    $("#details_payments").append(html);
}
async function cargar_detalles_pagos(data){
    let html="";
    let payments= data.payments;
    for(let i in payments){
        let payment= payments[i];   
        if (payment['payment_type'].indexOf(lang('sales_giftcard'))!==-1) { 
            html+='<tr class="gift_receipt_element">';							
            html+='<td class="right_text_align" colspan="5">';
            html+= lang('sales_giftcard_balance')+payment['payment_type'];
            html+='</td>';
            let giftcard_payment_row = payment['payment_type'].split(":");
            html+='<td class="right_text_align" colspan="2">';
            html+= await to_currency(await objGiftcard.get_giftcard_value(giftcard_payment_row[1])); 
            html+='</td>';
            html+='</tr>';
        }
    }
    if (data.customer_balance_for_sale!=undefined && data.customer_balance_for_sale !== false &&  await objAppconfig.item('hide_balance_receipt_payment')==false) {
        html+=' <tr>';
        html+='<td class="right_text_align" colspan="'+(data.discount_exists ? '4' : '3')+'">';
        html+= lang('sales_customer_account_balance')+':';
        html+='</td>';
        html+='<td class="right_text_align" colspan="2">';
        html+=await  to_currency(data.customer_balance_for_sale); 
        html+='</td>';
        html+='</tr>';
     } 
      if (data.points!=undefined &&  await objAppconfig.item('show_point')==1 && await objAppconfig.item('system_point')==1) { 
        html+='<tr>';
        html+='<td class="right_text_align" colspan="'+(data.discount_exists==true ? '4' : '3')+'">';
        html+= lang('config_value_point_accumulated')+":";
        html+='</td>';
        html+='<td class="right_text_align" colspan="2">';
        html+=data.points; 
        html+='</td>';
        html+='</tr>';
     } 
     if (Boolean(Number(data.ref_no))==true) { 
        html+='<tr>';
        html+='<td class="right_text_align" colspan="'+(data.discount_exists==1 ? '4' : '3')+'">';
        html += lang('sales_ref_no'); 
        html+='</td>';
        html+='<td class="right_text_align" colspan="1">';
        html+= data,ref_no; 
        html+='</td> </tr>';
    }
    if (data.auth_code !=undefined && Boolean(Number(data.auth_code))) { 
        html+='<tr>';
        html+='<td class="right_text_align" colspan="'+(data.discount_exists ? '4' : '3')+'">';
        html+= lang('sales_auth_code'); 
        html+='</td>';
        html+='<td class="right_text_align" colspan="1">';
        html+=data.auth_code; 
        html+='</td></tr>';
     } 
     html+='<tr>';
	 html+='<td colspan="'+(data.discount_exists ? '5' : '4')+'" align="right">';
	if(data.show_comment_on_receipt==1)
	{
        html+=data.comment ;
	}
							
    html+='</td></tr>';
    $("#details_options").append(html);

}
async function cargar_barcode(data){
     //if (!Boolean(Number(await objAppconfig.item('hide_barcode_on_sales_and_recv_receipt'))) && !data.store_account_payment==1) { 
        if (!data.store_account_payment==1) {   
        var  sale_id=data.sale_id ; 

        /*if(data.mode!=undefined && data.mode=='return') {
            sale_id=lang('items_sale_mode')+data.sale_id;
        } 
        else if(data.mode!=undefined  && data.store_account_payment==1) {
            sale_id= lang('sales_store_account_name')+' '+data.sale_id;
        } 
        else if(data.show_comment_ticket==1) {
            sale_id='BOLETA'+' '+data.sale_id_raw;
        } */
        //let bar ="<img src='".site_url('barcode')+"?barcode=$sale_id&text=".ucfirst(strtolower($this->config->item('sale_prefix')))." $sale_number' />";
     
        JsBarcode("#barcode_img", sale_id, {
            width: 1,
            height: 40,
            displayValue: true
          });
     }else{
        $("#barcode").hide();  
     } 
}
async function gargar_firma(data){
    let html="";
    if(!Boolean(Number(await objAppconfig.item('hide_signature')))) { 		
        let payments = data.payments;	
        for(let i in payments ){
            payment=payments[i];
            if(payment['payment_type'].indexOf(lang('sales_credit'))!==-1){                
                html+= ang('sales_signature')+"--------------------------------- <br />	";
                html+= lang('sales_card_statement');
                break;
            }
        }       
     } else{
        $("#signature").hide();   
     }
}


