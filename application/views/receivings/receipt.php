<?php $this->load->view("partial/header"); ?>
	<?php
		if (isset($error_message))
		{
			echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
			exit;
		}
	?>

	<div class="portlet light">
		<div class="portlet-body">
			<div id="receipt_wrapper" class="receipt_<?php echo $this->config->item('receipt_text_size');?>">				
				<div id="receipt_header">
					<div id="company_name">
						<?php echo $this->config->item('company'); ?>
					</div>
					<div id="company_address">
						<?php echo nl2br($this->Location->get_info_for_key('address')); ?>
					</div>
					<?php if($this->config->item('company_logo')) {?>
						<div id="company_logo">
							<?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?>
						</div>
					<?php } ?>
					<div id="company_phone">
						<?php echo $this->Location->get_info_for_key('phone'); ?>
					</div>
					<div id="sale_receipt">
						<?php echo $receipt_title; ?>
					</div>
					<div id="sale_time">
						<?php echo $transaction_time ?>
					</div>
					
				</div>
				<div id="receipt_general_info">
					<?php if(isset($supplier)) { ?>
						<div id="customer">
							<?php echo lang('suppliers_supplier').": ".$supplier; ?>
						</div>
					<?php } ?>
					<?php if(isset($transfer_to_location)) { ?>
						<div id="transfer_from">
							<?php echo lang('receivings_transfer_from').': '.$transfer_from_location ?>
						</div>
						<div id="transfer_to">
							<?php echo lang('receivings_transfer_to').': '.$transfer_to_location ?>
						</div>
					<?php } ?>
					<div id="sale_id">
						<?php echo lang('receivings_id').": ".$receiving_id; ?>
					</div>
					<div id="employee">
						<?php echo lang('employees_employee').": ".$employee; ?>
					</div>
				</div>
				<!-- <div class="table-responsive">  -->
				<table id="receipt_items">
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
						<th class="left_text_align" style="width:30%;"><?php echo lang('items_item'); ?></th>
						<th class="left_text_align" style="width:15%;"><?php echo lang('receivings_cost_without_tax'); ?></th>
						<th class="left_text_align" style="width:12%;"><?php echo lang('receivings_item_tax'); ?></th>
						<th class="left_text_align" style="width:12%;"><?php echo lang('receivings_cost'); ?></th>
						<th class="left_text_align" style="width:10%;"><?php echo lang('sales_quantity'); ?></th>
						<th class="left_text_align" style="width:10%;"><?php echo lang('sales_discount'); ?></th>
						<th class="left_text_align" style="width:10%;"><?php echo lang('receivings_cost_transport'); ?></th>
						<th class="right_text_align" style="width:25%;"><?php echo lang('sales_total'); ?></th>
					</tr>

					<?php foreach(array_reverse($cart, true) as $line=>$item) { ?>
						<?php
							$tax_info = isset($item['item_id']) ? $this->Item_taxes_finder->get_info($item['item_id']) : $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
							$i=0;
							foreach($tax_info as $key=>$tax)
							{
								$prev_tax[$item['item_id']][$i]=$tax['percent']/100;
								$i++;
							}												
							$sum_tax=array_sum($prev_tax[$item['item_id']]);
							$value_tax=$item['price']*$sum_tax;
							if (isset($type_supplier)) 
							{
								$cost_with_tax=$item['price']+$value_tax+$item['cost_transport'];
							}
							else
							{
								$cost_with_tax=$item['price']+$item['cost_transport'];																					
							}															
						?>
						<tr>
							<td class="left_text_align"><?php echo character_limiter(H($item['name']),40); ?></td>
							<td class="left_text_align"><?php echo to_currency($item['price'], 2); ?></td>		
							<?php if (isset($type_supplier)){?>
							{
								<td class="left_text_align"><?php echo to_currency($value_tax, 2); ?></td>
								<td class="left_text_align"><?php echo to_currency($cost_with_tax, 2);?></td>
							}					
						    <?php } 
						    else{?>
						    	<td class="left_text_align"><?php echo to_currency(0, 2); ?></td>
								<td class="left_text_align"><?php echo to_currency($cost_with_tax, 2);?></td>
							<?php }?>
							<td class="left_text_align"><?php echo round($item['quantity']); ?></td>							
							<td class="left_text_align"><?php echo $item['discount']; ?></td>
							<td class="left_text_align"><?php echo to_currency($item['cost_transport']); ?></td>
							<td class="right_text_align"><?php echo to_currency($cost_with_tax*$item['quantity']-$cost_with_tax*$item['quantity']*$item['discount']/100, 2); ?></td>
						</tr>

					    <tr>
						    <td colspan="3" align="left"><?php echo H($item['description']); ?></td>
							<td colspan="1" ><?php echo H($item['serialnumber']); ?></td>
							<td class="right_text_align" colspan="2"><?php echo '---'; ?></td>
					    </tr>
					<?php } ?>	
					<tr>
					<td colspan="8" style='border-top:1px solid #000000;'></td>
					</tr>
					
					<tr>
						<td class="right_text_align" colspan="6" style='solid #000000;'><?php echo lang('sales_sub_total'); ?></td>
						<td class="right_text_align" colspan="2" style=' solid #000000;'><?php echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ; ?></td>
					</tr>

					<tr>
						<td class="right_text_align" colspan="6" style='solid #000000;'><?php echo lang('receivings_total_cost_transport'); ?></td>
						<td class="right_text_align" colspan="2" style=' solid #000000;'><?php echo $this->config->item('round_value')==1 ? to_currency(round($total_cost_transport)) :to_currency($total_cost_transport) ; ?></td>
					</tr>

					<?php if ($this->config->item('group_all_taxes_on_receipt')) { ?>
						<?php 
							$total_tax = 0;
							foreach($taxes as $name=>$value) 
							{
								$total_tax+=$value;
				 			}
						?>	
						<tr>
						<td class="right_text_align" colspan="6" style='solid #000000;'>
								<?php echo lang('reports_tax'); ?>:
							</td>
							
							<td class="right_text_align" colspan="2" style='solid #000000;'>
								<?php echo $this->config->item('round_value')==1 ? to_currency(round($total_tax)) :to_currency($total_tax) ; ?>
							</td>
						</tr>
					
					<?php } 
					elseif(isset($taxes))
					{
						foreach($taxes as $name=>$value) { 
							if(!($value=='0.00')) { ?>
								<tr >
								<td class="right_text_align" colspan="6" style='solid #000000;'>
										<?php echo $name; ?>:
									</td>
								<td class="right_text_align" colspan="2" style='solid #000000;'>
										<?php echo $this->config->item('round_value')==1 ? to_currency(round($value)) :to_currency($value) ; ?>
									</td>
								</tr>
							<?php }						
						}
					} ?>
					
					<tr>
						<td class="right_text_align" colspan="6" style='solid #000000;'><?php echo lang('sales_total'); ?></td>
						<td class="right_text_align" colspan="2" style=' solid #000000;'><?php echo to_currency($total, 10); ?></td>
					</tr>

					<tr>
						<td class="right_text_align" colspan="6"><?php echo lang('sales_payment'); ?></td>
						<td class="right_text_align" colspan="2"><?php echo ($payment_type== "") ? "Ninguno" : $payment_type; ?></td>
					</tr>

					<?php if(isset($amount_change)) { ?>
						<tr>
							<td class="right_text_align" colspan="6"><?php echo lang('sales_amount_tendered'); ?></td>
							<td class="right_text_align" colspan="2"><?php echo to_currency($amount_tendered, 10); ?></td>
						</tr>

						<tr>
							<td class="right_text_align" colspan="6"><?php echo lang('sales_change_due'); ?></td>
							<td class="right_text_align" colspan="2"><?php echo $amount_change; ?></td>
						</tr>
					<?php } ?>
				</table>
				<!-- </div> -->

				<div id="sale_return_policy">
					<?php echo nl2br($this->config->item('return_policy')); ?>
				</div>
				<?php if (!$this->config->item('hide_barcode_on_sales_and_recv_receipt')) {?>
					<div id='barcode'>
						<?php echo "<img src='".site_url('barcode')."?barcode=$receiving_id&text=$receiving_id' />"; ?>
					</div>
				<?php } ?>
				
				<hr class="hidden-print" />

				<div class="row">
					<div class="col-md-12">
						<div class="pull-right">
							<button class="btn btn-warning hidden-print" id="print_button" onClick="window.print()" > <?php echo lang('sales_print'); ?> </button>
							<button class="btn btn-success hidden-print" id="new_receiving_button_2" onclick="window.location='<?php echo site_url('receivings'); ?>'" > <?php echo lang('receivings_new_receiving'); ?> </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php if ($this->config->item('print_after_receiving')) { ?>
		<script type="text/javascript">
			$(window).load(function()
			{
				window.print();
			});
		</script>
	<?php } ?>

<?php $this->load->view("partial/footer"); ?>	