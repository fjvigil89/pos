
function validation_indexedDB_browser(motrar_dialogo=true){
    if (!window.indexedDB) {
        //window.alert("Su navegador no soporta una versión estable de indexedDB. Tal y como las características no serán validas");        
        if(motrar_dialogo){
            alert("Esta versión del navegador no soporta las ventas Offline!");
            window.location ="index.php/home";

        }
        return false;
        
    }else{

        return true;
    }
}
function es_db_actualizada(BASE_URL){
    if(localStorage.getItem("synchronized")!=1){
        /*let accion = function(){
            window.location =BASE_URL+"/php/home";
        }
        dialogo("Error!","La base de dato no está actualizada, por favor sincronícela primero e intente nuevamente .","red","btn-red",accion);
        */
       alert("La base de dato no está actualizada, por favor sincronícela primero e intente nuevamente.");
       window.location ="index.php/home";

    }
}
function es_login(){
    if (sessionStorage.getItem("person_id")!=null && localStorage.getItem("store_login")!=null){
        window.location ="index.php/sales/offline"; 
    }
}
function elimiar_sesion(){
    sessionStorage.removeItem("person_id");
    localStorage.removeItem("store_login");
    localStorage.removeItem("employee_current_register_id");
    localStorage.removeItem("cart");
    localStorage.removeItem("payments");
    localStorage.removeItem("data_imprimir");
    localStorage.setItem("customer",-1);
    localStorage.removeItem("show_comment_ticket");
    localStorage.removeItem("sale_mode");
    localStorage.removeItem("sale_id");
    
    window.location ="index.php/login/login2"; 
    
    
}

function validation_session_employee(){

    if(localStorage.getItem("sales_offline")!=1){
        window.location ="index.php/home";
    }
    if (sessionStorage.getItem("person_id")!=null && localStorage.getItem("store_login")!=null) {
        return true;
    }else{
        window.location ="index.php/login/login2";
    }
    
}
async function iniciar_sesion(user,pass, store){
   // await db1.close(nombreDB);
   let login=false;
   if("store_"+store!=localStorage.getItem('store_login')){
        localStorage.setItem('store_login',"store_"+store);
         nombreDB = localStorage.getItem('store_login');
         db1  = new Dexie(nombreDB);
         _contador=0;        
   }
   await iniciarDB();   
   let employe =await objEmployee.get_info_by_user_passorwd(user,pass);
   if(employe!==undefined){
        sessionStorage.setItem("person_id",employe.person_id);
        sessionStorage.setItem("mostrar_dialogo_internet",1);
        await set_person_id(employe.person_id);
        login=true;
   }
   return login;

}
async function db_existe(db_nombre){
    let existe=false;
    if(db_nombre!=null){
        try{
            existe=  await  Dexie.exists("store_"+db_nombre);
        } catch (e) {
        console.log(e);
        }
    }
    return existe;
    
}
 