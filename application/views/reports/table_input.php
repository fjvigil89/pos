

<?php 
    if($export_pdf==1){

        $this->pdf->generar_pdf_reporte_table($tables, $subtitle,$title,$ntable);
        exit();

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
            <?php for ($i = 0; $i < $ntable; $i++) { ?>

                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 ">
                    <div class="dashboard-stat grey-gallery">
                        <div class="visual">
                            <i class="glyphicon glyphicon-stats icon-box"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <strong><?php echo lang("table") ." ". ($i + 1); ?></strong>
                            </div>
                            <div class="desc">

                                <?php
                                if (isset($tables[($i + 1)]))
                                    echo $tables[($i + 1)];
                                else
                                    echo 0;
                                ?>

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


                <div class="portlet light">
                    <div class="portlet-title">

                        <div class="actions">								
                            <a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <p class="text-center">						

                        </p>
                        <div id="details_tables" class="chart_custom">
<?php $this->load->view("charts/tables_rotated"); ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>



    </div>
</div>




<script type="text/javascript" language="javascript">

    function init_table_sorting()
    {
        //Only init if there is more than one row

    }
    $(document).ready(function()
    {
        //init_table_sorting();
    });
</script>

<?php $this->load->view("partial/footer"); ?>