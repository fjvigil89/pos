<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                    	
                      Cierre de rangos
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" id="modal-button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">				
					<div class="col-md-12">
					<?php echo form_open('sales/save_range/',array('id'=>'item_form','class'=>'form-horizontal ')); ?>

						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped text-center">
								<thead>
									<tr>
										<th style="text-align: center;"><?php echo "Producto"?></th>
										<th style="text-align: center;"><?php echo "Rango de inicio";?></th>
										<th style="text-align: center;"><?php echo "Saldo agregado";?></th>
										<th style="text-align: center;"><?php echo "Rango final";?></th>
										<th style="text-align: center;"><?php echo "Ventas";?></th>

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
														"value"=>(double)$item_ranges[$item->item_id]["final_range"]
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
								<input type="submit" value="Facturar" class="btn"/>
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
			$("#"+elemento_id).val(Math.abs($(this).val()-start_range));
		});		
		
	});
	
	
</script>


