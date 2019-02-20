class AppConfig {
    constructor() {
        this.taxes = null;
        this.arraydata = undefined;       
    }
    async cargar_data(){
        //let db = await idb2.open(nombreDB, version);
        await iniciarDB();
        const config = await db1.appconfig.where({id_fila: 1}).first();
       
        this.arraydata = config;        
    }
    async item(key) {        
       if(this.arraydata==undefined){
           await  this.cargar_data();
       }      
       let data = this.arraydata[key];
       return  data != undefined ?data :false;
      
    }    
     async  get(key) {
        return await this.item(key);
    }
    async get_additional_payment_types() {
        var return_ = [];
        let payment_types = await this.get('additional_payment_types');
        if (payment_types) {
            return_ = payment_types.split(",").map(item => item.trim());
        }
        return return_;
    }
}