var categorias = [];
var config_paginacion = [];

$(document).ready(function () {
    (async function () {
        await categories();
    })();
});
async function categories() {
    if (categorias.length == 0) {
        let db = await idb.open(nombreDB, version);
        const transaction = (db).transaction(['categories'], 'readonly');
        const objectStore = transaction.objectStore('categories');
        //var index = objectStore.index("categorie");
        let categories = await objectStore.get(1);

        if (categories != undefined) {
            categorias = Array();
            for (i in categories) {
                categorias[i] = categories[i];
            }
        }
    }
    paginar(1, categorias);
}
async function item_categories(categorie) {
    let data = await objItem.get_all_by_category(categorie);
    $("#category_item_selection_wrapper").plainOverlay('hide');
    paginar(2, data, 17);
}
function paginar(type = 1, data_array, pageSize = 18) {

    $('.pagination-container').pagination({
        dataSource: data_array,
        pageSize: pageSize,
        //showPrevious: trur,
        //showNext: false,
        autoHidePrevious: true,
        autoHideNext: true,
        callback: function (data, pagination) {
            data.sort();//ordenamos 
            if (type == 1) {
                template(data);
            } else {
                template2(data);
            }
        }
    });
}
function template(data) {

    $('#category_item_selection').html('')
    data.forEach(function (item) {
        $('#category_item_selection').append('<a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6"><p>' + item + '</p></a>');
    });
    $("#category_item_selection_wrapper").plainOverlay('hide');
}
function template2(data) {

    $("#category_item_selection").html('');
    var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class', 'category_item back-to-categories col-md-2 col-sm-3 col-xs-6 ').append('<p>&laquo; ' + "Volver a categor\u00edas" + '</p>');
    $("#category_item_selection").append(back_to_categories_button);
    data.forEach(function (item) {
        var image_src = "img/no-photo.jpg";
        var prod_image = "";
        var item_parent_class = "";
        if (image_src != '') {
            var item_parent_class = "item_parent_class";
            var prod_image = '<a class="btn grey-gallery"><img class="img-thumbnail" style="width:100%; height:60px;" src="' + image_src + '" alt="" />';
        }
        var item = $("<div/>").attr('class', 'category_items item space-item col-lg-2 col-md-2 col-sm-3 col-xs-6  ' + item_parent_class).attr('data-id', item.item_id).append(prod_image + '<p class="letter-space-item">' + item.name + '</p>' + '</a>');
        $("#category_item_selection").append(item);

    });
    $("#category_item_selection_wrapper").plainOverlay('hide');

}
/*async function items_suggestions(){
   let  search = $("#item").val();
   let suggestions= await objItem.get_item_search_suggestions(search,50);
   return suggestions;
}*/
async function items_suggestions(){
    let search = $("#item").val();
    let suggestions = Array();
    if (search != "") {
        suggestions= await objItem.get_item_search_suggestions(search,50);
        suggestions= suggestions.concat(await objItem_kit.get_item_kit_search_suggestions(search, 50) );
        $("#item").autocomplete({
            source: suggestions,
            delay: 300,
            autoFocus: false,
            minLength: 1, 
            select: function (event, ui) {
                event.preventDefault();
                $( "input[name='no_valida_por_id']" ).val("0");
                $("#item").val(ui.item.value);
                $('#add_item_form').submit();
            }
        });
    }
}

async function  suggestions_customer(){
    let search = $("#customer").val();
    let suggestions = Array();
    if (search != "") {
        suggestions= await objCustomer.get_customer_search_suggestions(search,100);
        $("#customer").autocomplete({
            source: suggestions,
            delay: 300,
            autoFocus: false,
            minLength: 1,
            select: function (event, ui) {
                event.preventDefault();
                $("#customer").val(ui.item.value);
                $('#select_customer_form').submit();
            }
        });
    }
}


