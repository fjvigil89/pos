/**
 * Este js contiene todas consultas a la db y APIS de sincronización
 */
const version_db=1;//debe de ser multiplo de 10
const version = version_db*10; //esta es pa idb 
var nombreDB = localStorage.getItem('store_login');
var db2 = null;
const name_obj_item = "items";
const name_obj_item_location = "location_items";
const name_obj_item_taxes = "item_taxes";
const name_obj_item_kits_taxes = "item_kits_taxes";
const name_obj_location_items_taxes = "location_items_taxes";
const name_obj_location_item_kits_taxes = "location_item_kits_taxes";
const name_obj_items_tier_prices = "items_tier_prices";
const name_obj_location_items_tier_prices = "location_items_tier_prices";
const name_obj_location_item_kits_tier_prices = "location_item_kits_tier_prices";
const name_obj_appconfig = "appconfig";
const name_additional_item_numbers = "additional_item_numbers";
const name_obj_Item_kits = "item_kits";
const name_obj_Item_kits_items = "item_kit_items";
const name_obj_location_item_kits = "location_item_kits";
const mame_obj_item_kits_tier_prices = "item_kits_tier_prices";
const name_obj_giftcards = "giftcards";
const name_obj_people = "people";
const name_obj_Customers = "customers";
const name_obj_employees = "employees";
const name_obj_locations = "locations";
const name_obj_employees_locations = "employees_locations";
const name_obj_permissions_actionss = "permissions_actions";
const name_obj_price_tiers = "price_tiers";
const name_obj_points = "points";
const name_obj_permissions = "permissions";
const name_obj_language="language";
const name_obj_Items_subcategory="items_subcategory";
const name_obj_data_sesion="data_sesion";
const name_obj_additional_item_seriales="additional_item_seriales";
const name_obj_cajas_empleados="cajas_empleados";
const name_obj_categories="categories";
const name_obj_sales="sales";
const name_obj_sales_offline="sales_offline";
const name_obj_register_log="register_log";
const name_obj_register="register";
const name_obj_registers_movement="registers_movement";
var last_data_update;
var db1  = new Dexie(nombreDB);
var _contador=0;

