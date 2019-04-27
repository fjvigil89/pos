<?php
	if($export_excel == 1)
	{
		if (!$this->config->item('legacy_detailed_report_export'))
		{
			$rows = array();
		
			$row = array();
			foreach ($headers['details'] as $header) 
			{
				$row[] = strip_tags($header['data']);
			}

			foreach ($headers['summary'] as $header) 
			{
				$row[] = strip_tags($header['data']);
			}
			$rows[] = $row;
		
			foreach ($summary_data as $key=>$datarow) 
			{		
				foreach($details_data[$key] as $datarow2)
				{
					$row = array();
					foreach($datarow2 as $cell)
					{
						$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));				
					}
				
					foreach($datarow as $cell)
					{
						$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));
					}
					$rows[] = $row;
				}
			
			}
		}
		else
		{
			$rows = array();
			$row = array();
			foreach ($headers['summary'] as $header) 
			{
				$row[] = strip_tags($header['data']);
			}
			$rows[] = $row;
		
			foreach ($summary_data as $key=>$datarow) 
			{
				$row = array();
				foreach($datarow as $cell)
				{
					$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));
				
				}
			
				$rows[] = $row;

				$row = array();
				foreach ($headers['details'] as $header) 
				{
					$row[] = strip_tags($header['data']);
				}
			
				$rows[] = $row;
			
				foreach($details_data[$key] as $datarow2)
				{
					$row = array();
					foreach($datarow2 as $cell)
					{
						$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));				
					}
					$rows[] = $row;
				}
			}
		}
		$content = array_to_spreadsheet($rows);
		force_download(strip_tags($title) . '.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}
	else if($export_pdf==1){
		$this->pdf->generar_pdf_reporte_detallado($summary_data, $headers, $subtitle,$title, $overall_summary_data,$details_data);
	exit;
	}
?>

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
				<?php foreach($overall_summary_data as $name=>$value) { ?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="dashboard-stat grey-gallery">
							<div class="visual">
								<i class="glyphicon glyphicon-stats icon-box"></i>
							</div>
							<div class="details">
								<div class="number">
									<strong><?php echo str_replace(' ','&nbsp;', to_currency($value)); ?></strong>
								</div>
								<div class="desc">
									<?php echo lang('reports_'.$name); ?>
								</div>
							</div>						
						</div>
					</div>
				<?php } ?>
			</div>

			<?php if(isset($pagination) && $pagination) {  ?>
				<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top" >
					<?php echo $pagination;?>
				</div>
			<?php }  ?>

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
				<div class="portlet-body nopadding">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed table-hover detailed-reports tablesorter" id="sortable_table">
							<thead>
								<tr align="center" style="font-weight:bold">
									<td class="hidden-print"><a href="#" class="expand_all" >+</a></td>
									<?php foreach ($headers['summary'] as $header) { ?>
										<td align="<?php echo $header['align']; ?>">
											<?php echo $header['data']; ?>
										</td>
									<?php } ?>							
								</tr>
							</thead>
							<tbody>
								<?php foreach ($summary_data as $key=>$row) { ?>
									<tr align="center">
										<td class="hidden-print"><a href="#" class="expand" style="font-weight: bold;">+</a></td>
										<?php foreach ($row as $cell) { ?>
											<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
										<?php } ?>
									</tr>
									<tr>
										<td colspan="<?php echo count($headers['summary']) + 1; ?>" class="innertable">
											<table class="table table-advance">
												<thead>
													<tr>
										<?php foreach ($headers['details'] as $header) { ?>
														<th align="<?php echo $header['align']; ?>"><?php echo $header['data']; ?></th>
														<?php } ?>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($details_data[$key] as $row2) { ?>

														<tr>
															<?php foreach ($row2 as $cell) { ?>
															<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
															<?php } ?>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>


	<script type="text/javascript" language="javascript">

		$(document).ready(function()
		{
			$(".tablesorter a.expand").click(function(event)
			{
				$(event.target).parent().parent().next().find('td.innertable').toggle();
				
				if ($(event.target).text() == '+')
				{
					$(event.target).text('-');
				}
				else
				{
					$(event.target).text('+');
				}
				return false;
			});
			
			$(".tablesorter a.expand_all").click(function(event)
			{
				$('td.innertable').toggle();
				
				if ($(event.target).text() == '+')
				{
					$(event.target).text('-');
					$(".tablesorter a.expand").text('-');
				}
				else
				{
					$(event.target).text('+');
					$(".tablesorter a.expand").text('+');
				}
				return false;
			});
			
		});
	</script>

<?php $this->load->view("partial/footer"); ?>