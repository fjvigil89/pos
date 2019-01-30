<?php $this->load->view("partial/header"); ?>
<style>
	table {

		width: 100%;
	}

	.border {
		border: none;
		border-bottom: 1px solid #333;
	}

	.fondo{

		background: #eee;
	}

	#payments tr{

		border-bottom: 1px solid #777;

	}

	table tr th{
		padding: 5px 7px;
	}

	table tr td{
		padding: 3px 7px;
	}

}

</style>
<div class="portlet light no-padding-general hidden-print">
	<div class="portlet-body">
		<div class="row margin-top-15 hidden-print">
			<div class="col-lg-2 col-md-2 col-sm-12 " style="text-align:right;">
				<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="print_detail_register()" >
					<?php echo lang('sales_print'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet light no-padding-general">
	<div class="portlet-body">
		<?php
		 $currency_symbol    = $this->config->item('currency_symbol');
		 $decimal_separator  = $this->config->item('decimal_separator');
		 $thousand_separator = $this->config->item('thousand_separator');
		 ?>
 		<div id="receipt_general_info">
 			<h3 style="border-bottom:2px solid #333;padding:7px;">
 				Reporte de la caja '<?=$info_register->name;?>'
 			</h3>
 			 <div class="border">
 				<table>
 					<tr class="fondo">
 						<th>
 							Fecha de apertura
 						</th>
 						<th  width="50%">
 							Fecha de cierre
 						</th>
 					</tr>
 					<tr  class="border">
 						<td>
 							<?=  date(get_date_format().' @ '.get_time_format(), strtotime($start_date));?>
 						</td>
 						<td>
						 
 							<?= date(get_date_format().' @ '.get_time_format(), strtotime($end_date));?>
 						</td>
 					</tr>
 					<tr class="fondo">
 						<th>
 							Monto de apertura
 						</th>
 						<th>
 							Monto de cierre
 						</th>
 					</tr>
 					<tr>
 						<td  width="50%">
 							<?=$currency_symbol.number_format($amount_register_open, 2, $decimal_separator, $thousand_separator);?>
 						</td>
 						<td>
 							<?=$currency_symbol.number_format($amount_register_close, 2, $decimal_separator, $thousand_separator);?>
 						</td>
 					</tr>


 				</table>

 			</div>
 			<div class="border">

 				<table>
 					<tr class="fondo">
						<th>
 					 		Cantidad de ventas
 						</th>
 						<th>
 							Total en ventas
 						</th>
 					</tr>
 					<tr>
 						<td  width="50%">
 							<?= $total_sales; ?>
 						</td>
 						<td>
 							<?= $currency_symbol.number_format($amount_sales, 2, $decimal_separator, $thousand_separator); ?>
 						</td>
 					</tr>

 				</table>

 			</div>
			 <!-- abonos linea de creditos -->
				<?php if ($credito_tienda): ?>

				<div class="border">

					<table>
						<tr class="fondo">
							<th >
								Pagos de crédito
							</th>
							<th>
								Total en pagos
							</th>
						</tr>
						<tr>
							<td  width="50%">
								<?= lang("sales_store_account_payment") ?>
							</td> 
							<td>
								<?= $currency_symbol.number_format($payments_petty_cash, 2, $decimal_separator, $thousand_separator); ?>
							</td>
						</tr>

					</table>
				</div>
				 <?php endif; ?>
				 <div class="border">

					<table>
						<tr class="fondo">
							<th >
								Entrada/salida
							</th>
							<th>
								Totales
							</th>
						</tr>
						<tr>
							<td  width="50%">
								<?= lang("reports_entrada") ?>
							</td> 
							<td>
								<?= $currency_symbol.number_format($entrada, 2, $decimal_separator, $thousand_separator); ?>
							</td>
						</tr>
						<tr>
							<td  width="50%">
								<?= lang("reports_salida") ?>
							</td> 
							<td>
								<?= $currency_symbol.number_format($salida, 2, $decimal_separator, $thousand_separator); ?>
							</td>
						</tr>

					</table>

				</div>


 			<div>

 				<div style="border-bottom:1px solid #111;">
 					<label style="font-size:18px;padding:5px 7px;"><b>Pagos</b></label>
 				</div>
 				<div>

 					<table id="payments">

 						<tr style="background:#eee;">
 							<th>Método de pago</th>
 							<th>Cantidad</th>
 							<th>Monto total</th>
 						</tr>

 						<?php

 							$total_payments  = 0;
 							$amount_payments = 0;

 							foreach ($payments_types as $key => $type) {

 								$cantidad        = isset($payments[$type]) ? count($payments[$type]) : 0;
 								$total_payments  = $total_payments + $cantidad;
 								$total           = isset($payments[$type]) ? array_sum($payments[$type]) : 0;
 								$amount_payments = $amount_payments + $total;
 								$total           = number_format($total, 2, $decimal_separator, $thousand_separator);
 						?>
	 							<tr>
	 								<td>
	 									<?=$type;?>
	 								</td>
	 								<td>
	 									<?= $cantidad;?>
	 								</td>
	 								<td>
	 									<?= $currency_symbol.$total; ?>
	 								</td>
	 							</tr>
 						<?php

 							}
 						?>
 								<tr style="background:#eee;">
 									<td><b>Total</b></td>
 									<td>
 										<b>
 											<?=$total_payments?>
 										</b>
 									</td>
 									<td>
 										<b>
 											<?=$currency_symbol.number_format($amount_payments, 2, $decimal_separator, $thousand_separator);?>
 										</b>
 									</td>
 								</tr>
								 

 					</table>
 				</div>
 			</div>
			 <div class="border">
					<table>	
						<tr class="fondo">
							<th ><a class="help_config_options tooltips" data-placement="right" title="" data-original-title="La diferencia es la cantidad de dinero del sistema menos la física, normalmente representa un descuadre del cajero.">
								<?= lang("reports_difference") ?><span class="hidden-sm hidden-xs">&nbsp;</span><i class="fa fa-question-circle"></i></a>
							</th>
							
						</tr>					
						<tr>
							 
							<td>
								<?= $currency_symbol.number_format($difference, 2, $decimal_separator, $thousand_separator); ?>
							</td>
						</tr>					

					</table>

				</div>
 		</div>
		<div id="domain_ing">

			<br/>
		</div>
 	</div>
</div>

<div>
	<script>

		function print_detail_register()
		{
			window.print();
		}

	</script>

</div>

<?php $this->load->view("partial/footer"); ?>
