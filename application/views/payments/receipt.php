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
						<?php 
					 		
					 	
			                 	echo lang('sales_store_account_name').' '.$petty_cash->petty_cash_id;
					 		
							
				 		?>
				 		</strong> 
			 		</div>
					<div id="sale_time">
						<?php echo date('Y-m-d,H:i:s', time()); ?>
					</div>
					
				</div>

				<div id="receipt_general_info">
					<?php if(isset($customer)) { ?>
						<div id="customer">
							<?php echo lang('customers_customer').": ".$customer->first_name; ?>
						</div>
						<?php if(!empty($customer->address_1)){ ?>
							<div>
								<?php echo lang('common_address'); ?> : <?php echo $customer->address_1. ' '.$customer->address_2; ?>
							</div>
						<?php } ?>
						<?php if (!empty($customer->city)) { 
							echo $customer->city.' '.$customer->state.', '.$customer->zip;
						} ?>
						<?php if (!empty($customer->country)) { 
							echo '<div>'.$customer->country.'</div>';
						} ?>			
						<?php if(!empty($customer->phone)){ ?>
							<div>
								<?php echo lang('common_phone_number'); ?> : <?php echo $customer->phone; ?>
							</div>
						<?php } ?>
						<?php if(!empty($customer->email)){ ?>
							<div>
								<?php echo lang('common_email'); ?> : <?php echo $customer->email; ?>
							</div>
						<?php } ?>
					<?php } ?>	

							
					<div id="employee">
						<?php echo lang('employees_employee').": ".$employee->first_name; ?>
					</div>
					
					<?php if($this->Location->get_info_for_key('enable_credit_card_processing')){
						echo '<div id="mercahnt_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
					} ?>				
				</div>

				<table id="details_payments">
								
						<tr>
						<th class="text-center" style="width:100%" colspan="7"><?php echo lang('sales_details_payments'); ?></th>						
					    </tr>
						</tr>
						<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">			
							<th class="left_text_align" style="width:30%" colspan="2"><?php echo lang('sales_payment_date'); ?></th>
							<th class="right_text_align" style="width:40%" colspan="3"><?php echo lang('sales_payment_type'); ?></th>											
							<th class="right_text_align" style="width30%" colspan="2"><?php echo lang('sales_total'); ?></th>
						</tr>
								<tr>	
															
									<td class="left_text_align" colspan="2">
										<?php 
										echo date(get_date_format(), strtotime($petty_cash->petty_cash_time));
										?> 
									</td>
									<td class="right_text_align" colspan="3">
										<?php 
										echo $petty_cash->payment_type;
										?> 
									</td>
									<td class="right_text_align" colspan="2">
										<?php 
										echo to_currency($petty_cash->monton_total);
										?> 
									</td>
								</tr>
				</table>

					<div id='barcode'>
					<?php 
							$code_barra="ID ".$petty_cash->petty_cash_id;/*						
					 		if(isset($mode) && $mode=='return') {
								$code_barra=  "ID ".$sale_id_raw;
					 		} 
					 		elseif(isset($mode) && $store_account_payment==1) {
								$code_barra= 'ID '.$sale_id_raw;
					 		} 
							elseif($show_comment_ticket==1) {
								$code_barra= 'ID '.$sale_id_raw;
					 		} 
					 		else
					 		{
								$code_barra= 'ID '.$sale_id_raw;
					 		}  
				 		?>
						<?php */
