<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-bar-chart-o"></i>
				<?php echo lang('reports_report_input'); ?>
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
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-align-justify"></i>
						<span class="caption-subject bold">
							<?php echo lang('reports_date_range'); ?>
						</span>
					</div>					
				</div>
				<div class="portlet-body form">
					<?php
						if(isset($error))
						{
							echo "<div class='error_message'>".$error."</div>";
						}
					?>
						<?php foreach ($shop_select as $row){

						$name[]=$row->name;

						  }?>
					<?php echo form_open('',['class'=>'form-horizontal']);?>
						<div class="form-body">
							<div class="form-group">
							<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("reports_shop_reports_help").'">'.lang('reports_shop_reports').'</a>'.':', 'pull_payments_by',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
								
 								<?php echo form_dropdown("location_id", $name, '', 'id="shop" class="form-control"'); ?>
							 								</div>
							
							</div>

						</div>

						<div class="form-actions">
							<div class="pull-right">
							<?php echo form_button(array(
								'name'=>'generate_report',
								'id'=>'generate_report',
								'content'=>lang('common_submit'),
								'class'=>'btn btn-success')
							);?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript" language="javascript">


		
		$(document).ready(function()
		{
			
			$("#generate_report").click(function()
			{
					window.location = 'index.php/reports/detailed_shop'+'/'+$("#shop option:selected").val();
	
			});
		});
	</script>

<?php $this->load->view("partial/footer"); ?>