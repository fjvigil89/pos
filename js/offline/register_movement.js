class Register_movement {
    constructor() {
    }
    async save(cash, description=" ", register_id = false, valid_greater_than_zero_cash = true,categorias_gastos="",id_employee=false)
	{  
		if(id_employee==false){
			id_employee= (await objEmployee.get_logged_in_employee_info()).person_id;
		}
		let register_cash = await objSale.get_current_register_log(register_id); //Obtengo la caja actual abierta
  
		if (register_cash!=undefined) { 
			var type_movement        = 1;
			var current_cash_amount  = register_cash.cash_sales_amount; // Monto efectivo caja actual
			var register_log_id      = register_cash.register_log_id;
			var new_cash_amount      = Number(current_cash_amount )+Number(cash); //Sumar o restar a la caja actual el efectivo

			if (new_cash_amount < 0 ){
				new_cash_amount = 0;
			}
			if (cash < 0) { //Es una salida sino una entrada
				var cash = Math.abs(cash);
				var type_movement = 0;
			}
			if (cash > 0 && valid_greater_than_zero_cash || !valid_greater_than_zero_cash) {
				
				var register_movement = {
					'register_log_id' :register_log_id,
					'register_date'   :  moment().format("YYYY-MM-DD HH:mm:ss"),
					'mount'           :cash,
					'description'     :description,
					'type_movement'   : type_movement,
					'mount_cash'      : new_cash_amount,
					"categorias_gastos": categorias_gastos,
					"id_employee": id_employee
				};
				let query=0;
				let result = await db1.registers_movement.add(register_movement);
				if(result>0){
					let data_tem = {};
					data_tem['cash_sales_amount'] = new_cash_amount;
					data_tem["synchronized"]=0;
					 query = await db1.register_log.where({ register_log_id: Number(register_log_id) }).modify(data_tem);
					
				}	
				return true;
			} 
			
			return false;
		}
	}
}