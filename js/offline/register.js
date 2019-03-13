class Register{
    constructor(){

    }
    async get_register_name(register_id)
	{
		let info = await this.get_info(register_id);		
		if (info && info.name)
		{
			return info.name;
		}		
		return false;
	}
	async exists(register_id)
	{
		let result = await db1.register.where({	register_id: ""+register_id	}).first();		
		return result==undefined? false:true;
	} 
	async count_all(location_id = false)
	{
		if (!location_id)
		{
			location_id=await objEmployee.get_logged_in_employee_current_location_id();
		}
		let result = await db1.register.where(
			{
				location_id: ""+location_id,
				deleted:"0"
			}
		).toArray();		
		return result.length;
	}
	async get_all(location_id = false)
	{
		if (!location_id) {

			location_id=await objEmployee.get_logged_in_employee_current_location_id();
		}
		let result = await db1.register.where(
			{
				location_id: ""+location_id,
				deleted:"0"
			}
		).toArray();			
		return  result;
	}
    async get_info(register_id)
	{
        const register = await db1.register.where({register_id: String(register_id)}).first();		
		if(register!=undefined)
		{
			return register;
		}
		else
		{			
			return {
                register_id:"",
                location_id:"",
                name:false,
                deleted:""
            };
		}
	}
	async get_by_id(register_id)
	{
        const register = await db1.register.where({register_id: String(register_id)}).first();		
		return register;
	}
}