<div class="register-items-holder">
    <div style="width:100%;height:270px;overflow-x:auto;overflow-y:auto;border:1px solid #ddd;margin:10px 0!important">
        <table id="table_cart" class="table table-advance table-bordered table-custom">
            <thead>
                <tr>
                    <th></th>
                    <th><?= lang('technical_supports_titu_car_art'); ?></th>
                    <th><?= lang('technical_supports_titu_car_pre'); ?></th>
                    <th><?= lang('technical_supports_titu_car_cant'); ?></th>
                    <th><?= strtoupper(lang('sales_total')); ?></th>
                </tr>
            </thead>
            <tbody class="register-item-content">
                <?php if (isset($carrito) and is_array($carrito)): ?>
                <?php foreach ($carrito as $line=>$item): ?>
                <tr class="register-item-details">
                    <td class="text-center">
                        <?= anchor("technical_supports/delete_item/$line/$is_cart_reparar/$support_id",'<i class="fa fa-trash-o fa fa-2x font-red"></i>', array('class' => 'delete_item'));?>
                    </td>
                    <td class="text-center"><?=H($item['name']) ?></td>
                    <td class="text-center"><?php echo to_currency("{$item['precio_e_iva']}", "2"); ?></td>
                    <td width="5">
                        <?= form_open("technical_supports/edit_item/$line/$is_cart_reparar/$support_id", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
					if(isset($item['is_serialized']) && $item['is_serialized']==1)
					{
						echo to_quantity($item['quantity']);
						echo form_hidden('quantity',to_quantity($item['quantity']));
					}
					else
					{
						echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'class'=>'form-control form-inps-sale text-center quantity', 'id' => 'quantity_'.$line, 'tabindex'=>'2'));
					}?>
                        </form>
                    </td>
                    <td class="text-center"><?php echo to_currency($item['price']*$item['quantity'], "2"); ?></td>
                </tr>
                <?php if($item['is_serialized']==1) :?>
                <tr class="register-item-bottom">
                    <td>
                        <strong><?= lang('sales_serial')?> : </strong>
                    </td>
                    <td COLSPAN="2">
                        <?= form_open("technical_supports/edit_item/$line/$is_cart_reparar/$support_id", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
					        echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'class'=>'form-control form-inps-sale  ', 'id' => 'serialnumber_'.$line, 'tabindex'=>'2'));
					    ?>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">
                        <h4 class="text-center"><code><?php echo lang('technical_supports_menj_car'); ?></code></h4>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="portlet light margin-top-15 no-padding">

    <ul class="list-group">
        <div class="item-subtotal-block">
            <div class="num_items amount">
                <span class="name-item-summary"><?php echo lang('technical_supports_menj_car_cestart'); ?>:</span>
                <span class="pull-right badge badge-cart badge-success">
                    <?php if (isset($articulos_total)) { echo $articulos_total;} ?>
                </span>
            </div>
            <div class="subtotal">
                <span class="name-item-summary"><?php echo lang('technical_supports_menj_car_subt'); ?>:</span>
                <span class="pull-right name-item-summary">
                    <strong>
                        <?php if (isset($subtotal_total)) { echo to_currency("$subtotal_total", "2");} ?>
                    </strong>
                </span>
            </div>
        </div>
        <li class="list-group-item" style="border: 0px none;">
            <h5 class="text-center"><?php echo lang('technical_supports_menj_car_prodiva'); ?></h5>
            <table class="table">

                <thead>
                    <tr>
                        <th><?php echo lang('technical_supports_menj_car_product'); ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($articulos_iva)): ?>
                    <?php foreach ($articulos_iva as $data) { ?>
                    <tr>
                        <td class="text-center"><?php echo $data['nombre'] ?></td>
                        <?php foreach ( $data["ivas"] as $key => $iva) :?>
                        <td class="text-right"><?=  $iva["percent"]."% ".$iva["name"] . " = " ?>
                            <strong>
                                <?php $total =  ($iva["valor"]);
										echo to_currency($total, 2); ?>
                            </strong>
                        </td>
                        <?php endforeach ?>
                        <?php for ($i=count($data["ivas"]); $i <5 ; $i++) { 
								echo ("<td></td>");
							} ?>

                        <td class="text-right">
                            <?php   $total_iva=0;
                                foreach ($data["ivas"] as  $iva) {
                                    $total_iva+= $iva["valor"];
                                }
                            ?>
                            <strong><?php echo to_currency($total_iva,2) ?></strong>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php endif ?>
                </tbody>
            </table>
        </li>

    </ul>
    <?php if($is_cart_reparar) :?>
    <div class="amount-block">
        <div class="total amount">
            <div class="side-heading">
                <?=lang("sales_total") ?>:
            </div>
            <div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">
                <?= to_currency($precio_total, 2);?>
            </div>
        </div>
        <div class="total">
            <div class="side-heading">
                Cantidad a pagar:
            </div>
            <div id="amount-due" class="amount animation-count text-danger" data-speed="1000" data-decimals="2">
                <?= to_currency($a_pagar, 2); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<div>
    <button id="btn_suspender" class="btn">Suspender</button>
</div>

<script type="text/javascript">
<?php
if(isset($error))
			{
				echo "toastr.error(".json_encode($error).", ".json_encode(lang('common_error')).");";
			}

			if (isset($warning))
			{
				echo "toastr.warning(".json_encode($warning).", ".json_encode(lang('common_warning')).");";
			}

			if (isset($success))
			{
				echo "toastr.success(".json_encode($success).", ".json_encode(lang('common_success')).");";
			}
			?>
$('.quantity').focus();
$("#btn_suspender").click(function(e) {

    if (confirm("¿Desea suspender la reparación?, si suspende, todos los repuesto serán guardados.")) {
        let url = '<?php echo site_url("technical_supports/guardar_cart");?>';
        $.post(url, {
            "support_id": <?= $support_id?>
        }, function(data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.respuesta == true) {
                toastr.success(data.mensaje, <?=json_encode(lang('common_success'))?>);
                //window.location = '<?php echo site_url('technical_supports'); ?>';
            } else {
                toastr.error(data.mensaje, <?=json_encode(lang('common_error'))?>);
            }
        });
    }
});
$('.delete_item').click(function(event) {
    event.preventDefault();
    $("#cart_body").load($(this).attr('href'));
});
$('.line_item_form').ajaxForm({
    target: "#cart_body",
    beforeSubmit: antes_enviar
});
$("#table_cart input").change(function() {
    $(this.form).ajaxSubmit({
        target: "#cart_body",
        beforeSubmit: antes_enviar
    });
});

// animacion de saldo
$('.animation-count').data('countToOptions', {
    formatter: function(value, options) {
        return value.toFixed(options.decimals).replace('',
            '<?php echo $this->config->item("currency_symbol")?>').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
});
var last_total_amount_due =
    <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($a_pagar)) : to_currency_no_money($a_pagar)?>;
var last_total_amount =
    <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($precio_total)) : to_currency_no_money($precio_total)?>;


$('#total-amount').data('to', last_total_amount);
$('#amount-due').data('to', last_total_amount_due);
$('.animation-count').each(count);

function count(options) {
    var $this = $(this);
    options = $.extend({}, options || {}, $this.data('countToOptions') || {});
    $this.countTo(options);
}

function antes_enviar(formData, jqForm, options) {
    /*$("#ajax-loader").show();
    $("#add_payment_button").hide();
    $("#finish_sale_button").hide();*/
}
</script>