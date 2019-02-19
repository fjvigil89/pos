<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-bar-chart-o"></i>
				<?php echo lang('reports_reports'); ?>
				<?php 
					$extra=" style='display: none; '";
					$url_video_ver="";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("REPORTES");
						if($url_video!=null){
							$url_video_ver=$url_video;
							$extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;	
					//<a class="icon fa fa-youtube-play help_button" id='maxreports' rel='0' data-toggle="modal" data-target="#stack7"></a>
			
				?>
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

	<div class="row report-listing">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><strong><?php echo lang('reports_make_a_selection')?></strong></h3>
				</div>
				<div class="panel-body">
					<div class="parent-list">
                        
                        <!--artículos-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_items', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>					
							<a href="#" class="icon-btn" id="items">
								<i class="fa fa-table"></i>	
								<div>
									<?php echo lang('reports_items'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--categorias-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_categories', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="categories">
								<i class="fa fa-th"></i>
								<div>
									<?php echo lang('reports_categories'); ?>
								</div>						
							</a>						
						<?php } ?>
						
                        
                        <!--clientes-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_customers', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="customers">
								<i class="fa fa-user"></i>	
								<div>
									<?php echo lang('reports_customers'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--comisión-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_commissions', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="commissions">
								<i class="fa fa-money"></i>	
								<div>
									<?php echo lang('reports_commission'); ?>
								</div>
							</a>
						<?php } ?>                        
                        <!--compras-->
						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_receivings', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="receivings">
								<i class="fa fa-cloud-download"></i>
								<div>
									<?php echo lang('reports_receivings'); ?>
								</div>	
							</a>
						<?php } ?>
                        
                        <!--descuentos-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_discounts', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="discounts">
								<i class="fa fa-magic"></i>	
								<div>
									<?php echo lang('reports_discounts'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--empleados-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_employees', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="employees">
								<i class="fa fa-user"></i>	
								<div>
									<?php echo lang('reports_employees'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--impuestos-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_taxes', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="taxes">
								<i class="fa fa-book"></i>	
								<div>
									<?php echo lang('reports_taxes'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--inventario-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_inventory_reports', $this->Employee->get_logged_in_employee_info()->person_id) ) { ?>					
							<a href="#" class="icon-btn" id="inventory">
								<i class="fa fa-table"></i>	
								<div>
									<?php echo lang('reports_inventory_reports'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--kits-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_item_kits', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>					
							<a href="#" class="icon-btn" id="item-kits">
								<i class="fa fa-inbox"></i>	
								<div>
									<?php echo lang('module_item_kits'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--pagos-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_payments', $this->Employee->get_logged_in_employee_info()->person_id)) {
						?>					
							<a href="#" class="icon-btn" id="payments">
								<i class="fa fa-money"></i>	
								<div>
									<?php echo lang('reports_payments'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--perdidas y ganancias-->
						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_profit_and_loss', $this->Employee->get_logged_in_employee_info()->person_id)) {
						?>
							<a href="#" class="icon-btn" id="profit-and-loss">
								<i class="fa fa-shopping-cart"></i>	
								<div>
									<?php echo lang('reports_profit_and_loss'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--perzonalisados-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_sales_generator', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="custom-report">
								<i class="fa fa-search"></i>	
								<div>
									<?php echo lang('reports_sales_generator'); ?>
								</div>
							</a>
						<?php } ?>
                         
                        
                        <!--proveedores-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_suppliers', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="suppliers">
								<i class="fa fa-download"></i>	
								<div>
									<?php echo lang('reports_suppliers'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--tarjetas de regalo-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_giftcards', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="giftcards">
								<i class="fa fa-credit-card"></i>	
								<div>
									<?php echo lang('reports_giftcards'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--tiendas-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_taxes', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="shop">
								<i class="fa fa-home"></i>	
								<div>
									<?php echo lang('reports_shop_reports'); ?>
								</div>
							</a>
						<?php } ?>
						
						
                        <!--turnos-->
						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_register_log', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<?php if ($this->config->item('track_cash')) { ?>
								<a href="#" class="icon-btn" id="register-log">
									<i class="fa fa-search"></i>	
									<div>
										<?php echo lang('reports_register_log_title'); ?>
									</div>
								</a>
							<?php } ?>
						<?php } ?>
						
                        <!--ventas-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_sales', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="sales">
								<i class="fa fa-shopping-cart"></i>	
								<div>
									<?php echo lang('reports_sales'); ?>
								</div>
							</a>
						<?php } ?>
                        
                        <!--ventas eliminadas-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_deleted_sales', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>	
							<a href="#" class="icon-btn" id="deleted-sales">
								<i class="fa fa-trash-o"></i>
								<div>
									<?php echo lang('reports_deleted_sales'); ?>
								</div>
							</a>
						<?php } ?>
						
                        <!-- Linea de credito-->
						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_store_account', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<?php if($this->config->item('customers_store_accounts')) { ?>
								<a href="#" class="icon-btn" id="store-accounts">
									<i class="fa fa-credit-card"></i> 
									<div>
										<?php echo lang('reports_store_account'); ?>
									</div>
								</a>
							<?php } ?>
						<?php } ?>
						

                        <!--ventas suspendidos-->
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_suspended_sales', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="suspended_sales">
								<i class="fa fa-download"></i>	
								<div>
									<?php echo lang('reports_suspended_sales'); ?>
								</div>
							</a>
						<?php } ?>
						
						<?php if ($this->Employee->has_module_action_permission('reports', 'view_movement_cash', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
							<a href="#" class="icon-btn" id="movement_cash">
								<i class="fa fa-money"></i>	
								<div>
									<?php echo lang('reports_movement_cash'); ?>
								</div>
							</a>
						<?php } ?>

						<!--corte diario-->
						<?php if($this->config->item("mostrar_reportes_personalizados1")==1):?>
							<?php if ($this->Employee->has_module_action_permission('reports', 'daily_cut', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
								<a href="#" class="icon-btn" id="daily_cut">
									<i class="fa fa-scissors"></i>	
									<div>
										<?php echo lang('reports_daily_cut'); ?>
									</div>
								</a>
							<?php } ?>
						<?php endif; ?>

						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_tables',  $this->Employee->get_logged_in_employee_info()->person_id) &&  $this->config->item('enabled_for_Restaurant')) { ?>
							
								<a href="#" class="icon-btn" id="view_table">
									<i class="fa fa-table"></i> 
									<div>
										<?php echo lang('report_tables'); ?>
									</div>
								</a>
							
						<?php } ?>
						<?php
						if ($this->Employee->has_module_action_permission('reports', 'view_change_house',  $this->Employee->get_logged_in_employee_info()->person_id) &&  $this->config->item('activar_casa_cambio')) { ?>
							
								<a href="#" class="icon-btn" id="view_change_house">
									<i class="fa fa-exchange"></i> 
									<div>
										<?php echo lang('reports_change_house'); ?>
									</div>
								</a>
							
						<?php } ?>
						
                        
					</div>
				</div>
			</div> <!-- /panel -->
		</div>
		<div class="col-md-6 hidden" id="report_selection">
			<div class="panel panel-default">
				<div class="panel-heading child-list">
					<h3 class="panel-title text-success"></h3>
				</div>
				<div class="panel-body child-list">		

					<div class="list-group custom-report hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/sales_generator');?>" class="btn icon-btn letter-space">
								<i class="fa fa-search report-icon"></i>  
								<div>
									<?php echo lang('reports_sales_search'); ?>
								</div>
							</a>
						</div>
					</div>

					<div class="list-group customers hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_customers');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_customers');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_customer');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/detailed_payments_cash');?>" class="btn icon-btn letter-space">
								<i class="fa fa-money"></i>
								<div>
									<?php echo "ABONOS DETALLADOS" ?>
								</div>
							</a>
							
						</div>						
					</div>

					<div class="list-group commissions hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_commissions');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_commissions');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/detailed_commissions');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>
					
					<div class="list-group employees hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_employees');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_employees');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_employee');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group sales hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_sales');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_sales');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/detailed_sales');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
							
						</div>
    						<div class="btn-group btn-group-justified">
							<?php if ($this->Employee->has_module_action_permission('reports', 'view_consolidated', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
								
								<a href="<?php echo site_url('reports/detailed_sales2');?>" class="btn icon-btn letter-space">
									<i class="fa fa-calendar"></i>
									<div>
										<?php echo lang('reports_detailed_reports'); ?> 2
									</div>
								</a>

    							<a href="<?php echo site_url('reports/sales_consolidation');?>" class="btn icon-btn letter-space">
    								<i class="fa fa-file-text"></i>
    								<div>
    									<?php echo lang('reports_consolidated'); ?>
    								</div>
    							</a>
								<a href="<?php echo site_url('reports/detailed_sales_serial');?>" class="btn icon-btn letter-space">
    								<i class="fa fa-barcode"></i>
    								<div>
    									Reporte por seriales
    								</div>
    							</a>	
							<?php } ?>		
											
    						</div>                      
							<div class="btn-group btn-group-justified">								
								<?php if ($this->config->item('activar_casa_cambio')==1) { ?>
									<a href="<?php echo site_url('reports/detailed_sales_rate');?>" class="btn icon-btn letter-space">
										<i class="fa fa-exchange"></i>
										<div>
											<?php echo "Reporte por tasa" ?>
										</div>
									</a>									
								<?php } ?>						
    						</div>        						
					</div>

					<div class="list-group deleted-sales hidden">
						<div class="btn-group btn-group-justified">							
							<a href="<?php echo site_url('reports/deleted_sales');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group register-log hidden">
						<div class="btn-group btn-group-justified">							
							<a href="<?php echo site_url('reports/detailed_register_log');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>							
					</div>

					<div class="list-group categories hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_categories');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_categories');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>							
						</div>						
					</div>

					<div class="list-group discounts hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_discounts');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_discounts');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>							
						</div>						
					</div>

					<div class="list-group items hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_items');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_items');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>							
						</div>						
					</div>

					<div class="list-group item-kits hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_item_kits');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_item_kits');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>							
						</div>						
					</div>

					<div class="list-group payments no-border-bottom hidden">
						<div class="btn-group btn-group-justified">
						<!--
							<a href="<?php echo site_url('reports/graphical_summary_payments');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>-->
							<a href="<?php echo site_url('reports/summary_store_payments');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_store_payments'); ?>
								</div>
							</a>							
							<a href="<?php echo site_url('reports/specific_supplier_store_payment');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_store_payments'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group suppliers hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_suppliers');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_suppliers');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_supplier');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>
						
						<div class="btn-group btn-group-justified">
				            <a href="<?php echo site_url('reports/suppliers_credit');?>" class="btn icon-btn letter-space">
				                <i class="fa fa-money"></i>
				                <div>
				                    <?php echo "Credito a proveedores"; ?>
				                </div>
				            </a>
				        </div>							
					</div>
					
						<div class="list-group daily_cut hidden">
							<div class="btn-group btn-group-justified">
								<a href="<?php echo site_url('reports/detailed_daily_cut');?>" class="btn icon-btn letter-space">
									<i class="fa fa-calendar"></i>
									<div>
										<?php echo lang('reports_detailed_reports'); ?>
									</div>
								</a>
							</div>						
						</div>
					
					

					<div class="list-group view_table hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/tables_report');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group suspended_sales hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/detailed_suspended_sales');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>
					
					<div class="list-group taxes hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/graphical_summary_taxes');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_graphical_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_taxes');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
						</div>						
					</div>
					

						<div class="list-group receivings hidden">
							<div class="btn-group btn-group-justified">
								<a href="<?php echo site_url('reports/detailed_receivings');?>" class="btn icon-btn letter-space">
									<i class="fa fa-calendar"></i>
									<div>
										<?php echo lang('reports_detailed_reports'); ?>
									</div>
								</a>
							
									<a href="<?php echo site_url('reports/purchase_provider');?>" class="btn icon-btn letter-space">
										<i class="fa fa-calendar"></i>
										<div>
											<?php echo "Compra proveedor" ?>
										</div>
									</a>
														
							</div>							
						</div>
					

					<div class="list-group inventory hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/inventory_low');?>" class="btn icon-btn letter-space">
								<i class="ion-arrow-graph-down-right"></i>
								<div>
									<?php echo lang('reports_low_inventory'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/inventory_summary');?>" class="btn icon-btn letter-space">
								<i class="ion-clipboard"></i>
								<div>
									<?php echo lang('reports_inventory_summary'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/detailed_inventory');?>" class="btn icon-btn letter-space">
								<i class="ion-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>	
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/defective_items_log');?>" class="btn icon-btn letter-space">
								<i class="icon-trash"></i>
								<div>
									<?php echo lang('reports_defective_items_log_title'); ?>
								</div>
							</a>							
						</div>						
					</div>

					<div class="list-group giftcards hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/summary_giftcards');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/detailed_giftcards');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group store-accounts hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/store_account_statements');?>" class="btn icon-btn letter-space">
								<i class="fa fa-bar-chart-o"></i>
								<div>
									<?php echo lang('reports_store_account_statements'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/summary_store_accounts');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_customer_store_account');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>
					<div class="list-group consolidated hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/detailed_consolidated');?>" class="btn icon-btn letter-space">
									<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>

						</div>						
					</div>

					<div class="list-group profit-and-loss hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/summary_profit_and_loss');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/detailed_profit_and_loss');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>

					<div class="list-group shop hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/shop');?>" class="btn icon-btn letter-space">
								<i class="fa fa-home"></i>
								<div>
									<?php echo lang('reports_shop_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_transfer_location');?>" class="btn icon-btn letter-space">
								<i class="fa fa-exchange"></i>
								<div>
									<?php echo lang('reports_transfer_location'); ?>
								</div>
							</a>
						</div>						
					</div>
					
					<div class="list-group movement_cash hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/summary_movement_cash');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo lang('reports_summary_reports'); ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/specific_movement_cash');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo lang('reports_detailed_reports'); ?>
								</div>
							</a>
						</div>						
					</div>
					<div class="list-group view_change_house hidden">
						<div class="btn-group btn-group-justified">
							<a href="<?php echo site_url('reports/depostos_salidas');?>" class="btn icon-btn letter-space">
								<i class="fa fa-building-o"></i>
								<div>
									<?php echo "Saldo - depositos y retiros" ?>
								</div>
							</a>
							<a href="<?php echo site_url('reports/movement_balance');?>" class="btn icon-btn letter-space">
								<i class="fa fa-calendar"></i>
								<div>
									<?php echo "Movimientos del saldo" ; ?>
								</div>
							</a>
						</div>						
					</div>
				</div>
			</div> <!-- /panel -->
		</div>
	</div>

	<script type="text/javascript">
		

		<?php if($this->config->item('hide_video_stack7') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack7').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack7")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack7').css({'display':'none'});

         }
         
     
         });
         $('#closereports').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack7').css({'display':'none'});
            	$('#maxreports').removeClass('icon fa fa-youtube-play help_button');
               	 $('#maxreports').html("<a href='javascript:;' id='maxreports' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
            	
               
              

         });
      
         $('#checkBoxStack7').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video7:$(this).is(':checked') ? '1' : '0',video7:'hide_video_stack7'});
               
         });

	 	$('.parent-list a').click(function(e){
	 		e.preventDefault();
	 		$('.parent-list a').removeClass('active');
	 		$('#report_selection').removeClass('hidden');
	 		$(this).addClass('active');
	 		var currentClass='.child-list .'+ $(this).attr("id");
	 		$('.child-list .panel-title').html($(this).html());
	 		$('.child-list .list-group').addClass('hidden');
	 		$(currentClass).removeClass('hidden');
		
			$('html, body').animate({
		    scrollTop: $("#report_selection").offset().top
		 	}, 500);
	 	});
 	</script>


<?php $this->load->view("partial/footer"); ?>