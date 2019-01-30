<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-pencil'></i>
				<?php if(!$person_info->person_id) { echo lang('suppliers_new'); } else { echo lang('suppliers_update');  }?>
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
						<?php echo lang("suppliers_basic_information"); ?>
					</span>
				</div>
				
			</div>
			

			<div class="portlet-body form">
				<?php echo form_open('suppliers/save/'.$person_info->person_id,array('id'=>'supplier_form','class'=>'form-horizontal'));?>
					<div class="form-body">

						<h4><?php echo lang('common_fields_required_message'); ?></h4>

						<div class="form-group">
							<?php echo form_label('<a class="help_config_required tooltips" data-placement="left" title="'.lang("suppliers_company_name_help").'">'.lang('suppliers_company_name').'</a>'.':', 'company_name', array('class'=>'requireds col-md-3 control-label')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'company_name',
									'id'=>'company_name_input',
									'value'=>$person_info->company_name)
								);?>
							</div>
						</div>

						<?php $this->load->view("people/form_basic_info"); ?>

						<div class="form-group">
							<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("suppliers_account_number_help").'">'.lang('suppliers_account_number').'</a>'.':', 'account_number', array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'account_number',
									'id'=>'account_number',
									'value'=>$person_info->account_number)
								);?>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("suppliers_type_help").'">'.lang('suppliers_type').'</a>'.':', 'type', array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-8">							
								<?php echo form_dropdown('suppliers_type', array(lang('suppliers_legal') => lang('suppliers_legal'),lang('suppliers_natural') => lang('suppliers_natural')), $person_info->type, 'class="bs-select form-control"'); ?>
							</div>
						</div>

						<?php echo form_hidden('redirect', $redirect); ?>
					</div>

					<div class="form-actions right">

						<?php if ($redirect == 1)
						{
							echo form_button(array(
						    'name' => 'cancel',
						    'id' => 'cancel',
							'class' => 'btn btn-danger',
						    'value' => 'true',
						    'content' => lang('common_cancel')));
						}
							echo form_button(array(
							'type'=>'submit',
							'name'=>'submitf',
							'id'=>'submitf',
							'content'=>lang('common_submit'),
							'class'=>'btn btn-primary submit_button btn-large'));
						?>
					
					</div>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

	
	<script type='text/javascript'>

		var imagen = "<?php echo site_url('app_files/view/'.$person_info->image_id); ?>"
		console.log(imagen);
		$("#image_id").fileinput({
		    initialPreview: [
		        "<img src='<?php echo $person_info->image_id ? site_url('app_files/view/'.$person_info->image_id) : base_url().'img/avatar.png'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"		        
		    ],
		    overwriteInitial: true,
		    initialCaption: "Avatar"
		});

		//validation and submit handling
		$(document).ready(function()
		{
			
			


			$("#cancel").click(cancelAddSupplier);
			
		    setTimeout(function(){$(":input:visible:first","#supplier_form").focus();},100);
			var submitting = false;			
			
			$('#supplier_form').validate({
				submitHandler:function(form)
				{
					$("#form").plainOverlay('show');
					if (submitting) return;
					submitting = true;
					$(form).ajaxSubmit({
					success:function(response)
					{
						$("#form").plainOverlay('hide');
						submitting = false;

						if(response.success)
						{
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.person_id);							
							$("html, body").animate({ scrollTop: 0 }, "slow");
						}
						else
						{
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
						}
						
						if(response.redirect==1 && response.success)
						{ 
							$.post('<?php echo site_url("receivings/select_supplier");?>', {supplier: response.person_id}, function()
							{
								window.location.href = '<?php echo site_url('receivings'); ?>'
							});					
						}
						if(response.redirect==2 && response.success)
						{ 
							window.location.href = '<?php echo site_url('suppliers'); ?>'
						}

					},
					
					<?php if(!$person_info->person_id) { ?>
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
					<?php if(!$person_info->person_id) { ?>
					account_number:
					{
						remote: 
						    { 
							url: "<?php echo site_url('suppliers/account_number_exists');?>", 
							type: "post"
							
						    } 
					},
					<?php } ?>
					company_name: "required"
				},
				messages: 
				{
					<?php if(!$person_info->person_id) { ?>
					account_number:
					{
						remote: <?php echo json_encode(lang('common_account_number_exists')); ?>
					},
					<?php } ?>
		     		company_name: <?php echo json_encode(lang('suppliers_company_name_required')); ?>,
		     		last_name: <?php echo json_encode(lang('common_last_name_required')); ?>
				}
			});
		});

		function cancelAddSupplier()
		{
			if (confirm(<?php echo json_encode(lang('suppliers_are_you_sure_cancel')); ?>))
			{
				window.location = <?php echo json_encode(site_url('receivings')); ?>;
			}
			
		}
	</script>
<?php $this->load->view('partial/footer')?>