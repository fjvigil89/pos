class Item_kit_location_taxes{
    constructor(){

    }
    async  get_info(item_kit_id, location_id = false)
	{
		if(!location_id)
		{
			location_id= await get_ubicacio_id();
        }        
       // var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['location_item_kits_taxes'], 'readonly');
        const objectStore = transaction.objectStore('location_item_kits_taxes');
        var index = objectStore.index("[item_kit_id+location_id]");
        var result = await index.getAll([String(item_kit_id), String(location_id)]);		
		return result;
        
		
	}
}