iniciarDB();
async function iniciarDB() {
    if(nombreDB!=null){
        _contador++;
        if(_contador==1 && !db1.isOpen()){
            await db1.version(version_db).stores({
                items: '&item_id, product_id, item_number,name,category,deleted,promo_quantity,supplier_id,description,size,colour,model,marca,unit,tax_included,cost_price,unit_price,items_discount,costo_tax,promo_price,start_date,'+
                'end_date,expiration_date,expiration_day,reorder_level,allow_alt_description,is_serialized,image_id,override_default_tax,is_service,commission_percent,commission_fixed,custom1,custom2,custom3,subcategory,update_item',
                location_items:'&[location_id+item_id],location_id,item_id,location,items_discount,cost_price,unit_price,promo_price,start_date,end_date,quantity,quantity_warehouse,quantity_defect,reorder_level,override_default_tax,override_defect,update_item_location',//---
                item_taxes:'&id,item_id,name,percent,update_item_taxes',
                location_items_taxes:'&id,item_id,name,percent,location_id,[tem_id+location_id],update_item_taxes_location',
                location_item_kits_taxes:'id,location_id,item_kit_id,name,[item_kit_id+location_id],percent',
                items_tier_prices:'&[tier_id+item_id],tier_id,item_id,unit_price,percent_off,update_items_tier_price',
                location_items_tier_prices:'[tier_id+item_id+location_id],[item_id+location_id]',
                appconfig:'id_fila',
                additional_item_numbers:'&[item_id+item_number],item_number,item_id',
                item_kits_taxes:'id,item_kit_id,name,percent',
                item_kits:'item_kit_id,&item_kit_number,product_id,name,category,deleted',
                item_kit_items:'&[item_kit_id+item_id+quantity],item_id,item_kit_id',
                location_item_kits:'[location_id+item_kit_id],item_kit_id',
                location_item_kits_tier_prices:'[tier_id+item_kit_id+location_id],[item_kit_id+location_id]',
                item_kits_tier_prices:'[tier_id+item_kit_id],item_kit_id',
                giftcards:'&giftcard_id,giftcard_number,customer_id,deleted,[giftcard_number+deleted],update_giftcard',
                price_tiers:'id,name',
                items_subcategory:'&[item_id+location_id+custom1+custom2],[item_id+custom1+location_id],[item_id+custom2+location_id],[item_id+location_id],[custom1+location_id],[custom2+location_id],[item_id+location_id+custom1+custom2+deleted],item_id,custom1,custom2,location_id,deleted',
                people:'person_id,email,first_name,last_name,image_id,update_people',
                customers:'&id,&person_id,&account_number,phone_number,email,company_name,cc_token,tier_id,deleted,first_name, last_name,balance,credit_limit,update_custome,update_people,is_new, sincronizado',
                employees:'&id,person_id,account_number,deleted,password_offline,username',
                locations:'location_id',
                employees_locations:'[employee_id+location_id],location_id,employee_id',
                permissions_actions:'[module_id+person_id+action_id],person_id,action_id,module_id',
                points:'++id_point,customer_id,points,update_points_customer',
                permissions:'[module_id+person_id],person_id,module_id',
                language:'id_fila',
                data_sesion:'key,value',
                additional_item_seriales:'[item_id+item_serial],item_serial,item_id,location_id',
                cajas_empleados:'[register_id+person_id],person_id,register_id',
                categories:"id_fila,value",
                sales_offline:"++sale_id,sale_time,customer_id,employee_id,sold_by_employee_id,comment,show_comment_on_receipt,payment_type,"+
                "cc_ref_no,auth_code,deleted_by,deleted,ticket_number,invoice_number,is_invoice,suspended,store_account_payment,location_id,"+
                "register_id,tier_id,ntable,transaction_rate,opcion_sale,divisa,synchronized,sales_items,sale_items_taxes,sales_item_kits,sales_item_kits_taxes,sales_payments,dato_imprimir,"+
                "inv_data,id_synchronization,store,token,balance,movement_register,store_account_transaction,mensaje",
                sales:"++sale_id,sale_time,customer_id,employee_id,sold_by_employee_id,comment,show_comment_on_receipt,payment_type,"+
                "cc_ref_no,auth_code,deleted_by,deleted,ticket_number,invoice_number,is_invoice,suspended,store_account_payment,location_id,"+
                "register_id,tier_id,ntable,transaction_rate,opcion_sale,divisa,synchronized",
                sales_items:"[sale_id+item_id+line],sale_id,item_id,line,description,serialnumber,quantity_purchased,item_cost_price,item_unit_price,discount_percent,"+
                "commission,custom1_subcategory,custom2_subcategory,id_tier,numero_cuenta,numero_documento,titular_cuenta,tasa,tipo_documento,fecha_estado,"+
                "tipo_cuenta,transaction_status,comentarios",
                sales_items_taxes:"[sale_id+item_id+line+name+percent],cumulative,sale_id,item_id,name",
                sales_item_kits:"[sale_id+item_kit_id+line],sale_id,item_kit_id,line,description,quantity_purchased,item_kit_cost_price,item_kit_unit_price,discount_percent,commission,id_tier",
                sales_item_kits_taxes:"[sale_id+item_kit_id+line+name+percent],cumulative,sale_id,item_kit_id,name",
                sales_payments:"++payment_id,sale_id,payment_type,payment_amount,truncated_card,card_issuer,payment_date",
                register_log:"++register_log_id,employee_id_open,employee_id_close,register_id,shift_start,shift_end,open_amount,close_amount,cash_sales_amount,deleted,"+
                "created_online,closed_manual,synchronized,id_synchronization,token,mensaje,register_log_id_offline,synchronizations_offline_id,register_log_id_origen",
                register:"register_id,location_id,name,deleted",
                registers_movement:"++register_movement_id,register_log_id,register_date,mount,description,detailed_description,type_movement,mount_cash,categorias_gastos,id_employee,offline"
            });
            if(!db1.isOpen()){
                db1.open().catch(function (e) {
                    log("Error opening database: " + e, "error");
                });
            }
        }
        if(this.db2==null){
            this.db2= await idb.open(nombreDB, version);// para trabajar con idb
        }
    }
}

