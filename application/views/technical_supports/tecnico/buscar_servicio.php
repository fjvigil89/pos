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
                            <span class="caption-subject bold"><?php echo lang("technical_supports_asig_fallas"); ?></span>
                    </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-sm-12" id="asigDiagnostico">

                    </div>
                </div>
            </div>
        </div>
        <div id="asigFallas2"></div>
    </div>
    <div class="col-sm-3" >
            <div class="portlet box">
                <div class="portlet-body">
                    <div class="blog-single-sidebar bordered blog-container">
                        <div class="blog-single-sidebar-search">
                                <div class="input-icon right">
                                        <i class="icon-magnifier"></i> 
                                        <input class="form-control ciContc" placeholder="<?php echo lang('common_search'); ?>" type="text" accesskey="c" name="search_equipoo" id="search_equipoo" value="<?php echo $support->Id_support ?>">
                                        <div class="col-sm-12" id="resultado" style="position: absolute;margin-top: 1px;margin-left: 0px;z-index: 9000;"></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mostrar_datos"> </div>

    </div>
</div>
</div>
 
<script type="text/javascript"> 
        $('#asigDiagnostico').load("<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/","supprt=<?php echo $support->Id_support; ?>&T=1");        
</script> 
<?php 
if($support->state=="DIAGNOSTICADO" Or $support->state=="APROBADO") { ?>
<script type="text/javascript"> 
        //$('#asigFallas2').load("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/","supprt=<?php echo $support->Id_support; ?>");        
</script>
<?php } ?>

<script type="text/javascript">
    $(".ciContc").keyup(function () { // function(event)
        var ciCont = $(this).val();
        if (ciCont == '') {
            $('div[id^="resultado"]').html('');
        } else {
            $('#resultado').load("<?php echo site_url() ?>/technical_supports/buscar_cliente_serv_tecnico/","bas=2&t=1&ciCont="+ciCont); 
        } 
        return false;
    });
</script>
<script>
    $(document).ready(function () {
            function get_info_equipos(id_equipo) {
                    $('#mostrar_datos').load("<?php echo site_url() ?>/technical_supports/mostrarResumen/" + id_equipo);
            }

            $( "#search_equipoo" ).autocomplete({
                    source: '<?php echo site_url("sales/equipos_search"); ?>',
                    delay: 0,
                    autoFocus: true,
                    minLength: 2,
                    select: function(event, ui)
                    {
                            get_info_equipos(ui.item.value);
                    }
            });

            var value = $( "#search_equipoo" ).val();
            if (value != "") {
                    get_info_equipos(value);
            }

    });
</script>
<!--<script type="text/javascript">
    $('#state').change(function () {
        var perf = $('#state').val();
        if (perf == 'DIAGNOSTICADO' || perf == 'APROBADO' || perf == 'RECHAZADO' || perf == 'REPARADO' || perf == 'RETIRADO') {
            controler('<?php //echo site_url() ?>/technical_supports/ingFallaTec/','supprt=<?php //echo $support->Id_support ?>&vIng=1','asigFallas','');
        }else{
            $('#asigFallas').html('');
        }
    });
</script> -->
    
<?php $this->load->view("partial/footer"); ?>
<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>