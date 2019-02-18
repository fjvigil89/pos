<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-bar-chart-o"></i>
				<?php echo lang('reports_reports'); ?> - <?php echo lang('reports_profit_and_loss') ?>
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
					<h3><strong><?php echo lang('reports_sales'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<?php foreach($details_data['sales_by_payments'] as $sale_payment) { ?>
								<tr>
									<td><?php echo $sale_payment['payment_type']; ?></td>
									<td style="text-align: right;"><?php echo to_currency($sale_payment['payment_amount']); ?></td>
								</tr>								
							<?php } ?>
						</table>
					</div>

					<h3><strong><?php echo lang('reports_returns'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<?php foreach($details_data['returns_by_category'] as $category) { ?>
								<tr>
									<td><?php echo $category['category']; ?></td>
									<td style="text-align: right;"><?php echo to_currency($category['total']); ?></td>
								</tr>								
							<?php } ?>
						</table>
					</div>

					<h3><strong><?php echo lang('reports_discounts'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<td><?php echo lang('reports_discount'); ?></td>
								<td style="text-align: right;"><?php echo to_currency($details_data['discount_total']['discount']); ?></td>
							</tr>
						</table>
					</div>

					<h3><strong><?php echo lang('reports_taxes'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<tr>
								<td><?php echo lang('reports_taxes'); ?></td>
								<td style="text-align: right;"><?php echo to_currency($details_data['taxes']['tax']); ?></td>
							</tr>
						</table>
					</div>
					<?php if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id)) { ?>
						<h3><strong><?php echo lang('reports_profit')." Bruta"; ?></strong></h3>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tr>
									<td><?php echo lang('reports_total'); ?></td>
									<td style="text-align: right;"><?php echo to_currency($details_data['profit']); ?></td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>

				<div class="col-md-6">
					<h3><strong><?php echo lang('reports_receivings'); ?></strong></h3>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<?php foreach($details_data['receivings_by_category'] as $category) { ?>
								<tr>
									<td><?php echo $category['category']; ?></td>
									<td style="text-align: right;"><?php echo to_currency($category['total']); ?></td>
								</tr>								
							<?php } ?>
						</table>
					</div>

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

					<?php if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id)) { ?>
						<h3><strong><?php echo lang('reports_profit')." Neta"; ?></strong></h3>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tr>
									<td><?php echo lang('reports_total'); ?></td>
									<td style="text-align: right;"><?php echo to_currency($details_data['ganacias_neta']); ?></td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>



<?php $this->load->view("partial/footer"); ?>