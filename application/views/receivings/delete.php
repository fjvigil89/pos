<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-pencil"></i>
				<?php echo lang('receivings_register') ?>
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
	
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>
						<h3 class="panel-title">
							<i class="fa fa-align-justify"></i>
							<?php echo lang('receivings_delete_successful'); ?>
						</h3>
					</strong>
				</div>
				<div class="panel-body">
					<?php if ($success)
					{
					?>
						<h1 class="text-success"> <i class="fa fa-check"></i> <?php echo lang('receivings_delete_successful'); ?></h1>
					<?php	
					}
					else
					{
					?>
						<h1><?php echo lang('receivings_delete_unsuccessful'); ?></h1>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view("partial/footer"); ?>