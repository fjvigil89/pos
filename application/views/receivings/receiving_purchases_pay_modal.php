<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                    	
						<?=lang('receivings_last_100_purchases')?> - <?php echo $supplier->first_name.' '.$supplier->last_name;?>
                    </span>
                </div>
                <div class="tools" >                         
                      <button id="modal-button" type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>
			<?php
				$delete_payments_to_credit= $this->Employee->has_module_action_permission('receivings', 'delete_payments_to_credit', 
				$this->Employee->get_logged_in_employee_info()->person_id);	
			?>
            <div class="portlet-body">
            	<div class="row">
				<div class="col-sm-12 margin-bottom-05">
				<h4><strong>Balance : </strong> <?php  echo to_currency( $balance_total);?></h4>
				</div>
					<div class="col-sm-12 margin-bottom-05">
						<div class="table-responsive">
							<table id="table-pay-cash" class="table tablesorter table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>Fecha</th> 
										<th>Metodos de pago</th>
										<th>Total</th>
										<?php if($delete_payments_to_credit):?>
											<th></th>
										<?php endif;?>
									</tr>
								</thead>
								<tbody>
									<?php foreach($receivings as $cash) { 	?>
								
										<tr>
											<td align="center">
												<a href="<?php echo site_url('receivings/receipt/'.$cash->receiving_id); ?>"> <?php echo $cash->receiving_id;?></a>
												
												
											</td>
											<td align="center">
												<?php echo date(get_date_format().' @ '.get_time_format(), strtotime($cash->receiving_time));?>
												</td>
											<td align="center">
												<?php echo $cash->payment_type ;?>
											</td>								

											<td align="center"><?php  echo to_currency($cash->mount);?></td>
											<?php if($delete_payments_to_credit):?>
												<td align="center">
													<button onclick="eliminar_compra(this)" data-id-receiving="<?php echo $cash->receiving_id;?>" class="btn btn-xs">Eliminar</button>
												</td>
											<?php endif;?>

										</tr>
									<?php } ?>
								<tbody>
							
							</table>
						</div>
					</div>						
				</div>

				
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){
		
		$("#table-pay-cash").DataTable({
				"language": {
					"sProcessing":     "Procesando...",
					"sLengthMenu":     "Mostrar _MENU_ registros",
					"sZeroRecords":    "No se encontraron resultados",
					"sEmptyTable":     "Ningún dato disponible en esta tabla",
					"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix":    "",
					"sSearch":         "Buscar:",
					"sUrl":            "",
					"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "Primero",
						"sLast":     "Último",
						"sNext":     "Siguiente",
						"sPrevious": "Anterior"
					},

					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				},
				"order": [[ 0, "desc" ]]
			});
		});

function eliminar_compra(elemento){
	var mensaje = "<?=lang('receivings_If_you_delete_this_record_the_supplier')?>";
	let opt= confirm(mensaje);
	if(opt==1){
		let receiving_id = elemento.getAttribute("data-id-receiving");	
		$("#ajax-loader").show();
		$.post('<?php echo site_url("receivings/delete_receivings");?>', {receiving_id: receiving_id},
		function(data)
		{					
			$("#modal-button").click();
			$("#register_container").html(data);					
		});
	}

	
	
}
</script>

