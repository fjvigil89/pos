<div class="modal-dialog">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-align-justify"></i> 
                    <span class="caption-subject bold">
                        <?php echo lang("items_defect"); ?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body form">
                <?php echo form_open('formulario/defectuoso', array('id'=>'defect_item_form','class'=>'form-horizontal'),array('location_id'=>5));?>
                    <div class="form-body">
                        
                        <div class="form-group">
                            <?php echo form_label(lang('items_name').':', '', array('class'=>'col-md-5 control-label wide')); ?>
                            <div class="col-md-7">
                                <?php 
                                    $quantity = array (
                                        'name'=>'item_name',
                                        'id'=>'item_name',
                                        'class'=>'form-control',
                                        'value'=>$item_info->name,
                                        'style'=>'border:none',
                                        'readonly'=>'readonly'
                                    );
                                    echo form_input($quantity)
                                ?>
                            </div>                         
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_quantity_warehouse').':', '', array('class'=>'col-md-5 control-label wide')); ?>
                            <div class="col-md-7">
                                <?php 
                                    $quantity = array (
                                        'name'=>'quantity_warehouse',
                                        'id'=>'quantity_warehouse',
                                        'class'=>'form-control',
                                        'value'=>to_quantity($item_location_info->quantity_warehouse),
                                        'style'=>'border:none',
                                        'readonly'=>'readonly'
                                    );
                                    echo form_input($quantity)
                                ?>
                            </div>                         
                        </div>                     
                        
                        <!--Spinner for defective quantity-->
                        <div class="form-group">
                            <?php echo form_label(lang('items_quantity_defect').':', '', array('class'=>'col-md-5 control-label wide')); ?>
                            <div class="col-md-6">
                                <div id="quantity_spinner">
                                    <div class="input-group">
                                        <?php echo form_input(array(
                                            'name'=>'quantity_defect',
                                            'id'=>'quantity_defect',
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
                        </div>

                        <div class="form-group">
                            <?php echo form_label(lang('items_defect_description').':', '', array('class'=>'col-md-5 control-label wide')); ?>
                            <div class="col-md-6">
                                <?php echo form_input(array(
                                    'name'=>'defect_description',
                                    'id'=>'defect_description',
                                    'class'=>'form-control form-inps',
                                    'value'=>'')
                                );?>
                            </div>
                        </div>
                        <!--END: Spinner for defective quantity-->   
                        <!--
                        <div class="form-group">
                            <?php #echo form_label(lang('items_total_available').':', '', array('class'=>'col-md-5 control-label wide')); ?>
                            <div class="col-md-6">
                                <span id="available_items" class="badge badge-defect-item bg-green badge-roundless">
                                    <?php #echo ($item_location_info->quantity_warehouse - $item_location_info->quantity_defect) ;?>
                                </span>
                            </div>  
                        </div>  
                        -->
                    </div>  

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12"> 
                                <?php
                                    echo form_button(array(
                                    'type'=>'button',
                                    'id'=>'save_button',
                                    'content'=>lang('common_submit'),
                                    'class'=>'btn btn-primary btn-block')
                                ); ?> 
                            </div>
                        </div>
                    </div>

                    <span id="save_status" style="display: none"></span>

                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script>
    //check if defective items are greater than the warehouse quantity
    $('#quantity_spinner').spinner({min: 0, max:<?php echo $item_location_info->quantity_warehouse != NULL ? $item_location_info->quantity_warehouse : 0?>}).change(function()
    {
        var quantity_warehouse = <?php echo $item_location_info->quantity_warehouse != NULL ? $item_location_info->quantity_warehouse : 0?>;
        var quantity_defect = $('#quantity_defect').val();
            
        if( quantity_warehouse < quantity_defect )
        {
            $('#quantity_defect').val(quantity_warehouse);
            $('#available_items').text( quantity_warehouse - quantity_warehouse );
        }
        else
        {
            $('#available_items').text( quantity_warehouse - quantity_defect);
        }
    });
   
    $('#save_button').click(function()
    {        
        //grab values
        var form_data = {
            item_id : <?php echo $item_location_info->item_id != NULL ? $item_location_info->item_id : -1 ?>,
            location_id : <?php echo $item_location_info->location_id != NULL ? $item_location_info->location_id : -1 ?>,
            quantity_warehouse : <?php echo $item_location_info->quantity_warehouse != NULL ? $item_location_info->quantity_warehouse : -1?>,
            quantity_defect : $('#quantity_defect').val(),
            defect_description : $('#defect_description').val()
        };

        $.ajax({
            method: "POST",
            url: '<?php echo site_url("items/defective_item");?>',
            data: form_data,
            success: function(data)
            {
                if(data.success)
                {
                    toastr.success(data.message, <?php echo json_encode(lang('common_success')); ?>);
                    $("#quantity_warehouse").val(data.quantity_warehouse);
                    $('#myModal').modal('toggle');
                    location.reload();
                }
                else
                {
                    toastr.error(data.message, <?php echo json_encode(lang('common_error')); ?>);
                }
            },
            dataType:'json',        
        }); 
    });

</script>