<div class="modal-dialog modal-full">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-align-justify"></i> 
                    <span class="caption-subject bold">
                        <?php echo lang("items_edit_multiple_items"); ?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body form">     
                <?php echo form_open('items/bulk_update/',array('id'=>'bulk_item_form','class'=>'form-horizontal')); ?>           
                    <div class="form-body">                     
                        <div class="form-group">
                            <?php echo form_label(lang('items_category').':', 'category',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'category',
                                    'id'=>'category',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('supplier_id', $suppliers, '','class="bs-select form-control"');?>
                            </div>
                        </div>
                          <div class="form-group"> 
                            <?php echo form_label(lang('suppliers_price').':', 'supplier',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <input type="text" class="form-control form-inps" size="50" name="price_suppliers" value="" />
                            </div>
                        </div>


                        <div class="form-group"> 
                            <?php echo form_label(lang('items_cost_price').':', 'cost_price',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'cost_price',
                                    'id'=>'cost_price',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'unit_price',
                                    'id'=>'unit_price',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('costo_tax').':', 'costo_tax',array('class'=>'col-md-3 control-label requireds wide')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'costo_tax',
                                    'id'=>'costo_tax',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_promo_price').':', 'promo_price',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'promo_price',
                                    'id'=>'promo_price',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>
                        <!-- permitir en tienda online -->
						<div class="form-group">
							<?php echo form_label('<a class="help_config_options   tooltips"  title="Tienda On-line">Tienda On-line</a>'.':', 'prices_include_tax',array('class'=>'col-md-3 control-label wide')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'shop_online',
											'id'=>'shop_online',
											'class'=>'md-check',
											'value'=>1,
											'checked'=> 0)
										);?>
										<label for="shop_online">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_promo_start_date').':', 'start_date',array('class'=>'col-md-3 control-label text-info wide')); ?>
                            <div class="col-md-8">                                   
                                <div class="input-group date datepicker" data-date="" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <?php echo form_input(array(
                                        'name'=>'start_date',
                                        'id'=>'start_date',
                                        'class'=>'form-control',
                                        'value' => '')
                                    );?> 
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_promo_end_date').':', 'end_date',array('class'=>'col-md-3 control-label text-info wide')); ?>
                            <div class="col-md-8">
                                <div class="input-group date datepicker" data-date="" data-date-format=<?php echo json_encode(get_js_date_format()); ?>>
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <?php echo form_input(array(
                                        'name'=>'end_date',
                                        'id'=>'end_date',
                                        'class'=>'form-control',
                                        'value'=>'')
                                    );?> 
                                </div>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_override_default_tax').':', 'override_default_tax',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('override_default_tax', $override_default_tax_choices, '', 'id="override_default_tax" class="bs-select form-control"');?>
                            </div>
                        </div>

                        <div id="tax_container" class="tax-container hidden">                           
                            <div class="form-group"> 
                                <?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'tax_names[]',
                                        'id'=>'tax_name_1',
                                        'size'=>'8',
                                        'class'=>'form-control form-inps',
                                        'placeholder' =>lang('common_tax_name'),
                                    ));?>
                                </div>                                  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'tax_percents[]',
                                            'id'=>'tax_percent_name_1',
                                            'size'=>'3',
                                            'class'=>'form-control form-inps',
                                            'placeholder' =>lang('items_tax_percent'),
                                        ));?>
                                        <span class="input-group-addon">%</span>
                                        <?php echo form_hidden('tax_cumulatives[]', '0'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'tax_names[]',
                                        'id'=>'tax_name_2',
                                        'size'=>'8',
                                        'class'=>'form-control form-inps',
                                        'placeholder' =>lang('common_tax_name'),
                                    ));?>
                                </div>                                  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'tax_percents[]',
                                            'id'=>'tax_percent_name_2',
                                            'size'=>'3',
                                            'class'=>'form-control form-inps',
                                            'placeholder' =>lang('items_tax_percent'),
                                        ));?>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                    <div class="md-checkbox-inline">
                                        <div class="md-checkbox">
                                            <?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative'] ? true : false, 'id="cumulative_checkbox2"'); ?>                                            
                                            <label for="cumulative_checkbox2">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                <?php echo lang('common_cumulative'); ?>
                                            </label>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            
                            <div class="form-group"> 
                                <?php echo form_label(lang('items_tax_3').':', 'tax_percent_3',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'tax_names[]',
                                        'id'=>'tax_name_3',
                                        'placeholder' =>lang('common_tax_name'),
                                        'size'=>'8',
                                        'class'=>'form-control form-inps',
                                    ));?>
                                </div>                                  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'tax_percents[]',
                                            'id'=>'tax_percent_name_3',
                                            'size'=>'3',
                                            'class'=>'form-control form-inps',
                                            'placeholder' =>lang('items_tax_percent'),
                                        ));?>
                                        <span class="input-group-addon">%</span>
                                        <?php echo form_hidden('tax_cumulatives[]', '0'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group"> 
                                <?php echo form_label(lang('items_tax_4').':', 'tax_percent_4',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'tax_names[]',
                                        'id'=>'tax_name_4',
                                        'size'=>'8',
                                        'class'=>'form-control form-inps',
                                        'placeholder' =>lang('common_tax_name'),
                                    ));?>
                                </div>                                  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'tax_percents[]',
                                            'id'=>'tax_percent_name_4',
                                            'size'=>'3',
                                            'class'=>'form-control form-inps',
                                            'placeholder' =>lang('items_tax_percent'),
                                        ));?>
                                        <span class="input-group-addon">%</span>
                                        <?php echo form_hidden('tax_cumulatives[]', '0'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <?php echo form_label(lang('items_tax_5').':', 'tax_percent_5',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'tax_names[]',
                                        'id'=>'tax_name_5',
                                        'size'=>'8',
                                        'class'=>'form-control form-inps',
                                        'placeholder' =>lang('common_tax_name'),
                                    ));?>
                                </div>                                  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'tax_percents[]',
                                            'id'=>'tax_percent_name_5',
                                            'size'=>'3',
                                            'class'=>'form-control form-inps',
                                            'placeholder' =>lang('items_tax_percent'),
                                        ));?>
                                        <span class="input-group-addon">%</span>
                                        <?php echo form_hidden('tax_cumulatives[]', '0'); ?>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('common_prices_include_tax').':', 'tax_included',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('tax_included', $tax_included_choices, '', 'class="bs-select form-control"');?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_is_service').':', 'is_service',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('is_service', $is_service_choices, '', 'class="bs-select form-control"');?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_quantity').':', 'quantity',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'quantity',
                                    'id'=>'quantity',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_input(array(
                                    'name'=>'reorder_level',
                                    'id'=>'reorder_level',
                                    'class'=>'form-control form-inps')
                                );?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('allow_alt_description', $allow_alt_desciption_choices, '', 'class="bs-select form-control"');?>
                            </div>
                        </div>

                        <div class="form-group">
                        <?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-8">
                                <?php echo form_dropdown('is_serialized', $serialization_choices, '', 'class="bs-select form-control"');?>
                            </div>
                        </div>                       
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9"> 
                                <?php
                                    echo form_submit(array(
                                    'name'=>'submit',
                                    'id'=>'submit',
                                    'value'=>lang('common_submit'),
                                    'class'=>'btn btn-primary')
                                ); ?> 
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>                    
            </div>
        </div>        
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type='text/javascript'>

    //validation and submit handling
    $(document).ready(function()
    {
        setTimeout(function(){$(":input:visible:first","#bulk_item_form").focus();},100);
        
        $('#bulk_item_form .datepicker').datetimepicker({
            format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
            locale: "es"
        });

        $( "#category" ).autocomplete({
            source: "<?php echo site_url('items/suggest_category');?>",
            delay: 300,
            autoFocus: false,
            minLength: 0
        });
        
        $("#override_default_tax").change(function()
        {
            if ($(this).val() == '1')
            {
                $("#tax_container").removeClass('hidden');
            }
            else
            {
                $("#tax_container").addClass('hidden');
            }
        });

        var submitting = false;
        
        $('#bulk_item_form').validate({
            submitHandler:function(form)
            {
                if (submitting) return;         
                if(confirm(<?php echo json_encode(lang('items_confirm_bulk_edit')); ?>))
                {
                    //Get the selected ids and create hidden fields to send with ajax submit.
                    var selected_item_ids=get_selected_values();
                    for(k=0;k<selected_item_ids.length;k++)
                    {
                        $(form).append("<input type='hidden' name='item_ids[]' value='"+selected_item_ids[k]+"' />");
                    }
                    
                    $("#bulk_item_form").plainOverlay('show');
                    submitting = true;
                    $(form).ajaxSubmit({
                    success:function(response)
                    {
                        $("#bulk_item_form").plainOverlay('hide');
                        post_bulk_form_submit(response);                        
                        submitting = false;
                    },
                    dataType:'json'
                    });
                }

            },
            errorLabelContainer: "#error_message_box",
            wrapper: "li",
            rules: 
            {
                "tax_percents[]":
                {
                    number:true
                },
                reorder_level:
                {
                    number:true
                }
            },
            messages: 
            {
                "tax_percents[]":
                {
                    number:<?php echo json_encode(lang('items_tax_percent_number')); ?>
                },
                reorder_level:
                {
                    number:<?php echo json_encode(lang('items_reorder_level_number')); ?>
                }
            }
        });
    });
</script>

