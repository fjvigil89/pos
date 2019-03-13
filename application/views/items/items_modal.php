<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                        <?php echo lang("items_basic_information"); ?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
	            	<div class="col-sm-5 margin-bottom-05">	            		
						<?php echo $item_info->image_id ? img(array('src' => site_url('app_files/view/'.$item_info->image_id),'class'=>'img-responsive img-thumbnail', 'width'=>'100%', 'height'=>'100%')) : img(array('src' => base_url().'img/no-image.png', 'class'=>'img-responsive img-thumbnail', 'width'=>'100%', 'height'=>'100%', 'id'=>'image_empty')); ?>
					</div>
					<div class="col-sm-7 margin-bottom-05">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" width="100%">
								<tr> <td><strong><?php echo lang('items_item_number'); ?></strong></td> <td> <?php echo $item_info->item_number; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_product_id'); ?></strong></td> <td> <?php echo $item_info->product_id; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_name'); ?></strong></td> <td> <?php echo $item_info->name; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_category'); ?></strong></td> <td> <?php echo $item_info->category; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_size'); ?></strong></td> <td> <?php echo $item_info->size; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_supplier'); ?></strong></td> 
									<td> <?php if (isset($supplier) && $supplier != '' ){
											echo $supplier;
										}else {
										   echo lang('items_none');  
										}
										?></td>
								</tr>
								<?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="")	{ ?>
								<tr> <td><strong><?php echo lang('items_cost_price'); ?></strong></td> <td> <?php echo to_currency($item_info->cost_price, 10); ?></td></tr>
								<?php } ?>
								<tr> <td><strong><?php echo lang('items_unit_price'); ?></strong></td> <td> <?php echo to_currency($item_info->unit_price, 10); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_price_tax_include'); ?></strong></td> <td> <?php echo to_currency($item_price_with_tax, 10); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_promo_price'); ?></strong></td> <td> <?php echo to_currency($item_info->promo_price, 10); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_quantity'); ?></strong></td> <td> <?php echo to_quantity($item_location_info->quantity); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_reorder_level'); ?></strong></td> <td> <?php echo to_quantity($reorder_level); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_location'); ?></strong></td> <td> <?php echo $item_location_info->location; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_description'); ?></strong></td> <td> <?php echo $item_info->description; ?></td></tr>
								<tr> <td><strong><?php echo lang('items_allow_alt_desciption'); ?></strong></td> <td> <?php echo $item_info->allow_alt_description ? lang('common_yes') : lang('common_no'); ?></td></tr>
								<tr> <td><strong><?php echo lang('items_is_serialized'); ?></strong></td> <td> <?php echo $item_info->is_serialized ? lang('common_yes') : lang('common_no'); ?></td></tr>
							</table>
						</div>
					</div>						
				</div>
				<?php if (count($tiers)>0): ?>
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-info-circle"></i> 
								<span class="caption-subject bold">
									<?php echo "Tiers"; ?>
								</span>
							</div>		               
						</div>
						<div class="portlet-body">
						
						<table class="table table-bordered table-hover table-striped" width="1200px">							
							<thead>
								<tr>
									<th ><?php echo "Tier"?></th>
									<th ><?php echo "Precio"?></th>
								</tr>							
							</thead>
							<tbody>
							<?php foreach ($tiers as $tier) :?>
							
								<tr>
									<td style="text-align: center;"><?php echo $tier["name"] ?></td>
									<td style="text-align: center;"><?php echo $tier["precio"] ?></td>
									
								</tr>
						<?php endforeach;?>
							</tbody>
						</table>
						</div>
		            
        			</div>
											
				<?php endif; ?>	

				<div class="portlet box grey-cascade">
		            <div class="portlet-title">
		                <div class="caption">
		                    <i class="fa fa-info-circle"></i> 
		                    <span class="caption-subject bold">
		                        <?php echo lang('receivings_list_of_suspended'); ?>
		                    </span>
		                </div>		               
		            </div>
		            <div class="portlet-body">
	            	<table class="table table-bordered table-hover table-striped" width="1200px">							
					<thead>
						<tr>
							<th><?php echo lang('receivings_id');?></th>
							<th><?php echo lang('items_quantity');?></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($suspended_receivings as $receiving_item) {?>
							<tr>
								<td style="text-align: center;"><?php echo anchor('receivings/receipt/'.$receiving_item['receiving_id'], 'RECV '.$receiving_item['receiving_id'], array('target' => '_blank'));?></td>
								<td style="text-align: center;"><?php echo to_quantity($receiving_item['quantity_purchased']);?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
		            
        		</div>
			
				<?php if ($this->config->item('subcategory_of_items') && $item_info->subcategory): ?>
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-info-circle"></i> 
								<span class="caption-subject bold">
									<?php echo lang('items_subcategory')."(s)"; ?>
								</span>
							</div>		               
						</div>
						<div class="portlet-body">
						<table class="table table-bordered table-hover table-striped" width="1200px">							
						<thead>
							<tr>
								<th ><?php echo $this->config->item("custom_subcategory1_name");?></th>
								<th ><?php echo $this->config->item("custom_subcategory2_name");?></th>
								<th ><?php echo "Cantidad";?></th>
							</tr>
							</thead>
							<tbody>
							<?php foreach($subcategory_info as $subcategoria) {?>
								<tr>
									<td style="text-align: center;"><?php echo $subcategoria->custom1 ?></td>
									<td style="text-align: center;"><?php echo $subcategoria->custom2 ?></td>
									<td style="text-align: center;"><?php echo (float)$subcategoria->quantity ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						</div>
        			</div>
											
				<?php endif; ?>	




			</div>
		</div>
	</div>
</div>



