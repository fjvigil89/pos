<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-bar-chart-o"></i>
				<?php echo lang('reports_report_input'); ?>
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

	<div class="row" id="form">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-align-justify"></i>
						<span class="caption-subject bold">
							<?php echo lang('reports_date_range'); ?>
						</span>
					</div>					
				</div>
				<div class="portlet-body form">
					<?php
						if(isset($error))
						{
							echo "<div class='error_message'>".$error."</div>";
						}
					?>
					<?php echo form_open('',['class'=>'form-horizontal']);?>
						<div class="form-body">
							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("reports_fixed_range_help").'">'.lang('reports_fixed_range').'</a>'.': ', 'range',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 align-vertical">
									<div class="md-radio">
										<input type="radio" name="report_type" id="simple_radio" value='simple' class="md-radiobtn" checked='checked'/>
										<label for="simple_radio">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
										</label>
									</div>
									<br/>
									<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, '', 'id="report_date_range_simple" class="form-control"'); ?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("reports_custom_range_help").'">'.lang('reports_custom_range').'</a>'.' :', 'range',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 align-vertical">
									<div class="md-radio">
										<input type="radio" id="complex_radio" name="report_type" class="md-radiobtn" value='complex'>
										<label for="complex_radio">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>										
										</label>
									</div>
									<br/>
									<div class="row">
										<div class="col-md-6 margin-bottom-05">
											<div class="input-group input-daterange" id="reportrange">
			                                    <span class="input-group-addon">
						                        	Desde					                       	
					                           	</span>
			                                    <input type="text" class="form-control start_date" name="start_date" id="start_date" placeholder="Selecciona una fecha">
			                                </div>
										</div>
										<div class="col-md-6">
											<div class="input-group input-daterange" id="reportrange1">
		                                    	<span class="input-group-addon">
			                                   		Hasta
			                                    </span>
		                                    	<input type="text" class="form-control end_date" name="end_date" id="end_date" placeholder="Selecciona una fecha">
		                                	</div>	
										</div>
									</div>									
								</div>
							</div>

							

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_sale_type_help").'">Tienda</a>'.' :', 'reports_store_label', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
									<?php 
									$tiendas=array("all"=>"Todo");
									foreach( $authenticated_locations as $id=>$loctation){
										$tiendas[$id]=$loctation;
									}
									
									echo form_dropdown('store',$tiendas, 'all', 'id="store" class="form-control"'); ?>
								</div>
							</div>
                           
							

							
							

						</div>

						<div class="form-actions">
							<div class="pull-right">
							<?php echo form_button(array(
								'name'=>'generate_report',
								'id'=>'generate_report',
								'content'=>lang('common_submit'),
								'class'=>'btn btn-success')
							);?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript" language="javascript">

		var JS_DATE_FORMAT = 'YYYY-MM-DD';
		var JS_TIME_FORMAT = "H:mm:s";

		$('#start_date').datetimepicker({
			format: JS_DATE_FORMAT+" "+JS_TIME_FORMAT,
			locale: "es"
		});

		$('#end_date').datetimepicker({
			format: JS_DATE_FORMAT+" "+JS_TIME_FORMAT,
			locale: "es"
		});
	
		$(document).ready(function()
		{
			 

			
			$("#generate_report").click(function()
			{
				var store = $("#store").val();
				

				if ($("#simple_radio").prop('checked'))
				{
					var url= window.location+'/'+$("#report_date_range_simple option:selected").val()+ '/' +  store;

					if($("#export_pdf_yes").prop('checked'))
						window.open(url);
					else
						window.location =url;
				}
				else
				{
					var start_date = $("#start_date").val();
					var end_date = $("#end_date").val(); 
					var url=  window.location+'/'+start_date + '/'+ end_date + '/' + store;

					if($("#export_pdf_yes").prop('checked'))
						window.open(url);
					else
						window.location =url;		

				}
			});

			$("#start_date").click(function()
			{
				$("#complex_radio").prop('checked', true);
			});

			$("#end_date").click(function(){
				$("#complex_radio").prop('checked', true);
			}); 

			$("#report_date_range_simple").change(function()
			{
				$("#simple_radio").prop('checked', true);
			});

			

		});
	</script>

<?php $this->load->view("partial/footer"); ?>