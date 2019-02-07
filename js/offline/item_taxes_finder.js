class Item_taxes_finder {
	constructor() {

		this.default_tax_1_rate = null;
		this.default_tax_1_name = null;

		this.default_tax_2_rate = null;
		this.default_tax_2_name = null;

		this.default_tax_2_cumulative = null;
		this.default_tax_3_rate = null;
		this.default_tax_3_name = null;

		this.default_tax_4_rate = null;
		this.default_tax_4_name = null;

		this.default_tax_5_rate = null;
		this.default_tax_5_name = null;

		//--appconfig---
		this.default_tax_1_rate2 = null;
		this.default_tax_1_name2 = null;

		this.default_tax_2_rate2 = null;
		this.default_tax_2_name2 = null;
		this.default_tax_2_cumulative2 = null;

		this.default_tax_3_rate2 = null;
		this.default_tax_3_name2 = null;

		this.default_tax_4_rate2 = null;
		this.default_tax_4_name2 = null;

		this.default_tax_5_rate2 = null;
		this.default_tax_5_name2 = null;
	}
	async  get_info(item_id) {
		var item_location_info = await objItem_location.get_info(item_id);
		if (Boolean(Number(item_location_info.override_default_tax))) {

			return await objItem_location_taxes.get_info(item_id);
		}

		var item_info = await objItem.get_info(item_id);

		if (Boolean(Number(item_info.override_default_tax))) {
			return objItem_taxes.get_info(item_id);
		}

		var return_ = [];

		//Location Config
		if (this.default_tax_1_rate == null) {
			let info_location = await objLocation.get_info(await get_ubicacio_id());

			this.default_tax_1_rate = info_location['default_tax_1_rate'];
			this.default_tax_1_name = info_location['default_tax_1_name'];

			this.default_tax_2_rate = info_location['default_tax_2_rate'];
			this.default_tax_2_name = info_location['default_tax_2_name'];

			this.default_tax_2_cumulative = info_location['default_tax_2_cumulative'] == 1 ?
				info_location['default_tax_2_cumulative'] : 0;


			this.default_tax_3_rate = info_location['default_tax_3_rate'];
			this.default_tax_3_name = info_location['default_tax_3_name'];

			this.default_tax_4_rate = info_location['default_tax_4_rate'];
			this.default_tax_4_name = info_location['default_tax_4_name'];

			this.default_tax_5_rate = info_location['default_tax_5_rate'];
			this.default_tax_5_name = info_location['default_tax_5_name'];
			//------------


		}

		if (Boolean(Number(this.default_tax_1_rate))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_1_name,
				'percent': this.default_tax_1_rate,
				'cumulative': 0
			});
		}

		if (Boolean(Number(this.default_tax_2_rate))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_2_name,
				'percent': this.default_tax_2_rate,
				'cumulative': this.default_tax_2_cumulative
			});
		}

		if (Boolean(Number(this.default_tax_3_rate))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_3_name,
				'percent': this.default_tax_3_rate,
				'cumulative': 0
			});
		}


		if (Boolean(Number(this.default_tax_4_rate))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_4_name,
				'percent': this.default_tax_4_rate,
				'cumulative': 0
			});
		}


		if (Boolean(Number(this.default_tax_5_rate))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_5_name,
				'percent': this.default_tax_5_rate,
				'cumulative': 0
			});
		}

		if (!$.isEmptyObject(return_)) {
			return return_;
		}

		//Global Store Config
		if (this.default_tax_1_rate2 == null) {
			this.default_tax_1_rate2 = await objAppconfig.item('default_tax_1_rate');
			this.default_tax_1_name2 = await objAppconfig.item('default_tax_1_name');

			this.default_tax_2_rate2 = await objAppconfig.item('default_tax_2_rate');
			this.default_tax_2_name2 = await objAppconfig.item('default_tax_2_name');
			this.default_tax_2_cumulative2 = await objAppconfig.item('default_tax_2_cumulative') == 1 ?
				await objAppconfig.item('default_tax_2_cumulative') : 0;

			this.default_tax_3_rate2 = await objAppconfig.item('default_tax_3_rate');
			this.default_tax_3_name2 = await objAppconfig.item('default_tax_3_name');

			this.default_tax_4_rate2 = await objAppconfig.item('default_tax_4_rate');
			this.default_tax_4_name2 = await objAppconfig.item('default_tax_4_name');

			this.default_tax_5_rate2 = await objAppconfig.item('default_tax_5_rate');
			this.default_tax_5_name2 = await objAppconfig.item('default_tax_5_name');
		}


		return_ = [];

		if (Boolean(Number(this.default_tax_1_rate2))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_1_name2,
				'percent': this.default_tax_1_rate2,
				'cumulative': 0
			});
		}

		if (Boolean(Number(this.default_tax_2_rate2))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_2_name2,
				'percent': this.default_tax_2_rate2,
				'cumulative': this.default_tax_2_cumulative2
			});
		}

		if (Boolean(Number(this.default_tax_3_rate2))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_3_name2,
				'percent': this.default_tax_3_rate2,
				'cumulative': 0
			});
		}

		if (Boolean(Number(this.default_tax_4_rate2))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_4_name2,
				'percent': this.default_tax_4_rate2,
				'cumulative': 0
			});
		}

		if (Boolean(Number(this.default_tax_5_rate2))) {
			return_.push({
				'id': -1,
				'item_id': item_id,
				'name': this.default_tax_5_name2,
				'percent': this.default_tax_5_rate2,
				'cumulative': 0
			});
		}


		return return_;

	}
}