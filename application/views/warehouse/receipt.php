<?php $this->load->view("partial/header"); ?>
	

	<div class="portlet light no-padding-general hidden-print">
		<div class="portlet-body">
			<div class="row margin-top-15 hidden-print">
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="print_receipt()" > 
						<?php echo lang('sales_print'); ?> 
					</button>
				</div>
					
				
			</div>
		</div>
	</div>

	<div class="portlet light no-padding-general">
		<div class="portlet-body"> 
			<div id="receipt_wrapper"  style="padding:<?php echo $this->config->item('padding_ticket')?>px !important;"	class="receipt_<?php echo $this->config->item('receipt_text_size')?>";>
				<div id="receipt_header">
					<div id="company_name">
						<?php echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('name') : $this->config->item('company') ; ?>
					</div>
					<?php if($this->config->item('company_logo')) {?>
						<div id="company_logo">
							<?php echo img( array('src' => $this->Location->get_info_for_key('overwrite_data')==1 ? site_url('app_files/view/'.$this->Location->get_info_for_key('image_id')): $this->Appconfig->get_logo_image())); ?>
						</div>
					<?php } ?>
					<div id="company_dni">
						<?php echo lang('config_company_dni').'. ' .($this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('company_dni') : $this->config->item('company_dni')); ?>
					</div>
					<div id="company_address">
						<?php echo nl2br($this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('address'): $this->config->item('address')); ?>
					</div>
					<div id="company_phone">
						<?php echo $this->Location->get_info_for_key('phone'); ?>
					</div>
					<?php if(($this->Location->get_info_for_key('overwrite_data')==1 and  $this->Location->get_info_for_key('website') ) or $this->config->item('website')) { ?>
						<div id="website">
							<?php echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('website') : $this->config->item('website') ;  ?>
						</div>
					<?php } ?>
					<div id="sale_receipt">
						<strong>
						<?php echo lang("warehouse_numero")." ".$orden_info->number;?>
				 		</strong> 
			 		</div>
					<div id="sale_time">
						<?php echo $transaction_time ?>
					</div>
					
				</div>

				<div id="receipt_general_info">
					<?php if(isset($customer)) { ?>
						<div id="customer">
							<?php echo lang('customers_customer').": ".$customer; ?>
						</div>
						<?php if(!empty($customer_address_1)){ ?>
							<div>
								<?php echo lang('common_address'); ?> : <?php echo $customer_address_1. ' '.$customer_address_2; ?>
							</div>
						<?php } ?>
						<?php if (!empty($customer_city)) { 
							echo $customer_city.' '.$customer_state.', '.$customer_zip;
						} ?>
						<?php if (!empty($customer_country)) { 
							echo '<div>'.$customer_country.'</div>';
						} ?>			
						<?php if(!empty($customer_phone)){ ?>
							<div>
								<?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?>
							</div>
						<?php } ?>
						<?php if(!empty($customer_email)){ ?>
							<div>
								<?php echo lang('common_email'); ?> : <?php echo $customer_email; ?>
							</div>
						<?php } ?>
					<?php } ?>					
					
					<div id="employee">
						<?php echo lang('employees_employee').": ".$vendedor->first_name." ".$vendedor->last_name;; ?>
					</div>
					
									
				</div>

				<table id="receipt_items">
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
						<th class="left_text_align"  ><?php echo lang('items_item'); ?></th>						
						<th class="text-center" ><?php echo lang ("items_item_number"); ?></th>
						<th class="text-center"  ><?php echo lang('sales_quantity_short'); ?></th>						
						<th class=" right_align" > <?php echo lang("items_supplier"); ?></th>
					</tr>
					<?php foreach($items as $item) { 
						$item_info= $this->Item->get_info($item->item_id);
						$proveedore = $this->Supplier->suppliers_info($item->item_id);
						?>
						<tr style="border-top: 1px dashed #000000;">
							<td class="left_text_align"  >								
								<?php echo character_limiter(H($item_info->name), $this->config->item('show_fullname_item') ? 200 : 25); ?>													
							</td>						
							<td class="text-center"  >
								<?php echo $item_info->item_number;	 ?>
							</td>							
							<td class="text-center" >
								<?php echo to_quantity($item->quantity_purchased); ?>								
							</td>							
							<td class="right_text_align" >
								<?php
									$data_proveedores= "";
									if(count($proveedore)>0){
										foreach ($proveedore as $proveedor) { 
											$data_proveedores.=$proveedor->company_name." / "; 
										}
									}
									echo 	character_limiter(H($data_proveedores),32);										 
									?>
																
							</td>
					
						</tr>
						<?php  if(!empty($item_info->description)):?>
							<tr>							
								<td colspan="5" >
										<strong><?php echo lang("items_description");?></strong><br> <?php echo  $item_info->description?>
								</td>								
							</tr>
						<?php endif;?>
					<?php } ?>	
					
		
				</table>

				<?php if (!$this->config->item('hide_barcode_on_sales_and_recv_receipt') ) { ?>
					<div id='barcode'>
					<?php 
							$code_barra="ID ".$orden_info->order_sale_id;					
					 		

					 		$bar ="<img src='".site_url('barcode')."?barcode=$orden_info->order_sale_id&text="." $code_barra' />";
						echo $bar;  ?>
					</div>
				<?php } ?>
				
				
			</div>

			<div id="duplicate_receipt_holder">
			
			</div>
		</div>
	</div>


	<?php if ($this->config->item('print_after_sale')) { ?>
		<script type="text/javascript">
			$(window).bind("load", function() {
				print_receipt();
				//window.location = '<?php// echo site_url("sales"); ?>';
			});
		</script>
	<?php } ?>

	<script type="text/javascript">

		$("#tawkchat-minified-box").hide();
        function print_receipt()
 		{
            <?php if ($this->config->item('receipt_copies') > 1): ?>
                var receipt_amount = <?php echo $this->config->item('receipt_copies');?> - 2;
                var receipt = $('#receipt_wrapper').clone();
            $('#duplicate_receipt_holder').html(receipt);
                $("#duplicate_receipt_holder").addClass('active');
                
                for ( i = 0; i < receipt_amount; i++ )
                {
                    $('<div id="duplicate_receipt_holder_'+i+'"></div>').insertAfter( "#receipt_wrapper" );
                    $('#duplicate_receipt_holder_'+i).html(receipt.html());
                    $("#duplicate_receipt_holder_"+i).addClass('active');
                }
                
                window.print();
            <?php else: ?>
                window.print();
            <?php endif; ?>
		}
 
 		function toggle_gift_receipt()
 		{
	 		var gift_receipt_text = <?php echo json_encode(lang('sales_gift_receipt')); ?>;
	 		var regular_receipt_text = <?php echo json_encode(lang('sales_regular_receipt')); ?>;
	 
	 		if ($("#gift_receipt_button").hasClass('regular_receipt'))
	 		{
				$('#gift_receipt_button').addClass('gift_receipt');	 	
		 		$('#gift_receipt_button').removeClass('regular_receipt');
		 		$("#gift_receipt_button").text(gift_receipt_text);	
		 		$('.gift_receipt_element').show();	
	 		}
	 		else
	 		{
		 		$('#gift_receipt_button').removeClass('gift_receipt');	 	
		 		$('#gift_receipt_button').addClass('regular_receipt');
		 		$("#gift_receipt_button").text(regular_receipt_text);
		 		$('.gift_receipt_element').hide();	
	 		} 	
		}
	</script>

	

	<!-- This is used for mobile apps to print receipt-->
	
	

<?php $this->load->view("partial/footer"); ?>