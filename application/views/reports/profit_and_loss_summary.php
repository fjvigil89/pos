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


	<div class="row" id="form">
		<div class="col-md-12">

			<div class="row">				
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-shopping-cart icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['sales_total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_sales'); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-history icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['returns_total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_returns'); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-cloud-download icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['receivings_total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_receivings'); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-level-down icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['discount_total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_discounts'); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-plus icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['taxes_total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_taxes'); ?>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<div class="dashboard-stat grey-gallery">
						<div class="visual">
							<i class="fa fa-money icon-box"></i>
						</div>
						<div class="details">
							<div class="number">
								<strong><?php echo to_currency($details_data['total']); ?></strong>
							</div>
							<div class="desc">
								<?php echo lang('reports_total'); ?>
							</div>
						</div>						
					</div>
				</div>

				<?php if($this->Employee->has_module_action_permission('reports','show_profit',$this->Employee->get_logged_in_employee_info()->person_id)) { ?>
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class="dashboard-stat grey-gallery">
							<div class="visual">
								<i class="fa fa-dollar icon-box"></i>
							</div>
							<div class="details">
								<div class="number">
									<strong><?php echo to_currency($details_data['profit']); ?></strong>
								</div>
								<div class="desc">
									<?php echo lang('reports_profit'); ?>
								</div>
							</div>						
						</div>
					</div>	
				<?php } ?>			
			</div>

		</div>
	</div>


<?php $this->load->view("partial/footer"); ?>