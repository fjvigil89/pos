<?php $this->load->view("partial/header");
	$controller_name="items";
?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-warning'></i>
				<?php echo lang('receivings_list_of_suspended'); ?>
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


	<div class="row" id="table-suspended">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
							<?php echo lang('receivings_suspended_search')?>
						</span>
					</div>
					
				</div>
				
				<div class="portlet-body">
					<div class="table-responsive table-responsive-force">
						<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="dTable_receivings">
							<thead>	
								<tr>
									<th><?php echo lang('receivings_id'); ?></th>
									<th><?php echo lang('sales_date'); ?></th>
									<th><?php echo lang('items_supplier'); ?></th>
									<th><?php echo lang('reports_items'); ?></th>
									<th><?php echo lang('sales_comments'); ?></th>
									<th><?php echo lang('sales_unsuspend'); ?></th>
									<th><?php echo lang('receivings_receipt'); ?></th>
									<th><?php echo lang('common_delete'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($suspended_receivings as $suspended_receiving) { ?>
									<tr>
										<td><?php echo $suspended_receiving['receiving_id'];?></td>
										<td><?php echo date(get_date_format(). ' @ '.get_time_format(),strtotime($suspended_receiving['receiving_time']));?></td>
										<td>
											<?php if (isset($suspended_receiving['supplier_id']))
											{
												$supplier = $this->Supplier->get_info($suspended_receiving['supplier_id']);
												echo $supplier->company_name.' ('.$supplier->first_name. ' '. $supplier->last_name.')';
											}
											else
											{
											?>
												&nbsp;
											<?php
											}
											?>
										</td>
										<td><?php echo character_limiter(H($suspended_receiving['items']),80);?></td>										
										<td><?php echo $suspended_receiving['comment'];?></td>
										<td>
											<?php echo form_open('receivings/unsuspend');
												echo form_hidden('suspended_receiving_id', $suspended_receiving['receiving_id']); ?>
												<button type="submit" name="submit" id="submit_unsuspend" class="btn btn-success btn-sm"><?php echo lang('sales_unsuspend'); ?></button>
											</form>
										</td>
										<td>
											<?php echo form_open('receivings/receipt/'.$suspended_receiving['receiving_id'], array('method'=>'get', 'class' => 'form_receipt_suspended_recv')); ?>
												<button type="submit" name="submit" id="submit_receipt" class="btn btn-success btn-sm"><?php echo lang('sales_recp'); ?></button>
											</form>
										</td>
										<td>
											<?php echo form_open('receivings/delete_suspended_receiving', array('class' => 'form_delete_suspended_recv'));
												echo form_hidden('suspended_receiving_id', $suspended_receiving['receiving_id']); ?>
												<button type="submit" name="submit" id="submit_delete" class="btn btn-danger btn-sm"><?php echo lang('common_delete'); ?></button>
											</form>
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


	<script type="text/javascript">
		$(".form_delete_suspended_recv").submit(function()
		{
			if (!confirm(<?php echo json_encode(lang("receivings_delete_confirmation")); ?>))
			{
				return false;
			}
		});
	</script>

<?php $this->load->view("partial/footer"); ?>