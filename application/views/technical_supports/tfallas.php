<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
        <form id="form_s" action="javascript:void(0)" onsubmit='controler("<?php echo site_url() ?>/config/tfallas", $("#form_s").serialize(), "ventanaVer","")'>    
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button"  class="btn btn-danger"  style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/config/tvfallas/','1','damage_failure',$('#ventanaVer').html(''));"><span aria-hidden="true">X</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title"><?php echo lang('config_company_vent_falla_titulo'); ?></h4>
                <small class="font-bold"><?php echo lang('config_company_vent_falla_titulo_menj'); ?></small>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins"> 
                        <div class="ibox-content"> 
                            <div class="form-group">
                                <label class="col-lg-1 control-label"><?php echo lang('config_company_vent_falla_label'); ?></label>
                                <div class="col-lg-10">
                                    <input type="text" value="" placeholder="Ingresa nombre de la falla" id="nomb" name="nomb" class="form-control" required>
                                </div> 
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
             <button type="submit" class="btn btn-primary"><?php echo lang('config_company_vent_falla_boton'); ?></button>
        </div>
        </form>
            
          <div class="row">
	<div class="col-sm-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<span class="icon"><i class="fa fa-th"></i></span>
					<?php echo lang('config_company_ventetqtfallas'); ?>
				</div> 
			</div>
			<div class="portlet-body table-responsive">
				<table class="table table-bordered" id="table-suppor">
					<thead>
					<tr>
						<th><?php echo lang("config_company_ventlbltfallas"); ?></th> 
						<th><?php echo lang("technical_supports_option"); ?></th>
					</tr>
					</thead> 
					<tbody>
					<?php foreach ($tservice->result() as $tservice): ?>
						<tr> 
							<td><?php echo $tservice->tfallas; ?></td> 
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);"  onclick="controler('<?php echo site_url() ?>/config/tfallas/','ed=<?php echo $tservice->id; ?>','ventanaVer','');">
                                                            <button class="btn btn-primary"><span class="icon"><i class="fa fa-edit"></i></span></button>
                                                            </a>
                                                            <a href="javascript:void(0);"  onclick="controlerConfirm('<?php echo site_url() ?>/config/tfallas/','eld=<?php echo $tservice->id; ?>','ventanaVer','Estas seguro de eliminar el registro');">
                                                            <button class="btn btn-danger"><span class="icon"><i class="fa fa-trash"></i></span></button>
                                                            </a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>  
            
        </div>
        
    </div>
</div>