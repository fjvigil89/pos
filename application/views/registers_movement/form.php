<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-money'></i>
				<?php echo lang('cash_flows'); ?>
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
						<?php echo lang("cash_flows_information")." - ".$text_info; ?>
					</span>
				</div>
				<div class="tools">							
					<a href="" class="collapse" data-original-title="" title=""></a>							
					<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>	
				</div>
			</div>

			<div class="portlet-body form">		
				<?php echo form_open_multipart('registers_movement/save/'.$operation,array('id'=>'registers_form','class'=>'form-horizontal'));?>				
					<div class="form-body">	
						<h4><?php echo lang('common_fields_required_message'); ?></h4>

						<?php if ($location_registers > 0) {?>
							
							<div class="form-group">	
								<?php echo form_label('<a class="tooltips" data-placement="left" title="'.lang("sales_register").'">'.lang('sales_register').'</a>'.':', 'cash',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">

									<div class="btn-group">
									
										<?php echo form_dropdown('register_id', $location_registers,'','id="location_registers" class="bs-select form-control"'); ?>
								   								
									</div>
								</div>
							</div>	
						<?php } ?>
						<div class="form-group">	
								<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="Categoría a registrar">'.lang('config_categoria').'</a>'.':', 'categoria',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-2">
									<div class="btn-group">									
										<?php echo form_dropdown('categorias_gastos', $categorias_gastos,'','id="categorias_gastos" class="bs-select form-control"'); ?>
								   								
									</div>
								</div>
								<div class="col-md-7"  id="others_category" style="display:none;" >
									<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("sales_others_help").'">'.lang('sales_others').'</a>'.':', 'others_category',array('class'=>'col-md-3 control-label requireds')); ?>
									<div class="col-md-6">
										
										<input id="input_others_category" type="text"  name="others_category" class="form-control form-inps">
									</div>
								</div>
						</div>	
						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("cash_flows_cash_help").'">'.lang('cash_flows_cash').'</a>'.':', 'cash',array('class'=>'col-md-3 control-label requireds')); ?>
							<div class="col-md-8">
								
								<input id="input-cash" type="number" min="0" name="cash" class="form-control form-inps">
							</div>
						</div>	
						<!--
						<?php echo form_hidden('type', $type); ?>
						-->
						<input type="hidden" name="operation" value="<?=$operation;?>">

						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("cash_flows_description_help").'">'.lang('cash_flows_description').'</a>'.':', 'description',array('class'=>'col-md-3 control-label requireds')); ?>
	
							<div class="col-md-8">
								
								<textarea id="description" name="description" class="form-control form-textarea" row="5" cols="17" ></textarea>
							</div>
						</div>
						<div class="form-group">	
								<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="">'.lang('common_imprimir_recibo').'</a>'.':', 'imprimir',array('class'=>'col-md-3 control-label ')); ?>
								<div class='col-md-9'>
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'imprimir',
												'id'=>'imprimir',
												'value'=>'1',
												'class'=>'md-check'));
											?>
											<label for="imprimir">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
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
				{			
					$("#registers_form").plainOverlay('show');

					$(form).ajaxSubmit({
						success:function(response)
						{
							$("#registers_form").plainOverlay('hide');
							if(response.success === true) { 
							
								toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);										
							    window.location = '<?php echo site_url('registers_movement'); ?>';		 							
							
							}else if(response.success >0){
								window.location = '<?php echo site_url('registers_movement/receipt'); ?>'+"/"+response.success;	
							} 
								
							else{
								toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
							}
						},
						dataType:'json',
						resetForm: false
					});
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

				window.location = "<?php echo site_url('registers_movement'); ?>";
			}

			$( "#categorias_gastos" ).change(function() {
				
				if ($("#categorias_gastos").val()=='<?php echo lang('otros')?>'){
					$("#others_category").show();
				}else{
					$("#others_category").hide();
				}
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>