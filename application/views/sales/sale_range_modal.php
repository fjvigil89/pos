<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">                    	
                      <?php echo lang("sales_closing_ranges");?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" id="modal-button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">				
					<div class="col-md-12">
					<?php echo form_open('sales/save_range/',array('id'=>'item_form_range','class'=>'form-horizontal ')); ?>

						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped text-center">
								<thead>
									<tr>
										<th style="text-align: center;"><?php echo lang("items_item")?></th>
										<th style="text-align: center;"><?php echo lang("sales_start_range");?></th>
										<th style="text-align: center;"><?php echo lang("sales_added_balance");?></th>
										<th style="text-align: center;"><?php echo lang("sales_final_rank");?></th>
										<th style="text-align: center;"><?php echo lang("sales_sale");?></th>

									</tr>
								</thead>
								<tbody>																			 
									<?php 
										$i=0;
										foreach($items as $item) { 
											if(isset($item_ranges[$item->item_id])){?>	
											<tr>						
												<td>
													<?php echo form_label($item->name, 'item_rango'.$item->item_id); ?>
												</td>
												<td>
												<?php  echo (double) $item_ranges[$item->item_id]["start_range"];
													
													echo form_input(array(
														'id'=>"item_id$item->item_id",
														'class'=>'',
														'value'=>$item->item_id,
														'type'=>"hidden",
														'name'=>"item_id[$item->item_id]",
													));?>				
												</td>
												<td>
													<?php echo (double) $item_ranges[$item->item_id]["extra_charge"]?>
												</td> 
												<td>
													<?php echo form_input(array(
														'id'=>"item_final_range$item->item_id",
														'class'=>'form-control form-inps item_final_range',
														'name'=>"item_final_range[$item->item_id]",
														"type"=>"number",														
														"required"=>"",
														"id_total_sale"=>"total_sale$item->item_id",
														"start_range"=>$item_ranges[$item->item_id]["start_range"],
														"value"=>(double)$item_ranges[$item->item_id]["final_range"],
														"extra_charge"=>(double)$item_ranges[$item->item_id]["extra_charge"]
														//"min"=>$item_ranges[$item->item_id]["start_range"]
														));?>
												</td>  
												<td>
													<?php echo form_input(array(
														'id'=>"total_sale$item->item_id",
														'class'=>'form-control form-inps',
														'name'=>"total_sale[$item->item_id]",
														"type"=>"number",
														"readonly"=>"",
														
														));?>
												</td> 										   
											</tr>
										<?php $i++;} } ?>								
									</tbody>
								</table>
								<input type="submit" id="enviar_form" value="<?php echo lang("sales_invoice") ?>" class="btn"/>
							</div>							
						</div>		

					</form>
							
				</div>			
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {		
		$( ".item_final_range" ).change(function(e) {
			let elemento_id= $(this).attr( 'id_total_sale' );
			let start_range= $(this).attr( 'start_range' );
			let extra_charge= $(this).attr( 'extra_charge' );
			let total= Number(start_range)+Number(extra_charge);
			$("#"+elemento_id).val(Math.abs($(this).val()- total));
		});
		$('#item_form_range').submit(function(e) {
			e.preventDefault();
			$('#enviar_form').attr('disabled','disabled');
			$('#enviar_form').attr('value','Enviando...');
			$.ajax({                        
				type: "POST",                 
				url: $(this).attr("action"),                    
				data: $(this).serialize(),
				success: function(data)            
				{
					location.href ="<?php echo site_url("sales")?>";   

				}
			});
        
        });		
		
	});
	
	
</script>


