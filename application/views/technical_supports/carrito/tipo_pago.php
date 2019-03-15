<?php foreach ($dataServTecAbono->result() as $dataServTecAbono) { $abonado=$dataServTecAbono->Abonado;  } ?>

<?php $restante = $precio_total - $abonado; ?>

<?php $total_de_payments = 0; if(isset($payments)) { ?>
	<div class="payments">
		<ul class="list-group">
			<?php  foreach($payments as $payment_id=>$payment) { 
				$total_de_payments += floatval($payment['payment_amount']);
				?>
				<li class="list-group-item no-border-top">
					<span class="name-item-summary">
						<i class="fa fa-times-circle fa-lg font-red" class="delete_payment" onclick="controler('<?php echo site_url() ?>/technical_supports/deletePago', 'payment_id=<?php echo $payment_id ?>&precio_total=<?php echo $precio_total ?>', 'contenedor_pago')"></i>
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
		<li class="list-group-item no-border-top tier-style">
			<?php if ("$total_de_payments" === "$restante"): ?>
				<script type="text/javascript">
					$('#contenedor_submit').html('<input type="button" class="btn btn-success btn-large btn-block" id="finish_sale_button" value="Completar Venta">');
				</script>				
				<?php else: ?>
					<script type="text/javascript">
						$('#contenedor_submit').html('');
					</script>
				<?php endif; ?>
			</li>
			<script type="text/javascript">
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
