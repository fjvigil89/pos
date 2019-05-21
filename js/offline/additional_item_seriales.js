class Additional_item_seriales {
    constructor()
    {
        
    }
    async save_one(item_id, item_serial)
	{		
		if (item_serial!='' &&  item_serial!=null)
		{
            db1.additional_item_seriales.bulkPut([{"item_id": String(item_id),"item_serial":item_serial}]);			
		}		
		return true;
	}
    async delete_serial(item_id,item_serial)
	{
        db1.additional_item_seriales.where({"item_id": String(item_id), item_serial: String(item_serial) }).delete(); 
		return true;
	}

    async get_item_id(item_serial)
	{
        const seriales = await db1.additional_item_seriales.where({item_serial: String(item_serial)}).toArray();
		
		if(seriales.length == 1)
		{
			return seriales.item_id;
		}		
		return false;
    }
    async get_item_serales_unsold(item_id)
    {
        const seriales = await db1.additional_item_seriales.where({item_id: String(item_id)}).toArray();
		return seriales;			
    }
    async get_serailes(item_serial)
    {
        const seriales = await db1.additional_item_seriales.where({item_serial: String(item_serial)}).toArray();
		return seriales;
    }
}