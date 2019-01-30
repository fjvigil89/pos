<?php $this->load->view("partial/header"); ?>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title hidden-print">
			<h1>
				<i class="icon fa fa-bar-chart"></i>
				<?php echo lang("items_inventory_tracking"); ?>
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
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">
							<span class="icon hidden-print">
								<i class="fa fa-file"></i>
							</span>
							<?php echo lang("items_basic_information"); ?>
						</div>						
					</div>
					<div class="portlet-body form">
						<?php echo form_open('items/save_inventory/'.$item_info->item_id,array('id'=>'item_form', 'class'=>'form-horizontal')); ?>
							<div class="form-body">							
								
								<div class="form-group">
									<?php echo form_label(lang('items_item_number').':', 'name',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$inumber = array (
											'name'=>'item_number',
											'id'=>'item_number',
											'class'=>'form-control',
											'value'=>$item_info->item_number,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($inumber)
										?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_name').':', 'name',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$iname = array (
											'name'=>'name',
											'id'=>'name',
											'class'=>'form-control',
											'value'=>$item_info->name,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($iname);
										?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_category').':', 'category',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$cat = array (
											'name'=>'category',
											'id'=>'category',
											'class'=>'form-control',
											'value'=>$item_info->category,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($cat);
										?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_model').':', 'model',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$model = array (
											'name'=>'model',
											'id'=>'model',
											'class'=>'form-control',
											'value'=>$item_info->model,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($model);
										?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_colour').':', 'colour',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$colour = array (
											'name'=>'colour',
											'id'=>'colour',
											'class'=>'form-control',
											'value'=>$item_info->colour,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($colour);
										?>
									</div>
								</div>
								<div class="form-group">
									<?php echo form_label(lang('items_brand').':', 'marca',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$marca = array (
											'name'=>'marca',
											'id'=>'colour',
											'class'=>'form-control',
											'value'=>$item_info->marca,
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($marca);
										?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_current_quantity').':', 'quantity',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php 
											$qty = array (
											'name'=>'quantity',
											'id'=>'quantity',
											'class'=>'form-control',
											'value'=>to_quantity($item_location_info->quantity),
											'style'=>'border:none',
											'readonly'=>'readonly'
											);
											echo form_input($qty);
										?>
									</div>
								</div>

								<div class="form-group hidden-print">
									<?php echo form_label(lang('items_add_minus').':', 'quantity',array('class'=>'col-md-3 control-label requireds wide')); ?>
									<div class="col-md-9">
										<?php echo form_input(array(
											'name'=>'newquantity',
											'id'=>'newquantity',
											'class'=>'form-control form-inps'));
										?>
									</div>
								</div>

								<div class="form-group hidden-print">
									<?php echo form_label(lang('items_inventory_comments').':', 'description',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-9">
										<?php echo form_textarea(array(
											'name'=>'trans_comment',
											'id'=>'trans_comment',
											'class'=>'form-control form-inps',
											'rows'=>'3',
											'cols'=>'17'));
										?>
									</div>
								</div>
										
								<div class="form-group">
									<div class="col-md-12">	                    
		                    			<?php
											echo form_button(array(
											'type'=>'submit',
											'name'=>'submit',
											'id'=>'submit',
											'content'=>lang('common_submit'),
											'class'=>'btn btn-primary hidden-print pull-right'));
										?>
									</div>
								</div>
																	
							</div>	
						<?php  echo form_close(); ?>
					</div>
				</div>
				
				<div class="panel panel-default">					
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr align="center" style="font-weight:bold">
										<td width="15%"><?php echo lang("items_inventory_tracking"); ?></td>
										<td width="25%"><?php echo lang("employees_employee"); ?></td>
										<td width="15%"><?php echo lang("items_in_out_qty"); ?></td>
										<td width="45%"><?php echo lang("items_remarks"); ?></td>
									</tr>
								</thead>
								<tbody>
									<?php foreach($this->Inventory->get_inventory_data_for_item($item_info->item_id)->result_array() as $row) { ?>
										<tr  align="center">
											<td><?php echo date(get_date_format(). ' '.get_time_format(), strtotime($row['trans_date']))?></td>
											<td>
												<?php
													$person_id = $row['trans_user'];
													$employee = $this->Employee->get_info($person_id);
													echo $employee->first_name." ".$employee->last_name;
												?>
											</td>
											<td align="center"><?php echo to_quantity($row['trans_inventory']);?></td>
											
											<?php
											$row['trans_comment'] = preg_replace('/'.$this->config->item('sale_prefix').' ([0-9]+)/', anchor('sales/receipt/$1', $row['trans_comment']), $row['trans_comment']);
																							$row['trans_comment'] = preg_replace('/RECV ([0-9]+)/', anchor('receivings/receipt/$1', $row['trans_comment']), $row['trans_comment']);
											?>
											<td><?php echo $row['trans_comment'];?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<div class="text-center">
								<button class="btn btn-primary btn-sm hidden-print" id="print_button" > Imprimir </button>	
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	
			
	<script type='text/javascript'>
		function print_inventory()
		{
		 	window.print();
		}

		//validation and submit handling
		$(document).ready(function()
		{	
			
<?php if($this->config->item('hide_video_stack4') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack4').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack4")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack4').css({'display':'none'});

         }
         
     
         });
      
         $('#checkBoxStack4').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video4:$(this).is(':checked') ? '1' : '0',video4:'hide_video_stack4'});

             
               
         });


			$('#print_button').click(function(e){
				e.preventDefault();
				print_inventory();
			});

			var submitting = false;
			$('#item_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$(form).ajaxSubmit({
					success:function(response)
					{
							if(!response.success)
								{ 
									toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);																		
								}
								else
								{
									toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);									
									setTimeout(function()
									{
										window.location.reload(true);								
									}, 1200);
								}
							submitting = false;
					},
					dataType:'json'
				});

				},
					errorClass: "text-danger",
					errorElement: "span",
					highlight:function(element, errorClass, validClass) {
						$(element).parents('.control-group').addClass('error');
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).parents('.control-group').removeClass('error');
						$(element).parents('.control-group').addClass('success');
					},
				rules: 
				{
					newquantity:
					{
						required:true,
						number:true
					}
		   		},
				messages: 
				{
					
					newquantity:
					{
						required:<?php echo json_encode(lang('items_quantity_required')); ?>,
						number:<?php echo json_encode(lang('items_quantity_number')); ?>
					}
				}
			});
		});
	</script>

<?php $this->load->view('partial/footer'); ?>
