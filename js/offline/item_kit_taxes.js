class Item_kit_taxes{
    constructor(){

    }
    async get_info(item_kit_id)
	{
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['item_kits_taxes'], 'readonly');
        const objectStore = transaction.objectStore('item_kits_taxes');
        var index = objectStore.index("item_kit_id");
        var result = await index.getAll(String(item_kit_id)); 

		return result;
	}
}