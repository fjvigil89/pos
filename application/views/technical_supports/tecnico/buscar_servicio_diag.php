<?php $this->load->view("partial/header"); ?>

<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class="icon fa fa-wrench"></i>
        <?php echo lang('module_' . $controller_name); ?>
        <a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack4"></a>
    </h1>
</div>
<!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD -->
<!-- BEGIN PAGE BREADCRUMB -->
<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->

<div class="clear"></div>

<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-9">
            <div class="portlet box green">
                <div class="portlet-title padding">
                    <div class="caption">
                        <span
                            class="caption-subject bold"><?php echo lang("technical_supports_reporte_tecnico"); ?></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <form action="" class="form">
                                    <div class="form-body">
                                        <div class="col-sm-4 pull-right">
                                            <div class="form-group">
                                                <label for="estado"><?php echo lang("technical_supports_stat"); ?>:
                                                </label>
                                                <?php 
                                                            if($support->state==lang("technical_supports_recibido")) {
                                                                //echo form_dropdown('state', $options, "RECIBIDO", 'id="state" class="form-control"'); 
                                                                echo "<b>$support->state </b>"; 
                                                            }else{
                                                                echo "<b>$support->state </b>"; 
                                                            }
                                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="asigFallas">
                                <div class="row">
                                    <form action="" class="form">

                                        <div class="form-body">
                                            <div class="col-md-12">
                                                <div class="input-group input-group-lg bootstrap-touchspin">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default bootstrap-touchspin-down"
                                                            type="button">?</button>
                                                    </span>
                                                    <span class="input-group-addon bootstrap-touchspin-prefix"
                                                        style="display: none;"></span>
                                                    <input id="touchspin_9" value="" name="demo4_2"
                                                        class="form-control input-lg" style="display: block;"
                                                        type="text"
                                                        placeholder="<?php echo lang("technical_supports_reg_falla"); ?>">
                                                    <span
                                                        class="input-group-addon bootstrap-touchspin-postfix btn green">
                                                        <a href="javascript:void(0);"
                                                            title="<?php echo lang("technical_supports_reg_falla_n"); ?>"
                                                            style="color: #FFFFFF;"
                                                            onclick="controler('<?php echo site_url() ?>/technical_supports/ingFallaTec/','supprt=<?php echo $support->Id_support ?>&vIng=2','asigFallas2','');">
                                                            <span class="fa fa-save"></span>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="row">
                                    <form action="" class="form">
                                        <div class="form-body" id="asigFallas2">

                                            <div class="col-sm-6 col-sm-offset-3">
                                                <div class="blog-single-sidebar bordered blog-container">
                                                    <div class="blog-single-sidebar-search">
                                                        <div class="input-icon right">
                                                            <i class="icon-magnifier"></i>
                                                            <input class="form-control"
                                                                placeholder="<?php echo lang('common_search'); ?> el repuesto o servicio"
                                                                type="text" accesskey="c" name="search_repuesto">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 col-sm-offset-2" style="margin-top: 5px">
                                                <div class="form-group">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="note">
                                            <h4 class="block">
                                                <?php echo lang("technical_supports_datos_entrega_garantia"); ?></h4>
                                            <p class="bold"><?php echo lang("technical_supports_tiene_garantia"); ?>
                                                <span class="fa fa-check-circle"></span> <b
                                                    class="pull-right"><?php echo lang("technical_supports_garantia"); ?></b>
                                            </p>
                                            <div class="form-group">
                                                <label for="comentarios"
                                                    class=""><?php echo lang("technical_supports_comentarios_entrega"); ?></label>
                                                <textarea name="comentarios" id=""
                                                    placeholder="<?php echo lang("technical_supports_ingrese_reporte"); ?>"
                                                    class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="comentarios"><?php echo lang("technical_supports_repair_cost"); ?></label>
                                                <input type="number" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="portlet box">
                <div class="portlet-body">
                    <div class="blog-single-sidebar bordered blog-container">
                        <div class="blog-single-sidebar-search">
                            <div class="input-icon right">
                                <i class="icon-magnifier"></i>
                                <input class="form-control" placeholder="<?php echo lang('common_search'); ?>"
                                    type="text" accesskey="c" name="search_equipoo" id="search_equipoo"
                                    value="<?php echo $support->Id_support ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mostrar_datos"></div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function get_info_equipos(id_equipo) {
        $('#mostrar_datos').load("<?php echo site_url() ?>/technical_supports/mostrarResumen/" + id_equipo);
    }

    $("#search_equipoo").autocomplete({
        source: '<?php echo site_url("sales/equipos_search"); ?>',
        delay: 0,
        autoFocus: true,
        minLength: 2,
        select: function(event, ui) {
            get_info_equipos(ui.item.value);
        }
    });

    var value = $("#search_equipoo").val();
    if (value != "") {
        get_info_equipos(value);
    }

});
</script>
<script type="text/javascript">
$('#state').change(function() {
    var perf = $('#state').val();
    if (perf == 'DIAGNOSTICADO' || perf == 'APROBADO' || perf == 'RECHAZADO' || perf == 'REPARADO' || perf ==
        'RETIRADO') {
        controler('<?php echo site_url() ?>/technical_supports/ingFallaTec/',
            'supprt=<?php echo $support->Id_support ?>&vIng=1', 'asigFallas', '');
    } else {
        $('#asigFallas').html('');
    }
});
</script>

<?php $this->load->view("partial/footer"); ?>
<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>