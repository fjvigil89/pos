<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-pencil"></i>
				<?php echo lang('receivings_register')." - ".lang('receivings_edit_receiving'); ?> <span class="text-warning"> RECV <?php echo $receiving_info['receiving_id']; ?> </span>
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
							<?php echo lang("items_basic_information"); ?>
						</span>
					</div>					
				</div>
				<div class="portlet-body form">
					<?php echo form_open("receivings/save/".$receiving_info['receiving_id'],array('id'=>'receivings_edit_form','class'=>'form-horizontal')); ?>
						<div class="form-body">
							<div class="form-group">
								<?php echo form_label(lang('receivings_receipt').':', 'receipt',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 sale-s">
									<?php echo anchor('receivings/receipt/'.$receiving_info['receiving_id'], 'RECV '.$receiving_info['receiving_id'], array('target' => '_blank'));?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('sales_date').':', 'date',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_input(array(
										'name'=>'date',
										'class'=>'form-control',
										'value'=>date(get_date_format(), strtotime($receiving_info['receiving_time'])), 
										'id'=>'date'));
									?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('receivings_supplier').':', 'supplier',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_dropdown('supplier_id', $suppliers, $receiving_info['supplier_id'], 'id="supplier_id" class="bs-select form-control"');?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('sales_employee').':', 'employee',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_dropdown('employee_id', $employees, $receiving_info['employee_id'], 'id="employee_id" class="bs-select form-control"');?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('sales_comment').':', 'comment',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_textarea(array('name'=>'comment', 'class'=>'form-control form-textarea', 'value'=>$receiving_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
								</div>
							</div>
						</div>


						<div class="form-actions">
							<div class="row">
								<div class="col-md-3 pull-right">
									<?php echo form_button(array(
										'name'=>'submit',
										'id'=>'submit',
										'type' => 'submit',
										'content'=>lang('common_submit'),
										'class'=>'btn btn-success btn-block'));
									?>
					</form>
									<?php if ($receiving_info['deleted']) { ?>
										<?php echo form_open("receivings/undelete/".$receiving_info['receiving_id'],array('id'=>'receivings_undelete_form')); ?>
											<?php echo form_button(array(
												'name'=>'submit',
												'id'=>'submit',
												'type' => 'submit',
												'content'=>lang('receivings_undelete_entire_sale'),
												'class'=>'btn btn-primary btn-block'));
											?>
										</form>					
									<?php }
									else { ?>
										<?php echo form_open("receivings/delete/".$receiving_info['receiving_id'],array('id'=>'receivings_delete_form')); ?>
											<?php echo form_button(array(
												'name'=>'submit',
												'id'=>'submit',
												'type' => 'submit',
												'content'=>lang('receivings_delete_entire_receiving'),
												'class'=>'btn btn-danger btn-block letter-space'));
											?>
										</form>	
									<?php } ?>
			                	</div>
							</div>					
						</div>
							
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" language="javascript">
		$(document).ready(function()
		{	
			$('#date').datetimepicker({
				format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
				locale: "es"
			});

			$("#receivings_delete_form").submit(function()
			{
				if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation")); ?>))
				{
					return false;
				}
			});
			
			$("#receivings_undelete_form").submit(function()
			{
				if (!confirm(<?php echo json_encode(lang("receivings_undelete_confirmation")); ?>))
				{
					return false;
				}
			});
			var submitting = false;
			$('#receivings_edit_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					
					$(form).ajaxSubmit({
					success:function(response)
					{
						submitting = false;
						if(response.success)
						{
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);
						}
						else
						{
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