<?php $this->load->view("partial/header"); ?>

<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class='fa fa-pencil'></i>
        <?php if(!$item_info->item_id || (isset($is_clone) && $is_clone)) { echo lang($controller_name.'_new'); } else { echo lang($controller_name.'_update'); } ?>

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


<?php echo form_open_multipart('items/save/'.(!isset($is_clone) ? $item_info->item_id : ''),array('id'=>'item_form','class'=>'form-horizontal ')); ?>
<div id="form">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-book-open"></i>
                <span class="caption-subject bold">
                    <?php echo lang("items_basic_information"); ?>
                </span>
            </div>
            <div class="tools">
                <a href="" class="collapse" data-original-title="" title=""></a>
                <a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>
            </div>
        </div>

        <div class="portlet-body form">

            <div class="form-body">

                <div class="text-center">
                    <?php
							if (isset($prev_item_id) && $prev_item_id)
							{
								echo anchor('items/view/'.$prev_item_id, "<i class='fa fa-chevron-left'></i> &nbsp;".lang('items_prev_item'), array('class' => 'btn btn-circle btn-sm green-jungle'));
							}
							?>

                    <?php
							if (isset($next_item_id) && $next_item_id)
							{
								echo anchor('items/view/'.$next_item_id, lang('items_next_item')."&nbsp; <i class='fa fa-chevron-right'></i>", array('class' => 'btn btn-circle btn-sm green-jungle'));
							}
							?>
                </div>

                <h4><?php echo lang('common_fields_required_message'); ?></h4>

                <div class="form-group">
                    <?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("items_item_number_help").'">'.lang('items_item_number').'</a>'.':', 'item_number',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'item_number',
									'id'=>'item_number',
									'class'=>'form-control form-inps',
									'value'=>$item_info->item_number)
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("items_product_id_help").'">'.lang('items_product_id').'</a>'.':', 'product_id',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'product_id',
									'id'=>'product_id',
									'class'=>'form-control form-inps',
									'value'=>$item_info->product_id)
								);?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_label(lang('items_additional_item_numbers').':', 'additional_item_numbers',array('class'=>'col-md-3 control-label ')); ?>
                        <div class="col-md-8 table-responsive">
                            <table id="additional_item_numbers" class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('reports_item_number'); ?></th>
                                        <th><?php echo lang('common_delete'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($additional_item_numbers) && $additional_item_numbers) {?>
                                    <?php foreach($additional_item_numbers->result() as $additional_item_number) { ?>
                                    <tr>
                                        <td><input type="text" class="form-control form-inps" size="50"
                                                name="additional_item_numbers[]"
                                                value="<?php echo H($additional_item_number->item_number); ?>" /></td>
                                        <td><a class="btn delete_item_number"
                                                href="javascript:void(0);"><?php echo lang('common_delete'); ?></a></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <a href="javascript:void(0);" id="add_addtional_item_number"
                                class="btn btn-circle default btn-xs"><?php echo lang('items_add_item_number'); ?></a>
                            <br /><br />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('items_name').':', 'name',array('class'=>'col-md-3 control-label requireds wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'name',
									'id'=>'name',
									'class'=>'form-control form-inps',
									'value'=>$item_info->name)
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_category').':', 'category',array('class'=>'col-md-3 control-label requireds wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'category',
									'id'=>'category',
									'class'=>'form-control form-inps',
									'value'=>$item_info->category)
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_size').':', 'size',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'size',
									'id'=>'size',
									'class'=>'form-control form-inps',
									'value'=>$item_info->size)
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_colour').':', 'colour',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'colour',
									'id'=>'colour',
									'class'=>'form-control form-inps',
									'value'=>$item_info->colour)
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_model').':', 'model',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'model',
									'id'=>'model',
									'class'=>'form-control form-inps',
									'value'=>$item_info->model)
								);?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('items_brand').':', 'model',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'marca',
									'id'=>'marca',
									'class'=>'form-control form-inps',
									'value'=>$item_info->marca)
								);?>
                    </div>
                </div>

                <?php
						for ($i = 1; $i <= 10; ++$i)
						{
						?>
                <?php
							if($this->config->item('custom'.$i.'_name') != null)
							{
								$item_arr = (array)$item_info;
							?>
                <div class="form-group form-group-sm">
                    <?php echo form_label($this->config->item('custom'.$i.'_name').':', 'custom'.$i, array('class'=>'control-label col-xs-3')); ?>
                    <div class='col-xs-8'>
                        <?php echo form_input(array(
												'name'=>'custom'.$i,
												'id'=>'custom'.$i,
												'class'=>'form-control input-sm',
												'value'=>$item_arr['custom'.$i])
												);?>
                    </div>
                </div>
                <?php
							}
						}
						?>

                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'col-md-3 control-label ')); ?>
                        <div class="col-md-8 table-responsive">
                            <table id="additional_suppliers" class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('suppliers_supplier'); ?></th>
                                        <th><?php echo lang('suppliers_price'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($suppliers) && $suppliers) {?>
                                    <?php foreach($selected_supplier as $value){ ?>
                                    <tr>
                                        <td><?php echo form_dropdown("supplier_id[$value->supplier_id]", $suppliers,$value->supplier_id,'class="bs-select form-control"');?>
                                        </td>
                                        <td><input type="text" class="form-control form-inps price_suppliers"
                                                id="price_suppliers<?php echo $value->supplier_id;?>" size="50"
                                                rel="<?php echo $value->supplier_id;?>"
                                                value="<?php echo to_currency_no_money($value->price_suppliers); ?>"
                                                name="price_suppliers[]" placeholder="Precio del proveedor" /></td>
                                        <td><a class="btn delete_item_supplier" rel="<?php echo $value->supplier_id;?>"
                                                href="javascript:void(0);"><?php echo lang('common_delete'); ?></a></td>
                                        <td><a class="btn asign_price" rel="<?php echo $value->supplier_id;?>"
                                                href="javascript:void(0);"><?php echo lang('common_asign_price'); ?></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <a href="javascript:void(0);" id="add_addtional_suppliers"
                                class="btn btn-circle default btn-xs"><?php echo lang('suppliers_new'); ?></a>
                            <br /><br />
                        </div>
                    </div>
                </div>


                <div class="form-group reorder-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
                    <?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_reorder_level_help").'">'.lang('items_reorder_level').'</a>'.':', 'reorder_level',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_input(array(
									'name'=>'reorder_level',
									'id'=>'reorder_level',
									'class'=>'form-control form-inps',
									'value'=>$item_info->reorder_level ? to_quantity($item_info->reorder_level) :'')
								);?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('items_expiration_date').':', 'items_expiration_date',array('class'=>'col-md-3 control-label text-info wide')); ?>
                    <div class="col-md-8">
                        <div class="input-group date" id="date_items_expiration_date"
                            data-date="<?php echo $item_info->expiration_date ? date(get_date_format(), strtotime($item_info->expiration_date)) : ''; ?>"
                            data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <?php echo form_input(array(
									'name'=>'expiration_date',
									'id'=>'expiration_date',
									'class'=>'form-control',
									'value'=>$item_info->expiration_date ? date(get_date_format(), strtotime($item_info->expiration_date)) : '')
								);?>
                        </div>
                        <!--<?php var_dump(strtoupper(get_js_date_format())) ?>
							<br/>
							<?php var_dump($item_info->start_date) ?> -->
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label(lang('items_expiration_day').':', 'items_expiration_day',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-2">

                        <?php
					$array_day=array('select_day'=>'select day','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31');
						echo form_dropdown('expiration_day',$array_day, ($item_info->expiration_day) ? $item_info->expiration_day : 'select day'   , 'class="bs-select form-control"');?>

                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_description').':', 'description',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <?php echo form_textarea(array(
									'name'=>'description',
									'id'=>'description',
									'value'=>$item_info->description,
									'class'=>'form-control form-textarea',
									'rows'=>'5',
									'cols'=>'17')
								);?>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label('<a class="help_config_options   tooltips"  title="'.lang("common_prices_include_tax_help").'">'.lang('common_prices_include_tax').'</a>'.':', 'prices_include_tax',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
											'name'=>'tax_included',
											'id'=>'tax_included',
											'class'=>'delete-checkbox md-check',
											'value'=>1,
											'checked'=>($item_info->tax_included || (!$item_info->item_id && $this->config->item('prices_include_tax'))) ? 1 : 0)
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

                <div class="form-group">
                    <?php echo form_label('<a class="help_config_options   tooltips"  title="'.lang("items_is_service_help").'">'.lang('items_is_service').'</a>'.':', 'is_service',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
											'name'=>'is_service',
											'id'=>'is_service',
											'class'=>'delete-checkbox md-check',
											'value'=>1,
											'checked'=>($item_info->is_service) ? 1 : 0)
										);?>
                                <label for="is_service">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
											'name'=>'allow_alt_description',
											'id'=>'allow_alt_description',
											'class'=>'delete-checkbox md-check',
											'value'=>1,
											'checked'=>($item_info->allow_alt_description)? 1  :0)
										);?>
                                <label for="allow_alt_description">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
											'name'=>'is_serialized',
											'id'=>'is_serialized',
											'class'=>'delete-checkbox md-check',
											'value'=>1,
											'checked'=>($item_info->is_serialized)? 1 : 0)
										);?>
                                <label for="is_serialized">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" id="panel_seriales"
                    style='<?php echo $item_info->is_serialized==1 ? "" :"display:none"?> '>
                    <div class="form-group">
                        <?php echo form_label('Seriales:', 'additional_item_serial',array('class'=>'col-md-3 control-label ')); ?>
                        <div class="col-md-8 table-responsive">
                            <table id="additional_item_seriales" class="table">
                                <thead>
                                    <tr>
                                        <th>Número de serie</th>
                                        <th><?php echo lang('common_delete'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($seriales_item) and $seriales_item) {?>
                                    <?php foreach($seriales_item->result() as $seriale) { ?>
                                    <tr>
                                        <?php if ($this->Employee->has_module_action_permission('items','delete_serial', $this->Employee->get_logged_in_employee_info()->person_id) ) { ?>
                                        <td><input type="text" class="form-control form-inps" size="50"
                                                name="additional_item_seriales[]"
                                                value="<?php echo H($seriale->item_serial); ?>" /></td>
                                        <td><a class="btn delete_item_serial"
                                                href="javascript:void(0);"><?php echo lang('common_delete'); ?></a></td>
                                        <?php }
														else{?>
                                        <td align="center"><?=  H($seriale->item_serial); ?> </td>
                                        <td></td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <a href="javascript:void(0);" id="add_addtional_item_serial"
                                class="btn btn-circle default btn-xs">Añadir número de serie</a>
                            <br /><br />
                        </div>
                    </div>
                </div>
                <?php if($this->config->item("monitor_product_rank")==1): ?>
                <div class="form-group">
                    <?php echo form_label(lang('items_activate_range').':', 'activate_range',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
												'name'=>'activate_range',
												'id'=>'activate_range',
												'class'=>'delete-checkbox md-check',
												'value'=>1,
												'checked'=>($item_info->activate_range)? 1 : 0)
											);?>
                                <label for="activate_range">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <?php echo form_label(lang('items_images').':', 'image_id',array('class'=>'col-md-3 control-label')); ?>
                    <div class="col-md-8">
                        <?php echo form_upload(array(
									'name'=>'image_id',
									'id'=>'image_id',
									'class' => 'file form-control',
									'multiple' => "false",
									'data-show-upload' => 'false',
									'data-show-remove' => 'false',
									'value'=>$item_info->image_id));
								?>
                    </div>
                </div>

                <?php if($item_info->image_id) {  ?>
                <div class="form-group">
                    <?php echo form_label(lang('items_del_image').':', 'del_image',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
												'name'=>'del_image',
												'id'=>'del_image',
												'class'=>'delete-checkbox',
												'value'=>1
											));?>
                                <label for="del_image">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if ($this->config->item('subcategory_of_items')){?>
                <div class="form-group">
                    <?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_subcategory_item_help").'">'.lang('config_subcategory_item').'</a>'.':', 'company_logo', array('class'=>'col-md-3 control-label')); ?>

                    <div class="col-md-8">
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox(array(
												'name'=>'subcategory',
												'id'=>'subcategory',
												'class'=>'delete-checkbox md-check',
												'value'=>1,
												'checked'=>($item_info->subcategory)? 1 : 0)
											);?>
                                <label for="subcategory">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }?>

            </div>
        </div>
    </div>

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-speech"></i>
                <span class="caption-subject bold">
                    <?php echo lang("items_pricing_and_inventory"); ?>
                </span>
            </div>
            <div class="tools">
                <a href="" class="collapse" data-original-title="" title=""></a>
                <a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>
            </div>
        </div>
        <div class="portlet-body form">

            <?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="") { ?>
            <div class="form-group">
                <?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("items_cost_price_help").'">'.lang('items_cost_price').'</a>'.' ('.lang('items_without_tax').')'.':', 'cost_price',array('class'=>'col-md-3 control-label requireds wide')); ?>
                <div class="col-md-8">
                    <?php echo form_input(array(
									'name'=>'cost_price',
									'size'=>'8',
									'id'=>'cost_price',
									'class'=>'form-control form-inps',
									'value'=>$item_info->cost_price ? to_currency_no_money($item_info->cost_price,10) : '')
								);?>
                </div>
            </div>
            <?php
					}
					else
					{
						echo form_hidden('cost_price', $item_info->cost_price);
					}
					?>
            <div class="form-group">
                <?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("items_again_help").'">'.lang('items_again').' ('."%".'):'.'</a>', 'items_discount',array('class'=>'col-md-3 control-label  wide')); ?>
                <div class="col-md-6">
                    <?php echo form_input(array(
								'name'=>'items_discount',
								'size'=>'8',
								'id'=>'items_discount',
								'class'=>'form-control form-inps',
									'value'=>$item_info->items_discount ? to_currency_no_money($item_info->items_discount, 10) : ''
							));?>
                </div>
                <a class="btn items_discount" href="javascript:void(0);"><?php echo lang('items_discount'); ?></a>
            </div>


            <div class="form-group">
                <?php echo form_label(lang('items_unit_price').' ('.lang('items_without_tax').'):', 'unit_price',array('class'=>'col-md-3 control-label requireds wide')); ?>
                <div class="col-md-8">
                    <?php echo form_input(array(
								'name'=>'unit_price',
								'size'=>'8',
								'id'=>'unit_price',
								'class'=>'form-control form-inps',
								'value'=>$item_info->unit_price ? to_currency_no_money($item_info->unit_price, 10) : '')
							);?>
                </div>
            </div>

            <?php foreach($tiers as $tier) { ?>
            <div class="form-group">
                <?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-8">
                    <?php echo form_input(array(
									'name'=>'item_tier['.$tier->id.']',
									'size'=>'8',
									'class'=>'form-control form-inps margin-bottom-05',
									'value'=> $tier_prices[$tier->id] !== FALSE ? ($tier_prices[$tier->id]->unit_price != 0 ? to_currency_no_money($tier_prices[$tier->id]->unit_price, 10) : $tier_prices[$tier->id]->percent_off): '')
								);?>
                    <?php echo form_dropdown('tier_type['.$tier->id.']', $tier_type_options, $tier_prices[$tier->id] !== FALSE && $tier_prices[$tier->id]->unit_price==0 ? 'percent_off' : 'unit_price', 'class="bs-select form-control"');?>
                </div>
            </div>
            <?php } ?>


            <div class="form-group">
                <?php echo form_label(lang('items_promo_price').':', 'promo_price',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-8">
                    <?php echo form_input(array(
								'name'=>'promo_price',
								'size'=>'8',
								'class'=>'form-control form-inps',
								'id'=>'promo_price',
								'value'=> $item_info->promo_price ? to_currency_no_money($item_info->promo_price,10) : '')
							);?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label(lang('items_promo_quantity').':', 'promo_quantity',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-8">
                    <?php echo form_input(array(
								'name'=>'promo_quantity',
								'size'=>'8',
							'class'=>'form-control form-inps',
								'id'=>'promo_quantity',
														'value'=> $item_info->promo_quantity ? $item_info->promo_quantity : ''
														)
							);?>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label(lang('items_promo_start_date').':', 'start_date',array('class'=>'col-md-3 control-label text-info wide')); ?>
                <div class="col-md-8">
                    <div class="input-group date" id="date_promo_start"
                        data-date="<?php echo $item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : ''; ?>"
                        data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <?php echo form_input(array(
									'name'=>'start_date',
									'id'=>'start_date',
									'class'=>'form-control',
									'value'=>$item_info->start_date ? date(get_date_format(), strtotime($item_info->start_date)) : '')
								);?>
                    </div>
                    <!--<?php var_dump(strtoupper(get_js_date_format())) ?>
							<br/>
							<?php var_dump($item_info->start_date) ?> -->
                </div>
            </div>

            <div class="form-group offset1">
                <?php echo form_label(lang('items_promo_end_date').':', 'end_date',array('class'=>'col-md-3 control-label text-info wide')); ?>
                <div class="col-md-8">
                    <div class="input-group date" id="date_promo_end"
                        data-date="<?php echo $item_info->end_date ? date(get_date_format(), strtotime($item_info->end_date)) : ''; ?>"
                        data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <?php echo form_input(array(
									'name'=>'end_date',
									'id'=>'end_date',
									'class'=>'form-control',
									'value'=>$item_info->end_date ? date(get_date_format(), strtotime($item_info->end_date)) : '')
								);?>
                    </div>
                </div>
            </div>

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
										'checked'=>(boolean)(($item_info->commission_percent > 0) || ($item_info->commission_fixed > 0))));
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

            <div
                class="commission-container <?php if (!($item_info->commission_percent > 0) && !($item_info->commission_fixed > 0)){echo 'hidden';} ?>">
                <p style="margin-top: 10px;"><?php echo lang('common_commission_help');?></p>
                <div class="form-group">
                    <?php echo form_label(lang('reports_commission'), 'commission_value',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class='col-md-8'>
                        <?php echo form_input(array(
									'name'=>'commission_value',
									'size'=>'8',
									'class'=>'form-control margin-bottom-05 form-inps',
									'value'=> $item_info->commission_fixed > 0 ? to_quantity($item_info->commission_fixed, FALSE) : to_quantity($item_info->commission_percent, FALSE))
								);?>

                        <?php echo form_dropdown('commission_type', array('percent' => lang('common_percentage'), 'fixed' => lang('common_fixed_amount')), $item_info->commission_fixed > 0 ? 'fixed' : 'percent', 'class="bs-select form-control"');?>
                    </div>
                </div>
            </div>

            <div class="form-group override-taxes-container">
                <?php echo form_label('<a class="help_config_options  tooltips"  title="'.lang("items_override_default_tax_help").'">'.lang('items_override_default_tax').'</a>'.':', '',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-8">
                    <div class="md-checkbox-inline">
                        <div class="md-checkbox">
                            <?php echo form_checkbox(array(
										'name'=>'override_default_tax',
										'id'=>'override_default_tax',
										'class' => 'override_default_tax_checkbox delete-checkbox md-check',
										'value'=>1,
										'checked'=>(boolean)$item_info->override_default_tax));
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

            <!-- <div class="prueba"></div> -->

            <!-- BEGIN TAX-CONTAINER -->
            <div class="tax-container <?php if (!$item_info->override_default_tax){echo 'hidden';} ?>">
                <div class="form-group">
                    <?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'col-md-3 control-label wide')); ?>
                    <div class="col-md-4">
                        <?php echo form_input(array(
									'name'=>'tax_names[]',
									'id'=>'tax_name_1 noreset',
									'size'=>'8',
									'class'=>'form-control form-inps',
									'placeholder' => lang('common_tax_name'),
									'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name')))
								);?>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_1',
										'size'=>'3',
										'class'=>'form-control form-inps',
										'placeholder' => lang('items_tax_percent'),
										'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : '')
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
									'name'=>'tax_names[]',
									'id'=>'tax_name_2',
									'size'=>'8',
									'class'=>'form-control form-inps',
									'placeholder' => lang('common_tax_name'),
									'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : ($this->Location->get_info_for_key('default_tax_2_name') ? $this->Location->get_info_for_key('default_tax_2_name') : $this->config->item('default_tax_2_name')))
								);?>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <?php echo form_input(array(
										'name'=>'tax_percents[]',
										'id'=>'tax_percent_name_2',
										'size'=>'3',
										'class'=>'form-control form-inps',
										'placeholder' => lang('items_tax_percent'),
										'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : '')
									);?>
                            <span class="input-group-addon">%</span>
                        </div>
                        <div class="md-checkbox-inline">
                            <div class="md-checkbox">
                                <?php echo form_checkbox('tax_cumulatives[]', '1', (isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative']) ? (boolean)$item_tax_info[1]['cumulative'] : (boolean)$this->config->item('default_tax_2_cumulative'), 'class="cumulative_checkbox md-check" id="tax_cumulatives[]"'); ?>
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

                <div class="col-md-offset-3"
                    style="visibility: <?php echo isset($item_tax_info[2]['name']) ? 'hidden' : 'visible';?>">
                    <a href="javascript:void(0);"
                        class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?>
                        &raquo;</a>
                </div>
                <!-- BEGIN MORE TAXES CONTAINER -->
                <div class="more_taxes_container"
                    style="display: <?php echo isset($item_tax_info[2]['name']) ? 'block' : 'none';?>">
                    <div class="form-group">
                        <?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'col-md-3 control-label wide')); ?>
                        <div class="col-md-4">
                            <?php echo form_input(array(
										'name'=>'tax_names[]',
										'id'=>'tax_name_3 noreset',
										'size'=>'8',
										'class'=>'form-control form-inps margin10',
										'placeholder' => lang('common_tax_name'),
										'value'=> isset($item_tax_info[2]['name']) ? $item_tax_info[2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name')))
									);?>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <?php echo form_input(array(
											'name'=>'tax_percents[]',
											'id'=>'tax_percent_name_3',
											'size'=>'3',
											'class'=>'form-control form-inps',
											'placeholder' => lang('items_tax_percent'),
											'value'=> isset($item_tax_info[2]['percent']) ? $item_tax_info[2]['percent'] : '')
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
										'name'=>'tax_names[]',
										'id'=>'tax_name_4 noreset',
										'size'=>'8',
										'class'=>'form-control form-inps',
										'placeholder' => lang('common_tax_name'),
										'value'=> isset($item_tax_info[3]['name']) ? $item_tax_info[3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name')))
									);?>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <?php echo form_input(array(
											'name'=>'tax_percents[]',
											'id'=>'tax_percent_name_4',
											'size'=>'3',
											'class'=>'form-control form-inps',
											'placeholder' => lang('items_tax_percent'),
											'value'=> isset($item_tax_info[3]['percent']) ? $item_tax_info[3]['percent'] : '')
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
										'name'=>'tax_names[]',
										'id'=>'tax_name_5 noreset',
										'size'=>'8',
										'class'=>'form-control form-inps',
										'placeholder' => lang('common_tax_name'),
										'value'=> isset($item_tax_info[4]['name']) ? $item_tax_info[4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name')))
									);?>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <?php echo form_input(array(
											'name'=>'tax_percents[]',
											'id'=>'tax_percent_name_5',
											'size'=>'3',
											'class'=>'form-control form-inps-tax margin10',
											'placeholder' => lang('items_tax_percent'),
											'value'=> isset($item_tax_info[4]['percent']) ? $item_tax_info[4]['percent'] : '')
										);?>
                                <span class="input-group-addon">%</span>
                                <?php echo form_hidden('tax_cumulatives[]', '0'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END MORE TAXES CONTAINER-->
                <div class="clear"></div>
            </div>
            <!--END TAXES CONTAINER-->

            <br>
            <div class="form-group ">
                <?php echo form_label('<a class="help_config_options  tooltips"  title="'.lang("items_has_sales_units_help").'">'.lang('items_has_sales_units').'</a>'.':', '',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-8">
                    <div class="md-checkbox-inline">
                        <div class="md-checkbox">
                            <?php echo form_checkbox(array(
										'name'=>'has_sales_units',
										'id'=>'has_sales_units',
										'class' => ' delete-checkbox md-check',
										'value'=>1,
										'checked'=>(boolean)$item_info->has_sales_units));
									?>
                            <label for="has_sales_units">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" <?php echo $item_info->has_sales_units == 1 ? "" : "hidden" ?>" id="panel_has_sales_units">
                <div class="form-group ">
                    <?php echo form_label(lang("items_unit_quantity").":", 'quantity_unit_sale',array('class'=>'col-md-3 control-label text-info wide')); ?>
                    <div class="col-md-2">
                        <div class="input-group date">
                            <span class="input-group-addon">
                                <i class="fa fa-calculator"></i>
                            </span>
                            <?php echo form_input(array(
											'name'=>'quantity_unit_sale',
											'type'=>'number',
											'min'=>'0',
											'id'=>'quantity_unit_sale',
											'class'=>'form-control',
											'value'=>(double) ($item_info->quantity_unit_sale ? $item_info->quantity_unit_sale : 1))
										);?>
                        </div>
                    </div>

                    <div class="col-md-4" id="price_unit_sale_item">
                    </div>

                </div>

                <div class="form-group">
                    <?php echo form_label(lang("common_units").':', 'unit_sale',array('class'=>'col-md-3 control-label ')); ?>
                    <div class="col-md-8 ">
                        <div class="col-md-12 ">

                        </div>
                        <div class="col-md-12 table-responsive">
                            <table id="table_has_sales_units" class="table">
                                <thead>
                                    <tr>
                                        <th><?=lang("common_units");?></th>
                                        <th><?=lang("items_name");?></th>
                                        <th><?=lang("items_quantity");?></th>
                                        <th><?=lang("items_unit_price")." (".lang('items_without_tax').")"?></th>
                                        <th><?=lang("common_default")?></th>
                                        <th><?php echo lang('common_delete'); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (isset($unit_sale) and $unit_sale) {  ?>
                                    <?php foreach($unit_sale->result() as $unit) { ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" size="30" name="unit_sale[]" value="<?=$unit->id?>" />

                                            <?= form_dropdown('unit_sale[]', $units,
                                                $unit->unit_measurement,
                                                'class="form-control"');
                                            ?>                                            
                                        </td>
                                        <td>
                                            <input type="text" onkeyup="mayuscula(this)" required class="form-control form-inps" size="25"
                                                name="unit_sale[]" value="<?php echo H($unit->name); ?>" />
                                        </td>
                                        <td><input type="number" min="1" onchange="validate_quantity()"
                                                class="quantity_unit_sale_1 form-control form-inps" size="10"
                                                name="unit_sale[]" value="<?php echo (double) $unit->quatity; ?>" />
                                        </td>
                                        <td><input type="number" min="0" class="form-control form-inps" size="15"
                                                name="unit_sale[]" value="<?php echo (double) $unit->price; ?>" /></td>
                                        <td>
                                            <input type="radio" name="default_select" onclick="select_radio(this)"
                                                <?=($unit->default_select == 1 ? "checked" :"")?> value="1" />
                                            <input type="hidden" class="default_select" name="unit_sale[]"
                                                value="<?=$unit->default_select?>" />
                                        </td>
                                        <td><a class="btn btn-delte_unit" onclick="delete_unit_sale_item(this)"
                                                href="javascript:void(0);"><?php echo lang('common_delete'); ?></a></td>
                                    </tr>
                                    <?php  } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="javascript:void(0);" id="add_unit_sale" class="btn btn-circle default btn-xs">Añadir
                            unidad</a>
                        <br /><br />
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


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

        <div class="form-group quantity-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
            <?php echo form_label('<a class="help_config_options tooltips" data-placement="left" title="'.lang("items_quantity_stock_help").'">'.lang('items_quantity_stock').'</a>'.':', '', array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-4">
                <div id="quantity_spinner">
                    <div class="input-group">
                        <?php
										$data_aux= array(
											'name'=>'locations['.$location->location_id.'][quantity]',
											'id'=>'locations['.$location->location_id.'][quantity]',
											'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->quantity !== NULL ? to_quantity($location_items[$location->location_id]->quantity): '',
											'class'=>'spinner-input form-control form-inps',
											
										);
										if(!$is_new_item and  !$this->Employee->has_module_action_permission('items','edit_quantity', $this->Employee->get_logged_in_employee_info()->person_id)){
											$data_aux["readonly"]=1;
										}
										echo form_input($data_aux);?>
                        <div class="spinner-buttons input-group-btn btn-group-vertical">
                            <button type="button" class="btn spinner-up btn-xs green">
                                <i class="fa fa-angle-up"></i>
                            </button>
                            <button type="button" class="btn spinner-down btn-xs green">
                                <i class="fa fa-angle-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php echo form_dropdown('unit', $units,
									$item_info->unit,
									'class="bs-select form-control"');
								?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label(lang('items_quantity_warehouse').':', '', array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <div id="quantity_warehouse_spinner">
                    <div class="input-group">
                        <?php 
										$data_aux= array(
											'name'=>'locations['.$location->location_id.'][quantity_warehouse]',
											'id'=>'locations['.$location->location_id.'][quantity_warehouse]',
                                            'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->quantity_warehouse !== NULL ? to_quantity($location_items[$location->location_id]->quantity_warehouse): '',
											'class'=>'spinner-input form-control form-inps',
											
										);
										if(!$is_new_item and  !$this->Employee->has_module_action_permission('items','edit_quantity', $this->Employee->get_logged_in_employee_info()->person_id)){

											$data_aux["readonly"]=1;
										}
										echo form_input($data_aux);?>
                        <div class="spinner-buttons input-group-btn btn-group-vertical">
                            <button type="button" class="btn spinner-up btn-xs green">
                                <i class="fa fa-angle-up"></i>
                            </button>
                            <button type="button" class="btn spinner-down btn-xs green">
                                <i class="fa fa-angle-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label(lang('items_quantity_transfer').':', '', array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-4">
                <div id="quantity_transfer_spinner">
                    <div class="input-group">
                        <?php echo form_input(array(
											'name'=>'locations['.$location->location_id.'][quantity_transfer]',
											'id'=>'locations_quantity_transfer',
											'class'=>'spinner-input form-control form-inps',
										));?>
                        <div class="spinner-buttons input-group-btn btn-group-vertical">
                            <button type="button" class="btn spinner-up btn-xs green">
                                <i class="fa fa-angle-up"></i>
                            </button>
                            <button type="button" class="btn spinner-down btn-xs green">
                                <i class="fa fa-angle-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <?php echo form_dropdown('locations['.$location->location_id.'][type_transfer]', array(
									lang('stocktowarehouse')=> lang('stocktowarehouse'),
									lang('warehousetostock')=> lang('warehousetostock')),
									'',
									'class="bs-select form-control"');
								?>
            </div>
            <div class="col-md-2">
                <?php echo form_button(array(
									'name' => 'transferent',
									'id' => 'transferent',
									'class' => 'btn green btn-block transferent',
									'value' => 'true',
									'rel'=>$location->location_id,
									'content' => lang('common_transfer')
								));?>
            </div>
        </div>

        <?php if ($this->Location->count_all() > 1) {?>
        <div class="form-group reorder-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
            <?php echo form_label(lang('items_reorder_level').':', '', array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <?php echo form_input(array(
										'name'=>'locations['.$location->location_id.'][reorder_level]',
										'value'=> $location_items[$location->location_id]->item_id !== '' &&  $location_items[$location->location_id]->reorder_level !== NULL ? to_quantity($location_items[$location->location_id]->reorder_level): '',
										'class'=>'form-control form-inps',
									));?>
            </div>
        </div>
        <?php } ?>

        <div class="form-group">
            <?php echo form_label(lang('items_location_at_store').':', '', array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <?php echo form_input(array(
									'name'=>'locations['.$location->location_id.'][location]',
									'class'=>'form-control form-inps',
									'value'=> $location_items[$location->location_id]->item_id !== '' ? $location_items[$location->location_id]->location: ''
								));?>
            </div>
        </div>

    </div>


    <?php if ($this->Location->count_all() > 1) {?>

    <div class="form-group override-prices-container">
        <?php echo form_label(lang('items_override_prices').':', '',array('class'=>'col-md-3 control-label wide')); ?>
        <div class="col-md-8">
            <div class="md-checkbox-inline">
                <div class="md-checkbox">
                    <?php echo form_checkbox(array(
												'name'=>'locations['.$location->location_id.'][override_prices]',
												'id'=>'override_prices_checkbox'.$location->location_id,
												'class' => 'override_prices_checkbox delete-checkbox',
												'rel'=>$location->location_id,
												'value'=>1,
												'checked'=>(boolean)isset($location_items[$location->location_id]) && is_object($location_items[$location->location_id]) && $location_items[$location->location_id]->is_overwritten));
											?>
                    <label for="override_prices_checkbox<?php echo $location->location_id?>">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>


    <div
        class="item-location-price-container <?php if ($location_items[$location->location_id] === FALSE || !$location_items[$location->location_id]->is_overwritten){echo 'hidden';} ?>">
        <?php if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="") { ?>
        <div class="form-group">
            <?php echo form_label(lang('items_cost_price').' ('.lang('items_without_tax').'):', '',array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][cost_price]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->cost_price ? to_currency_no_money($location_items[$location->location_id]->cost_price, 10): ''
											));?>
            </div>
        </div>
        <?php
								}
								else
								{
									echo form_hidden('locations['.$location->location_id.'][cost_price]', $location_items[$location->location_id]->item_id !== '' ? $location_items[$location->location_id]->cost_price: '');
								}
								?>
        <div class="form-group">
            <?php echo form_label(lang('items_again').' ('."%".'):', '',array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-6">
                <?php echo form_input(array(
											'name'=>'locations['.$location->location_id.'][items_discounts]',
											'size'=>'8',
											'class'=>'form-control form-inps',
											'value'=>$location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->items_discount ? to_currency_no_money($location_items[$location->location_id]->items_discount, 10) : ''
										));?>
            </div>
            <div class="col-md-2">
                <?php echo form_button(array(
									'name' => 'items_discounts',
									'id' => 'items_discounts',
									'class' => 'btn green btn-block items_discounts',
									'value' => 'true',
									'rel'=>$location->location_id,
									'content' => lang('items_discount')
								));?>
            </div>

        </div>

        <div class="form-group">
            <?php echo form_label(lang('items_unit_price').':', '',array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <?php echo form_input(array(
											'name'=>'locations['.$location->location_id.'][unit_price]',
											'size'=>'8',
											'class'=>'form-control form-inps',
											'value'=>$location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->unit_price ? to_currency_no_money($location_items[$location->location_id]->unit_price, 10) : ''
										));?>
            </div>

        </div>

        <?php foreach($tiers as $tier) { ?>
        <div class="form-group">
            <?php echo form_label($tier->name.':', $tier->name,array('class'=>'col-md-3 control-label wide')); ?>
            <div class='col-md-8'>
                <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][item_tier]['.$tier->id.']',
												'size'=>'8',
												'class'=>'form-control margin-bottom-05 form-inps',
												'value'=> $location_tier_prices[$location->location_id][$tier->id] !== FALSE ? ($location_tier_prices[$location->location_id][$tier->id]->unit_price != NULL ? to_currency_no_money($location_tier_prices[$location->location_id][$tier->id]->unit_price, 10) : $location_tier_prices[$location->location_id][$tier->id]->percent_off): '')
											);?>
                <?php echo form_dropdown('locations['.$location->location_id.'][tier_type]['.$tier->id.']', $tier_type_options, $location_tier_prices[$location->location_id][$tier->id] !== FALSE && $location_tier_prices[$location->location_id][$tier->id]->unit_price === NULL ? 'percent_off' : 'unit_price', 'class="bs-select form-control"');?>
            </div>
        </div>
        <?php } ?>

        <div class="form-group">
            <?php echo form_label(lang('items_promo_price').':', '',array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-8">
                <?php echo form_input(array(
											'name'=>'locations['.$location->location_id.'][promo_price]',
											'size'=>'8',
											'class'=>'form-control form-inps',
											'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->promo_price ? to_currency_no_money($location_items[$location->location_id]->promo_price, 10): ''
										));?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label(lang('items_promo_start_date').':', '',array('class'=>'col-md-3 control-label text-info wide')); ?>
            <div class="col-md-8">
                <div class="input-group date datepicker"
                    data-date="<?php echo $location_items[$location->location_id]->item_id !== '' &&  $location_items[$location->location_id]->start_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->start_date)): ''; ?>"
                    data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][start_date]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->start_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->start_date)): ''
											));?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label(lang('items_promo_end_date').':', '',array('class'=>'col-md-3 control-label text-info wide')); ?>
            <div class="col-md-8">
                <div class="input-group date datepicker"
                    data-date="<?php echo $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->end_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->end_date)): ''; ?>"
                    data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][end_date]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'value'=> $location_items[$location->location_id]->item_id !== '' && $location_items[$location->location_id]->end_date ? date(get_date_format(), strtotime($location_items[$location->location_id]->end_date)): ''
											));?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group override-taxes-container">
        <?php echo form_label(lang('items_override_default_tax').':', '',array('class'=>'col-md-3 control-label wide')); ?>
        <div class="col-md-8">
            <div class="md-checkbox-inline">
                <div class="md-checkbox">
                    <?php echo form_checkbox(array(
												'name'=>'locations['.$location->location_id.'][override_default_tax]',
												'id'=>'override_default_tax_checkbox1'.$location->location_id,
												'class' => 'override_default_tax_checkbox  delete-checkbox md-check',
												'value'=>1,
												'checked'=> $location_items[$location->location_id]->item_id !== '' ? (boolean)$location_items[$location->location_id]->override_default_tax: FALSE
											));?>
                    <label for="override_default_tax_checkbox1<?php echo $location->location_id?>">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div
        class="tax-container <?php if ($location_items[$location->location_id] === FALSE || !$location_items[$location->location_id]->override_default_tax){echo 'hidden';} ?>">
        <div class="form-group">
            <?php echo form_label(lang('items_tax_1').':', '',array('class'=>'col-md-3 control-label wide')); ?>
            <div class="col-md-4">
                <?php echo form_input(array(
											'name'=>'locations['.$location->location_id.'][tax_names][]',
											'size'=>'8',
											'class'=>'form-control form-inps',
											'placeholder' => lang('common_tax_name'),
											'value' => isset($location_taxes[$location->location_id][0]['name']) ? $location_taxes[$location->location_id][0]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
										));?>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][tax_percents][]',
												'size'=>'3',
												'class'=>'form-control form-inps',
												'placeholder' => lang('items_tax_percent'),
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
											'name'=>'locations['.$location->location_id.'][tax_names][]',
											'size'=>'8',
											'class'=>'form-control form-inps',
											'placeholder' => lang('common_tax_name'),
											'value' => isset($location_taxes[$location->location_id][1]['name']) ? $location_taxes[$location->location_id][1]['name'] : ($this->Location->get_info_for_key('default_tax_1_name') ? $this->Location->get_info_for_key('default_tax_1_name') : $this->config->item('default_tax_1_name'))
										));?>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][tax_percents][]',
												'size'=>'3',
												'class'=>'form-control form-inps',
												'placeholder' => lang('items_tax_percent'),
												'value' => isset($location_taxes[$location->location_id][1]['percent']) ? $location_taxes[$location->location_id][1]['percent'] : ''
											));?>
                    <span class="input-group-addon">%</span>
                </div>
                <div class="md-checkbox-inline">
                    <div class="md-checkbox">
                        <?php echo form_checkbox(array(
													'name'=>'locations['.$location->location_id.'][tax_cumulatives][]',
													'value'=> 1,
													'id'=>'cumulative_checkbox1'.$location->location_id,
													'class'=>'cumulative_checkbox',
													'checked'=> isset($location_taxes[$location->location_id][1]['cumulative']) ? (boolean)$location_taxes[$location->location_id][1]['cumulative'] : ($this->Location->get_info_for_key('default_tax_2_cumulative') ? (boolean)$this->Location->get_info_for_key('default_tax_2_cumulative') : (boolean)$this->config->item('default_tax_2_cumulative'))
												));?>
                        <label for="cumulative_checkbox1<?php echo $location->location_id?>">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>
                            <?php echo lang('common_cumulative'); ?>
                        </label>
                    </div>
                </div>
            </div> <!-- end col-sm-9...-->
        </div>
        <!--End form-group-->

        <div class="col-md-offset-3"
            style="visibility: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'hidden' : 'visible';?>">
            <a href="javascript:void(0);"
                class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?>
                &raquo;</a>
        </div>

        <div class="more_taxes_container"
            style="display: <?php echo isset($location_taxes[$location->location_id][2]['name']) ? 'block' : 'none';?>">
            <div class="form-group">
                <?php echo form_label(lang('items_tax_3').':', '',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-4">
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'placeholder' => lang('common_tax_name'),
												'value' => isset($location_taxes[$location->location_id][2]['name']) ? $location_taxes[$location->location_id][2]['name'] : ($this->Location->get_info_for_key('default_tax_3_name') ? $this->Location->get_info_for_key('default_tax_3_name') : $this->config->item('default_tax_3_name'))
											));?>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <?php echo form_input(array(
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'size'=>'3',
													'class'=>'form-control form-inps',
													'placeholder' => lang('items_tax_percent'),
													'value' => isset($location_taxes[$location->location_id][2]['percent']) ? $location_taxes[$location->location_id][2]['percent'] : ''
												));?>
                        <span class="input-group-addon">%</span>
                        <?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo form_label(lang('items_tax_4').':', '',array('class'=>'col-md-3 control-label wide')); ?>
                <div class="col-md-4">
                    <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'placeholder' => lang('common_tax_name'),
												'value' => isset($location_taxes[$location->location_id][3]['name']) ? $location_taxes[$location->location_id][3]['name'] : ($this->Location->get_info_for_key('default_tax_4_name') ? $this->Location->get_info_for_key('default_tax_4_name') : $this->config->item('default_tax_4_name'))
											));?>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <?php echo form_input(array(
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'size'=>'3',
													'class'=>'form-control form-inps',
													'placeholder' => lang('items_tax_percent'),
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
												'name'=>'locations['.$location->location_id.'][tax_names][]',
												'size'=>'8',
												'class'=>'form-control form-inps',
												'placeholder' => lang('common_tax_name'),
												'value' => isset($location_taxes[$location->location_id][4]['name']) ? $location_taxes[$location->location_id][4]['name'] : ($this->Location->get_info_for_key('default_tax_5_name') ? $this->Location->get_info_for_key('default_tax_5_name') : $this->config->item('default_tax_5_name'))
											));?>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <?php echo form_input(array(
													'name'=>'locations['.$location->location_id.'][tax_percents][]',
													'size'=>'3',
													'class'=>'form-control form-inps',
													'placeholder' => lang('items_tax_percent'),
													'value' => isset($location_taxes[$location->location_id][4]['percent']) ? $location_taxes[$location->location_id][4]['percent'] : ''
												));?>
                        <span class="input-group-addon">%</span>
                        <?php echo form_hidden('locations['.$location->location_id.'][tax_cumulatives][]', '0'); ?>
                    </div>
                </div>
            </div>
        </div><!-- End more taxes container-->
    </div><!-- End tax-container-->
    <?php } /*End if for multi locations*/ ?>

    <?php echo form_hidden('redirect', isset($redirect) ? $redirect : ''); ?>
    <?php echo form_hidden('sale_or_receiving', isset($sale_or_receiving) ? $sale_or_receiving : ''); ?>

