<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-money'></i>
				<?php echo "Saldo de la cuenta"; ?>
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

	
	<div id="form">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-book-open"></i>
					<span class="caption-subject bold">
						Saldo del empleado
					</span>
				</div>
				<div class="tools">							
					<a href="" class="collapse" data-original-title="" title=""></a>							
					<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>	
				</div>
			</div>

			<div class="portlet-body form">		
				<?php echo form_open_multipart('changes_house/save_balance/',array('id'=>'registers_form','class'=>'form-horizontal'));?>				
					<div class="form-body">	
						<h4><?php echo lang('common_fields_required_message'); ?></h4>

						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="">Empleado</a>'.':', 'cash',array('class'=>'col-md-3 control-label requireds')); ?>
							<div class="col-md-8">
								
							<?php echo form_dropdown('empleado', $empleados,'','id="empleado_id" class="bs-select form-control"'); ?>

							</div>
						</div>	
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("cash_flows_cash_help").'">'.lang('cash_flows_cash').'</a>'.':', 'cash',array('class'=>'col-md-3 control-label requireds')); ?>
							<div class="col-md-8">
								
								<input id="input-cash" type="text" name="cash" class="form-control form-inps">
							</div>
						</div>	
						
						<input type="hidden" name="operation" value="<?=$operation;?>">

						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("cash_flows_description_help").'">'.lang('cash_flows_description').'</a>'.':', 'description',array('class'=>'col-md-3 control-label requireds')); ?>
	
							<div class="col-md-8">
								
								<textarea id="description" name="description" class="form-control form-textarea" row="5" cols="17" ></textarea>
							</div>
						</div>
					</div>

					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">								

									<button id="submit-button" name="submit" class="btn green"><?=lang('common_submit');?></button>
									<button id="cancel-button"  type="button" class="btn default"><?=lang('common_cancel');?></button>
									
							</div>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>


	<script type='text/javascript'>
		$(document).ready(function()
		{	
			//Adicionar evento al boton cancel	
			$("#cancel-button").click(cancel_operation);
				
			$('#registers_form').validate({
				
				submitHandler:function(form)
				{	if($.isNumeric( $("#empleado_id").val())){
						$("#registers_form").plainOverlay('show');

					$(form).ajaxSubmit({
						success:function(response)
						{
							$("#registers_form").plainOverlay('hide');
							if(response.success == true) { 
							
								toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);										
							    window.location = '<?php echo site_url('changes_house/saldo/'.$operation); ?>';									
							
							} else {	
							
								toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
							}
						},
						dataType:'json',
						resetForm: false
					});
				}else{
					toastr.error("Seleccione empleado", <?php echo json_encode(lang('common_error')); ?>);

				}
			    },	
				rules: 
			  	{					
					cash: {
						required: true,
						number: true
					},
					description: "required"
		       	},
				errorClass: "text-danger",
				errorElement: "span",
				
				highlight : function(element, errorClass, validClass) {
				
					$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
				
				},
				unhighlight : function(element, errorClass, validClass) {
					 
					$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
				},
				messages: 
				{
					cash: {
						required: <?php echo json_encode(lang('cash_flows_cash_required')); ?>,
						number: <?php echo json_encode(lang('cash_flows_cash_number')); ?>
					},
					
			        description: <?php echo json_encode(lang('cash_flows_description_required')); ?>,
				}
			});

			function cancel_operation() {

				window.location = "<?php echo site_url('changes_house'); ?>";
			}
		});
	</script>

<?php $this->load->view("partial/footer"); ?>