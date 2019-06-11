<div class="modal-dialog modal-lg ">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        <?php echo "Información de la transacción" ?>
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
								<tr> <td><strong>Estado</strong></td> <td> <?php echo $item_info->transaction_status; ?></td></tr>
								<tr> <td><strong>Banco</strong></td> <td> <?php echo $item_info->name; ?></td></tr>
								<tr> <td><strong>Titular</strong></td> <td> <?php echo $item_info->titular_cuenta; ?></td></tr>

								<tr> <td><strong>Cuenta Bancaria</strong></td> <td> <?php echo $item_info->numero_cuenta; ?></td></tr>
								<tr> <td><strong>Tipo de cuenta</strong></td> <td> <?php echo $item_info->tipo_cuenta?></td></tr>

								<tr> <td><strong>Tipo de documento</strong></td> <td> <?php echo $item_info->tipo_documento; ?></td></tr>
								<tr> <td><strong>Número del documento del titular</strong></td> <td> <?php echo $item_info->numero_documento; ?></td></tr>
								<tr> <td><strong>Divisa</strong></td> <td> <?php echo lang("sales_" . $item_info->divisa); ?></td></tr>
								<tr> <td><strong>Tipo de transacción</strong></td> <td> <?php echo $item_info->opcion_sale ?></td></tr>

								<?php
									$total = 0;
									$tasa = ($item_info->tasa == 0 || $item_info->tasa == null) ? 1 : $item_info->tasa;
									
										$total = ($item_info->quantity_purchased * $item_info->item_unit_price) ;
										if($item_info->opcion_sale=="venta"){
											$total=$total/$tasa;
										}else{
											$total=$total*$tasa;	
										}
    									
									
									?>
								<tr> <td><strong><?php echo lang("sales_" . $item_info->divisa) ?></strong></td> <td> <?php echo  to_currency($total,3,lang("sales_".$item_info->divisa)." "); ?></td></tr>
								<tr> <td><strong>Total transfererido:</strong></td> <td><?php echo to_currency( $item_info->quantity_purchased * $item_info->item_unit_price)?></td></tr>

								<tr> <td><strong>Observaciones del ticket:</strong></td> <td><?php echo $item_info->comment ?></td></tr>
								<tr> <td><strong>Observaciones de la transferencia:</strong></td> <td><?php echo $item_info->observaciones ?></td></tr>

								<tr> <td><strong>Tasa</strong></td> <td> <?php echo (double)$tasa ?></td></tr>
								<tr> <td><strong><?php echo "Fecha de " . $item_info->transaction_status; ?> </strong></td><td> <?php echo $item_info->fecha_estado!=null? date(get_date_format() . ' ' . get_time_format(), strtotime($item_info->fecha_estado)):"No establecida " ?>  </td></tr>
								<tr> <td><strong>Celular(whatsapp):</strong></td> <td><?php echo $item_info->celular ?></td></tr>

								<tr> <td><strong>Comentarios:</strong></td> <td><?php echo $item_info->comentarios ?></td></tr>

							</table>
						</div>
					</div>					
					<div class="col-sm-12 ">
					 	<?php if ($this->Employee->has_module_action_permission('changes_house', 'refuse_approve',$this->Employee->get_logged_in_employee_info()->person_id)) {?>
          
						<?php echo form_open_multipart('changes_house/save/'.$item_info->sale_id."/".$item_info->item_id."/".$item_info->line,array('id'=>'item_form')); ?>

							<div class="form-body">
								<div class="row">
									
									<div class="col-sm-6 ">
										<div class="form-group">
											<label for="conetario" class="col-md-12 control-label wide">Comentarios:</label>										
											<div class="col-md-12">
												<textarea id="description" name="comentario" class="form-control form-textarea" row="50" cols="17"><?php echo  $item_info->comentarios ?></textarea>
											</div>
										</div>
									</div>
									<div class="col-sm-6 ">
										<div class="form-group">
											<label for="conetario" class="col-md-12 control-label wide">
												<a class="help_config_options  tooltips" data-placement="left" title="" >Estado</a>:
											</label>	
											<div class="col-md-12">
												<?php echo form_dropdown('estado', $options, $item_info->transaction_status, 'id="estado" class="bs-select form-control"'); ?>
											</div>
										</div>
									</div>
									<div class="col-sm-6 ">
										<div class="form-group">
										<label for="conetario" class="col-md-12 control-label wide">
												<a class="help_config_options  tooltips" data-placement="left" title="" >Imagen:</a>
											</label>	
											<div class="col-md-12">
												<?php echo form_upload(array(
													'name'=>'image_id',
													'id'=>'image_id',
													'class' => 'file form-control',
													'multiple' => "false",
													'data-show-upload' => 'false',
													'data-show-remove' => 'false',
													'value'=>3));
												?>
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
						<?php }
						else{
							echo '<div class="   col-md-4 col-md-offset-5 margin-top-10 ">';
							echo '<button id="cancel-button" type="button" class="btn default">Cancelar</button>';
							echo '</div >';
						}

						 ?>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

<script>

	$("#image_id").fileinput({
		/*initialPreview: [
			"<img src='<?php echo $item_info->file_id ? site_url('app_files/view_transfer/'.$item_info->file_id) : base_url().'img/no-photo.jpg'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
		],*/
		overwriteInitial: true,
		initialCaption: "Imagen"
	});
$("#item_form").submit(function(e){
	e.preventDefault();
	let estado="cambiar a pendiente";
	if($("#estado").val()=="Aprobada")
		estado="Aprobar";
	if($("#estado").val()=="Rechazada")
		estado="Rechazar";

	if (confirm("Desea "+estado+" esta orden?")){
		$("#submit-button").html("Guardando...");
		$("#submit-button").prop('disabled', true);

			var data = new FormData();
			jQuery.each($('input[type=file]')[0].files, function(i, file) {
				data.append('image_id', file);
			});
			var other_data = $("#item_form").serializeArray();
			$.each(other_data,function(key,input){
				data.append(input.name,input.value);
			});
			jQuery.ajax({
				url: $("#item_form").attr('action'),
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function(data){
					if(JSON.parse(data).success==true){
				location.reload(true);
			}else{
				$("#submit-button").html("Guardar");
				$("#submit-button").prop('disabled', false)
			}
				}
			});
	
		/*$.post($("#item_form").attr('action'), $("#item_form").serializeArray(),function(data){
		
					
		});*/
	}
});
$('#cancel-button').click(function(e){
	$('.close').click();
});

</script>


