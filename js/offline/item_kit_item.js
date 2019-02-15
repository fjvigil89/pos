class Item_kit_item{

    constructor(){

    }
    async get_kits_have_item(item_id) {
       // var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['item_kit_items'], 'readonly');
        const objectStore = transaction.objectStore('item_kit_items');
        var index = objectStore.index("item_id");
        const result = await index.getAll(String(item_id));
        if (result != undefined) {
            return result;
        }

        return {};
    }
    async get_info(item_kit_id) {
       // var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['item_kit_items'], 'readonly');
        const objectStore = transaction.objectStore('item_kit_items');
        var index = objectStore.index("item_kit_id");
        const result = await index.getAll(String(item_kit_id));

        return result;

    }
}