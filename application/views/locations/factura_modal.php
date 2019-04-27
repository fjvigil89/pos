<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                        Perzonalizar número de factura
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" class="close" id="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
	            	
					<div class="col-sm-12 margin-bottom-05">
					<?php echo form_open('locations/save_config_invoicer/'.$location_id,array('id'=>'location_form_config_invoicer','class'=>'')); ?>		
						<div class="portlet-body form">
							<div class="form-body">	
								<div class="form-group">
									<div class="row">
										<div class="col-md-3">
											<?php echo form_label("Serie de la factura", 'serie_number',array('class'=>'')); ?>
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'serie_number',
												"style"=>"text-align: center",
												"value"=>$invoice_number_info->serie_number==""?1:$invoice_number_info->serie_number,
												'id'=>'serie_number')
											);?>
										</div>
										<div class="col-md-3">
											<?php echo form_label('Rango de inicio:', 'start_range',array('class'=>'')); ?>
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'start_range',
												"style"=>"text-align: center",
												"value"=>$invoice_number_info->start_range==""?1:$invoice_number_info->start_range,
												'id'=>'start_range')
											);?>
										</div>
										<div class="col-md-3">
											<?php echo form_label('Rango final:', 'final_range',array('class'=>'')); ?>
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'final_range',
												"style"=>"text-align: center",
												"value"=>$invoice_number_info->final_range,
												'id'=>'final_range')
											);?>
										</div>
										<div class="col-md-3">
											<?php echo form_label('Fecha limite:', 'limit_date',array('class'=>'')); ?>
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'limit_date',
												"style"=>"text-align: center",
												"value"=>$invoice_number_info->limit_date,
												'id'=>'limit_date')
											);?>
										</div>
										<!--<div class="col-md-2">
											<?php echo form_label('Incremento:', 'increment',array('class'=>'')); ?>
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'increment',
												"type"=>"Number",
												"value"=>$invoice_number_info->increment==""?1:$invoice_number_info->increment,
												"min"=>1,
												'id'=>'increment')
											);?>
										</div>-->
										<div class="col-md-12">										
												<?php echo form_label('Mostrar serie en la factura:', 'show_serie',array('class'=>'col-md-3 control-label ')); ?>
												<div class="col-md-9">
													<div class="md-checkbox-inline">
														<div class="md-checkbox">
															<?php echo form_checkbox(array(
																'name'=>'show_serie',
																'id'=>'show_serie',
																'value'=>'1',
																'class'=>'md-check',
																'checked'=>$invoice_number_info->show_serie));
															?>
															<label for="show_serie">
															<span></span>
															<span class="check"></span>
															<span class="box"></span>
															</label>
														</div>
													</div>
												
											</div>
										</div><br>
										<div class="col-md-12">										
												<?php echo form_label('Mostrar rango en la factura:', 'show_rango',array('class'=>'col-md-3 control-label ')); ?>
												<div class="col-md-9">
													<div class="md-checkbox-inline">
														<div class="md-checkbox">
															<?php echo form_checkbox(array(
																'name'=>'show_rango',
																'id'=>'show_rango',
																'value'=>'1',
																'class'=>'md-check',
																'checked'=>$invoice_number_info->show_rango));
															?>
															<label for="show_rango">
															<span></span>
															<span class="check"></span>
															<span class="box"></span>
															</label>
														</div>
													</div>
												
											</div>
										</div>

									</div>	
								</div>
								
							</div>
						</div>
						<hr>
						<div class="form-actions ">
							<?php echo form_button(array(
								'type'=>'button',								
								'id'=>'cancelar',
								'content'=>"Cancelar",
								'class'=>'btn red-haze')
							);?>
							
							<?php echo form_button(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'type' => 'submit',	
								'class'=>'btn btn-primary pull-right'),
								"Guardar"); 
							?>							   
						
						
						</div>
					</form>
						
					</div>						
				</div>
		
			</div>
		</div>
	</div>
</div>
<script>
	var JS_DATE_FORMAT = 'YYYY-MM-DD';
	$('#limit_date').datetimepicker({
		format: JS_DATE_FORMAT,
		locale: "es"
	});
	$('#location_form_config_invoicer').validate({
		submitHandler:function(form)
		{					
			$('#submitf').attr('disabled','disabled');	
			$(form).ajaxSubmit({
			success:function(response)
			{
				$('#submitf').removeAttr('disabled');
				if(response.success)
				{
					toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> );
					$("#close").click();
				}
				else
				{
					toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
				}						
			},					
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
			/*serie_number:
			{
				required:true,
			},*/
			start_range:
			{
				required:true,
				number:true
			},/*
			final_range:
			{
				required:true,
				number:true
			},
			limit_date:
			{
				required: true
			},
			increment:{
				required: true,
				number:true
			}*/
		},
		messages:
		{
			serie_number:
			{
				required:"La serie es obligatoria",
			},
			start_range:
			{
				required:"Rango inicial es obligatorio",
				number:"Rango inicial debe de ser numérico"
			},
			final_range:
			{
				required:"Rango final es obligatorio",
				number:"Rango final debe de ser numérico"
			},
			limit_date:
			{
				required:"La fecha limite es obligatoria"
			},
			increment:
			{
				required:"El incrmento es obligatorio",
				number:"El incrmento debe de ser numérico"					
			}
		}
	});
	$("#cancelar").click(function(){
		$("#close").click();
	});

</script>



