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
								<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("specific_input_name_help2").'">'.$specific_input_name.'</a>', 'specific_input_name_label', array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 " id='controls'>	
									<?php if (isset($search_suggestion_url)) {?>							
										<?php echo form_input(array(
											'name'=>'specific_input_data',
											'id'=>'specific_input_data',
											'class' => 'form-control',
											'size'=>'10',
											'value'=>''));
										?>		
									<?php } else { ?>
										<?php echo form_dropdown('specific_input_data',$specific_input_data, '', 'id="specific_input_data" class="form-control"'); ?>
									<?php } ?>							
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_sale_type_help").'">'.lang('reports_sale_type').'</a>'.' :', 'reports_sale_type_label', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
									<?php echo form_dropdown('sale_type',array('all' => lang('reports_all'), 'sales' => lang('reports_sales'), 'returns' => lang('reports_returns')), 'all', 'id="sale_type" class="form-control"'); ?>
								</div>
							</div>
                            <?php /*$giftcard_payment_row = explode(':', $payment['payment_type']);*/

                             		$data['payment_options']=array(
                lang('0') => 'Seleccionar todo',            			
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit'),
				lang('sales_store_account') => lang('sales_store_account'),
				lang('sales_supplier_credit') => lang('sales_supplier_credit'));


                            ?>
							<div id="payment_type_show" class="form-group" style="display:none;">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_sale_type_help").'">Metodo de pago</a>'.' :', 'reports_sale_type_label', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
								<?php echo form_dropdown('payment_type',$data['payment_options'],'', 'id="payment_types" class="bs-select form-control"');?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("employees_employee_type_help").'">'.lang('employees_employee_type').'</a>'.' :', 'reports_employee_type_label', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
									<?php echo form_dropdown('employee_type',array( 'sale_person' => lang('employees_sale_person'), 'logged_in_employee' => lang('employees_logged_in_employee')), 'sale_person', 'id="employee_type" class="form-control"'); ?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_export_to_excel_help").'">'.lang('reports_export_to_excel').'</a>'.' :', 'reports_export_to_excel', array('class'=>'col-md-3 control-label')); ?> 
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
							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_export_to_pdf_help").'">'.lang('reports_export_to_pdf').'</a>'.' :', 'reports_export_to_pdf', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9 align-vertical">
									<div class="md-radio-inline">
										<div class="md-radio">
											<input type="radio" name="export_pdf" id="export_pdf_yes" class="md-radiobtn" value='1' />
											<label for="export_pdf_yes">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>	
												<?php echo lang('common_yes'); ?>									
											</label>
										</div>
										<div class="md-radio">
											<input type="radio" name="export_pdf" id="export_pdf_no" class="md-radiobtn" value='0' checked='checked' /> 
											<label for="export_pdf_no">
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
		var JS_TIME_FORMAT = "H:mm";

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
			 $('#export_excel_yes').change(function () {
			 	
			 	$("#export_pdf_no").prop("checked", true);
			 });
			 $('#export_pdf_yes').change(function () {
			 	
			 	$("#export_excel_no").prop("checked", true);
			 });

			<?php
			if (isset($search_suggestion_url))
			{
			?>
				$("#specific_input_data").select2(
				{
					placeholder: <?php echo json_encode(lang('common_search')); ?>,
					id: function(suggestion){ return suggestion.value; },
					ajax: {
						url: <?php echo json_encode($search_suggestion_url); ?>,
						dataType: 'json',
					   data: function(term, page) 
						{
					      return {
					          'term': term
					      };
					    },
						results: function(data, page) {
							return {results: data};
						}
					},
					formatSelection: function(suggestion) {
						return suggestion.label;
					},
					formatResult: function(suggestion) {
						return suggestion.label;
					}
				});
			<?php
			}
			else
			{
			?>
				$("#specific_input_data").select2();		
			<?php
			}
			?>
			
			$("#generate_report").click(function()
			{
				var sale_type = $("#sale_type").val();
				var payment_types = $("#payment_types").val();
				var export_excel = 0;
				var export_pdf = 0;
				var specific_id = $("#specific_input_data").val() ? $("#specific_input_data").val() : -1;
				var employee_type = $("#employee_type").val();
				
				if ($("#export_excel_yes").prop('checked'))
				{
					export_excel = 1;

				}
				if ($("#export_pdf_yes").prop('checked'))
				{
					export_pdf = 1;
				}

				if ($("#simple_radio").prop('checked'))
				{
				//	var url=window.location+'/'+$("#report_date_range_simple option:selected").val()+ '/' + specific_id + '/' + sale_type +  '/'+ employee_type + '/' + payment_types +'/' + export_excel+ '/' + export_pdf;
					var url= window.location+'/'+$("#report_date_range_simple option:selected").val()+ '/' + specific_id + '/' + sale_type + '/' + payment_types + '/'+ employee_type + '/' + export_excel +'/' + export_pdf;

					if($("#export_pdf_yes").prop('checked'))
						window.open(url);
					else
						window.location =url;
				}
				else
				{
					var start_date = $("#start_date").val();
					var end_date = $("#end_date").val(); 
					var url=  window.location+'/'+start_date + '/'+ end_date + '/' + specific_id + '/' + sale_type + '/'  + payment_types + '/' + employee_type + '/'+ export_excel+ '/' + export_pdf;

					//var url=window.location+'/'+start_date + '/'+ end_date + '/' + specific_id + '/' + sale_type +  '/' + employee_type +'/'  + payment_types + '/'+ export_excel+ '/' + export_pdf;
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

			$("#sale_type").change(function()
			{
				var sale_type = $("#sale_type").val();
				if(sale_type=="sales"){
                 $("#payment_type_show").show();
				}else{
				 $("#payment_type_show").hide();

				 $('#payment_types > option[value="0"]').attr('selected', 'selected');
	
				}
			});


		});
	</script>

<?php $this->load->view("partial/footer"); ?>