async function descargar(url_api){
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error ese captura en el catch
        var data = await $.post(url_api);
        data = JSON.parse(data);        
        return data;
    } catch (e) {
        throw Error(e);
    }
}
async function agregar_datos_a_db(obj, data, add_all = true){
    console.log(obj+" descargado, agrgando a la db");
    try {      
        if(add_all){
            await db1[obj].clear();
            await db1[obj].bulkAdd(data);
        }else if(data.length > 0){
          await db1[obj].bulkPut(data);
        }
        return true;
    } catch (e) {
        throw Error(e);
    }  

}
async function descargar_uno(url_items,obj,all=false){
    try {      
       
        var data = await $.post(url_items);
        data = JSON.parse(data);        
        let add_all = all==true ? all: (last_data_update=="0000-00-00 00:00:00"? true : false);
        await  agregar_datos_a_db(obj,data,add_all);   
        return true;      
    } catch (e) {
        throw Error(e);
    }   
    
}

async function descargar_configuraciones_ubicaciones_add_a_db() {
    var data = null;
    try {
        var url_appconfig = SITE_URL + "/sincronizar/backup_appconfig_location";
        var data = await $.post(url_appconfig);
        data = JSON.parse(data);
        await agregar_datos_a_db(name_obj_appconfig,[ data["appconfig"]]);
        await agregar_datos_a_db(name_obj_locations, data["locations"]);
        await agregar_datos_a_db(name_obj_employees_locations, data["employees_locations"]);
        await agregar_datos_a_db(name_obj_permissions_actionss, data["permissions_actions"]);
        await agregar_datos_a_db(name_obj_permissions, data["permissions"]);      
        await agregar_datos_a_db(name_obj_language, [data["language"]]);
        await agregar_datos_a_db(name_obj_categories,[ data["categories"]]);
      
      
        return true;
    } catch (e) {
        throw Error(e);
    }
}
// descargamos los kits, Item_kits_items, y los location_item_kits
async function descargar_kits_add_db() {
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error se captura en el catch
        var data = await $.post(SITE_URL + "/sincronizar/backup_kits_and_mumber_serial/"+last_data_update);
        data = JSON.parse(data);
        let add_all= last_data_update=="0000-00-00 00:00:00"? true : false;
        await agregar_datos_a_db(name_obj_Item_kits, data["item_kits"],add_all);
        await agregar_datos_a_db(name_obj_Item_kits_items, data["item_kits_item"],add_all);
        await agregar_datos_a_db(name_obj_location_item_kits, data["location_item_kits"],add_all);
        await agregar_datos_a_db(name_obj_Items_subcategory, data["items_subcategory"],add_all);
        await agregar_datos_a_db(name_additional_item_numbers, data["additional_item_numbers"],add_all);
        await agregar_datos_a_db(name_obj_additional_item_seriales, data["additional_item_seriales"],true);     

        return true;
    } catch (e) {

        throw Error(e);
    }
}
async function actuliza_register_log(register_log_id_offline,synchronizations_offline_id,register_log_id_origen,data){
    let query=0;
    if(register_log_id_offline > 0 && synchronizations_offline_id > 0){
         query = await db1.register_log.where(
           {
               "register_log_id_offline": register_log_id_offline,
               "synchronizations_offline_id": synchronizations_offline_id,
           }
       ).modify(data);      
   }else{
        query = await db1.register_log.where({"register_log_id_origen": register_log_id_origen}).modify(data);
      
   }
}
async function agregar_register_log(data){
   
 if(data.length>0){
    await db1.register_log.where({synchronized:1}).delete();
    await close_register_log();
    for(let i in data){
        let register= data[i];
        if(!await existe_register_log(register)){   
            register["created_online"]=1;// 1 por que este registro ya exixte en el servidor  
            await db1.register_log.add(register);
        }else{
            let _data={
                "register_log_id_origen": register.register_log_id_origen,
                //"closed_manual":register.closed_manual,
                "created_online":1,
                "employee_id_close": register.employee_id_close,
                "shift_end":register.shift_end,                
            }
            await actuliza_register_log(register.register_log_id_offline,register.synchronizations_offline_id
                ,register.register_log_id_origen, _data)
        }
    } 
 }
}

