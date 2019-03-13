<?php echo form_open('login/switch_user/',array('id'=>'login_form','class'=>'form-horizontal')); ?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h4><strong><?php echo lang('login_switch_user'); ?></strong></h4>
			</div>

			<div class="modal-body ">				
				<div class="form-group">
					<?php echo form_label(lang('employees_employee').':', 'employee',array('class'=>'control-label col-md-4 text-warning wide')); ?>
					<div class="col-md-8">
						<?php echo form_dropdown('username', $employees, $this->Employee->get_logged_in_employee_info()->username, 'class="bs-select form-control"');?>
					</div>
				</div>
				<div class="form-group form-md-line-input">
					<?php echo form_label(lang('login_password').':', 'supplier',array('class'=>'control-label col-md-4 text-warning wide')); ?>
					<div class="col-md-8">
						<div class="input-icon right">
							<?php echo form_password(array(
								'name'=>'password', 
								'value'=>'',
								'class' => 'form-control',
								'size'=>'20')); ?>
							<div class="form-control-focus">
							</div>
							<i class="fa fa-key"></i>
						</div>
					</div>
				</div>			
			</div>

			<div class="modal-footer">
				<div class="form-actions">					
					<?php
					echo form_button(array(
						'name'=>'submit',
						'id'=>'submit',
						'type' => 'submit',						
						'class'=>'btn blue btn-block'), lang('common_submit')
					);
					?>
					<i id="spin" class="fa fa-spinner fa fa-spin fa fa-2x hidden"></i>
					<span id="error_message" class="text-danger">&nbsp;</span>
				</div>			
			</div>			
		</div>
	</div>
<?php echo form_close(); ?>

<script type='text/javascript'>
	//validation and submit handling
	var submitting = false;

	$('#login_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$('#spin').removeClass('hidden');
			$(form).ajaxSubmit({
				success:function(response)
				{
					$('#spin').addClass('hidden');
					submitting = false;
					if(!response.success)
					{
						$('#error_message').html(response.message);
					}
					else
					{
						$('#myModal').modal('hide');
						window.location.href = '<?php echo site_url('sales'); ?>';
					}
				},
				dataType:'json'
			});
		},
		errorClass: "text-danger display-block",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('error');
			$(element).parents('.form-group').addClass('success');
		},rules:
		{

			password:"required",

		},
		messages:
		{
			password:
			{
				required: <?php echo json_encode(lang('login_invalid_username_and_password')); ?>
			}

		}
	});

	Metronic.init(); // init metronic core componets
   	Layout.init(); // init layout
   	Demo.init(); // init demo features
    Index.init(); // init index page
 	Tasks.initDashboardWidget(); // init tash dashboard widget  
 	ComponentsDropdowns.init();
</script>
