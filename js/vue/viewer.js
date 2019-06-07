var scales_modal = new Vue({
    el: "#app",
    data: {       
        items: [],        
        peso: 0,
        total:0,
        name_item: "",
        unit: "",
        price_tax:0,
        img: BASE_URL + "img/no-photo.jpg"    
    },
    methods: {
        init() {
           
        },
        formatMoney: function(number){           
            return accounting.formatMoney(number, currency_symbol, 2, thousand_separator, decimal_separator);
        }, 
        set_data: function(data)
        {
           this.items = data.items_cart == undefined ? [] : data.items_cart;
           this.peso = data.peso;
           this.price_tax = data.price_tax;
           this.name_item  = data.name == undefined ? "" : data.name;
           this.total = data.total;
           this.unit = data.unit;
           this.img =  this.get_item_img(data.img);
           
        } ,
        get_item_img(img)
        {
            if (img == "" || img == null)
                return BASE_URL + "img/no-photo.jpg"
            else
                return img;
        },
        char_limit : (str, limit, c = "...") =>{

            if (str.length > limit) {
                str = str.substring(0, limit);
                str = str + " " + c;
            }
            return str;
        }
     
    },
    mounted() {
        this.init();   
      
    }
});