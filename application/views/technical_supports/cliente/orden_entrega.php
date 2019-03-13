<?php foreach ($dataServTecCliente->result() as $dataServTecCliente) {  ?>
<?php foreach ($dataServTecAbono->result() as $dataServTecAbono) { $abonado=$dataServTecAbono->Abonado;  }
$totalResp=0;
foreach ($dataRespuestomas->result() as $dataRespuestomasC) { $totalResp=$totalResp+$dataRespuestomasC->repuesto_total; }
?>
<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg" style="width: 96%">
        <div class="modal-content animated bounceInRight">
        
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" title="Cerrar la ventana" class="btn btn-danger" style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/technical_supports/index/','quien=1','contTabla',$('#ventanaVer').html(''));"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title"><?php echo lang("technical_supports_orden_titu"); ?></h4>
                <small class="font-bold"><?php echo lang("technical_supports_orden_titu_menj"); ?></small>
            </div>

            <div class="row">
                <div class="col-lg-12" style="overflow: hidden;">
                    <div class="ibox float-e-margins"> 
                        <div class="col-lg-12" style="background: #FAFAFA;height: auto;overflow: hidden;">
                        <div id="impritOrdenc"></div>
                            <div class="form-group" style="background: #FAFAFA;height: auto;overflow: hidden;">
                            <div class="col-lg-3" style="margin-top: 10px;overflow: hidden;">
                                <div class="portlet light profile-sidebar-portlet bordered">
                                    <div class="portlet-title">
                                        <div class="text-center">
                                            <h4 style="font-weight: 700;"><?php echo lang("technical_supports_customer"); ?></h4>
                                                <span class="caption-subject font-blue-madison bold uppercase "></span>
                                        </div>
                                    </div> 
                                    <div class="profile-userpic">
                                        <?php
                                        if($dataServTecCliente->image_id !='') { ?>
                                            <img src="<?php echo site_url() ?>/app_files/view/<?php echo $dataServTecCliente->image_id ?>" class="img-responsive" alt="profile-photo" > 
                                        <?php }  else { ?>
                                            <img src="assets/template/images/perfil.JPG" class="img-responsive" alt="photo" > <?php
                                        } ?> 
                                    </div>
                                <br>
                                <div class="profile-usertitle text-center ">
                                        <div class="caption-subject bold "><?php echo $dataServTecCliente->first_name . " ". $dataServTecCliente->last_name  ?></div>
                                </div> 
                                <div class="profile-usermenu">
                                    <ul class="nav">
                                        <li>
                                            <a href="javascript:void(0)">
                                            <i class="fa fa-inbox"></i> <?php echo $dataServTecCliente->email ?></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)">
                                            <i class="fa fa-mobile-phone"></i> <?php echo $dataServTecCliente->phone_number ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-9" style="margin-top: 10px;height: auto;"> 
                                <div class="col-lg-12" style="overflow: hidden;">
                                    <div class="col-lg-5" style="overflow: hidden;">
                                        <div class="portlet sale-summary box green">
                                            <div class="portlet-title padding">
                                                <div class="caption"> <?php echo lang("technical_supports_pagos_y_abonos"); ?> </div> 
                                            </div>
                                            <div class="portlet-body">
                                                <ul class="list-unstyled">
                                                    <li class="list-group-item ">
                                                        <span class="sale-info" style="font-weight: 700;"> <?php echo lang("technical_supports_abono"); ?> <i class="fa fa-img-up"></i></span>
                                                        <span class="sale-num"> <?php if($abonado!=''){  echo to_currency("{$abonado}", "2"); }else{ $abonado=0;  echo to_currency("{$abonado}", "2"); } ?> </span>
                                                    </li>
                                                    <li class="list-group-item ">
                                                        <span class="sale-info" style="font-weight: 700;"> <?php echo lang("technical_supports_repair_cost"); ?> <i class="fa fa-img-down"></i></span>
                                                        <span class="sale-num">  <?php echo to_currency("{$dataServTecCliente->repair_cost}", "2"); ?> </span>
                                                    </li>
                                                    <li class="list-group-item ">
                                                        <span class="sale-info" style="font-weight: 700;"> <?php echo lang("technical_supports_repair_mart"); ?> <i class="fa fa-img-down"></i></span>
                                                        <span class="sale-num">  <?php echo to_currency("{$totalResp}", "2"); ?> </span>
                                                    </li>
                                                    <?php 
                                                    $resta=$dataServTecCliente->repair_cost+$totalResp;  
                                                    $resta=$resta- $abonado;
                                                    ?>
                                                    <li class="list-group-item ">
                                                        <span class="sale-info" style="font-weight: 700;"> <?php echo lang("technical_supports_diferencia"); ?> </span>
                                                        <span class="sale-num"> <?php echo to_currency("{$resta}", "2"); ?>  </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet light box green col-lg-7" style="height: auto;overflow: hidden;">
                                        <div class="portlet-title padding">
                                            <div class="caption">
                                                    <i class="fa fa-tablet"></i>
                                                    <span class="caption-subject bold"><?php echo lang("technical_supports_information"); ?></span>
                                            </div>
                                        </div>
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_serial"); ?>:</span>
                                            <span class="pull-right"><?php echo $dataServTecCliente->Id_support ?></span>
                                        </li>
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_marca"); ?>:</span>
                                            <span class="pull-right"><?php echo $dataServTecCliente->marca ?></span>
                                        </li>
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_model"); ?>:</span>
                                            <span class="pull-right"><?php echo $dataServTecCliente->model ?></span>
                                        </li>
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_color"); ?>:</span>
                                            <span class="pull-right"><?php echo $dataServTecCliente->color ?></span>
                                        </li>
                                    </div>
                                    <div class="portlet light box green col-lg-12" style="height: auto;overflow: hidden;">
                                        <div class="portlet-title padding">
                                            <div class="caption">
                                                    <i class="fa fa-tablet"></i>
                                                    <span class="caption-subject bold"><?php echo lang("technical_supports_orden_information_entrega"); ?></span>
                                            </div>
                                        </div>
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_orden_fecha_garantia"); ?>:</span>
                                            <span class="pull-right">
                                            <?php
                                            if($dataServTecCliente->do_have_guarantee==1) {
                                                echo $dataServTecCliente->date_garantia;
                                            }else{
                                               echo lang("technical_supports_orden_inf_garant");
                                            }
                                            ?></span>
                                        </li> 
                                        <li class="list-group-item col-lg-6">
                                            <span class="name-item-summary"><?php echo lang("technical_supports_orden_observ"); ?>:</span>
                                            <span class="pull-right"><?php echo $dataServTecCliente->observaciones_entrega ?></span>
                                        </li>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12" style="overflow: hidden;">
                                
                                    <div class="portlet light box green col-lg-6" style="height: auto;overflow: hidden;">
                                        <div class="portlet-title padding">
                                            <div class="caption">
                                                    <i class="fa fa-tablet"></i>
                                                    <span class="caption-subject bold"><?php echo lang("technical_supports_entrega_title_diag"); ?></span>
                                            </div>
                                        </div> 
                                        <?php if($dataServTecCliente->technical_failure!='') { ?>
                                            <li class="list-group-item">
                                                <span class="name-item-summary"><?php echo $dataServTecCliente->technical_failure ?></span> 
                                            </li>
                                        <?php }
                                        foreach ($dataDiagnostico->result() as $dataDiagnostico) {  ?>
                                            <li class="list-group-item">
                                                <span class="name-item-summary"><?php echo $dataDiagnostico->diagnostico ?></span> 
                                            </li>
                                        <?php } ?>
                                    </div>
                                    <?php //if($dataServTecCliente->state =="REPARADO") { ?>
                                        <div class="portlet light box green col-lg-6" style="height: auto;overflow: hidden;">
                                            <div class="portlet-title padding">
                                                <div class="caption">
                                                        <i class="fa fa-tablet"></i>
                                                        <span class="caption-subject bold"><?php echo lang("technical_supports_entrega_title_resp"); ?></span>
                                                </div>
                                            </div>
                                            <?php foreach ($dataRespuesto->result() as $dataRespuesto) {  ?>
                                                <li class="list-group-item" style="overflow: hidden;height: auto;">
                                                    <span class="name-item-summary"><?php echo $dataRespuesto->name ?></span> 
                                                </li>
                                            <?php } ?>
                                            <?php foreach ($dataRespuestomas->result() as $dataRespuestomas) {  ?>
                                                <li class="list-group-item" style="overflow: hidden;height: auto;">
                                                    <div class="green col-lg-8" ><span class="name-item-summary"><?php echo $dataRespuestomas->name ?></span> </div>
                                                    <div class="green col-lg-1" style="text-align: center;"> <?php echo $dataRespuestomas->respuesto_cantidad; ?> </div>
                                                    <div class="green col-lg-3" > <?php echo to_currency("{$dataRespuestomas->repuesto_total}", "2"); ?> </div>
                                                </li>
                                            <?php } ?>
                                        </div>
                                    <?php //} ?>
                                    
                                </div>
                                <?php if($dataServTecCliente->ubi_equipo!='') { ?>
                                <div class="col-lg-12" style="overflow: hidden;">
                                
                                    <div class="portlet light box green col-lg-12" style="height: auto;overflow: hidden;">
                                        <div class="portlet-title padding">
                                            <div class="caption">
                                                    <i class="fa fa-tablet"></i>
                                                    <span class="caption-subject bold"><?php echo lang("technical_supports_orden_ent_ubi_eq"); ?></span>
                                            </div>
                                        </div>  
                                            <li class="list-group-item">
                                                <span class="name-item-summary"><?php echo $dataServTecCliente->ubi_equipo ?></span> 
                                            </li> 
                                    </div>                                
                                    
                                </div>
                                <?php } ?>
                                <div class="portlet light profile-sidebar-portlet bordered" style="height: auto;overflow: hidden;">
                                    <div class="portlet-title"> 
                                        <div class="form-group">
                                            <label class="col-lg-12 control-label"><?php echo lang("technical_supports_orden_ret_por"); ?></label>
                                            <div class="col-lg-12">
                                                <input type="text" value="<?php echo $dataServTecCliente->retirado_por ?>" placeholder="<?php echo lang("technical_supports_orden_ret_por"); ?>" id="nota" name="nota" class="form-control" required>
                                            </div> 
                                        </div>  
                                    </div>
                                </div> 
                                <div class="portlet light profile-sidebar-portlet bordered" style="height: auto;overflow: hidden;">
                                    <div class="portlet-title">
                                        <div class="text-right">
                                            <?php if($dataServTecCliente->state =="REPARADO" Or $dataServTecCliente->state =="RECHAZADO") { ?>
                                            <a href="javascript:void(0);"  onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/detalles_serv_tecnico/','Entregar=1&hc=<?php echo $dataServTecCliente->Id_support ?>&nota='+$('#nota').val(),'ventanaVer','Estas seguro de entragar el equipo');">
                                            <button class="btn btn-danger"><span class="icon"><i class="fa fa-user"></i></span> <?php echo lang("technical_supports_orden_boton_entregar"); ?></button>
                                            </a>
                                            <?php }   ?>
                                            <a href="javascript:void(0);"  onclick="controler('<?php echo site_url() ?>/technical_supports/imprime_order/','idOrd=<?php echo $dataServTecCliente->Id_support ?>&ventana=1','impritOrdenc','');">
                                            <button class="btn btn-danger"><span class="icon"><i class="fa fa-print"></i></span> <?php echo lang("technical_supports_orden_boton_imp"); ?></button>
                                            </a> 
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
</div>
<?php }
if($dataServTecCliente->state =="ENTREGADO") { ?>
<script type="text/javascript"> 
        $('#apcion<?php echo $dataServTecCliente->Id_support; ?>').load("<?php echo site_url() ?>/technical_supports/ver_opciones/","supprt=<?php echo $dataServTecCliente->Id_support; ?>&cambOpcion=1");        
</script>
<?php } ?>