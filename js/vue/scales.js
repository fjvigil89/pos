Vue.component("category-item", {
    props: ["category", "index"],
    template: `    
        <div v-on:click="$emit('click',index)" class="thumbnail" style=" min-height: 82px; max-height: 82px; margin:2px">
            <img style="width:90%; height:50px;" v-bind:src="get_category_img(category)" v-bind:alt="category.name" class="img-rounded">
            <h5 style="margin:1px">{{get_char_limit(category.name,20)}} </h5>
        </div>    
    `,
    methods: {
        get_category_img(category)
        {
            if (category.img == "" || category.img == null)
                return BASE_URL + "img/no-photo.jpg"
            else
                return BASE_URL + path_img +"/"+category.img;
        },
        get_char_limit(string, limit, c){
            return char_limit(string,limit,c)
        }
            
    }, 
});


Vue.component("item-info", {
    props: ["item", "index"],
    template: ` 
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2 " style="padding:2px" v-on:click="$emit('click',item)"  @dblclick="$emit('add',item)">   
            <div class="thumbnail text-center" style=" min-height: 108px; max-height: 108px; margin:2px;">
                <img  style="width:90%; height:60px;" :src="get_item_img(item)" :alt="item.name"
                    class="img-rounded">
                <p style="margin:1px">{{get_char_limit(item.name,16)}}</p>
            </div>  
        </div>  
    `,
    data:{
        counter: 0
    },
    methods: {
        get_item_img(item)
        {
            if (item.image_src == "")
                return BASE_URL + "img/no-photo.jpg"
            else
                return item.image_src;
        },
        get_char_limit(string, limit, c){
            return char_limit(string,limit,c)
        }
            
    }, 
});

var scales = new Vue({
    el: "#app-scales",
    data: {
        cotegories: [],
        items: [],
        items_cache:{},
        cart:[],
        item_to_sell:{
            price_tax :0,
            name:"",            
            total:0,
            unit:""
        },
        peso: isNaN(Number("0"+localStorage.getItem("peso"))) == false ? Number("0"+localStorage.getItem("peso")) : 0,
        category :"",
        pagination: "",
        focus_seeker :false,
        peso_tem:"",
        show : true,
        item_id:""
    },
    methods: {
        init() {
            this.cotegories = categories_new;    
        },        
        get_items: function (index) 
        {
            this.category = this.cotegories[index].name;  

            if(this.items_cache[SITE_URL+'/sales/items/0/1?category=' +  this.category] == undefined)
            {        
                axios
                    .get(SITE_URL+'/sales/items/0/1?category=' +  this.category)
                    .then(items => {
                        this.items = items.data.items; 
                        items.data.pagination = items.data.pagination.replace(new RegExp("<a","g") ,"<a onclick='return get_item_pagination(this)' ");
                        this.pagination = items.data.pagination; 
                        this.items_cache[SITE_URL+'/sales/items/0/1?category=' +  this.category] = items.data; 
                    }
                    );
            }
            else{
               var data  = this.items_cache[SITE_URL+'/sales/items/0/1?category=' +  this.category];
               this.pagination = data.pagination;
               this.items = data.items;  
              
            }
        },
        agregar_item: function()
        {

            if($.isNumeric(this.item_id)){
                axios
                .get(SITE_URL+'/sales/item?item_id=' +  this.item_id)
                .then(res => {  
                    if($.isNumeric(res.data.item.id))
                        this.add_item(res.data.item)
                    else
                        toastr.error("Artículo no encontrado");
     

                }                
                );
            }else if(this.item_id.indexOf("KIT")>-1){
                toastr.error("Este artículo ("+this.item_id+ ") no puede ser agregado en esta sección.");
            }
            this.item_id = "";
        },
        add_item_cart: function(){
            var data = csfrData;
            data["items"] =  this.cart;            
            $.post(SITE_URL+'/sales/add_items_cart',data,function(response){
                $("#close").click();
                $("#register_container").load(SITE_URL+"/sales/reload");
            });            
            
            this.show = false;
        },
        set_table(){
            try{
                var total = this.item_to_sell.price_tax * this.peso;
                this.item_to_sell.total =  total;
            }catch(e){}
        },
        _focus: function() {
            this.focus_seeker = true;
        },
        _blur: function()
        {
            this.focus_seeker = false;
        },
        
        delete_item: function(index)
        {
            this.cart.splice( index, 1 );
        },
        formatMoney: function(number){           
            return accounting.formatMoney(number, currency_symbol, 2, thousand_separator, decimal_separator);
        },
        
        get_item: function(item)
        {
            this.item_to_sell.price_tax = item.price_tax;
            this.item_to_sell.name = item.name;
            this.item_to_sell.unit = item.unit;
            this.set_table();
        },
       
        add_item(item_new)
        {
            var maxkey = 0;                       
            var itemalreadyinsale = false;        
            var updatekey = 0;                 
            var items =  this.cart;
            
            for (var key in items)
            {
                var item = items[key];
                
                if(maxkey <= item['line'])                
                    maxkey = item['line'];
                
                
                if(item['item_id'] != undefined && item.item_id == item_new.id )
                {                    
                    itemalreadyinsale = true;
                    updatekey = key; 
                } 
            }

            var line  = maxkey + 1;       
           
            var item = {
                    'item_id' : item_new.id,
                    'line' : line,
                    'quantity' : this.peso,
                    "name" : item_new.name,
                    "price_tax" :  item_new.price_tax,
                    
                };

            if(itemalreadyinsale)                               
                items[updatekey]['quantity'] += parseFloat(Number(this.peso));
            
            else {           
                items.unshift(item);
                this.item_to_sell.price_tax =0;
                this.item_to_sell.name = "";
                this.item_to_sell.unit = "";
                this.item_to_sell.total= 0;
                this.peso = 0;
            }
            
            this.cart = items;     
        }        
     
    },
    mounted() {
        this.init();   
      
    }
    

});


const char_limit = (str, limit, c = "...") =>{

    if (str.length > limit) {
        str = str.substring(0, limit);
        str = str + " " + c;
    }
    return str;
}