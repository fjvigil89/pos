class Item_location {
    constructor() {

    }
    async save_quantity(quantity, item_id, location_id=false)
	{
		if(!location_id)
		{
			location_id= await objEmployee.get_logged_in_employee_current_location_id();
		}
        try {                  
           
            let query = await db1.location_items.where(
                { 
                    item_id: String(item_id),
                    location_id:String(location_id)
                }
            ).modify({quantity:quantity});     
                      
        } catch (e) {
            return false
        }
        return true;	
		
		
	}

    async get_info(id_item, id_ubicacion = false) {
        if (!id_ubicacion) {
            id_ubicacion = await get_ubicacio_id();
        }

        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['location_items'], 'readonly');
        const objectStore = transaction.objectStore('location_items');
        const item = await objectStore.get([String(id_ubicacion), String(id_item)]);
        if (item != undefined) {
            //Store a boolean indicating if the price has been overwritten
            item.is_overwritten = (item.cost_price !== null ||
                item.unit_price !== null ||
                item.promo_price !== null ||
                await this.is_tier_overwritten(id_item, id_ubicacion));
            return item;
        }
        else {
            var obj = {
                location_id: "",
                item_id: "",
                location: "",
                items_discount: "",
                cost_price: "",
                unit_price: "",
                promo_price: "",
                start_date: "",
                end_date: "",
                quantity: "",
                quantity_warehouse: "",
                quantity_defect: "",
                reorder_level: "",
                override_default_tax: "",
                override_defect: "",
                is_overwritten: false

            }
            return obj;
        }

    }

    async get_tier_price_row(tier_id, item_id, location_id = 0) {
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['location_items_tier_prices'], 'readonly');
        const objectStore = transaction.objectStore('location_items_tier_prices');
        const tier_price = await objectStore.get([String(tier_id), String(item_id), String(location_id)]);
        return tier_price != undefined ? tier_price : {};
    }
    async get_location_quantity(item_id, id_ubicacion=false) {
        if(!id_ubicacion) 
		{
			id_ubicacion= await get_ubicacio_id();
		}

        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['location_items'], 'readonly');
        const objectStore = transaction.objectStore('location_items');
        const item = await objectStore.get([String(id_ubicacion), String(item_id)]);
        if (item != undefined) {
            return item.quantity
        }
        return false;
    }
    
    async  is_tier_overwritten(item_id, location_id) {		
        
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['location_items_tier_prices'], 'readonly');
        const objectStore = transaction.objectStore('location_items_tier_prices');
        var index = objectStore.index("[item_id+location_id]");
        var result = await index.getAll([String(item_id), String(location_id)]);      

        return (result.length>=1);
       
    }
}