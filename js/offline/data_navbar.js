async function carga_location_select(){
   let id_employee=  await get_person_id();
   let info_employee= await objEmployee.get_info(id_employee);
   $("#username").html(info_employee.first_name+" "+info_employee.last_name);
    let ubicacion_id = await get_ubicacion_id();
    let  locations = await objEmployee.get_locations(id_employee);
    let inf_location=null;
    if(locations.length>1){
       $("#div_Locations").show();
       let html='<select name="employee_current_id_location" onchange="cambia_ubicacion(this)" id="employee_current_id_location" class=" form-control"> ';                      
      for(i  in locations )  {
            let row=   locations[i];        
            let selected="";
            inf_location =await objLocation.get_info(row.location_id);
            if(row.location_id==ubicacion_id){
                selected="selected='1'";
            }
            html+=('<option value="'+row.location_id+'" '+selected+'" >'+inf_location.name+'</option>');
        };
       html+=' </select>';
       $("#div_Locations").html(html);
    }else{
        $("#div_Locations").hide();
    }
    let now= moment().format("DD-MM-YYYY HH:mm");
   $("#fecha").html(now);
    
}

async function cambia_ubicacion(){
    let valor = document.getElementById("employee_current_id_location").value;
    await set_ubicacion_id(valor);    
    objEmployee.set_employee_current_register_id(false);
    location.reload(true);
};