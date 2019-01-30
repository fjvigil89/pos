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

			<div class="row"></div>

			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo $subtitle ?></span>						
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse" data-original-title="" title="">
						</a>												
						<a href="javascript:;" class="fullscreen" data-original-title="" title="">
						</a>						
					</div>
				</div>
				<div class="portlet-body">
					
                    <?php echo $errors;?>                
                    
				</div>
			</div>

			

		</div>
	</div>

<?php $this->load->view("partial/footer"); ?>