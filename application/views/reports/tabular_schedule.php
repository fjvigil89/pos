<?php
	if($export_excel == 1)
	{
		$rows = array();
		$row = array();
		foreach ($headers as $header) 
		{
			$row[] = strip_tags($header['data']);
		}
		
		$rows[] = $row;
		
		foreach($data as $datarow)
		{
			$row = array();
			foreach($datarow as $cell)
			{
				$row[] = str_replace('&#8209;', '-', strip_tags($cell['data']));
			}
			$rows[] = $row;
		}
		
		$content = array_to_spreadsheet($rows);
		
		force_download(strip_tags($title) . '.'.($this->config->item('spreadsheet_format') == 'XLSX ' ? 'xlsx' : 'csv'), $content);
		exit;
	}
	else if($export_pdf == 1){	
		

		$this->pdf->generar_pdf_reporte($data,$headers, $subtitle,$title,$summary_data/*,$this->Appconfig->get_logo_image()*/);
		
		//echo "<br><br><br>string";
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
                    <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar"
                              id="pagination_top">
                              <?php echo $pagination;?>
                    </div>
                    <?php }  ?>

                    <div class="portlet light">
                              <div class="portlet-title">
                                        <div class="caption">
                                                  <i class="icon-bar-chart font-green-haze"></i>
                                                  <span
                                                            class="caption-subject bold uppercase font-green-haze"><?php echo $subtitle ?></span>
                                        </div>
                                        <div class="tools">
                                                  <a href="javascript:;" class="collapse" data-original-title=""
                                                            title="">
                                                  </a>
                                                  <a href="javascript:;" class="fullscreen" data-original-title=""
                                                            title="">
                                                  </a>
                                        </div>
                              </div>
                              <div class="portlet-body">
                                        <div class="table-responsive">
                                                  <table class="table table-bordered table-striped table-hover data-table tablesorter"
                                                            id="sortable_table">
                                                            <thead>
                                                                      <tr>
                                                                                <?php foreach ($headers as $header)  { ?>
                                                                                <th
                                                                                          align="<?php echo $header['align'];?>">
                                                                                          <?php echo $header['data']; ?>
                                                                                </th>
                                                                                <?php } ?>
                                                                      </tr>
                                                            </thead>
                                                            <tbody>
                                                                      <?php foreach ($data as $row) {?>
                                                                      <tr>
                                                                                <?php foreach ($row as $cell) { ?>
                                                                                <td
                                                                                          align="<?php echo $cell['align'];?>">
                                                                                          <?php echo ($cell['data']); ?>
                                                                                </td>
                                                                                <?php } ?>
                                                                      </tr>
                                                                      <?php } ?>
                                                            </tbody>
                                                  </table>
                                        </div>
                              </div>
                    </div>

                    <?php if(isset($pagination) && $pagination) {  ?>
                    <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar"
                              id="pagination_top">
                              <?php echo $pagination;?>
                    </div>
                    <?php }  ?>

          </div>
</div>




<script type="text/javascript" language="javascript">
function init_table_sorting() {
          //Only init if there is more than one row
          if ($('.tablesorter tbody tr').length > 1) {
                    $("#sortable_table").tablesorter();
          }
}
$(document).ready(function() {
          init_table_sorting();
});
</script>

<?php $this->load->view("partial/footer"); ?>