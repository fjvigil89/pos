
<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-bar-chart-o"></i>
				<?php echo lang('reports_reports'); ?> - <?php echo $title ?>
			</h1>
		</div>
		<!-- END PAGE TITLE -->		
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE BREADCRUMB -->
	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	</div>
	<!-- END PAGE BREADCRUMB -->

	<div class="clear"></div>


	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">				
				<span class="caption-subject font-green-sharp bold uppercase"><?php echo $subtitle ?></span>				
			</div>			
		</div>
		<div class="portlet-body">
			<div class="row">
				<div class="col-md-6">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead class="cabecera">	
								<tr>    							
    								<th colspan="2"  > <strong><?php echo  strtoupper(lang('reports_sales')); ?></strong></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($details_data['sales_by_payments'] as $sale_payment) { ?>
									<tr>
										<td><?php echo $sale_payment['payment_type']; ?></td>
										<td style="text-align: right;"><?php echo to_currency($sale_payment['payment_amount']); ?></td>
									</tr>								
								<?php } ?>
								</tbody>
								<tfoot>
								<tr>
										<td  colspan="2" style="text-align: right;"><strong><?php echo strtoupper(lang('reports_total')).": ".to_currency($details_data["total_sales"]); ?></strong></td>
								</tr>
								</tfoot>
							
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead class="cabecera">	
								<tr>    							
    								<th colspan="2"  > <strong><?php echo  strtoupper(lang('reports_sales') ." + ".lang("sales_abono")."S"); ?></strong></th>
								</tr>
							</thead>
							<tbody>
								
								</tbody>
								<tfoot>
								<tr>
										<td  colspan="2" style="text-align: right;"><strong><?php echo strtoupper(lang('reports_total')).": ".to_currency($details_data["sales_abonos"]); ?></strong></td>
								</tr>
								</tfoot>
							
						</table>
					</div>

					
					
				</div>

				<div class="col-md-6">
				
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>	
								<tr>    							
									<th colspan="2"><strong><?php echo  strtoupper(lang("sales_abono")."S"); ?></strong></th>
								</tr>
							</thead>	
							<tbody>
								<?php foreach($details_data['petty_cash_payments'] as $sale_payment) { ?>
									<tr>
										<td><?php echo $sale_payment['payment_type']; ?></td>
										<td style="text-align: right;"><?php echo to_currency($sale_payment['total_pago']); ?></td>
									</tr>								
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td  colspan="2" style="text-align: right;"><strong><?php echo strtoupper(lang('reports_total')).": ".to_currency($details_data["total_petty_cash"]); ?></strong></td>
								</tr>
							</tfoot>
						</table>
					</div>
<!--
					<h3><strong><?php echo lang('reports_total'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<td><?php echo lang('reports_total'); ?></td>
								<td style="text-align: right;"><?php echo to_currency($details_data['total']); ?></td>
							</tr>
						</table>
					</div>
				
					<h3 >
						<strong>
							<a class="help_config_options tooltips"
							 data-placement="left"
							  title=""
							 data-original-title="Gastos registrados por otros conceptos diferentes a ventas y compras (estos se registran en movimientos de caja) ">
							 Total gastos</a>
						</strong>
					</h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<td><?php echo lang('reports_total'); ?></td>
								<td style="text-align: right;"><?php echo to_currency($details_data['total_egresos']); ?></td>
							</tr>
						</table>
					</div>
					<h3>
					<strong>
							<a class="help_config_options tooltips"
							 data-placement="left"
							  title=""
							 data-original-title="Ingreos registrados por otros conceptos diferentes a ventas y compras (estos se registran en movimientos de caja) ">
							 Total ingresos</a>
						</strong>
					</h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<td><?php echo lang('reports_total'); ?></td>
								<td style="text-align: right;"><?php echo to_currency($details_data['total_ingresos']); ?></td>
							</tr>
						</table>
					</div>
								-->
				</div>
			</div>
		</div>
	</div>



<?php $this->load->view("partial/footer"); ?>

