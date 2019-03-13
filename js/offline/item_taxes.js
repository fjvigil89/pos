class Item_taxes{
    constructor(){

    }
    async get_info(item_id)
	{
       // var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['item_taxes'], 'readonly');      
        const objectStore = transaction.objectStore('item_taxes');           
        const index = objectStore.index("item_id");
        const item_taxes = await index.getAll(String(item_id));       
		return item_taxes;
	}	
}