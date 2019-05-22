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
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-hidden="true"></button>
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
                                <tr>
                                    <td><strong><?php echo lang('items_item_number'); ?></strong></td>
                                    <td> <?php echo $item_info->item_number; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo lang('items_name'); ?></strong></td>
                                    <td> <?php echo $item_info->name; ?></td>
                                </tr>


                            </table>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" width="1200px">
                                    <thead>
                                        <caption class="text-center"><strong><?=lang("items_presentations")?></strong></caption>
                                        <tr>
                                            <th><?=lang("items_name");?></th>
                                            <th><?=lang("common_units")?></th>
                                            <th><?=lang("items_unit_price")." (".lang('items_without_tax').")"?></th>
                                            <th></th>
                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($unit_item as $unit) :?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php 
														if($name_unit ==  $unit->name)
															echo '<span class="badge">'.H($unit->name)." / ".H($unit->unit_measurement).'</span>';
														
														else
														 echo  $unit->name
													?>
                                            <td style="text-align: center;"><?= (double) $unit->quatity?></td>
                                            <td style="text-align: center;"><?= to_currency($unit->price) ?></td>
                                            <td style="text-align: center;">
                                                <?php if($name_unit ==  $unit->name){ ?>
                                                <!--<a class="edit_unit btn btn-xs opcion   btn-danger"
                                                    href="<?=site_url('sales/edit_unit/'.$line."/".$unit->item_id."/".$unit->id."/0")  ?>">
                                                    <?=lang("common_remove")?>
                                                </a>-->
                                                <?php } else {?>
                                                <a class=" edit_unit btn btn-xs opcion btn-success"
                                                    href="<?=site_url('sales/edit_unit/'.$line."/".$unit->item_id."/".$unit->id."/1")  ?>">
                                                    <?=lang("common_select")?>
                                                </a>
												<?php }?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
$( ".edit_unit" ).click(function( event ) {
	event.preventDefault();
    salesBeforeSubmit();
	$(".opcion").attr("disabled", true);
	$.post($(this).attr('href'),{},function(){
		$("#register_container").load('<?php echo site_url("sales/reload"); ?>');
		$("#close").click();
		
	});
	
});

</script>