</div>





<div class="portlet light subcategory_hide"
    <?php if( !$this->config->item('subcategory_of_items') || !$item_info->subcategory ||$item_info->is_service ) echo"style='display: none'";?>>
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-speech"></i>
            <span class="caption-subject bold">
                Subcategoría <?php echo $location->name;  ?>
            </span>
        </div>
        <div class="tools">
            <a href="" class="collapse" data-original-title="" title=""></a>
            <a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>
        </div>
    </div>
    <div class="portlet-body form ">
        <div class="cuerpo">
            <?php  foreach($locations_subcategory[$location->location_id] as $subcategory) {?>
            <div class="subcategory_div">
                <div class="text-right form-group col-md-12">
                    <a href="javascript:void(0);" class="text-right " onclick="eliminar_subcategoria(this)"
                        title="Eliminar subcategoría">
                        <i class="fa fa-remove  " style="font-size:14px;color:red"></i>
                    </a>
                </div>
                <div class="form-group subcategory-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
                    <label class="col-md-3 control-label  requireds wide"
                        style=" <?php echo $this->config->item('inhabilitar_subcategory1')==1 ?  "display:none;":"" ?>"><?php echo $this->config->item('custom_subcategory1_name') ?  $this->config->item('custom_subcategory1_name'):'Personalizado1'?></label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][subcategory_data_custom1][]',
												'value'=> $this->config->item('inhabilitar_subcategory1')==1?"»": $subcategory->custom1,
												"onkeyup"=>"mayuscula(this)",
												"onblur"=>"mayuscula(this)",
												"required"=>"required",
												"type"=> $this->config->item('inhabilitar_subcategory1') ?  "hidden":"text",
												'class'=>'spinner-input form-control form-inps customs1_subcategory',
											));?>
                    </div>
                </div>
                <div class="form-group subcategory-input <?php if ($item_info->is_service){echo 'hidden';} ?>">
                    <label
                        class="col-md-3 control-label requireds  wide"><?php echo $this->config->item('custom_subcategory2_name') ?  $this->config->item('custom_subcategory2_name'):'Personalizado2'?></label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][subcategory_data_custom2][]',
												'value'=>  $subcategory->custom2,
												'class'=>'spinner-input form-control form-inps customs2_subcategory',
												"onkeyup"=>"mayuscula(this)",
												"onblur"=>"mayuscula(this)",
												"required"=>"required"
											));?>
                    </div>
                </div>
                <div class="form-group subcategory-input <?php if ($item_info->is_service){echo 'hidden';} ?>">

                    <?php echo form_label('<a class="help_config_options requireds tooltips" data-placement="left" title="'.lang("items_quantity_stock_subcategory_help").'(Cantidad Stock '.$location->name.')">'.lang('items_quantity_stock').'</a>'.':', '', array('class'=>'col-md-3 control-label wide')); ?>

                    <div class="col-md-8">
                        <?php echo form_input(array(
												'name'=>'locations['.$location->location_id.'][subcategory_data_quantity][]',
												'value'=> (float) $subcategory->quantity,
												'type'=>"number",
												"required"=>"required",
												'class'=>'spinner-input form-control form-inps  ',
											));?>
                    </div>
                </div>
                <div class="form-group subcategory-input">
                    <div class=" col-md-offset-3 col-md-8">
                        <hr>
                    </div>
                </div>
            </div>
            <!--subcategory_div-->
            <?php }?>
        </div>

        <div class="form-group subcategory_data-input">
            <div class=" col-md-offset-3 col-md-8">
                <a href="javascript:void(0)" onclick="addsubcategory(this)" class="btn btn-circle default btn-xs">Añadir
                    subcategoría</a>
            </div>
        </div>
    </div>
