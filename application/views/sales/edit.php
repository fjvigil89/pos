<?php $this->load->view("partial/header"); ?>
	
	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-pencil"></i>
				<?php echo lang('sales_register')." <small> -  ".lang('sales_edit_sale'); ?></small>
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
							<?php echo lang("sales_edit_sale"); ?>
						</span>
					</div>					
				</div>
				<div class="portlet-body form">
					<?php echo form_open("sales/save/".$sale_info['sale_id'],array('id'=>'sales_edit_form','class'=>'form-horizontal')); ?>					
						<div class="form-body">
							<div class="form-group">	
								<?php echo form_label(lang('sales_receipt').':', 'sales_receipt',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-9 sale-s">
									<?php
                                        if( $sale_numeration->is_invoice == 1 )
                                        {
                                            echo anchor('sales/receipt/'.$sale_info['sale_id'], $this->config->item('sale_prefix').' '.$sale_numeration->invoice_number, array('target' => '_blank'));
                                        }
                                        else 
                                        {
                                            echo anchor('sales/receipt/'.$sale_info['sale_id'], lang("sales_ticket").' '.$sale_numeration->ticket_number, array('target' => '_blank'));
                                        }
                                    ?>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('sales_date').':', 'date',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-9">
									<?php echo form_input(array(
										'name'=>'date',
										'class'=>'form-control',
										'value'=>date(get_date_format(), strtotime($sale_info['sale_time'])), 
										'id'=>'date'));
									?>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('sales_customer').':', 'customer',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_dropdown('customer_id', $customers, $sale_info['customer_id'], 'id="customer_id" class="bs-select form-control"');?>
									<br/>
									<?php if ($sale_info['customer_id']) { ?>
										<?php echo anchor('sales/email_receipt/'.$sale_info['sale_id'], lang('sales_email_receipt'), array('id' => 'email_receipt'));?>
									<?php }?>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('sales_employee').':', 'employee',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_dropdown('employee_id', $employees, $sale_info['employee_id'], 'id="employee_id" class="bs-select form-control"');?>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('sales_comments_receipt').':', 'sales_comments_receipt',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'show_comment_on_receipt',
												'id'=>'show_comment_on_receipt',
												'value'=>'1',
												'checked'=>(boolean)$sale_info['show_comment_on_receipt']));
											?>
											<label for="show_comment_on_receipt">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">	
								<?php echo form_label(lang('sales_comment').':', 'comment',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_textarea(array('name'=>'comment', 'class'=>'form-control form-textarea','value'=>$sale_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<div class="row">
								<div class="col-md-3 pull-right">							
									<?php
										echo form_button(array(
										'name'=>'submit',
										'id'=>'submit',
										'type' => 'submit',
										'content' => lang('common_submit'),
										'class'=>'btn btn-success btn-block'));
									?>																				
							</form>

							<?php if ($sale_info['deleted']) {?>
								<?php echo form_open("sales/undelete/".$sale_info['sale_id'],array('id'=>'sales_undelete_form','class'=>'form-horizontal')); ?>									
									<div class="form-group">	
										<?php echo form_label(lang('sales_deleted_by').':&nbsp;', 'deleted_by',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label')); ?>												
										<div class="controls" style="padding-top:7px;">
											<?php echo anchor('employees/view/'.$sale_info['deleted_by'], $this->Employee->get_info($sale_info['deleted_by'])->first_name.' '.$this->Employee->get_info($sale_info['deleted_by'])->last_name, array('target' => '_blank'));?>
										</div>
									</div>
														
									<?php echo form_button(array(
										'name'=>'submit',
										'id'=>'submit',
										'type' => 'submit',
										'content'=>lang('sales_undelete_entire_sale'),
										'class'=>' btn btn-success'));
									?>
									
								</form>
							<?php }
							else{ ?>
							<?php if (!$store_account_payment && $this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->person_id)){

							 	$edit_sale_url = $sale_info['suspended'] > 0 ? 'unsuspend' : 'change_sale';
								$url=$sale_info['support_id'] !=null ? "technical_supports/?edit=".$sale_info['support_id'] : "sales/$edit_sale_url/".$sale_info['sale_id'];


								echo form_open($url,array('id'=>'sales_change_form','class'=>'form-horizontal')); ?>
									<?php echo form_submit(array(
										'name'=>'submit',
										'id'=>'submit',
										'value'=>lang('sales_change_sale'),
										'class'=>' btn btn-success btn-block')); 
								}?>
							</form>
																
							<?php if (!$store_account_payment && $this->Employee->has_module_action_permission('sales', 'delete_sale', $this->Employee->get_logged_in_employee_info()->person_id))
							{ 
									echo form_open("sales/delete/".$sale_info['sale_id'],array('id'=>'sales_delete_form','class'=>'form-horizontal'));
									echo form_submit(array(
									'name'=>'submit',
									'id'=>'submit',
									'value'=>lang('sales_delete_entire_sale'),
									'class'=>' btn btn-danger btn-block'));
								?>
							</form>
						</div>
					</div>	
					<?php
					} }
					?>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript" language="javascript">
		$(document).ready(function()
		{	
			$("#email_receipt").click(function()
			{
				$.get($(this).attr('href'), function()
				{
					toastr.success('<?php echo lang('sales_receipt_sent'); ?>', <?php echo json_encode(lang('common_success')); ?>);					
				});
				
				return false;
			});

			$('#date').datetimepicker({
				format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
				locale: "es"
			});

			//$('#date').datepicker({format: '<?php echo get_js_date_format(); ?>'});

			$("#sales_delete_form").submit(function()
			{
				if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation")); ?>))
				{
					return false;
				}
			});
			
			$("#sales_undelete_form").submit(function()
			{
				if (!confirm(<?php echo json_encode(lang("sales_undelete_confirmation")); ?>))
				{
					return false;
				}
			});
			
			$('#sales_edit_form').validate({
				submitHandler:function(form)
				{
					$(form).ajaxSubmit({
					success:function(response)
					{
						if(response.success)
						{
							submitting = false;
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);													
						}
						else
						{
							submitting = false;
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);														
						}
					},
					dataType:'json'
				});

				},
				errorLabelContainer: "#error_message_box",
		 		wrapper: "li",
				rules: 
				{
		   		},
				messages: 
				{
				}
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>