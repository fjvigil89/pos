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
				<td class="text-center"><span style="cursor: pointer;" class="fa fa-trash-o fa-2x font-red" onclick="controler('<?php echo site_url() ?>/technical_supports/eliminarArticulo', 'id=<?php echo $row['unique_id']; ?>&nombre=<?php echo $row['nombre']; ?>&idSupport=<?php echo $idSupport ?>', 'tala_carrito')"></span></td>
				<td class="text-center"><?php echo $row['nombre']; ?></td>
				<td class="text-center"><?php echo to_currency("{$row['precio_con_iva']}", "2"); ?></td>
				<td width="5"><input type="text" name="cantidad"  id="cantidad<?php echo $row['id']; ?>" value="<?php echo $row['cantidad']; ?>" class="text-center form-control cantidad" onchange="controler('<?php echo site_url() ?>/technical_supports/updateCantidad','id=<?php echo $row['id']; ?>&idSupport=<?php echo $idSupport ?>&cantidad='+$('#cantidad<?php echo $row['id']; ?>').val(),'tala_carrito');"></td>
				<td class="text-center"><?php echo to_currency("{$row['total']}", "2"); ?></td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="5"><h4 class="text-center"><code><?php echo lang('technical_supports_menj_car'); ?></code></h4></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
<div class="portlet light margin-top-15 no-padding">

	<ul class="list-group">
		<div class="item-subtotal-block">
			<div class="num_items amount">
				<span class="name-item-summary"><?php echo lang('technical_supports_menj_car_cestart'); ?>:</span>
				<span class="pull-right badge badge-cart badge-success">
					<?php if (isset($articulos_total)) { echo $articulos_total;} ?>
				</span>
			</div>
			<div class="subtotal">
				<span class="name-item-summary"><?php echo lang('technical_supports_menj_car_subt'); ?>:</span>
				<span class="pull-right name-item-summary">
					<strong>
						<?php if (isset($subtotal_total)) { echo to_currency("$subtotal_total", "2");} ?>
					</strong>
				</span>
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
					<strong><?php $iva_uno = ($iva_general['total'] * ($iva_general[1] / 100)); ?><?php echo to_currency("$iva_uno", "2") ?></strong>
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
			<div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">
				<?php if (isset($precio_total)) { echo to_currency("$precio_total", "2");} ?>
			</div>
		</div>
		<div class="total">
			<div class="side-heading">
				Cantidad a pagar:
			</div>
			<div id="amount-due" class="amount animation-count text-danger" data-speed="1000" data-decimals="2">
				<?php if (isset($precio_total)) { echo to_currency("$precio_total", "2");} ?>
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