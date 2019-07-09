<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-pencil'></i>
				<?php if(!$location_info->location_id) { echo lang('locations_new'); } else { echo lang('locations_update'); }?>
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

	
	<?php if (isset($needs_auth) && $needs_auth) {?>
		<?php echo form_open('locations/check_auth',array('id'=>'location_form_auth','class'=>'form-horizontal')); ?>		
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
							<?php echo lang('locations_validate_licenses'); ?>
						</span>
					</div>					
				</div>

				<div class="portlet-body form">
					<div class="form-body">	
						<div class="form-group">
							<?php echo form_label(lang('locations_purchase_email').':', 'name',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'purchase_email',
									'id'=>'purchase_email')
								);?>
							</div>	
						</div>
						
						<div class="col-md-offset-3">
							<a class="btn btn-circle green btn-xs" href="http://ingeniandoweb.com/pos" target="_blank"><?php echo lang('locations_purchase_additional_licenses'); ?> &raquo;</a>
						</div>
						
						<?php if (validation_errors()) {?>
					        <div class="alert alert-danger margin-top-10">
					        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
					            <strong><?php echo lang('common_error'); ?></strong>
					            <?php echo validation_errors(); ?>
					        </div>
				        <?php } ?>						
					</div>

					<div class="form-actions right">
						<?php echo form_button(array(
							'type'=>'submit',
							'name'=>'submitf',
							'id'=>'submitf',
							'content'=>lang('common_submit'),
							'class'=>'submit_button btn btn-primary')
						);?>
					</div>					
				</div>
			</div>
		<?php echo form_close(); ?>

	<?php } else {?>

		<?php echo form_open_multipart('locations/save/'.$location_info->location_id,array('id'=>'location_form','class'=>'form-horizontal','autocomplete'=> 'off')); ?>
			<div id="form">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-book-open"></i>
							<span class="caption-subject bold">
								<?php echo lang("locations_basic_information"); ?>
							</span>
						</div>
						<div class="tools">							
							<a href="" class="collapse" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>	
						</div>
					</div>

					<div class="portlet-body form">						
						<div class="form-body">	
							<h4><?php echo lang('common_fields_required_message'); ?></h4>

										
							<div class="form-group ">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_company_info_logo_help").'">'.lang('config_company_logo').'</a>'.':', 'company_logo', array('class'=>'col-md-3 control-label')); ?> 						
								<div class="col-md-8">
										<?php echo form_upload(array(
											'name'=>'company_logo',
											'id'=>'company_logo',
											'class' => 'file form-control',
											'multiple' => "false",
											'data-show-upload' => 'false',
											'data-show-remove' => 'false',
											'value'=>$location_info->image_id));
										?>
								</div>	
							</div>
						
						<div class="form-group">
							<?php echo form_label(lang('config_delete_logo').':', 'delete_logo',array('class'=>'col-md-3 control-label')); ?>							
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">		
										<?php echo form_checkbox(array('name'=>'delete_logo', 'id'=>'checkbox30', 'value'=>'1', 'class'=>'md-check'));?>																		
										<label for="checkbox30">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>									
								</div>
							</div>
						</div>	
							<div class="form-group">
								<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("locations_name_help").'">'.lang('locations_name').'</a>'.':', 'name',array('class'=>'col-md-3 control-label requireds')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'name',
										'id'=>'name',
										'value'=>$location_info->name)
									);?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("locations_address_help").'">'.lang('locations_address').'</a>'.':', 'address',array('class'=>'col-md-3 control-label requireds')); ?>
								<div class="col-md-8">									
									<?php echo form_textarea(array(
										'name'=>'address',
										'id'=>'address',
										'class'=>'form-textarea',
										'rows'=>'2',
										'cols'=>'30',
										'value'=>$location_info->address)
									);?>								
								</div>
							</div> 
							<div class="form-group">					
								<?php echo form_label(lang('config_company_dni').':', 'company_dni',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control',
										'name'=>'company_dni',
										'id'=>'company_dni',
										'value'=>$location_info->company_dni));
									?>
								</div>
							</div>
							<div class="form-group">					
								<?php echo form_label(lang('locations_code').':', 'company_codigo',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array( 
										'class'=>'form-control',
										'name'=>'company_codigo',
										'id'=>'company_codigo',
										'value'=>$location_info->codigo));
									?>
								</div>
							</div>
							<?php if($this->config->item('language')=="spanish_chile"):?>

								<div class="form-group">					
									<?php echo form_label(lang('config_giros').':', 'company_giros',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<?php echo form_input(array(
											'class'=>'form-control',
											'name'=>'company_giros',
											'id'=>'company_giros',
											"placeholder"=>"Ejemplo: Botillerías, Almacén, Verdulería, ...",
											'value'=>$location_info->company_giros));
										?>
									</div>
								</div>
							<?php endif; ?>

							<div class="form-group">	
								<?php echo form_label(' <a class="help_config_options tooltips" data-placement="left"  title="'.lang("config_company_regimen_help").'">'. lang('config_company_regimen').'</a>'.':','company_dni',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control',
										'name'=>'company_regimen',
										'id'=>'company_regimen',
										'value'=>$location_info->company_regimen));
									?>
								</div>
							</div>
							<div class="form-group">	
								<?php echo form_label(lang('config_website').':', 'website',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'website',
									'id'=>'website',
									'value'=>$location_info->website));?>
								</div>
							</div>
						<div class="form-group">	
						<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="Permite sobrescribir los datos que se mostraran en la factura, de los contrario se tomaran lo de configuración">Sobrescribir datos de la compañía:</a>', 'overwrite_data',array('class'=>'col-md-3 control-label requireds')); ?>

							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">		
										<?php echo form_checkbox(array(
											'name'=>'overwrite_data', 
											'id'=>'overwrite_data', 
											'value'=>'1', 
											'class'=>'md-check', 
											'checked'=>$location_info->overwrite_data));
										?>
										<label for="overwrite_data">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>									
								</div>							
							</div>
						</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("locations_phone_help").'">'.lang('locations_phone').'</a>'.':', 'phone',array('class'=>'col-md-3 control-label requireds')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'phone',
										'id'=>'phone',
										'value'=>$location_info->phone)
									);?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('locations_fax').':', 'fax',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'fax',
										'id'=>'fax',
										'value'=>$location_info->fax)
									);?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('locations_email').':', 'email',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'email',
										'id'=>'email',
										'value'=>$location_info->email)
									);?>
								</div>
							</div>
							<div class="form-group">
								<?php echo form_label('Personalizar número de factura:', 'factura',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
								<?php echo 
											anchor("locations/modal_factura/$location_info->location_id",
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title=""></i> <span class="visible-lg">Perzonalizar</span>',
											array('class'=>'btn btn-medium green-seagreen effect', "id"=>"modal-serial", 'data-toggle'=>"modal",
											'data-target'=>'#myModal', 
												'title'=>"Perzonalizar número de factura"));
										?>
								</div>
							</div>							

							<div class="form-group">
						
								<?php echo form_label(lang('locations_registers').':', 'registers',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-8 table-responsive">
									<table id="price_registers" class="table">
										<thead>
											<tr>
												<th><?php echo lang('locations_register_name'); ?></th>
												<th><?php echo lang('common_delete'); ?></th>
											</tr>
										</thead>
										
										<tbody>
											<?php foreach($registers->result() as $register) { ?>
												<tr>
													<td><input type="text" class="form-control form-inps" name="registers_to_edit[<?php echo $register->register_id; ?>]" value="<?php echo H($register->name); ?>" /></td>
													<td align="center"><a class="btn delete_register" href="javascript:void(0);" data-register-id='<?php echo $register->register_id; ?>'><?php echo lang('common_delete'); ?></a></td>
												<tr>
											<?php } ?>
										</tbody>
									</table>									
									<a href="javascript:void(0);" id="add_register" class="btn btn-circle default btn-xs"><?php echo lang('locations_add_register'); ?></a>
									<br/><br/>
								</div>
								<div class="col-md-3"></div>
								<div class="col-md-8">
								*Si tiene de más de un vendedor debe de asignar las cajas a las que puede acceder.
								</div>
								
							</div>

							<div id="merchant_information">
								<div class="form-group">	
									<?php echo form_label($this->lang->line('config_merchant_id').':', 'merchant_id',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'merchant_id',
											'id'=>'merchant_id',
											'value'=>$location_info->merchant_id)
										);?>
									</div>
								</div>
								<div class="form-group">	
									<?php echo form_label($this->lang->line('config_merchant_password').':', 'merchant_password',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<?php echo form_password(array(
											'name'=>'merchant_password',
											'id'=>'merchant_password',
											'class'=>'form-control form-inps',
											'value'=>$location_info->merchant_password)
										);?>
										<span id="merchant_password_note"><?php echo lang('sales_mercury_password_note'); ?></span>
									</div>									
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("receive_stock_alert_help").'">'.lang('receive_stock_alert').'</a>'.':', 'receive_stock_alert',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'receive_stock_alert',
												'id'=>'receive_stock_alert',
												'value'=>'1',
												'checked'=>$location_info->receive_stock_alert)
											);?>
											<label for="receive_stock_alert">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left">'.lang('location_receive_expired_alert').'</a>'.':', 'receive_expired_alert',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'receive_expired_alert',
												'id'=>'receive_expired_alert',
												'value'=>'1',
												'checked'=>$location_info->receive_expired_alert)
											);?>
											<label for="receive_expired_alert">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("stock_alert_email_help").'">'.lang('stock_alert_email').'</a>'.':', 'stock_alert_email',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'stock_alert_email',
										'id'=>'stock_alert_email',
										'value'=>$location_info->stock_alert_email)
									);?>
								</div>
							</div>

							<div id="tax_currency_info" class="portlet-body form">						
						
								<div class="form-group">	
									<?php echo form_label(lang('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'col-md-3 control-label ')); ?>
									<div class="col-md-5">
										<?php echo form_input(array(
											'class'=>'form-control',
											'name'=>'default_tax_1_name',
											'placeholder' => lang('common_tax_name'),
											'id'=>'default_tax_1_name',
											'size'=>'10',
											'value'=>$location_info->default_tax_1_name!==FALSE ?$location_info->default_tax_1_name : lang('items_sales_tax_1')));
										?>
									</div>
							
									<div class="col-md-3">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control',
												'placeholder' => lang('items_tax_percent'),
												'name'=>'default_tax_1_rate',
												'id'=>'default_tax_1_rate',
												'size'=>'4',
												'value'=>$location_info->default_tax_1_rate));
											?>
											<span class="input-group-addon">%</span>
										</div>
									</div>							
								</div>	
								<div class="form-group">	
									<?php echo form_label(lang('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'col-md-3 control-label ')); ?>
									<div class="col-md-5">
										<?php echo form_input(array(
										'class'=>'form-control',
										'name'=>'default_tax_2_name',
										'placeholder' => lang('common_tax_name'),
										'id'=>'default_tax_2_name',
										'size'=>'10',
										'value'=>$location_info->default_tax_2_name!==FALSE ? $location_info->default_tax_2_name : lang('items_sales_tax_2')));?>
									</div>

									<div class="col-md-3">
										<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',	
										'name'=>'default_tax_2_rate',
										'placeholder' => lang('items_tax_percent'),
										'id'=>'default_tax_2_rate',
										'size'=>'4',
										'value'=>$location_info->default_tax_2_rate));?>
										<span class="input-group-addon">%</span>		    							
									</div>
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'default_tax_2_cumulative', 
												'id'=>'default_tax_2_cumulative', 
												'value'=>'1', 
												'class'=>'md-check cumulative', 
												'checked'=>$location_info->default_tax_2_cumulative ? true : false));
											?>
											<label for="default_tax_2_cumulative">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											<?php echo lang('common_cumulative'); ?>
											</label>
										</div>
									</div>
								</div>
							</div>

						<div class="col-md-offset-3" style="display: <?php echo $location_info->default_tax_3_rate ? 'none' : 'block';?>">
							<button href="javascript:void(0);" class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</button>
						</div>
					
						<div class="more_taxes_container" style="display: <?php echo $location_info->default_tax_3_rate ? 'block' : 'none';?>">
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_3').':', 'default_tax_3_rate',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-5">
									<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'default_tax_3_name',
									'placeholder' => lang('common_tax_name'),
									'id'=>'default_tax_3_name',
									'size'=>'10',
									'value'=>$location_info->default_tax_3_name!==FALSE ? $location_info->default_tax_3_name: lang('items_sales_tax_3')));?>
								</div>							
								<div class="col-md-3">
									<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_3_rate',
										'id'=>'default_tax_3_rate',
										'size'=>'4',
										'value'=>$location_info->default_tax_3_rate));?>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>								
							
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_4').':', 'default_tax_4_rate',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-5">
									<?php echo form_input(array(
									'class'=>'form-control',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_4_name',
									'id'=>'default_tax_4_name',
									'size'=>'10',
									'value'=>$location_info->default_tax_4_name!==FALSE ? $location_info->default_tax_4_name: lang('items_sales_tax_4')));?>
								</div>							
								<div class="col-md-3">
									<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_4_rate',
										'id'=>'default_tax_4_rate',
										'size'=>'4',
										'value'=>$location_info->default_tax_4_rate));?>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>
							
						</div>	
							<?php echo form_hidden('redirect', $redirect); ?>

						</div>

						<div class="form-actions right">
							<?php if ($purchase_email)
							{
								echo form_hidden('purchase_email', $purchase_email);
							}
							
							echo form_button(array(
								'type'=>'submit',
								'name'=>'submitf',
								'id'=>'submitf',
								'content'=>lang('common_submit'),
								'class'=>'submit_button btn btn-primary')
							);?>
						</div>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
	<?php }?>


	<script type='text/javascript'>
		var submitting = false;
		<?php if($location_info->image_id!=0):?>

			$("#company_logo").fileinput({
				initialPreview: [
					"<img src='<?php echo  site_url('app_files/view/'.$location_info->image_id) ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
				],
				overwriteInitial: true,
				initialCaption: "Imagen"
			});
			<?php endif; ?>

		//validation and submit handling
		$(document).ready(function()
		{
			
		
			
			$(".delete_register").click(function()
			{
				$("#location_form").append('<input type="hidden" name="registers_to_delete[]" value="'+$(this).data('register-id')+'" />');
				$(this).parent().parent().remove();
			});
	
			$("#add_register").click(function()
			{
				$("#price_registers tbody").append('<tr><td><input type="text" class="form-control form-inps registers_to_add" name="registers_to_add[]" value="" /></td><td>&nbsp;</td></tr>');
			});
						
			if ($("#location_form_auth").length == 1)
			{
			    setTimeout(function(){$(":input:visible:first","#location_form_auth").focus();},100);
			}
			else
			{
			    setTimeout(function(){$(":input:visible:first","#location_form").focus();},100);				
			}
			var submitting = false;
			$('#location_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$("#form").plainOverlay('show');
					$(form).ajaxSubmit({
					success:function(response)
					{
						//Don't let the registers be double submitted, so we change the name
						$(".registers_to_add").attr('name', 'registers_added[]');
						
						$("#form").plainOverlay('hide');
						submitting = false;

						if(response.success)
						{
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.location_id);
						}
						else
						{
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
						}						
						
						if(response.redirect==2 && response.success)
						{
							window.location.href = '<?php echo site_url('locations'); ?>'
						}
										
					},
					<?php if(!$location_info->location_id) { ?>
					resetForm: true,
					<?php } ?>
					dataType:'json'
				});

				},
				errorClass: "text-danger",
				errorElement: "span",
					highlight:function(element, errorClass, validClass) {
						$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
					},
				rules:
				{
					name:
					{
						required:true,
					},
					phone:
					{
						required:true
					},
					address:
					{
						required:true
					},
					timezone:
					{
						required: true
					}
		   		},
				messages:
				{
					name:
					{
						required:<?php echo json_encode(lang('locatoins_name_required')); ?>,

					},
					phone:
					{
						required:<?php echo json_encode(lang('locations_phone_required')); ?>,
						number:<?php echo json_encode(lang('locations_phone_valid')); ?>
					},
					address:
					{
						required:<?php echo json_encode(lang('config_address_required')); ?>
					},
					timezone:
					{
						required:<?php echo json_encode(lang('config_timezone_required_field')); ?>
					}
				}
			});
			
			$("#enable_credit_card_processing").change(check_enable_credit_card_processing).ready(check_enable_credit_card_processing);

			function check_enable_credit_card_processing()
			{
				if($("#enable_credit_card_processing").prop('checked'))
				{
					$("#merchant_information").show();
				}
				else
				{
					$("#merchant_information").hide();
				}

			}
		});
	</script>

<?php $this->load->view('partial/footer'); ?>
