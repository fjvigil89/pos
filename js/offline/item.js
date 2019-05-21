class Item {
    constructor() {
    }
    async get_info(id_item) {
      
       
        if (isNaN(id_item) || id_item ==false) {
            return {
                name: "",
                category: "",
                supplier_id: "",
                item_number: "",
                product_id: "",
                description: "",
                size: "",
                colour: "",
                model: "",
                marca: "",
                unit: "",
                tax_included: "",
                cost_price: "",
                unit_price: "",
                items_discount: "",
                costo_tax: "",
                promo_price: "",
                promo_quantity: "",
                start_date: "",
                end_date: "",
                expiration_date: "",
                expiration_day: "",
                reorder_level: "",
                item_id: "",
                allow_alt_description: "",
                is_serialized: "",
                image_id: "",
                override_default_tax: "",
                is_service: "",
                commission_percent: "",
                commission_fixed: "",
                deleted: ""
            };
        }       
        const item = await db1.items.where({item_id: String(id_item)}).first();
        if (item!=undefined) {
            return item;
        }
        else {
            return {
                name: "",
                category: "",
                supplier_id: "",
                item_number: "",
                product_id: "",
                description: "",
                size: "",
                colour: "",
                model: "",
                marca: "",
                unit: "",
                tax_included: "",
                cost_price: "",
                unit_price: "",
                items_discount: "",
                costo_tax: "",
                promo_price: "",
                promo_quantity: "",
                start_date: "",
                end_date: "",
                expiration_date: "",
                expiration_day: "",
                reorder_level: "",
                item_id: "",
                allow_alt_description: "",
                is_serialized: "",
                image_id: "",
                override_default_tax: "",
                is_service: "",
                commission_percent: "",
                commission_fixed: "",
                deleted: ""
            };
        }

    }
    async get_items_by_serial(serial)
    {
        const seriales = await  objAdditional_item_seriales.get_serailes(serial);
        var items =[];
        for (const key in seriales) 
        {
            const _serial =  seriales[key];
            var item = await this.get_info(_serial.item_id);
            item["item_serial"] = _serial.item_serial;
            items.push(item);
        }		
		return items;		
    }
    async get_item_id(item_number) {
        let item = await db1.items.where({item_number: item_number}).first();

        if (item != undefined) {
            return item.item_id;
        }
        item = await db1.items.where({product_id: item_number}).first();
       
        if (item != undefined) {
            return item.item_id;
        }
        var additional_item_id = await this.get_id_item_por_numero(item_number);

        if (Boolean(Number(additional_item_id))) {
            return additional_item_id;
        }

        return false;
    }

    async existe(id_item) {
        if(isNaN(id_item)){
            return false;
        }
        const item = await db1.items.where({item_id: String(id_item)}).first();     
        
        return item != undefined ? true : false;
    }
    async get_tier_price_row(tier_id, item_id) {
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['items_tier_prices'], 'readonly');
        const objectStore = transaction.objectStore('items_tier_prices');
        const tier_price = await objectStore.get([String(tier_id), String(item_id)]);
        return tier_price != undefined ? tier_price : {};
    }
    async  is_item_promo(item_id) {
        today = strtotime(moment().format("YYYY-MM-DD"));;
        item_info = await this.get_info(item_id);
        is_item_promo = await (
            this.get_promo_quantity(item_id) > 0 && (item_info.start_date != null && item_info.end_date != null) && (strtotime(item_info.start_date) <= today && strtotime(item_info.end_date) >= today)
        );

        if (is_item_promo) {
            return true;
        }

        return false;
    }
    async get_promo_quantity(id_item) {
        var query = await this.get_info(id_item);
        if (query != undefined && query.promo_quantity > 0) {
            return query.promo_quantity;
        }
        return false;
    }
    async get_id_item_por_numero(item_number){
        let item = await db1.additional_item_numbers.where({item_number: item_number}).first();

        if (item != undefined) {
            return item.item_id;
        }
        return false;
    } 
    async get_store_account_item_id(name) {
    
       if(name==null){
            name= lang('sales_store_account_payment');
       }
       let result = await db1.items.where({name: name}).first();

        if (result != undefined ) {
            return result.item_id;
        }
        return false;
    }
    async add_items(data, add_all=false){
        try {
            

        } catch (e) {
            throw Error(e);
        }

    }
    async delete_by_deleted(){
        await db1.items.where({deleted:1}).delete();
    }
    async  get_all_by_category(category)
	{        
        let items = await db1.items.where("category").equalsIgnoreCase(category).toArray();
        let data=Array();  
        let arry_tem;     
        items.forEach(function(item) {
            // si va  amostrar imagen  add al arry item.image_id
            arry_tem=Array();
            arry_tem["name"]=item.name;
            arry_tem["item_id"]=item.item_id;
            data.push(arry_tem);          
        });
        items = await db1.item_kits.where("category").equalsIgnoreCase(category).toArray();
        items.forEach(function(item) {
            arry_tem=Array();
            arry_tem["name"]=item.name;
            arry_tem["item_id"]="KIT "+item.item_kit_id;
            data.push(arry_tem);          
        });
        
        return data;
    }
    async  startsWithIgnoreCase(name, cadena,offset=0,limit=50 ) {   
        /*const data = await db.items
        .filter(friend => /cadena/i.test(friend.name))
        .toArray()*/
       return  await db1.items.where(name).startsWithIgnoreCase(cadena).
        offset(offset).limit(limit).toArray();
      
           
    }
    async  get_item_search_suggestions(search,limit=25)
	{
		let suggestions = Array();
        let by_name =await this.startsWithIgnoreCase("name", search,0,limit );		
		let temp_suggestions = Array();		
		by_name.forEach(function (row) {
			if (row.category && row.size)
			{
				temp_suggestions[row.item_id] =  row.name + ' ('+row.category+', '+row.size+')';
			}
			else if (row.category)
			{
				temp_suggestions[row.item_id] =  row.name +' ('+row.category+')';
			}
			else if  (row.size)
			{
				temp_suggestions[row.item_id] =  row.name + ' ('+row.size+')';
			}
			else
			{
				$temp_suggestions[row.item_id] = row.name;				
			}
			
		});
		
        //temp_suggestions.sort();
        for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }		
		let by_item_number= await this.startsWithIgnoreCase("item_number", search,0,limit );	
		
		temp_suggestions = Array();
		by_item_number.forEach(function (row) {		
			temp_suggestions[row.item_id] = row.item_number;
		});		
        //temp_suggestions.sort();
        for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }		
        let by_product_id=  await this.startsWithIgnoreCase("product_id", search,0,limit );
		temp_suggestions = Array();		
		by_product_id.forEach(function (row) {		
			temp_suggestions[row.item_id] = row.product_id;
		});			
        //temp_suggestions.sort();
		for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }	
        let k= suggestions.length-1;       
		for(k;k>=0;k--)
		{
			if (!suggestions[k]['label'])
			{
                delete suggestions[k];				
			}
		}		
		//suggestions = array_values(suggestions);
		
		//only return $limit suggestions
		if(suggestions.length > limit)
		{
			suggestions= suggestions.slice(0, limit);
		}
		return suggestions;

	}


}