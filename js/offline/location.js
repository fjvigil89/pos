class Location {
    constructor() {
        this.location=null;
    }
    async get_info(location_id) {
        const transaction = db2.transaction(['locations'], 'readonly');
        const objectStore = transaction.objectStore('locations');
        const location = await objectStore.get(String(location_id));

        if (location != undefined) {
            return location;
        }
        else {

          let   location_obj = {
                address: "",
                default_tax_1_name: "",
                default_tax_1_rate: "",
                default_tax_2_cumulative: "",
                default_tax_2_name: "",
                default_tax_2_rate: "",
                default_tax_3_name: "",
                default_tax_3_rate: "",
                default_tax_4_name: "",
                default_tax_4_rate: "",
                default_tax_5_name: "",
                default_tax_5_rate: "",
                deleted: "",
                email: "",
                enable_credit_card_processing: "",
                fax: "",
                location_id: "",
                mailchimp_api_key: "",
                merchant_id: "",
                merchant_password: "",
                name: "",
                phone: "",
                receive_stock_alert: "",
                stock_alert_email: "",
                timezone: ""
            };
            return location_obj;
        }
    }
    async get_info_for_key(key) 
    {
        if(this.location==null){
            var location_id = await get_ubicacio_id();
            this.location = await this.get_info(location_id);
        }
        return this.location[key];
    }
}