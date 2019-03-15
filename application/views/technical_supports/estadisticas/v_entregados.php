<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content animated bounceInRight">
        <form id="form_s" action="javascript:void(0)" onsubmit='controler("<?php echo site_url() ?>/config/tfallas", $("#form_s").serialize(), "ventanaVer","")'>    
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button"  class="btn btn-danger"  style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/config/tvfallas/','1','damage_failure',$('#ventanaVer').html(''));"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title"><?php echo lang('technical_supports_estad_vent_titu')." ".$tipoNomb; ?></h4>
                <small class="font-bold"><?php echo lang('technical_supports_estad_vent_mensj'); ?></small>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins"> 
                        <div class="ibox-content"> 
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label style="border-bottom: 2px solid #EEEEEE;background: #FAFAFA;padding: 7px;" class="col-lg-12 control-label"><?php echo lang('technical_supports_estad_vent_label_tec'); ?></label>
                                    <?php       
                                    $vet=''; $tFalla=''; $montoRepT=0;
                                    foreach ($listarTecnico->result() as $listarTecnicos){   
                                        $T=0;$montoRep=0;
                                        foreach ($listarTotal->result() as $listarTotals){ 
                                            $tF=$listarTotals->id_technical; 
                                            if($tF==$listarTecnicos->id_technical){ 
                                                $T++; $montoRep=$montoRep+$listarTotals->repair_cost; 
                                                $montoRepT=$montoRepT+$listarTotals->repair_cost;
                                            } 
                                        }
                                        
                                        ?>  
                                        <div class="col-lg-12" style="border-bottom: 1px solid #EEEEEE;padding: 5px;">
                                            <div class="col-lg-12">
                                            <?php echo $listarTecnicos->first_name." ".$listarTecnicos->last_name." <a title='Atendio $T servicios'>(".$T.")</a>"; ?>
                                            </div>
                                            <div class="col-lg-12">
                                            <?php echo "<b>".lang('technical_supports_vent_cacum').":</b> ".  to_currency($montoRep, 2,',','.'); ?>
                                            </div>
                                        </div> 
                                        <?php
                                    } ?>    
                                    <label style="border: 2px solid #EEEEEE;;padding: 7px;" class="col-lg-12 control-label"><?php echo lang('technical_supports_vent_tcob').": ".to_currency($montoRepT, 2,',','. '); ?></label>
                                </div> 
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label style="border-bottom: 2px solid #EEEEEE;background: #FAFAFA;padding: 7px;" class="col-lg-12 control-label"><?php echo lang('technical_supports_estad_vent_label_falla'); ?></label>
                                    <?php                                                                      
                                    foreach ($listarFallas->result() as $listarFallas){ 
                                        $F=0;
                                        foreach ($listarTotal->result() as $listarTotals){ 
                                            $tF=$listarTotals->damage_failure; 
                                            if($tF=="$listarFallas->damage_failure"){ $F++; }
                                        }
                                        ?> 
                                        <div class="col-lg-12" style="border-bottom: 1px solid #EEEEEE;padding: 5px;">
                                            <?php echo $listarFallas->damage_failure." <a title='Atendido'>(".$F.")</a>"; ?>
                                        </div> 
                                        <?php
                                    } ?>
                                </div> 
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label style="border-bottom: 2px solid #EEEEEE;background: #FAFAFA;padding: 7px;" class="col-lg-12 control-label"><?php echo lang('technical_supports_estad_vent_label_tserv'); ?></label>
                                    <?php                                                                      
                                    foreach ($listarServ->result() as $listarServ){ 
                                        $F=0;
                                        foreach ($listarTotal->result() as $listarTotals){ 
                                            $tF=$listarTotals->type_team; 
                                            if($tF=="$listarServ->type_team"){ $F++; }
                                        }
                                        ?> 
                                        <div class="col-lg-12" style="border-bottom: 1px solid #EEEEEE;padding: 5px;">
                                            <?php echo $listarServ->type_team." <a title='Atendido'>(".$F.")</a>"; ?>
                                        </div> 
                                        <?php
                                    } ?>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
             
        </div>
        </form>
            
        </div>
        
    </div>
</div>