<?php 
if($actua=='1') { echo "<script>$.alert('Registro actualizado con exito.');</script>"; } 
foreach ($dataDiagnostico->result() as $dataDiagnostico) { ?>
<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
        <form id="form_s" action="javascript:void(0)" onsubmit='controler("<?php echo site_url() ?>/technical_supports/actualizar_diagnostico", $("#form_s").serialize(), "ventanaVer","")'>    
            <input type="hidden" name="supprt" id="supprt" value="<?php echo $dataDiagnostico->id_support; ?>">
            <input type="hidden" name="act" id="act" value="<?php echo $dataDiagnostico->id; ?>">
            <input type="hidden" name="id" id="id" value="<?php echo $dataDiagnostico->id; ?>">
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" class="btn btn-danger"  style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/technical_supports/ver_opciones/','supprt=<?php echo $dataDiagnostico->id_support ?>&verListaDiagnostico=1','verListDiagnostico',$('#ventanaVer').html(''));"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title"><?php echo lang('technical_supports_mod_diag_titu'); ?></h4>
                <small class="font-bold"><?php echo lang('technical_supports_mod_diag_titu_menj'); ?></small>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins"> 
                        <div class="ibox-content"> 
                            <div class="form-group">
                                <label class="col-lg-11 control-label"><?php echo lang('technical_supports_mod_diag_label'); ?></label>
                                <div class="col-lg-12">
                                    <input type="text" value="<?php echo $dataDiagnostico->diagnostico ?>" id="nomb" name="nomb" class="form-control" required>
                                </div> 
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
             <button type="submit" class="btn btn-primary"><?php echo lang('technical_supports_mod_diag_boton'); ?></button>
        </div>
        </form>         
            
        </div>
        
    </div>
</div>
<?php } ?>
