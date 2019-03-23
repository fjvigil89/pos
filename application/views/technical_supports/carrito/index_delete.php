<div class="portlet light">
	<input type="text" name="item" id="item" class="form-control" placeholder="<?php echo lang('common_search'); ?>">
	<div class="col-sm-10 no-padding" id="resultado_repuesto" style="position: absolute; z-index: 9000;"></div>
</div>
<div class="portlet light table-responsive">
	<div id="tala_carrito">
		<table class="table table-hover">
			<thead>
			<tr>
				<th></th>
				<th><?php echo lang('technical_supports_titu_car_art'); ?></th>
				<th><?php echo lang('technical_supports_titu_car_pre'); ?></th>
				<th><?php echo lang('technical_supports_titu_car_cant'); ?></th>
				<th><?php echo lang('technical_supports_titu_subt'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php if (isset($carrito) and is_array($carrito)): ?>
				<?php foreach ($carrito as $row): ?>
					<tr>
						<td class="text-center"><span style="cursor: pointer;" class="fa fa-trash-o fa-2x font-red"
													  onclick="controler('<?php echo site_url() ?>/technical_supports/eliminarArticulo', 'id=<?php echo $row['unique_id']; ?>&nombre=<?php echo $row['nombre']; ?>&idSupport=<?php echo $idSupport ?>', 'tala_carrito')"></span>
						</td>
						<td class="text-center"><?php echo $row['nombre']; ?></td>
						<td class="text-center"><?php echo to_currency("{$row['precio_con_iva']}", "2"); ?></td>
						<td width="5"><input type="text" name="cantidad" id="cantidad<?php echo $row['id']; ?>" value="<?php echo $row['cantidad']; ?>" class="text-center form-control cantidad" onchange="controler('<?php echo site_url() ?>/technical_supports/updateCantidad','id=<?php echo $row['id']; ?>&idSupport=<?php echo $idSupport ?>&cantidad='+$('#cantidad<?php echo $row['id']; ?>').val(),'tala_carrito');"></td>
						<td class="text-center"><?php echo to_currency("{$row['total']}", "2"); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="6"><h4 class="text-center"><code><?php echo lang('technical_supports_menj_car'); ?></code></h4></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<div class="portlet light margin-top-15 no-padding">
			<ul class="list-group">
				<div class="item-subtotal-block">
					<div class="num_items amount">
						<span class="name-item-summary"><?php echo lang('technical_supports_menj_car_cestart'); ?>:</span>
						<span class="pull-right badge badge-cart badge-success"><?php if (isset($articulos_total)) {
								echo $articulos_total;
							} ?></span>
					</div>
					<div class="subtotal">
						<span class="name-item-summary"><?php echo lang('technical_supports_menj_car_subt'); ?>:</span>
						<span class="pull-right name-item-summary"><strong><?php if (isset($precio_subtotal)) {
									echo to_currency("$precio_subtotal", "2");
								} ?></strong></span>
					</div>
				</div>
				<li class="list-group-item" style="border: 0px none;">
					<h5 class="text-center"><?php echo lang('technical_supports_menj_car_prodiva'); ?></h5>
					<table class="table">

						<thead>
						<tr>
							<th><?php echo lang('technical_supports_menj_car_product'); ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php if (isset($articulos_iva)): ?>
							<?php foreach ($articulos_iva as $data) { ?>
								<tr>
									<td class="text-center"><?php echo $data['nombre'] ?></td>
									<?php if ($data['iva_uno'] != ""): ?>
										<td class="text-right"><?php echo $data['iva_uno'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_uno'] / 100));
												echo to_currency("$total", "2"); ?></strong></td>
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<?php if ($data['iva_dos'] != ""): ?>
										<td class="text-right">

											<?php echo $data['iva_dos'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_dos'] / 100));
												echo to_currency("$total", "2"); ?></strong></td>
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<?php if ($data['iva_tres'] != ""): ?>
										<td class="text-right">

											<?php echo $data['iva_tres'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_tres'] / 100));
												echo to_currency("$total", "2"); ?></strong></td>
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<?php if ($data['iva_cuatro'] != ""): ?>
										<td class="text-right"><?php echo $data['iva_cuatro'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_cuatro'] / 100));
												echo to_currency("$total", "2"); ?></strong></td>
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<?php if ($data['iva_cinco'] != ""): ?>
										<td class="text-right"><?php echo $data['iva_cinco'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_cinco'] / 100));
												echo to_currency("$total", "2"); ?></strong></td>
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<td class="text-right"><?php $total = ($data['precio'] * ($data['iva_total'] / 100)) ?>
										<strong><?php echo to_currency("$total", "2") ?></strong>
									</td>
								</tr>
							<?php } ?>
						<?php endif ?>
						</tbody>
					</table>
				</li>
				<li class="list-group-item" style="border: 0px none;">
					<h5 class="text-center"><?php echo lang('technical_supports_menj_car_ivat'); ?></h5>
				</li>
				<?php if (isset($iva_general)): ?>
					<?php if ($iva_general[0] != 0): ?>
						<li class="list-group-item" style="border: 0px none;">
				<span class="name-item-summary">
				<?php echo $iva_general[0] ?> %
				</span>
							<span class="name-item-summary pull-right">
					<strong><?php $iva_cero = ($iva_general['total'] * ($iva_general[0] / 100)); ?><?php echo to_currency("$iva_cero", "2") ?></strong>
				</span>
						</li>
					<?php endif ?>
					<?php if ($iva_general[1] != 0): ?>
						<li class="list-group-item" style="border: 0px none;">
				<span class="name-item-summary">
				<?php echo $iva_general[1] ?> %
				</span>
							<span class="name-item-summary pull-right">
					<strong><?php $iva_uno = ($iva_general['total'] * ($iva_general[2] / 100)); ?><?php echo to_currency("$iva_uno", "2") ?></strong>
				</span>
						</li>
					<?php endif ?>
					<?php if ($iva_general[2] != 0): ?>
						<li class="list-group-item" style="border: 0px none;">
				<span class="name-item-summary">
				<?php echo $iva_general[2] ?> %
				</span>
							<span class="name-item-summary pull-right">
					<strong><?php $iva_dos = ($iva_general['total'] * ($iva_general[2] / 100)); ?><?php echo to_currency("$iva_dos", "2") ?></strong>
				</span>
						</li>
					<?php endif ?>
					<?php if ($iva_general[3] != 0): ?>
						<li class="list-group-item" style="border: 0px none;">
				<span class="name-item-summary">
				<?php echo $iva_general[3] ?> %
				</span>
							<span class="name-item-summary pull-right">
					<strong><?php $iva_tres = ($iva_general['total'] * ($iva_general[3] / 100)); ?><?php echo to_currency("$iva_tres", "2") ?></strong>
				</span>
						</li>
					<?php endif ?>
					<?php if ($iva_general[4] != 0): ?>
						<li class="list-group-item" style="border: 0px none;">
				<span class="name-item-summary">
				<?php echo $iva_general[4] ?> %
				</span>
							<span class="name-item-summary pull-right">
					<strong><?php $iva_cuatro = ($iva_general['total'] * ($iva_general[4] / 100)); ?><?php echo to_currency("$iva_cuatro", "2") ?></strong>
				</span>
						</li>
					<?php endif ?>
				<?php endif ?>
			</ul>
			<div class="amount-block">
				<div class="total amount">
					<div class="side-heading">
						Total:
					</div>
					<div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000"
						 data-decimals="2"><?php if (isset($precio_total)) {
							echo to_currency("$precio_total", "2");
						} ?></div>
				</div>
				<div class="total">
					<div class="side-heading">
						Cantidad a pagar:
					</div>
					<div id="amount-due" class="amount animation-count text-danger" data-speed="1000"
						 data-decimals="2"><?php if (isset($precio_total)) {
							echo to_currency("$precio_total", "2");
						} ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">

	$('.animation-count').data('countToOptions', {
		formatter: function (value, options) {
			return value.toFixed(options.decimals).replace('', '<?php echo $this->config->item("currency_symbol")?>').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		}
	});


	$('.cantidad').focus();


	var last_total_amount_due = <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($precio_total)) : to_currency_no_money($precio_total)?>;
	var last_total_amount = <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($precio_total)) : to_currency_no_money($precio_total)?>;
	$('#total-amount').data('to', last_total_amount);
	$('#amount-due').data('to', last_total_amount_due);
	$('.animation-count').each(count);

	function count(options) {
		var $this = $(this);
		options = $.extend({}, options || {}, $this.data('countToOptions') || {});
		$this.countTo(options);
	}
</script>

<script>
	$("#item").keyup(function () {
		var value = $(this).val();
		if (value == '') {
			$('#resultado_repuesto').html('');
		} else {
			$('#resultado_repuesto').load("<?php echo site_url() ?>/technical_supports/buscarItems", "idSupport=<?php echo $idSupport ?>&buscar=" + value);
		}
		return false;
	});
	$(document.body).click(function () {
		$('#item').val('');
		$('#resultado_repuesto').html('');
	});
</script>

<?php foreach ($dataSupport->result() as $dataSupport) { $status=$dataSupport->state; $idSupport=$dataSupport->Id_support; $garantia=$dataSupport->do_have_guarantee; $fgarantia=$dataSupport->date_garantia; $costo=$dataSupport->repair_cost; } ?>

<div class="portlet box green">
	<div class="portlet-title padding">
		<div class="caption">
			<span class="caption-subject bold"><?php echo lang("technical_supports_datos_entrega_garantia"); ?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-sm-12">
				<form id="form_dt" action="javascript:void(0)" onsubmit='controlerConfirm("<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/", $("#form_dt").serialize(), "asigFallas2","Estas seguro de cambiar el estado del equipo a REPARADO, al confirmar el equipo estara disponible para la entrega")'>
					<div class="row">

						<div class="col-sm-12">
							<?php if ($status!="RECHAZADO") { ?>
								<div class="note" style="height: auto;overflow: hidden;">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label">
												<p class="bold"><?php echo lang("technical_supports_tiene_garantia"); ?>  <span class="fa fa-check-circle"></span> </p></label>

												<div>
													<?php
													echo '<div class="text-right md-radio-inline" style="width: 40%;float: left;margin-left: 15px;">';
													echo '<div class="md-radio">';
													echo form_radio(array(
														'name' => 'do_have_accessory',
														'id' => 'do_have_garantia_yes',
														'value' => '1',
														'class' => 'md-check',
														'checked' => $garantia != 1 ? 1 : 1)
												);
													echo '<label id="show_comment_invoice" for="do_have_garantia_yes">';
													echo '<span></span>';
													echo '<span class="check"></span>';
													echo '<span class="box"></span>';
													echo lang('technical_supports_yes');
													echo '</label>';
													echo '</div>';
													echo '</div>';

													echo '<div class="md-radio-inline" style="width: 40%;float: left;margin-left: 15px;">';
													echo '<div class="md-radio">';
													echo form_radio(array(
														'name' => 'do_have_accessory',
														'id' => 'do_have_garantia_no',
														'value' => '0',
														'class' => 'md-check',
														'checked' => $garantia != 0 ? 0 : 1)
												);
													echo '<label  for="do_have_garantia_no">';
													echo '<span></span>';
													echo '<span class="check"></span>';
													echo '<span class="box"></span>';
													echo lang('technical_supports_no');
													echo '</label>';
													echo '</div>';
													echo '</div>';
													?>
												</div>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group" id="accessory" style="display:none">
												<label class="control-label">
													<a class="help_config_required tooltips" data-placement="left"
													title="<?php echo lang("technical_supports_garantia") ?>"><?php echo lang('technical_supports_garantia') ?></a>
												</label>
												<input type="date" id="fecha_garantia" name="fecha_garantia" value="<?php echo $fgarantia; ?>" class="form-control">
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group col-sm-8">
												<label for="comentarios" class=""><?php echo lang("technical_supports_comentarios_entrega"); ?></label>
												<textarea name="comentarios" id="comentarios" placeholder="<?php echo lang("technical_supports_ingrese_reporte"); ?>"
													class="form-control"></textarea>
												</div>
												<div class="form-group col-sm-4">
													<label for="costo"><?php echo lang("technical_supports_repair_cost"); ?></label>
													<input type="number" id="costo" name="costo" value="<?php echo $costo; ?>" class="form-control">
												</div>

											</div>
										</div>
									<?php } ?>
									<div class="col-sm-12" style="text-align: right;">
										<input type="hidden" id="acept" name="acept" value="2">
										<input type="hidden" id="supprt" name="supprt" value="<?php echo $idSupport; ?>">
										<?php if ($status!="RECHAZADO" and $status!="REPARADO") { ?>
											<button type="submit" id="form_dt" class="btn btn-primary"> <span class="fa fa-save"></span> Marcar como Reparado </button>
											<div id="rechz" style="float: right;margin-left: 7px;">
												<a href="javascript:void(0);" class="btn btn-primary" title="Devolver el equipo" style="color: #FFFFFF;" onclick="controlerConfirm('<?php echo site_url() ?>/technical_supports/asignar_detalles_falla_tec/','rechaz=2&supprt=<?php echo $idSupport; ?>','asigFallas2','Estas seguro de RECHAZAR el servicio, al confirmar el equipo estara disponible para la entrega');">
													<span class="fa fa-save"></span> Rechazar
												</a>
											</div>
										<?php }
										if ($status=="RECHAZADO" Or $status=="REPARADO") { ?>
											<script type="text/javascript">
												window.location='<?php echo site_url('technical_supports'); ?>';
											</script>
									<?php /*<a href="javascript:void(0);"  class="btn btn-primary" title="Equipo reparado" style="color: #FFFFFF;" onclick="window.location='<?php echo site_url('technical_supports'); ?>'">
                                    <span class="fa fa-search"></span> Servicio Rechazado Listar
                                    </a> */ ?>
                                <?php }  ?>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if($dataCond!="") {
	?>
	<script type="text/javascript">
		$(document).ready(function (response) {
			event.preventDefault();
			$('#spin').removeClass('hidden');

			$.getJSON($(this).attr('href'), function(response)
			{
				$('#spin').addClass('hidden');
				alert(response.message);
			});
			toastr.success(response.message, <?php echo json_encode(lang('technical_supports_asig_estado')." ".$dataCond); ?>);
			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
	</script>
	<?php
}
if($instrp==1) {
	?>
	<script type="text/javascript">
		$(document).ready(function (response) {
			event.preventDefault();
			$('#spin').removeClass('hidden');

			$.getJSON($(this).attr('href'), function(response)
			{
				$('#spin').addClass('hidden');
				alert(response.message);
			});
			toastr.success(response.message, <?php echo json_encode(lang('technical_supports_respuesto_add')); ?>);
			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
	</script>
	<?php
}
if($elmrp==1) {
	?>
	<script type="text/javascript">
		$(document).ready(function (response) {
			event.preventDefault();
			$('#spin').removeClass('hidden');

			$.getJSON($(this).attr('href'), function(response)
			{
				$('#spin').addClass('hidden');
				alert(response.message);
			});
			toastr.error(response.message, <?php echo json_encode(lang('technical_supports_respuesto_delet')); ?>);
			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
	</script>
	<?php
}
if($t_resp==1) {
	?>
	<script type="text/javascript">
		$(document).ready(function (response) {
			event.preventDefault();
			$('#spin').removeClass('hidden');

			$.getJSON($(this).attr('href'), function(response)
			{
				$('#spin').addClass('hidden');
				alert(response.message);
			});
			toastr.error(response.message, <?php echo json_encode(lang('technical_supports_adv_asig_resp')); ?>);
			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
	</script>
	<?php
}
?>
<script type="text/javascript">
	$("#do_have_garantia_yes").click(function () {
		$('div[id^="accessory"]').show();
		$('div[id^="rechz"]').hide();
	});
	$("#do_have_garantia_no").click(function () {
		$('div[id^="accessory"]').hide();
		$('div[id^="rechz"]').show();
	});
</script>
<?php
if($garantia==1) {
	?>
	<script type="text/javascript">
		$('div[id^="accessory"]').show();
	</script>
	<?php
}