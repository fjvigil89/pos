
<?php if($this->config->item('active_keyboard')==1){ ?>
		<script type="text/javascript" src="<?php echo base_url();?>js/keyboard/keyboard_active.js"></script>
	<?php } ?>

	<?php if($this->sale_lib->get_change_sale_id() and (isset($sale_data)) ) { ?>
		<small>
			<?php echo lang('sales_editing_sale'); ?>
				<b>
					<?php
					 	if( $sale_data['is_invoice'] )
						{
							echo $this->config->item('sale_prefix').' '.$sale_data['invoice_number'];
						}
						else
						{
							echo 'Boleta '.$sale_data['ticket_number'];
						}
					?>
				</b>
		</small>
	<?php } ?>

	<div class="row">
		<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
			<div class="portlet light register-items margin-bottom-15">
				<div id="ajax-loader" style="display: none;"><?php echo lang('common_wait')." ".img(array('src' => base_url().'/img/ajax-loader.gif')); ?></div>
				<div class="portlet-body">
					<div class="row">
						<?php // para buscar los producto para las ventas
							 if ($mode != 'store_account_payment') { ?>
							<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
								<?php echo form_open("sales/add",array('id'=>'add_item_form','class'=>'', 'autocomplete'=> 'off')); ?>
									<div class="input-group">
										<span class="input-group-btn">
											<?php echo anchor("items/view/-1/1/sale",
												'<i class="icon-note hidden-lg"></i><span class="visible-lg">'.lang('sales_new_item').'</span>',
												array('class'=>'btn btn-success tooltips','data-original-title'=>lang('sales_new_item')));
											?>
										</span>

										<?php echo form_input(array('name'=>'item','id'=>'item','class'=>'form-control form-inps-sale', 'accesskey' => 'k', 'tabindex'=>'1', 'placeholder' => lang('sales_start_typing_item_name')));?>
										<input type="hidden" name="no_valida_por_id" value="<?php echo (int)$add_cart_by_id_item?>">

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
								<?php echo form_open("sales/change_mode",array('id'=>'mode_form', 'autocomplete'=> 'off')); ?>
									<?php echo form_dropdown('mode',$modes,$mode,'id="mode" class="bs-select form-control"'); ?>
								</form>
							</div>
						<?php }
						else{?>
						<div class="col-lg-12">
							<?php echo form_open("sales/change_mode",array('id'=>'mode_form', 'autocomplete'=> 'off')); ?>
								<?php echo form_dropdown('mode',$modes,$mode,'id="mode" class="bs-select form-control"'); ?>
							</form>
						</div>
						<?php } ?>
					</div>

				</div>
			</div>

			<div class="portlet light register-items-table margin-bottom-15">

				<div class="table-toolbar">
					<div class="row">
						<div class="col-lg-9 col-md-12">
							<?php if(count($cart) > 0){ ?>
								<div class="sale-buttons">
									<!-- Cancel and suspend buttons -->
									<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form', 'autocomplete'=> 'off')); ?>
										<div class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
											<?php if ($mode != 'store_account_payment') { ?>
												<a type="button" class="btn yellow-gold letter-space" id="quotes"><?php echo lang('sales_quotes_create');?></a>
												<a type="button" class="btn yellow-gold letter-space" id="suspend_sale_button"><?php echo lang('sales_suspend_sale');?></a>
												<a type="button" class="btn yellow-gold letter-space" id="layaway_sale_button" style="display: none;"><?php echo lang('sales_layaway');?></a>
												<a type="button" class="btn yellow-gold letter-space" id="estimate_sale_button" style="display: none;"><?php echo lang('sales_estimate');?></a>
												<?php if($cancelar_despues_desuspender):?>
													<a type="button" class="btn red-thunderbird" id="cancel_sale_button"><?php echo lang('sales_cancel_sale');?></a>
												<?php endif; ?>
											<?php } ?>
										</div>
									</form>
								</div>
							<?php } ?>
						</div>
						<div class="col-lg-2 col-md-12 pull-right">
							<div class="btn-group pull-right">
								<?php
									if($this->appconfig->get('enabled_for_Restaurant') == '1'){

										$cantmensa = $this->appconfig->get('table_acount');
										$data_mesa = array(lang("sale_select_table"));
										for ($i = 1; $i <= $cantmensa; $i++) {
											array_push($data_mesa, lang("ntable")." #" . $i);
										}
										echo form_dropdown('table_number',$data_mesa,0,'id="table_number" class="bs-select form-control"');
									}
								?>
								<button  style="width: 100%" class="btn btn-sm btn-success dropdown-toggle tooltips " data-original-title="<?php echo lang('more_options_help') ?>" data-toggle="dropdown"><?php echo lang("sale_more_options"); ?><i class="fa fa-angle-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php if ($this->config->item('track_cash')) { ?>
										<li>
											<?php echo anchor(site_url('sales/closeregister?continue=home'),
												lang('sales_close_register'),
												array('class'=>''));
											?>
										</li>
									<?php } ?>
									<?php if ($this->Appconfig->is_offline_sales()) { ?>
										<li>
											<?php echo anchor(site_url('sales/offline'),
												lang("sale_go_to_offline_sales"),
												array('class'=>''));
											?>
										</li>
									<?php } ?>
									<li>
										<?php echo anchor(site_url('sales/sale_seriales_modal'),
											lang("sale_search_by_serial"),
											array('class'=>'', "id"=>"modal-serial", 'data-toggle'=>"modal",
											'data-target'=>'#myModal'));
										?>
									</li>
									<?php if($this->config->item("monitor_product_rank")==1): ?>
										<li>
											<?php echo anchor(site_url('sales/modal_range'),
												lang("sales_range"),
												array('class'=>'', "id"=>"modal_range", 'data-toggle'=>"modal",
												'data-target'=>'#myModal'));
											?>
										</li>
										<li>
											<?php echo anchor(site_url('sales/add_balance'),
												lang("sales_add_balance"),
												array('class'=>'', "id"=>"modal_range", 'data-toggle'=>"modal",
												'data-target'=>'#myModal'));
											?>
										</li>
									<?php endif; ?>
									
									
									<?php if ($this->Register->count_all($this->Employee->get_logged_in_employee_current_location_id()) > 1) {?>
										<li>
											<?php echo anchor(site_url('sales/clear_register'),
												lang('sales_change_register'),
												array('class'=>''));
											?>
										</li>
									<?php } ?>
									<?php if ($mode != 'store_account_payment') { ?>
										<li>
											<?php echo anchor("sales/suspended/",
												lang('sales_suspended_sales'),
												array('class'=>'','title'=>lang('sales_suspended_sales')));
											?>
										</li>
										<li>
											<?php echo anchor("sales/quoteses/",
												lang('sales_quotes'),
												array('class'=>'','title'=>lang('sales_quotes')));
											?>
										</li>
										<?php if ($this->Employee->has_module_action_permission('giftcards', 'add_update', $logged_in_employee_id)) {?>
											<li>
												<?php echo anchor("sales/new_giftcard",
													lang('sales_new_giftcard'),
													array('title'=>lang('sales_new_giftcard')));
												?>
											</li>
										<?php } ?>
										<?php
										if ($this->Employee->has_module_action_permission('reports', 'view_sales_generator', $logged_in_employee_id)) { ?>
											<li>
												<?php echo anchor("reports/sales_generator",
													lang('sales_search_reports'),
													array('title'=>lang('sales_search_reports')));
												?>
											</li>
										<?php } ?>
									<?php } ?>
									<li>
										<?php echo anchor("sales/batch_sale/",
											lang('batch_sale'),
											array('class'=>'','title'=>lang('batch_sale')));
										?>
									</li>
									<?php if ($this->Employee->has_module_action_permission('reports','view_sales', $logged_in_employee_id) ) { ?>
										<li>
											<?php echo anchor("reports/detailed_sales/".date('Y-m-d').' 00:00:00'. '/' .date('Y-m-d').' 23:59:59/all/0/0/-1',
												lang('sale_sales_day'),
												array('class'=>'','target'=>'_blank','title'=>'Ventas realizadas hoy'));
											?>
										</li>
									<?php } ?>

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
										<th ></th>

										<th class="item_name_heading" ><?php echo lang('sales_item_name'); ?></th>
										<?php if ($subcategory_of_items==true): ?>
											<?php if ($this->config->item("inhabilitar_subcategory1")==0): ?>
												<th ><?php echo $this->config->item("custom_subcategory1_name");?></th>
											<?php endif; ?>
											<th ><?php echo $this->config->item("custom_subcategory2_name");?></th>
										<?php endif; ?>
										

										<?php if($show_sales_num_item){?>
											<th class="sales_item sales_items_number">

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
										<?php }

										if ($show_sales_inventory) { ?>
											<th class="sales_stock"><?php echo lang('sales_stock'); ?></th>
										<?php }

										$name=0;
										foreach(array_reverse($cart, true) as $line=>$item)
										{
											$name=$item['name'];
										}?>
										<?php if ($name==lang('sales_giftcard')){
											if($show_sales_price_without_tax){?>
												<th class="sales_price"><?php echo lang('sales_price_iva'); ?></th>
											<?php }
											if($show_sales_price_iva){?>
												<th id="reg_item_tax"><?php echo lang('receivings_item_tax'); ?></th>
											<?php }
										}else{
											if($show_sales_price_without_tax){?>
												<th class="sales_price_without_tax"><?php echo lang('sales_price_without_tax'); ?></th>
											<?php }
											if($show_sales_price_iva){?>
												<th id="sales_price_tax"><?php echo lang('sales_price_tax'); ?></th>
											<?php }
										}?>
										<th class="sales_price"><?php echo lang('sales_price'); ?></th>
										<?php if ($edit_tiers) { ?>
											<th class="sales_edit_tiers"><?php echo lang('sales_tier'); ?></th>
										<?php } ?>
										
										<th class="sales_quality"><?php echo lang('sales_quantity'); ?></th>
										<?php if ($show_sales_discount) { ?>
											<th class="sales_discount"><?php echo lang('sales_discount'); ?></th>
										<?php } ?>
										
										<th class="sales_total"><?php echo lang('sales_total'); ?></th>
									</tr>
								</thead>
								<tbody id="cart_contents" class="register-item-content">
									<?php
									 if(count($cart)==0) { ?>
										<tr class="cart_content_area">
											<td colspan='10'>
												<div class='text-center text-danger' >
													<h3><?php echo lang('sales_no_items_in_cart'); ?>
												</div>
											</td>
										</tr>
									<?php
									}
									else
									{
										foreach(array_reverse($cart, true) as $line=>$item)
										{
											
											$cur_item_location_info = isset($item['item_id']) ? $this->Item_location->get_info($item['item_id']) : $this->Item_kit_location->get_info($item['item_kit_id']);
											$item_info = isset($item['item_id']) ? $this->Item->get_info($item['item_id']) : $this->Item_kit->get_info($item['item_kit_id']);
											$itemId = isset($item_info->item_id) ? $item_info->item_id : $item_info->item_kit_id;

											?>
											<tr id="reg_item_top" class="register-item-details">
												<td class="text-center">
													<?php echo anchor("sales/delete_item/$line",'<i class="fa fa-trash-o fa fa-2x font-red"></i>', array('class' => 'delete_item'));?>
												</td>
												<td  class="text text-success">
													<a href="<?php echo isset($item['item_id']) ? site_url('home/view_item_modal/'.$item['item_id']) : site_url('home/view_item_kit_modal/'.$item['item_kit_id']) ; ?>" data-toggle="modal" data-target="#myModal" >
														<?php echo H($item['name']); ?>
													</a>
												</td>

												<?php if ($subcategory_of_items==true): 
													$subcategory_item = (isset($item['item_id'])&& $item['has_subcategory']==1)
												?>
													<?php if ($this->config->item("inhabilitar_subcategory1")==0): ?>
														<td width="3%" class="text text-center">
																<?php

																if ($subcategory_item ==true/*isset($item['item_id'])&& $this->Item->get_info($item['item_id'])->subcategory*/) {
																	 echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
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
																	echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
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
												<?php endif; ?>
												<?php if($show_sales_num_item){?>
													<td class="text-center text-info sales_item" id="reg_item_number">
														<?php switch($this->config->item('id_to_show_on_sale_interface'))
														{
															case 'number':
															echo array_key_exists('item_number', $item) ? H($item['item_number']) : H($item['item_kit_number']);
															break;

															case 'product_id':
															echo array_key_exists('product_id', $item) ? H($item['product_id']) : lang('items_none');
															break;

															case 'id':
															echo array_key_exists('item_id', $item) ? H($item['item_id']) : 'KIT '.H($item['item_kit_id']);
															break;

															default:
															echo array_key_exists('item_number', $item) ? H($item['item_number']) : H($item['item_kit_number']);
															break;
														}?>
													</td>
												<?php }

												if ($show_sales_inventory) { ?>
													<td class="text-center font-yellow-gold sales_stock" id="reg_item_stock" >
														<?php echo property_exists($cur_item_location_info, 'quantity') ? to_quantity($cur_item_location_info->quantity) : ''; ?>
													</td>
												<?php }

												if ($item_info->tax_included)
												{
													if (isset($item['item_id']))
													{
														$tax_info = isset($item['item_id']) ? $this->Item_taxes_finder->get_info($item['item_id']) : $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
														$i=0;

														foreach($tax_info as $key=>$tax)
														{
														   	$prev_tax[$item['item_id']][$i]=$tax['percent']/100;
															$i++;
														}
														if (isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
														{
															
															if(!$overwrite_tax){
																$sum_tax=array_sum($prev_tax[$item['item_id']]);
																$price_without_tax=$item['price']/(1+$sum_tax);
																$value_tax=$price_without_tax*$sum_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}

															if($show_sales_price_without_tax){ ?>
																<td class="text-center">
																	<?php echo to_currency($price_without_tax, 2);?>
														    	</td>
														    <?php }
														    if($show_sales_price_iva){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($value_tax, 2) ?>
															    </td>
															<?php }
														}
														elseif (!isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
														{
															if(!$overwrite_tax){
																$sum_tax=0;
																$price_without_tax=$item['price']/(1+$sum_tax);
																$value_tax=$price_without_tax*$sum_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}
															if($show_sales_price_without_tax){ ?>
																<td class="text-center">
																	<?php echo to_currency($price_without_tax, 2);?>
														    	</td>
														    <?php }
														    if($show_sales_price_iva){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($value_tax, 2) ?>
															    </td>
															<?php }
														}
														else{ ?>
															<td></td>
															<td></td>
														<?php }
													}
													if (isset($item['item_kit_id']))
													{
														$tax_kit_info = isset($item['item_kit_id']) ? $this->Item_kit_taxes_finder->get_info($item['item_kit_id']) : $this->Item_kit_taxes_finder->get_info($item['item_id']);
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
																$price_without_tax=$item['price']/(1+$sum_tax);
																$value_tax=$price_without_tax*$sum_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}

															if($show_sales_price_without_tax){ ?>
																<td class="text-center">
																	<?php echo to_currency($price_without_tax, 2);?>
														    	</td>
															<?php }
														    if($show_sales_price_iva){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($value_tax, 2) ?>
															    </td>
															<?php }
														}
														elseif (!isset($prev_tax[$item['item_kit_id']]) && $item['name']!=lang('sales_giftcard'))
														{
															
															if(!$overwrite_tax){
																$sum_tax=0;
																$price_without_tax=$item['price']/(1+$sum_tax);
																$value_tax=$price_without_tax*$sum_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}

															if($show_sales_price_without_tax){ ?>
																<td class="text-center">
																	<?php echo to_currency($price_without_tax, 2);?>
														    	</td>
															<?php }
														    if($show_sales_price_iva){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($value_tax, 2) ?>
															    </td>
															<?php }
														}
														else{ ?>
															<td></td>
															<td></td>
														<?php }
													}

													if ($this->Employee->has_module_action_permission('sales', 'edit_sale_price', $logged_in_employee_id))
													{ ?>
														<td class="text-center">
															<?php
												                echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
												                echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'form-control form-inps-sale input-small text-center', 'id' => 'price_'.$line));
												            ?>
															</form>
														</td>
													<?php }
													else{ ?>
														<td class="text-center">
															<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																echo $item['price'];
																echo form_hidden('price',$item['price']); ?>
															</form>
														</td>
													<?php }
												}
												else
												{
													if($show_sales_price_without_tax)
													{
														if ($this->Employee->has_module_action_permission('sales', 'edit_sale_price', $logged_in_employee_id))
														{ ?>
															<td class="text-center">
																<?php
													                echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
													                echo form_input(array('name'=>'price','value'=>to_currency_no_money($item['price'], 10),'class'=>'form-control form-inps-sale input-small text-center', 'id' => 'price_'.$line));
													            ?>
																</form>
															</td>
														<?php }
														else{ ?>
															<td class="text-center">
																<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																	echo $item['price'];
																	echo form_hidden('price',$item['price']); ?>
																</form>
															</td>
														<?php }
													}
													if (isset($item['item_id']))
													{
														$tax_info = isset($item['item_id']) ? $this->Item_taxes_finder->get_info($item['item_id']) : $this->Item_kit_taxes_finder->get_info($item['item_kit_id']);
														$i=0;

														foreach($tax_info as $key=>$tax)
														{
														   	$prev_tax[$item['item_id']][$i]=$tax['percent']/100;
															$i++;
														}
														if (isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
														{
															if(!$overwrite_tax){
																$sum_tax=array_sum($prev_tax[$item['item_id']]);
																$value_tax=$item['price']*$sum_tax;
																$price_with_tax=$item['price']+$value_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}

															if($show_sales_price_iva){ ?>
																<td class="text-center">
																	<?php echo to_currency($value_tax, 2);?>
														    	</td>
														    <?php } ?>
													    	<td class="text-center">
														    	<?php echo to_currency($price_with_tax, 2) ?>
														    </td>
															<?php
														}
														elseif (!isset($prev_tax[$item['item_id']]) && $item['name']!=lang('sales_giftcard'))
														{
															if(!$overwrite_tax){
																$sum_tax=0; 
																$value_tax=$item['price']*$sum_tax;
																$price_with_tax=$item['price']+$value_tax;
															}else{
																$value_tax=get_nuevo_iva($new_tax,$item['price']);
																$price_with_tax =get_precio_con_nuevo_iva($new_tax,$item['price']);
															}
															

															if($show_sales_price_iva){ ?>
																<td class="text-center">
																	<?php echo to_currency($value_tax, 2);?>
														    	</td>
														    <?php } ?>
													    	<td class="text-center">
														    	<?php echo to_currency($price_with_tax, 2) ?>
														    </td>
														    <?php
														}
														else{
															if($show_sales_price_iva){ ?>
																<td></td>
															<?php } ?>

																<td></td>
															<?php
														}
													}
													if (isset($item['item_kit_id']))
													{
														$tax_kit_info = isset($item['item_kit_id']) ? $this->Item_kit_taxes_finder->get_info($item['item_kit_id']) : $this->Item_kit_taxes_finder->get_info($item['item_id']);
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

															if($show_sales_price_iva){ ?>
																<td class="text-center">
																	<?php echo to_currency($value_tax, 2);?>
														    	</td>
														    <?php }
														    if($show_sales_price_without_tax){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($price_with_tax, 2) ?>
															    </td>
															<?php } ?>
															<?php if (!$show_sales_num_item && !$show_sales_inventory && !$show_sales_price_iva && !$show_sales_price_without_tax && !$show_sales_discount) { ?>
																<td></td>
															<?php }
															elseif (!$show_sales_price_without_tax) { ?>
																<td></td>
															<?php }
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

															if($show_sales_price_iva){ ?>
																<td class="text-center">
																	<?php echo to_currency($value_tax, 2);?>
														    	</td>
														    <?php }
														    if($show_sales_price_without_tax){ ?>
														    	<td class="text-center">
															    	<?php echo to_currency($price_with_tax, 2) ?>
															    </td>
															<?php } ?>
															<?php if (!$show_sales_num_item && !$show_sales_inventory && !$show_sales_price_iva && !$show_sales_price_without_tax && !$show_sales_discount) { ?>
																<td></td>
															<?php }
															elseif (!$show_sales_price_without_tax) { ?>
																<td></td>
															<?php }
														}

														else{ ?>
															<td></td>
															<td></td>
														<?php }
													}
												}
												?>
												<?php if($edit_tiers) :?>
												<td width="4%" class="text-center">
													<?php echo form_open("sales/edit_item_tier/$line/$itemId", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
													echo '<span class="">';
													echo form_dropdown('tier_id_item', $tiers, $item["id_tier"], 'style="width: 90px;" class="form-control tier_id_item"');
													echo '</span>';
														
													?>
													</form>
												</td>
											<?php endif;?>

												<td width="3%" class="text-center">
													<?php echo form_open("sales/edit_item/$line/$itemId", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
														if(isset($item['is_serialized']) && $item['is_serialized']==1)
														{
															echo to_quantity($item['quantity']);
															echo form_hidden('quantity',to_quantity($item['quantity']));
														}
														else
														{
															echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'class'=>'form-control form-inps-sale text-center', 'id' => 'quantity_'.$line, 'tabindex'=>'2'));
														}?>
													</form>
												</td>

												<?php if ($show_sales_discount)
												{
													if ($this->Employee->has_module_action_permission('sales', 'give_discount', $logged_in_employee_id)){ ?>
														<td width="3%">
															<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																echo form_input(array('name'=>'discount','value'=>$item['discount'],'class'=>'form-control form-inps-sale input-small text-center', 'id' => 'discount_'.$line));?>
														  	</form>
														</td>
													<?php }
													else{ ?>
														<td width="3%">
															<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																echo $item['discount'];
																echo form_hidden('discount',$item['discount']); ?>
															</form>
														</td>
													<?php }
												} ?>

												<?php if ($item_info->tax_included)
												{
		                                       		$Total=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
		                                       	}
		                                       	else if ($item_info->category == lang('giftcards_giftcard'))
		                                       	{
		                                       		$Total=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
		                                       	}
		                                       	else
		                                       	{
		                                       		$Total=$price_with_tax*$item['quantity']-$price_with_tax*$item['quantity']*$item['discount']/100;
		                                       	}
		                                       	?>
												
												<td class="text-center">
													<?php echo $this->config->item('round_value')==1 ? to_currency(round($Total)) : to_currency($Total);?>
												</td>
											</tr>

											<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard') || $show_sales_description){ ?>
												<tr id="reg_item_bottom" class="register-item-bottom">
													<?php if ($show_sales_description) { ?>
														<td >
															<strong><?php echo lang('sales_description_abbrv').':';?></strong>
														</td>
														<td colspan="2" class="edit_description">
															<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
																if(isset($item['allow_alt_description']) && $item['allow_alt_description']==1)
																{
																	echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20', 'id' => 'description_'.$line, 'class' =>'form-control description', 'maxlength' => 255));
																}
																else
																{
																	if ($item['description']!='')
																	{
																		echo $item['description'];
																		echo form_hidden('description',$item['description']);
																	}
																	else
																	{
																		echo 'None';
																		echo form_hidden('description','');
																	}
																}?>
															</form>
														</td>
													<?php } ?>

													<td>
														<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard')){
															echo "<strong>";
															echo lang('sales_serial').':';
															echo "</strong>";
														}?>
													</td>
													<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard')){ ?>
														<td colspan="1" class="edit_serialnumber">
															<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));
															    if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard'))
															    {
																	echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'], 'class' => 'form-control form-inps-sale serial_item','size'=>'20', 'id' => 'serialnumber_'.$line, 'maxlength' => 255));
																}
																else
																{
																	echo form_hidden('serialnumber', '');
																}?>
															</form>
														</td>
													<?php } ?>
												</tr>
											<?php }		
											
											
										}
									}
									?>
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
									<th ><?php echo lang('sales_item_name'); ?></th>
									<th ><?php echo lang('sales_payment_amount'); ?></th>
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
											<?php echo form_open("sales/edit_item/$line", array('class' => 'line_item_form', 'autocomplete'=> 'off'));

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

				<div class="row">
					<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
						<?php
							echo '<div class="md-checkbox-inline">';
							echo '<div class="md-checkbox">';
							echo form_checkbox(array(
								'name'=>'show_comment_on_receipt',
								'id'=>'show_comment_on_receipt',
								'value'=>'1',
								'class'=>'md-check',
								'checked'=>(boolean)$show_comment_on_receipt
                            ));

							echo '<label id="show_comment_on_receipt_label" for="show_comment_on_receipt">';
							echo '<span></span>';
							echo '<span class="check"></span>';
							echo '<span class="box"></span>';
							echo lang('sales_comments_receipt');
							echo '</label>';
							echo '</div>';
							echo '</div>';
						?>

					</div>

					<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
						<?php
							echo '<div class="md-checkbox-inline">';
							echo '<div class="md-checkbox">';
							echo form_checkbox(array(
								'name'=>'show_receipt',
								'id'=>'show_receipt',
								'value'=>'1',
								'class'=>'md-check',
                                'checked'=>(boolean)$show_receipt)
							);

							echo '<label id="show_receipt_label" for="show_receipt">';
							echo '<span></span>';
							echo '<span class="check"></span>';
							echo '<span class="box"></span>';
							echo lang("sale_not_print_invoice"); 
							echo '</label>';
							echo '</div>';
							echo '</div>';
						?>
					</div>

                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                        <?php  if( ($mode=='sale' or $mode=='return') && $this->config->Item('hide_ticket')){


                            echo '<div class="md-radio-inline">';
                            echo '<div class="md-radio">';
                            echo form_radio(array(
                                    'name'=>'show_comment_ticket',
                                    'id'=>'show_comment_ticket',
                                    'value'=>'0',
                                    'class'=>'md-check',
                                    'checked'=>($this->session->userdata('show_comment_ticket') == 1) ? 1 : 0
                                )
                            );

                            echo '<label id="show_comment_ticket" for="show_comment_ticket">';
                            echo '<span></span>';
                            echo '<span class="check"></span>';
                            echo '<span class="box"></span>';
                            echo  lang('sales_ticket_on_receipt');
                            echo '</label>';
                            echo '</div>';
                            echo '</div>';
                            }
                        ?>

                    </div>

                    <div class="col-lg-1 col-md-3 col-sm-12 col-xs-12">
                        <?php  if( ($mode=='sale' or $mode=='return') && $this->config->Item('hide_ticket')){


                            echo '<div class="md-radio-inline">';
                            echo '<div class="md-radio">';
                            echo form_radio(array(
                                    'name'=>'show_comment_ticket',
                                    'id'=>'show_comment_invoice',
                                    'value'=>'1',
                                    'class'=>'md-check',
                                    'checked'=>($this->session->userdata('show_comment_ticket') == 0) ? 1 : 0
                                )
                            );

                            echo '<label id="show_comment_invoice" for="show_comment_invoice">';
                            echo '<span></span>';
                            echo '<span class="check"></span>';
                            echo '<span class="box"></span>';
                            echo ucfirst(strtolower($this->config->item('sale_prefix')));
                            echo '</label>';
                            echo '</div>';
                            echo '</div>';
                            }
                        ?>

					</div>
					<div class="clearfix"></div>

					
					<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
						<div class="row col-md-4 pull-right">
							<div class="sale-buttons ">
								<!-- Cancel and suspend buttons -->
								<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form', 'autocomplete'=> 'off')); ?>
									<div class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
										<?php if ($mode == 'store_account_payment') { ?>
											<a type="button" class="btn red-thunderbird" id="cancel_sale_button"><?php echo lang('sales_cancel_sale');?></a>
										<?php } ?>
									</div>
								</form>
							</div>
						</div>
						<?php if ($this->Employee->has_module_action_permission('sales', 'give_discount', $logged_in_employee_id) && $mode != 'store_account_payment'){ ?>
							<div class="" id="global_discount">
								<?php echo form_open("sales/discount_all", array('id' => 'discount_all_form', 'class'=>'form-horizontal', 'autocomplete'=> 'off'));
									echo '<div class="form-group no_margin_bottom"><label class="col-md-7 col-sm-12 col-xs-12 control-label" id="discount_all_percent_label" for="discount_all_percent">';
									echo lang('sales_global_sale_discount').': ';
									echo '</label>';
									echo '<div class="col-md-5 col-sm-12 col-xs-12">';
									echo '<div class="input-group"><div class="input-icon right"><i class="icon-percent"></i>';
									echo form_input(array('name'=>'discount_all_percent','value'=> '','size'=>'3', 'class' => 'form-control' , 'id' => 'discount_all_percent'));
									echo '</div><span class="input-group-btn">';
									echo form_button(array('name'=>'submit_discount_form','type'=>'submit', 'class'=>'btn btn-success'), lang('common_submit'));
									echo '</span></div></div></div>'
									?>
								</form>
							</div>
						<?php } ?>
					</div>
					<?php if ($this->Employee->has_module_action_permission('sales', 'select_seller_during_sale', $logged_in_employee_id)) :?>
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" id="div_employees"></div>
					<?php endif; ?>
					<?php if ($this->Employee->has_module_action_permission('sales', 'overwrite_tax', $logged_in_employee_id)) :?>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<br>
							<div >
								<?php echo form_open("sales/set_new_tax", array('id' => 'new_tax_form', 'class'=>'form-horizontal', 'autocomplete'=> 'off'));?>
									<div class="form-group no_margin_bottom">
										<label class="col-md-9 col-sm-12 col-xs-12 control-label" id="new_tax" for="new_tax"><?php echo $this->config->item('name_new_tax').': ';?></label>
										<div class="col-md-3 col-sm-12 col-xs-12">
											<div class="input-group">
												<div class="input-icon right"><i class="icon-percent"></i>
													<?php echo form_input(array('name'=>'new_tax','value'=> '','min'=>0,'size'=>'3',"type"=>"number", 'class' => 'form-control' , 'id' => 'new_tax'))?>
												</div>
												<span class="input-group-btn">
													<?php echo form_button(array('name'=>'submit_tax_form','type'=>'submit', 'class'=>'btn btn-success'), lang('common_submit'))?>
												</span>
											</div>
										</div>
									</div>
								</form>
							</div>								
						</div>
					<?php endif; ?>
				</div>
			</div>

			
			<?php if (!$this->config->item('hide_customer_recent_sales') && isset($customer)) { ?>
				<div class="portlet light register-items-table margin-bottom-15">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-info-circle"></i>
							<span class="caption-subject bold">
								<?php echo lang('sales_recent_sales').' '.H($customer);?>
							</span>
							<?php if($this->appconfig->get("customers_store_accounts")):?>
								<a href="<?php echo site_url('sales/sale_details_modal')."/". (isset($customer_id) ? $customer_id: 0 ); ?>" data-toggle="modal" data-target="#myModal" > - Ver pagos de crédito</a>
							<?php endif; ?>
						</div>
					</div>
					<div class="table-responsive">
						<table id="recent_sales" class="table table-advance">
							<thead>
								<tr>
									<th align="center"><?php echo lang('items_date');?></th>
									<th align="center"><?php echo lang('reports_payments');?></th>
									<th align="center"><?php echo lang('reports_items_purchased');?></th>
									<th align="center"><?php echo lang('sales_receipt');?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($recent_sales as $sale) {
										?>
									<tr>
										<td align="center"><?php echo date(get_date_format().' @ '.get_time_format(), strtotime($sale['sale_time']));?></td>
										<td align="center">
											<?php echo $sale['payment_type'];?>
											</td>
										<td align="center"><?php echo to_quantity($sale['items_purchased']);?></td>
										<td align="center"><?php echo anchor('sales/receipt/'.$sale['sale_id'], lang('sales_receipt'), array('target' =>'_blank')); ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php } ?>

		</div>

		<!-- BEGIN RIGHT SMALL BOX  -->
		<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 no-padding-left sale_register_rightbox" id="box-customers">
			<!-- BEGIN SMALL BOX CUSTOMER -->
			<div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
				<div class="portlet-title padding">
					<div class="caption">
						<i class="fa fa-user"></i>
						<span class="caption-subject bold">
							<?php if(isset($customer))
							{
							 	echo lang('customers_basic_information');
							}
							else
							{
								if ($this->config->item('require_customer_for_sale'))
								{
									echo lang('sales_select_customer_required');
								}
								else
								{
									echo lang('sales_select_customer');
								}
							}?>
						</span>
						<a class="icon fa fa-youtube-play help_button" id='maxsales' data-toggle="modal" data-target="#stack5"></a>
					</div>

				</div>
				<div class="portlet-body padding">
					<?php if(isset($customer)) { ?>
						<div class="customer-box">
							<div class="avatar">
								<?php if($avatar != '')
								{
									echo '<img src="'.$avatar.'" alt="Customer" class="img-thumbnail" width="100px" />';
								}
								else
								{
									echo '<img src="'.base_url("img/avatar.jpg").'" alt="Customer" class="img-thumbnail"  />';
								}
								?>
							</div>
							<div class="information <?php echo empty($customer_email) ? 'info-no-email' : 'info-yes-email'?>">
								<a class="name" href="<?php echo isset($customer_id) ? site_url('customers/view_modal/'.$customer_id) : site_url('customers/view_modal/'.$customer_id) ; ?>" data-toggle="modal" data-target="#myModal" ><?php echo character_limiter(H($customer), 29); ?>
									<?php if ($this->config->item('customers_store_accounts') && isset($customer_balance)) {?>
										<span class="<?php echo $is_over_credit_limit ? 'credit_limit_warning' : 'credit_limit_ok'; ?>">(<?php echo lang('customers_balance').': '.to_currency($customer_balance); ?>)</span>
									<?php } ?>
								</a>
								<span class="email">
									<?php if(!empty($customer_email)) {
										echo $customer_email; ?>

										<div class="md-checkbox-inline">
											<div class="md-checkbox">
												<?php echo form_checkbox(array(
													'name'=> 'email_receipt',
													'id'  => 'email_receipt',
													'value' => '1',
													'class' => 'email_receipt_checkbox',
													'checked' => (boolean)$email_receipt));
												?>
												<label for="email_receipt">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo lang('sales_email_receipt') ?>
												</label>
											</div>
										</div>
									<?php }?>
								</span>
							</div>
						</div>
						<div class="customer-buttons-<?php echo empty($customer_email) ? 'no-email' : 'yes-email'?>">
							<div class="btn-group btn-group-justified btn-group-xs">
								<?php
									echo anchor("customers/view/$customer_id/1", lang('common_edit'),  array('id' => 'edit_customer','class'=>'btn btn-success','title'=>lang('customers_update'))).'';
									echo anchor("sales/delete_customer", lang('sales_detach'), array('id' => 'delete_customer','class'=>'btn yellow-gold'));
								?>
							</div>
						</div>
					<?php }
					else
					{?>
						<div class="customer-search">
							<div class="input-group">
								<span class="input-group-btn">
									<?php echo anchor("customers/view/-1/1",
										'<i class="icon-user-follow hidden-lg"></i><span class="visible-lg">'.lang('sales_new_customer').'</span>',
										array('class'=>'btn btn-success tooltips', 'data-original-title'=>lang('sales_new_item'), 'title'=>lang('sales_new_customer'), 'id' => 'new-customer'));
									?>
								</span>
								<?php echo form_open("sales/select_customer",array('id'=>'select_customer_form', 'autocomplete'=> 'off')); ?>
									<?php echo form_input(array('name'=>'customer','id'=>'customer', 'class'=>'form-control form-inps-sale', 'size'=>'30','value'=>lang('sales_start_typing_customer_name'), 'placeholder'=>lang('sales_start_typing_customer_name'),  'accesskey' => 'c'));?>
								</form>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
			<!-- END SMALL BOX CUSTOMERS -->

			<!-- BEGIN SMALL BOX SALE SUMMARY-->
			<div class="portlet light margin-top-15 no-padding">
				<div class="portlet-title padding">
					<div class="caption">
						<i class="icon-basket-loaded"></i>
						<span class="caption-subject bold">
							<?php echo lang('sales_summary'); ?>
						</span>
					</div>
					<div class="options">
						<a href="<?php echo site_url('sales/view_shortcuts') ; ?>" data-toggle="modal" data-target="#myModal" class="pull-right tooltips" id="opener" data-original-title="Atajos de teclado"><i class="fa fa-keyboard-o" style="font-size: 30px"></i></a>
					</div>
				</div>
				<ul class="list-group">
					<?php if (count($tiers) > 1 && $mode != "store_account_payment")
					{
						echo '<li class="list-group-item tier-style">';
						echo '<div class="row">';
						echo '<div class="col-md-6">';
						echo '<span class="name-tier">'.lang('items_tiers'),'</span>';
						echo '</div>';
						// margin: 12px 0px 0px 12px;
						if ($this->Employee->has_module_action_permission('sales', 'edit_tier_all', $logged_in_employee_id)|| $this->Employee->has_module_action_permission('sales', 'edit_sale_price', $logged_in_employee_id))
						{
							echo '<div class="col-md-6">';
							echo '<span class="">';
							echo form_dropdown('tier_id', $tiers, $selected_tier_id, 'id="tier_id" class="bs-select form-control"');
							echo '</span></div>';
						}
						else
						{
							echo '<span class="pull-right">';
							echo "<div class='item_tier_no_edit text-info'>: ".H($tiers[$selected_tier_id])."</div>";
							echo '</span';
						}
						echo '</div></li>';
					}?>
				

					<div class="item-subtotal-block">
						<div class="num_items amount">
							<span class="name-item-summary">
								<?php echo lang('sales_items_in_cart'); ?>:
							</span>
							<span class="pull-right badge badge-cart badge-<?php echo $items_in_cart==0 ? 'warning' : 'success'?>">
								<?php echo $items_in_cart; ?>
							</span>
						</div>
						<div class="subtotal">
							<span class="name-item-summary">
								<?php echo lang('sales_sub_total'); ?>:
							</span>
							<span class="pull-right name-item-summary">
								<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ; ?></strong>
							</span>
						</div>
					</div>

					<?php foreach($payments as $payment) {?>
						<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
							<li class="list-group-item">
								<span class="name-item-summary">
									<?php echo $payment['payment_type']. ' '.lang('sales_balance') ?>:
								</span>
								<?php $giftcard_payment_row = explode(':', $payment['payment_type']); ?>
								<span class="name-item-summary pull-right">
									<?php echo to_currency($this->Giftcard->get_giftcard_value(end($giftcard_payment_row)) - $payment['payment_amount']);?>
								</span>
							</li>
						<?php }?>
					<?php }?>

					<?php foreach($taxes as $name=>$value) {
						if(!($value=='0.00')) { ?>
							<li class="list-group-item">
								<span class="name-item-summary">
									<?php if (!$is_tax_inclusive && $this->Employee->has_module_action_permission('sales', 'delete_taxes', $logged_in_employee_id)){ ?>
										<?php echo anchor("sales/delete_tax/".rawurlencode($name),'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_tax'));?>
									<?php } ?>
									<?php echo $name; ?>:
								</span>
								<span class="name-item-summary pull-right">
									<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($value)) :to_currency($value) ; ?></strong>
								</span>
							</li>
						<?php }
					}; ?>
				</ul>

				<div class="amount-block">
					<div class="total amount">
						<div class="side-heading">
							<?php echo lang('sales_total'); ?>:
						</div>
						<div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">
							<?php echo $this->config->item('round_value')==1 ? to_currency(round($total)) :to_currency($total) ; ?>
							<br>
						</div>
						
						
					</div>
					<div class="total">
						<div class="side-heading">
							<?php echo lang('sales_amount_due'); ?>:
						</div>
						<div id="amount-due" class="amount animation-count <?php if($payments_cover_total) { echo 'font-green-jungle'; } else { echo 'text-danger'; }?>"  data-speed="1000" data-decimals="2">
							<?php echo $this->config->item('round_value')==1 ? to_currency(round($amount_due)) : to_currency($amount_due)?>
						</div>
					</div>
				</div>
				
				<?php if(count($cart) > 0) {		// Only show this part if there are Items already in the sale.?>
					<?php if(count($payments) > 0) {	// Only show this part if there is at least one payment entered. ?>
						<div class="payments">
							<ul class="list-group">
								<?php foreach($payments as $payment_id=>$payment) { ?>
									<li class="list-group-item no-border-top">
										<span class="name-item-summary">
											<?php echo anchor("sales/delete_payment/$payment_id",'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_payment'));?>
											<?php echo $payment['payment_type']; ?>
										</span>
										<span class="name-item-summary pull-right">
											<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])):to_currency($payment['payment_amount']);?></strong>
										</span>
									</li>
								<?php } ?>
							<ul>
						</div>
					<?php } ?>

					<?php if ($customer_required_check) { ?>
						<!-- BEGIN ADD PAYMENT -->
						<div class="add-payment">

							<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form', 'autocomplete'=> 'off')); ?>

								<ul class="list-group"> 
									<li class="list-group-item no-border-top tier-style">
										<div class="row">
											<div class="col-md-6">
												<span class="name-addpay">
													<a class="sales_help tooltips" data-placement="left" title="<?php echo lang('csales_add_payment_help'); ?>"><?php echo lang('sales_add_payment'); ?> </a>:
												</span>
											</div>
											<div class="col-md-6">
												<span class="">
													<?php echo form_dropdown('payment_type',$payment_options,$this->config->item('default_payment_type'), 'id="payment_types" class="bs-select form-control"');?>
												</span>
											</div>
										</div>
										<!--<div class="row margin-top-10">
											<div class="col-md-12">
												<div class="input-group">
													<?php $value=$this->config->item('round_value')==1 ? round($amount_due): to_currency_no_money($amount_due); ?>
													<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>$value,'class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
													<span class="input-group-btn">
													<input class="btn btn-success" type="button" id="add_payment_button" value="<?php echo lang('sales_add_payment'); ?>" />
													</span>
												</div>
											</div>

										</div>-->

										<div class="row margin-top-10" id="panel1" style="display: <?php echo $pagar_otra_moneda? "none":"block" ?>;">
											<div class="col-md-12">
												<div class="input-group">
													<?php $value=$this->config->item('round_value')==1 ? round($amount_due): to_currency_no_money($amount_due); ?>
													<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>$value,'class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
													<span class="input-group-btn">
													<input class="btn btn-success" type="button" id="add_payment_button" value="<?php echo lang('sales_add_payment'); ?>" />
													</span>
												</div>
											</div>
										</div>
										<div class="row margin-top-10" id="panel2" style="display: <?php echo $pagar_otra_moneda?"block":"none" ?>;">
											<div class="col-md-12">
												<div class="input-group">												
													<div class="input-group">
														<span class="input-group-addon" id="abreviatura"><?php echo $currency?></span>
														<?php echo form_input(array('name'=>'amount_tendered2','id'=>'amount_tendered2','class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
														<span class="input-group-btn">
															<input class="btn btn-success" type="button" id="add_payment_button2" value="<?php echo lang('sales_add_payment'); ?>" />
														</span>
													</div>
												</div>
											</div>
											
										</div>
										<?php if($this->config->item('activar_pago_segunda_moneda')==1){
											$moneda1= $this->config->item('moneda1');
											$moneda2=$this->config->item('moneda2');
											$moneda3=$this->config->item('moneda3');
											?>
											<?= lang("sales_pay")?>
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda1')?>" equivalencia ="<?php echo $this->config->item('equivalencia1')?>" id="otra_moneda1" value="1" <?php echo ($pagar_otra_moneda and ($moneda_numero==1 or $currency==$moneda1))? "checked" :"" ?>> <strong> <?php echo $moneda1;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda2')?>" equivalencia ="<?php echo $this->config->item('equivalencia2')?>" id="otra_moneda2" value="2" <?php echo ($pagar_otra_moneda and ($moneda_numero==2 or $currency==$moneda2)) ? "checked" :"" ?>> <strong> <?php echo $moneda2;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda3')?>" equivalencia ="<?php echo $this->config->item('equivalencia3')?>" id="otra_moneda3" value="3" <?php echo ($pagar_otra_moneda and ($moneda_numero==3 or $currency==$moneda3)) ? "checked" :"" ?>> <strong> <?php echo $moneda3;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="0" equivalencia ="1" id="otra_moneda0" value="0" <?php echo  ($pagar_otra_moneda==0 and $moneda_numero==0) ? "checked" :"" ?>> <strong>Default</strong><br>

										<?php }?>

									</li>
								</ul>
							</form>
						</div>
						<!-- END ADD PAYMENT -->
					<?php } ?>

					<div class="comment-sale">
						<ul class="list-group">
							<li class="list-group-item no-border-top tier-style">
								<?php
									// Only show this part if there is at least one payment entered.
									if((count($payments) > 0 /* && !is_sale_integrated_cc_processing()*/))
									{?>
										<div id="finish_sale">
											<?php echo form_open("sales/complete",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
												<?php if ($payments_cover_total && $customer_required_check)
												{
													echo "<input type='button' class='btn btn-success btn-large btn-block' id='finish_sale_button' value='".lang('sales_complete_sale')."' />";
												}?>
											</form>
										</div>

									<?php }
									

									echo '<div id="container_comment" class="hidden">';
									echo '<div class="title-heading"';
									echo '<label id="comment_label" for="comment">';
									echo lang('common_comments');
									echo ':</label></div>';
									echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'class'=>'form-control form-textarea','value'=>$comment,'rows'=>'2',  'accesskey' => 'o'));
									echo '</div>';



									if($this->appconfig->get('enabled_for_Restaurant') == '1'){

										$cantmensa = $this->appconfig->get('table_acount');
										$data_mesa = array(lang("sale_select_table"));
										for ($i = 1; $i <= $cantmensa; $i++) {
											array_push($data_mesa,  lang("ntable")." #"  . $i);
										}
										echo '<div class="title-heading"';
										echo '<label id="comment_label" for="comment">';
										echo lang('numer_table');
										echo ':</label></div>';

										echo form_dropdown('ntable',$data_mesa,$ntable,'id="ntable" class="bs-select form-control"');
									}



								?>

								<?php if($this->sale_lib->get_change_sale_id()) {
									echo '<br />';
									echo '<div class="md-checkbox-inline">';
									echo '<div class="md-checkbox">';
									echo form_checkbox(array(
										'name'=>'change_sale_date_enable',
										'id'=>'change_sale_date_enable',
										'value'=>'1',
										'checked'=>(boolean)$change_sale_date_enable)
									);
									echo '<label id="comment_label" for="change_sale_date_enable">';
									echo '<span></span>';
									echo '<span class="check"></span>';
									echo '<span class="box"></span>';
									echo lang('sales_change_date');
									echo '</label>';
									echo '</div>';
									echo '</div>';
									?>

									<div class="row margin-top-10" id="change_sale_input">
										<div class="col-md-12">
											<div id="change_sale_date_picker" class="input-group date datepicker" data-date="date(get_date_format())" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
				  								<span class="input-group-addon">
				  									<i class="fa fa-calendar"></i>
			  									</span>
												<?php echo form_input(array(
													'name'=>'change_sale_date',
													'id' => 'change_sale_date',
													'size'=>'8',
													'class'=>'form-control form-inps',
													'value'=> date(get_date_format())
												));?>
									    	</div>
										</div>
									</div>
								<?php } ?>
							</li>
						</ul>
					</div>

				<?php } ?>

				<div id="finish_sale" class="finish-sale">

				</div>


			</div>
			<!-- END SMALL BOX SALE SUMMARY -->

		</div>
		<!-- END RIGHT SMALL BOX -->

	</div>




	<?php if (!$this->config->item('disable_sale_notifications')) { ?>
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
	<?php } ?>

	<script type="text/javascript" language="javascript">

		<?php if($this->config->item('activar_pago_segunda_moneda')==1){?>
				let monto= convertir_moneda(<?php echo $amount_due ?>,<?php echo $equivalencia?>);
				window.equivalencia= '<?=  $equivalencia?>';
				$("#amount_tendered2").val(monto);
				$("#amount_tendered2").change(function(){
					$("#add_payment_button2").attr('disabled','disabled');
					if($.isNumeric($("#amount_tendered2").val()) ){
						$("#amount_tendered").val(revertir_moneda($("#amount_tendered2").val(),window.equivalencia));
						$("#add_payment_button2").removeAttr('disabled');
					}else{
						$("#add_payment_button2").removeAttr('disabled');
					}
				});
			<?php }?>
			$( "input[type=radio][name=otra_moneda]").change(function(){
			
				let moneda_numero= $(this).val();
				let equivalencia= $(this).attr("equivalencia");
				let abreviatura= $(this).attr("abreviatura");
				if (moneda_numero>0) {
					$.post('<?php echo site_url("sales/set_otra_moneda");?>', {"otra_moneda": 1,"moneda_numero":moneda_numero}, function(data)
					{
						/*$("#panel2").show();
						$("#panel1").hide();
						$("#abreviatura").html(abreviatura);
						let monto= convertir_moneda(<?php echo $amount_due ?>,equivalencia);
					$("#amount_tendered2").val(monto);*/
					$("#register_container").load('<?php echo site_url("sales/reload"); ?>');

					});		
				}
				else{
					$.post('<?php echo site_url("sales/set_otra_moneda");?>', {"otra_moneda": 0,"moneda_numero":"default"}, function()
					{
						$("#panel1").show();
						$("#panel2").hide();
					});		
				}
			});

		//Table headers fixed
		var $table_receivings = $('#register');
		$table_receivings.floatThead({
			scrollContainer: function($table){
				return $table.closest('.table-scrollable');
			}
		});
		$( ".select_custom_subcategory" ).change(function() {
		$($(this).parent()).submit();
		});
		$( "#id_credito" ).change(function() {
			var opt= $("#id_credito").val();
			if(opt==2){
				$.ajax(
					{
					url :'<?php echo base_url();?>index.php/sales/get_monton_pendiente/'+<?php echo isset($customer_id) ? $customer_id: 0; ?>,
					type: "GET"

					})
					.done(function(data) {

					$("input[name='price']").val(data);
					$("input[name='price']").focus();					

					})
					.fail(function(data) {
						alert( "error al consultar el Monto" );
					})
					.always(function(data) {

				});
			}


  			
		});
		// Animation total sales
		$('.animation-count').data('countToOptions', {
	        formatter: function (value, options) {
	          	return value.toFixed(options.decimals).replace('','<?php echo $this->config->item("currency_symbol")?>').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	        }
		  });
		  $("#add_payment_button2").click(function()
			{
				$("#add_payment_button").click();
			});
		//$('#total-amount').data('to',1);
      	$('.animation-count').each(count);

		$('#total-amount').data('from', last_total_amount);
		$('#total-amount').html(last_total_amount);
		$('#amount-due').data('from', last_total_amount_due);
		$('#amount-due').html(last_total_amount_due);
		var last_total_amount_due = <?php echo $this->config->item('round_value')==1 ? to_currency_no_money(round($amount_due)) : to_currency_no_money($amount_due)?>;
        var last_total_amount = <?php echo $this->config->item('round_value')==1 ? to_currency_no_money(round($total)) : to_currency_no_money($total)?>;
		$('#total-amount').data('to',last_total_amount);
		$('#amount-due').data('to',last_total_amount_due);
    	$('.animation-count').each(count);

    	function count(options) {
	        var $this = $(this);
	        options = $.extend({}, options || {}, $this.data('countToOptions') || {});
	        $this.countTo(options);
      	}

		//Remove attr small box sale summary
		$('.list-group-item:first-child').css('border-top-left-radius', '0').css('border-top-right-radius','0');
		$('.list-group-item').css('border', '0');

		//
		$(document).ready(function()
		{



			<?php if($this->config->item('hide_video_stack5') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack5').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){

         if($(e.target)[0].id == "stack5")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack5').css({'display':'none'});

         }


         });
          $('#closesales').click(function(){

               $('.modal.fade.in').removeClass('in');
               $('#stack5').css({'display':'none'});
               $('#maxsales').removeClass('icon fa fa-youtube-play help_button');
               $('#maxsales').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });

         $('#checkBoxStack5').click(function(e){

             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video5:$(this).is(':checked') ? '1' : '0',video5:'hide_video_stack5'});



         });
			if($("#show_comment_on_receipt").is(":checked") == true)
			{
				$("#container_comment").removeClass('hidden');
			}
			$("#show_comment_on_receipt").click(function(event) {

			    if ($(this).is(":checked"))
			    {
			      	$("#container_comment").removeClass('hidden');
			    }
			    else
			    {
			      	$("#container_comment").addClass('hidden');
			    }
		  	});

			//Acomodar la tabla dependiendo la resolucion y el contenedor de customers en el padding izquierdo

			//$(window).bind("resize",function(){
			var size = $(this).width();
		    if (size <= 1280)
	    	{
	    		$('.table-scrollable').css('height', '360px');
	    	}
	    	else if (size > 1280 && size <= 1366)
	    	{
	    		$('.table-scrollable').css('height', '410px');
	    	}

		    if(size < 992){
			    $('#box-customers').removeClass('no-padding-left')
		    }
		    else{
		    	$('#box-customers').addClass('no-padding-left')
		    }
			//});
		});

		// Show or hide item grid
		$(".show-grid").on('click',function(e)
		{
			e.preventDefault();
			$("#sale-grid-big-wrapper").removeClass('hidden');
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

		//
		$(document).keydown(function(event)
		{
			var mycode = event.keyCode;

			if (mycode == 113)
			{
				$("#item").focus();
			}

			//F4
			if (mycode == 115)
			{
				event.preventDefault();
				$("input[name='quantity']").focus();
				event.originalEvent.keyCode = 0;
			}
			//F1
			if (mycode == 112)
			{
				event.preventDefault();
				$("#modal-serial").click();
				event.originalEvent.keyCode = 0;
			}

			//F7
			if (mycode == 118)
			{
				event.preventDefault();
				$("#amount_tendered").focus();
				$("#amount_tendered").select();
			}

			//F8
			if (mycode == 119)
			{
		    	$("#add_payment_button").click();
			}

			//F9
			if (mycode == 120)
			{
				event.preventDefault();
				$("#finish_sale_button").click();
				event.originalEvent.keyCode = 0;
			}

			//F10
			if (mycode == 121)
			{
				event.preventDefault();
				$("#customer").focus();
				$("#customer").select();
				event.originalEvent.keyCode = 0;
			}

			//ESC
			if (mycode == 27)
			{
		    	$("#cancel_sale_button").click();
			}
		});

   		var submitting = false;
		$(document).ready(function()
		{
			$(function() { $.keyTips(); });

			$( "#keyboardhelp" ).dialog({
				autoOpen: false,
				show: {
					effect: "blind",
			  	 	duration: 1000
				},
			width: 800,
			hide: {
			  	 effect: "explode",
				 duration: 1000
				}
			});

			$( "#opener" ).click(function(e) {
				e.preventDefault();
				$( "#keyboardhelp" ).dialog( "open" );
			});


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
			$('#mode_form, #select_customer_form, #add_payment_form, .line_item_form, #discount_all_form, #new_tax_form').ajaxForm({target: "#register_container", beforeSubmit: salesBeforeSubmit});


			$('#add_item_form').ajaxForm({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: itemScannedSuccess});
			$("#cart_contents input").change(function()
			{
			

				$(this.form).ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
			});

			$( "#item" ).autocomplete({
				source: '<?php echo site_url("sales/item_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{

					event.preventDefault();
					$( "#item" ).val(ui.item.value);
					$( "input[name='no_valida_por_id']" ).val("0");
					$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit, success: itemScannedSuccess});
				}
			});

			$('#item,#customer').click(function()
			{
				$(this).attr('value','');
			});
 
			$( "#customer" ).autocomplete({
				source: '<?php echo site_url("sales/customer_search"); ?>',
				delay: 300,
				autoFocus: false,
				minLength: 1,
				select: function(event, ui)
				{
					$("#customer").val(ui.item.value);
					$('#select_customer_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
				}
			});

			$('#customer').blur(function()
			{
				$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_customer_name')); ?>);
			});

			$('#item').blur(function()
			{
				$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_item_name')); ?>);
			});

			//Datepicker change
			$('#change_sale_date_picker').datetimepicker({
					format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
					locale: "es"
				}).on('dp.change',function(){
					$.post('<?php echo site_url("sales/set_change_sale_date");?>', {
						change_sale_date: $('#change_sale_date').val()});
			});

			//Input change
			$("#change_sale_date").change(function(){
				$.post('<?php echo site_url("sales/set_change_sale_date");?>', {change_sale_date: $('#change_sale_date').val()});
			});

			$('#change_sale_date_enable').change(function()
			{
				$.post('<?php echo site_url("sales/set_change_sale_date_enable");?>', {change_sale_date_enable: $('#change_sale_date_enable').is(':checked') ? '1' : '0'});
			});

			$('#ntable').change(function()
			{
				$.post('<?php echo site_url("sales/set_ntable");?>', {comment: $('#ntable').val()});
			});

			$('#comment').change(function()
			{
				$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()});
			});

			$('#show_comment_on_receipt').change(function()
			{
				$.post('<?php echo site_url("sales/set_comment_on_receipt");?>', {show_comment_on_receipt:$('#show_comment_on_receipt').is(':checked') ? '1' : '0'});
			});

			$('#show_receipt').change(function()
			{
				$.post('<?php echo site_url("sales/set_show_receipt");?>', {show_receipt:$('#show_receipt').is(':checked') ? '1' : '0'});
			});

            $('#show_comment_ticket').change(function()
            {
                if($('#show_comment_ticket').is(':checked')==true){
                    $.post('<?php echo site_url("sales/set_comment_ticket");?>', {show_comment_ticket:1});
                }
            });

			$('#email_receipt').change(function()
			{
				$.post('<?php echo site_url("sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
			});

			/*	$('#save_credit_card_info').change(function()
			{
				$.post('<?php //echo site_url("sales/set_save_credit_card_info");?>', {save_credit_card_info:$('#save_credit_card_info').is(':checked') ? '1' : '0'});
			});
			*/
			$('#change_sale_date_enable').is(':checked') ? $("#change_sale_input").show() : $("#change_sale_input").hide();

			$('#change_sale_date_enable').click(function() {
				if( $(this).is(':checked')) {
					$("#change_sale_input").show();
				} else {
					$("#change_sale_input").hide();
				}
			});

			$('#use_saved_cc_info').change(function()
			{
				$.post('<?php echo site_url("sales/set_use_saved_cc_info");?>', {use_saved_cc_info:$('#use_saved_cc_info').is(':checked') ? '1' : '0'});
			});

			$("#finish_sale_button").click(function()
			{
				<?php
					$total_max1=$total+$total_max;

					if($this->config->item('value_max_cash_flow') <= $total_max1 && $this->config->item('limit_cash_flow')==1) {?>
       	  				toastr.error(<?php echo json_encode(lang("cash_flows_limit_1").to_currency($total_max1).lang("cash_flows_limit_2").to_currency($this->config->item('value_max_cash_flow'))); ?>, <?php echo json_encode(lang('common_error')); ?>);
						return false;
					<?php }?>

					<?php	if ($subcategory_of_items==true && !$this->sale_lib->is_select_subcategory_items()){ ?>
								toastr.error(<?php
									echo json_encode(lang("error_subcategory_item")." (".$this->config->item("custom_subcategory1_name")." y ".$this->config->item("custom_subcategory2_name").")");
								?>);
							return false;
						<?php } ?>

				//Prevent double submission of form
				$("#finish_sale_button").hide();
				$("#register_container").plainOverlay('show');

				<?php if ($is_over_credit_limit) { ?>
					if (!confirm(<?php echo json_encode(lang('sales_over_credit_limit_warning')); ?>))
					{
						//Bring back submit and unmask if fail to confirm
						$("#finish_sale_button").show();
						$("#register_container").plainOverlay('hide');

						return;
					}
				<?php } ?>

				<?php if(!$payments_cover_total) { ?>

					if (!confirm(<?php echo json_encode(lang('sales_payment_not_cover_total_confirmation')); ?>))
					{
						//Bring back submit and unmask if fail to confirm
						$("#finish_sale_button").show();
						$("#register_container").plainOverlay('hide');

						return;
					}
				<?php } ?>

				<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
					if (confirm(<?php echo json_encode(lang("sales_confirm_finish_sale")); ?>))
					{
						<?php } ?>

						if ($("#comment").val())
						{
							$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function()
							{
								$('#finish_sale_form').submit();
							});
						}
						else
						{
							$('#finish_sale_form').submit();
						}

						<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
						}
						else
						{
							//Bring back submit and unmask if fail to confirm
							$("#finish_sale_button").show();
							$("#register_container").plainOverlay('hide');
						}
						<?php } ?>
					});


			$("#suspend_sale_button").click(function()
			{
				if (confirm(<?php echo json_encode(lang("sales_confirm_suspend_sale")); ?>))
				{
					$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function() {
						<?php if ($this->config->item('show_receipt_after_suspending_sale')) { ?>
							window.location = '<?php echo site_url("sales/suspend/1"); ?>';
							<?php }else { ?>
								$("#register_container").load('<?php echo site_url("sales/suspend/1"); ?>');
						<?php } ?>
					});
				}
			});


			$("#table_number").change(function () {
				var end = this.value;
				if (end > 0){
					location.href = '<?php echo site_url("sales/unsuspend" );?>/' + end;
				}
			});

	       $("#quotes").click(function()
			{
				if (confirm(<?php echo json_encode(lang("sales_confirm_quotes")); ?>))
				{
					$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function() {
						<?php if ($this->config->item('show_receipt_after_suspending_sale')) { ?>
							window.location = '<?php echo site_url("sales/suspend/3"); ?>';
						<?php }else { ?>
							$("#register_container").load('<?php echo site_url("sales/suspend/3"); ?>');
						<?php } ?>

					});
				}
			});

			$("#cancel_sale_button").click(function()
			{
				if (confirm(<?php echo json_encode(lang("sales_confirm_cancel_sale")); ?>))
				{
					$('#cancel_sale_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
				}
			});

			$("#add_payment_button").click(function()
			{
				$('#add_payment_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});

			});

			$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard);
			$('#mode').change(function()
			{
				if ($(this).val() == "store_account_payment") { // Hiding the category grid
					$('#show_hide_grid_wrapper, #category_item_selection_wrapper').fadeOut();
				}
				else if($(this).val()=="return_ticket"){
					$("#modal-return-ticket").click();
					return 0;
				}
				else { // otherwise, show the categories grid
					$('#show_hide_grid_wrapper, #show_grid').fadeIn();
					$('#hide_grid').fadeOut();
				}
				$('#mode_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});

			});

			$('.delete_item, .delete_payment, #delete_customer, .delete_tax').click(function(event)
			{
				event.preventDefault();
				$("#register_container").load($(this).attr('href'));
			});

			$("#tier_id").change(function()
			{
				$("#ajax-loader").show();
				$.post('<?php echo site_url("sales/set_tier_id");?>', {tier_id: $(this).val()}, function()
				{
					$("#register_container").load('<?php echo site_url("sales/reload"); ?>');
				});
			});
			
			$(".tier_id_item").change(function()
			{
				$($(this).parent()).parent().submit();
				
			});
			$("#tipo_documento").change(function()
			{			
				
				$($(this).parent()).parent().submit();
				
			});
			
			$("#opcion_sale").change(function()
			{
				$("#ajax-loader").show();
				$.post('<?php echo site_url("sales/set_opcion_sale");?>', {opcion_sale: $(this).val()}, function()
				{
					$("#register_container").load('<?php echo site_url("sales/reload"); ?>');
				});
			});

			

			$("input[type=text]").not(".description").click(function() {
				$(this).select();
			});

			if(screen.width <= 768) //set the colspan on page load
			{
				jQuery('td.edit_description').attr('colspan', '2');
				jQuery('td.edit_serialnumber').attr('colspan', '4');
			}

			 $(window).resize(function() {
				var wi = $(window).width();

				if (wi <= 768){
					jQuery('td.edit_description').attr('colspan', '2');
					jQuery('td.edit_serialnumber').attr('colspan', '4');
				}
				else {
					jQuery('td.edit_description').attr('colspan', '4');
					jQuery('td.edit_serialnumber').attr('colspan', '2');
				}
			});

			$("#new-customer").click(function()
			{
				$("body").plainOverlay('show');
			});
		});

		function checkPaymentTypeGiftcard()
		{
			if ($("#payment_types").val() == <?php echo json_encode(lang('sales_giftcard')); ?>)
			{
				$("#amount_tendered").val('');

				<?php if (!$this->agent->is_mobile()) { ?>
					$("#amount_tendered").focus();
				<?php } ?>
				<?php if (!$this->config->item('disable_giftcard_detection')) { ?>
				giftcard_swipe_field($("#amount_tendered"));
				<?php
				}
				?>
			}
		}

		function salesBeforeSubmit(formData, jqForm, options)
		{

			if (submitting)
			{
				return false;
			}
			submitting = true;
			//ajax-loader
			$("#ajax-loader").show();
			$("#add_payment_button").hide();
			$("#finish_sale_button").hide();

		}

		function itemScannedSuccess(responseText, statusText, xhr, $form)
		{
			setTimeout(function(){$('#item').focus();}, 10);
		}

		crear_select_empleado(<?php echo $sold_by_employee_id; ?> ,"div_employees");
		function convertir_moneda($value, equivalencia=1){
			let total =  $value/equivalencia;
			return total;
		}
		function revertir_moneda($value,equivalencia=1){
			let total =  $value*equivalencia;
			return total;
		}
	</script>

	<script>
		jQuery(document).ready(function() {
		   	Metronic.init(); // init metronic core componets
		   	Layout.init(); // init layout
		   	Demo.init(); // init demo features
		 	ComponentsDropdowns.init();
		});
		//$('.selectpicker').selectpicker();

	</script>
