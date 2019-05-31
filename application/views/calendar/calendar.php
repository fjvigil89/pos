<?php $this->load->view("partial/header"); ?>



	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-shopping-cart'></i>
				<?php echo lang('sales_opening_amount');?>
				<a class="icon fa fa-youtube-play help_button" id='maxamount' rel='0' data-toggle="modal" data-target="#stack8"></a>
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
<?php


?>

	<div class="row">		
		<div class="col-md-12">
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">						
                        <i class=" icon-layers "></i>
                        <span class="caption-subject sbold uppercase">Calendar</span>
					</div>					
				</div>
				<div class="portlet-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <!-- BEGIN DRAGGABLE EVENTS PORTLET-->
                            <h3 class="event-form-title margin-bottom-20">Draggables Events</h3>
                            <div id="external-events">
                                <form class="inline-form">
                                    <input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title">
                                    <br>
                                    <a href="javascript:;" id="event_add" class="btn green"> Add Events </a>
                                </form>
                                <hr>
                                <div id="event_box" class="margin-bottom-10">
								
								</div>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" for="drop-remove"> remove after drop
                                    <input type="checkbox" class="group-checkable" id="drop-remove">
                                    <span></span>
                                </label>
                                <hr class="visible-xs"> </div>
                            <!-- END DRAGGABLE EVENTS PORTLET-->
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div id="calendar" class="has-toolbar fc fc-ltr fc-unthemed">
							</div>
                    </div>
        
                    
				</div>
			</div>
		</div>
	</div>




<?php $this->load->view('partial/footer.php'); ?>