</div>


<?php } /*End foreach for locations*/ ?>

<div class="portlet light">
    <div class="portlet-body form">
        <div class="text-center">
            <?php
						if (isset($prev_item_id) && $prev_item_id)
						{
							echo anchor('items/view/'.$prev_item_id, "<i class='fa fa-chevron-left'></i> &nbsp;".lang('items_prev_item'), array('class' => 'btn btn-circle btn-sm green-jungle'));
						}
						?>

            <?php
						if (isset($next_item_id) && $next_item_id)
						{
							echo anchor('items/view/'.$next_item_id, lang('items_next_item')."&nbsp; <i class='fa fa-chevron-right'></i>", array('class' => 'btn btn-circle btn-sm green-jungle'));
						}
						?>
        </div>
        <div class="row">
            <div class="col-md-2 col-md-offset-5 margin-top-10">
                <?php
							if (isset($redirect) && $redirect == 1)
							{
								echo form_button(array(
								'name' => 'cancel',
								'id' => 'cancel',
								'class' => 'btn btn-danger',
								'value' => 'true',
								'content' => lang('common_cancel')
								));
							}
							?>

                <?php
								echo form_button(array(
								'type'=>'submit',
								'name'=>'submitf',
								'id'=>'submitf',
								'content'=>lang('common_submit'),
								'class'=>'submit_button btn btn-block btn-primary')
								);
							?>
            </div>
        </div>
    </div>
