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


	<div class="row" id="form">
		<div class="col-md-12">

			<div class="row">
				<?php foreach($summary_data as $name=>$value) { ?>
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class="dashboard-stat grey-gallery">
							<div class="visual">
								<i class="fa fa-shopping-cart icon-box"></i>
							</div>
							<div class="details">
								<div class="number">
									<strong><?php echo to_currency($value); ?></strong>
								</div>
								<div class="desc">
									<?php echo lang('reports_'.$name); ?>
								</div>
							</div>						
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo $title ?></span>						
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse" data-original-title="" title="">
						</a>												
						<a href="javascript:;" class="fullscreen" data-original-title="" title="">
						</a>						
					</div>
				</div>
				<div class="portlet-body">
					<div id="chart_wrapper">
						<div id="chart"></div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		$.getScript('<?php echo $graph_file; ?>');
	</script>
	
<?php $this->load->view("partial/footer"); ?>