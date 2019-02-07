<?php $this->load->view("partial/header"); ?>
	
	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-credit-card'></i>
				<?php echo lang('giftcards_new'); ?>
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
			<?php echo form_open('giftcards/save_item/'.$item_id,array('id'=>'giftcard_form','class'=>'form-horizontal')); ?>
				<div class="form-body">
					<div class="form-group">
						<?php echo form_label(lang('giftcards_giftcard_number').':', 'name',array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-8">
							<?php echo form_input(array(
								'name'=>'description',
								'size'=>'8',
								'id'=>'description',
								'class'=>'form-control form-inps',
								)
							);?>
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label(lang('giftcards_card_value').':', 'name',array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-8">
							<?php echo form_input(array(
								'name'=>'unit_price',
								'size'=>'8',
								'class'=>'form-control form-inps',
								'id'=>'unit_price')
							);?>
						</div>
					</div>

					<?php echo form_hidden('redirect', 1); ?>
					<?php echo form_hidden('sale_or_receiving', 'sale'); ?>
					<?php echo form_hidden('is_service', 1); ?>
					<?php echo form_hidden('sale', 1); ?>
					<?php echo form_hidden('item_number', lang('sales_giftcard')); ?>
					<?php echo form_hidden('name', lang('sales_giftcard')); ?>
					<?php echo form_hidden('category', lang('sales_giftcard')); ?>
					<?php echo form_hidden('size', '');?>
					<?php echo form_hidden('quantity', ''); ?>
					<?php echo form_hidden('allow_alt_description', '1'); ?>
					<?php echo form_hidden('is_serialized', '1'); ?>
					<?php echo form_hidden('override_default_tax', '1'); ?>

				</div>

				<div class="form-actions right">
					<?php echo form_button(array(
						'type'=>'submit',
						'name'=>'submit',
						'id'=>'submit',
						'content'=>lang('common_submit'),
						'class'=>'btn btn-success')
					);?>	
				</div>

			<?php echo form_close(); ?>
		</div>
	</div>


	<script type='text/javascript'>

		//validation and submit handling
		$(document).ready(function()
		{
			<?php if (!$this->config->item('disable_giftcard_detection')) { ?>
				giftcard_swipe_field($('#description'));
			<?php } ?>

		    setTimeout(function(){$(":input:visible:first","#giftcard_form").focus();},100);

			var submitting = false;
			$('#giftcard_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$(form).ajaxSubmit({
					success:function(response)
					{
						$('#spin').addClass('hidden');
						submitting = false;
						toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.giftcard_id);
						
						if(response.redirect==1)
						{ 
							if (response.sale_or_receiving == 'sale')
							{
								$.post('<?php echo site_url("sales/add");?>', {item: response.item_id}, function()
								{
									window.location.href = '<?php echo site_url('sales'); ?>'
								});
							}
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
					description:
					{
						required:true,
						remote: 
						    { 
							url: "<?php echo site_url('giftcards/giftcard_exists');?>", 
							type: "post"
							
						    } 
					},
					unit_price:
					{
						required:true,
						number:true
					}
		   		},
				messages:
				{
					description:
					{
						required:<?php echo json_encode(lang('giftcards_number_required')); ?>,
						remote:<?php echo json_encode(lang('giftcards_exists')); ?>
					},
					unit_price:
					{
						required:<?php echo json_encode(lang('giftcards_value_required')); ?>,
						number:<?php echo json_encode(lang('giftcards_value')); ?>
					}
				}
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>