async function existe_register_log(data){      

    let result=false;
    let query=0;
    if(data["register_log_id_offline"]>0 && data["synchronizations_offline_id"]>0){
          query = await db1.register_log.where(
            {
                "register_log_id_offline": data["register_log_id_offline"],
                "synchronizations_offline_id": data["synchronizations_offline_id"],
            }
        ).first();
        result= query !=undefined ?true: false;
    }else if(!result){
         query = await db1.register_log.where(
            {
                "register_log_id_origen": data["register_log_id_origen"]
            }
        ).first();
        result= query !=undefined ?true: false;
    }
    return result;
   
}
async function close_register_log(){
  
    let query = await db1.register_log.where({ "shift_end": '0000-00-00 00:00:00' }).modify({"shift_end":moment().format("YYYY-MM-DD HH:mm:ss")});

}
async function descargar_register() {
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error se captura en el catch
        var data = await $.post(SITE_URL + "/sincronizar/get_register");
        data = JSON.parse(data);
        await agregar_register_log(data["register_log"]);
        await agregar_datos_a_db(name_obj_register, data["register"]);
        await agregar_datos_a_db(name_obj_cajas_empleados, data["cajas_empleados"]);       
        return true;
    } catch (e) {

        throw Error(e);
    }
}
async function descargar_taxes_y_tier_add_db() {
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error se captura en el catch
        var data = await $.post(SITE_URL + "/sincronizar/backup_taxes_and_tier/"+last_data_update);
        data = JSON.parse(data); 
        let add_all= last_data_update=="0000-00-00 00:00:00"? true : false;
        await  agregar_datos_a_db(name_obj_item_taxes,data["item_taxes"],add_all);
        await  agregar_datos_a_db(name_obj_location_items_taxes,data["item_location_taxes"],add_all);   
        await  agregar_datos_a_db(name_obj_items_tier_prices,data["items_tier_prices"],add_all);   

        await agregar_datos_a_db(name_obj_item_kits_taxes, data["item_kit_taxes"]);
        await agregar_datos_a_db(name_obj_location_item_kits_taxes, data["item_kit_location_taxes"]);
       // await add_a_db(name_obj_items_tier_prices, data["items_tier_prices"]);
        await agregar_datos_a_db(name_obj_location_items_tier_prices, data["location_items_tier_prices"]);
        await agregar_datos_a_db(name_obj_location_item_kits_tier_prices, data["location_item_kits_tier_prices"]);
        await agregar_datos_a_db(mame_obj_item_kits_tier_prices, data["item_kits_tier_prices"]);
        await agregar_datos_a_db(name_obj_price_tiers, data["price_tiers"]);
        return true;
    } catch (e) {
        throw Error(e);
    }
}
async function descargar_giftcard_and_points() {
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error se captura en el catch
        var data = await $.post(SITE_URL + "/sincronizar/backup_giftcard_and_points/"+last_data_update);
        data = JSON.parse(data);
        let add_all= last_data_update=="0000-00-00 00:00:00"? true : false;     
        await agregar_datos_a_db(name_obj_giftcards, data["giftcards"],add_all);
        await agregar_datos_a_db(name_obj_points, data["points"],add_all);
        return true;
    } catch (e) {
        throw Error(e);
    }
}
async function descargar_employee_and_customer() {
    var data = null;
    try {
        // primero se consultan los dato antes de add, si hay error se captura en el catch
        var data = await $.post(SITE_URL + "/sincronizar/backup_employee_and_customers/"+last_data_update);
        data = JSON.parse(data);
        let add_all= last_data_update=="0000-00-00 00:00:00"? true : false;     
        await agregar_datos_a_db(name_obj_Customers, data["customer"],add_all);
        await agregar_datos_a_db(name_obj_employees, data["employee"],add_all);
        return true;
    } catch (e) {
        throw Error(e);
    }
}
/*async function add_a_db(name_objeto, data) {
    try {
        console.log(name_objeto + " Descargados... Agregando a db");
        var transaction = (db2).transaction([name_objeto], 'readwrite');
        var obj = transaction.objectStore(name_objeto);
        obj.clear();
        for (var i in data) {
            await obj.put(data[i]);
        }
        return true;
    } catch (e) {
        throw Error("error: " + name_objeto + " -- " + e);
    }
}*/
/*async function add_categories_a_db(name_objeto, data) {
    try {
        console.log(name_objeto + " Descargados... Agregando a db");
        var transaction = (db2).transaction([name_objeto], 'readwrite');
        var obj = transaction.objectStore(name_objeto);
        obj.clear();
        for (var i in data) {          
            await obj.add([i,data[i]]);
        }
        return true;
    } catch (e) {
        throw Error("error: " + name_objeto + " -- " + e);
    }
}*/
/*async function add_language(data) {
    try {
        console.log(name_obj_language + " Descargados... Agregando a db");
        var transaction = (db2).transaction([name_obj_language], 'readwrite');
        var obj = transaction.objectStore(name_obj_language);
        obj.clear();
        for (var key in data) {
            await obj.add({"key":key,"valor":data[key]});
        }
        return true;
    } catch (e) {
        throw Error("error: " + name_obj_language + " -- " + e);
    }
}*/

 async function sincronizar_ventas(mostrar_dialogo=true, primero_cierra_caja_offline=false, ir_offline=false){
    const objSale= new Sale(),
        objCustomer = new Customer();

    if(mostrar_dialogo == true && primero_cierra_caja_offline == true)
    {
        
        let register_open=  await objSale.get_register_log_open_offline();

        if(register_open.length>0)
        {            
                location.href= SITE_URL +"/sales/closing_all";        
                return 0;
            
        }
    }
    const result1 = await  sincroniza_clientes();
    
    if(!result1)
    {
        dialogo("Error!","Ocurrió un error al sincronizar los nuevos clientes.","red","btn-red",function(){location.reload();});
        return 0;
    }

   var url_api=SITE_URL +"/sincronizar/synchronize_sales";   
   let sales= await objSale.get_sales_no_synchronized();
   let register_log_data=await objSale.get_register_log_no_synchronized();   

   if(sales.length == 0 && register_log_data.length == 0)
   {       
       if(ir_offline)       
        location.href= SITE_URL +"/sales"; 
       
       return 0;
   }
   sales = elimina_datos_no_necesarios(sales);

   let data={sales:sales, register:register_log_data};

   $.ajax({
	type: "POST",
    url:url_api,
    data: {data:JSON.stringify(data)},
    cache: false,
    async:false,
	success: function(result) {
		try{
            (async function(){
                resultado=JSON.parse(result);
                    await cambiar_estado_register_sincronizada(resultado["register"],mostrar_dialogo);
                    await cambiar_estado_sale_sincronizada(resultado["sales"],mostrar_dialogo);     
                    if(ir_offline) {
                        location.href= SITE_URL +"/sales"; 
                    }
                })();
        }catch(e){
            if(mostrar_dialogo)
                alert("Ocurrio un error al sincronizar.."+ e);
                
        }
	},
	error: function() {
        if(mostrar_dialogo)
        dialogo("Error!","Ocurrió un error al sincronizar las ventas, asegúrese haber iniciado sesión en la tienda antes de sincronizar y tener conexión a internet.","red","btn-red",function(){});

    }
}); 

}
async function  sincroniza_clientes()
{

    const objCustomer = new Customer(),
        url_api=SITE_URL +"/sincronizar/synchronize_customers",
        customers = await objCustomer.lits_new();
        
    if(customers.length == 0)
        return true;

    return new Promise(function(resolve, reject) {
        $.ajax({
            type: "POST",
            url:url_api,
            data: {data:JSON.stringify(customers)},
            cache: false,
            async:false,
            success: function(new_data_customers) {
                try{
                    new_data_customers = JSON.parse(new_data_customers);

                    if(new_data_customers.succes == true)
                    {
                        new_data_customers = new_data_customers.data;

                        db1.transaction('rw', db1.sales_offline, db1.customers, async ()=>{

                            await db1.customers.where({is_new:"1"}).delete();
                            await db1.customers.bulkPut(new_data_customers);
                        
                            for (const key in customers)
                            {
                                const new_info =  get_data_cutomer_online(new_data_customers,customers[key].account_number);
                                await db1.sales_offline.where({ customer_id: customers[key].person_id }).modify({customer_id : new_info.person_id});
                            }

                        }).then(() => { 
                            resolve(true);                         
                        }).catch(err => { 
                            //console.error(err.stack);
                            resolve(false); 
                        });
                    }
                    else
                        resolve(false); 
                    
                }catch(e){
                    resolve(false); 
                }
            },
            error: function() {
                resolve(false); 
            }
        });       
    });   
}
function get_data_cutomer_online(customers, account_number)
{    
    for (const key in customers)
    {
        if(customers[key].account_number == account_number)
            return customers[key];
    }

    return false;
}
async function cambiar_estado_register_sincronizada(resultado,mostrar_dialogo){
    let objSale= new Sale();
    let no_sincronizada=false;
    for(let i in resultado ){
        let data =resultado[i];
        if(data.error==false){
            await objSale.update_register_by_id({"synchronized":1,"mensaje":data.mensaje},Number(data.register_log_id));
        }else{
            await objSale.update_register_by_id({"synchronized":0,"mensaje":data.mensaje},Number(data.register_log_id));
            no_sincronizada=true;
        }
    }
}
async function cambiar_estado_sale_sincronizada(resultado,mostrar_dialogo){
    let objSale= new Sale();   
    let no_sincronizada=false;
    for(let i in resultado ){
        let data =resultado[i];
        if(data.error==false){
            await objSale.actualizar_estado(data.sale_id_offline,data.mensaje,1);
        }else{
            await objSale.actualizar_estado(data.sale_id_offline,data.mensaje,0);
            no_sincronizada=true;
        }
    }
    let accion = function(){
        location.reload(true);
    }
    if(no_sincronizada){
        if(mostrar_dialogo)
             dialogo("Error!","Algunas ventas no se sincronizaron","red","btn-red",accion);
    }else if(!no_sincronizada){
        if(mostrar_dialogo)
            dialogo("Éxito!","Sincronización exitosa ","blue","btn-green",accion);

    }

}