</div>

</div>
<?php echo form_close(); ?>

<script type='text/javascript'>
function eliminar_subcategoria(elemento) {
    var padre = $(elemento).parent().parent();
    if ($(padre.parent()).children().length > 1) {
        $(padre).remove();
    }
}

function addsubcategory(elemento) {
    var padre = $(elemento).parent().parent().siblings('div');
    var hijo = $(padre).children()[0];
    var cantidad =
        <?php echo $this->config->item('quantity_subcategory_of_items')? $this->config->item('quantity_subcategory_of_items'):5?>;
    if ($(padre).children().length < cantidad) {
        var clone = hijo.cloneNode(true);
        $(padre).append($(clone));
        $(':input', clone).each(function() {
            var type = this.type;

            if (type == 'number') {
                this.value = "0"
            } else {
                this.value = ""
            }
            <?php if($this->config->item('inhabilitar_subcategory1') ):?>
            if (this.type == "hidden") {
                this.value = "»";
            }
            <?php endif; ?>

        });
        add_autocomplete();

    } else {
        toastr.error("Solo se permite un máximo de " + cantidad + " subcategoría por producto ",
            <?php echo json_encode(lang('common_error')); ?>);
    }
}



$('#override_default_tax').change(function() {
    var $input = $(this);
    $('.prueba').html(
        ".attr( \"checked\" ): <b>" + $input.attr("checked") + "</b><br>" +
        ".prop( \"checked\" ): <b>" + $input.prop("checked") + "</b><br>" +
        ".is( \":checked\" ): <b>" + $input.is(":checked")) + "</b>";
}).change();

