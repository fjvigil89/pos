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
					foreach($datarow as $cell)
					{
						$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));
					}
					$rows[] = $row;
			
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
			
			}
		}
		$content = array_to_spreadsheet($rows);
		force_download(strip_tags($title) . '.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}
	else if($export_pdf==1){
		$this->pdf->generar_pdf_reporte_detallado($summary_data, $headers, $subtitle,$title);
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
										<?php foreach ($row as $cell) { ?>
											<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
										<?php } ?>
									</tr>
								<?php } ?>
                            </tbody>
                            
                            <tfoot>
									<tr align="left">
                                    <th align="left"><?php echo lang('sales_total') ?></th>
                                    
                                        <th align="left"><?php echo $total_data['efectivo'] ?></th>
                                        <th align="left"><?php echo $total_data['datafonos'] ?></th>
                                        <th align="left"><?php echo $total_data['otros']?></th>
                                        <th align="left"><?php echo $total_data['credito']; ?></th>
                                        <th align="left"><?php echo $total_data['gastos']; ?></th>
                                        <th align="left"><?php echo $total_data['total'] ?></th>
									</tr>
							</tfoot>
                        </table>
                        <!-- totales-->
                        
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