function dialogo(title="Error!",content,type="red",btnClass="btn-red",action, text="OK"){
    $.confirm({
        title: title,
        content: content,
        type: type,
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: text,
                btnClass: btnClass,
                action: action
            }
        }
    });
}
function elimina_datos_no_necesarios(data){
    for(i in data){
        delete  data[i]['dato_imprimir'];
    }
    return data;
}
async  function eliminar_ventas_sincronizadas(){
    await db1.sales_offline.where({synchronized:1}).delete();
}
async function eliminar_eliminados(){
    let items_delete= await db1.items.where({deleted:"1"}).toArray(); 
    for(let i in items_delete ){
       
        await  await db1.location_items.where({item_id:items_delete[i].item_id}).delete();
        await  await db1.items_tier_prices.where({item_id:items_delete[i].item_id}).delete();
        await  await db1.additional_item_numbers.where({item_id:items_delete[i].item_id}).delete();
        
    }  
    await  await db1.items.where({deleted:"1"}).delete();
     
    //----------kit
    let kits_delete= await db1.item_kits.where({deleted:"1"}).toArray(); 
    for(let i in kits_delete ){
        await  await db1.item_kit_items.where({item_kit_id:kits_delete[i].item_kit_id}).delete();
        await  await db1.location_item_kits.where({item_kit_id:kits_delete[i].item_kit_id}).delete();

    } 
    await  await db1.item_kits.where({deleted:"1"}).delete(); 
    //-------------------customer and puntos
    let customer_delete= await db1.customers.where({deleted:"1"}).toArray();
    for(let i in customer_delete ){
        await  await db1.points.where({customer_id:customer_delete[i].person_id}).delete();
    } 
    await  await db1.customers.where({deleted:"1"}).delete();

    //--------------empleados
    await  await db1.employees.where({deleted:"1"}).delete();

    //------------items_subcategory
    await  await db1.items_subcategory.where({deleted:"1"}).delete(); 
    
  //  await  await db1.cu.where({deleted:"1"}).delete();
    return true;
}
function limpiar_memoria(){
    localStorage.removeItem("cart");
    localStorage.removeItem("employee_current_register_id");
    return true;
}
function bajar_db(mostrar_dialogo=true) {       
        iniciarDB();
        console.log("descargando db...");
        $("#icon-offline").removeClass('fa-plug');             
                
        $("#icon-offline").addClass("fa-cloud-download");
        (async function () {
            try {
                // dialogo de espera actualizacion BD Offline            
                let accion = function(){
                    location.reload(true);
                }
               if(mostrar_dialogo){
                dialogo("<div id='id_sincronizacion_offline_title'>Cargando...</div>",
                "<div id='id_sincronizacion_offline_contenido'> <center><img src='"+BASE_URL+"/img/loading_sale.gif' alt='' width='30%' ></center><br> Esto podría tardar varios minutos </div>",
                "blue","btn-blue btn_sincronizacion_offline",accion,"Cancelar");
               }
                let temporal = await db1.data_sesion.get("date_synchronization");       
                
                this.last_data_update= temporal!=undefined ?temporal.value :temporal ;
                this.last_data_update=(last_data_update==undefined || last_data_update==null)?"0000-00-00 00:00:00":last_data_update;
                var url_items = SITE_URL + "/sincronizar/backup_items/"+last_data_update;
                var url_data_session = SITE_URL + "/sincronizar/datos_sesion";
                var url_items_locations = SITE_URL + "/sincronizar/backup_location_items/"+last_data_update;
                // var url_customer = SITE_URL + "/sincronizar/backup_customer";
                var url_additional_item_numbers = SITE_URL + "/sincronizar/backup_additional_item_numbers";

                //var url_employee = SITE_URL + "/sincronizar/backup_employee";       
                
                
                await sincronizar_ventas(false);
                await eliminar_ventas_sincronizadas();
                localStorage.setItem("synchronized",0);
                $.when(
                    await descargar_uno(url_items,name_obj_item),
                    await descargar_uno(url_items_locations,name_obj_item_location),
                    await descargar_taxes_y_tier_add_db(),
                    await descargar_kits_add_db(),
                    await descargar_register(),
                    await descargar_giftcard_and_points(),
                    await descargar_employee_and_customer(),
                    await descargar_configuraciones_ubicaciones_add_a_db(),
                    await eliminar_eliminados(),
                    limpiar_memoria(),
                    await descargar_uno(url_data_session,name_obj_data_sesion,true),          
                    
                ).done(function () {
                   // alert("DB ACTUALIZADA CON EXITO");   
                   // dialogo("Éxito!","Sincronización","blue","btn-green",accion);
                    
                    $("#icon-offline").removeClass(' fa-cloud-download');               
                    $("#icon-offline").addClass(" fa-plug");
                    localStorage.setItem("synchronized",1);
                    if(mostrar_dialogo){
                        document.getElementById("id_sincronizacion_offline_title").innerHTML="Sincronización Exitosa!";
                        document.getElementById("id_sincronizacion_offline_contenido").innerHTML="Usted ya puede trabajar en modo Offline";
                        document.getElementsByClassName("btn_sincronizacion_offline")[0].innerHTML="OK";
                    }
                    
                   
                    
                })
                
                console.log("Db actualizada..");
            } catch (e) {
                try{
                    $("#icon-offline").removeClass('fa-cloud-download');      
                    $("#icon-offline").addClass(" fa-plug");
                    //alert("error")
                    if(mostrar_dialogo){
                        document.getElementById("id_sincronizacion_offline_title").innerHTML="<i class='fa fa-warning'></i> Ha ocurrido un error!";
                        document.getElementById("id_sincronizacion_offline_contenido").innerHTML="Compruebe su conexion a internet e intente nuevamente";
                        document.getElementsByClassName("btn_sincronizacion_offline")[0].innerHTML="OK";
                        }
                    console.log(e);
                    
                } catch (e) {
                    if(mostrar_dialogo){
                        location.reload(true);
                    }
                }
               
            }
        })();

}

$(document).ready(function () {
   
    
    $("#btn-offline").click(function(e){
        bajar_db(e);
    })
});