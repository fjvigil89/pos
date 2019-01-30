<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                    	
						Pagos de  la línea de crédito - <?php echo $customer->first_name.' '.$customer->last_name;?>
                    </span>
                </div>
                <div class="tools" >                         
                      <button id="modal-button" type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>
			<?php
				$delete_payments_to_credit= $this->Employee->has_module_action_permission('sales', 'delete_payments_to_credit', 
				$this->Employee->get_logged_in_employee_info()->person_id);	
			?>
            <div class="portlet-body">
            	<div class="row">
				<div class="col-sm-12 margin-bottom-05">
				<h4><strong>Balance : </strong> <?php  echo to_currency( $customer->balance);?></h4>
				</div>
					<div class="col-sm-12 margin-bottom-05">
						<div class="table-responsive">
							<table id="table-petty-cash" class="table tablesorter table-striped table-bordered table-hover" width="100%">
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
									<?php foreach($petty_cash as $cash) { 	?>
								
										<tr>
											<td align="center">
												<?php echo $cash->petty_cash_id;?>
											</td>
											<td align="center">
												<?php echo date(get_date_format().' @ '.get_time_format(), strtotime($cash->petty_cash_time));?>
												</td>
											<td align="center">
												<?php echo $cash->payment_type ;?>
											</td>								

											<td align="center"><?php  echo to_currency($cash->monton_total);?></td>
											<?php if($delete_payments_to_credit):?>
												<td align="center">
													<button onclick="eliminar_abono(this)" data-id-petty-cash="<?php echo $cash->petty_cash_id;?>" class="btn btn-xs">Eliminar</button>
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
		
		$("#table-petty-cash").DataTable({
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

function eliminar_abono(elemento){
	let opt= confirm("Si elimina este registro el saldo del cliente se actualizará y el efectivo de  caja. ¿Realmente desea eliminar este registro ?");
	if(opt==1){
		let petty_cash_id = elemento.getAttribute("data-id-petty-cash");	
		$("#ajax-loader").show();
		$.post('<?php echo site_url("sales/delete_petty_cash");?>', {petty_cash_id: petty_cash_id},
		function(data)
		{					
			$("#modal-button").click();
			$("#register_container").html(data);					
		});
	}

	
	
}
</script>

