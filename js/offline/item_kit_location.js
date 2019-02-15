class Item_kit_location {

    constructor() {

    }
    async get_tier_price_row(tier_id, item_kit_id, location_id) {
        //var db = await idb.open(nombreDB, version);
        const transaction = db2.transaction(['location_item_kits_tier_prices'], 'readonly');
        const objectStore = await transaction.objectStore('location_item_kits_tier_prices');
        const tier_price = await objectStore.get([String(tier_id), String(item_kit_id), String(location_id)]);

        return tier_price != undefined ? tier_price : {};
    }
    async get_info(item_kit_id, location = false) {
        if (!location) {
            location = await get_ubicacio_id();
        }
        //var db = await idb2.open(nombreDB, version);
        const transaction = (db2).transaction(['location_item_kits'], 'readonly');
        const objectStore = transaction.objectStore('location_item_kits');
        const row = await objectStore.get([String(location), String(item_kit_id)]);
        if (row != undefined) {
            //Store a boolean indicating if the price has been overwritten
            row.is_overwritten = (row.cost_price != null ||
                row.unit_price != null ||
                await this.is_tier_overwritten(item_kit_id, location)
            );
            return row;
        }
        else {
            var obj = {
                "location_id": "",
                "item_kit_id": "",
                "unit_price": "",
                "cost_price": "",
                "override_default_tax": "",
                "is_overwritten": false
            }
            return obj;
        }

        
    }
    async  is_tier_overwritten(item_kit_id, location_id) {
        //var db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['location_item_kits_tier_prices'], 'readonly');
        const objectStore = transaction.objectStore('location_item_kits_tier_prices');
        var index = objectStore.index("[item_kit_id+location_id]");
        var result = await index.getAll([String(item_kit_id), String(location_id)]);

        return (result.length >= 1);
    }
}