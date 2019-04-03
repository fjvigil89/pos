<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <?= form_open('technical_supports/actualizar_diagnostico', 'class="" id="form_diagnostico_actualizar" autocomplete="off"'); ?>

            <?= form_input(array(
                    'type'  => 'hidden',
                    'name'  => 'supprt_id',
                    'id'    => 'supprt_id',
                    'value' => $dataDiagnostico->id_support,
            ));?>
            <?= form_input(array(
                    'type'  => 'hidden',
                    'name'  => 'id_diagnostico',
                    'id'    => 'id_diagnostico',
                    'value' => $dataDiagnostico->id,
            ));?>

            <div class="modal-header"
                style="height: 90px;background: url('img/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" class="btn btn-danger" id="cerrar"
                    style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;"
                    data-dismiss="modal"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title"><?php echo lang('technical_supports_mod_diag_titu'); ?></h4>
                <small class="font-bold"><?php echo lang('technical_supports_mod_diag_titu_menj'); ?></small>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="form-group">
                                <label
                                    class="col-lg-11 control-label"><?php echo lang('technical_supports_mod_diag_label'); ?></label>
                                <div class="col-lg-12">
                                    <?= form_input(
                                        array(
                                           'type'  => 'text',
                                            'name'  => 'nuevo_diagnostico',
                                            'id'    => 'nuevo_diagnostico',
                                            'value' => $dataDiagnostico->diagnostico ,
                                            "required"=>"required",
                                            'placeholder'=> lang("technical_supports_regs_diag"),
                                            "class"=>"form-control "
                                        ));?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit"
                    class="btn btn-primary"><?php echo lang('technical_supports_mod_diag_boton'); ?></button>
            </div>
            </form>

        </div>

    </div>
</div>
<script>
$('#form_diagnostico_actualizar').submit(function(e) {
    e.preventDefault();
    let data_enviar = $('#form_diagnostico_actualizar').serializeArray();
    $.post('<?php echo site_url("technical_supports/actualizar_diagnostico");?>',data_enviar, function(data) {
        data = JSON.parse(data);
        if (data.respuesta != true) {           
			toastr.error(data.mensaje, <?=json_encode(lang('common_error'))?>);
		}else{
			toastr.success(data.mensaje,<?=json_encode(lang('common_success'))?>);
        }
        $("#cerrar").click();
			
    });
});
</script>