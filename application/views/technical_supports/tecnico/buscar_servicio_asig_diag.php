<?php 
$tieneDiag="";
foreach ($dataSupport->result() as $dataSupport) { $status=$dataSupport->state; $IdSupport=$dataSupport->Id_support; $diagTec=$dataSupport->technical_failure; } 
 if($diagTec!=''){ $tieneDiag="1";  }
 foreach ($dataDiagnostico->result() as $dataDiagnostico) { $tieneDiag="1"; }
 foreach ($dataRespServ->result() as $dataRespServ) { $tieneDiag="1"; }
?>
</script>
<div class="row">
    <form action="" class="form">
        <div class="form-body">
            <div class="col-sm-4 pull-right">
                <div class="form-group">
                        <label for="estado"><?php echo lang("technical_supports_stat"); ?>: </label>
                        <?php  echo "<b>$status </b>"; 
                        ?>
                </div>
            </div>
        </div>
    </form>
</div>
<div  id="asigFallas">
    <div class="row">

        <div class="form-body">
            <div class="ibox-content"> 
            <div class="form-group">
            <form id="form_s" action="javascript:void(0)" onsubmit='controler("<?php echo site_url() ?>/technical_supports/ingresar_fallas_tecnica/", $("#form_s").serialize(), "asigDiagnostico","")'>
            <input type="hidden" name="supprt" id="supprt" value="<?php echo $IdSupport ?>"> 
            <div class="col-md-12">   
                <div class="col-md-12" style="overflow: hidden;height: auto;">
                    <input value="" name="diag" id="diag" class="form-control input-lg" style="display: block;width: 80%;float: left;" type="text" autocomplete="off" placeholder="Ingrese el diagnostico técnico">
                    <button type="submit" class=" btn green" title="Registrar diagnostico" style="height: 45px;margin-left: 5px;float: left;">                                                                
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
            <div class="form-group" id="verListDiagnostico"> </div>
            </div>
            </div>
            </div>
        </div>
    </div> 
</div>
<?php 
if($inst==1) {
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
            toastr.success(response.message, <?php echo json_encode(lang('technical_supports_diagnostico_add')); ?>);						
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    </script>    
    <?php
} 
if($elm==1) {
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
            toastr.error(response.message, <?php echo json_encode(lang('technical_supports_diagnostico_delet')); ?>); 
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    </script>    
    <?php
}

if($this->input->get('diag')=="" and $this->input->get('eld')=='' and $this->input->get('T')=='') { 
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
            toastr.error(response.message, <?php echo json_encode(lang('technical_supports_adv_ing_diagnostigo')); ?>); 
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    </script>    
    <?php  
}
if($this->input->get('eld')!='' and $tiene=='1') { 
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
            toastr.error(response.message, <?php echo json_encode(lang('technical_supports_adv_elim_diagnostigo')); ?>); 
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    </script>    
    <?php     
}

if($tieneDiag=="1") { ?>
<script type="text/javascript"> 
        $('#verListDiagnostico').load("<?php echo site_url() ?>/technical_supports/ver_opciones/","supprt=<?php echo $IdSupport; ?>&verListaDiagnostico=1");        
</script>
<?php 
}
if($status=="RECIBIDO") { ?>
<script type="text/javascript"> 
        $('#asigFallas2').html('');        
</script>
<?php } 
  
if($status=="DIAGNOSTICADO" Or $status=="APROBADO" Or $tieneDiag=="1") { ?>
<script type="text/javascript"> 
        $('#asigFallas2').load("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/","supprt=<?php echo $IdSupport; ?>");        
</script>
<?php } ?>
<script>