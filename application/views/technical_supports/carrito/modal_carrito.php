<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="display:block;overflow-y: auto">
    <div class="modal-dialog modal-lg" style="width: 96%">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header"
                style="height: 90px;background: url('img/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" title="Cerrar la ventana" class="btn btn-danger"
                    style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;"
                    data-dismiss="modal"
                    onclick="controler('<?php echo site_url() ?>/technical_supports/index/','quien=1','contTabla',$('#ventanaVer').html(''));">
                    <span aria-hidden="true">X</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><?php echo lang("technical_supports_orden_titu"); ?></h4>
                <small class="font-bold"><?php echo lang("technical_supports_orden_titu_menj"); ?></small>
            </div>
           <?= $this->load->View("technical_supports/carrito/carrito_cuerpo"); ?>
        </div>
    </div>
</div>

										
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
												<form action="javascript:void(0)" method="post" accept-charset="utf-8" id="add_payment_form" autocomplete="off" onsubmit="controler('<?php echo site_url() ?>/technical_supports/facturacion', $('#add_payment_form').serialize(), 'refrescar')">
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
																<div id="contenedor_submit">
																	<?php if ("$total_de_payments" == "$restante"): ?>
																		<input type="submit" class="btn btn-success btn-large btn-block" id="finish_sale_button" value="Completar Venta">																			
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
									</div>
								</div>
							</div>
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