var imagen = "<?php echo site_url('app_files/view/'.$item_info->image_id); ?>"

$("#image_id").fileinput({
    initialPreview: [
        "<img src='<?php echo $item_info->image_id ? site_url('app_files/view/'.$item_info->image_id) : base_url().'img/no-photo.jpg'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
    ],
    overwriteInitial: true,
    initialCaption: "Imagen"
});



$(".price_suppliers").inputmask({
    "alias": 'numeric'
});


//Spinners
$('#quantity_spinner, #quantity_warehouse_spinner, #quantity_transfer_spinner').spinner({
    min: 0
});

//Value quantity
var val_quantity = $('input[name="locations[' + <?php echo $location->location_id ?> + '][quantity]"').val();
var val_quantity_warehouse = $('input[name="locations[' + <?php echo $location->location_id ?> +
    '][quantity_warehouse]"').val();
var val_quantity_transfer = $('input[name="locations[' + <?php echo $location->location_id ?> + '][quantity_transfer]"')
    .val();



//validation and submit handling
$(document).ready(function() {
    <?php if($item_info->item_id > 0) echo "calcule_price_unit();"?>
    
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    // subcategoria_ocultar"
    $('#subcategory').click(function() {
        if (!$(this).is(':checked')) {
            $('.subcategory_hide').hide(600);

        } else {
            $(".subcategory_hide").show(600);
        }
    });
    //
    $(".asign_price").click(function() {
        var val = $(this).attr('rel');
        var price = $('#price_suppliers' + val).val();

        $("#cost_price").val(price).focus();

    });
    $(".items_discount").click(function() {
        var items_discount = ($('#items_discount').val() / 100);
        var cost_price = $('#cost_price').val();
        var cost_price_with_tax = parseFloat(cost_price) * parseFloat(items_discount);
        var new_price = parseFloat(cost_price_with_tax) + parseFloat(cost_price);
        $("#unit_price").val(new_price);

    });
    $(".items_discounts").click(function() {
        var id_location = $(this).attr('rel');
        var items_discount = ($('input[name="locations[' + id_location + '][items_discounts]"').val() /
            100);
        var cost_price = $('input[name="locations[' + id_location + '][cost_price]"').val();
        var cost_price_with_tax = parseFloat(cost_price) * parseFloat(items_discount);
        var new_price = parseFloat(cost_price_with_tax) + parseFloat(cost_price);
        $('input[name="locations[' + id_location + '][unit_price]"').val(new_price);

    });

    //
    $(".transferent").click(function() {
        //$(".transferent").valid();
        //var quantity = //	var quantity_warehouse=$("input[name='locations[1][quantity_warehouse]'").val());
        //var quantity=$("input[name='locations[1][quantity_transfer]'").val());
        var id_location = $(this).attr('rel');
        var data = {
            quantity: $('input[name="locations[' + id_location + '][quantity]"').val(),
            quantity_transfer: $('input[name="locations[' + id_location + '][quantity_transfer]"')
                .val(),
            quantity_warehouse: $('input[name="locations[' + id_location + '][quantity_warehouse]"')
                .val(),
            type_transferent: $('select[name="locations[' + id_location + '][type_transfer]"')
            .val(),
            item_id: +<?php echo $item_info->item_id ==NULL ? '-1':$item_info->item_id ?>,
            location_id: id_location
        }

        $("#form").plainOverlay('show');

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: data,
            url: '<?php echo site_url("items/transferent");?>',

            success: function(response) {
                if (response.success == true) {
                    $("#form").plainOverlay('hide');
                    location.reload();
                    toastr.success(response.message,
                        <?php echo json_encode(lang('common_success')); ?> + ' #' +
                        response.item_id);
                } else {
                    $("#form").plainOverlay('hide');
                    toastr.error(response.message,
                        <?php echo json_encode(lang('common_error')); ?>);
                }
            },
        });
    });

    $(".delete_item_number").click(function() {
        $(this).parent().parent().remove();
    });
    $(".delete_item_serial").click(function() {
        $(this).parent().parent().remove();
    });


    $("#add_addtional_item_number").click(function() {
        $("#additional_item_numbers tbody").append(
            '<tr><td><input type="text" class="form-control form-inps" size="40" name="additional_item_numbers[]" value="" /></td><td>&nbsp;</td></tr>'
            );
    });
    $("#add_addtional_item_serial").click(function() {
        $("#additional_item_seriales tbody").append(
            '<tr><td><input type="text" class="form-control form-inps" size="40" name="additional_item_seriales[]" value="" /></td><td>&nbsp;</td></tr>'
            );
    });
    $("#add_unit_sale").click(function() {
        var units =`<?= form_dropdown('unit_sale[]', $units,"", 'class="form-control"');?>`;
        $("#table_has_sales_units tbody").append(`
				<tr>
                    <td>
						<input type="hidden" name="unit_sale[]" size="30" value="-1" />
						${units}
					</td>
					<td>
						<input type="text" onkeyup="mayuscula(this)" required class="form-control form-inps" size="30" name="unit_sale[]" value="" />
					</td>
					<td>
						<input type="number" min="0" onchange="validate_quantity()" class="form-control form-inps quantity_unit_sale_1" size="40" name="unit_sale[]" value="1" />
					</td>
					<td>
						<input type="number" min="0" class="form-control form-inps" size="40" name="unit_sale[]" value="0" />
					</td>
					<td>
						<input type="radio" name="default_select" onclick="select_radio(this)" value="1" />
						<input type="hidden" class="default_select" name="unit_sale[]" value="0" />					
					</td>
					<td><a class="btn btn-delte_unit " onclick="delete_unit_sale_item(this)" href="javascript:void(0);"><?php echo lang('common_delete'); ?></a></td>
				</tr>`);
        calcule_price_unit();
    });

    $('#quantity_unit_sale').change(function() {
        calcule_price_unit();
    });
    $('#unit_price').change(function() {
        calcule_price_unit();
    });

    $("#add_addtional_suppliers").click(function() {

        $("#additional_suppliers tbody").append(
            "<tr><td><select class='bs-select form-control' name='supplier_id[]' ><?php foreach($suppliers as $name=>$value){?> <option  value='<?php echo $name;?>' selected='selected'><?php echo $value;}?></option></select></td><td><input type='text' class='form-control form-inps price_suppliers' size='50' name='price_suppliers[]' placeholder='Precio del proveedor' /></td></tr>"
            );

    });


    $(".delete_item_supplier").click(function() {
        $(this).parent().parent().remove();

        var data = {
            id_supplier: $(this).attr('rel'),
            item_id: +<?php echo $item_info->item_id ==NULL ? '-1':$item_info->item_id ?>
        }
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: data,
            url: '<?php echo site_url("items/deleted_suplliers");?>',
            success: function(response) {
                if (response.success == true) {
                    toastr.success(response.message,
                        <?php echo json_encode(lang('common_success')); ?> + ' #' +
                        response.item_id);
                }
            },
        });
    });

    $("#cancel").click(cancelItemAddingFromSaleOrRecv);

    setTimeout(function() {
        $(":input:visible:first", "#item_form").focus();
    }, "slow");
    //$('#image_id').imagePreview({ selector : '#avatar' }); // Custom preview container

    //
    $('#start_date').datetimepicker({
        format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
        locale: "es"
    });

    $('#end_date').datetimepicker({
        format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
        locale: "es"
    });
    $('#expiration_date').datetimepicker({
        format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
        locale: "es"
    });


    $('.datepicker').datetimepicker({
        format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
        locale: "es"
    });

    //
    $(".override_default_tax_checkbox, .override_prices_checkbox, .override_default_commission").change(
        function() {
            $(this).parent().parent().parent().parent().next().toggleClass('hidden')
        });
    $("#is_serialized").change(function() {

        if ($("#is_serialized").is(':checked')) {
            $("#panel_seriales").show(600);
        } else {
            $("#panel_seriales").hide(600);
        }
        //$(this).parent().parent().parent().parent().next().toggleClass('hidden')
    });

    $("#has_sales_units").change(function() {
        if ($("#has_sales_units").is(':checked')) {
            $("#panel_has_sales_units").removeClass('hidden');
           
            if ($('#table_has_sales_units >tbody >tr').length == 0) 
							$("#add_unit_sale").click();
            
        } else
            $("#panel_has_sales_units").addClass('hidden');
    });

    $("#is_service").change(function() {
        if ($(this).prop('checked')) {
            $(".quantity-input").addClass('hidden');
            $(".reorder-input").addClass('hidden');
        } else {
            $(".quantity-input").removeClass('hidden');
            $(".reorder-input").removeClass('hidden');
        }
    });

    $("#category").autocomplete({
        source: "<?php echo site_url('items/suggest_category');?>",
        delay: 300,
        autoFocus: false,
        minLength: 0
    });
    add_autocomplete();

    jQuery.validator.addMethod("quanttity", function(value, element) {

            <?php foreach($locations as $location) { ?>
            valido = stock_valido(<?php echo $location->location_id ?>);
            if (!valido) {
                return false;
            }

            <?php } ?>
            return true;

        },
        'La sumatoria de todas las cantidades de las subcategoría no puede sobrepasar o ser menor que la cantidad disponible en la tienda'
        );
    jQuery.validator.addMethod("doble", function(value, element) {

        <?php foreach($locations as $location) { ?>
        valido = valida_doble(<?php echo $location->location_id ?>);
        if (valido) {
            return false;
        }

        <?php } ?>
        return true;

    }, 'Subcategoría ya existe.');

    $('#item_form').validate({
        submitHandler: function(form) {
            $.post('<?php echo site_url("items/check_duplicate");?>', {
                    term: $('#name').val()
                }, function(data) {
                    <?php if(!$item_info->item_id) {  ?>
                    if (data.duplicate) {

                        if (confirm(
                            <?php echo json_encode(lang('items_duplicate_exists'));?>)) {
                            doItemSubmit(form);
                        } else {
                            return false;
                        }
                    }
                    <?php }  else ?> {
                        doItemSubmit(form);
                    }
                }, "json")
                .error(function() {});
        },
        errorClass: "text-danger",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
        },
        rules: {
            <?php if(!$item_info->item_id) {  ?>
            item_number: {
                remote: {
                    url: "<?php echo site_url('items/item_number_exists');?>",
                    type: "post"

                }
            },
            product_id: {
                remote: {
                    url: "<?php echo site_url('items/product_id_exists');?>",
                    type: "post"

                }
            },
            <?php } ?>

            <?php foreach($tiers as $tier) { ?> "<?php echo 'item_tier['.$tier->id.']'; ?>": {
                number: true
            },
            <?php } ?>
            <?php if ($this->config->item('subcategory_of_items') /*&& $this->Item->get_info($item_id)->subcategory*/){?>
            <?php	foreach($locations as $location) {?> "<?php echo 'locations['.$location->location_id.'][subcategory_data_custom1][]'; ?>": {
                required: true,
                doble: true,
            },
            "<?php echo 'locations['.$location->location_id.'][subcategory_data_custom2][]'; ?>": {
                required: true,
                doble: true,
            },
            "<?php echo 'locations['.$location->location_id.'][subcategory_data_quantity][]'; ?>": {
                required: true,
                number: true,
                quanttity: true,
            },
            <?php }	?>
            <?php }	?>

            <?php foreach($locations as $location) { ?> "<?php echo 'locations['.$location->location_id.'][quantity]'; ?>": {
                number: true
            },

            "<?php echo 'locations['.$location->location_id.'][reorder_level]'; ?>": {
                number: true
            },
            "<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>": {
                number: true
            },
            "<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>": {
                number: true
            },
            "<?php echo 'locations['.$location->location_id.'][promo_price]'; ?>": {
                number: true
            },
            <?php foreach($tiers as $tier) { ?> "<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>": {
                number: true
            },
            <?php } ?>

            <?php } ?>

            name: {
                required: true
            },
            category: "required",
            cost_price: {
                required: true,
                number: true
            },

            unit_price: {
                required: true,
                number: true
            },
            promo_price: {
                number: true
            },
            reorder_level: {
                number: true
            },
        },
        messages: {
            <?php if(!$item_info->item_id) {  ?>
            item_number: {
                remote: <?php echo json_encode(lang('items_item_number_exists')); ?>

            },
            product_id: {
                remote: <?php echo json_encode(lang('items_product_id_exists')); ?>

            },
            <?php } ?>

            <?php foreach($tiers as $tier) { ?> "<?php echo 'item_tier['.$tier->id.']'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            <?php } ?>
            <?php if ($this->config->item('subcategory_of_items') ){?>
            <?php	foreach($locations as $location) {?>

            "<?php echo 'locations['.$location->location_id.'][subcategory_data_custom1][]'; ?>": {
                required: "<?php ?> Este dato es requerido "
            },
            "<?php echo 'locations['.$location->location_id.'][subcategory_data_custom2][]'; ?>": {
                required: "Este dato es requerido "
            },
            "<?php echo 'locations['.$location->location_id.'][subcategory_data_quantity][]'; ?>": {
                required: "Este dato es requerido "
            },
            <?php }	?>
            <?php }	?>

            <?php foreach($locations as $location) { ?> "<?php echo 'locations['.$location->location_id.'][quantity]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            "<?php echo 'locations['.$location->location_id.'][quantity_transfer]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            "<?php echo 'locations['.$location->location_id.'][reorder_level]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            "<?php echo 'locations['.$location->location_id.'][cost_price]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            "<?php echo 'locations['.$location->location_id.'][unit_price]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            "<?php echo 'locations['.$location->location_id.'][promo_price]'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            <?php foreach($tiers as $tier) { ?> "<?php echo 'locations['.$location->location_id.'][item_tier]['.$tier->id.']'; ?>": {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            <?php } ?>
            <?php } ?>

            name: {
                required: <?php echo json_encode(lang('items_name_required')); ?>
            },
            category: <?php echo json_encode(lang('items_category_required')); ?>,
            cost_price: {
                required: <?php echo json_encode(lang('items_cost_price_required')); ?>,
                number: <?php echo json_encode(lang('items_cost_price_number')); ?>
            },
            unit_price: {
                required: <?php echo json_encode(lang('items_unit_price_required')); ?>,
                number: <?php echo json_encode(lang('items_unit_price_number')); ?>
            },
            promo_price: {
                number: <?php echo json_encode(lang('common_this_field_must_be_a_number')); ?>
            },
            //required:"Este dato es requerido ",
        }
    });
});

