<style>
.modal-header-success {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #26a69a;
}
</style>
<div class="row">

    <div class="col-sm-12 margin-bottom-05">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%">
                <tr>
                    <td>
                        <strong>
                            Serial:
                        </strong>
                    </td>
                    <td>
                        <?php echo form_open("sales/add_by_serial",array('id'=>'add_item_serial','class'=>'', 'autocomplete'=> 'off')); ?>
                        <?php echo form_input(array(
									'name'=>'item_serial',
									'id'=>'item_serial',
									"autofocus"=>"",
									
									'class'=>'form-control form-inps',
									'placeholder' =>"Ingrese el serial  y presione enter o buscar",
									'value'=>"")
									);?>
                        </form>
                    </td>
                    <td>
                        <button class="btn" id="boton-buscar-serial"> BUSCAR </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="display:none" id="panel-table" class="col-sm-12 margin-bottom-05">
        <div class="portlet box grey-cascade">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        Productos con el mismos número de serie
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-bordered table-hover table-striped" width="1200px">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th><?php echo lang('items_item_number'); ?></th>
                        <th></th>
                    </tr>
                    <tbody id="tbody-series">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {

		document.getElementById("item_serial").focus();
    $("#add_item_serial").submit(function(event) {
        event.preventDefault();

        if ($("#item_serial").val() != "") {
            const sereial = $("#item_serial").val();

            (async function() {
                const items = await objItem.get_items_by_serial(sereial);
                if (items.length > 1) {
                    add_row(items);
                    $("#panel-table").show();
								} 
								else if (items.length == 1) 
								{
									await add_by_serial(items[0]);
									
								} 
								else 
								{
                    toastr.error('Artículo no encontrado');
                }
                //23242
            })();
        }
    });
    $("#boton-buscar-serial").click(function() {
        $("#add_item_serial").submit();
    });
});

function add_row(data) {
    $("#tbody-series").html("");
    for (const i in data) {
        $("#tbody-series").append(
            "<tr>" +
            "<td align='center'>" + data[i].item_id + "</td>" +
            "<td align='center'>" + data[i].name + "</td>" +
            "<td align='center'>" + (data[i].item_number == null ? "" : data[i].item_number) + "</td>" +
            "<td align='center'>" +
            "<button  data-item_id-type ='" +
            data[i].item_id + "' data-item_serial-type ='" +
            data[i].item_serial +
            "' onclick='add_cart(this)' style='width: 90%' class='btn btn-sm btn-success '>" +
            "Agregar</button></td>" +
            "</tr>"
        );
    }
}

async function add_by_serial(item) 
{
	$("#close-modal").click();
    const item_id = item.item_id,
        item_info = await objItem.get_info(item_id);

    if (item_info.deleted == 1 || !await add_item(item_id, 1, 0, null, null,
				item.item_serial, false, false, null, null, false)) {
        toastr.error(lang('sales_unable_to_add_item'));
    }

    if (await objAppconfig.item('sales_stock_inventory') == false &&
        await out_of_stock(item_id, false)) {
        toastr.warning(
            "Advertencia. La cantidad deseada es insuficiente. Puedes procesar la venta pero revisa tu inventario."
        )
    } else if (await objAppconfig.item('sales_stock_inventory') == true &&
        await out_of_stock(item_id, false)) {
        toastr.error(
            'No se puede agregar el producto si el inventario se encuentra escaso'
        );
		}
		await armar_vista();

}

function add_cart(elemento) {
    let item_id = elemento.getAttribute("data-item_id-type");
    let item_serial = elemento.getAttribute("data-item_serial-type");

    add_by_serial({
        item_serial: item_serial,
        item_id: item_id
    });

    

}
</script>