class Item_kit {
    constructor() {

    }  
   
    async exists(item_kit_id) {
        if(isNaN(item_kit_id)){
            return false;
        }
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['item_kits'], 'readonly');
        const objectStore = transaction.objectStore('item_kits');
        const item = await objectStore.get(String(item_kit_id));

        if (item != undefined) {
            return true;
        }
        return false;

    }
    async  get_item_kit_id(item_kit_number) {
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['item_kits'], 'readonly');
        const objectStore = transaction.objectStore('item_kits');
        var index = objectStore.index("item_kit_number");
        var result = await index.get(String(item_kit_number));
        if (result != undefined) {
            return result.item_kit_id;
        }
        index = objectStore.index("product_id");
        result = await index.get(String(item_kit_number));
        if (result != undefined) {
            return result.item_kit_id;
        }

        return false;
    }
    async  get_item_kit_by_id(item_kit_id) {
        //var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['item_kits'], 'readonly');
        const objectStore = await transaction.objectStore('item_kits');
        const item = await objectStore.get(String(item_kit_id));
        return item;

    } 
    
    async  get_tier_price_row(tier_id,item_kit_id)
	{
        //var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['item_kits_tier_prices'], 'readonly');
        const objectStore = await transaction.objectStore('item_kits_tier_prices');
        const tier_price = await objectStore.get([String(tier_id),String(item_kit_id)]);
        return tier_price != undefined ? tier_price : {};

		
	
	}  
    async get_info(item_kit_id) {
        var item_obj = {
            "category": "",
            "commission_fixed": "",
            "commission_percent": "",
            "cost_price": "",
            "deleted": "0",
            "description": "",
            "item_kit_id": "",
            "item_kit_number": "",
            "name": "",
            "override_default_tax": "",
            "product_id": "",
            "tax_included": "",
            "unit_price": ""
        };
        //If we are NOT an int return empty item
        if (isNaN(item_kit_id) || item_kit_id == false) {
            return item_obj;
        }
        //KIT #
        var pieces = ("" + item_kit_id).split(' ');
        if (pieces.length == 2) {
            item_kit_id = parseInt(pieces[1]);
        }
        var query = await this.get_item_kit_by_id(item_kit_id);

        if (query != undefined) {
            return query;
        }
        else {
            return item_obj;
        }
    }
    async  startsWithIgnoreCase(name, cadena,offset=0,limit=50 ) {   
        return  await db1.item_kits.where(name).startsWithIgnoreCase(cadena).
        offset(offset).limit(limit).toArray();
      
           
    }
    async  get_item_kit_search_suggestions(search, limit=25)
	{
		let suggestions = Array();

		let by_name =await this.startsWithIgnoreCase("name", search,0,limit );		
		let temp_suggestions = Array();	
		
		by_name.forEach(function (row) 		{
			if (row.category)
			{
				temp_suggestions['KIT '+row.item_kit_id] =  row.name + ' ('+row.category+')';
			}
			else
			{
				$temp_suggestions['KIT '+row.item_kit_id] = row.name;
			}
        });
        for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }
		let by_item_kit_number =await this.startsWithIgnoreCase("item_kit_number", search,0,limit );		
		

		temp_suggestions = Array();
		
		by_item_kit_number.forEach(function (row) 	
		{
			temp_suggestions['KIT '+row.item_kit_id] = row.item_kit_number;
		});		
		
		for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }

        let by_product_id =await this.startsWithIgnoreCase("item_kit_number", search,0,limit );				
		temp_suggestions = Array();
		
		by_product_id.forEach(function (row) 	
		{
			temp_suggestions['KIT '+row.item_kit_id] = row.product_id;
		});
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
		//only return $limit suggestions
		if(suggestions.length > limit)
		{
			suggestions= suggestions.slice(0, limit);
		}
		return suggestions;
		
	}
	
   

}