var submitting = false;

function doItemSubmit(form) {
    if (submitting) return;
    submitting = true;
    $("#form").plainOverlay('show');
    $(form).ajaxSubmit({
        success: function(response) {
            $("#form").plainOverlay('hide');
            submitting = false;

            if (response.success) {
                toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> + ' #' +
                    response.item_id);
                location.reload();
                //$("html, body").animate({ scrollTop: 0 }, "slow");
            } else {
                toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
            }
            if (response.redirect == 1 && response.success) {
                if (response.sale_or_receiving == 'sale') {
                    $.post('<?php echo site_url("sales/add");?>', {
                        item: response.item_id
                    }, function() {
                        window.location.href = '<?php echo site_url('sales'); ?>'
                    });
                } else {
                    $.post('<?php echo site_url("receivings/add");?>', {
                        item: response.item_id
                    }, function() {
                        window.location.href = '<?php echo site_url('receivings'); ?>'
                    });
                }
            } else if (response.redirect == 2 && response.success) {
                window.location.href = '<?php echo site_url('items'); ?>'
            }


            <?php if(!$item_info->item_id) { ?>
            //If we have a new item, make sure we hide the tax containers to "reset"
            $(".tax-container").addClass('hidden');
            $(".item-location-price-container").addClass('hidden');
            $('.commission-container').addClass('hidden');

            //Make the quantity inputs show up again in case they were hidden
            $(".quantity-input").removeClass('hidden');
            $(".reorder-input").removeClass('hidden');

            <?php } ?>
        },
        <?php if(!$item_info->item_id) { ?>
        resetForm: true,
        <?php } ?>
        dataType: 'json'
    });
}

