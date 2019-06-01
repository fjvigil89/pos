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
//aqui va todo el body editable 

$this->load->view('calendar/'.$vistas);

?>


	



<?php $this->load->view('partial/footer.php'); ?>