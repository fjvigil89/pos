class Customer{
    constructor(){

    }
    async lits_new()
    {
        const data = await db1.customers.where({is_new: "1"}).toArray();	

		return data; 
    }
    async  get_info_by_account_number(account_number)
	{
        const data = await db1.customers.where({account_number: account_number}).first();	

		return data;		
    }
    async  get_info(customer_id)
	{
        //let db = await idb.open(nombreDB, version);
        const transaction = (db2).transaction(['customers'], 'readonly');
        const objectStore = transaction.objectStore('customers');        
       
        var index = objectStore.index("person_id");
        var customer = await index.get(String(customer_id));
        if (customer != undefined) {
            return customer;
        }else{
		let 	person_obj={
                account_number :"",
                address_1:"",
                address_2:"",               
                balance:"",                
                card_issuer:"",
                cc_preview:"",
                cc_token:"",
                city:"",
                comments:"",
                company_name:"",
                country:"",
                credit_limit:"",
                deleted:"",
                email:"",
                first_name:"",
                id:"",
                image_id:"",
                last_name:"",
                person_id:"",
                phone_number:"",
                state:"",
                taxable:"",
                tier_id:"",
                zip:"",
            }		
			
			return person_obj;
		}
    }
    async save(person_id = -1, data_customer)
    {
        var save = true,
            now = moment().format("YYYY-MM-DD HH:mm:ss");

        if(person_id <= 0)
        {
            if(await this.account_number_exists(data_customer.account_number))        
                throw("Cliente ya registrado '"+data_customer.account_number+"'");
           
            var ids = await this.get_maxi_ids();
            var data ={
                account_number: data_customer.account_number,
                address_1:  data_customer.address_1,
                address_2: "",
                balance:  "0",
                card_issuer: null,
                cc_preview: null,
                cc_token: null,
                city: data_customer.city,
                comments: "",
                company_name: "",
                country: data_customer.country,
                credit_limit: null,
                deleted: "0",
                email: data_customer.email,
                first_name: data_customer.first_name,
                id: ""+ids["id"],
                image_id: null,
                last_name:data_customer.last_name,
                person_id: ""+ids["person_id"],
                phone_number: data_customer.phone_number,
                state:data_customer.state,
                taxable: "1",
                is_new:"1",
                tier_id: data_customer.tier_id == 0 ? null: data_customer.tier_id,
                update_customer: now,
                update_people: now,
                zip: ""
            }
            var result = await db1.customers.add(data);
            
            if(result <= 0)
                save = false;
    
        }
        else if(await this.exists(person_id))
        {
            const info = await this.get_info_by_account_number(data_customer.account_number);

            if(info != undefined && person_id != info.person_id)
                    throw("Cliente ya registrado '"+data_customer.account_number+"'");

            data =  await this.get_info(person_id); 

            data["account_number"]= data_customer.account_number;
            data["address_1"]=  data_customer.address_1;          
            data["city"]= data_customer.city;          
            data["country"]= data_customer.country;         
            data["email"]= data_customer.email;
            data["first_name"]= data_customer.first_name;          
            data["last_name"]=data_customer.last_name;          
            data["phone_number"]= data_customer.phone_number;
            data["state"]=data_customer.state;          
            data["tier_id"]= data_customer.tier_id == 0 ? null:data_customer.tier_id;
            data["update_customer"]= now;
            data["update_people"]= now;

            result = await db1.customers.where({ person_id: String(person_id) }).modify(data);
          
        }
        return save;
    }
    async account_number_exists(account_number){
        const query = await db1.customers.where({account_number: account_number}).first();

        return(query != undefined) ;
    }
    async email_exists(email){
        const query = await db1.customers.where("email").equalsIgnoreCase(email).first();

        return(query != undefined) ;
    }
    async  exists(person_id)
	{
        const query = await db1.customers.where({person_id: person_id}).first();		
		return (query != undefined);
	}
    async customer_id_from_account_number(account_number){
        const query = await db1.customers.where({account_number: account_number}).first();
        
		if (query != undefined)
		{
			return query.person_id;
		}		
		return false;

    }
    async  get_info_points(customer_id)
	{      
        
        const query = await db1.points.where({customer_id: ""+customer_id}).first();        
		if(query!=undefined)
		{
			return query.points;
		}
		else
		{
			return 0;
		}
    }
    async  startsWithIgnoreCase(name, cadena,offset=0,limit=50 ) {   
        return  await db1.customers.where(name).startsWithIgnoreCase(cadena).
        offset(offset).limit(limit).toArray();
      
           
    }
    async get_maxi_ids()
    {
        const customers =  await  db1.customers.toArray();
        var id = 0, person_id = 0, customer = null;

        for(var key in customers)
        {
            customer = customers[key];
            
            if(Number(customer.id) > id)
                id = Number(customer.id);
            if(Number(customer.person_id) > person_id)
                person_id = Number(customer.person_id);

        }
       return {id: id+1 ,person_id: person_id+1};
       
    }
    async get_customer_search_suggestions(search,limit=25)
	{
		let suggestions = Array();
        let by_name= await  db1.customers.where("first_name").startsWithIgnoreCase(search).or("last_name").
        startsWithIgnoreCase(search).offset(0).limit(limit).toArray();
        
        let temp_suggestions = Array();
		
		by_name.forEach(function (row) {
			temp_suggestions[row.person_id] = row.last_name+', '+row.first_name;
		});
		
        for(i in temp_suggestions)
        {
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }
		
		let by_account_number= await this.startsWithIgnoreCase("account_number", search,0,limit );
		
		temp_suggestions = Array();
		
		by_account_number.forEach(function (row) 
		{
			temp_suggestions[row.person_id] = row.account_number;
		});
		
        for(i in temp_suggestions)
        {
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }		
		
		let by_email = await this.startsWithIgnoreCase("email", search,0,limit );
		
		temp_suggestions = Array();
		
		by_email.forEach(function (row) 
		{
			temp_suggestions[row.person_id] = row.email;
		});
		
        for(i in temp_suggestions)
        {
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }		
		
		 let by_phone_number = await this.startsWithIgnoreCase("phone_number", search,0,limit );		
		
		temp_suggestions = Array();
		
		by_phone_number.forEach(function (row) 
		{
			temp_suggestions[row.person_id] = row.phone_number;
		});
		
		for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }	
		
		let by_company_name= await this.startsWithIgnoreCase("company_name", search,0,limit );	
		
		temp_suggestions = Array();
		
		by_company_name.forEach(function (row) 
		{
			temp_suggestions[row.person_id] = row.company_name;
		});
		
        for(i in temp_suggestions)
        {
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }
		let k= suggestions.length-1;       
		for(k; k >= 0; k--)
		{
			if (!suggestions[k]['label'])
			{
                delete suggestions[k];				
			}
		}
		
		if(suggestions.length > limit)
		{
			suggestions= suggestions.slice(0, limit);
		}
		return suggestions;       

	}
	
}