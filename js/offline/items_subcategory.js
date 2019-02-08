class Items_subcategory {
    constructor() {

    }
    async  get_info(item_id, location_id = false, custom1, custom2)
    {
        if (!location_id) {
            location_id = await objEmployee.get_logged_in_employee_current_location_id();
        }
        let query = await db1.items_subcategory.where(
            {
                item_id: String(item_id),
                location_id: String(location_id),
                custom1:custom1==null?"":custom1,
                custom2:custom2==null?"":custom2
            }).first();       
        if (query!=undefined) {
            return query;
        } else {
            return {item_id:"",location_id:"",custom1:"",custom2:"",quantity:0,deleted:""};
        }

    }
   async  save_quantity(quantity, item_id, location_id = false, custom1, custom2)
    {
        if (!location_id) {
            location_id = await objEmployee.get_logged_in_employee_current_location_id();
        }
        try{
            let query = await db1.items_subcategory.where(
                { 
                    item_id: String(item_id),
                    location_id:String(location_id),
                    custom1:custom1==null?"":custom1,
                    custom2:custom2==null?"":custom2
                }
            ).modify({quantity:quantity});
        }catch(e){
            return false;
        }
        return true;
        
    }
    async  get_custom1(item_id, location_id) {
        if (!location_id) {
            location_id = await get_ubicacio_id();
        }
        const subcategory = await db1.items_subcategory.where(
            {
                item_id: String(item_id),
                location_id: String(location_id)
            }).toArray();    
     
        if (subcategory != undefined) {
            let array = Array();
            for (i in subcategory) {
                array[subcategory[i].custom1] = subcategory[i].custom1;
            }
            return array;
        }

        return [];

    }
    async get_quantity(item_id, location_id = false, custom1, custom2) {
        if (!location_id) {
            location_id = await get_ubicacio_id();
        }
        const subcategory = await db1.items_subcategory.where(
            {
                item_id: String(item_id),
                location_id: String(location_id),
                custom1:custom1==null?"":custom1,
                custom2:custom2==null?"":custom2,
                deleted:"0"
            }).first();   
        if (subcategory != undefined) {
            return subcategory.quantity;
        }
        return 0;
    }
    async get_custom2(item_id, location_id = false, custom1) {
        if (!location_id) {
            location_id = await get_ubicacio_id();
        }
        const subcategory = await db1.items_subcategory.where(
            {
                item_id: String(item_id),
                location_id: String(location_id),
                custom1:custom1==null?"":custom1
            }).toArray();
       
        return subcategory;

    }
}