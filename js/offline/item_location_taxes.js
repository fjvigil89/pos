class Item_location_taxes{
    constructor(){

    }
    async  get_info(item_id, location_id = false)
	{
		if(!location_id)  
		{
			location_id= await get_ubicacio_id();
		}        
        //var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['location_items_taxes'], 'readonly');
        const objectStore = transaction.objectStore('location_items_taxes');
        var index = objectStore.index("[tem_id+location_id]");
        var result = await index.getAll([String(item_id), String(location_id)]);		
		return result;
	} 
}