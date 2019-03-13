class Employee {
    constructor() {
    this. authed_employees =undefined;
    }
    async  tiene_caja(register_id,person_id)
	{		
		let query = await objCajas_empleados.get_caja(register_id,person_id);
        return query==undefined? false :true;
      
    }
    async get_info_by_user_passorwd(username,pass){
        let result = await db1.employees.where({"password_offline": pass,"username":username }).first();
        return result;
    }
	async  tiene_caja_en_tienda(person_id,location_id)
	{		
		let query = await objCajas_empleados.get_cajas_ubicacion_por_persona(person_id,location_id);
		return query.length >0;
	}
    async get_logged_in_employee_info() {
        let person_id = await get_person_id();
        if (person_id != false) {
            return await this.get_info((person_id));
        }
        return false;
    }
     get_logged_in_employee_current_register_id()
	{	
		if (localStorage.getItem('employee_current_register_id')!=null)
		{
			return localStorage.getItem('employee_current_register_id');
		}
		return null;		
    }
    set_employee_current_register_id(register_id)
	{
        localStorage.setItem('employee_current_register_id',register_id);
	}
    async get_info(employee_id) {
       /* const transaction = (db2).transaction(['employees'], 'readonly');
        const objectStore = transaction.objectStore('employees');
        const employee = await objectStore.get(String(employee_id));*/
        const employee = await db1.employees.where({"person_id": String(employee_id)}).first();


        if (employee != undefined) {
            return employee;
        }
        else {
            let person_obj = {
                address_1: "",
                address_2: "",
                city: "",
                comments: "",
                commission_percent: "",
                country: "",
                deleted: "",
                email: "",
                first_name: "",
                id: "",
                image_id: "",
                language: "",
                last_name: "",
                person_id: "",
                phone_number: "",
                state: "",
                zip: ""
            };
            return person_obj;
        }
    }
    async  get_all() {
        //let db = await idb.open(nombreDB, version);
        const employees = await db1.employees.where({"deleted": "0"}).toArray();
        /*const transaction = (db2).transaction(['employees'], 'readonly');
        const objectStore = transaction.objectStore('employees');
        const employees = await objectStore.getAll();*/


        return employees;
    }
    async  is_employee_authenticated(employee_id, location_id) {
        if(this.authed_employees==undefined){
            //let db = await idb.open(nombreDB, version);
            const transaction = (db2).transaction(['employees_locations'], 'readonly');
            const objectStore = transaction.objectStore('employees_locations');
            var index = objectStore.index("location_id");
            var employee = await index.getAll(String(location_id));
            this.authed_employees = [];
            for (i in employee) {
                this.authed_employees[employee[i].employee_id] = true;
            }
        }
        return this.authed_employees[employee_id] != undefined && this.authed_employees[employee_id];
    }
    async  has_module_permission(module_id,person_id)
	{
		//if no module_id is null, allow access
		if(module_id==null)
		{
			return true;
        }
        const query = await db1.permissions.where(
            {
                "module_id": String(module_id),
                "person_id":String(person_id)
            }).first();
        //let db = await idb.open(nombreDB, version);
        /*let transaction = (db2).transaction(['permissions'], 'readonly');
        let objectStore = transaction.objectStore('permissions');
        let query = await objectStore.get([String(module_id),String(person_id)]);	*/	
		return query!=undefined;
    }
    async has_module_action_permission(module_id, action_id, person_id)
	{
		//if no module_id is null, allow access
		if(module_id==null)
		{
            return true;
        }
        //let db = await idb.open(nombreDB, version);
        const query = await db1.permissions_actions.where(
            {
                "module_id": String(module_id),
                "person_id":String(person_id),
                "action_id": String(action_id)
            }).first();

        /*let transaction = (db2).transaction(['permissions_actions'], 'readonly');
        let objectStore = transaction.objectStore('permissions_actions');
        let query = await objectStore.get([String(module_id),String(person_id),String(action_id)]);		*/
		return query!=undefined;
		
    }
    async get_locations(employee_id){
        const data = await db1.employees_locations.where({employee_id:String(employee_id)}).toArray();
        return data;
    }
    async get_logged_in_employee_current_location_id(){      
        let ubicacion_id = await get_ubicacion_id();               
		return ubicacion_id;	      
    }
}