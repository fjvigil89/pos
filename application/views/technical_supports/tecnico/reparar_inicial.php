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

<!--<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>-->
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
                            <div class="row">
                                <div class="col-sm-4 pull-right">
                                    <div class="form-group">
                                        <label for="estado"><?php echo lang("technical_supports_stat"); ?>:
                                        </label>

                                        <?php  echo "<b id='state'>$support->state </b>"; ?>
                                    </div>
                                </div>
                            </div>
                            <div id="asigFallas">
                                <div class="row">

                                    <div class="form-body">
                                        <div class="ibox-content">
                                            <div class="form-group">
                                                <?= form_open('technical_supports/add_diagnostico_tecnico', 'class="" id="form_diagnostico" autocomplete="off"'); ?>
                                                <!--    <form id="form_s" action="javascript:void(0)"
                                                    onsubmit='controler("<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/", $("#form_s").serialize(), "asigDiagnostico","")'>-->
                                                <?= form_input(array(
                                                        'type'  => 'hidden',
                                                        'name'  => 'supprt_id',
                                                        'id'    => 'supprt_id',
                                                        'value' => $support->Id_support,
                                                    ));?>
                                                <div class="col-md-12">
                                                    <div class="col-md-12" style="overflow: hidden;height: auto;">
                                                        <?php  echo form_input(array(
                                                            'type'  => 'text',
                                                            'name'  => 'diagnostico',
                                                            'id'    => 'diagnostico',
                                                            'value' => "",
                                                            "required"=>"required",
                                                            'placeholder'=> lang("technical_supports_regs_diag"),
                                                            "style"=>"display: block;width: 80%;float: left;",
                                                            "class"=>"form-control input-lg"
                                                        ));?>

                                                        <button type="submit" class=" btn green"
                                                            title="<?php echo lang("technical_supports_reg_diag"); ?>"
                                                            style="height: 45px;margin-left: 5px;float: left;">
                                                            <span class="fa fa-save" style="font-size: 1.700em;"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="form-group">
                                                <div class="col-sm-9 " style="margin-top: 10px;margin-left: 15px;">
                                                    <div class="form-group" id="verListDiagnostico">

                                                        <?php                                             
                                                       
                                                        $diagTec=$support->technical_failure; ?>
                                                        <table class="table table-bordered" id="table_diagnostico">
                                                            <tbody>
                                                                <?php if($diagTec!=''){   ?>
                                                                <tr>
                                                                    <td style="width: 80%;"><?php echo $diagTec; ?></td>
                                                                    <td>
                                                                        <a href="javascript:void(0);"
                                                                            onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/','eld=<?php echo $IdSupport; ?>&ant=1&supprt=<?php echo $IdSupport; ?>','asigDiagnostico','<?php echo lang("technical_supports_menj_detalle_eli"); ?>');">
                                                                            <button class="btn btn-danger"><span
                                                                                    class="icon"><i
                                                                                        class="fa fa-trash"></i></span></button>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php   }
                                                                foreach ($dataDiagnostico->result() as $dataDiagnostico) {                                                         ?>
                                                                <tr>
                                                                    <td style="width: 75%;">
                                                                        <?php echo $dataDiagnostico->diagnostico ?></td>
                                                                    <td style="text-align: center;width: 25%;">
                                                                        <a href="<?=site_url('technical_supports/modal_actualizar_diagnostico')."/".$support->Id_support."/".$dataDiagnostico->id?>"
                                                                            data-toggle="modal" data-target="#myModal">
                                                                            <span class="icon"
                                                                                style="font-size: 1.500em;"><i
                                                                                    class="fa fa-edit"></i></span>
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            onclick="eliminar_diagnostico(this,<?= $support->Id_support ?>,<?=$dataDiagnostico->id ?>)">
                                                                            <span class="icon"
                                                                                title="<?php echo lang("technical_supports_title_detalle_eli"); ?>"
                                                                                style="margin-left: 10px;font-size: 1.500em;"><i
                                                                                    class="fa fa-trash"></i></span>
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

                        </div>
                    </div>
                </div>
            </div>
            <div id="cart_body" style="display: <?= $support->state== lang("technical_supports_recibido")? "none":""?>">
                <?php
               // $this->load->View("technical_supports/carrito/carrito_cuerpo"); 

                 $this->load->view('technical_supports/carrito/cart_reparar');?>
            </div>
            <div id="div_entrega"
                style="display: <?= $support->state== lang("technical_supports_recibido")? "none":""?>">
                <?php $this->load->view("technical_supports/tecnico/entrega_garantia");?>
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
                                    type="text" name="search_equipo" id="search_equipo"
                                    value="<?php echo $support->Id_support ?>">
                                <div class="col-sm-12" id="resultado"
                                    style="position: absolute;margin-top: 1px;margin-left: 0px;z-index: 9000;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mostrar_datos">
                <?php $this->load->view("technical_supports/tecnico/index");?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#search_equipo").autocomplete({
        minLength: 0,
        source: '<?=site_url("technical_supports/buscar_servicios");?>',
        focus: function(event, ui) {
            // $( "#customer1" ).val( ui.item.cuenta );
            return false;
        },
        select: function(event, ui) {
            $("#search_equipo").val(ui.item.value);
            window.location = "<?php echo site_url("technical_supports/repair") ?>"+"/"+ui.item.value;
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append(add_html(item)).appendTo(ul);
    };

    function add_html(item) {
        let html = "<div>" +
            "<strong><?=lang("technical_supports_order_n")?>: </strong>" + item.order_support + "<br>" +
            "<strong><?=lang("technical_supports_type")?> : </strong>" + item.type_team +
            " - <?=lang("technical_supports_marca")?>: " + item.marca + "<br>";
             if('<?=$this->config->item("custom1_support_name")?>'!=""  ){
                html += "<strong><?=$this->config->item("custom1_support_name")?> : </strong>" + item.custom1_support_name ;
                html += " - <?=$this->config->item("custom2_support_name")?> : " + item.custom2_support_name +"<br>" ;
             
             }

        if (item.customer_id == "") {
            html += "<strong><?=lang("technical_supports_customer")?>: </strong>" + item.cliente;
        }
        return  "</div>"+html;

    }


    $('#form_diagnostico').submit(function(e) {
        e.preventDefault();
        let data_enviar = $('#form_diagnostico').serializeArray();
        let support = <?= $support->Id_support ?>;
        $.post('<?php echo site_url("technical_supports/add_diagnostico_tecnico");?>', data_enviar,
            function(data) {
                data = JSON.parse(data);
                if (data.respuesta == true) {
                    let url2 =
                        '<?=site_url('technical_supports/modal_actualizar_diagnostico')."/".$support->Id_support?>';
                    let html = '<tr><td style="width: 75%;">' +
                        $("#diagnostico").val() + '</td>' +
                        '<td style="text-align: center;width: 25%;">' +
                        '<a href="' + url2 + "/" + data.id +
                        '" data-toggle="modal" data-target="#myModal"> ' +
                        '<span class="icon" style="font-size: 1.500em;"><i  class="fa fa-edit"></i> </span>' +
                        '</a>' +
                        '<a href="javascript:void(0);" onclick="eliminar_diagnostico(this,' +
                        support + ',' + data.id + ')">' +
                        '<span class="icon" title="<?php echo lang("technical_supports_title_detalle_eli"); ?>"' +
                        'style="margin-left: 10px;font-size: 1.500em;"><i class="fa fa-trash"></i></span>' +
                        '</a>  </td>   </tr>';
                    $("#diagnostico").val("");
                    $('#table_diagnostico tbody').append(html);
                    if (data.state != '<?= lang("technical_supports_diagnosticado")?>') {
                        $("#div_entrega").show("swing");
                        $("#cart_body").show("swing");

                    }

                }
            });
    });

});

function eliminar_diagnostico(elemento, id_support, id_diagnostico) {
    if (confirm(<?php echo json_encode(lang('technical_supports_menj_detalle_eli')."?")?>)) {
        let url = '<?php echo site_url("technical_supports/eliminar_diagnostico");?>/' + id_support + "/" +
            id_diagnostico;
        $.post(url, {}, function(data) {
            data = JSON.parse(data);
            if (data.respuesta == true) {
                $(elemento).closest('tr').remove();
                $("#state").html(data.state);
                if (data.state == '<?= lang("technical_supports_recibido")?>') {
                    $("#div_entrega").hide("linear");
                    $("#cart_body").hide("linear");
                }
            }

        });
    }
}

function get_info_equipos(id_equipo) {
    $('#mostrar_datos').load("<?= site_url("technical_supports/mostrarResumen")?>/" + id_equipo);
}
</script>




<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>
<?php $this->load->view("partial/footer"); ?>