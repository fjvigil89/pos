<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-upload'></i>
				<?php  if(!$item_kit_info->item_kit_id) { echo lang($controller_name.'_new'); } else { echo lang($controller_name.'_update'); }?>
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
	
	<div id="form">
		<?php echo form_open('item_kits/save/'.$item_kit_info->item_kit_id,array('id'=>'item_kit_form','class'=>'form-horizontal')); ?>
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
							<?php echo lang("item_kits_info"); ?>
						</span>
					</div>
					<div class="actions">							
						
					</div>
				</div>

				<div class="portlet-body form">
					<!-- BEGIN FORM-->

					<div class="form-body">
						<p class="text-center"><span class="help-block"><?php echo lang('item_kits_desc'); ?></span></p>
						<br/>
						<div class="form-group">
							<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_kits_name_help").'">'.lang('item_kits_add_item').'</a>'.':', 'item',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'item',
									'id'=>'item'
								));?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-magnifier-add"></i>
						<span class="caption-subject bold">
							<?php echo lang('item_kits_items_added');?>
						</span>					
					</div>
				</div>
				<div class="portlet-body form">
					<div class="table-responsive">
						<table id="item_kit_items" class="table table-striped table-bordered table-hover text-center">
							<tr>
								<th><?php echo lang('common_delete');?></th>
								<th><?php echo lang('item_kits_item');?></th>
							
								<th><?php echo lang('item_kits_quantity');?></th>
							</tr>
							<tr>
								<?php 
								$value = $this->Item_kit_items->get_info($item_kit_info->item_kit_id);
								if (empty($value)) 
								{
									echo "<td id='column' colspan='3' class='text-danger'>".lang('item_kits_no_items_added')."</td>";
							 	}
								else
								{ ?>
							</tr>
								<?php foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item) {?>			
									<tr>
										<?php
											$item_info = $this->Item->get_info($item_kit_item->item_id);								
										?>
										<td><a href="#" onclick='return deleteItemKitRow(this);'><i class=' fa fa-trash-o fa-2x font-red'</i></a></td>
										<td><?php echo $item_info->name; ?></td>
										

										<td><input class='quantity' onchange="calculateSuggestedPrices();" id='item_kit_item_<?php echo $item_kit_item->item_id ?>' type='text' size='3' name=item_kit_item[<?php echo $item_kit_item->item_id ?>] value='<?php echo to_quantity($item_kit_item->quantity); ?>'/></td>
									</tr>
								<?php }} ?>
						</table>
					</div>
				</div>
			</div>

			<div class="portlet light">			
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
							<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_item_number_help").'">'.lang('items_item_number').'</a>'.':', 'name',array('class'=>'col-md-3 control-label  ')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'item_kit_number',
									'id'=>'item_kit_number',
									'value'=>$item_kit_info->item_kit_number)
								);?>
							</div>
						</div>

						<?php echo form_hidden('redirect', $redirect); ?>

						<div class="form-group">
							<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_product_id_help").'">'.lang('items_product_id').'</a>'.':', 'product_id',array('class'=>'col-md-3 control-label wide')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'name'=>'product_id',
									'id'=>'product_id',
									'class'=>'form-control form-inps',
									'value'=>$item_kit_info->product_id)
								);?>
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label('<a class="help_config_required tooltips" data-placement="left" title="'.lang("item_kits_name_help").'">'.lang('item_kits_name').'</a>'.':', 'name',array('class'=>'col-md-3 control-label  requireds')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'name',
									'id'=>'name',
									'value'=>$item_kit_info->name)
								);?>
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label('<a class="help_config_required tooltips" data-placement="left" title="'.lang("items_category_help").'">'.lang('items_category').'</a>'.':', 'category',array('class'=>'col-md-3 control-label requireds wide')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'category',
									'id'=>'category',
									'value'=>$item_kit_info->category)
								);?>
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label(lang('item_kits_description').':', 'description',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<?php echo form_textarea(array(
									'name'=>'description',
									'id'=>'description',
									'class'=>'form-textarea',
									'value'=>$item_kit_info->description,
									'rows'=>'5',
									'cols'=>'17')
								);?>
							</div>
						</div>
					

						<div class="form-group">
							<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("common_prices_include_tax_help").'">'.lang('common_prices_include_tax').'</a>'.':', 'prices_include_tax',array('class'=>'col-md-3 control-label  wide')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'tax_included',
											'id'=>'tax_included',
											'class'=>'tax-checkboxes md-check',
											'value'=>1,
											'checked'=>($item_kit_info->tax_included || (!$item_kit_info->item_kit_id && $this->config->item('prices_include_tax'))) ? 1 : 0)
										);?>
										<label for="tax_included">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<?php if ($this->Employee->has_module_action_permission('item_kits','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_kit_info->name=="") { ?>
							<div class="form-group">
								<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_cost_price_help").'">'.lang('items_cost_price').' ('.lang('items_without_tax').'</a>'.'):', 'cost_price',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-8">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'cost_price',
										'id'=>'cost_price',
										'value'=>$item_kit_info->cost_price ? to_currency_no_money($item_kit_info->cost_price) : '')
									);?>
								</div>
							</div>
						<?php 
						}
						else
						{
							echo form_hidden('cost_price', $item_kit_info->cost_price);
						}
						?>

						<div class="form-group">
							<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<?php echo form_input(array(
									'class'=>'form-control form-inps',
									'name'=>'unit_price',
									'id'=>'unit_price',
									'value'=>$item_kit_info->unit_price ? to_currency_no_money($item_kit_info->unit_price,10) : '')
								);?>
							</div>
						</div>

						<?php foreach($tiers as $tier) { ?>	
							<div class="form-group">
								<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-8'>
									<?php echo form_input(array(
										'class'=>'form-control form-inps margin-bottom-05',
										'name'=>'item_kit_tier['.$tier->id.']',
										'size'=>'8',
										'value'=> $tier_prices[$tier->id] !== FALSE ? ($tier_prices[$tier->id]->unit_price != NULL ? to_currency_no_money($tier_prices[$tier->id]->unit_price, 10) : $tier_prices[$tier->id]->percent_off): '')
									);?>

									<?php echo form_dropdown('tier_type['.$tier->id.']', $tier_type_options, $tier_prices[$tier->id] !== FALSE && $tier_prices[$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price', 'class="bs-select form-control"');?>
								</div>
							</div>
						<?php } ?>

						<div class="form-group override-commission-container">
							<?php echo form_label(lang('common_override_default_commission').':', '',array('class'=>'col-md-3 control-label wide')); ?>						
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'override_default_commission',
											'id'=>'override_default_commission',
											'class' => 'override_default_commission delete-checkbox md-check',
											'value'=>1,
											'checked'=>(boolean)(($item_kit_info->commission_percent > 0) || ($item_kit_info->commission_fixed > 0))));
										?>
										<label for="override_default_commission">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="commission-container <?php if (!($item_kit_info->commission_percent > 0) && !($item_kit_info->commission_fixed > 0)){echo 'hidden';} ?>">
							<p style="margin-top: 10px;"><?php echo lang('common_commission_help');?></p>
							<div class="form-group">
								<?php echo form_label(lang('reports_commission'), 'commission_value',array('class'=>'col-md-3 control-label wide')); ?>
								<div class='col-md-8'>
									<?php echo form_input(array(
										'name'=>'commission_value',
										'size'=>'8',
										'class'=>'form-control margin-bottom-05 form-inps', 
										'value'=> $item_kit_info->commission_fixed > 0 ? to_quantity($item_kit_info->commission_fixed, FALSE) : to_quantity($item_kit_info->commission_percent, FALSE))
									);?>

								<?php echo form_dropdown('commission_type', array('percent' => lang('common_percentage'), 'fixed' => lang('common_fixed_amount')), $item_kit_info->commission_fixed > 0 ? 'fixed' : 'percent', 'class="bs-select form-control"');?>
								</div>
							</div>
						</div>

						<div class="form-group override-taxes-container">
							<?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_override_default_tax_help").'">'.lang('items_override_default_tax').'</a>'.':', '',array('class'=>'col-md-3 control-label wide')); ?>						
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'override_default_tax',
											'id'=>'override_default_tax',
											'class' => 'override_default_tax_checkbox tax-checkboxes md-check',
											'value'=>1,
											'checked'=>(boolean)$item_kit_info->override_default_tax));
										?>
										<label for="override_default_tax">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<!-- BEGIN TAXES CONTAINER -->
						<div class="tax-container <?php if (!$item_kit_info->override_default_tax){echo 'hidden';} ?>">	
							<div class="form-group">
								<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'col-md-3 control-label wide')); ?>
								<div class="col-md-4">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'tax_names[]',
										'placeholder' => lang('common_tax_name'),
										'id'=>'tax_name_1 noreset',
										'size'=>'8',
										'value'=> isset($item_kit_tax_info[0]['name']) ? $item_kit_tax_info[0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name')))
									);?>
								</div>							
								<div class="col-md-4">
									<div class="input-group">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'tax_percents[]',
											'placeholder' => lang('items_tax_percent'),
											'id'=>'tax_percent_name_1',
											'size'=>'3',
											'value'=> isset($item_kit_tax_info[0]['percent']) ? $item_kit_tax_info[0]['percent'] : '')
										);?>
										<span class="input-group-addon">%</span>
										<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'col-md-3 control-label wide')); ?>
								<div class="col-md-4">
									<?php echo form_input(array(
										'class'=>'form-control form-inps margin10',
										'name'=>'tax_names[]',
										'placeholder' => lang('common_tax_name'),
										'id'=>'tax_name_2',
										'size'=>'8',
										'value'=> isset($item_kit_tax_info[1]['name']) ? $item_kit_tax_info[1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name')))
									);?>
								</div>							
								<div class="col-md-4">
									<div class="input-group">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'tax_percents[]',
											'placeholder' => lang('items_tax_percent'),
											'id'=>'tax_percent_name_2',
											'size'=>'3',
											'value'=> isset($item_kit_tax_info[1]['percent']) ? $item_kit_tax_info[1]['percent'] : '')
										);?>
										<span class="input-group-addon">%</span>
									</div>
									<div class="md-checkbox-inline">
										<div class="md-checkbox">							
											<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_kit_tax_info[1]['cumulative']) && $item_kit_tax_info[1]['cumulative'] ? (boolean)$item_kit_tax_info[1]['cumulative'] : (boolean)$this->config->item('default_tax_2_cumulative'), 'class="cumulative_checkbox md-check" id="tax_cumulatives[]"'); ?>
										    <label for="tax_cumulatives[]">
												<span></span>
												<span class="check"></span>
												<span class="box"></span>
												<?php echo lang('common_cumulative'); ?>
											</label>
										</div>
									</div>
								</div>
							</div>
						
							<div class="col-md-offset-3" style="visibility: <?php echo isset($item_kit_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
								<a href="javascript:void(0);" class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
							</div>
							
							<!-- BEGIN MORE TAXES CONTAINER -->
							<div class="more_taxes_container" style="display: <?php echo isset($item_kit_tax_info[2]['name']) ? 'block' : 'none';?>">
								<div class="form-group">
									<?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'col-md-3 control-label  wide')); ?>
									<div class="col-md-4">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'tax_names[]',
											'placeholder' => lang('common_tax_name'),
											'id'=>'tax_name_3 noreset',
											'size'=>'8',
											'value'=> isset($item_kit_tax_info[2]['name']) ? $item_kit_tax_info[2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name')))
										);?>
									</div>								
									<div class="col-md-4">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'tax_percents[]',
												'placeholder' => lang('items_tax_percent'),
												'id'=>'tax_percent_name_3',
												'size'=>'3',
												'value'=> isset($item_kit_tax_info[2]['percent']) ? $item_kit_tax_info[2]['percent'] : '')
											);?>
											<span class="input-group-addon">%</span>																	
											<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<?php echo form_label(lang('items_tax_4').':', 'tax_percent_4',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-4">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'tax_names[]',
											'placeholder' => lang('common_tax_name'),
											'id'=>'tax_name_4 noreset',
											'size'=>'8',
											'value'=> isset($item_kit_tax_info[3]['name']) ? $item_kit_tax_info[3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name')))
										);?>
									</div>								
									<div class="col-md-4">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'tax_percents[]',
												'placeholder' => lang('items_tax_percent'),
												'id'=>'tax_percent_name_4',
												'size'=>'3',
												'value'=> isset($item_kit_tax_info[3]['percent']) ? $item_kit_tax_info[3]['percent'] : '')
											);?>
											<span class="input-group-addon">%</span>
											<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_tax_5').':', 'tax_percent_5',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-4">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'tax_names[]',
											'placeholder' => lang('common_tax_name'),
											'id'=>'tax_name_5 noreset',
											'size'=>'8',
											'value'=> isset($item_kit_tax_info[4]['name']) ? $item_kit_tax_info[4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name')))
										);?>
									</div>								
									<div class="col-md-4">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'tax_percents[]',
												'placeholder' => lang('items_tax_percent'),
												'id'=>'tax_percent_name_5',
												'size'=>'3',
												'value'=> isset($item_kit_tax_info[4]['percent']) ? $item_kit_tax_info[4]['percent'] : '')
											);?>
											<span class="input-group-addon">%</span>								
											<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
										</div>
									</div>
								</div>				
							</div>		
							<!-- END MORE TAXES CONTAINER -->
							<div class="clear"></div>
						</div>
						<!--END TAXES CONTAINER-->
					</div>
				</div>
			</div>

			<?php if ($this->Location->count_all() > 1) {?>		
				<?php foreach($locations as $location) { ?>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-speech"></i>
								<span class="caption-subject bold">
									<?php echo $location->name; ?>
								</span>					
							</div>
							<div class="tools">
								<a href="" class="collapse" data-original-title="" title=""></a>							
								<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-group override-prices-container">
								<?php echo form_label(lang('items_override_prices').':', '',array('class'=>'col-md-3 control-label wide')); ?>
								<div class="col-md-8">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'locations['.$location->location_id.'][override_prices]',
												'id'=>'override_prices_checkbox',
												'class' => 'override_prices_checkbox tax-checkboxes md-check',
												'value'=>1,
												'checked'=>(boolean)isset($location_item_kits[$location->location_id]) && is_object($location_item_kits[$location->location_id]) && $location_item_kits[$location->location_id]->is_overwritten));
											?>
											<label for="override_prices_checkbox">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="item-kit-location-price-container <?php if ($location_item_kits[$location->location_id] === FALSE || !$location_item_kits[$location->location_id]->is_overwritten){echo 'hidden';} ?>">	
								<div class="form-group">
								<?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').'):', '',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-8">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'locations['.$location->location_id.'][cost_price]',
											'size'=>'8',
											'value'=> $location_item_kits[$location->location_id]->item_kit_id !== '' && $location_item_kits[$location->location_id]->cost_price ? to_currency_no_money($location_item_kits[$location->location_id]->cost_price, 10): '')
										);?>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_unit_price').':', '',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-8">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'locations['.$location->location_id.'][unit_price]',
											'size'=>'8',
											'value'=>$location_item_kits[$location->location_id]->item_kit_id !== '' &&  $location_item_kits[$location->location_id]->unit_price ? to_currency_no_money($location_item_kits[$location->location_id]->unit_price, 10): '')
										);?>
									</div>
								</div>

								<?php foreach($tiers as $tier) { ?>	
									<div class="form-group">
										<?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-md-3 control-label ')); ?>
										<div class="col-md-8">
											<?php echo form_input(array(
												'class'=>'form-control form-inps margin-bottom-05',
												'name'=>'locations['.$location->location_id.'][item_tier]['.$tier->id.']',
												'size'=>'8',
												'value'=> $location_tier_prices[$location->location_id][$tier->id] !== FALSE ? ($location_tier_prices[$location->location_id][$tier->id]->unit_price != NULL ? to_currency_no_money($location_tier_prices[$location->location_id][$tier->id]->unit_price ,10) : $location_tier_prices[$location->location_id][$tier->id]->percent_off): '')
											);?>

											<?php echo form_dropdown('locations['.$location->location_id.'][tier_type]['.$tier->id.']', $tier_type_options, $location_tier_prices[$location->location_id][$tier->id] !== FALSE && $location_tier_prices[$location->location_id][$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price', 'class="bs-select form-control"');?>
										</div>
									</div>
								<?php } ?>
							</div>

							<div class="form-group override-taxes-container">
								<?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-md-3 control-label wide')); ?>
								<div class="col-md-8">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'locations['.$location->location_id.'][override_default_tax]',
												'id'=>'override_default_tax_checkbox1',
												'class' => 'override_default_tax_checkbox tax-checkboxes',
												'value'=>1,
												'checked'=> $location_item_kits[$location->location_id]->item_kit_id !== '' ? (boolean)$location_item_kits[$location->location_id]->override_default_tax: FALSE)
											);?>
											<label for="override_default_tax_checkbox1">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="tax-container <?php if ($location_item_kits[$location->location_id] === FALSE || !$location_item_kits[$location->location_id]->override_default_tax){echo 'hidden';} ?>">	
								<div class="form-group">
									<?php echo form_label(lang('items_tax_1').':', '',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-4">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'locations['.$location->location_id.'][tax_names][]',
											'placeholder' => lang('common_tax_name'),
											'size'=>'8',
											'value' => isset($location_taxes[$location->location_id][0]['name']) ? $location_taxes[$location->location_id][0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
										));?>
									</div>								
									<div class="col-md-4">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'locations['.$location->location_id.'][tax_percents][]',
												'placeholder' => lang('items_tax_percent'),
												'size'=>'3',
												'value' => isset($location_taxes[$location->location_id][0]['percent']) ? $location_taxes[$location->location_id][0]['percent'] : ''
											));?>
											<span class="input-group-addon">%</span>								
											<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<?php echo form_label(lang('items_tax_2').':', '',array('class'=>'col-md-3 control-label wide')); ?>
									<div class="col-md-4">
										<?php echo form_input(array(
											'class'=>'form-control form-inps',
											'name'=>'locations['.$location->location_id.'][tax_names][]',
											'placeholder' => lang('common_tax_name'),
											'size'=>'8',
											'value' => isset($location_taxes[$location->location_id][1]['name']) ? $location_taxes[$location->location_id][1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name'))
										));?>
									</div>								
									<div class="col-md-4">
										<div class="input-group">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'locations['.$location->location_id.'][tax_percents][]',
												'placeholder' => lang('items_tax_percent'),
												'size'=>'3',
												'value' => isset($location_taxes[$location->location_id][1]['percent']) ? $location_taxes[$location->location_id][1]['percent'] : ''
											));?>
											<span class="input-group-addon">%</span>
										</div>
										<div class="md-checkbox-inline">
											<div class="md-checkbox">
												<?php echo form_checkbox('locations['.$location->location_id.'][tax_cumulatives][]', '1', isset($location_taxes[$location->location_id][1]['cumulative']) ? (boolean)$location_taxes[$location->location_id][1]['cumulative'] :(boolean)$this->config->item('default_tax_2_cumulative'), 'id="cumulative_checkbox1" class="cumulative_checkbox"'); ?>
								    			<label for="cumulative_checkbox1">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo lang('common_cumulative'); ?>
												</label>
											</div>
										</div>	
									</div>
								</div>
								
								<div class="col-md-offset-3"  style="visibility: <?php echo isset($item_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
									<a href="javascript:void(0);" class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</a>
								</div>
						
								<div class="more_taxes_container" style="display: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'block' : 'none';?>">
									<div class="form-group">
										<?php echo form_label(lang('items_tax_3').':', '',array('class'=>'col-md-3 control-label wide')); ?>
										<div class="col-md-4">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'placeholder' => lang('common_tax_name'),
												'size'=>'8',
												'value' => isset($location_taxes[$location->location_id][2]['name']) ? $location_taxes[$location->location_id][2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name'))
											));?>
										</div>									
										<div class="col-md-4">
											<div class="input-group">
												<?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'placeholder' => lang('items_tax_percent'),
													'size'=>'3',
													'value' => isset($location_taxes[$location->location_id][2]['percent']) ? $location_taxes[$location->location_id][2]['percent'] : ''
												));?>
												<span class="input-group-addon">%</span>								
												<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
											</div>
										</div>
									</div>

									<div class="form-group">
										<?php echo form_label(lang('items_tax_4').':', '',array('class'=>'col-sm-3 col-md-3 col-lg-2 control-label  wide')); ?>
										<div class="col-md-4">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'placeholder' => lang('common_tax_name'),
												'size'=>'8',
												'value' => isset($location_taxes[$location->location_id][3]['name']) ? $location_taxes[$location->location_id][3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name'))
											));?>
										</div>										
										<div class="col-md-4">
											<div class="input-group">
												<?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'placeholder' => lang('items_tax_percent'),
													'size'=>'3',
													'value' => isset($location_taxes[$location->location_id][3]['percent']) ? $location_taxes[$location->location_id][3]['percent'] : ''
												));?>
												<span class="input-group-addon">%</span>
												<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
											</div>
										</div>
									</div>

									<div class="form-group">
										<?php echo form_label(lang('items_tax_5').':', '',array('class'=>'col-md-3 control-label wide')); ?>
										<div class="col-md-4">
											<?php echo form_input(array(
												'class'=>'form-control form-inps',
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'placeholder' => lang('common_tax_name'),
												'size'=>'8',
												'value' => isset($location_taxes[$location->location_id][4]['name']) ? $location_taxes[$location->location_id][4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name'))
											));?>
										</div>										
										<div class="col-md-4">
											<div class="input-group">
												<?php echo form_input(array(
													'class'=>'form-control form-inps-tax',
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'placeholder' => lang('items_tax_percent'),
													'size'=>'3',
													'value' => isset($location_taxes[$location->location_id][4]['percent']) ? $location_taxes[$location->location_id][4]['percent'] : ''
												));?>
												<span class="input-group-addon">%</span>										
												<?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>						

						</div>
					</div>
				<?php } /*End foreach locations*/ ?>
			<?php } /*End if for multi locations*/?>

			<div class="form-actions pull-right">
				<?php echo form_button(array(
					'type'=>'submit',
					'name'=>'submit',
					'id'=>'submit',
					'content'=>lang('common_submit'),
					'class'=>'submit_button btn btn-primary')
				);?>
			</div>
		<?php echo form_close(); ?>
	</div>




	<script type='text/javascript'>
	  

		$( "#item" ).autocomplete({
			source: '<?php echo site_url("items/item_search"); ?>',
			delay: 300,
			autoFocus: false,
			minLength: 0,
			select: function( event, ui ) 
			{	
				$( "#item" ).val("");
				if ($("#item_kit_item_"+ui.item.value).length ==1)
				{
					$.post('<?php echo site_url("items/does_have_subcategory"); ?>',{item_id: ui.item.value	},
					function(data, status){						
						if(JSON.parse(data)){
							toastr.error("Para los productos con subcategoría no están disponibles los kits", <?php echo json_encode(lang('common_error')); ?>);
						}else{
							$("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);

						}
						
					});
					
				}
				else
				{
					$.post('<?php echo site_url("items/does_have_subcategory"); ?>',{item_id: ui.item.value	},
					function(data, status){						
						if(JSON.parse(data)){
							toastr.error("Para los productos con subcategoría no están disponibles los kits", <?php echo json_encode(lang('common_error')); ?>);
						}else{
							$("#column").hide();
							$("#item_kit_items").append("<tr class='item_kit_item_row'><td><a  href='#' onclick='return deleteItemKitRow(this);'><i class='fa fa-trash-o fa-2x font-red'></i></a></td><td>"+ui.item.label+"</td><td><input class='quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
				
						}
						
					});
					
				}
			
				calculateSuggestedPrices();
			
				return false;
			}
		});

		//validation and submit handling
		$(document).ready(function()
		{
		    setTimeout(function(){$(":input:visible:first","#item_kit_form").focus();},100);
			$( "#category" ).autocomplete({
				source: "<?php echo site_url('items/suggest_category');?>",
				delay: 300,
				autoFocus: false,
				minLength: 0
			});
			
			$(".override_default_tax_checkbox, .override_prices_checkbox, .override_default_commission").change(function()
			{
				$(this).parent().parent().parent().parent().next().toggleClass('hidden')
			});	

			$('#item_kit_form').validate({
				submitHandler:function(form)
				{
					$.post('<?php echo site_url("item_kits/check_duplicate");?>', {term: $('#name').val()},function(data) {
					<?php if(!$item_kit_info->item_kit_id) { ?>
					if(data.duplicate)
						{
							if(confirm(<?php echo json_encode(lang('items_duplicate_exists'));?>))
							{
								doItemKitSubmit(form);
							}
							else 
							{
								return false;
							}
						}
					<?php } else ?>		
						{
							doItemKitSubmit(form);
						}} , "json")
						.error(function() {
						});
				

				},
			errorClass: "text-danger",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
			},
				rules:
					{
						<?php foreach($tiers as $tier) { ?>
							"<?php echo 'item_kit_tier['.$tier->id.']'; ?>":
							{
								number: true
							},
						<?php } ?>
				
						<?php foreach($locations as $location) { ?>
							"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
							{
								number: true
							},
							"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
							{
								number: true
							},			
							<?php foreach($tiers as $tier) { ?>
								"<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>":
								{
									number: true
								},
							<?php } ?>				
						<?php } ?>					
						name:"required",
						category:"required",
						unit_price: "number",
						cost_price: "number"
					},
					messages:
					{
						<?php foreach($tiers as $tier) { ?>
							"<?php echo 'item_kit_tier['.$tier->id.']'; ?>":
							{
								number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
							},
						<?php } ?>
				
						<?php foreach($locations as $location) { ?>
							"<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>":
							{
								number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
							},
							"<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>":
							{
								number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
							},			
							<?php foreach($tiers as $tier) { ?>
								"<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>":
								{
									number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
								},
							<?php } ?>				
						<?php } ?>
						name:<?php echo json_encode(lang('items_name_required')); ?>,
						category:<?php echo json_encode(lang('items_category_required')); ?>,
						unit_price: <?php echo json_encode(lang('items_unit_price_number')); ?>,
						cost_price: <?php echo json_encode(lang('items_cost_price_number')); ?>
					}
			});
		});

		function deleteItemKitRow(link)
		{
			$(link).parent().parent().remove();
			//console.log($('#item_kit_items >tbody >tr').length);
			if ($('#item_kit_items >tbody >tr').length == 2)
			{
				$("#column").show();
			}
			
			calculateSuggestedPrices();
			return false;
		}

		function calculateSuggestedPrices()
		{
			var items = [];
			$("#item_kit_items").find('input').each(function(index, element)
			{
				var quantity = parseFloat($(element).val());
				var item_id = $(element).attr('id').substring($(element).attr('id').lastIndexOf('_') + 1);
			
				items.push({
					item_id: item_id,
					quantity: quantity
				});
			});
			calculateSuggestedPrices.totalCostOfItems = 0;
			calculateSuggestedPrices.totalPriceOfItems = 0;
			getPrices(items, 0);
		}

		function getPrices(items, index)
		{
			if (index > items.length -1)
			{
				$("#unit_price").val(calculateSuggestedPrices.totalPriceOfItems);
				$("#cost_price").val(calculateSuggestedPrices.totalCostOfItems);
			}
			else
			{
				$.get('<?php echo site_url("items/get_info");?>'+'/'+items[index]['item_id'], {}, function(item_info)
				{
					calculateSuggestedPrices.totalPriceOfItems+=items[index]['quantity'] * parseFloat(item_info.unit_price);
					calculateSuggestedPrices.totalCostOfItems+=items[index]['quantity'] * parseFloat(item_info.cost_price);
					getPrices(items, index+1);
				}, 'json');
			}
		}
		
		var submitting = false;
		function doItemKitSubmit(form)
		{	
			$("#form").plainOverlay('show');
			if (submitting) return;
			submitting = true;
			$(form).ajaxSubmit({
			success:function(response)
		    {
				$('#form').plainOverlay('hide');
				submitting = false;
				if(response.success)
				{
					toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.item_kit_id);
					$("html, body").animate({ scrollTop: 0 }, "slow");
				}
				else
				{
					toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
				}
				
				
				<?php if(!$item_kit_info->item_kit_id) { ?>
				//If we have a new item, make sure we hide the tax containers to "reset"
				$(".tax-container").addClass('hidden');
				$(".item-kit-location-price-container").addClass('hidden');
				$('.commission-container').addClass('hidden');
				$('.item_kit_item_row').remove();
				<?php } ?>

				if(response.redirect==2 && response.success)
				{
					window.location.href = '<?php echo site_url('item_kits'); ?>'
				}
			},
			<?php if(!$item_kit_info->item_kit_id) { ?>
			resetForm: true,
			<?php } ?>
			dataType:'json'
			});
		}	
	</script>

<?php $this->load->view("partial/footer"); ?>