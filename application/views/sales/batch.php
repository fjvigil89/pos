<?php $this->load->view("partial/header"); ?>
	
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-upload"></i>
				<?php echo lang('import_export_batch_sale'); ?>
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
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>
						<h3 class="panel-title">
							<i class="fa fa-align-justify"></i>
							<?php echo lang('import_export_batch_sale'); ?>
						</h3>
					</strong>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('sales/do_excel_import/',array('id'=>'item_form','class'=>'form-horizontal')); ?>
						<h2><?php echo lang('common_step_1'); ?>: </h2>
						<p><?php echo lang('download_file_batch_sale'); ?></p>

						<div class="row">
							<div class="col-md-12 margin-bottom-05">
								<a class="btn btn-success" href="<?php echo site_url('sales/excel'); ?>">
									<span class="letter-space"><?php echo lang('download_excel_template_batch_sale'); ?></span>
								</a>
							</div>							
						</div>

						<hr />

						<h2><?php echo lang('common_step_2'); ?>: </h2>
						<p><?php echo lang('import_batch_sale'); ?></p>	

						<div class="control-group">
							<ul class="text-error" id="error_message_box"></ul>
							<?php echo form_label(lang('common_file_path').':', 'name',array('class'=>'wide control-label')); ?>
							<div class="controls">
								<?php echo form_upload(array(
									'name'=>'file_path',
									'id'=>'file_path',
									'type'=>'file',
									'value'=>''));
								?>
								<div id="error" class="help-block"></div>
							</div>
							<br/>
							<div class="form-actions">
								<?php echo form_submit(array(
									'name'=>'submitf',
									'id'=>'submitf',
									'value'=>lang('common_submit'),
									'class'=>'btn btn-primary')); 
								?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script type='text/javascript'>

		$("#file_path").fileinput({
		    showPreview: false,
		    showRemove: false,
		    showUpload: false,
		    allowedFileExtensions: ["xls", "xlsx", "cvs"],
		    elErrorContainer: "#error"
		    // you can configure `msgErrorClass` and `msgInvalidFileExtension` as well
		});

		//validation and submit handling
		$(document).ready(function()
		{	
			var submitting = false;
			$('#item_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$("#form").plainOverlay('show');
					$(form).ajaxSubmit({
						success:function(response)
						{
							$("#form").plainOverlay('hide');
							if(!response.success)
							{ 
								toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
							}
							else
							{
								toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);
								window.location.href="<?php echo base_url(); ?>index.php/sales";								
							}
							
							submitting = false;
						},
						dataType:'json',
						resetForm: true
					});
				},
				errorLabelContainer: "#error_message_box",
		 		wrapper: "li",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.control-group').addClass('error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.control-group').removeClass('error');
					$(element).parents('.control-group').addClass('success');
				},
				rules: 
				{
					file_path:"required"
		   		},
				messages: 
				{
		   			file_path:<?php echo json_encode(lang('suppliers_full_path_to_excel_required')); ?>
				}
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>