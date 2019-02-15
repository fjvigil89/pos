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
								<?php echo form_label(lang('suppliers_supplier').' :', 'supplier_input', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
									<?php echo form_input(array(
										'name'=>'supplier_input',
										'id'=>'supplier_input',
										'class'=>'form-control',
										'size'=>'10',
										'value'=>''));
									?>																		
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('reports_date_range').":", 'report_date_range_label', array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 align-vertical">
									<div class="md-radio">
										<input type="radio" id="simple_radio" name="report_type" value="simple" class="md-radiobtn" checked="checked">
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
								<?php echo form_label(lang('reports_hide_items').':', 'hide_items',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">	
											<?php echo form_checkbox(array(
												'name'=>'hide_items',
												'id'=>'hide_items',
												'value'=>'hide_items',
											));?>
											<label for="hide_items">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>
											</label>
										</div>									
									</div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('reports_pull_payments_by').':', 'pull_payments_by',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_dropdown('pull_payments_by',array('payment_date' => lang('reports_payment_date'), 'sale_date' => lang('reports_sale_date')), '', 'id="pull_payments_by" class="form-control"'); ?>
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

		$('#start_date').datetimepicker({
			format: 'YYYY-MM-DD',
			locale: "es"
		});

		$('#end_date').datetimepicker({
			format: 'YYYY-MM-DD',
			locale: "es"
		});
		
		$(document).ready(function()
		{
			$("#supplier_input").select2(
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
						data.unshift({label:<?php echo json_encode('--'.lang('common_all').'--'); ?>, value: -1});
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
				var supplier_id = $("#supplier_input").val() ? $("#supplier_input").val() : -1;
				var hide_items = $("#hide_items").prop('checked') ? 1 : 0;
				
				var start_date = $("#start_date").val();
				var end_date = $("#end_date").val();
				var pull_payments_by = $("#pull_payments_by").val();
				
				if ($("#simple_radio").prop('checked'))
				{
					window.location = window.location+'/'+supplier_id+'/'+$("#report_date_range_simple option:selected").val()+ '/'+hide_items + '/'+pull_payments_by;
				}
				else
				{
					var start_date = $("#start_date").val();
					var end_date = $("#end_date").val();

					window.location = window.location+'/'+supplier_id+'/'+start_date+'/'+end_date+'/'+hide_items + '/'+pull_payments_by;
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