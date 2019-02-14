
	<div class="row">
		<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet light register-items margin-bottom-15">		
				<div id="ajax-loader" style="display: none;">
					<?php echo lang('common_wait')." ".img(array('src' => base_url().'/img/ajax-loader.gif')); ?>
				</div>		
				<div class="portlet-body">									
					<div class="row">
						<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">	
							<?php echo form_open("receivings/add",array('id'=>'add_item_form', 'autocomplete'=> 'off')); ?>
								<div class="input-group">
									<span class="input-group-btn">
										<?php echo anchor("items/view/-1/1/receiving",
											'<i class="icon-note hidden-lg"></i><span class="visible-lg">'.lang('sales_new_item').'</span>',
											array('class'=>'btn btn-success tooltips','data-original-title'=>lang('sales_new_item')));
										?>	
									</span>

									<?php echo form_input(array('name'=>'item','id'=>'item', 'class'=>'form-control form-inps', 'size'=>'30', 'accesskey' => 'k', 'placeholder' => lang('sales_start_typing_item_name')));?>

									<span class="input-group-btn">											
										<a href="javascript:void(0);" class="btn btn-success show-grid">
											<i class="icon-magnifier-add hidden-lg"></i>
											<span class="visible-lg"><?php echo lang('sales_show_grid');?></span>												
										</a>
										<a href="javascript:void(0);" class="btn btn-success hide-grid hidden">
											<i class="icon-magnifier-remove hidden-lg"></i>
											<span class="visible-lg"><?php echo lang('sales_hide_grid');?></span>												
										</a>	
									</span>
								</div>
							</form>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
							<?php echo form_open("receivings/change_mode",array('id'=>'mode_form', 'autocomplete'=> 'off')); ?>
								<?php echo form_dropdown('mode',$modes,$mode, "id='mode' class='bs-select form-control'"); ?>
							</form>
						</div>
					</div>
				</div>
			</div> 

			<div class="portlet light register-items-table margin-bottom-15">

				<div class="table-toolbar">
					<div class="row">						
						<div class="col-md-12">
							<div class="btn-group pull-right">
								<button class="btn btn-success dropdown-toggle tooltips" data-original-title="<?php echo lang('receivings_more_options_help')?>" data-toggle="dropdown">Más opciones<i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<li>
										<?php echo anchor("receivings/suspended/",
											lang('receivings_suspended_receivings'),
											array('class'=>'','title'=>lang('receivings_suspended_receivings')));
										?>
									</li>
									<li>
										<?php echo anchor("receivings/batch_receiving/",
											lang('batch_receivings'),
											array('class'=>'','title'=>lang('batch_receivings_title')));
										?>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<?php // cundo se va a realizar una venta
				 if ($mode != 'store_account_payment')
				{ ?>
				<div class="register-items-holder">
					<div class="table-scrollable">
						<table id="register" class="table table-advance table-bordered table-custom">
							<thead>
								<tr>
									<th id="reg_item_del"></th>
									
									<th id="reg_item_name"><?php echo lang('receivings_item_name'); ?></th>
										<?php if ($subcategory_of_items==true): ?>
											<?php if ($this->config->item("inhabilitar_subcategory1")==0): ?>
												<th ><?php echo $this->config->item("custom_subcategory1_name");?></th>
											<?php endif; ?>
											<th ><?php echo $this->config->item("custom_subcategory2_name");?></th>
											<th ><?php  echo lang('receivings_quantity')." subcategoría"; ?></th>

										<?php endif; ?>
									<?php if ($show_receivings_num_item) { ?>
										<th id="reg_item_number">
											<?php switch($this->config->item('id_to_show_on_sale_interface'))
											{
												case 'number':
												echo lang('sales_item_number'); 
												break;
												
												case 'product_id':
												echo lang('items_product_id'); 
												break;
												
												case 'id':
												echo lang('items_item_id'); 
												break;
												
												default:
												echo lang('sales_item_number'); 
												break;
											}?>
										</th>
									<?php } ?>
									<?php if ($show_receivings_cost_without_tax){ ?>
										<th id="reg_item_without_tax"><?php echo lang('receivings_cost_without_tax'); ?></th>
									<?php } ?>
									<?php if($show_receivings_cost_iva){ ?>
										<th id="reg_item_tax"><?php echo lang('receivings_item_tax'); ?></th>
									<?php } ?>
									<th id="reg_item_price"><?php echo lang('receivings_cost'); ?></th>
									<?php if ($this->config->item('calculate_average_cost_price_from_receivings')){ ?>
										<th id="reg_cost_price_preview">
											<?php echo lang('receivings_cost_price_preview'); ?>
										</th>
									<?php } ?>
									
									<th id="reg_item_qty"><?php echo lang('receivings_quantity'); ?></th>
									<?php if ($show_receivings_inventory) { ?>										
										<th class="sales_stock"><?php echo lang('sales_stock'); ?></th>		
									<?php } ?>
									<?php if ($show_receivings_price_sales) { ?>																									
										<th id="reg_item_price_sales"><?php echo 'Precio de Venta'; ?></th>
									<?php } ?>
									<?php if ($show_receivings_discount) { ?>																									
										<th id="reg_item_discount"><?php echo lang('receivings_discount'); ?></th>
									<?php } ?>
									<?php if ($show_receivings_cost_transport) { ?>	
										<th id="reg_item_cost_transport"><?php echo lang('receivings_cost_transport'); ?></th>
									<?php } ?>
									<th id="reg_item_total"><?php echo lang('receivings_total'); ?></th>
								</tr>
							</thead>
							<tbody id="cart_contents" class="register-item-content">
								<?php if(count($cart)==0) { ?>
									<tr class="cart_content_area">
										<td colspan='10' style="height:60px;border:none;">
											<div  class='text-center text-warning'>
												<h3><?php echo lang('sales_no_items_in_cart'); ?></h3>
											</div>
										</td>
									</tr>
								<?php
								}
								else
								{
									
									foreach(array_reverse($cart, true) as $line=>$item)
									{
										$cur_item_info = $this->Item->get_info($item['item_id']);
										$cur_item_location_info = isset($item['item_id']) ? $this->Item_location->get_info($item['item_id']) : $this->Item_kit_location->get_info($item['item_kit_id']);?>												 
												
										<tr id="reg_item_top" class="register-item-details">
											<td id="reg_item_del" class="text-center"><?php //echo anchor("receivings/delete_item/$line",lang('common_delete'), array('class' => 'delete_item'));?>
												<?php echo anchor("receivings/delete_item/$line",'<i class="fa fa-trash-o fa fa-2x font-red"></i>', array('class' => 'delete_item'));?>
											</td>
											<td id="reg_item_name">
												<?php echo H($item['name']); ?>
											</td>
											<?php if ($subcategory_of_items==true): 
													$subcategory_item = (isset($item['item_id'])&& $item['has_subcategory']==1)
												?>
													<?php if ($this->config->item("inhabilitar_subcategory1")==0): ?>
														<td width="3%" class="text text-center">
																<?php

																if ($subcategory_item ==true/*isset($item['item_id'])&& $this->Item->get_info($item['item_id'])->subcategory*/) {
																	 echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																	 $customs1=$this->items_subcategory-> get_custom1($item['item_id'], false);
																	 $data_custom1_subcategory=array(""=>"---------");
																	 foreach($customs1 as $custom1){
																		$data_custom1_subcategory[$custom1->custom1]=($custom1->custom1);
																	 }
																	 if (!isset($data_custom1_subcategory[$item['custom1_subcategory']])) {
																		$data_custom1_subcategory[$item['custom1_subcategory']]=$item['custom1_subcategory']."(No disponible)";
																	 }
																	 echo form_dropdown('custom1_subcategory',$data_custom1_subcategory,$item['custom1_subcategory'],'style="width: 90px;" class=" form-control select_custom_subcategory"');
																	 echo form_close();
																 }
															?>
														</td>
													<?php endif;?>
														<td  width="3%"class="text text-success">
															<?php
																if ($subcategory_item ==true) {
																	echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																	$customs2=$this->items_subcategory-> get_custom2($item['item_id'], false,$item['custom1_subcategory']);
															 		$data_custom2_subcategory=array(""=>"---------");
																    foreach($customs2 as $custom2){
																		$data_custom2_subcategory[$custom2->custom2]=$custom2->custom2." (".(double)$custom2->quantity.")";
																	 }
																	 if (!isset($data_custom2_subcategory[$item['custom2_subcategory']])) {
																		$data_custom2_subcategory[$item['custom2_subcategory']]=$item['custom2_subcategory']."(No disponible)";
																	 }
																	echo form_dropdown('custom2_subcategory',$data_custom2_subcategory,$item['custom2_subcategory'],' style="width: 90px;" class=" form-control select_custom_subcategory"');
																	echo form_close();
																}
															?>
														</td>
														<td id="reg_item_qty">
														<?php
															if ($subcategory_item ==true) {?>
																<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																	echo form_input(array('name'=>'quantity_subcategory','value'=>to_quantity($item['quantity_subcategory']),'class'=>'form-control form-inps input-small quantity', 'id' => 'quantity_subcategory'.$line));?>
														
																	<?php echo form_hidden('id',$line); ?>
																</form>
								<?php } ?>
								</td>
								<?php endif; ?>
								<?php if ($show_receivings_num_item) { ?>
												<td class="text-center text-info sales_item" id="reg_item_number">
													<?php switch($this->config->item('id_to_show_on_sale_interface'))
													{
														case 'number':
														echo array_key_exists('item_number', $item) ? H($item['item_number']) : "Hola1"; 
														break;
												
														case 'product_id':
														echo array_key_exists('product_id', $item) ? H($item['product_id']) : "Hola2"; 
														break;
												
														case 'id':
														echo array_key_exists('item_id', $item) ? H($item['item_id']) : 'KIT '.H($item['item_kit_id']); 
														break;
														
														default:
														echo array_key_exists('item_number', $item) ? H($item['item_number']) : "Hola3"; 
														break;
													}?>																					
												</td>
											<?php } ?>

											<?php if ($show_receivings_cost_without_tax){ ?>
												<?php if ($items_module_allowed) { ?>											
													<td id="reg_item_price">
														<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));															   
															echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 2),'class'=>'form-control form-inps input-small', 'id' => 'price_'.$line));?>
														</form>
													</td>
												<?php }
												else{ ?>
													<td id="reg_item_price">
														<?php echo $item['price']; ?>
														<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
															echo form_hidden('price',$item['price']); ?>
														</form>
													</td>											
												<?php }	
											} ?>

											<?php
												$tax_info = isset($item['item_id']) ? $this->Item_taxes_finder->get_info($item['item_id']) : $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
												$i=0;
												foreach($tax_info as $key=>$tax)
												{													
													if ($tax['cumulative'])
													{
														$prev_tax = ($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
														$prev_tax = (($price_to_use*$item['quantity']-$price_to_use*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);
													}
													else
													{
														$prev_tax[$item['item_id']][$i]=$tax['percent']/100;
														$i++;
													}												
												}	
														
												if(isset($prev_tax)){
													$sum_tax=array_sum($prev_tax[$item['item_id']]);
													$value_tax=$item['price']*$sum_tax;
												}
												else
												{
													$value_tax=0;
												}
												if (isset($type_supplier)) 
												{
													$cost_with_tax=$item['price']+$value_tax;
												}
												else
												{
													$cost_with_tax=$item['price'];																			
												}
											?>
											
											<?php if (isset($type_supplier)) {
												if($show_receivings_cost_iva) { ?>
													<td id="reg_item_price_without_tax" class="text-center">
														<?php echo to_currency($value_tax, 2);?>		
												    </td>
												<?php } ?>
											    <td id="reg_item_tax" class="text-center">
											    	<?php echo to_currency($cost_with_tax, 2); ?>
											    </td>
										    <?php } 
										    else{
										    	if($show_receivings_cost_iva) { ?>
										    	   	<td id="reg_item_price_without_tax" class="text-center">
														<?php echo to_currency(0, 2); ?>
												    </td>
												<?php } ?>
											    <td id="reg_item_tax" class="text-center">
											    	<?php echo to_currency($cost_with_tax, 2); ?>
											    </td>									
										    <?php } ?>											
										
											<?php if ($this->config->item('calculate_average_cost_price_from_receivings')) { ?>
												<td id="reg_cost_price_preview" class="text-center">
													<?php echo $item['cost_price_preview']; ?>
												</td>
											<?php } ?>
																							
											<td id="reg_item_qty">
												<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
													echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'class'=>'form-control form-inps input-small quantity', 'id' => 'quantity_'.$line));?>
													
													<?php echo form_hidden('id',$line); ?>
												</form>
											</td>
											<?php if ($show_receivings_inventory) { ?>											
												<td class="text text-warning sales_stock text-center" id="reg_item_stock" >
													<?php echo property_exists($cur_item_location_info, 'quantity') ? to_quantity($cur_item_location_info->quantity) : ''; ?>
												</td>
											<?php } ?>
											<?php if ($show_receivings_price_sales) { ?>
												<td id="reg_item_sales">
													<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_input(array('name'=>'unit_price','value'=>to_currency_no_money($item['unit_price'],2),'class'=>'form-control form-inps input-small', 'id' => 'unit_price'.$line));?>
														<?php echo form_hidden('id',$line); ?>
													</form>	
												</td>
											<?php } ?>
											<?php if ($show_receivings_discount) { ?>
												<td id="reg_item_discount">
													<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_input(array('name'=>'discount','value'=> to_currency_no_money($item['discount'],2),'class'=>'form-control form-inps input-small', 'id' => 'discount_'.$line));?>
													</form>	
												</td>
											<?php } ?>
											
											<?php if ($show_receivings_cost_transport) { ?>											
												<td id="reg_item_cost_transport">
													<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));															   
														echo form_input(array('name'=>'cost_transport','value'=>to_currency_no_money($item['cost_transport'], 2),'class'=>'form-control form-inps input-small', 'id' => 'cost_transport_'.$line));?>
													</form>
												</td>
											<?php } ?>
											<td id="reg_item_total">
												<?php echo to_currency($cost_with_tax*$item['quantity']-$cost_with_tax*$item['quantity']*$item['discount']/100+ $item['cost_transport'], 2); ?>
											</td>
										</tr>
										<?php if ($show_receivings_description) { ?>										
											<tr id="reg_item_bottom" class="register-item-bottom">
												<td id="reg_item_descrip_label">
													<?php echo lang('sales_description_abbrv').':';?>
												</td>
												<td id="reg_item_descrip" colspan="10">
													<?php echo H($item['description']);											 
													echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														echo form_hidden('description',$item['description']); ?>		
													</form>	
												</td>									 
											</tr>
										<?php } ?>
					
									<?php }
								} ?>
							</tbody>
						</table>
					</div>
				</div>
				
				
				<?php }
				// para los pagos
				else
				{ /*Store Account Mode*/ ?>
					<div class="margin-top-15">
						<table id="register"  class="tablesorter table table-bordered">
							<thead>
								<tr>
									<th ><?php echo lang('receivings_item_name'); ?></th>
									<th ><?php echo lang('receivings_payment_amount'); ?></th>
								</tr>
							</thead>
							<tbody id="cart_contents">
								<?php foreach(array_reverse($cart, true) as $line=>$item)
								{
									$cur_item_location_info = isset($item['item_id']) ? $this->Item_location->get_info($item['item_id']) : $this->Item_kit_location->get_info($item['item_kit_id']);
									?>
									<tr id="reg_item_top" bgcolor="#eeeeee" >
										<td class="text text-success">

											<select id="id_credito"  name="id_credito" class="
											bs-select form-control" >
											<option value="1"><?php echo"Pago personalizado"  ?></option>
											<option value="2"><?php echo"Pagar Todo"  ?></option>

											</select>



										</td>
										<td>
											<?php echo form_open("receivings/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));

												echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'form-control form-inps-sale input-small', 'id' => 'price_'.$line));
												 
												echo form_hidden('quantity',to_quantity($item['quantity']));
												echo form_hidden('description','');
												echo form_hidden('serialnumber', '');
											echo form_close(); ?>
										</td>
									</tr>
								<?php } /*Foreach*/?>
							</tbody>
						</table>
					</div>
				<?php } ?>
				
			</div>
			
	<?php if (isset($supplier)) { ?>
		<div class="portlet light register-items-table margin-bottom-15">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-info-circle"></i>
					<span class="caption-subject bold">
					<?php echo lang('receivings_recent_receivings').' '.H($supplier);?>
					</span>
				
						<a href="<?php echo site_url('receivings/receiving_details_modal')."/". (isset($supplier_id) ? $supplier_id: 0 ); ?>" data-toggle="modal" data-target="#myModal" > - Ver pagos de Cuentas por Pagar</a>
					
				</div>
			</div>
			<div class="table-responsive">
				<table id="recent_receivings" class="table table-advance">
					<thead>
						<tr>
							<th align="center"><?php echo lang('items_date');?></th>
							<th align="center"><?php echo lang('reports_payments');?></th>
							<th align="center"><?php echo lang('reports_items_purchased');?></th>
							<th align="center"><?php echo lang('receivings_receipt');?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($recent_receivings as $receiving) {
								?>
							<tr>
								<td align="center"><?php echo date(get_date_format().' @ '.get_time_format(), strtotime($receiving['receiving_time']));?></td>
								<td align="center">
									<?php echo $receiving['payment_type'];?>
									</td>
								<td align="center"><?php echo to_quantity($receiving['items_purchased']);?></td>
								<td align="center"><?php echo anchor('receivings/receipt/'.$receiving['receiving_id'], lang('receivings_receipt'), array('target' =>'_blank')); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>			
			
		</div>
		

	
		<!-- BEGIN RIGHT SMALL BOX  -->
		<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 no-padding-left sale_register_rightbox" id="box-customers">
			<!-- BEGIN SMALL BOX SUPPLIERS -->
			<div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
				<?php if(count($cart) > 0){ ?>
					<div class="sale-buttons">
						<!-- Cancel and suspend buttons -->
						<?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form', 'autocomplete'=> 'off')); ?>
							<div class="btn-group btn-group-solid btn-group-justified ">
								<a type="button" class="btn yellow-gold letter-space" id="suspend_recv_button">
									<?php echo lang('receivings_suspend_recv');?>
								</a>
								<a type="button" class="btn btn-danger letter-space" id="cancel_sale_button">
									<?php echo lang('receivings_cancel_receiving');?>
								</a>
							</div>
						</form>
					</div>
				<?php } ?>

				<?php if($mode=="transfer") { ?>
					<!-- Location info starts here-->
					<div class="portlet-title padding">
						<div class="caption">
							<i class="fa fa-home"></i>
							<span class="caption-subject bold">
								<?php if(isset($location)) { 
									echo "Tienda agregada"; 
								} 
								else 
								{  
									echo lang('receivings_select_location'); 
								} ?>
							</span>
						</div>				
					</div>

					<div class="portlet-body padding">						
						<?php if(isset($location)) { ?>							
							<?php echo '<div class="name-receivings" id="location_name">'.character_limiter($location, 25).'</div>'; ?>
							<div class="customer-buttons">
								<div class="btn-group btn-group-justified">
									<?php 
									echo anchor("locations/view/$location_id/1", lang('common_edit'),  array('class'=>'btn btn-success','title'=>lang('suppliers_update')));
									echo anchor("receivings/delete_location", lang('sales_detach'),array('id' => 'delete_location','class'=>'btn btn-danger')); ?>
								</div>
							</div>							
						<?php }
						else
						{ ?>
							<div class="customer-search">
								<div class="input-group">
									<span class="input-group-btn">
										<?php echo anchor("locations/view/-1",
											'<i class="icon-home hidden-lg"></i><span class="visible-lg">'.lang('receivings_new_location').'</span>',
											array('class'=>'btn btn-success tooltips', 'data-original-title'=>lang('receivings_new_location'), 'title'=>lang('receivings_new_location')));
										?>
									</span>
									<?php echo form_open("receivings/select_location",array('id'=>'select_location_form', 'autocomplete'=> 'off')); ?>
										<?php echo form_input(array('name'=>'location','id'=>'location', 'class'=>'form-control form-inps', 'size'=>'30','value'=>lang('receivings_start_typing_location_name')));?>
									</form>
								</div>
							</div>								
						<?php } ?>
					</div>
				<?php } 
				else { ?>
					<!-- Supplier info starts here-->
					<div class="portlet-title padding">
						<div class="caption">
							<i class="fa fa-group"></i>
							<span class="caption-subject bold">
								<?php if(isset($supplier)) { 
									echo lang('receivings_supplier_was_added');
								} 
								else 
								{  
									echo lang('receivings_select_supplier'); 
								} ?>
							</span>
						</div>				
					</div>

					<div class="portlet-body padding">
						<?php if(isset($supplier)) { ?>
						<div class="supplier-box">
							<?php echo '<div class="name_receiving" id="supplier_name"><h4 class="text-info text-capitalize bold">'.character_limiter(H($supplier), 50).'</h4></div>'; ?>
							<span class="credit_limit_warning">(<?php echo lang('suppliers_balance').': '.to_currency($supplier_balance); ?>)</span>
							<div class="supplier-buttons">
								<div class="btn-group btn-group-justified">
									<?php 
									echo anchor("suppliers/view/$supplier_id/", lang('common_edit'),  array('class'=>'btn btn-success','title'=>lang('suppliers_update')));
									echo anchor("receivings/delete_supplier", lang('sales_detach'),array('id' => 'delete_supplier','class'=>'btn btn-danger')); ?>
								</div>
							</div>
							</div>
						<?php }
						else
						{ ?>
							<div class="customer-search">
								<div class="input-group">
									<span class="input-group-btn">
										<?php echo anchor("suppliers/view/-1/1",
											'<i class="icon-users hidden-lg"></i><span class="visible-lg">'.lang('receivings_new_supplier').'</span>',
											array('id' => 'new-supplier', 'class'=>'btn btn-success tooltips', 'data-original-title'=>lang('receivings_new_supplier'), 'title'=>lang('receivings_new_supplier')));
										?>	
									</span>
									<?php echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form', 'autocomplete'=> 'off')); ?>
										<?php echo form_input(array('name'=>'supplier','id'=>'supplier', 'class'=>'form-control form-inps', 'size'=>'30','value'=>lang('receivings_start_typing_supplier_name')));?>
									</form>									
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<!-- END SMALL BOX CUSTOMERS -->

			<!-- BEGIN SMALL BOX RECEIVINGS SUMMARY-->
			<div class="portlet light margin-top-15 no-padding">
				<div class="portlet-title padding">
					<div class="caption">
						<i class="icon-basket-loaded"></i>
						<span class="caption-subject bold">
							<?php echo lang('receivings_summary'); ?>
						</span>
					</div>				
				</div>
				<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
					<?php if($mode=="transfer") { ?>
						<ul class="list-group">
							<li class="list-group-item">
								<span class="name-item-summary-total font-green-jungle">
									<?php echo lang('sales_items_in_cart'); ?>:
								</span>
								<span class="name-item-summary-total font-green-jungle pull-right">
									<?php echo $items_in_cart; ?>
								</span>
							</li>
						</ul>
						<?php if(count($cart) > 0){ ?>
							<div class="comment-sale">
								<ul class="list-group">
									<li class="list-group-item no-border-bottom tier-style">
										<label id="comment_label" for="comment"><?php echo lang('common_comments'); ?>:</label>
										<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'class'=>'form-control form-textarea', 'value'=>$comment,'rows'=>'4'));?>
									</li>
								</ul>
							</div>
							<div class="finish-receivings">
								<?php if ($mode!= 'transfer' || ($mode=='transfer' && isset($location_id))) { ?>
									<?php echo "<div class='btn btn-success btn-block'  id='finish_sale_button' >".lang('receivings_complete_receiving')."</div>"; ?>
								<?php } ?>	
							</div>						
						<?php } ?>
					<?php } 
					// Mode receive and return 
					else { ?>
						<li class="list-group-item">
							<span class="name-item-summary">
								<?php echo lang('sales_sub_total'); ?>:
							</span>
							<span class="badge badge-cart bg-yellow badge-roundless">
								<?php echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ; ?>
							</span>
						</li>
						<li class="list-group-item">
							<span class="name-item-summary">
								<?php echo lang('receivings_total_cost_transport'); ?>:
							</span>
							<span class="badge badge-cart bg-yellow badge-roundless">
								<?php echo $this->config->item('round_value')==1 ? to_currency(round($total_cost_transport)) :to_currency($total_cost_transport) ; ?>
							</span>
						</li>
						<ul class="list-group">
							<?php 
							if(isset($taxes))
							{
								foreach($taxes as $name=>$value) {
								if(!($value=='0.00')) { ?>
									<li class="list-group-item">
										<span class="name-item-summary">
											<?php if (!$is_tax_inclusive && $this->Employee->has_module_action_permission('sales', 'delete_taxes', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
												<?php //echo anchor("receivings/delete_tax/".rawurlencode($name),'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_tax'));?>
											<?php } ?>
											<?php echo $name; ?>:
										</span>
										<span class="name-item-summary pull-right">
											<?php echo $this->config->item('round_value')==1 ? to_currency(round($value)) :to_currency($value) ; ?>
										</span>
									</li>
								<?php }
								}
							}; ?>	
							<!-- // -->

							<li class="list-group-item">
								<span class="name-item-summary-total font-green-jungle">
									<?php echo lang('sales_total'); ?>:
								</span>
								<span class="name-item-summary-total font-green-jungle pull-right">
									<?php echo to_currency($total, 2); ?>
								</span>
							</li>						
						</ul>
						<?php if(count($cart) > 0){ ?>
							<!-- BEGIN ADD PAYMENT -->
							<div class="add-payment-receivings">
								<ul class="list-group">
									<li class="list-group-item no-border-bottom tier-style">	
										<div class="row">		
											<div class="col-md-6">	
												<span class="name-addpay">
													<?php echo lang('receivings_payment').':   ';?>
												</span>
											</div>
											<div class="col-md-6">
												<span class="">				
													<?php echo form_dropdown('payment_type',$payment_options,$this->config->item('default_payment_type'), 'class="bs-select form-control"');?>
												</span>
											</div>
										</div>
										<!--<div class="row margin-top-10">
											<div class="col-md-12">
												<?php echo form_input(array('name'=>'amount_tendered', 'value'=>'', 'size'=>'10', 'class'=>'form-control form-inps', 'placeholder'=> lang('common_enter_amount_tendered'))); ?>
											</div>
										</div>-->
									</li>
								</ul>
							</div>
							<div class="comment-sale">
								<ul class="list-group">
									<li class="list-group-item no-border-bottom tier-style">
										<label id="comment_label" for="comment"><?php echo lang('common_comments'); ?>:</label>
										<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'class'=>'form-control form-textarea', 'value'=>$comment,'rows'=>'4'));?>
									</li>
								</ul>
							</div>
							<div class="finish-receivings">
								<?php if ($mode!= 'transfer' || ($mode=='transfer' && isset($location_id))) { ?>
									<?php echo "<div class='btn btn-success btn-block'  id='finish_sale_button' >".lang('receivings_complete_receiving')."</div>"; ?>
								<?php } ?>	
							</div>	
						<?php } ?>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>

		
	<script type="text/javascript">
		<?php
		if(isset($error))
		{
			echo "toastr.error(".json_encode($error).", ".json_encode(lang('common_error')).");";
		}

		if (isset($warning))
		{
			echo "toastr.warning(".json_encode($warning).", ".json_encode(lang('common_warning')).");";
		}

		if (isset($success))
		{
			echo "toastr.success(".json_encode($success).", ".json_encode(lang('common_success')).");";
		}
		?>
	</script>

	<script type="text/javascript" language="javascript">

		//Table headers fixed
		var $table_receivings = $('#register');
		$table_receivings.floatThead({
			scrollContainer: function($table){
				return $table.closest('.table-scrollable');
			}
		});		

		//
		$('.list-group-item:first-child').css('border-top-left-radius', '0').css('border-top-right-radius','0');
		$('.list-group-item').css('border', '0');

		// Show or hide item grid
		$(".show-grid").on('click',function(e)
		{
			e.preventDefault();
			$("#category_item_selection_wrapper").slideDown();
			$('.show-grid').addClass('hidden');
			$('.hide-grid').removeClass('hidden');
		});

		$(".hide-grid").on('click',function(e)
		{
			e.preventDefault();
			$("#category_item_selection_wrapper").slideUp();
			$('.hide-grid').addClass('hidden');
			$('.show-grid').removeClass('hidden');
		});

		$(document).ready(function()
		{
			//Acomodar el contenedor de customers en el padding izquierdo
			$(window).bind("resize",function(){
			    console.log($(this).width())
			    if($(this).width() <992){
				    $('#box-customers').removeClass('no-padding-left')
			    }
			    else{
			    	$('#box-customers').addClass('no-padding-left')
			    }
			});

			//Solo permite numeros decimales positivos
			$(".quantity").inputmask("decimal", {
				allowPlus: false,
    			allowMinus: false,
			});

		});
		

		var submitting = false;
		$(document).ready(function()
		{		
			//Here just in case the loader doesn't go away for some reason
			$("#ajax-loader").hide();
			
			<?php if (!$this->agent->is_mobile()) { ?>
				<?php if (!$this->config->item('auto_focus_on_item_after_sale_and_receiving'))
				{
				?>
					if (last_focused_id && last_focused_id != 'item' && $('#'+last_focused_id).is('input[type=text]'))
					{
						$('#'+last_focused_id).focus();
						$('#'+last_focused_id).select();
					}
					<?php 
				}
				else
				{
				?>
					setTimeout(function(){$('#item').focus();}, 10);	
				<?php
				}
				?>
			
				$(document).focusin(function(event) 
				{
					last_focused_id = $(event.target).attr('id');
				});
			<?php } ?>

			$('#mode_form, #select_supplier_form,#select_location_form,.line_item_form').ajaxForm({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
			$('#add_item_form').ajaxForm({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});
			
			$( "#item" ).autocomplete({
				source: '<?php echo site_url("receivings/item_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{
		 			event.preventDefault();
		 			$( "#item" ).val(ui.item.value);
					$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});
				},
				change: function(event, ui)
				{
					if ($(this).attr('value') != '' && $(this).attr('value') != <?php echo json_encode(lang('sales_start_typing_item_name')); ?>)
					{
						$("#add_item_form").ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
					}
			
		    		$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_item_name')); ?>);
				}
			});
			
			$("#cart_contents input").change(function()
			{
				$(this.form).ajaxSubmit({target: "#register_container",beforeSubmit: receivingsBeforeSubmit});
			});
			
			$('#item,#supplier,#location').click(function()
		    {
		    	$(this).attr('value','');
		    });

			$('#mode').change(function()
			{
				$('#mode_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
			});
			
			$('#comment').change(function() 
			{
				$.post('<?php echo site_url("receivings/set_comment");?>', {comment: $('#comment').val()});
			});

			$( "#supplier" ).autocomplete({
				source: '<?php echo site_url("receivings/supplier_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{			
					$( "#supplier" ).val(ui.item.value);
					$('#select_supplier_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: itemScannedSuccess});		
				}
			});

			$( "#location" ).autocomplete({
				source: '<?php echo site_url("receivings/location_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{
					$( "#location" ).val(ui.item.value);
					$('#select_location_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});			
				}
			});


		    $('#supplier').blur(function()
		    {
		    	$(this).attr('value',<?php echo json_encode(lang('receivings_start_typing_supplier_name')); ?>);
		    });

		    $('#location').blur(function()
		    {
		    	$(this).attr('value',<?php echo json_encode(lang('receivings_start_typing_location_name')); ?>);
		    });


		    $("#finish_sale_form").submit(function()
			{       
				<?php if($this->config->item('in_suppliers')==1 ) {?>
	       	  		if($("#supplier").length>0){
	       	  			
	       	  			toastr.error(<?php echo json_encode(lang("receivings_in_suppliers")); ?>, <?php echo json_encode(lang('common_error')); ?>);	       				
						//alert(<?php echo json_encode(lang("receivings_in_suppliers")); ?>);
						return false;
				  	} 
				<?php } 

				if($mode=="transfer" and !isset($location)) { ?>
					alert(<?php echo json_encode(lang("receivings_location_required")); ?>);
					$('#location').focus();
					return;
				<?php } ?>
					
				if (confirm(<?php echo json_encode(lang("receivings_confirm_finish_receiving")); ?>))
	    		{
					//Prevent double submission of form
					$("#finish_sale_button").hide();
					return true;
	    		}
				else {
					return false;
				}
			});
		    $("#finish_sale_button").click(function()
		    {
				$('#finish_sale_form').submit();
			});
			$( ".select_custom_subcategory" ).change(function() {
				$($(this).parent()).submit();
			});

		    $("#cancel_sale_button").click(function()
		    {
		    	if (confirm(<?php echo json_encode(lang("receivings_confirm_cancel_receiving")); ?>))
		    	{
					$('#cancel_sale_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit});
		    	}
		    });

			$('.delete_item, #delete_supplier, #delete_location').click(function(event)
			{
				event.preventDefault();
				$("#register_container").load($(this).attr('href'));	
			});

			$("input[type=text]").click(function() {
				$(this).select();
			});
			
			$("#new-supplier").click(function()
			{
				$("body").plainOverlay('show');		
			});
			
			$("#suspend_recv_button").click(function()
			{
				if (confirm(<?php echo json_encode(lang("receivings_confim_suspend_recv")); ?>))
				{
					if ($("#comment").val())
					{
						$.post('<?php echo site_url("receivings/set_comment");?>', {comment: $('#comment').val()}, function()
						{
							doSuspendRecv();
						});						
					}
					else
					{
						doSuspendRecv();	
					}
				}
			});
		});

		function doSuspendRecv()
		{
			<?php if ($this->config->item('show_receipt_after_suspending_sale')) { ?>
				window.location = '<?php echo site_url("receivings/suspend"); ?>';
			<?php }else { ?>
				$("#register_container").load('<?php echo site_url("receivings/suspend"); ?>');
			<?php } ?>
		}

		function receivingsBeforeSubmit(formData, jqForm, options)
		{
			if (submitting)
			{
				return false;
			}
			submitting = true;
			
			$("#ajax-loader").show();
			$("#finish_sale_button").hide();
		}

		function itemScannedSuccess(responseText, statusText, xhr, $form)
		{
			setTimeout(function(){$('#item').focus();}, 10);
		}

	</script>

	<script>
		jQuery(document).ready(function() {    
		   	Metronic.init(); // init metronic core componets
		   	Layout.init(); // init layout
		   	Demo.init(); // init demo features
		 	ComponentsDropdowns.init();
		});
	</script>	