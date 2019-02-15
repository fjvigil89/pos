async function get_ubicacio_id(){
    let ubicacion = await get_data_por_key("ubicacion_id");
    if (!$.isNumeric(ubicacion)) {
        alert("Debe de seleccionar una tienda");
       // location.reload();
    } else{
        ubicacion= Number(ubicacion);
    }
    return ubicacion;
}
async function get_data_por_key(key){
    const data = await db1.data_sesion.get(key);       
    return data!=undefined ?data.value :data ;

}
async function set_person_id(person_id) {
    await  set_data_por_key("person_id", person_id);

}
async function get_ubicacion_id(){
    let ubicacion_id = await get_data_por_key("ubicacion_id");
    if (!$.isNumeric(ubicacion_id)) {
        alert("Tenda  no éxiste");
        return false;
    } else{
        person_id= Number(ubicacion_id);
    }
    return ubicacion_id;
}
async function get_person_id(){  

    let person_id = await get_data_por_key("person_id");
    if (!$.isNumeric(person_id)) {
        alert("Empleado no existe");
        return false;
    } else{
        person_id= Number(person_id);
    }
    return person_id;
}
async function set_ubicacion_id(id_ubicacion){
   await  set_data_por_key("ubicacion_id", id_ubicacion);
}
async function set_data_por_key(key, value){
    await db1.data_sesion.put({
        key: key,
        value: value
    });

}
