<?php foreach ($dataServTecAbono->result() as $dataServTecAbono) { $abonado=$dataServTecAbono->Abonado;  } ?>

<div class="col-lg-8 table-responsive">
	<div class="portlet light">
		<input type="text" name="item" id="item" class="form-control" placeholder="<?php echo lang('common_search'); ?>">
		<div class="col-sm-10 no-padding" id="resultado_repuesto" style="position: absolute; z-index: 9000;"></div>
	</div>
	<table class="table table-hover table-bordered">
		<thead class="bg-grey">
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
					<td class="text-center"><span style="cursor: pointer;"class="fa fa-trash-o fa-2x font-red" onclick="controler('<?php echo site_url() ?>/technical_supports/eliminarArticuloSupport', 'id=<?php echo $row['unique_id']; ?>&uni=<?php echo $row['id']; ?>&nombre=<?php echo $row['nombre']; ?>&idSupport=<?php echo $idSupport ?>', 'refrescar')"></span></td>
					<td class="text-center"><?php echo $row['nombre']; ?></td>
					<td class="text-center"><?php echo to_currency("{$row['precio_con_iva']}", "2"); ?></td>
					<td width="5"><input type="text" name="cantidad" id="cantidad<?php echo $row['id']; ?>" value="<?php echo $row['cantidad']; ?>" class="text-center form-control cantidad" onchange="controler('<?php echo site_url() ?>/technical_supports/updateCantidadSupport','id=<?php echo $row['id']; ?>&idSupport=<?php echo $idSupport ?>&cantidad='+$('#cantidad<?php echo $row['id']; ?>').val(),'refrescar');"></td>
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
	<div class="portlet light no-padding">
		<ul class="list-group">
			<li class="list-group-item">
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
							<?php foreach ($articulos_iva as $data): ?>
								<tr>
									<td class="text-center"><?php echo $data['nombre'] ?></td>
									<?php if ($data['iva_uno'] != ""): ?>
										<td class="text-right"><?php echo $data['iva_uno'] . "% = " ?>
										<strong><?php $total = ($data['precio'] * ($data['iva_uno'] / 100)); echo to_currency("$total", "2"); ?></strong>
									</td>	
									<?php else: ?>
										<td></td>
									<?php endif; ?>
									<?php if ($data['iva_dos'] != ""): ?>
										<td class="text-right">
											<?php echo $data['iva_dos'] . "% = " ?>
											<strong><?php $total = ($data['precio'] * ($data['iva_dos'] / 100)); echo to_currency("$total", "2"); ?></strong>
										</td>
										<?php else: ?>
											<td></td>
										<?php endif; ?>
										<?php if ($data['iva_tres'] != ""): ?>
											<td class="text-right">
												<?php echo $data['iva_tres'] . "% = " ?>
												<strong><?php $total = ($data['precio'] * ($data['iva_tres'] / 100)); echo to_currency("$total", "2"); ?></strong>
											</td>
											<?php else: ?>
												<td></td>
											<?php endif; ?>
											<?php if ($data['iva_cuatro'] != ""): ?>
												<td class="text-right"><?php echo $data['iva_cuatro'] . "% = " ?>
												<strong><?php $total = ($data['precio'] * ($data['iva_cuatro'] / 100)); echo to_currency("$total", "2"); ?></strong>
											</td>
											<?php else: ?>
												<td></td>
											<?php endif; ?>
											<?php if ($data['iva_cinco'] != ""): ?>
												<td class="text-right"><?php echo $data['iva_cinco'] . "% = " ?>
												<strong><?php $total = ($data['precio'] * ($data['iva_cinco'] / 100)); echo to_currency("$total", "2"); ?></strong>
											</td>
											<?php else: ?>
												<td></td>
											<?php endif; ?>
											<td class="text-right"><?php $total = ($data['precio'] * ($data['iva_total'] / 100)) ?>
											<strong><?php echo to_currency("$total", "2") ?></strong>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif ?>
						</tbody>
					</table>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
			<div class="portlet-title padding">
				<div class="caption">
					<i class="fa fa-user"></i>
					<span class="caption-subject bold">Información del Cliente</span>
				</div>
			</div>
			<div class="portlet-body padding">
				<div class="customer-box">
					<div class="avatar">
						<?php if($dataServTecCliente->image_id !=''): ?>
							<img src="<?php echo site_url() ?>/app_files/view/<?php echo $dataServTecCliente->image_id ?>" class="img-thumbnail" alt="profile-photo" >
							<?php else: ?>
								<img src="<?php echo base_url() ?>/img/avatar.jpg" alt="Customer" class="img-thumbnail">
							<?php endif ?>												
						</div>
						<div class="information info-yes-email">
							<h4><?php echo $dataServTecCliente->first_name . " ". $dataServTecCliente->last_name  ?></h4>
							<a ></a>
							<span class="email"><?php echo $dataServTecCliente->email ?>
							<div class="md-checkbox-inline">
								<div class="md-checkbox">
									<input type="checkbox" name="email_receipt" value="1" id="email_receipt" class="email_receipt_checkbox">
									<label for="email_receipt">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										Enviar recibo al e-mail
									</label>
								</div>
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="portlet light no-padding">
			<div class="portlet-title padding bg-green">
				<div class="caption">
					<i style="color: white" class="icon-basket-loaded"></i>
					<span class="caption-subject bold" style="color: white">Resumen de la venta</span>
				</div>
				<div class="options">
					<?php /* http://localhost/desarrollo/pos/index.php/sales/view_shortcuts */ ?>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="pull-right tooltips" id="opener" data-original-title="Atajos de teclado"><i class="fa fa-keyboard-o" style="font-size: 30px; color: white"></i></a>
				</div>
			</div>
			<ul class="list-group">
				<div class="item-subtotal-block">
					<div class="num_items amount">
						<span class="name-item-summary">En la cesta de artículos:</span>
						<span class="pull-right badge badge-cart badge-warning">
							<?php if (isset($articulos_total)) echo $articulos_total ?>
						</span>
					</div>
					<div class="subtotal">
						<span class="name-item-summary"><?php echo lang('technical_supports_menj_car_subt'); ?>:</span>
						<span class="pull-right name-item-summary">
							<strong><?php if (isset($subtotal_total)) echo to_currency("$subtotal_total", "2") ?></strong>
						</span>
					</div>
				</div>
			</ul>
			<div class="amount-block">
				<div class="total amount">
					<div class="side-heading">
						Total:
					</div>
					<div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">

					</div>
				</div>
				<div class="total">
					<div class="side-heading">
						Cantidad a pagar:
					</div>
					<div id="amount-due" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">

					</div>
				</div>
			</div>
			<li class="list-group-item ">
				<span class="sale-info" > <i class="fa fa-money fa-lg font-green"></i> <?php echo lang("technical_supports_abono"); ?> <i class="fa fa-img-up"></i></span>
				<span class="sale-num pull-right"> <strong><?php if($abonado!=''){  echo to_currency("{$abonado}", "2"); }else{ $abonado=0;  echo to_currency("{$abonado}", "2"); } ?></strong> </span>


				<?php $restante = $precio_total - $abonado; ?>
				
			</li>
			<?php $total_de_payments = 0; if (isset($precio_total) and $precio_total != 0): ?>
			<div id="contenedor_pago">
				<?php if(isset($payments)) { ?>
					<div class="payments">
						<ul class="list-group">
							<?php foreach($payments as $payment_id=>$payment) { 
								$total_de_payments += floatval($payment['payment_amount']);
								?>
								<li class="list-group-item no-border-top">
									<span class="name-item-summary">
										<i class="fa fa-times-circle fa-lg font-red" class="delete_payment" onclick="controler('<?php echo site_url() ?>/technical_supports/deletePago', 'payment_id=<?php echo $payment_id ?>&idSupport=<?php echo $idSupport; ?>&precio_total=<?php echo $precio_total ?>', 'contenedor_pago')"></i>
										<?php echo $payment['payment_type']; ?>
									</span>
									<span class="name-item-summary pull-right">
										<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])):to_currency($payment['payment_amount']);?></strong>
									</span>
								</li>
							<?php } ?>
							<ul>
							</div>
						<?php } ?>
					</div>
					<div class="add-payment">
						<form action="javascript:void(0)" method="post" accept-charset="utf-8" id="add_payment_form" autocomplete="off">
							<input type="hidden" name="precio_total" value="<?php echo $precio_total ?>">
							<input type="hidden" name="idSupport" value="<?php echo $idSupport ?>">
							<ul class="list-group">
								<li class="list-group-item no-border-top tier-style" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px none;">
									<div class="row">
										<div class="col-md-6">
											<span class="name-addpay">
												<a class="sales_help tooltips" data-placement="left" title="" data-original-title="Aquí puedes poner el tipo de pago  y puedes pagar con diferentes tipos de pagos ">Agregar Pago </a>:
											</span>
										</div>
										<div class="col-md-6">
											<span class="">
												<select name="payment_type" id="payment_types" class="bs-select form-control">
													<option value="Efectivo" selected="selected">Efectivo</option>
													<option value="Cheque">Cheque</option>
													<option value="Tarjeta de Regalo">Tarjeta de Regalo</option>
													<option value="Tarjeta de Débito">Tarjeta de Débito</option>
													<option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
												</select>
											</div>
										</div>
										<div class="row margin-top-10">
											<div class="col-md-12">
												<div class="input-group">
													<input type="text" name="amount_tendered" value="0.00" id="amount_tendered" class="form-control form-inps-sale" accesskey="p">													<span class="input-group-btn">
														<input class="btn btn-success" type="button" id="add_payment_button" value="Agregar Pago" onclick="controler('<?php echo site_url() ?>/technical_supports/agregarPago', $('#add_payment_form').serialize(), 'contenedor_pago')">
													</span>
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item no-border-top tier-style">
										<div class="contenedor_submit">
											<?php if ("$total_de_payments" === "$restante"): ?>
												<input type="button" class="btn btn-success btn-large btn-block" id="finish_sale_button" value="Completar Venta">
												<?php else: ?>
													<script type="text/javascript">
														$('#contenedor_submit').html('');
													</script>
												<?php endif; ?>
											</div>
										</li>
									</ul>
								</form>
							</div>
						<?php endif ?>
						<div id="finish_sale" class="finish-sale">
						</div>
					</div>
				</div>



				<script type="text/javascript">




					$("#item").keyup(function () {
						var value = $(this).val();
						if (value == '') {
							$('#resultado_repuesto').html('');
						} else {
							$('#resultado_repuesto').load("<?php echo site_url() ?>/technical_supports/buscarItemsSupport", "idSupport=<?php echo $idSupport ?>&buscar=" + value);
						}
						return false;
					});

					$(document.body).click(function () {
						$('#item').val('');
						$('#resultado_repuesto').html('');
					});

					$('.animation-count').data('countToOptions', {
						formatter: function (value, options) {
							return value.toFixed(options.decimals).replace('', '<?php echo $this->config->item("currency_symbol")?>').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
						}
					});

					$('.cantidad').focus();

					<?php if(isset($payments) and count($payments) > 0) {

						$saldo_total = 0;
						$total_restar = 0;

						foreach ($payments as $payment_id => $payment) {
							$total_restar += $payment['payment_amount'];
						} 



						$saldo_total = $restante - $total_restar;

					} else {

						$saldo_total = $restante;

					} ?>

					var last_total_amount_due = <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($saldo_total)) : to_currency_no_money($saldo_total)?>;
					var last_total_amount = <?php echo $this->config->item('round_value') == 1 ? to_currency_no_money(round($precio_total)) : to_currency_no_money($precio_total)?>;

					$('#total-amount').data('to', last_total_amount);
					$('#amount-due').data('to', last_total_amount_due);
					$('.animation-count').each(count);

					function count(options) {
						var $this = $(this);
						options = $.extend({}, options || {}, $this.data('countToOptions') || {});
						$this.countTo(options);
					}

					if (last_total_amount_due == 0) {
						$('#add_payment_button').attr("disabled", true);
					} else {
						$('#add_payment_button').attr("disabled", false);
					}

					$('#amount_tendered').val(last_total_amount_due);

				</script>