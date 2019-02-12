<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-exchange'></i>
				<?php echo lang('customers_redeempoints'); ?>
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

	<div class="row">
		<div class="col-md-12">
			<div class="portlet light bordered form-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-speech"></i>
						<span class="caption-subject bold font-green-jungle uppercase">
							<?php echo lang("customers_basic_redeempoints"); ?>
						</span>
						<span class="caption-helper">more samples...</span>
					</div>
					<div class="actions">							
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
					</div>
				</div>
				<div class="portlet-body form">	
					<?php echo form_open_multipart('giftcards/point_reedempoints/'.$person_info->person_id,array('id'=>'customer_form','class'=>'form-horizontal form-row-seperated'));?>
						<h6><?php echo lang('common_fields_required_message'); ?></h6>

						 <div class="form-group">
							<?php echo form_label(lang('config_value_point_accumulated').':', 'value_point',array('class'=>' col-lg-3 control-label ')); ?>
	                        <div class="col-md-12 col-lg-8">
		                        <h4>
		                        	<span class="label label-success">
		                        		<?php echo $person_info_point ?>
		                        	<?php echo form_hidden('value_point',$person_info_point);
	                           			 ?>
									</span>
								</h4>		                        
	                        </div>
	                    </div>  

	                    <?php $required = ($controller_name == "_redeempoints") ? "" : "required";?>

	                    <div class="form-group">       
	                        <?php echo form_label(lang('customers_redeempoints_label').':', 'point_reedempoints',array('class'=>$required.' col-lg-3 control-label requireds')); ?>
	                        <div class="col-md-12 col-lg-8">
	                            <?php echo form_input(array(
	                                'name'=>'point_reedempoints',
	                                'id'=>'point_reedempoints',
	                                'class'=>'point_reedempoints form-control form-inps'));
	                            ?>
	                        </div>
	                    </div>

	                    <div class="form-group">       
		                    <?php echo form_label(lang('giftcards_giftcard_number').':', 'giftcard_number',array('class'=>' required col-lg-3 control-label requireds')); ?>
		                    <div class="col-md-12 col-lg-8">
	                            <?php echo form_input(array(
	                                'name'=>'giftcard_number',
	                                'id'=>'giftcard_number',
	                                'class'=>'giftcard_number form-control form-inps'));
	                            ?>
	                        </div>
	                    </div>

	                    <?php echo form_hidden('redirect_code', $redirect_code); ?>

	                    <div class="form-actions">
	                    	<div class="row">
	                    		<div class="col-md-offset-3 col-md-9">
			                        <?php
			                            echo form_button(array(
			                            	'type'=>'submit',
			                                'name'=>'submitf',
			                                'id'=>'submitf',
			                                'content' => lang('common_submit'),
			                                'class'=>' btn btn-primary')
			                            );
			                        ?>
			           				<?php
			                            echo form_button(array(
			                        		'name' => 'cancel',
			                        		'id' => 'cancel',
			                            	'class' => 'btn btn-danger',
			                        		'value' => 'true',
			                        		'content' => lang('common_cancel')
			                            ));                                    
			               			?> 
		               			</div>  
	               			</div>                         
	                    </div>

                	<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>


	<script type='text/javascript'>
	$(document).ready(function()
	{
		$("#cancel").click(cancelCustomerAddingFromSale);
		
		var submitting = false;
		$('#customer_form').validate({
			submitHandler:function(form)
			{
				$.post('<?php echo site_url("giftcards/check_duplicate");?>', {term:$('#giftcard_number').val()},function(data) {
				
				if(data.duplicate)
				{
					if(data.duplicate==true)
					{					
						toastr.error('Este numero de tarteja ya se encuentra en uso ', 'duplicado');
					}
					else 
					{
						return false;
					}
				}
			
				{
					doCustomerSubmit(form);
				}} , "json")

				.error(function() { 
				});			
			},
			rules: 
			{
				giftcard_number: "required",
				point_reedempoints: "required"
			},

			errorClass: "text-danger",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
			},
			messages: 
			{			
				giftcard_number: <?php echo json_encode(lang('giftcards_number_required')); ?>,
		        point_reedempoints: <?php echo json_encode(lang('giftcards_redeempoints_required')); ?>,				
			}
		});
	});

	var submitting = false;

	function doCustomerSubmit(form)
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
				}
				else
				{
					toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
				}
				
				if(response.redirect_code==2 && response.success)
				{
					window.location.href = '<?php echo site_url('customers'); ?>';
				}

			   	else{

			   	}			
			},

			<?php if(!$person_info->person_id) { ?>
				resetForm: true,
			<?php } ?>
			dataType:'json'
		});
	}

	function cancelCustomerAddingFromSale()
	{
		window.location = <?php echo json_encode(site_url('customers')); ?>;		
	}
	
	
	</script>
<?php $this->load->view("partial/footer"); ?>
