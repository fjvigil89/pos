class Cajas_empleados{
    constructor() {
             
    }
    async  get_caja(register_id,person_id){   
        let reg= await objRegister.get_by_id(register_id);
        if(reg==undefined || reg.deleted==1) return undefined;
        let result = await db1.cajas_empleados.where(
			{
				register_id: ""+register_id,
				person_id:""+person_id
			}
        ).first();             
        return result;
     }
     async get_cajas(){       
        let result = await db1.cajas_empleados.toArray();
        for(i in result){
            let reg= await objRegister.get_by_id(result[i].register_id);
            if(reg.deleted==1){
                delete result[i];
            }
        }
        return result;
     }
     async get_caja_ubicacion_por_persona(register_id,person_id,location_id){
       
        let result = await db1.cajas_empleados.where(
			{
				register_id: ""+register_id,
				person_id:""+person_id
			}
        ).first();
        let reg= await objRegister.get_by_id(result[i].register_id);
        if(reg==undefined || reg.deleted==1 || location_id!=reg.location_id ){
             return undefined;
        }
         else{            
            result[i]["name"]= reg.name
             result[i]["location_id"] =reg.location_id;            
        }
        return result;
     }
     async get_cajas_ubicacion_por_persona(person_id,location_id){      
        let result = await db1.cajas_empleados.where({person_id:""+person_id}).toArray();
        for(let i in result){
            let reg= await objRegister.get_by_id(result[i].register_id);
            if(reg==undefined || reg.deleted==1 || reg.location_id != location_id){
                delete result[i];
            }else{
                result[i]["name"]= reg.name
                result[i]["location_id"] =reg.location_id;
            }
        }
        return result;      
     }
    
}