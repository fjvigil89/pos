<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-shopping-cart'></i>
				<?php echo lang('sales_opening_amount');?>
				<a class="icon fa fa-youtube-play help_button" id='maxamount' rel='0' data-toggle="modal" data-target="#stack8"></a>
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
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">						
						<?php echo lang('sales_opening_amount');?>
					</div>					
				</div>
				<div class="portlet-body">
					<h5><?php echo lang('sales_opening_amount_desc'); ?></h5>
					<br/>
					<div class="row">
						<div class="col-md-6">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped text-center">
									<thead>
										<tr>
											<th style="text-align: center;"><?php echo lang('sales_denomination');?></th>
											<th style="text-align: center;"><?php echo lang('reports_count');?></th>
											<th style="text-align: center;"><?php echo lang('config_currency');?></th>
										</tr>
									</thead>
									<tbody>																			 
										<?php 
										$i=0;
										foreach($currency->result() as $currency) { ?>	
										<tr>						
											<td>
			                                	<?php echo $this->config->item('currency_symbol').form_label($currency->name, 'currency'); ?>
											</td>
										  	<td>
												<?php echo form_input(array(
													'id'=>"currency$i",
													'class'=>'form-control form-inps',
													'name'=>"currency$i",
												));?>				
											</td>                                   
										  	<td>
												<?php echo form_input(array(
													'id'=>"type_currency",
													'class'=>'form-control form-inps',
													'value'=>$currency->type_currency
												));?>
											</td>
									
										    <div id='grupo'>
												<?php echo form_input(array(
													'id'=>"quantity$i",
													'name'=>"quantity$i",
											        'type'=>'hidden',
													'value'=>$currency->name
												));?>
											</div>
										</tr>
										<?php $i++;} ?>										
									</tbody>
								</table>
							</div>
						</div>

						<div class="col-md-6">
							<?php
							$reg_info = $this->Register->get_info($this->Employee->get_logged_in_employee_current_register_id());
							$reg_name = '<span class="btn btn-success btn-circle btn-sm">'.$reg_info->name .'&nbsp;&nbsp;&nbsp;('.lang('sales_change_register').')</span>';
							echo form_open('sales', array('id'=>'opening_amount_form', 'class'=>'form-horizontal')); ?>
								<div class="form-group">
									<?php echo form_label(lang('sales_opening_amount').':', 'opening_amount',array('class'=>'col-lg-4 col-md-5 control-label')); ?>
									<div class="col-lg-8 col-md-7">
										<div class="input-group">
											<span class="input-group-addon">
											<i class="fa fa-dollar"></i>
											</span>
											<?php echo form_input(array(
										        'name'=>'opening_amount',
										        'id'=>'opening_amount',
										        'class'=>'form-control form-inps',
										        'value'=>'')
										    );?>
										    <span class="input-group-btn">
												<?php echo form_button(array(
													'name'=>'submit',
													'type'=>'submit',
													'id'=>'submit',
													'content'=>lang('common_submit'),
													'class'=>'btn btn-success')
												);?>
											</span>
										</div>
										<span id="error"></span>
									</div>
								</div>

								<!-- TERMINAR -->
								<div class="form-group text-center">
									<h2><?php echo lang('common_or'); ?></h2>
											<?php echo lang('locations_register_name');?>:  <?php echo anchor('sales/clear_register', $reg_name);?>
									<br /><br />
								</div>
								      
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type='text/javascript'>
		//validation and submit handling
		$(document).ready(function()
		{

         <?php if($this->config->item('hide_video_stack8') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack8').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack8")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack8').css({'display':'none'});

         }
         
     
         });
         $('#closeamount').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack8').css({'display':'none'});
            	$('#maxamount').removeClass('icon fa fa-youtube-play help_button');
               	 $('#maxamount').html("<a href='javascript:;' id='maxamount' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
            	
               
              

         });
      
         $('#checkBoxStack8').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video8:$(this).is(':checked') ? '1' : '0',video8:'hide_video_stack8'});
          });
      	 $('#closamount').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack2').css({'display':'none'});
               $('#maxamount').removeClass('icon fa fa-youtube-play help_button');
               $('#maxamount').html("<a href='javascript:;' id='maxamount' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });    
               
         
			$("#opening_amount").focus();
			
			var submitting = false;

			$('#opening_amount_form').validate({
				submitHandler:function(form)
				{	
					var submitting = false;
				 	if (submitting) return;
					submitting = true;
					$("#portlet-content").plainOverlay('show');

					$(form).ajaxSubmit({
						success:function(response)
						{
							console.log(response);
							$("#portlet-content").plainOverlay('hide');
							if(response.success)
							{ 																	
							    window.location = '<?php echo site_url('sales'); ?>';									
							}
							else
							{	
								toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
							}

							submitting = false;
						},
						dataType:'json',
						resetForm: false
					});
			    },				    

				rules:
				{
					opening_amount: {
						required: true,
						number: true
					}
		   		},
				errorClass: "text-danger",
				errorElement: "span",
				errorLabelContainer: "#error",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
				},
				messages: {
			   		opening_amount: {
						required: <?php echo json_encode(lang('sales_amount_required')); ?>,
						number: <?php echo json_encode(lang('sales_amount_number')); ?>
					}
		   		}
			});
			
			function calculate_total()
			{
				var add = 0;
		   		var total=0;
		    	var contar=$('#grupo > input').length;

				for(var i=0; i<contar; i++)
				{
					currency = $("#currency" +i).val();
					quantity= $("#quantity" +i ).val();
				    total+=quantity*currency;
			 	}
				$("#opening_amount").val(parseFloat(Math.round(total * 100) / 100).toFixed(2));			
			}

			var contar=$('#grupo > input').length;
			for(var i=0; i<contar; i++)
			{
				$("#currency"+i).change(calculate_total);
				$("#currency"+i).keyup(calculate_total);
		    }
		 });
	</script>

<?php $this->load->view('partial/footer.php'); ?>