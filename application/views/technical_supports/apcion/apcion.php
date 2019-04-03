<?php 
if($cambOpcion=='1'){
    foreach ($servicios->result() as $servicios) {  
        if ($servicios->state == "ENTREGADO") { ?> 
            <script type="text/javascript">
            $('#apcioned<?php echo $servicios->Id_support; ?>').html('');
            </script>
            <a class="btn btn-xs btn-block btn-success" href="javascript:void(0);" title="<?php echo lang("technical_supports_title_detalle"); ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/detalles_serv_tecnico/','hc=<?php echo $servicios->Id_support; ?>','ventanaVer','');"> 
            <i class="fa fa-search"></i>
            </a>
        <?php } 
    } 
}
if($verListaDiagnostico=='1'){
    foreach ($dataSupport->result() as $dataSupport) { $status=$dataSupport->state; $IdSupport=$dataSupport->Id_support; $diagTec=$dataSupport->technical_failure; } ?>
    <table class="table table-bordered"> 
            <tbody>
            <?php if($diagTec!=''){ 
                ?>
                <tr>
                    <td style="width: 80%;"><?php echo $diagTec; ?></td>
                    <td>
                        <a href="javascript:void(0);"  onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/','eld=<?php echo $IdSupport; ?>&ant=1&supprt=<?php echo $IdSupport; ?>','asigDiagnostico','<?php echo lang("technical_supports_menj_detalle_eli"); ?>');">
                        <button class="btn btn-danger"><span class="icon"><i class="fa fa-trash"></i></span></button>
                        </a>
                    </td> 
                </tr>
            <?php 
            }
            foreach ($dataDiagnostico->result() as $dataDiagnostico) {
                ?>
                <tr>
                    <td style="width: 75%;"><?php echo $dataDiagnostico->diagnostico ?></td>
                    <td style="text-align: center;width: 25%;">
                        <a href="javascript:void(0);"  onclick="controler('<?php echo site_url() ?>/technical_supports/actualizar_diagnostico/','act=<?php echo $dataDiagnostico->id; ?>&supprt=<?php echo $dataDiagnostico->id_support; ?>','ventanaVer','');">
                            <span class="icon" style="font-size: 1.500em;"><i class="fa fa-edit"></i></span>
                        </a>
                        <a href="javascript:void(0);"  onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/','eld=<?php echo $dataDiagnostico->id; ?>&supprt=<?php echo $dataDiagnostico->id_support; ?>','asigDiagnostico','<?php echo lang("technical_supports_menj_detalle_eli"); ?>');">
                            <span class="icon" title="<?php echo lang("technical_supports_title_detalle_eli"); ?>" style="margin-left: 10px;font-size: 1.500em;"><i class="fa fa-trash"></i></span>
                        </a>
                    </td> 
                </tr>
            <?php } ?>
            </tbody>
    </table>
    <?php
}

?>

