<?php $this->load->view("partial/header");
	$controller_name="items";
?>
<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-file-o'></i>
				<?php echo lang('sales_list_of_quotes'); ?>
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

	
	<div class="row" id="table-quote">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
							<?php echo lang('sales_quotes_search')?>
						</span>
					</div>
					
				</div>
				
				<div class="portlet-body">
					<div class="table-responsive table-responsive-force">
						<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="dTable_quotes">
							<thead>	
								<tr>
									<th><?php echo lang('sales_quotes_id'); ?></th>
									<th><?php echo lang('sales_date'); ?></th>
									<th><?php echo lang('sales_customer'); ?></th>
									<th><?php echo lang('sales_comments'); ?></th>
									<th><?php echo lang('sales_receipt'); ?></th>
									<th><?php echo lang('sales_email_receipt'); ?></th>
									<?php if ($this->Employee->has_module_action_permission('sales', 'delete_suspended_sale', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
									<th><?php echo lang('common_delete'); ?></th>
									<th>Opciones</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($suspended_sales as $suspended_sale) { ?>
									<tr>
										<td><?php echo $suspended_sale['quote_id'];?></td>
										<td><?php echo date(get_date_format(). ' @ '.get_time_format(),strtotime($suspended_sale['sale_time']));?></td>								
										<td>
											<?php
											if (isset($suspended_sale['customer_id'])) 
											{
												$customer = $this->Customer->get_info($suspended_sale['customer_id']);
												$company_name = $customer->company_name;
												if($company_name) 
												{
													echo $customer->first_name. ' '. $customer->last_name.' ('.$customer->company_name.')';
												}
												else 
												{
													echo $customer->first_name. ' '. $customer->last_name;
												}
											}
											else
											{
											?>
												&nbsp;
											<?php
											}
											?>
										</td>			

										<td><?php echo $suspended_sale['comment'];?></td>

										<td align="center">
											<?php echo form_open('sales/quotes_receipt/'.$suspended_sale['quote_id'], array('method'=>'get', 'class' => 'form_receipt_suspended_sale')); ?>
												<button type="submit" name="submit" id="submit_receipt" class="btn btn-success btn-sm"><?php echo lang('sales_recp'); ?></button>
											</form>
																
										</td>

										<td align="center">
											<?php if ($suspended_sale['email']) 
											{
												echo form_open('sales/email_receipt_quotes/'.$suspended_sale['quote_id'], array('method'=>'get', 'class' => 'form_email_receipt_suspended_sale')); ?>
													<button type="submit" name="submit" id="submit_receipt" class="btn btn-success btn-sm"><?php echo lang('common_email'); ?></button>
												</form>
											<?php } ?>									
										</td>
										<td align="center">
											<?php 
										 	//	if ($this->Employee->has_module_action_permission('sales', 'delete_suspended_sale', $this->Employee->get_logged_in_employee_info()->person_id)){
										 		echo form_open('sales/delete_suspended_sale_quotes', array('class' => 'form_delete_suspended_sale'));
													echo form_hidden('suspended_quote_id', $suspended_sale['quote_id']); ?>
													<button type="submit" name="submit" id="submit_delete" class="btn btn-danger btn-sm"><?php echo lang('common_delete'); ?></button>
												</form>
											<?php //} ?>
										</td>
										<td align="center">
										
											<?php echo form_open('sales/edit_quote/'.$suspended_sale['quote_id'], array('method'=>'post', 'class' => 'form_receipt_suspended_sale')); ?>
												<button type="submit" name="submit" id="submit_receipt" class="btn btn-success btn-sm">Editar/Facturar</button>
											</form>
											
										</td>
									</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		$(".form_delete_suspended_sale").submit(function()
		{
			if (!confirm(<?php echo json_encode(lang("sales_delete_confirmation_quotes")); ?>))
			{
				return false;
			}
		});

		$(".form_email_receipt_suspended_sale").ajaxForm({success: function()
		{
			alert("<?php echo lang('sales_receipt_sent'); ?>");
		}});	
	</script>

<?php $this->load->view("partial/footer"); ?>