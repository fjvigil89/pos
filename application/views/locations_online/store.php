<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<!-- BEGIN PAGE TITLE -->

<!-- BEGIN PAGE TITLE -->
    <div class="page-title">
        <h1>
            <i class="icon fa fa-home"></i>
            <?php echo lang('module_'.$controller_name); ?>
            <a class="icon fa fa-youtube-play help_button" id='maxstore' rel='0' data-toggle="modal" data-target="#stack6"></a>
        </h1>
    </div>
    <!-- END PAGE TITLE -->		
</div>
<!-- END PAGE HEAD -->
<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->

    	<!-- END PAGE BREADCRUMB -->
        <?php echo form_open('locations_online/save/',array('id'=>'item_form','class'=>'form-horizontal ')); ?>
        <div class="" id="form">
        				<div class="portlet box green">
        				<div class="portlet-title">
        					<div class="caption">
        						<i class="icon-book-open"></i>
        						<span class="caption-subject bold">
        							Información Básica de Empleados						
                                </span>
                                <span style="margin-left:500px;">                        
                                <a class="btn btn-primary"  href="<?= site_url("tienda") ?>" target="_blank">Vista online</a>
                                </span>
        					</div>		
        				</div>
        				<div class="portlet-body">
        					<div class="tabbable-custom ">
        						<ul class="nav nav-tabs ">
        							<li class="active">
        								<a href="#tab_5_1" data-toggle="tab" aria-expanded="false">
        									General							</a>
        							</li>
            							<li class="">
            								<a href="#tab_5_2" data-toggle="tab" aria-expanded="false">
            									Plantilla  								</a>
            							</li>
        							<li class="">
        								<a href="#tab_5_3" data-toggle="tab" aria-expanded="true">
        									Redes Sociales								</a>
        							</li>    
        						</ul>
        						<div class="tab-content">
        							<div class="tab-pane active" id="tab_5_1">
        								<br>
        
        														
        			<div class="form-group has-success">
        				    <label class="requireds control-label col-md-3"><a class="help_config_required  tooltips " data-placement="left" title="" data-original-title="El nombre es un campo obligatorio ">Nombre de la Tienda</a>:					<!--<span class="required">* </span>-->
        				</label>
        			<div class="col-md-8">
        					<input type="text" name="shop" value="<?= $shop ?>" class="form-control form-inps" id="nomb_shop" aria-required="true" aria-invalid="false">
                    </div>
                    
        			</div>
        
        			<div class="form-group">
        				<label for="last_name" class="col-md-3 control-label">Descricpion:</label>				
                        <div class="col-md-8">
        				<textarea id="descricpion" name="shop_description" class="fomr-control form-inps" style="height:100px"><?= $shop_description ?></textarea>				
                        </div>
        			</div>
                    <div class="form-group">
        				<label for="last_name" class="col-md-3 control-label">Activar Tienda:</label>				
                        <div class="col-md-8">
        				<input type="checkbox" name="active_shop" value="1" id="active" <?= (empty($active_shop)|$active_shop==0) ?"":"checked" ?>  class="fomr-control ">				
                        </div>
        			</div>

        
        			</div>
        
        							<div class="tab-pane" id="tab_5_2">	
        								<br>						
        								<div class="form-group">	
        									<label for="language" class="col-md-3 control-label requireds">Plantilla:</label>									
                                            <div class="col-md-8">
        										<select class="form-control" name="shop_theme" id="theme">									
        				                            <option value="" >Selecione una plantilla</option>		                            
        				                            <option value="1">Theme 1</option>
                                                    <option value="2">Theme 2</option>																		
        										</select>
                                                <script type="text/javascript">$(document).ready(function(){$('#theme > option[value="<?php echo $shop_theme; ?>"]').attr('selected', 'selected');});</script>
                                            </div>
                                                
        								</div>

                                        
                                        
                                        
        						</div>
        
        							<div class="tab-pane " id="tab_5_3">
						
        									
        										<div class="portlet-body">	
        											<hr>
                                                    <div class="form-group">
                                        				<label for="last_name" class="col-md-3 control-label">Facebook:</label>				
                                                        <div class="col-md-8">
                                        				<input type="text" name="shop_redes_facebook" value="<?= $shop_redes_facebook ?>" class="form-control form-inps" id="nomb_shop" aria-required="true" aria-invalid="false">				
                                                        </div>
                                        			</div>
        											<br>
                                                    <div class="form-group">
                                                        <label for="last_name" class="col-md-3 control-label">Twitter:</label>				
                                                        <div class="col-md-8">
                                                        <input type="text" name="shop_redes_twitter" value="<?= $shop_redes_twitter ?>" class="form-control form-inps" id="nomb_shop" aria-required="true" aria-invalid="false">				
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <label for="last_name" class="col-md-3 control-label">Google plus+:</label>				
                                                        <div class="col-md-8">
                                                        <input type="text" name="shop_redes_google" value="<?= $shop_redes_google ?>" class="form-control form-inps" id="nomb_shop" aria-required="true" aria-invalid="false">				
                                                        </div>
                                                    </div>
                                                    
        											<div class="form-group">
        																							</div>
        										</div>
        																	</div>
        
        							</div>
        						</div>
        					</div>
        
        					
        <input type="hidden" name="redirect_code" value="0">
        					<div class="row">	
        						<div class="col-md-12">
        							<div class="pull-right">
        								<button name="submitf" type="button" id="submitf" class="btn btn-primary">Aceptar</button>							</div>
        						</div>
        					</div>
        					
        				</div>
        			</div>
        		</form>	</div>
                <script type="text/javascript">
                $(document).ready(function()
                {	
                    $("#submitf").click(function()
                    {
                        var data = $('#item_form').serialize();
                        //$("#form").plainOverlay('show');
                        //alert(data);
                        
    
                        
                $.post('<?php echo site_url("Locations_online/save");?>', data ,function(response) {
                    console.log(response);
                    if(response.success)
    				{
    					toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.item_form);
    					$("html, body").animate({ scrollTop: 0 }, "slow");
    				}
    				else
    				{
    					toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
    				}
                },"json"); 
                
                });
                });   
                </script>


                
<?php $this->load->view("partial/footer"); ?>