function add_autocomplete() {
    $(".customs1_subcategory").autocomplete({
        source: "<?php echo site_url('items/suggest_category_subcategory/custom1');?>",
        delay: 300,
        autoFocus: false,
        minLength: 0
    });
    $(".customs2_subcategory").autocomplete({
        source: "<?php echo site_url('items/suggest_category_subcategory/custom2');?>",
        delay: 300,
        autoFocus: false,
        minLength: 0
    });
}


function cancelItemAddingFromSaleOrRecv() {
    if (confirm(<?php echo json_encode(lang('items_are_you_sure_cancel')); ?>)) {
        <?php if (isset($sale_or_receiving) && $sale_or_receiving == 'sale') {?>
        window.location = <?php echo json_encode(site_url('sales')); ?>;
        <?php } else { ?>
        window.location = <?php echo json_encode(site_url('receivings')); ?>;
        <?php } ?>
    }
}

function delete_unit_sale_item(element) 
{
	if ($('#table_has_sales_units >tbody >tr').length > 1){
    $(element).parent().parent().remove();

	}
}

function calcule_price_unit() {

    if ($.isNumeric($("#cost_price").val()) && $.isNumeric($("#quantity_unit_sale").val())) 
		{
        const price_unit = $("#cost_price").val() / $("#quantity_unit_sale").val(),
            elements = document.getElementsByClassName("quantity_unit_sale_1");

        $("#price_unit_sale_item").html("<?=lang("items_price_unit_without_tax")?>: " + Number(price_unit));

        for (const key in elements)
            elements[key].max = "" + $("#quantity_unit_sale").val();
    }
    else if(!$.isNumeric($("#cost_price").val()))
    {
        alert("Debe ingresar el <?=lang('items_cost_price').' ('.lang('items_without_tax').')'?>");
    }
}

