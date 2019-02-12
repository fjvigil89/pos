class Customer{
    constructor(){

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
    async account_number_exists(account_number){
        const query = await db1.customers.where({account_number: account_number}).first();

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
    async get_customer_search_suggestions(search,limit=25)
	{
		let suggestions = Array();
       let by_name= await  db1.customers.where("first_name").startsWithIgnoreCase(search).or("last_name").
        startsWithIgnoreCase(search).offset(0).limit(limit).toArray();
      let temp_suggestions = Array();
		
		by_name.forEach(function (row) {
			temp_suggestions[row.person_id] = row.last_name+', '+row.first_name;
		});
		
		for(i in temp_suggestions){
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
		
		for(i in temp_suggestions){
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
		
		for(i in temp_suggestions){
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
		
		for(i in temp_suggestions){
            let arr=Array();
            arr["value"]=i;
            arr["label"]=temp_suggestions[i];
            suggestions.push(arr);
        }
		let k= suggestions.length-1;       
		for(k;k>=0;k--)
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