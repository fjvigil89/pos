<?php foreach ($dataSupport->result() as $dataSupport) { $status=$dataSupport->state; $idSupport=$dataSupport->Id_support; $garantia=$dataSupport->do_have_guarantee; $fgarantia=$dataSupport->date_garantia; $costo=$dataSupport->repair_cost; } ?>
<?php if ($status!="RECHAZADO") { ?>
<div class="portlet box green">
    <div class="portlet-title padding">
            <div class="caption">
                    <span class="caption-subject bold"><?php echo lang("technical_supports_asig_resp"); ?></span>
            </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-sm-12">                      
                <div class="row">                              
                    <form id="form_r" action="javascript:void(0)" onsubmit='controler("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/", $("#form_r").serialize(), "asigFallas2","")'>
                        <input type="hidden" name="supprt" id="supprt" value="<?php echo $idSupport ?>">
                        <div class="ibox-content"> 
                        <div class="form-group">
                        <div class="col-sm-3">                                                                    
                            <input class="form-control" placeholder="<?php echo lang('technical_supports_resp_cod'); ?>" type="text"  name="codigo" id="codigo">
                        </div>
                        </div>
                        </div>
                        <div class="ibox-content"> 
                        <div class="form-group">
                        <div class="col-sm-6"> 
                            <input class="form-control" placeholder="<?php echo lang('technical_supports_resp_nomb'); ?>" type="text"  name="respuesto" id="respuesto"> 
                        </div>
                        </div>
                        </div>
                        <div class="ibox-content"> 
                        <div class="form-group">
                        <div class="col-sm-3" style="overflow: hidden;height: auto;"> 
                            <input class="form-control" style="float: left;width: 60%;" placeholder="<?php echo lang('technical_supports_resp_cant'); ?> " type="number"  name="cant" id="cant"> 
                         
                            <button class="btn btn-primary" style="float: left;margin-left: 5px;" type="submit"> <span class="fa fa-save"></span> </button>
                        </div>
                        </div>
                        </div>
                    </form>
                    <div class="col-sm-12" style="margin-top: 10px">
                        <div class="form-group">
                            <table class="table table-bordered">                                     
                                    <tbody>
                                    <?php foreach ($dataRespuesto->result() as $dataRespuesto) { ?>
                                    <tr>
                                        <td style="width: 20%;"><?php echo $dataRespuesto->serie; ?></td>
                                        <td style="width: 50%;"><?php echo $dataRespuesto->name; ?></td>
                                        <td style="width: 10%;"><?php echo $dataRespuesto->quantity; ?></td>
                                        <td style="width: 10%;text-align: center;">
                                            <a href="javascript:void(0);"  onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/','eld=<?php echo $dataRespuesto->id; ?>&supprt=<?php echo $dataRespuesto->id_support; ?>','asigFallas2','Estas seguro de eliminar el registro');">
                                            <button class="btn btn-danger"><span class="icon"><i class="fa fa-trash"></i></span></button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>                             

                </div> 

            </div>
        </div>
    </div>
</div>
<?php }  ?>
<div class="portlet box green">
    <div class="portlet-title padding">
            <div class="caption">
                    <span class="caption-subject bold"><?php echo lang("technical_supports_datos_entrega_garantia"); ?></span>
            </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-sm-12">                      
                <form id="form_dt" action="javascript:void(0)" onsubmit='controlerConfirm("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/", $("#form_dt").serialize(), "asigFallas2","Estas seguro de cambiar el estado del equipo a REPARADO, al confirmar el equipo estara disponible para la entrega")'>
                <div class="row">
                    
                    <div class="col-sm-12">
                        <?php if ($status!="RECHAZADO") { ?>                 
                        <div class="note" style="height: auto;overflow: hidden;">                                
                            <div class="col-sm-6">
                                <div class="form-group">
                                        <label class="control-label">
                                        <p class="bold"><?php echo lang("technical_supports_tiene_garantia"); ?>  <span class="fa fa-check-circle"></span> </p></label>

                                    <div>
                                            <?php
                                            echo '<div class="text-right md-radio-inline" style="width: 40%;float: left;margin-left: 15px;">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                            'name' => 'do_have_accessory',
                                                            'id' => 'do_have_garantia_yes',
                                                            'value' => '1',
                                                            'class' => 'md-check',
                                                            'checked' => $garantia != 1 ? 1 : 1)
                                            );
                                            echo '<label id="show_comment_invoice" for="do_have_garantia_yes">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo lang('technical_supports_yes');
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                             
                                            echo '<div class="md-radio-inline" style="width: 40%;float: left;margin-left: 15px;">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                            'name' => 'do_have_accessory',
                                                            'id' => 'do_have_garantia_no',
                                                            'value' => '0',
                                                            'class' => 'md-check',
                                                            'checked' => $garantia != 0 ? 0 : 1)
                                            );
                                            echo '<label  for="do_have_garantia_no">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo lang('technical_supports_no');
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                            ?>
                                    </div>
                                </div>
                            </div>
                             
                            <div class="col-sm-6">
                                    <div class="form-group" id="accessory" style="display:none">
                                            <label class="control-label">
                                                    <a class="help_config_required tooltips" data-placement="left"
                                                       title="<?php echo lang("technical_supports_garantia") ?>"><?php echo lang('technical_supports_garantia') ?></a>
                                            </label>
                                        <input type="date" id="fecha_garantia" name="fecha_garantia" value="<?php echo $fgarantia; ?>" class="form-control">
                                    </div>
                            </div>                
                            <div class="col-sm-12">               
                            <div class="form-group col-sm-8">
                                    <label for="comentarios" class=""><?php echo lang("technical_supports_comentarios_entrega"); ?></label>
                                    <textarea name="comentarios" id="comentarios" placeholder="<?php echo lang("technical_supports_ingrese_reporte"); ?>"
                                                      class="form-control"></textarea>
                            </div>
                            <div class="form-group col-sm-4">
                                    <label for="costo"><?php echo lang("technical_supports_repair_cost"); ?></label>
                                    <input type="number" id="costo" name="costo" value="<?php echo $costo; ?>" class="form-control">
                            </div>
                             
                            </div>
                        </div>
                        <?php } ?> 
                        <div class="col-sm-12" style="text-align: right;"> 
                            <input type="hidden" id="acept" name="acept" value="2">
                            <input type="hidden" id="supprt" name="supprt" value="<?php echo $idSupport; ?>">
                            <?php if ($status!="RECHAZADO" and $status!="REPARADO") { ?>
                                <button type="submit" id="form_dt" class="btn btn-primary"> <span class="fa fa-save"></span> Marcar como Reparado </button>
                                    <div id="rechz" style="float: right;margin-left: 7px;">
                                        <a href="javascript:void(0);" class="btn btn-primary" title="Devolver el equipo" style="color: #FFFFFF;" onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/','rechaz=2&supprt=<?php echo $idSupport; ?>','asigFallas2','Estas seguro de RECHAZAR el servicio, al confirmar el equipo estara disponible para la entrega');"> 
                                        <span class="fa fa-save"></span> Rechazar 
                                        </a>
                                    </div>
                            <?php } 
                            if ($status=="RECHAZADO" Or $status=="REPARADO") { ?>
                                <script type="text/javascript"> 
                                        window.location='<?php echo site_url('technical_supports'); ?>';        
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
<?php
if($dataCond!="") {
?>
<script type="text/javascript">
    $(document).ready(function (response) {
        event.preventDefault();
        $('#spin').removeClass('hidden');

        $.getJSON($(this).attr('href'), function(response) 
        {
            $('#spin').addClass('hidden');
            alert(response.message);
        });
        toastr.success(response.message, <?php echo json_encode(lang('technical_supports_asig_estado')." ".$dataCond); ?>);						
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
</script>
    <?php
}
if($instrp==1) {
?>
<script type="text/javascript">
    $(document).ready(function (response) {
        event.preventDefault();
        $('#spin').removeClass('hidden');

        $.getJSON($(this).attr('href'), function(response) 
        {
            $('#spin').addClass('hidden');
            alert(response.message);
        });
        toastr.success(response.message, <?php echo json_encode(lang('technical_supports_respuesto_add')); ?>);						
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
</script>
    <?php
} 
if($elmrp==1) {
?>
<script type="text/javascript">
    $(document).ready(function (response) {
        event.preventDefault();
        $('#spin').removeClass('hidden');

        $.getJSON($(this).attr('href'), function(response) 
        {
            $('#spin').addClass('hidden');
            alert(response.message);
        });
        toastr.error(response.message, <?php echo json_encode(lang('technical_supports_respuesto_delet')); ?>); 
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
 </script>   
    <?php
} 
if($t_resp==1) {
?>
<script type="text/javascript">
    $(document).ready(function (response) {
        event.preventDefault();
        $('#spin').removeClass('hidden');

        $.getJSON($(this).attr('href'), function(response) 
        {
            $('#spin').addClass('hidden');
            alert(response.message);
        });
        toastr.error(response.message, <?php echo json_encode(lang('technical_supports_adv_asig_resp')); ?>); 
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
 </script>   
    <?php
}
?>
<script type="text/javascript">
    $("#do_have_garantia_yes").click(function () {  
        $('div[id^="accessory"]').show(); 
        $('div[id^="rechz"]').hide(); 
    });
    $("#do_have_garantia_no").click(function () {  
        $('div[id^="accessory"]').hide(); 
        $('div[id^="rechz"]').show();
    });
</script>
<?php
if($garantia==1) {
    ?>
    <script type="text/javascript"> 
            $('div[id^="accessory"]').show();  
    </script>
    <?php
}