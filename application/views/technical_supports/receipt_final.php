<?php $this->load->view("partial/header"); ?>
	

	<div class="portlet light no-padding-general hidden-print">
		<div class="portlet-body">
			<div class="row margin-top-15 hidden-print">
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="print_receipt()" > 
						<?php echo lang('sales_print'); ?> 
					</button>
				</div>
				
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button class="btn btn-success btn-block hidden-print" id="new_sale_button_1" onclick="window.location='<?php echo site_url('technical_supports/nuevo-servicio/-1'); ?>'" >
						<?php echo lang('technical_supports_new'); ?> 
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
					<?php if(!empty($this->Location->get_info_for_key('codigo'))) { ?>
						<div id="codigo">
							<?php echo lang("locations_code").". ".  $this->Location->get_info_for_key('codigo') ;  ?>
						</div>
					<?php } ?>
					<div id="sale_receipt">
						<strong>
						<?php 
					 		if(isset($mode) && $mode=='return') {
								echo lang('items_sale_mode').$sale_type." ".$sale_number;
								if($this->Location->get_info_for_key('show_serie')==1){									
									echo"<br>Serie: ".$serie_number;									
								}		                 	
					 		} 
					 		elseif(isset($mode) && $store_account_payment==1) {
			                 	echo lang('sales_store_account_name').' '.$sale_id;
					 		} 
							elseif($show_comment_ticket==1) {
								echo 'BOLETA '.$sale_number;
								if($this->Location->get_info_for_key('show_serie')==1){
									echo "<br>Serie: ".$serie_number;
								}			                 	
					 		} 
					 		else
					 		{
								echo $this->config->item('sale_prefix').' '.$sale_number;
								if($this->Location->get_info_for_key('show_serie')==1){
									echo "<br>Serie: ".$serie_number;									
								}
					 		}  
				 		?>
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

					<?php if (isset($sale_type)) { ?>
						<div id="sale_type">
							<?php echo $sale_type; ?>
						</div>
					<?php } ?>
					
					<?php if ($register_name) { ?>
						<div id="sale_register_used">
							<?php echo lang('locations_register_name').': '.$register_name; ?>
						</div>		
					<?php } ?>
									
					<?php if ($tier) { ?>
						<div id="tier">
							<?php echo lang('items_tier_name').': '.$tier; ?>
						</div>		
					<?php } ?>
					
					<div id="employee">
						<?php echo lang('employees_employee').": ".$employee; ?>
					</div>
					<div >
						<?php echo lang('technical_supports_orden_ret_por').": ".$retirado_por; ?>
					</div>
					<?php if ($support_info->do_have_guarantee==1) { ?>
						<div >
							<?php echo lang('technical_supports_titu_garant').': '.$support_info->date_garantia; ?>
						</div>		
					<?php } ?>
					<?php if($this->Location->get_info_for_key('enable_credit_card_processing')){
						echo '<div id="mercahnt_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
					} ?>				
				</div>
			

				<table id="receipt_items">
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
						<th class="left_text_align" style="width:<?php echo $discount_exists ? "33%" : "55%"; ?>;" colspan="<?php echo $discount_exists ? 3 : 1 ?>;"><?php echo lang('items_item'); ?></th>
						
						<?php if($this->config->item('subcategory_of_items') ){ ?>
							<th class="gift_receipt_element left_text_align" style="width:50%;">
							<?php 
							echo ($this->config->item('inhabilitar_subcategory1')==1?"":($this->config->item("custom_subcategory1_name")."/")). $this->config->item("custom_subcategory2_name");?></th>
						<?php }?>
						<?php if($show_number_item):?>
							<th class="gift_receipt_element text-center" style="width:30%;"><?php echo "UPC"/*lang('items_item_number')*/;?></th>

						<?php endif;?>
						<th class="gift_receipt_element left_text_align" style="width:20%;"><?php echo lang('common_price'); ?></th>
						<th class="text-center" style="width:15%;" colspan="<?php echo $discount_exists ? 1 : 2 ?>;"><?php echo lang('sales_quantity_short'); ?></th>
						<?php if($discount_exists) { ?>
							<th class="gift_receipt_element text-center" style="width:16%;"><?php echo lang('sales_discount_short'); ?></th>
						<?php } ?>
						<th class="gift_receipt_element right_align" style="width:5%;" colspan="3"><?php echo lang('sales_total'); ?></th>
					</tr>
					<?php foreach(array_reverse($cart, true) as $line=>$item) { ?>
						<tr  >
							<td class="left_text_align" colspan="<?php echo $discount_exists ? 3 : 1 ?>;">								
								<?php echo character_limiter(H($item['name']), $this->config->item('show_fullname_item') ? 200 : 20); ?>
								
								<?php if (isset($item['size'])){ ?> 
									<span class="hidden-print"><?php echo $item['size']; ?></span>
								<?php } ?>
								<?php if (isset($item['colour'])){ ?> 
									<span class="hidden-print"><?php echo $item['colour']; ?></span>
								<?php } ?>
								<?php if (isset($item['model'])){ ?> 
									<span class="hidden-print"><?php echo $item['model']; ?></span>
								<?php } ?>
								<?php if (isset($item['marca'])){ ?> 
									<span class="hidden-print"><?php echo $item['marca']; ?></span>
								<?php } ?>
							</td>
							<?php if($this->config->item('subcategory_of_items') ){ ?>
								<td class="gift_receipt_element left_text_align">
									<?php if (isset($item['item_id'])){ 
										echo(
											($this->config->item('inhabilitar_subcategory1')==1?"":($item['custom1_subcategory']?$item['custom1_subcategory']:"--")."/").
											($item['custom2_subcategory']?$item['custom2_subcategory']:"--")
										);
									}else{
										echo"--/--";
									}?>
								</td>
							
							<?php }?>
							<?php if($show_number_item):?>
								<td class=" gift_receipt_element text-center" colspan="1" >
									<?php 
										if (isset($item['item_number'])){ 
											echo $item['item_number']." ";
										}else if (isset($item['item_kit_number'])){
											echo $item['item_kit_number']." ";
										}
									 ?>
								</td>

							<?php endif;?>
							<td class="gift_receipt_element left_text_align">
								<?php
											echo $this->config->item('round_value')==1 ? to_currency(round($item['price'])) :to_currency($item['price']);
										
								?>
							</td>
							
							
							<td class="text-center" colspan="<?php echo $discount_exists ? 1 : 2 ?>;">
								<?php echo to_quantity($item['quantity']); ?>
								<?php
									if (isset($item['model'])){ 
								 		echo ($item['unit']);} ?>
							</td>
							<?php if($discount_exists) { ?>
								<td class="gift_receipt_element text-center">
									<?php echo $item['discount'].'%'; ?>
								</td>
							<?php } ?>

							<?php
								if (isset($item['item_id']) && $item['name']) 
								{
									$tax_info = $item['ivas'];
									$i=0;
									foreach($tax_info as $key=>$tax)
									{
										$prev_tax[$item['item_id']][$i]=$tax['percent']/100;
										$i++;
									}						

									if (isset($prev_tax[$item['item_id']]) ) 
									{									
										if(!$overwrite_tax){
											$sum_tax=array_sum($prev_tax[$item['item_id']]);
											$value_tax=$item['price']*$sum_tax;										
											$price_with_tax=$item['price']+$value_tax;
										}else{
											$value_tax=get_nuevo_iva($new_tax,$item['price']);
											$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
										}	
									}	
									elseif (!isset($prev_tax[$item['item_id']]) )
									{									
										if(!$overwrite_tax){
											$sum_tax=0;
											$value_tax=$item['price']*$sum_tax;										
											$price_with_tax=$item['price']+$value_tax;
										}else{
											$value_tax=get_nuevo_iva($new_tax,$item['price']);
											$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
										}	
									}	
								}

								/*if (isset($item['item_kit_id']) && $item['name']) 
								{
									$tax_kit_info = $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
									$i=0;									
									foreach($tax_kit_info as $key=>$tax)
									{
									   	$prev_tax[$item['item_kit_id']][$i]=$tax['percent']/100;
										$i++;
									}
									if (isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
									{										
										if(!$overwrite_tax){
											$sum_tax=array_sum($prev_tax[$item['item_kit_id']]);
											$value_tax=$item['price']*$sum_tax;									
											$price_with_tax=$item['price']+$value_tax;
										}else{
											$value_tax=get_nuevo_iva($new_tax,$item['price']);
											$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
										}
									}
									elseif (!isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard')) 
									{										
										if(!$overwrite_tax){
											$sum_tax=0;
											$value_tax=$item['price']*$sum_tax;									
											$price_with_tax=$item['price']+$value_tax;
										}else{
											$value_tax=get_nuevo_iva($new_tax,$item['price']);
											$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
										}
									}
								}				*/																							
							?>

							
								<td class="gift_receipt_element right_text_align" colspan="2">
									<?php
										 $Total=$price_with_tax*$item['quantity']-$price_with_tax*$item['quantity']*$item['discount']/100;
									 	echo $this->config->item('round_value')==1 ? to_currency(round($Total)) :to_currency($Total); 								 	
								 	?>
								 </td>
							
						</tr>

					    <tr>
					    	<?php if($item['description']!="" ){ ?>
						    	<td colspan="3" align="left">
									<?= lang('sales_description_abbrv').": ". $item['description']; ?>
					    		</td>	
				    		<?php } ?>
							<?php if( isset($item['serialnumber']) and $item["serialnumber"]!=null ):?>
								<td colspan="1" >
									<?= "Serial: ".$item['serialnumber']  ?>
								</td>
							<?php endif; ?>						
					    </tr>
						
					</tr>
					<?php } ?>					

					<tr class="gift_receipt_element">
						<td class="right_text_align" colspan="<?php echo $discount_exists ? '6' : '4'; ?>" style="border-top:1px solid #000000;">
							<?php echo lang('sales_sub_total'); ?>: 
						</td>
						<td class="right_text_align" colspan="2" style='border-top:1px solid #000000;'>
						<?php
							echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ;
						?>
						</td>							
					</tr>

					<tr class="gift_receipt_element">
						<td class="right_text_align "colspan="<?php echo $discount_exists ? '6' : '4'; ?>">
							<strong><?php echo "TOTAL ".$this->config->item('sale_prefix'); ?>:</strong>
						</td>
						<td class="right_text_align" colspan="2" >

							<?php
							$subtotal_= 0;							
							if( $this->config->item('round_cash_on_sales')==1 && $is_sale_cash_payment ){
								$subtotal_=to_currency(round_to_nearest_05($total));
							} 
							else if($this->config->item('round_value')==1  ){
								$subtotal_= to_currency(round($total));
							}else{
								$subtotal_= to_currency($total);
							}
							echo $subtotal_;
							?>
						</td>
					</tr>
					<?php if(isset($another_currency) and $another_currency==1){ ?>
						<tr class="gift_receipt_element">
						<td class="right_text_align "colspan="4">
							<strong>Total en <?php echo $currency ?>:</strong>
						</td>
						<td class="right_text_align" colspan="2" >
							<?php echo  to_currency_no_money($total_other_currency,4); ?>
							
						</td>
					</tr>
					<?php } ?>
					

					<?php foreach($payments as $payment_id=>$payment) { ?>
						<tr class="gift_receipt_element">
							<td class="right_text_align" colspan="<?php echo $discount_exists ? '6' : '4'; ?>">
								<?php echo $payment["payment_type"]!=lang('sales_store_account')?(
								(isset($another_currency) and $another_currency==1)?lang('sales_value_cancel')." ".$currency:lang('sales_value_cancel'))
								 : lang('sales_crdit'); ?>:
							</td>
							<td class="right_text_align" colspan="2">
								<?php 
								$payment_amount= 0;
								$payment_amount_value= (isset($another_currency) and $another_currency==1)?
										($payment['payment_amount']/$value_other_currency):$payment['payment_amount'];
								if( $this->config->item('round_cash_on_sales')==1 && $payment['payment_type'] == lang('sales_cash') ){
									
									$payment_amount=(round_to_nearest_05($payment_amount_value));
								} 
								else if($this->config->item('round_value')==1  ){
									$payment_amount= (round($payment_amount_value));
								}else{
									$payment_amount= $payment_amount_value;
								}
								if(isset($another_currency) and $another_currency==1){
									echo  to_currency_no_money($payment_amount,4);
								}else{
									echo to_currency($payment_amount);
								}
								?>  
							</td>
						</tr>
					<?php } ?>
					<tr>
					
		
				</table>

				<table id="receipt_items">
				<tr>
						<th class="text-center" style="width:100%" colspan="4"><?php echo "EQUIPO" ?></th>						
					</tr>
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
                        <th class="left_text_align" style="width:<?php echo "25%" ; ?>"> <?php echo lang("technical_supports_type_team") ?></th>
						<th class="text-center" style="width:25%;" ><?php echo"Marca" ?></th>
						<th class="text-center" style="width:25%;" ><?php echo lang('technical_supports_model'); ?></th>
                 	</tr>
                 <tr>
					<td class="left_text_align">							
							<?php echo $support_info->type_team ?>
                    </td>
					<td class="text-center">							
							<?php echo  $support_info->marca ?>
                    </td>
                    <td class="text-center" >							
							<?php echo  $support_info->model ?>
                    </td>
					
                  
                </tr>
            </table>
			<table id="details_options">
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
                        <th class="left_text_align" style="width:50%;" ><?php echo  $this->config->item("custom1_support_name");?></th>
						<th class="text-center" style="width:50%;" ><?php echo $this->config->item("custom2_support_name"); ?></th>
                	</tr>
                	<tr>
						<td class="left_text_align">							
							<?php echo $support_info->custom1_support_name ?>
                    	</td>                    
                    	<td class="text-center" >							
							<?php echo $support_info->custom2_support_name ?>
                    	</td>
               		</tr>
            </table>

				<?php if(isset($show_comment_ticket) && ($show_comment_ticket == 0 && $this->config->item('hide_invoice_taxes_details') == 0)  || ($this->config->item('hide_ticket_taxes')==0 && $show_comment_ticket == 1)){ ?>
				<table id="details_taxes">
					<tr>
						<th class="text-center" style="width:100%" colspan="6"><?php echo lang('sales_details_tax'); ?></th>						
					</tr>
					<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">
						<th class="text-center" colspan="2"><?php echo lang('sales_type_tax'); ?></th>
						<th class="right_text_align" style="width:20%"><?php echo lang('sales_base_tax'); ?></th>
						<th class="right_text_align" style="width:25%"><?php echo lang('sales_price_tax'); ?></th>						
						<th class="right_text_align" style="width:18%" colspan="2"><?php echo lang('sales_value_receiving'); ?></th>
					</tr>

					<?php if ($this->config->item('group_all_taxes_on_receipt')) { ?>
						<?php 
							$total_tax = 0;
							foreach($detailed_taxes as $name=>$value) 
							{
								$total_tax+=$value['total_tax'];
				 			}
						?>	
						<tr class="gift_receipt_element">
							<td class="left_text_align" colspan="2">
								<?php echo lang('reports_tax'); ?>:
							</td>
							<td class="right_text_align">
							<?php 
									echo $value['base']; 	
								  
								 ?>
							</td>
							<td class="right_text_align">
								<?php
									echo to_currency_no_money(round($value['total_tax']),1); 	
								  
								?>
							</td>
							<td class="right_text_align" colspan="2">	
								<?php echo $this->config->item('round_value')==1 ? to_currency(round($total_tax)) :to_currency($total_tax) ; ?>
							</td>
						</tr>
					<?php } 
					else {
						
						foreach($detailed_taxes as $name=>$value) {  							
							//if(!($value=='0.00')) { ?>
								<tr class="gift_receipt_element">
									<td class="left_text_align" colspan="2">
										<?php echo $name; ?>:
									</td>
									<td class="right_text_align">
									<?php
												echo $value['base']; 	
											
								 	?>
									</td>
									<td class="right_text_align">
									<?php 
											echo to_currency_no_money(round($value['total_tax']),1); 	
									?>
									</td>
									<td class="right_text_align" colspan="2">
									<?php	
										 echo $this->config->item('round_value')==1 ? to_currency(round($value['total'])) :to_currency($value['total']);
										 ?>
									</td>
								</tr>
							<?php //}
						}
					} ?>
					<tr style="border-top: 1px dashed #000000;">
						<th class="text-center" colspan="2"><?php echo lang('sales_total').'='; ?></th>
						<?php ?>
							<th class="right_text_align" style="width:15%"><?php echo to_currency($detailed_taxes_total['total_base_sum']); ?></th>
							<th class="right_text_align" style="width:25%"><?php echo to_currency($detailed_taxes_total['total_tax_sum']); ?></th>
							<th class="right_text_align" style="width:25%" colspan="2"><?php echo $this->config->item('round_value')==1 ? to_currency($detailed_taxes_total['total_sum']) :to_currency($detailed_taxes_total['total_sum']) ; ?></th>
							<?php //}
							
					   		 ?>
					</tr>						

				</table>
			<?php } ?>

				<?php if($this->config->item('ocultar_forma_pago')==0):?>
				<table id="details_payments">
					<tr>
						<th class="text-center" style="width:100%" colspan="7"><?php echo lang('sales_details_payments'); ?></th>						
					</tr>
					<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">
						<th class="left_text_align" style="width:30%" colspan="3"><?php echo lang('sales_payment_date'); ?></th>
						<th class="right_text_align" style="width:45%" colspan="3"><?php echo lang('sales_payment_type'); ?></th>											
						<th class="right_text_align" style="width:2%" colspan="2"><?php echo lang('sales_payment_value'); ?></th>
					</tr>
					<?php foreach($payments as $payment_id=>$payment) { ?>
						<tr class="gift_receipt_element">
							<td class="left_text_align" colspan="3">
								<?php echo (isset($show_payment_times) && $show_payment_times) ?  date(get_date_format().' '.get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?>
							</td>
					
							
							
								<td class="right_text_align" colspan="3">
									<?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> 
								</td>
								<td class="right_text_align" colspan="2">
								<?php 
								$payment_amount= 0;
								$payment_amount_otro= (isset($another_currency) and $another_currency==1)?
										($payment['payment_amount']/$value_other_currency):$payment['payment_amount'];
								if( $this->config->item('round_cash_on_sales')==1 && $payment['payment_type'] == lang('sales_cash') ){
									$payment_amount=(round_to_nearest_05($payment_amount_otro));
								} 
								else if($this->config->item('round_value')==1  ){
									$payment_amount= (round($payment_amount_otro));
								}else{
									$payment_amount= ($payment_amount_otro);
								}
								if(isset($mode) && $mode=='return') {
									$payment_amount=$payment_amount*(-1);
								}
								if(isset($another_currency) and $another_currency==1){
									echo  to_currency_no_money($payment_amount,4);
								}else{
									echo to_currency($payment_amount);
								}
								?> 
								</td>							
						</tr>
					<?php } ?>	
					 
					 

					<?php if ($amount_change >= 0) { ?>
						<tr class="gift_receipt_element" >
							<td class="right_text_align" colspan="<?php echo $discount_exists ? '6' : '6'; ?>">
								<?php echo lang('sales_change_due').((isset($another_currency) and $another_currency==1)?" ".$currency:""); ?>:
							</td>
							<td class="right_text_align" colspan="2">
							<?php
							$amount_change_= 0;
							$amount_change_otro= (isset($another_currency) and $another_currency==1)?
										($amount_change/$value_other_currency):$amount_change;
							if( $this->config->item('round_cash_on_sales')==1 && $is_sale_cash_payment ){
								$amount_change_=(round_to_nearest_05($amount_change_otro));
							} 
							else if($this->config->item('round_value')==1  ){
								$amount_change_= (round($amount_change_otro));
							}else{
								$amount_change_= ($amount_change_otro);
							}
							if(isset($another_currency) and $another_currency==1){
								echo  to_currency_no_money($amount_change_,4);
							}else{
								echo to_currency($amount_change_);
							}
							?>
							</td>
						</tr>
					<?php }
					else { ?>
						<tr>
							<td class="right_text_align" colspan="<?php echo $discount_exists ? '6' : '6'; ?>">
								<?php echo lang('sales_amount_due'); ?>:
							</td>
							<td class="right_text_align" colspan="2">
								<?php echo $this->config->item('round_cash_on_sales')  && $is_sale_cash_payment ?  to_currency(round_to_nearest_05($amount_change * -1)) : to_currency($amount_change * -1) || $this->config->item('round_value')==1 ? to_currency(round($amount_change)) :to_currency($amount_change); ?> 
							</td>
						</tr>	
					<?php } ?>
					<?php if(isset($payments_petty_cashes) and !empty($payments_petty_cashes)):?>
						<tr>
						<th class="text-center" style="width:100%" colspan="7"><?php echo lang('Last_payments_made_line_credit'); ?></th>						
					    </tr>
						</tr>
						<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">			
							<th class="left_text_align" style="width:30%" colspan="2"><?php echo lang('sales_payment_date'); ?></th>
							<th class="right_text_align" style="width:40%" colspan="3"><?php echo lang('sales_payment_type'); ?></th>											
							<th class="right_text_align" style="width30%" colspan="2"><?php echo lang('sales_total'); ?></th>
						</tr>
						<?php foreach($payments_petty_cashes as $petty_cash) { ?>
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
						<?php }?>
					<?php endif; ?>
				</table>

				<?php endif; ?>

				<table id="details_options">
					<?php foreach($payments as $payment) { ?>
						<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) { ?>
							<tr class="gift_receipt_element">								
								<td class="right_text_align" colspan="5">
									<?php echo lang('sales_giftcard_balance').$payment['payment_type'];?> 
								</td>
								<?php $giftcard_payment_row = explode(':', $payment['payment_type']); ?>
								<td class="right_text_align" colspan="2">
									<?php echo to_currency($this->Giftcard->get_giftcard_value(end($giftcard_payment_row))); ?>
								</td>
							</tr>
						<?php }?>
					<?php }?>
					
					<?php if (isset($customer_balance_for_sale) && $customer_balance_for_sale !== FALSE && !$this->config->item('hide_balance_receipt_payment')) {?>
						<tr>
							<td class="right_text_align" colspan="<?php echo $discount_exists ? '4' : '3'; ?>">
								<?php echo lang('sales_customer_account_balance'); ?>:
							</td>
							<td class="right_text_align" colspan="2">
								<?php echo to_currency($customer_balance_for_sale); ?> 
							</td>
						</tr>
					<?php } ?>
										
					<?php if (isset($points) && $this->config->item('show_point')==1 && $this->config->item('system_point')==1) { ?>
						<tr>
							<td class="right_text_align" colspan="<?php echo $discount_exists ? '4' : '3'; ?>">
								<?php echo lang('config_value_point_accumulated'); ?>:
							</td>
							<td class="right_text_align" colspan="2">
								<?php echo $points; ?> 
							</td>
						</tr>
					<?php } ?>
								
					<tr>
						<td colspan="<?php echo $discount_exists ? '5' : '4'; ?>" align="right">
							<?php if($show_comment_on_receipt==1)
								{
									echo $comment ;
								}
							?>
						</td>
					</tr>
					
					
				</table>

				<?php if (!$store_account_payment==1) { ?>
					<div id="sale_company_regimen">
						<?php echo nl2br( $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('company_regimen') : $this->config->item('company_regimen') ); ?>
					   	<br />   

						</div>
						<div id="sale_resolution">
						<?php echo nl2br($this->config->item('resolution')); ?>
					   	<br />   

						</div>
						<?php if($show_return_policy_credit ==1){?>
							<div id="sale_return_policy">
								<?php echo nl2br($this->config->item('return_policy_credit')); ?>
						   	<br />  
						<?php }
							else{?>
							<div id="sale_return_policy">
								<?php echo nl2br($this->config->item('return_policy')); ?>
						   	<br />   
						<?php	} ?>
						<?php if($this->Location->get_info_for_key('show_rango')==1):?>
							<div id="sale_rango">
								<?php echo nl2br("Rango autorizado: "/*.$this->Location->get_info_for_key('serie_number')." "*/.
								$this->Location->get_info_for_key('start_range')." a la ".
								$this->Location->get_info_for_key('final_range')); ?>
								<br />  
							</div>
							<?php endif; ?>
						
					</div>
				<?php } ?>

				<?php if (!$this->config->item('hide_barcode_on_sales_and_recv_receipt') && !$store_account_payment==1) { ?>
					<div id='barcode'>
					<?php 
							$code_barra="ID ".$sale_id_raw;/*						
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
					 		$bar ="<img src='".site_url('barcode')."?barcode=$$sale_id_raw&text="." $code_barra' />";
						echo $bar;  ?>
					</div>
				<?php } ?>
				<?php if(!$this->config->item('hide_signature')) { ?>			
					<div id="signature">				
						<?php foreach($payments as $payment) {?>
							<?php if (strpos($payment['payment_type'], lang('sales_credit'))!== FALSE) { ?>
								<?php echo lang('sales_signature'); ?> --------------------------------- <br />	
								<?php 
									echo lang('sales_card_statement');
									break;
								?>
							<?php }?>
						<?php }?>				
					</div>
					<div id="domain_ing">
						
						<br/>
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
 
 		
	</script>

	
	
<?php $this->load->view("partial/footer"); ?>