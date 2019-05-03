<?php $this->load->view("partial/header"); ?>
	<?php
		$is_integrated_credit_sale = is_sale_integrated_cc_processing();
		if (isset($error_message))
		{
			echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
			exit;
		} 
	?>

	<div class="portlet light no-padding-general hidden-print">
		<div class="portlet-body">
			<div class="row margin-top-15 hidden-print">
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="print_receipt()" > 
						<?php echo lang('sales_print'); ?> 
					</button>
				</div>				
				
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button class="btn btn-success btn-block hidden-print" id="new_sale_button_1" onclick="window.location='<?php echo site_url('sales'); ?>'" >
						<?php echo lang('sales_sale'); ?> 
					</button>
				</div>
			</div>
		</div>
	</div>
<!-- cajon monedero abrir-->
	<div class="portlet light no-padding-general">
		<div class="portlet-body"> 
			<div id="receipt_wrapper"  style="padding:<?php echo $this->config->item('padding_ticket')?>px !important;"	class="receipt_<?php echo $this->config->item('receipt_text_size')?>";>
				<div id="receipt_header">
						<div id="codigo">
						<p>l</p>
						</div>
					</div>
				
				
			</div>

			
		</div>
	</div>

		<script type="text/javascript">
			$(window).bind("load", function() {
				print_receipt();
				window.location = '<?php echo site_url("sales"); ?>';
			});
		</script>
	

	<script type="text/javascript">


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