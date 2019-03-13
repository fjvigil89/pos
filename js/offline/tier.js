class Tier{
    constructor(){

    }
    async  get_all()
	{
       
        const transaction = (db2).transaction(['price_tiers'], 'readonly');
        const objectStore = transaction.objectStore('price_tiers');
        const tier = await objectStore.getAll();
		
		return tier;
    }
    async get_info(id) {             
        const tier = await db1.price_tiers.where({id: String(id)}).first();
        if (tier!=undefined) {
            return tier;
        }
        else {
            return {id: "", name: ""};
        }
    }
}