<div class="modal-dialog modal-lg ">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        <?php echo "Productos en el carrito" ?>
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
								<?php foreach($cart as $item): ?>
									<tr> <td><strong>Banco</strong></td> <td> <?php echo $item["name"]; ?></td></tr>
									<tr> <td><strong>Titular</strong></td> <td> <?php echo $item["titular_cuenta"]; ?></td></tr>
									<tr> <td><strong># de cuenta</strong></td> <td> <?php echo $item["numero_cuenta"]; ?></td></tr>
									<tr> <td><strong>Documento/tipo</strong></td> <td> <?php echo $item["numero_documento"]." / ".$item["tipo_documento"]; ?></td></tr>
									<tr> <td><strong>Tipo de cuenta</strong></td> <td> <?php echo $item["tipo_cuenta"];?></td></tr>
									<tr> <td><strong>Celular(whatsapp)</strong></td> <td> <?php echo $item["celular"];?></td></tr>
									<tr> <td><strong>Observaciones de la transferencia.</strong></td> <td> <?php echo $item["observaciones"];?></td></tr>

									<?php
									$total = 0;
									$tota_peso=0;

									$tasa = $item["tasa"];									
									$total_peso = ($item["quantity"]* $item["price"]) ;
									if($opcion_sale=="venta"){
										$total=$total_peso/$tasa;
									}else{
										$total=$total_peso*$tasa;	
									}   									
									
									?>
									<tr> <td><strong><?php  echo lang('sales_total')." peso / ".lang('sales_total')." ".lang('sales_'.$divisa); ?> </strong></td> <td> <?php echo to_currency($total_peso)." / ".  to_currency($total,3,lang("sales_".$divisa)." ");?></td></tr>
									<tr> 
										<td align="center" colspan="2">
											<?php echo anchor(site_url('sales/delete_item/'.$item["line"]),"Eliminar",
                                        		array('class' => 'btn red-thunderbird btn-sm delete_item'));
                                    		?>
											<?php echo anchor(site_url('sales/get_item_cart/'.$item["line"]), "Editar",
                                     		   array('class' => 'btn btn-sm green item-line'));
                                    		?>
										</td>
									</tr>

								<?php endforeach;?>
								

							</table>
						</div>
					</div>					
					
				</div>


			</div>
		</div>
	</div>
</div>

<script>

$('#cancel-button, .delete_item').unbind( "click" ).click(function(e){
	$('.close').click();
	$("#add_payment_button").hide();
	$("#submit-button").hide();
	$("#finish_sale_button").hide();
});
$('.delete_item').unbind( "click" ).click(function(event){
	event.preventDefault();
	$("#resumen-venta").load($(this).attr('href'));
	$('.close').click();
	$("#add_payment_button").hide();
	$("#submit-button").hide();
	$("#finish_sale_button").hide();

});
$('.item-line').unbind( "click" ).click(function(event){
	event.preventDefault();
	$("#submit-button").hide();
	$.post(''+$(this).attr('href'), {},
	function(data){
		data=JSON.parse(data);
		$('.close').click();
		$("input[name='line']").val(data.line);
		$('#item').val(data.item_id);
        $('#numero_cuenta').val(data.numero_cuenta);		
		$("#tipo_cuenta").val(data.tipo_cuenta);
		$('#docuemento').val(data.numero_documento);
		$("#tipo_docuemento").val(data.tipo_documento);
        $('#titular_cuenta').val(data.titular_cuenta);
		$('#cantidad_peso').val(data.quantity*data.price);
		$('#observaciones').val(data.observaciones);
		$('#celular').val(data.celular);
		$('#item').prop('disabled', true);
		$('#item').selectpicker('refresh');
		$('#tipo_documento').selectpicker('refresh');
		$('#tipo_cuenta').selectpicker('refresh');
		$("#cantidad_peso").change();	
		

		$("#submit-button").show();

	}
);
});
</script>


