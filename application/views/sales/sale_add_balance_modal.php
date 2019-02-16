<div class="modal-dialog ">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
					<?php echo lang("sales_reload_product")?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" id="modal-button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">				
					<div class="col-md-12">
					<?php echo form_open('sales/add_cash/',array('id'=>'item_form_range','class'=>'form-horizontal ')); ?>

					<div class="portlet-body form">		
				<?php echo form_open_multipart('changes_house/save_balance/',array('id'=>'registers_form','class'=>'form-horizontal'));?>				
					<div class="form-body">	
						<h4><?php echo lang('common_fields_required_message'); ?></h4>

						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="">'.lang("items_item").'</a>'.':', 'item_id',array('class'=>'col-md-3 control-label requireds')); ?>
							<div class="col-md-8">
								
							<?php echo form_dropdown('item_id', $items,'','id="item_id" class="bs-select form-control"'); ?>

							</div>
						</div>	
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("cash_flows_cash_help").'">'.lang('cash_flows_cash').'</a>'.':', 'cash',array('class'=>'col-md-3 control-label requireds')); ?>
							<div class="col-md-8">
								
								<input id="input-cash" type="number" required name="cash" class="form-control form-inps">
							</div>
						</div>						
					</div>

					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">								

									<button id="submit-button" name="submit" class="btn green"><?=lang('common_submit');?></button>
									<button id="cancel-button"  type="button" class="btn default"><?=lang('common_cancel');?></button>
									
							</div>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
					</form>
							
				</div>			
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {		
		$('#cancel-button').click(function(e) {
			$("#modal-button").click();
		})
		$('#item_form_range').submit(function(e) {
			e.preventDefault();
			$('#submit-button').attr('disabled','disabled');
			$('#submit-button').attr('value','Enviando...');
			$.ajax({                        
				type: "POST",                 
				url: $(this).attr("action"),                    
				data: $(this).serialize(),
				success: function(data)            
				{
					$("#modal-button").click();
				}
			});
        
        });		
		
	});
	
	
</script>


