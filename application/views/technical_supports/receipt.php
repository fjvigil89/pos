<?php $this->load->view("partial/header"); ?>
	<?php
		
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
					<button class="btn btn-success btn-block hidden-print" id="imprimeir" onclick="window.location='<?php echo site_url('technical_supports/view/-1'); ?>'" >
						<?php echo lang('technical_supports_new'); ?> 
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button class="btn btn-success btn-block hidden-print" id="listar" onclick="window.location='<?php echo site_url('technical_supports'); ?>'" >
						<?php echo "Listar" ?> 
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button class="btn btn-success btn-block hidden-print" id="reparar" onclick="window.location='<?php echo site_url('technical_supports/repair/'.$Id_support); ?>'" >
						<?php echo "Reparar" ?> 
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="portlet light no-padding-general">
		<div class="portlet-body">
			<div id="receipt_wrapper" class="receipt_<?php echo $this->config->item('receipt_text_size');?>">
				<div id="receipt_header">
					<div id="company_name">
						<?php echo $this->config->item('company'); ?>
					</div>
					<?php if($this->config->item('company_logo')) {?>
						<div id="company_logo">
							<?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?>
						</div>
					<?php } ?>
					<div id="company_dni">
						<?php echo lang('config_company_dni').'. '.$this->config->item('company_dni'); ?>
					</div>
					<div id="company_address">
						<?php echo nl2br($this->Location->get_info_for_key('address')); ?>
					</div>
					<div id="company_phone">
						<?php echo $this->Location->get_info_for_key('phone'); ?>
					</div>
					<?php if($this->config->item('website')) { ?>
						<div id="website">
							<?php echo $this->config->item('website'); ?>
						</div>
					<?php } ?>
					<div id="order_receipt">
						<strong>
                            <?php echo lang("technical_supports_order").' '.$order_support; ?>
						
				 		</strong>
			 		</div>
					<div id="sale_time">
						<?php echo $date_register ?>
					</div>
					
				</div>

				<div id="receipt_general_info">
                        <div id="customer">
							<?php echo lang('customers_customer').": ".$customer->first_name." ".$customer->last_name; ?>
                        </div>	
                        <?php if(!empty($customer->account_number)){ ?>
							<div>
								<?php echo "Identificación"; ?> : <?php echo$customer->account_number; ?>
							</div>
						<?php } ?>								
						<?php if(!empty($customer->phone_number)){ ?>
							<div>
								<?php echo lang('common_phone_number'); ?> : <?php echo $customer->phone_number; ?>
							</div>
						<?php } ?>
						<?php if(!empty($customer->email)){ ?>
							<div>
								<?php echo lang('common_email'); ?> : <?php echo $customer->email; ?>
							</div>
						<?php } ?>
						<?php if(!empty($state)){ ?>
							<div>
								<?php echo lang('technical_supports_name_estad_equipo_imprime'); ?> : <?php echo $state; ?>
							</div>
						<?php } ?>

								
					<div id="employee">
						<?php echo lang('employees_employee').": ".$name_employee; ?>
					</div>	
					<?php if(!empty($name_tecnico)){ ?>
							<div>
								<?php echo lang('technical_supports_name_tecnico'); ?> : <?php echo $name_tecnico; ?>
							</div>
						<?php } ?>


					<?php if($this->Location->get_info_for_key('enable_credit_card_processing')){
						echo '<div id="mercahnt_id">'.lang('config_merchant_id').': '.$this->Location->get_info_for_key('merchant_id').'</div>';
					} ?>				
				</div>				
			</div>

			<table id="receipt_items">
					<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
                        <th class="left_text_align" style="width:<?php echo "25%" ; ?>"> <?php echo lang("technical_supports_type_team") ?></th>
						<th class="text-center" style="width:25%;" ><?php echo"Marca" ?></th>
						<th class="text-center" style="width:25%;" ><?php echo lang('technical_supports_model'); ?></th>
						<th class="text-center" style="width:25%;" ><?php echo lang("technical_supports_color");?></th>                        
                 	</tr>
                 <tr>
					<td class="left_text_align">							
							<?php echo $type_team ?>
                    </td>
					<td class="text-center">							
							<?php echo $marca ?>
                    </td>
                    <td class="text-center" >							
							<?php echo $model ?>
                    </td>
					<td class="text-center" >							
							<?php echo $color ?>
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
							<?php echo $custom1_support_name ?>
                    	</td>                    
                    	<td class="text-center" >							
							<?php echo $custom2_support_name ?>
                    	</td>
               		</tr>
            </table>
			<?php if(isset($spare_parts) &&  $spare_parts->num_rows() >0) :?>
				<table id="details_options">
						<tr>
							<th class="text-center" style="width:100%" colspan="7"><?php echo"Respuestos"; ?></th>						
						</tr>
						<tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
							<th class="left_text_align" style="width:50%;" ><?php echo"Nombre";?></th>
							<th class="text-center" style="width:50%;" ><?php echo "Serie"?></th>
							<th class="text-center" style="width:50%;" ><?php echo "Cantidad" ?></th>

						</tr>
					<?php foreach($spare_parts->result() as $spare_part) :?>
						<tr>
							<td class="left_text_align">							
								<?php echo $spare_part->name ?>
							</td>                    
							<td class="text-center" >							
								<?php echo $spare_part->serie ?>
							</td>
							<td class="text-center" >							
								<?php echo $spare_part->quantity ?>
							</td>
						</tr>
						<?php endforeach?>
				</table>
			<?php endif?>
            <div id='datos_abonos'>
				
           
			 <table id="details_payments">
					<tr>
						<th class="text-center" style="width:100%" colspan="7"><?php echo lang('sales_details_payments'); ?></th>						
					</tr>
					<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">
						<th class="left_text_align" style="width:30%" ><?php echo lang('sales_payment_date'); ?></th>
						<th class="right_text_align" style="width:20%" ><?php echo lang('sales_payment_type'); ?></th>											
						<th class="right_text_align" style="width:20%" ><?php echo lang('sales_payment_value'); ?></th>
					</tr>
					<?php foreach($payments as $payment) { ?>
						<tr class="gift_receipt_element">
							<td class="left_text_align" colspan="0">
								<?php echo  date(get_date_format().' '.get_time_format(), strtotime($payment->date))  ?>
							</td>						
							<td class="right_text_align" colspan="0">
								<?php echo lang("technical_supports_abono") ?>
							</td>
							<td class="right_text_align" colspan="0">
								<?php echo to_currency($payment->payment ? $payment->payment:0);?>
							</td>
											
						</tr>
						
					<?php } ?>	
					<tr>
							<td class="right_text_align" colspan="7">
								
									<div>
										<?php echo lang("technical_supports_repair_cost").": " . to_currency($repair_cost); ?>
									</div>
								
							</td>
						</tr>
					
			</table>
			<div id='datos_extras'>
                    <?php if(!empty($accessory)){ ?>
                        <div> 
                           <?php echo"<strong>". lang('technical_supports_accessorys')."</strong>"; ?> : <?php echo $accessory; ?>
                        </div>
                     <?php } ?>
					 <?php if(isset($do_have_guarantee)){ ?>
                        <div> 
                           <?php echo"<strong>Garantía </strong>"; ?> : <?php echo $do_have_guarantee==1?"Si - hasta ".$date_garantia  :"No"?>
                        </div>
                     <?php } ?>
                     <?php if(!empty($damage_failure) && $damage_failure!="Otro"){ ?>
                        <div> 
                           <?php echo"<strong>" .lang('technical_supports_falla_tecnica')."</strong>"; ?> : <?php echo $damage_failure; ?>
                        </div>
                     <?php }
                      else{ ?>
                       <div> 
                           <?php echo"<strong>". lang('technical_supports_falla_tecnica')."</strong>"; ?> : <?php echo $damage_failure_peronalizada; ?>
                        </div>
                    <?php } ?>

					<?php if(!empty($observaciones_entrega)){ ?>
                        <div> 
                           <?php echo"<strong>Observaciones </strong>"; ?> : <?php echo $observaciones_entrega; ?>
                        </div>
                     <?php } ?>
					 <?php if($this->config->item('return_policy_support')!=FALSE){ ?>
                        <div id="sale_return_policy">
							<?php echo nl2br($this->config->item('return_policy_support')); ?>
                        </div>
                     <?php } ?>

                    
             </div>
            <div id='barcode'>
                <?php 			 		
					echo "<img src='".site_url('barcode')."?barcode=$order_support&text=". $order_support."' />"; ?>
				</div>
		</div>
	</div>

<script type="text/javascript">
function print_receipt()
 		{
           
                window.print();
           
		}
 </script>



<?php $this->load->view("partial/footer"); ?>