/*
						if(isset($mode) && $mode=='return') {
			                 	 $sale_id=lang('items_sale_mode')." ".$sale_id;
					 		} 
					 		elseif(isset($mode) && $store_account_payment==1) {
			                 	 $sale_id= lang('sales_store_account_name').' '.$sale_id;
					 		} 
							elseif($show_comment_ticket==1) {
			                 	$sale_id='BOLETA'.' '.$sale_id_raw;
							 } 
							 $bar ="<img src='".site_url('barcode')."?barcode=$code_barra&text=".ucfirst(strtolower($this->config->item('sale_prefix')))." $sale_number' />";
							 */
					 		$bar ="<img src='".site_url('barcode')."?barcode=$$petty_cash->petty_cash_id&text="." $code_barra' />";
						echo $bar;  ?>
					</div>
				<br>
				
			</div>

			<div id="duplicate_receipt_holder">
			
			</div>
		</div>
	</div>


	<?php if ($this->config->item('print_after_sale')) { ?>
		<script type="text/javascript">
			$(window).bind("load", function() {
				print_receipt();
				window.location = '<?php echo site_url("sales"); ?>';
			});
		</script>
	<?php } ?>

	<script type="text/javascript">

		$(document).ready(function(){
			$("#email_receipt").click(function()
			{
				$.get($(this).attr('href'), function()
				{
					toastr.success('<?php echo lang('sales_receipt_sent'); ?>', <?php echo json_encode(lang('common_success')); ?>);				
				});
				
				return false;
			});
		});

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
	<script type="text/print" id="print_output">
		<?php echo $this->config->item('company'); ?>

		<?php echo $this->Location->get_info_for_key('address'); ?>

		<?php echo $this->Location->get_info_for_key('phone'); ?>

		<?php if($this->config->item('website')) { ?>
			<?php echo $this->config->item('website'); ?>
		<?php } ?>

		<?php echo $receipt_title; ?>

		<?php echo $transaction_time; ?>

		<?php if(isset($customer)) { ?>
			<?php echo lang('customers_customer').": ".$customer; ?>

			<?php if(!empty($customer_address_1)){ ?>
				<?php echo lang('common_address'); ?>: <?php echo $customer_address_1. ' '.$customer_address_2; ?>	
			<?php } ?>
			<?php if (!empty($customer_city)) { 
				echo $customer_city.' '.$customer_state.', '.$customer_zip; ?>
			<?php } ?>
			<?php if (!empty($customer_country)) { 
				echo $customer_country; ?>
			<?php } ?>
			<?php if(!empty($customer_phone)){ ?>
				<?php echo lang('common_phone_number'); ?> : <?php echo $customer_phone; ?>
			<?php } ?>
			<?php if(!empty($customer_email)){ ?>
				<?php echo lang('common_email'); ?> : <?php echo $customer_email; ?>
			<?php } ?>
		<?php } ?>

		<?php echo lang('sales_id').": ".$sale_id; ?>

		<?php if (isset($sale_type)) { ?>
			<?php echo $sale_type; ?>
		<?php } ?>

		<?php echo lang('employees_employee').": ".$employee; ?>

		<?php if($this->Location->get_info_for_key('enable_credit_card_processing')) {
			echo lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id');
		}?>

		<?php echo lang('items_item'); ?>            

		<?php echo lang('common_price'); ?> 

		<?php echo lang('sales_quantity'); ?>

		<?php if($discount_exists){echo ' '.lang('sales_discount');}?> <?php echo lang('sales_total'); ?>

		<?php foreach(array_reverse($cart, true) as $line=>$item) { ?>
			<?php echo character_limiter($item['name'], 14,'...'); ?><?php echo strlen($item['name']) < 14 ? str_repeat(' ', 14 - strlen($item['name'])) : ''; ?> <?php echo str_replace('&#8209;', '-', to_currency($item['price'])); ?> <?php echo to_quantity($item['quantity']); ?><?php if($discount_exists){echo ' '.$item['discount'];}?> <?php echo str_replace('&#8209;', '-', to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)); ?>

	  		<?php echo $item['description']; ?>  <?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?>
		<?php } ?>

		<?php echo lang('sales_sub_total'); ?>: <?php echo str_replace('&#8209;', '-', to_currency($subtotal)); ?>

		<?php foreach($taxes as $name=>$value) { ?>
			<?php echo $name; ?>: <?php echo str_replace('&#8209;', '-', to_currency($value)); ?>
		<?php }; ?>

		<?php echo lang('sales_total'); ?>: <?php echo $this->config->item('round_cash_on_sales') && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($total))) : str_replace('&#8209;', '-', to_currency($total)); ?>

		<?php foreach($payments as $payment_id=>$payment) { ?>
			<?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?>  <?php if ($is_integrated_credit_sale || sale_has_partial_credit_card_payment()) { ?><?php $splitpayment=explode(':',$payment['payment_type']);echo $splitpayment[0]; ?>: <?php echo $payment['card_issuer']. ' '.$payment['truncated_card']; ?> <?php } else { ?><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> <?php } ?><?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($payment['payment_amount']))) : str_replace('&#8209;', '-', to_currency($payment['payment_amount'])); ?>
		<?php } ?>	

		<?php foreach($payments as $payment) { 
			$giftcard_payment_row = explode(':', $payment['payment_type']);?>
			<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
				<?php echo lang('sales_giftcard_balance'); ?>  <?php echo $payment['payment_type'];?>: <?php echo str_replace('&#8209;', '-', to_currency($this->Giftcard->get_giftcard_value(end($giftcard_payment_row)))); ?>
			<?php }?>
		<?php }?>

		<?php if ($amount_change >= 0) {?>
			<?php echo lang('sales_change_due'); ?>: <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($amount_change))) : str_replace('&#8209;', '-', to_currency($amount_change)); ?>
		<?php }
		else { ?>
			<?php echo lang('sales_amount_due'); ?>: <?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  str_replace('&#8209;', '-', to_currency(round_to_nearest_05($amount_change * -1))) : str_replace('&#8209;', '-', to_currency($amount_change * -1)); ?>
		<?php } ?>
		<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE) {?>		
			<?php echo lang('sales_customer_account_balance'); ?>: <?php echo to_currency($customer_balance_for_sale); ?>
		<?php } ?>
		<?php if ($ref_no) { ?>
			<?php echo lang('sales_ref_no'); ?>: <?php echo $ref_no; ?>
		<?php }
		if (isset($auth_code) && $auth_code) { ?>
			<?php echo lang('sales_auth_code'); ?>: <?php echo $auth_code; ?>
		<?php } ?>

		<?php if($show_comment_on_receipt==1){echo $comment;} ?>

		<?php $this->config->item('return_policy'); ?>

		<?php if(!$this->config->item('hide_signature')) { ?>
			<?php foreach($payments as $payment) {?>
				<?php if (strpos($payment['payment_type'], lang('sales_credit'))!== FALSE) {?>			
					<?php echo lang('sales_signature'); ?>: 
					<?php echo lang('sales_card_statement');
					break; ?>
				<?php }?>
			<?php }?>
		<?php } ?>
	</script>
	

<?php $this->load->view("partial/footer"); ?>