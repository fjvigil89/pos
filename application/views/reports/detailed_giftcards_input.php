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
							<?php echo lang('reports_report_input'); ?>
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
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("specific_input_name_help1").'">'.$specific_input_name.'</a>', 'specific_input_name_label', array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 " id='controls'>								
									<?php echo form_input(array(
										'name'=>'specific_input_data',
										'id'=>'specific_input_data',
										'class' => 'form-control',
										'size'=>'10',
										'value'=>''));
									?>									
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
		$(document).ready(function()
		{
			$('#export_excel_yes').change(function () {
			 	
			 	$("#export_pdf_no").prop("checked", true);
			 });
			 $('#export_pdf_yes').change(function () {
			 	
			 	$("#export_excel_no").prop("checked", true);
			 });
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

			$("#generate_report").click(function()
			{
				var specific_id = $("#specific_input_data").val() ? $("#specific_input_data").val() : -1;
				
				var export_excel = 0;
				var export_pdf = 0;
				if ($("#export_excel_yes").prop('checked'))
				{
					export_excel = 1;
				}
				if ($("#export_pdf_yes").prop('checked'))
				{
					export_pdf = 1;
				}
				var url window.location+'/'+ specific_id + '/'+ export_excel+'/'+export_pdf;
				if($("#export_pdf_yes").prop('checked'))
						window.open(url);
					else
						window.location =url;

			});	
		});
	</script>

<?php $this->load->view("partial/footer"); ?>