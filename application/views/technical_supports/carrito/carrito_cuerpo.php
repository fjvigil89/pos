<div class="row">
    <div class="col-sm-12 margin-top-15 ">
        <div id="cart_body">
            <div class="col-lg-8 table-responsive">
                <div class="portlet light">
                    <input type="text" name="item" id="item" value="" class="form-control"
                        placeholder="<?php echo lang('common_search'); ?>">
                    <div class="col-sm-10 no-padding" id="resultado_repuesto"
                        style="position: absolute; z-index: 9000;"></div>
                </div>
                <div id="tala_carrito">
                    <?php $this->load->view("technical_supports/carrito/table_items");?>
                </div>

            </div>
            <div class="col-sm-4">
                <div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
                    <div class="portlet-title padding">
                        <div class="caption">
                            <i class="fa fa-user"></i>
                            <span class="caption-subject bold">Información del Cliente</span>
                        </div>
                    </div>
                    <div class="portlet-body padding">
                        <div class="customer-box">
                            <div class="avatar">
                                <?php if($dataServTecCliente->image_id !=''): ?>
                                <img src="<?php echo site_url() ?>/app_files/view/<?php echo $dataServTecCliente->image_id ?>"
                                    class="img-thumbnail" alt="profile-photo">
                                <?php else: ?>
                                <img src="<?php echo base_url() ?>/img/avatar.jpg" alt="Customer" class="img-thumbnail">
                                <?php endif ?>
                            </div>
                            <div class="information info-yes-email">
                                <h4><?php echo $dataServTecCliente->first_name . " ". $dataServTecCliente->last_name  ?>
                                </h4>
                                <a></a>
                                <span class="email"><?php echo $dataServTecCliente->email ?>
                                    <div class="md-checkbox-inline">
                                        <div class="md-checkbox">
                                            <input type="checkbox" name="email_receipt" value="1" id="email_receipt"
                                                class="email_receipt_checkbox">
                                            <label for="email_receipt">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                Enviar recibo al e-mail
                                            </label>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet light no-padding">
                    <div class="portlet-title padding bg-green">
                        <div class="caption">
                            <i style="color: white" class="icon-basket-loaded"></i>
                            <span class="caption-subject bold"
                                style="color: white"><?php  echo lang("sales_summary")?></span>
                        </div>
                        <div class="options">
                            <?php /* http://localhost/desarrollo/pos/index.php/sales/view_shortcuts */ ?>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal"
                                class="pull-right tooltips" id="opener" data-original-title="Atajos de teclado"><i
                                    class="fa fa-keyboard-o" style="font-size: 30px; color: white"></i></a>
                        </div>
                    </div>
                    <ul class="list-group">
                        <div class="item-subtotal-block">
                            <div class="num_items amount">
                                <span class="name-item-summary"><?php echo lang("sales_items_in_cart") ?>:</span>
                                <span class="pull-right badge badge-cart badge-warning">
                                    <?php if (isset($articulos_total)) echo $articulos_total ?>
                                </span>
                            </div>
                            <div class="subtotal">
                                <span
                                    class="name-item-summary"><?php echo lang('technical_supports_menj_car_subt'); ?>:</span>
                                <span class="pull-right name-item-summary">
                                    <strong><?php if (isset($subtotal_total)) echo to_currency("$subtotal_total", "2") ?></strong>
                                </span>
                            </div>
                        </div>
                    </ul>
                    <div class="amount-block">
                        <div class="total amount">
                            <div class="side-heading">
                                <?php echo lang("sales_total")?>:
                            </div>
                            <div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000"
                                data-decimals="2">

                            </div>
                        </div>
                        <div class="total">
                            <div class="side-heading">
                                Cantidad a pagar:
                            </div>
                            <div id="amount-due" class="amount animation-count font-green-jungle" data-speed="1000"
                                data-decimals="2">

                            </div>
                        </div>
                    </div>
                    <?php if($abonado!=""):?>
                    <li class="list-group-item ">
                        <span class="sale-info"> <i class="fa fa-money fa-lg font-green"></i>
                            <?php echo lang("technical_supports_abono"); ?> <i class="fa fa-img-up"></i></span>
                        <span class="sale-num pull-right">
                            <strong><?=to_currency($abonado,2);  ?></strong>
                        </span>
                    </li>
                    <?php endif; ?>
                    <div id="contenedor_pago">
                        <?php if(isset($payments)) { ?>
                        <div class="payments">
                            <ul class="list-group">
                                <?php foreach($payments as $payment_id=>$payment) {	?>
                                <li class="list-group-item no-border-top">
                                    <span class="name-item-summary">
                                        <?php echo anchor("technical_supports/delete_pago/$payment_id/$support_id",'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_payment'));?>
                                        <?php echo $payment['payment_type']; ?>
                                    </span>
                                    <span class="name-item-summary pull-right">
                                        <strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])):to_currency($payment['payment_amount']);?></strong>
                                    </span>
                                </li>
                                <?php } ?>
                                <ul>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="add-payment">

                        <?php echo form_open("technical_supports/agregarPago",array('id'=>'add_payment_form', 'autocomplete'=> 'off')); ?>

                        <?= form_input(array(
                                'type'  => 'hidden',
                                'name'  => 'support_id',
                                'id'    => 'support_id',
                                'value' => $support_id,
                                ));
                            ?>
                        <ul class="list-group">
                            <li class="list-group-item no-border-top tier-style">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="name-addpay">
                                            <a class="sales_help tooltips" data-placement="left"
                                                title="<?php echo lang('csales_add_payment_help'); ?>"><?php echo lang('sales_add_payment'); ?>
                                            </a>:
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="">
                                            <?php echo form_dropdown('payment_type',$payment_options,$this->config->item('default_payment_type'), 'id="payment_types" class="bs-select form-control"');?>
                                        </span>
                                    </div>
                                </div>


                                <div class="row margin-top-10" id="panel1"
                                    style="display: <?php echo $pagar_otra_moneda? "none":"block" ?>;">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <?php $value=$this->config->item('round_value')==1 ? round($a_pagar): to_currency_no_money($a_pagar); ?>
                                            <?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>$value ,'class'=>'form-control form-inps-sale'));?>
                                            <span class="input-group-btn">
                                                <input class="btn btn-success" type="button" id="add_payment_button"
                                                    value="<?php echo lang('sales_add_payment'); ?>" />
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-top-10" id="panel2"
                                    style="display: <?php echo $pagar_otra_moneda?"block":"none" ?>;">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"
                                                    id="abreviatura"><?php echo $currency?></span>
                                                <?php echo form_input(array('name'=>'amount_tendered2','id'=>'amount_tendered2','class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
                                                <span class="input-group-btn">
                                                    <input class="btn btn-success" type="button"
                                                        id="add_payment_button2"
                                                        value="<?php echo lang('sales_add_payment'); ?>" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php /*if($this->config->item('activar_pago_segunda_moneda')==1){
											$moneda1= $this->config->item('moneda1');
											$moneda2=$this->config->item('moneda2');
											$moneda3=$this->config->item('moneda3');
											?>
                                <?= lang("sales_pay")?>
                                <input type="radio" name="otra_moneda"
                                    abreviatura="<?php echo $this->config->item('moneda1')?>"
                                    equivalencia="<?php echo $this->config->item('equivalencia1')?>" id="otra_moneda1"
                                    value="1"
                                    <?php echo ($pagar_otra_moneda and ($moneda_numero==1 or $currency==$moneda1))? "checked" :"" ?>>
                                <strong> <?php echo $moneda1;?></strong> -
                                <input type="radio" name="otra_moneda"
                                    abreviatura="<?php echo $this->config->item('moneda2')?>"
                                    equivalencia="<?php echo $this->config->item('equivalencia2')?>" id="otra_moneda2"
                                    value="2"
                                    <?php echo ($pagar_otra_moneda and ($moneda_numero==2 or $currency==$moneda2)) ? "checked" :"" ?>>
                                <strong> <?php echo $moneda2;?></strong> -
                                <input type="radio" name="otra_moneda"
                                    abreviatura="<?php echo $this->config->item('moneda3')?>"
                                    equivalencia="<?php echo $this->config->item('equivalencia3')?>" id="otra_moneda3"
                                    value="3"
                                    <?php echo ($pagar_otra_moneda and ($moneda_numero==3 or $currency==$moneda3)) ? "checked" :"" ?>>
                                <strong> <?php echo $moneda3;?></strong> -
                                <input type="radio" name="otra_moneda" abreviatura="0" equivalencia="1"
                                    id="otra_moneda0" value="0"
                                    <?php echo  ($pagar_otra_moneda==0 and $moneda_numero==0) ? "checked" :"" ?>>
                                <strong>Default</strong><br>

                                <?php }*/?>

                            </li>
                        </ul>
                        </form>
                    </div>

                    <div class="comment-sale">
                        <ul class="list-group">
                            <li class="list-group-item no-border-top tier-style">
                                <?php
									// Only show this part if there is at least one payment entered.
									if((count($payments) > 0 ))
									{?>
                                <div id="finish_sale">
                                    <?php echo form_open("technical_supports/facturar",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
                                        <?= form_input(array(
                                        'type'  => 'hidden',
                                        'name'  => 'support_id',
                                        'id'    => 'support_id',
                                        'value' => $support_id,
                                        ));
                                    ?>
                                    <?php if ($payments_cover_total) {
                                         echo "<input type='button' class='btn btn-success btn-large btn-block' id='finish_sale_button' value='".lang('sales_complete_sale')."' />";
                                        }?>
                                    </form>
                                </div>
                                <?php }									
                                ?>
                                <!--class="hidden" -->
                                <div id="container_comment">
                                    <div class="title-heading">
                                        <label id="comment_label" for="comment"> <?= lang('common_comments');?>
                                        </label>
                                    </div>
                                    <?=form_textarea(array('name'=>'comment', 'id' => 'comment', 'class'=>'form-control form-textarea','value'=>$comment,'rows'=>'1',  'accesskey' => 'o'));?>
                                </div>

                                <?php if($change_sale_id) :?>
                                <br />
                                <div class="md-checkbox-inline">
                                    <div class="md-checkbox">'
                                        <?= form_checkbox(array(
										'name'=>'change_sale_date_enable',
										'id'=>'change_sale_date_enable',
										'value'=>'1',
										'checked'=>(boolean)$change_sale_date_enable)
									);?>
                                        <label id="comment_label" for="change_sale_date_enable">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                            <?= lang('sales_change_date')?>
                                        </label>
                                    </div>
                                </div>
                                <div class="row margin-top-10" id="change_sale_input">
                                    <div class="col-md-12">
                                        <div id="change_sale_date_picker" class="input-group date datepicker"
                                            data-date="date(get_date_format())"
                                            data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <?php echo form_input(array(
													'name'=>'change_sale_date',
													'id' => 'change_sale_date',
													'size'=>'8',
													'class'=>'form-control form-inps',
													'value'=> date(get_date_format())
												));?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif;?>



                            </li>
                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
$("#item").keyup(function() {
    var value = $(this).val();
    if (value == '') {
        $('#resultado_repuesto').html('');
    } else {
        $('#resultado_repuesto').load("<?php echo site_url() ?>/technical_supports/buscarItemsSupport",
            "idSupport=<?php echo $support_id ?>&buscar=" + value);
    }
    return false;
});

$(document.body).click(function() {
    $('#item').val('');
    $('#resultado_repuesto').html('');


});
$("#add_payment_button").click(function() {

    if (!$.isNumeric($("#amount_tendered").val())) {
        toastr.error(<?php echo json_encode(lang('sales_must_enter_numeric'))?>,
            <?=json_encode(lang('common_error'))?>);
    } else if ($("#amount_tendered").val() != 0) {
        activa_desactiva($estado = true)
        $('#add_payment_form').submit();
    }
});

function activa_desactiva($estado = true) {
    $('#add_payment_button').attr("disabled", $estado);
    $('#finish_sale_button').attr("disabled", $estado);
}

$('.delete_payment').click(function(event) {
    event.preventDefault();
    $("#cart_body").load($(this).attr('href'));
});
$('#add_payment_form').ajaxForm({
    target: "#cart_body",
    beforeSubmit: antes_enviar
});

$("#comment").change(function(e) {
    activa_desactiva(true);
    let url = '<?= site_url("technical_supports/set_comentario");?>';
    $.post(url, {
        "support_id": <?= $support_id?>,
        "comment": $(this).val(),
    }, function(data) {
        activa_desactiva(false);
    });
});
$("#finish_sale_button").click(function() {

    activa_desactiva();
    $("#cart_body").plainOverlay('show');
    <?php if(!$payments_cover_total) { ?>
        if (!confirm(<?php echo json_encode(lang('sales_payment_not_cover_total_confirmation')); ?>)) {
            $("#cart_body").plainOverlay('hide');
            return;
        }
    <?php } ?>
    <?php if (!$this->config->item('disable_confirmation_sale')) { ?>
        if (confirm(<?php echo json_encode(lang("sales_confirm_finish_sale")); ?>)) {
            $('#finish_sale_form').submit();
        }
    <?php }
    else{
            echo "$('#finish_sale_form').submit();";
        
    } ?>
});
</script>