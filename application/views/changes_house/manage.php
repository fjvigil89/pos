<?php $this->load->view("partial/header"); ?>
<style>
.aprobada {
	color: #17202A;
	background-color: rgb(0, 255, 109  ,0.2);
}
.aprobada:hover {
	color: #17202A;
	background-color: rgb(15, 240, 111  ,0.4);
}
.rechazada {
	color: #17202A;
	background-color: rgb(245, 183, 177,0.5);
}
.rechazada:hover {
	color: #17202A;
	background-color: rgb(245, 183, 177  ,0.8);
}
.pendiente {
	color: #17202A;
	background-color: rgb(236, 247, 238 ,0.1);
}
.pendiente:hover {
	color: #17202A;
	background-color: rgb(236, 247, 238 ,0.5);
}
.entregado {
	color: #17202A;
	background-color: rgb(0, 255, 109  ,0.5);
}
.entregado:hover {
	color: #17202A;
	background-color: rgb(15, 240, 111  ,0.7);
}
.procesando {
	color: #17202A;
	background-color:#f4f916de;
}
.procesando:hover {
	color: #17202A;
	background-color: yellow;
}

</style>
<script type="text/javascript">
$(document).ready(function(){
	var table_columns = ["invoice_number","titular_cuenta",'tipo_documento','numero_documento','numero_cuenta','divisa','',''];	
	enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir);?>);
});
</script>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-exchange"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<!--<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack888"></a>-->
				<?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=OkF8D_69e1E";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("CASA DE CAMBIO");
						if($url_video!=null){
							$url_video_ver=$url_video;
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				?>

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
		<div class="col-md-12">
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">
						<span class="icon">
							<i class="fa fa-th"></i>
						</span>
						<?php echo lang('common_list_of').' transacciones'; ?>

					</div>
					<div class="tools">
						<span data-original-title="<?php echo $total_rows; ?> total <?php echo $controller_name?>" class="label label-primary tip-left tooltips"><?php echo $total_rows; ?></span>
						
					</div>
				</div>
				<div class="portlet-body">

					<div class="row">
						<div class="col-md-12 ">
							<div class="pull-right margin-bottom-10">
								<div class="btn-group">
									<?php if ($this->Employee->has_module_action_permission($controller_name, 'edit_balance_employee', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
					
										<?php echo 
											anchor("$controller_name/saldo/add",
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title="Ingresal saldo"></i> <span class="visible-lg">Agregar saldo</span>',
											array('class'=>'btn btn-medium green-seagreen effect', 
												'title'=>"Saldo"));
										?>
										
										<?php echo 
											anchor("$controller_name/saldo/restar",
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title="restar saldo"></i> <span class="visible-lg">Restar saldo</span>',
											array('class'=>'btn hidden-xs btn-danger', 
												'title'=>"Saldo"));
										?>

																							
									<?php } ?>					
								

									
								</div>
							</div>
						</div>
					</div>

					<div class=" row margin-bottom-10">
						
                        <?php echo form_open("$controller_name/search",array('id'=>'search_form','class'=>'form-vertical', 'autocomplete'=> 'off'),array('search_flag'=>1)); ?>
                            
							
							<div class="col-xs-12 col-md-4 ">	
                                <div class="input-group">
                                    <input type="text" title="Ingrese # orden, # cuenta, # documento, nombre clinte, o estado" name ='search' id='search' class="form-control form-inps" value="<?php echo H($search); ?>"  placeholder="<?php echo lang('common_search')." transacción"?>"/>
                                    <span class="input-group-btn">
                                        <button name="submitf" class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>										
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-2 margin-bottom-05">
                                <?php echo form_dropdown('estado', $options, $estado, 'id="estado" class="bs-select form-control"'); ?>
                            </div>
							<?php if($this->Employee->has_module_action_permission('changes_house', 'refuse_approve', $this->Employee->get_logged_in_employee_info()->person_id)) :?>
								<div class="col-xs-12 col-sm-6 col-md-4 margin-bottom-05">
                            	    <?php echo form_dropdown('empleado_id', $empleados, $estado, 'id="empleado_id" class="bs-select form-control"'); ?>
                           		 </div>
							<?php endif;?>
							<div class="col-xs-12 col-sm-6 col-md-2 margin-bottom-05">
								<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, '0000-00-00/0000-00-00', 'id="report_date_range_simple" class="bs-select form-control"'); ?>

							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 margin-bottom-05">
								<div class="input-group input-daterange" id="reportrange">
			                        <span class="input-group-addon">
						              	Desde					                       	
					               	</span>
			                        <input type="text" value="<?php echo $start_date; ?>" class="form-control start_date" name="start_date" id="start_date" placeholder="Selecciona una fecha y hora">
			                    </div>
							</div> 
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2  margin-bottom-05">
								<div class="input-group input-daterange" id="reportrange1">
		                           	<span class="input-group-addon">
			                      		Hasta
			                        </span>
		                           	<input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" name="end_date" id="end_date" placeholder="Selecciona una fecha y hora">
		                       	</div>	
							</div>

							<div class="col-xs-12 col-sm-6 col-md-2 margin-bottom-05">
                                <?php echo form_submit('submit', lang('common_search'),'class="btn btn-primary btn-block"'); ?>
                            </div>

							
							<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 margin-bottom-05 ">
                            <a href="<?php echo site_url($controller_name."/clear_state"); ?>" class="btn btn-info btn-block clear-state pull-right effect"><?php echo lang('common_clear_search'); ?></a>
                        </div>
                            							
                        </form>
                        
                    </div>


					<div class="row">
						<div class="col-md-12">	

							<?php if($pagination) {  ?>
								<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
									<?php echo $pagination;?>
								</div>
							<?php } ?>

							<div class="table-responsive" id="manage_table" >
								<?php echo $manage_table; ?>			
							</div>	

							<?php if($pagination) {  ?>
								<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
									<?php echo $pagination;?>
								</div>
							<?php } ?>
						</div>
					</div>

				</div>
			</div>
			<!-- END CONDENSED TABLE PORTLET-->
		</div>
	</div>

	<script type="text/javascript">
	
	  	$(document).ready( function (){
			  
			var JS_DATE_FORMAT = 'YYYY-MM-DD';
		//var JS_TIME_FORMAT = "H:mm";

		$('#start_date, #end_date').datetimepicker({
			format: JS_DATE_FORMAT,
			locale: "es"
		});

		

		$("#report_date_range_simple").change(function(){
			
			let fechas= $("#report_date_range_simple").val().split("/");
			$("#start_date").val(fechas[0]);
			$("#end_date").val(fechas[1]);

		})

			
		});

		           

      
  	</script>

<?php $this->load->view("partial/footer"); ?>

