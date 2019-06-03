<style>
#app-scales {
    background-color: #FAFAFA;
}

#hr {
    margin: 4px;
    border-top: 1px dotted;

}

.scrollable::-webkit-scrollbar {
    width: 8px;
    background-color: #F5F5F5;
}

.scrollable::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-color: #F5F5F5;
}

.scrollable::-webkit-scrollbar-thumb {
    background-color: #26a69a;
    background-image: -webkit-linear-gradient(90deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent)
}

.scrollable {
    width: 100%;
    height: 420px;
    overflow-x: auto;
    overflow-y: auto;
    border: 1px solid #ddd;
    margin: 10px 0 !important;
}
</style>
<script>
var categories_new = JSON.parse('<?=$categories?>'),
    path_img = "<?=$path_img?>",
    currency_symbol = "<?= $this->config->item("currency_symbol")?>",
    thousand_separator = "<?= $this->config->item("currthousand_separatorency_symbol")?>",
    decimal_separator = "<?= $this->config->item("decimal_separator")?>";
</script>
<script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>js/axios.min.js"></script>
<div class="modal-dialog modal-full">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        Balanza
                    </span>
                </div>
                <div class="tools">
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div id="app-scales" class="portlet-body">
                <div class="row">
                    <div class=" text-center col-md-12 col-sm-12">
                        <div class="row">
                            <div class=" col-xs-4 col-sm-3">
                                <div>
                                    <h4><strong>{{item_to_sell.name}}</strong></h4>
                                </div>
                            </div>
                            <div class=" col-xs-4  col-sm-3">
                                <div>
                                    <h2>{{formatMoney(item_to_sell.price_tax)}}</h2>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-3">
                                <div>
                                    <h2>{{peso}}<SUB>{{item_to_sell.unit}}</SUB></h2>
                                </div>

                            </div>
                            <div class="col-xs-4 col-sm-3">
                                <div>
                                    <h2>{{formatMoney(item_to_sell.total)}}<SUB>Total</SUB></h2>
                                </div>

                            </div>
                        </div>
                        <hr id="hr">
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-1" style="padding-right:1px">
                        <div class="scrollable">
                            <!--<div v-for="(category, index) in cotegories" >
                            <div  v-on:click="get_items(index)"  class="thumbnail" style="margin:2px">
                                <img :src="category.img" :alt="category.title" class="img-rounded">
                                <p style="margin:1px">{{category.title}}</p>
                            </div> 
                        </div> -->
                            <category-item v-for="(category, index) in cotegories" :key="index" :index="index"
                                :category="category" @click="get_items">
                            </category-item>
                        </div>
                    </div>
                    <div class="col-xs-10 col-sm-4 col-md-6 ">

                        <div class="row">
                            <div class="col-md-12">
                                <?php echo form_open("#",array('id'=>'form_item_scale','class'=>'',"v-on:submit.prevent"=>"agregar_item", 'autocomplete'=> 'off')); ?>

                                <input @focus="_focus()" v-model="item_id"  placeholder ="<?= lang('sales_start_typing_item_name')?>" value="item_id" value="" v-on:blur="_blur()" id="item-scale" class="form-control"
                                    type="text">
                                </form>
                            </div>
                            <item-info v-for="(item, index) in items" @click="get_item" @add="add_item" :key="index"
                                :index="index" :item="item">
                            </item-info>
                            <!--<div v-for="(item, index) in items">

                                <div class="col-xs-2" style="padding:2px">
                                
                                    <div class="thumbnail text-center"
                                        style=" min-height: 108px; max-height: 108px; margin:2px;">
                                        <img style="width:90%; height:65px;" :src="item_img(index)" :alt="item.name"
                                            class="img-rounded">
                                        <p style="margin:1px">{{char_limit(item.name,16)}} </p>
                                    </div>
                                </div>
                            </div>-->
                            <div class="col-md-12">
                                <div ref="pagination"
                                    class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar >"
                                    v-html="pagination">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <div class="register-items-holder">
                            <div class="table-scrollable">
                                <table id="register" class="table table-advance table-bordered table-custom">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in cart" :key="index">
                                            <td class="text-center">
                                                <a href="javascript:void(0)" v-on:click="delete_item(index)"
                                                    class="delete_item"><i
                                                        class="fa fa-trash-o fa fa-2x font-red"></i></a></td>
                                            <td>{{item.name}}</td>
                                            <td>
                                                <input @focus="_focus()" v-on:blur="_blur()" class="form-control"
                                                    v-model="item.quantity" value="item.quantity" type="number">
                                            </td>
                                            <td>{{formatMoney(item.price_tax)}}</td>
                                            <td>{{formatMoney(item.price_tax*item.quantity)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-center" v-show="cart.length > 0 && show">
                            <input type="button" v-on:click="add_item_cart()" class="btn btn-success btn-large "
                                id="add-cart" value="Facturar">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function get_item_pagination(element) {
    
    const href = $(element).attr('href');
    var  url = href + '/1?category=' + scales._data.category;
    if(scales._data.items_cache[url] == undefined)
    { 
        $.get(url, {}, function(data) {

            data = JSON.parse(data)
            scales._data.items = data.items;
            data.pagination = data.pagination.replace(new RegExp("<a", "g"),
                "<a onclick='return get_item_pagination(this)' ")
            scales._data.pagination = data.pagination;

            scales._data.items_cache[url] = data;

        })
    }
    else
    {
        var data  = scales._data.items_cache[url];
        scales._data.pagination = data.pagination;
        scales._data.items = data.items;
    }
    return false;
}
$(document).ready(function() {
  

    $(document).off("keypress");
    $(document).off("keydown");

    $(document).keypress(function(event) {
        add_peso(event.key)
    });
    
});

function add_peso(char) {

    if (!scales._data.focus_seeker) {
        if (char == "Enter") {

            if (isNaN(Number("0" + scales._data.peso_tem)) == false && scales._data.peso_tem != "") {
                scales._data.peso = scales._data.peso_tem;
                scales.set_table();
            }
            scales._data.peso_tem = "";
            console.log(char)
        } else {
            try {
                if (isNaN(Number("0" + char)) == false || char == ".") {
                    scales._data.peso_tem = scales._data.peso_tem + "" + char;
                }

            } catch (e) {
                console.log(e)
            }
        }
    }
}
</script>

<script src="<?=base_url()?>/js/vue/scales.js"></script>
<<script>
$( "#item-scale" ).autocomplete({
        source: '<?php echo site_url("sales/item_search"); ?>',
        delay: 300,
        autoFocus: true,
        minLength: 1,
        select: function(event, ui) {
            event.preventDefault();
            scales._data.item_id = ui.item.value;  
            scales.agregar_item();          

        }
    });
</script>