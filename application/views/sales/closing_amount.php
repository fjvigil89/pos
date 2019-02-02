<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-lock'></i>
				<?php echo lang('sales_closing_amount')?>
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
	
	<div class="row">		
		<div class="col-md-12">
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">						
						<?php echo lang('sales_closing_amount')?>
					</div>					
				</div>
				<div class="portlet-body">
					<h5>
						<?php echo lang('sales_closing_amount_desc'); ?>
					</h5>
					<ul class="text-error" id="error_message_box"></ul>
					<?php echo form_open('sales/closeregister' . $continue, array('id'=>'closing_amount_form','class'=>'')); ?>
						<h3 class="text-success text-center">
							<?php echo sprintf(lang('sales_closing_amount_approx'), to_currency($closeout)); ?>
						</h3>					
						<br/>
						<div class="row"> 
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped text-center">
										<thead>
											<tr>
												<th style="text-align: center;"><?php echo lang('sales_denomination');?></th>
												<th style="text-align: center;"><?php echo lang('reports_count');?></th>
												<th style="text-align: center;"><?php echo lang('config_currency');?></th>
											</tr>
										</thead>
										<tbody>																			 
											<?php 
											$i=0;
											foreach($currency->result() as $currency) { ?>	
											<tr>						
												<td>
				                                	<?php echo $this->config->item('currency_symbol').form_label($currency->name, 'currency'); ?>
												</td>
											  	<td>
													<?php echo form_input(array(
														'id'=>"currency$i",
														'class'=>'form-control form-inps',
														'name'=>"currency$i",
													));?>				
												</td>                                   
											  	<td>
													<?php echo form_input(array(
														'id'=>"type_currency",
														'class'=>'form-control form-inps',
														'value'=>$currency->type_currency
													));?>
												</td>
										
											    <div id='grupo'>
													<?php echo form_input(array(
														'id'=>"quantity$i",
														'name'=>"quantity$i",
												        'type'=>'hidden',
														'value'=>$currency->name
													));?>
												</div>
											</tr>
											<?php $i++;} ?>										
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label(lang('sales_closing_amount').':', 'closing_amount',array('class'=>'control-label')); ?>
									
									<div class="input-group">
										<span class="input-group-addon">
										<i class="fa fa-dollar"></i>
										</span>
										<?php echo form_input(array(
									        'name'=>'closing_amount',
											'id'=>'closing_amount',
											
									        'class'=>'form-control form-inps',
									        'value'=>'')
									    );?>
									    <span class="input-group-btn">
											<?php echo form_button(array(
												'name'=>'submit',
												'type'=>'submit',
												'id'=>'close_submit',
												'content'=>lang('common_submit'),
												'class'=>'btn btn-success')
											);?>
										</span>
									</div>
									<span id="error"></span>
								</div>												
								<!--
								<div class="form-group text-center">
									<h2><?php echo lang('common_or'); ?></h2>
											<?php echo anchor('sales/clear_register', lang('sales_logout_without_closing_register'), array('id'=>'logout_without_closing', 'class'=>'btn btn-danger letter-space'));?>
									<br /><br />
								</div>
								-->
								<div style="clear:both;"></div>
								
								<div style="text-align: center;">
									<h1><?php echo lang('common_or'); ?></h1>					
									<input type="button" id="logout_without_closing" class="btn btn-primary" value="<?php echo lang('sales_logout_without_closing_register'); ?>">
									<br /><br />
								</div>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>


	<script type='text/javascript'>

		//validation and submit handling
		$(document).ready(function(e)
		{
			$("#closing_amount").focus();
			
			$("#closing_amount").keypress(function (e) {
			    if (e.keyCode == 13) {
			    	e.preventDefault();
			       	check_amount();
			    }
			 });

			$('#close_submit').click(function(){
				check_amount();
			});
			var submitting = false;

			$('#closing_amount_form').validate({
				rules:
				{
					closing_amount: {
						required: true,
						number: true
					}
				},
				errorClass: "text-danger",
				errorElement: "span",
				errorLabelContainer: "#error",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
				},
				messages: {
			   		closing_amount: {
						required: <?php echo json_encode(lang('sales_amount_required')); ?>,
						number: <?php echo json_encode(lang('sales_amount_number')); ?>
					}
		   		}
			});
			
			$("#logout_without_closing").click(function()
			{
				window.location = '<?php echo site_url('home/logout'); ?>';
			});
			
			function calculate_total()
			{				
			    var add = 0;
			    var total=0;
			    var contar=$('#grupo > input').length;

				for(var i=0; i<contar; i++)
				{
					currency = $("#currency" +i).val();
					quantity= $("#quantity" +i ).val();
			  
				    total+=quantity*currency;
				}

				$("#closing_amount").val(parseFloat(Math.round(total * 100) / 100).toFixed(2));
			}

		 	var contar=$('#grupo > input').length;

			for(var i=0; i<contar; i++)
			{			
				$("#currency"+i).change(calculate_total);
				$("#currency"+i).keyup(calculate_total);
			}
			
		});

		function check_amount()
		{
			if($('#closing_amount').val()==<?php echo $closeout; ?> ||
			 $('#closing_amount').val()==<?php echo to_currency_no_money($closeout); ?>) 
			{
				$('#closing_amount_form').submit();	
			}
			else
			{
				if(confirm(<?php echo json_encode(lang('closing_amount_not_equal')); ?>))
				{
					$('#closing_amount_form').submit();			
				}					
			}
		}
	</script>

<?php $this->load->view('partial/footer.php'); ?>