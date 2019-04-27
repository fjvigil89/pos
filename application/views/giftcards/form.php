<?php $this->load->view("partial/header"); ?>
	
	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-pencil'></i>
				</i> <?php  if(!$giftcard_info->giftcard_id) { echo lang('giftcards_new'); } else { echo lang('giftcards_update'); }?>
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

	<div class="portlet box green" id="form">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-book-open"></i>
				<span class="caption-subject bold">
					<?php echo lang("giftcards_basic_information"); ?>
				</span>
			</div>					
		</div>

		<div class="portlet-body form">
			<?php echo form_open('giftcards/save/'.$giftcard_info->giftcard_id,array('id'=>'giftcard_form','class'=>'form-horizontal')); ?>
				<div class="form-body">
				<h4><?php echo lang('common_fields_required_message'); ?></h4>

					<div class="form-group">	
						<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("giftcards_giftcard_number_help").'">'.lang('giftcards_giftcard_number').'</a>'.':', 'name',array('class'=>'col-md-3 control-label requireds wide')); ?>
						<div class="col-md-8">
							<?php echo form_input(array(
								'name'=>'giftcard_number',
								'size'=>'8',
								'id'=>'giftcard_number',
								'class'=>'form-control form-inps',
								'value'=>$giftcard_info->giftcard_number)
							);?>
						</div>
					</div>

					<div class="form-group">	
						<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("giftcards_card_value_help").'">'.lang('giftcards_card_value').'</a>'.':', 'name',array('class'=>'col-md-3 control-label requireds wide')); ?>
						<div class="col-md-8">
							<?php echo form_input(array(
								'name'=>'value',
								'size'=>'8',
								'class'=>'form-control form-inps ',
								'id'=>'value',
								'value'=>$giftcard_info->value ? to_currency_no_money($giftcard_info->value, 10) : '')
							);?>
						</div>
					</div>

					<div class="form-group">	
						<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("giftcards_customer_name_help").'">'.lang('giftcards_customer_name').'</a>'.':', 'customer_id',array('class'=>'col-md-3 control-label requireds wide')); ?>
						<div class="col-md-8">
							<?php echo form_dropdown('customer_id', $customers, $giftcard_info->customer_id, 'id="customer_id" class="bs-select form-control"');?>
						</div>
					</div>

					<?php
                     $redirect = site_url('giftcards');
					 echo form_hidden('redirect', $redirect); ?>

				</div>

				<div class="form-actions right">
					<?php echo form_button(array(
						'type'=>'submit',
						'name'=>'submit',
						'id'=>'submit',
						'content'=>lang('common_submit'),
						'class'=>'btn btn-primary')
					);?>	
				</div>

			<?php echo form_close(); ?>
		</div>
	</div>

	<script type='text/javascript'>
	
		<?php if (!$this->config->item('disable_giftcard_detection')) { ?>
			giftcard_swipe_field($('#giftcard_number'));
		<?php
		}
		?>
		
		//validation and submit handling
		$(document).ready(function()
		{
		    setTimeout(function(){$(":input:visible:first","#giftcard_form").focus();},100);
			var submitting = false;
			$('#giftcard_form').validate({
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
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.giftcard_id);
						}
						else
						{
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
						}
						
						if(response.redirect==2 && response.success)
						{
			 window.location.href = '<?php echo site_url("giftcards/giftcard_print/'+response.giftcard_id+'");?>';
						}
					},
					<?php if(!$giftcard_info->giftcard_id) { ?>
					resetForm:true,
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
					giftcard_number:
					{
						<?php if(!$giftcard_info->giftcard_id) { ?>
						remote: 
						    { 
							url: "<?php echo site_url('giftcards/giftcard_exists');?>", 
							type: "post"
			
						    }, 
						<?php } ?>
						required:true
		
					},
					value:
					{
						required:true,
						number:true
					}
		   		},
				messages:
				{
					giftcard_number:
					{
						<?php if(!$giftcard_info->giftcard_id) { ?>
						remote:<?php echo json_encode(lang('giftcards_exists')); ?>,
						<?php } ?>
						required:<?php echo json_encode(lang('giftcards_number_required')); ?>,

					},
					value:
					{
						required:<?php echo json_encode(lang('giftcards_value_required')); ?>,
						number:<?php echo json_encode(lang('giftcards_value')); ?>
					}
				}
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>