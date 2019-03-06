<?php 
$recibido=0;$recib=1;$diagnostico=0;$diag=1;$rechazado=0;$rechz=1;$repadados=0;$rep=1;
foreach ($servicios as $servicios){ 
    if ($servicios->state == lang("technical_supports_recibido")) {
        $recibido=$recib++;
    }
    if ($servicios->state == lang("technical_supports_diagnosticado")) {
        $diagnostico=$diag++;
    }
    if ($servicios->state == lang("technical_supports_rechazado")) {
        $rechazado=$rechz++;
    }
    if ($servicios->state == lang("technical_supports_reparado")) {
        $repadados=$rep++;
    }
}   ?>
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
     
    <div class="dashboard-stat blue">
            <div class="visual">
                    <i class="fa fa-download"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.500em;">  
                            <?php echo lang("technical_supports_recibido"); ?>
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                            <?php 
                            echo lang('common_total').": <strong>".$recibido." </strong>";
                            ?>
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
    
</div> 
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
    
    <a onclick='controler("<?php echo site_url() ?>/technical_supports/ver_estadisticas_entregados","id_tipo=DIAGNOSTICADO","ventanaVer","")' title="Hacer clic para ver el registro detallado" href="javascript:void(0)" >
    <div class="dashboard-stat blue-madison">
            <div class="visual">
                    <i class="fa fa-wrench"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.300em;">  
                            <?php echo lang("technical_supports_diagnosticado"); ?>
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                            <?php 
                            echo lang('common_total').": <strong>".$diagnostico." </strong>";
                            ?>
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
    </a>
    
</div>

<a onclick='controler("<?php echo site_url() ?>/technical_supports/ver_estadisticas_entregados","id_tipo=REPARADO","ventanaVer","")' title="Hacer clic para ver el registro detallado" href="javascript:void(0)" >
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
     
    <div class="dashboard-stat green">
            <div class="visual">
                    <i class="fa fa-credit-card"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.500em;">  
                            <?php echo lang("technical_supports_reparado"); ?>
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                            <?php 
                            echo lang('common_total').": <strong>".$repadados." </strong>";
                            ?>
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
    
</div>
</a>   
<a onclick='controler("<?php echo site_url() ?>/technical_supports/ver_estadisticas_entregados","id_tipo=RECHAZADO","ventanaVer","")' title="Hacer clic para ver el registro detallado" href="javascript:void(0)" >
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
     
    <div class="dashboard-stat red">
            <div class="visual">
                    <i class="fa fa-phone-square"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.500em;">  
                            <?php echo lang("technical_supports_rechazado"); ?>
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                            <?php 
                            echo lang('common_total').": <strong>".$rechazado." </strong>";
                            ?>
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
     
</div>
</a>
<a onclick='controler("<?php echo site_url() ?>/technical_supports/ver_estadisticas_entregados","id_tipo=ENTREGADO","ventanaVer","")' title="Hacer clic para ver el registro detallado" href="javascript:void(0)" >
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
     
    <div class="dashboard-stat  purple">
            <div class="visual">
                    <i class="fa fa-users"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.500em;">  
                            <?php echo lang("technical_supports_entregado"); ?>
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                            <?php 
                            echo lang('common_total').": <strong>".$serviciosStatus." </strong>";
                            ?>
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
    
</div> 
</a>
<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
     
    <div class="dashboard-stat blue-madison">
            <div class="visual">
                    <i class="fa fa-bar-chart"></i>
            </div>
            <div class="details">
                    <div class="number" style="font-size: 1.500em;">  
                            ESTADISTICAS
                    </div>
                    <!-- BEGIN STATISTICS -->
                    <div class="desc">
                             
                    </div>
                    <!-- END STATISTICS -->
            </div>
            <div class="more">
                    <?php echo lang('common_more_info_serv_act'); ?> <i class="m-icon-swapright m-icon-white"></i>
            </div>
    </div>
    
</div>
<?php /*
<div class="portlet-title padding">
    <div class="caption">
            <i class="fa fa-tablet"></i>
            <span class="caption-subject bold"><?php echo lang("technical_supports_information_resumen"); ?></span>
    </div>
</div>
<li class="list-group-item col-lg-3">
    <span class="name-item-summary"><?php echo lang("technical_supports_recibido"); ?>:</span>
    <span class="pull-right"><?php echo $recibido; ?></span>
</li>
<li class="list-group-item col-lg-3">
    <span class="name-item-summary"><?php echo lang("technical_supports_diagnosticado"); ?>:</span>
    <span class="pull-right"><?php echo $diagnostico; ?></span>
</li>
<li class="list-group-item col-lg-3">
    <span class="name-item-summary"><?php echo lang("technical_supports_rechazado"); ?>:</span>
    <span class="pull-right"><?php echo $rechazado; ?></span>
</li> 
<li class="list-group-item col-lg-3">
    <span class="name-item-summary"><?php echo lang("technical_supports_reparado"); ?>:</span>
    <span class="pull-right"><?php echo $repadados; ?></span>
</li> 
*/ ?>