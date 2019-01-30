<?php $this->load->view("partial/header"); ?>


	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-wrench"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack4"></a>
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


	<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>


	<div class="row">		
		<div class="col-md-12">
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">
						<span class="icon">
							<i class="fa fa-th"></i>
						</span>
						<?php echo ' '.lang('module_'.$controller_name); ?>

					</div>
					
				</div>
				<div class="portlet-body">
                <div id="contenedor">
                    <?php $this->load->View('technical_supports/repair');?>
                </div>

				</div>
			</div>
		
			<!-- END CONDENSED TABLE PORTLET-->
		</div>
	</div>

	

<?php $this->load->view("partial/footer"); ?>

