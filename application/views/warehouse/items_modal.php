<div class="modal-dialog modal-lg ">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        <?php echo "Información" ?>
                    </span>
                </div>
                <div class="tools">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
					<div class="col-sm-12 ">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" width="100%">
								<tr> <td><strong><?php echo lang("warehouse_estado"); ?></strong></td> <td> <?php echo $orden_info->state; ?></td></tr>
								<tr> <td><strong><?php echo lang("warehouse_numero"); ?></strong></td> <td> <?php echo $orden_info->number; ?></td></tr>
								<tr> <td><strong><?php echo "Vendedor" ?></strong></td> <td> <?php echo $vendedor->first_name." ".$vendedor->last_name; ?></td></tr>

								<tr> <td><strong><?php echo lang("warehouse_fecha"); ?></strong></td> <td> <?php echo date(get_date_format() . ' ' . get_time_format(), strtotime($orden_info->date)) ?></td></tr>
								
							</table>
						</div>
					</div>	
					<div class ="col-sm-12">
					<?php if (count($items)>0): ?>
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-info-circle"></i> 
								<span class="caption-subject bold">
									<?php echo lang("warehouse_cantidad_item"); ?>
								</span>
							</div>		               
						</div>
						<div class="portlet-body">
						
						<table class="table table-bordered table-hover table-striped" width="1200px">							
							<thead>
								<tr>
								<th ><?php echo lang ("items_name")?></th>
									<th ><?php echo lang ("items_item_number")?></th>
									<th ><?php echo lang("items_quantity")?></th>									
									<th ><?php echo lang("items_description")?></th>
									<th ><?php echo lang("items_supplier")?></th>
								</tr>							
							</thead>
							<tbody>
							<?php foreach ($items as $item) :
								$item_info= $this->Item->get_info($item->item_id);
								$proveedore = $this->Supplier->suppliers_info($item->item_id);
								?>
							
								<tr>
								<td style="text-align: center;"><?php echo $item_info->name ?></td>

									<td style="text-align: center;"><?php echo $item_info->item_number ?></td>
									<td style="text-align: center;"><?php echo to_quantity($item->quantity_purchased) ?></td>
									<td style="text-align: center;"><?php echo nl2br($item_info->description) ?></td>
									<td style="text-align: center;">
										<?php
											$data_proveedores= "";
											if(count($proveedore)>0){
												foreach ($proveedore as $proveedor) { 
													$data_proveedores.=$proveedor->company_name." / "; 
												}
											}
											echo 	$data_proveedores;										 
										 ?>
									
									</td>
									
								</tr>
						<?php endforeach;?>
							</tbody>
						</table>
						</div>
		            
        			</div>
											
				<?php endif; ?>	
					</div>				
					<div class="col-sm-12 ">
          
						<?php echo form_open_multipart('warehouse/update_state/'.$orden_info->order_sale_id,array('id'=>'order_form')); ?>

							<div class="form-body">
								<div class="row">
									
									<div class="col-sm-6 ">
										<div class="form-group">
											<label for="conetario" class="col-md-12 control-label wide">Comentarios:</label>										
											<div class="col-md-12">
												<textarea id="description" name="description" class="form-control form-textarea" row="2" cols="17"><?php echo  $orden_info->description ?></textarea>
											</div>
										</div>
									</div>
									<div class="col-sm-6 ">
										<div class="form-group">
											<label for="conetario" class="col-md-12 control-label wide">
												<a class="help_config_options  tooltips" data-placement="left" title="" >Estado</a>:
											</label>	
											<div class="col-md-12">
												<?php echo form_dropdown('state', $options, $orden_info->state, 'id="estado" class="bs-select form-control"'); ?>
											</div>
										</div>
									</div>
									<div class="   col-md-4 col-md-offset-5 margin-top-10 ">								

									<button id="submit-button" name="submit" class="btn green">Guardar</button>
									<button id="cancel-button" type="button" class="btn default">Cancelar</button>
									
									</div>
								</div>
								
							</div>						
						</form >
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

<script>
$("#order_form").submit(function(e){
	e.preventDefault();
	let estado="cambiar a pendiente";
	if($("#estado").val()=="<?php echo lang("warehouse_rechazado") ?>")
		estado="rechazar";
	if($("#estado").val()=="<?php echo lang("warehouse_entregado") ?>")
		estado="entregar";

	if (confirm("Desea "+estado+" esta orden?")){
		$("#submit-button").html("Guardando...");
		$("#submit-button").prop('disabled', true);
	
		$.post($("#order_form").attr('action'), $("#order_form").serializeArray(),function(data){
		
			if(JSON.parse(data).success!=true){
				$("#submit-button").html("Guardar");
				$("#submit-button").prop('disabled', false)
			}else{
				location.reload(true);				
			}		
		});
	}
});
$('#cancel-button').click(function(e){
	$('.close').click();
});

</script>


