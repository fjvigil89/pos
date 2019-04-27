<?php $this->load->view("partial/header"); ?>

	<div class="portlet light no-padding-general hidden-print">
		<div class="portlet-body">
			<div class="row margin-top-15 hidden-print">
				<div class="col-lg-2 col-md-2 col-sm-6">
					<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="window.print(); return false;" > 
						<?php echo lang('sales_print'); ?> 
					</button>
				</div>
			</div>
		</div>
	</div>


<div class="container-fluid tarjeta">
<div class="row">
<div class="col-md-5">
<div class="dashboard-stat red" style="height: 200px;">
						<div class="visual" >
							<i class="fa fa-gift" style="font-size: 180px;" ></i>
						</div>
						<div class="details">
							<div class="number" align="letf">
								<p style="font-size: 60px;">Giftcard </p>
								<p> <?php  
								 if($giftcard_info->value)
								 	{
								 		echo to_currency_no_money($giftcard_info->value, 10);
								 	}?>$</p>						
							</div>		
							<!-- BEGIN STATISTICS -->
							<div class="desc">
      						<?php echo $customers; ?>			
      						</div>	
							<!-- END STATISTICS -->		
						</div>
						<div class="more" style="margin-top: 150px;">
							 Code valid: 
							<?php echo $giftcard_info->giftcard_number; ?>
						</div>
					</div>
	</div>

  </div>
</div>

	<?php if ($this->config->item('print_after_sale')) { ?>
		<script type="text/javascript">
			$(window).bind("load", function() {
				print_receipt();
				window.location = '<?php echo site_url("giftcards"); ?>';
			});
		</script>
	<?php } ?>



<script type="text/javascript">
 function print_receipt()
 {
          
 window.print();
       
 }


</script>
<?php $this->load->view("partial/footer"); ?>