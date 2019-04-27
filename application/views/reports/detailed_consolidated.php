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
								<?php echo form_label(lang('reports_fixed_range').': ', 'range',array('class'=>'col-md-3 control-label')); ?>
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
									<?php echo form_dropdown('report_date_range_simple',array(), '', 'id="report_date_range_simple" class="form-control"'); ?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('reports_custom_range').' :', 'range',array('class'=>'col-md-3 control-label')); ?>
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
			                                    <input type="text" class="form-control start_date" name="start_date" id="start_date" placeholder="Selecciona una fecha y hora">
			                                </div>
										</div>
										<div class="col-md-6">
											<div class="input-group input-daterange" id="reportrange1">
		                                    	<span class="input-group-addon">
			                                   		Hasta
			                                    </span>
		                                    	<input type="text" class="form-control end_date" name="end_date" id="end_date" placeholder="Selecciona una fecha y hora">
		                                	</div>	
										</div>
									</div>									
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('reports_select_by_empleados').': ', 'report',array('class'=>'col-md-3 control-label')); ?>
		                     	<div class="col-md-9">
									<?php echo form_dropdown('reports_select_by_empleados',array(), '', 'id="reports_select_by_empleados" class="form-control"'); ?>
						       </div>	
					       	</div>

					       	<div class="form-group">	
								<?php echo form_label(lang('reports_show_manual_adjustments_only'), 'show_manual_adjustments_only',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'show_manual_adjustments_only',
												'id'=>'show_manual_adjustments_only',
												'value'=>'show_manual_adjustments_only',
											));?>
											<label for="show_manual_adjustments_only">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>
											</label>
										</div>									
									</div>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('reports_export_to_excel').' :', 'reports_export_to_excel', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9 align-vertical">
									<div class="md-radio-inline">
										<div class="md-radio">
											<input type="radio" name="export_excel" id="export_excel_yes" class="md-radiobtn" value='1' />
											<label for="export_excel_yes">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>	
												<?php echo lang('common_yes'); ?>									
											</label>
										</div>
										<div class="md-radio">
											<input type="radio" name="export_excel" id="export_excel_no" class="md-radiobtn" value='0' checked='checked' /> 
											<label for="export_excel_no">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>	
												<?php echo lang('common_no'); ?>									
											</label>
										</div>
									</div>
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
				var export_excel = 0;
				var show_manual_adjustments_only = $('#show_manual_adjustments_only').prop('checked') ? 1 : 0;
				var sh = $('#reports_select').prop('checked') ? 1 : 0;
				
				if ($("#export_excel_yes").prop('checked'))
				{
					export_excel = 1;
				}				

				if ($("#simple_radio").prop('checked'))
				{
					var url = window.location+'/'+$("#report_date_range_simple option:selected").val() + '/'+show_manual_adjustments_only+'/'+export_excel+'/'+$("#reports_select_by_empleados  option:selected").val() ;
					if($("#export_pdf_yes").prop('checked'))
						window.open(url);
					else
						window.location =url;
				}

				else
				{
					var start_date = $("#start_date").val();
					var end_date = $("#end_date").val();

					var url = window.location+'/'+start_date + '/'+ end_date + '/'+ show_manual_adjustments_only + '/'+export_excel+'/'+$("#reports_select_by_empleados  option:selected").val() ;
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
			
			$("#reports_select").change(function()
			{
				$("#report_select").prop('checked', true);
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>