function select_radio(element) 
{
    elements = document.getElementsByClassName("default_select");

    for (const key in elements)
        elements[key].value = "0";

    var her = $(element).siblings();

    $(her[0]).val(Number($(element).prop('checked')));
}

function mayuscula(e) 
{
    e.value = e.value.toUpperCase();
}
function validate_quantity()
{
    if (!$.isNumeric($("#cost_price").val()) ||  $("#quantity_unit_sale").val()< 1)
    {
        		alert("Debe ingresar la <?=lang("items_unit_quantity")?>");
    }
}
function stock_valido(location) {

    var elemento_input_id_Stock_store = "locations[" + location + "][quantity]";
    var elemento_input_id_Stock_subcategory = "locations[" + location + "][subcategory_data_quantity][]";

    var stock_store = $("input[id='" + elemento_input_id_Stock_store + "']").val();
    var elementos = $("input[name='" + elemento_input_id_Stock_subcategory + "']");
    var suma = 0;
    for (var i = 0; i < elementos.length; i++) {
        suma = suma + parseFloat($(elementos[i]).val());
    }

    if (suma != stock_store) {
        return false;
    }
    return true;
}

function valida_doble(location) {


    var elemento_input_id_custom1 = "locations[" + location + "][subcategory_data_custom1][]";
    var elemento_input_id_custom2 = "locations[" + location + "][subcategory_data_custom2][]";


    var elementos1 = $("input[name='" + elemento_input_id_custom1 + "']");
    var elementos2 = $("input[name='" + elemento_input_id_custom2 + "']");
    var suma = 0;
    for (var i = 0; i < elementos1.length; i++) {
        for (var j = 0; j < elementos1.length; j++) {
            if (i != j) {
                if ($(elementos1[i]).val() == $(elementos1[j]).val() &&
                    $(elementos2[i]).val() == $(elementos2[j]).val()) {
                    return true;
                }
            }
        }
    }

    return false;
}
</script>

<?php $this->load->view('partial/footer'); ?>