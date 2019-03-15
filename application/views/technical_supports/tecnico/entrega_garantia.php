<div class="portlet box green">
    <div class="portlet-title padding">
        <div class="caption">
            <span class="caption-subject bold"><?php echo lang("technical_supports_datos_entrega_garantia"); ?></span>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-sm-12">
                <form id="form_dt" action="javascript:void(0)"
                    onsubmit='controlerConfirm("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/", $("#form_dt").serialize(), "asigFallas2","Estas seguro de cambiar el estado del equipo a REPARADO, al confirmar el equipo estara disponible para la entrega")'>
                    <div class="row">

                        <div class="col-sm-12">
                            <?php if ($support->state!=lang("technical_supports_rechazado")) { ?>
                            <div class="note" style="height: auto;overflow: hidden;">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <p class="bold">
                                                <?php echo lang("technical_supports_tiene_garantia"); ?>
                                                <span class="fa fa-check-circle"></span>
                                            </p>
                                        </label>

                                        <div>
                                            <div class="text-right md-radio-inline"
                                                style="width: 40%;float: left;margin-left: 15px;">
                                                <div class="md-radio">
                                                    <?= form_radio(array(
                                                                'name' => 'do_have_guarantee',
                                                                'id' => 'do_have_garantia_yes',
                                                                'value' => '1',
                                                                'class' => 'md-check',
                                                                'checked' => $support->do_have_guarantee != 1 ? 1 : 1)
                                                            );?>
                                                    <label id="show_comment_invoice" for="do_have_garantia_yes">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <?= lang('technical_supports_yes')?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="md-radio-inline"
                                                style="width: 40%;float: left;margin-left: 15px;">
                                                <div class="md-radio">
                                                    <?= form_radio(array(
                                                                        'name' => 'do_have_guarantee',
                                                                        'id' => 'do_have_garantia_no',
                                                                        'value' => '0',
                                                                        'class' => 'md-check',
                                                                        'checked' => $support->do_have_guarantee  != 0 ? 0 : 1)
                                                                    );?>
                                                    <label for="do_have_garantia_no">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <?= lang('technical_supports_no');?>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group" id="accessory" style="display:none">
                                        <label class="control-label">
                                            <a class="help_config_required tooltips" data-placement="left"
                                                title="<?php echo lang("technical_supports_garantia") ?>"><?php echo lang('technical_supports_garantia') ?></a>
                                        </label>
                                        <input type="date" id="fecha_garantia" name="fecha_garantia"
                                            value="<?php echo $support->date_garantia; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-8">
                                        <label for="comentarios"
                                            class=""><?php echo lang("technical_supports_comentarios_entrega"); ?></label>
                                        <textarea name="comentarios" id="comentarios"
                                            placeholder="<?php echo lang("technical_supports_ingrese_reporte"); ?>"
                                            class="form-control"></textarea>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="costo"><?php echo lang("technical_supports_repair_cost"); ?></label>
                                        <input type="number" id="costo" name="costo"
                                            value="<?php echo $support->repair_cost; ?>" class="form-control">
                                    </div>

                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-sm-12" style="text-align: right;">
                                <input type="hidden" id="acept" name="acept" value="2">
                                <input type="hidden" id="supprt" name="supprt"
                                    value="<?php echo $support->Id_support; ?>">
                                <?php if ($support->state!=lang("technical_supports_rechazado") and $support->state!=lang("technical_supports_reparado")) { ?>
                                <button type="submit" id="form_dt" class="btn btn-primary"> <span
                                        class="fa fa-save"></span> Marcar como Reparado </button>
                                <div id="rechz" style="float: right;margin-left: 7px;">
                                    <a href="javascript:void(0);" class="btn btn-primary" title="Devolver el equipo"
                                        style="color: #FFFFFF;"
                                        onclick="rechazar(this ,<?=  $support->Id_support; ?>)">
                                        <span class="fa fa-save"></span> Rechazar
                                    </a>
                                </div>
                                <?php }
									if ($support->state==lang("technical_supports_rechazado") Or $support->state==lang("technical_supports_reparado")) { ?>
                                <script type="text/javascript">
                                window.location = '<?php echo site_url('technical_supports'); ?>';
                                </script>
                                <?php /*<a href="javascript:void(0);"  class="btn btn-primary" title="Equipo reparado" style="color: #FFFFFF;" onclick="window.location='<?php echo site_url('technical_supports'); ?>'">
                                <span class="fa fa-search"></span> Servicio Rechazado Listar
                                </a> */ ?>
                                <?php }  ?>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function rechazar(elemento, support_id){
        let msj="Estas seguro de RECHAZAR el servicio? , al confirmar el equipo estara disponible para la entrega";

        if (confirm(msj)) {
            let url = '<?php echo site_url("technical_supports/set_rechazar");?>';
            $.post(url, {"support_id":support_id}, function(data) {
                data=JSON.parse(data);        
                if (data.respuesta == true) {                       
                    toastr.success(data.mensaje,<?=json_encode(lang('common_success'))?>);
                    window.location = '<?php echo site_url('technical_supports'); ?>';
                }else{
                    toastr.error(data.mensaje, <?=json_encode(lang('common_error'))?>);
                }
            });
        }

    }
    $("#do_have_garantia_yes").click(function () {
		$('div[id^="accessory"]').show(200);
		$('div[id^="rechz"]').hide(200);
	});
	$("#do_have_garantia_no").click(function () {
		$('div[id^="accessory"]').hide(200);
		$('div[id^="rechz"]').show(200);
	});
</script>