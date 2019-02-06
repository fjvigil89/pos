class Item_kit_taxes_finder{
    constructor(){

    }
    async  get_info(item_kit_id)
	{
		var item_kit_location_info =  await objItem_kit_location.get_info(item_kit_id);
		if(Boolean(Number(item_kit_location_info.override_default_tax)))
		{
			return await objItem_kit_location_taxes.get_info(item_kit_id);
		}
		
		var item_kit_info = await objItem_kit.get_info(item_kit_id);

		if(Boolean(Number(item_kit_info.override_default_tax)))
		{
			return objItem_kit_taxes.get_info(item_kit_id);
        }
        
		
		//Location Config
		var default_tax_1_rate =await objLocation.get_info_for_key('default_tax_1_rate');
		var default_tax_1_name = await objLocation.get_info_for_key('default_tax_1_name');
				
		var default_tax_2_rate = await objLocation.get_info_for_key('default_tax_2_rate');
		var default_tax_2_name = await objLocation.get_info_for_key('default_tax_2_name');
		var default_tax_2_cumulative =  await objLocation.get_info_for_key('default_tax_2_cumulative')==1 ? 
		await objLocation.get_info_for_key('default_tax_2_cumulative') : 0;

		var default_tax_3_rate = await objLocation.get_info_for_key('default_tax_3_rate');
		var default_tax_3_name = await objLocation.get_info_for_key('default_tax_3_name');

		var default_tax_4_rate = await objLocation.get_info_for_key('default_tax_4_rate');
		var default_tax_4_name =await objLocation.get_info_for_key('default_tax_4_name');

		var default_tax_5_rate = await objLocation.get_info_for_key('default_tax_5_rate');
		var default_tax_5_name =await objLocation.get_info_for_key('default_tax_5_name');
		var return_=[];
		if (Boolean(Number(default_tax_1_rate)))
		{
			return_.push({
				'id' : -1,
				'item_kit_id' : item_kit_id,
				'name' : default_tax_1_name,
				'percent' : default_tax_1_rate,
				'cumulative' : 0
			});
		}
		
		if (Boolean(Number(default_tax_2_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' : item_kit_id,
				'name' :default_tax_2_name,
				'percent' :default_tax_2_rate,
				'cumulative' :default_tax_2_cumulative
			});
		}
				
		if (Boolean(Number(default_tax_3_rate)))
		{
			return_.push( {
				'id' :-1,
				'item_kit_id' :item_kit_id,
				'name' :default_tax_3_name,
				'percent' : default_tax_3_rate,
				'cumulative' : 0
			});
		}
		
		if (Boolean(Number(default_tax_4_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' : item_kit_id,
				'name' :default_tax_4_name,
				'percent' :default_tax_4_rate,
				'cumulative' : 0
			});
		}
		if (Boolean(Number(default_tax_5_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' :item_kit_id,
				'name' :default_tax_5_name,
				'percent' :default_tax_5_rate,
				'cumulative' : 0
			});
		}	
		
		if (!$.isEmptyObject(return_))
		{
			return return_;
		}
		default_tax_1_rate =  await objAppconfig.item('default_tax_1_rate');
		default_tax_1_name =  await objAppconfig.item('default_tax_1_name');
				
		default_tax_2_rate =  await objAppconfig.item('default_tax_2_rate');
		default_tax_2_name = await objAppconfig.item('default_tax_2_name');
		default_tax_2_cumulative =  await objAppconfig.item('default_tax_2_cumulative')==1 ? await objAppconfig.item('default_tax_2_cumulative') : 0;
		
		default_tax_3_rate =  await objAppconfig.item('default_tax_3_rate');
		default_tax_3_name =  await objAppconfig.item('default_tax_3_name');
		
		default_tax_4_rate =  await objAppconfig.item('default_tax_4_rate');
		default_tax_4_name =  await objAppconfig.item('default_tax_4_name');
		
		default_tax_5_rate =  await objAppconfig.item('default_tax_5_rate');
		default_tax_5_name =  await objAppconfig.item('default_tax_5_name');
		
		return_ = [];
		
		if (Boolean(Number(default_tax_1_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' :item_kit_id,
				'name' :default_tax_1_name,
				'percent' : default_tax_1_rate,
				'cumulative' : 0
			});
		}
		
		if (Boolean(Number(default_tax_2_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' : item_kit_id,
				'name' : default_tax_2_name,
				'percent' : default_tax_2_rate,
				'cumulative' : default_tax_2_cumulative
			});
		}

		if (Boolean(Number(default_tax_3_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' :item_kit_id,
				'name' :default_tax_3_name,
				'percent' :default_tax_3_rate,
				'cumulative' :0
			});
		}

		if (Boolean(Number(default_tax_4_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id' :item_kit_id,
				'name' : default_tax_4_name,
				'percent' :default_tax_4_rate,
				'cumulative' : 0
			});
		}

		if (Boolean(Number(default_tax_5_rate)))
		{
			return_.push( {
				'id' : -1,
				'item_kit_id': item_kit_id,
				'name' :default_tax_5_name,
				'percent' :default_tax_5_rate,
				'cumulative' : 0
			});
		}
		
				
        return return_;
        
	}
}