<?php $this->load->view("partial/header"); ?>
	<?php $this->load->view("partial/page_toolbar"); ?>
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">

			<h1>
			<?php echo lang('common_dashboard'); ?>
			<?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=PHET-RZfg6c&t=2s";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("INICIO Y ESTADÍSTICA");
						if($url_video!=null){
							$url_video_ver=$url_video;
						}else{
							$extra=" style='display: none; '";
						}

						$dashboard_stat_color = '';
						$theme_commpany = $this->Appconfig->get_theme_commpany($this->config->item('resellers_id'));
					    foreach ($theme_commpany as $row) {
					    	if ($row->name_feature_css == 'dashboard-stat') {
					    		$dashboard_stat_color = $row->css_class;
					    	}
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				
				
			?>
			<!--<a class="icon fa fa-youtube-play help_button" id='maxhome' rel='0' data-toggle="modal" data-target="#stack1"></a>
				-->
			<small><?php echo lang('module_name'). ' & ' .lang('statistics'); ?></small>
			</h1>
		</div>

		<!-- END PAGE TITLE -->

	</div>
	<!-- END PAGE HEAD -->

	<!-- BEGIN TITLE GENERAL -->
	<div class="note note-success note-shadow">
		<h4 class="center"><?php echo lang('common_welcome_message'); ?></h4>
	</div>
	<!-- END TITLE GENERAL -->

<?php if( !$this->config->item('Hide_panel_type_business') && count($profiles) && $this->Employee->es_demo()){ ?>
<div class="modal fade" id="configProfileType" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header note note-success note-shadow">
                <h3>SELECCIONE EL SOFTWARE QUE DESEA PROBAR</h3>
           </div>
           <div class="modal-body">
                <div class="form-group row">
                    <div id="msj"></div>
			        <div class="col-md-12 perfil">
			        	<?php 
			        	$i=0;
			        	foreach ($profiles as $profiles_columns) { ?>
				        	<div class="row">
			        			<?php foreach ($profiles_columns as $profile) { ?>
			        				<div class="col-md-2 col-sm-2 col-lg-2">
					        			<div class="portlet box <?=$colors_modal[$i]?>" id="modal-profile-box-<?=$profile->id?>-<?=$i?>">
					        				<div class="portlet-title">
					        					<h4 id="profile-name-<?=$i?>"><?=$profile->name_lang_key?></h4>
											</div>
											<div class="portlet-body">
												<i class="material-icons"><?=$profile->icon?></i>
											</div>
					        			</div>
				        			</div> 
			        			<?php $i++; } ?>
				        	</div>
						<?php } ?>
			        </div>
                </div>
	       </div>
      	</div>
   </div>
</div>
<?php } ?>

<?php if($this->config->item('initial_config') == 0){ ?>
<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<?php echo form_open('',array('id'=>'confi_impuesto_form','class'=>'form-horizontal')); ?>
      <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header note note-success note-shadow">
	     	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h3>Configuración de Impueto, Moneda</h3>
           </div>
           <div class="modal-body">
                    <div class="form-group row">
                    <div id="msj"></div>

                    <div class="col-md-6">
                    <div class="input-group"><?php echo form_label(lang('config_language').':', 'language',array('class'=>'control-label required')); ?></div>

                        <select id="language" class="bs-select form-control" data-show-subtext="true" name="language">
                            <option data-icon="" value=""  >Seleccione un país</option>
                            <option data-icon="flagstrap-icon flagstrap-ar" value="spanish_argentina">Argentina - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-bo" value="spanish_bolivia">Bolivia - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-br" value="portugues_brasil">Brasil - Portugues</option>
                            <option data-icon="flagstrap-icon flagstrap-cl" value="spanish_chile">Chile - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-co" value="spanish">Colombia - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-cr" value="spanish_costarica">Costa Rica - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-ec" value="spanish_ecuador">Ecuador - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-sv" value="spanish_elsalvador">El Salvador - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-es" value="spanish_spain">España - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-us" value="english">Estados Unidos - Ingles</option>
                            <option data-icon="flagstrap-icon flagstrap-gt" value="spanish_guatemala">Guatemala - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-hn" value="spanish_honduras">Honduras - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-mx" value="spanish_mexico">México - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-ni" value="spanish_nicaragua">Nicaragua - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-pa" value="spanish_panama">Panamá - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-py" value="spanish_paraguay">Paraguay - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-pe" value="spanish_peru">Perú - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-pr" value="spanish_puertorico">Puerto Rico - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-do" value="spanish_republicadominicana">República Dominicana - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-uy" value="spanish_uruguay">Uruguay - Español</option>
                            <option data-icon="flagstrap-icon flagstrap-ve" value="spanish_venezuela">Venezuela - Español</option>
                        </select>

                    </div>

                    <div class="col-md-6">
                    <div class="input-group"><?php echo form_label('Moneda:', 'moneda',array('class'=>'control-label required')); ?></div>
                    <input type="text" id="moneda" class="form-control" name="moneda" placeholder="Simbolo Moneda" value="<?php echo $this->config->item('currency_symbol'); ?>">
                    </div>


		        <div class="col-md-4">
		            <div class="input-group">
		            <?php echo form_label('Cobra impuesto: ', 'impuesto',array('class'=>'control-label required')); ?>
		            </div>
		            <label>
		            <span>Si</span>
		            <input type="radio" name="cobra" checked="checked" value="si"></label>
		            <label>
		            <span>&#160;&#160;No</span>
		            <input type="radio" name="cobra" value="no"></label>
		        </div>
		        <div class="col-md-4 impuesto">
		            <div class="input-group">
		            <label>Nombre Impuesto</label>
		                <input type="text" id="nombre_impuesto" class="form-control" name="nombre_impuesto" placeholder="Nombre Impuesto" value="<?php echo $this->config->item('default_tax_1_name'); ?>">
		            </div>
		        </div>

		        <div class="col-md-4 impuesto">
		            <div class="input-group">
		            <label>Impuesto %</label>
		                <input type="number" id="impuesto" class="form-control" name="impuesto" placeholder="Impuesto %" value="<?php echo $this->config->item('default_tax_1_rate'); ?>">
		            </div>
		        </div>
                </div>
	       </div>
           <div class="modal-footer">
			<label class="pull-left">
			<input type="checkbox" name="initial_config" value="1">
			No volver a mostrar este mensaje
			</label>
	        <button type="button" id="button" class="btn btn-primary">Guardar</button>
           </div>
      </div>
   </div>
   </form>
</div>
<?php } ?>
	<!-- BEGIN BOXES MODULES -->
	<div class="row">
		<?php

            $sorted_modules = $this->Module->apend_translated_module_names($allowed_modules);
            array_sort_by_column($sorted_modules,'translated_module_name');

            foreach($sorted_modules as $module) { ?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a href="<?php echo site_url($module['module_id']);?>">
					<div class="dashboard-stat <?php 
					if ($this->Appconfig->es_franquicia()) {
						echo $dashboard_stat_color;
					} else {
						echo $module['color'];
					}
					?>">
						<div class="visual">
							<i class="fa fa-<?php echo $module['icon']; ?>"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo lang("module_".$module['module_id']) ?>
							</div>
							<!-- BEGIN STATISTICS -->
							<div class="desc">
								<?php
									if (!$this->config->item('hide_dashboard_statistics'))
									{
										$module_items_1 = lang("module_".$module['module_id']);
										$module_items_2 = lang('module_items');
										if($module_items_1  == $module_items_2 )
										{
											echo lang('common_total').": <strong>". $total_items."</strong>";
										}

										$module_customer_1 = lang("module_".$module['module_id']);
										$module_customer_2 = lang('module_customers');
										if($module_customer_1 == $module_customer_2)
										{
											echo lang('common_total').": <strong>". $total_customers."</strong>";
										}

										$module_employee_1 = lang("module_".$module['module_id']);
										$module_employee_2 = lang('module_employees');
										if($module_employee_1 == $module_employee_2)
										{
											echo lang('common_total').": <strong>". $total_employees."</strong>";
										}

										$module_item_kits_1 = lang("module_".$module['module_id']);
										$module_item_kits_2 = lang('module_item_kits');
										if($module_item_kits_1 == $module_item_kits_2)
										{
											echo lang('common_total').": <strong>". $total_item_kits."</strong>";
										}

										$module_sales_1 = lang("module_".$module['module_id']);
										$module_sales_2 = lang('module_sales');
										if($module_sales_1 == $module_sales_2)
										{
											echo lang('common_total').": <strong>". $total_sales."</strong>";
										}

										$module_giftcards_1 = lang("module_".$module['module_id']);
										$module_giftcards_2 = lang('module_giftcards');
										if($module_giftcards_1 == $module_giftcards_2)
										{
											echo lang('common_total').": <strong>". $total_giftcards."</strong>";
										}
																				
									}
								?>
							</div>
							<!-- END STATISTICS -->
						</div>
						<div class="more">
							<?php echo lang('common_more_info'); ?> <i class="m-icon-swapright m-icon-white"></i>
						</div>
					</div>
				</a>
			</div>
		<?php } ?>
	</div>
	<!-- END BOXES MODULES -->

	<!-- BEGIN CHARTS -->
	<div class="row">
	<?php if ($this->Employee->has_module_action_permission('reports', 'view_graph_sale_month', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('best_sellers_items'); ?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>

				</div>
				<div class="portlet-body">
					<p class="text-center">
						<?php
							if ($best_sellers_items == null)
							{
								echo "No hay productos vendidos";
							}
							else
							{?>
								<?php echo lang('best_seller_item');?><strong><?php echo $best_seller_item_name?> </strong> con  <strong><?php echo to_quantity($best_seller_item_quantity)?> unidad(es)</strong>
							<?php }
						?>
					</p>
					<div id="chartdiv_best_sellers_items" class="chart_custom">
						<?php $this->load->view("charts/best_sellers_items"); ?>
					</div>
				</div>
			</div>
		</div>

		<?php } ?>
		<!-- ventas totales por tienda -->
		<?php if ($this->Employee->has_module_action_permission('reports', 'allow_graphics_by_store', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('reports_sales_by_store_total'); ?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>

				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-6"><?php echo lang('weekly_sales_message_filter')?></label>
							<div class="col-md-6">
					            <div class="form-group">

									<div class="input-group date" data-date="10/11/2012" data-date-format="mm/dd/yyyy" id='weekly_sales_store_total'>
                                        <input type="text" class="form-control" name="from" id="sales_store_date_start" >
                                        <span class="input-group-addon"> to </span>
										<input type="text" class="form-control" name="to" id="sales_store_date_end">
										<span class="input-group-addon" onclick="cargar_data_sales_by_store()"> Buscar </span>
										
									</div>
								</div>
								
						    </div>
						</div>
					</div>
					<p class="text-center">
						<?php
							if ($sales_total_by_store == null)
							{
								echo "No hay productos vendidos";
							}
						?>
					</p>
					<div id="chartdiv_sales_by_store_total" class="chart_custom">
						<?php $this->load->view("charts/sales_by_store"); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('reports_sales_by_store_total'); ?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>

				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-6"><?php echo lang('weekly_sales_message_filter')?></label>
							<div class="col-md-6">
					            <div class="form-group">

									<div class="input-group date" data-date="10/11/2012" data-date-format="mm/dd/yyyy" id='weekly_sales_store_total_money'>
                                        <input type="text" class="form-control" name="sales_store_date_start_money" id="sales_store_date_start_money" >
                                        <span class="input-group-addon"> to </span>
										<input type="text" class="form-control" name="sales_store_date_end_money" id="sales_store_date_end_money">
										<span class="input-group-addon" onclick="cargar_data_sales_by_store_money()"> Buscar </span>
										
									</div>
								</div>
								
						    </div>
						</div>
					</div>
					<p class="text-center">
						<?php
							if ($sales_total_by_store_money == null)
							{
								echo "No hay productos vendidos";
							}
						?>
					</p>
					<div id="chartdiv_sales_by_store_total_money" class="chart_custom">
						<?php $this->load->view("charts/sales_by_store_money"); ?>
					</div>
				</div>
			</div>
		</div>

		<?php } ?>


	    <?php if ($this->Employee->has_module_action_permission('reports', 'view_graph_amount_module', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-6">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"> <?php echo lang('quantity_module_total'); ?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
					<div id="chartdiv_items_scarce" class="chart_custom">
						<?php $this->load->view("charts/items_scarce"); ?>
					</div>
				</div>
			</div>
		</div>
        <?php } ?>
	    <?php if ($this->Employee->has_module_action_permission('reports', 'view_graph_sale_employee', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-6">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('sales_by_employees'); ?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
					<div id="chartdiv_by_empleados_sales" class="chart_custom" >
						<?php $this->load->view("charts/sales_by_employees"); ?>
					</div>
				</div>
			</div>
		</div>
        <?php } ?>
	    <?php if ($this->Employee->has_module_action_permission('reports', 'view_graph_sale_daily', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('weekly_sales')?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-6"><?php echo lang('weekly_sales_message_filter')?></label>
							<div class="col-md-6">
					            <div class="form-group">
					                <div class='input-group date' id='weeklysales'>
					                    <input type='text' class="form-control" id="weeklysales_input"/>
					                    <span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
					                </div>
					            </div>
						    </div>
						</div>
					</div>
					<div id="chartdiv_get_weekly_sales" class="chart_custom">
						<?php $this->load->view("charts/get_weekly_sales"); ?>
					</div>
				</div>
			</div>
		</div>
        <?php } ?>
		<!--
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('weekly_sales')?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
					<div id="chartdiv_profit_and_loss" class="chart_custom">
						<?php //$this->load->view("charts/profit_and_loss"); ?>
					</div>
				</div>
			</div>
		</div>
		-->
		<?php if ($this->Employee->has_module_action_permission('reports', 'allow_graphics_arnings_monsth', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('reports_earnings_monsth')?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
				<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-6"><?php echo lang('earnings_monsth_filter_year')?></label>
							<div class="col-md-4 pull-right">
					            <div class="form-group">
								<div class="input-group date" data-date="10/11/2012" data-date-format="mm/dd/yyyy" id='sale_earnings_monsth'>
									<input type="text" class="form-control" name="earnings_monsth" id="earnings_monsth" >
									<span class="input-group-addon" onclick="cargar_data_earnings_monsth()"> Buscar </span>
								</div>
								</div>
								
						    </div>
						</div>
					</div>
					<div id="chartdiv_get_earnings_monsth" class="chart_custom">
						<?php $this->load->view("charts/earnings_monsth"); ?>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if ($this->Employee->has_module_action_permission('reports', 'allow_graphics_arnings_monsth', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze"><?php echo lang('reports_sales_monsth')?></span>
					</div>
					<div class="actions">
						<a href="javascript:;" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""><span class="md-click-circle md-click-animate" style="height: 28px; width: 28px; top: -0.986113px; left: -8.24658px;"></span></a>
					</div>
				</div>
				<div class="portlet-body">
				<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-6"><?php echo lang('sales_monsth_filter_year')?></label>
							<div class="col-md-4 pull-right">
					            <div class="form-group">
								<div class="input-group date" data-date="10/11/2012" data-date-format="mm/dd/yyyy" id='sales_monsth_year'>
									<input type="text" class="form-control" name="sales_monsth" id="sales_monsth" >
									<span class="input-group-addon" onclick="cargar_data_sales_monsth()"> Buscar </span>
								</div>
								</div>
								
						    </div>
						</div>
					</div>
					<div id="chartdiv_get_sales_monsth" class="chart_custom">
						<?php $this->load->view("charts/sales_monsth"); ?>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
	<!-- END CHARTS -->
	<script src="<?php echo base_url();?>js/jquery.blockUI.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{

		$('#earnings_monsth').datetimepicker({
			    locale: 'es',
			    format: 'YYYY',
			    defaultDate: new Date()
			});

		$('#sales_monsth').datetimepicker({
			    locale: 'es',
			    format: 'YYYY',
			    defaultDate: new Date()
			});


         <?php if($this->config->item('hide_video_stack1') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack1').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){

         if($(e.target)[0].id == "stack1")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack1').css({'display':'none'});

         }


         });


         $('#close').click(function(){

               $('.modal.fade.in').removeClass('in');
               $('#stack1').css({'display':'none'});
            	$('#maxhome').removeClass('icon fa fa-youtube-play help_button');
               	 $('#maxhome').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");

         });

         $('#checkBoxStack1').click(function(e){

             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video1:$(this).is(':checked') ? '1' : '0',video1:'hide_video_stack1'});

         });

			$('#weeklysales').datetimepicker({
			    locale: 'es',
			    format: 'YYYY-MM-DD',
			    defaultDate: new Date()
			});

			//Obtener el valor del input
			value_input = $("#weeklysales_input").val();
			weekstart = moment(value_input).weekday(1).format('YYYY-MM-DD');  //Obtener fecha inicio de semana actual (iniciando lunes)
			weekend = moment(value_input).weekday(7).format('YYYY-MM-DD');  //Obtener fecha final de la semana actual (finalizando domingo)

			$("#weeklysales_input").val(weekstart+' a '+weekend); //Agregando al input el rango inicial de la semana

			//Get the value of Start and End of Week
			$('#weeklysales').on('dp.change', function (e) {
			    value = $("#weeklysales_input").val();
			    firstDate = moment(value, "YYYY-MM-DD").day(1).format("YYYY-MM-DD");
			    lastDate =  moment(value, "YYYY-MM-DD").day(7).format("YYYY-MM-DD");
			    value2 = $("#weeklysales_input").val(firstDate + " a " + lastDate);

			    strdateto= firstDate.replace("/", "-");
     			strdatefrom= lastDate.replace("/", "-");
				tstrdateto= strdateto.replace("/", "-");
				tstrdatefrom= strdatefrom.replace("/", "-");
			    $.ajax({
		            method: "POST",
		            url: '<?=site_url();?>/home/index/'+ tstrdateto+'/'+tstrdatefrom,
		            data: { start_date:firstDate, end_date:lastDate },
		            success: function(data)
		            {
						$('#chartdiv_get_weekly_sales').html(data);
		            },
		        });
			});
		});
	</script>
	<script>
	$(document).ready(function()
	{

	// Ventas totales por tienda
		$('#sales_store_date_start').datetimepicker({
			locale: 'es',
			format: 'YYYY-MM-DD',
			defaultDate: new Date(),
		});
		$('#sales_store_date_end').datetimepicker({
			locale: 'es',
			format: 'YYYY-MM-DD',
			defaultDate: new Date(),
		});
		
			//Obtener el valor del input
			value_input = $("#sales_store_date_start").val();
			date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD');  

			$("#sales_store_date_end").val(date_end); //Agrega un dia a la fecha fin
				
			//fecha inicio
			$('#sales_store_date_start').on('dp.change', function (e) {
			    if($("#sales_store_date_start").val()>=$("#sales_store_date_end").val()){
					value_input = $("#sales_store_date_start").val();
					date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD'); 
					$("#sales_store_date_end").val(date_end); //Agrega un dia a la fecha fin
				}

			});
			//fecha fin
			$('#sales_store_date_end').on('dp.change', function (e) {
			    if($("#sales_store_date_end").val()<=$("#sales_store_date_start").val()){
					value_input = $("#sales_store_date_end").val();
					date_end = moment(value_input).startOf('day').add(-1, 'day').format('YYYY-MM-DD'); 
					$("#sales_store_date_start").val(date_end); //Agrega un dia a la fecha fin
				}

			});

			
		});
		function cargar_data_earnings_monsth(){
				date_start = $("#earnings_monsth").val();
			    strdateto= date_start.replace("/", "-");
				tstrdateto= strdateto.replace("/", "-");
			    $.ajax({
		            method: "POST",
		            url: '<?=site_url();?>/home/get_sales_earnings_monsth/'+tstrdateto,
		            data: { start_date:date_start, end_date:date_end },
		            success: function(data)
		            {
						if(JSON.parse(data)!=null){
							set_sales_earnings_monsth(JSON.parse(data));
						}else{
							set_sales_earnings_monsth([]);
						}
						
					},
		        });
		}
		// ventas por anuales por meses
		function cargar_data_sales_monsth(){
				date_start = $("#sales_monsth").val();
			    strdateto= date_start.replace("/", "-");
				tstrdateto= strdateto.replace("/", "-");
			    $.ajax({
		            method: "POST",
		            url: '<?=site_url();?>/home/get_sales_monsth/'+tstrdateto,
		            data: { start_date:date_start, end_date:date_end },
		            success: function(data)
		            {
						if(JSON.parse(data)!=null){
							set_sales_monsth(JSON.parse(data));
						}else{
							set_sales_monsth([]);
						}
						
					},
		        });
		}
		function cargar_data_sales_by_store(){
				date_start = $("#sales_store_date_start").val();
				value_input = $("#sales_store_date_end").val();
				date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD');

			    strdateto= date_start.replace("/", "-");
     			strdatefrom= date_end.replace("/", "-");
				tstrdateto= strdateto.replace("/", "-");
				tstrdatefrom= strdatefrom.replace("/", "-");
			    $.ajax({
		            method: "POST",
		            url: '<?=site_url();?>/home/get_sales_store/'+ tstrdateto+'/'+tstrdatefrom,
		            data: { start_date:date_start, end_date:date_end },
		            success: function(data)
		            {
						if(JSON.parse(data)!=null){
							set_sales_by_store_total(JSON.parse(data));
						}else{
							set_sales_by_store_total([]);
						}
						
					},
		        });
			}
		$(document).ready(function()
		{
			// Ventas totales por tienda mostrar dinero
		$('#sales_store_date_start_money').datetimepicker({
			locale: 'es',
			format: 'YYYY-MM-DD',
			defaultDate: new Date(),
		});
		$('#sales_store_date_end_money').datetimepicker({
			locale: 'es',
			format: 'YYYY-MM-DD',
			defaultDate: new Date(),
		});
		
			//Obtener el valor del input
			value_input = $("#sales_store_date_start__money").val();
			date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD');  

			$("#sales_store_date_end_money").val(date_end); //Agrega un dia a la fecha fin
				
			//fecha inicio
			$('#sales_store_date_start_money').on('dp.change', function (e) {
			    if($("#sales_store_date_start_money").val()>=$("#sales_store_date_end_money").val()){
					value_input = $("#sales_store_date_start_money").val();
					date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD'); 
					$("#sales_store_date_end_money").val(date_end); //Agrega un dia a la fecha fin
				}

			});
			//fecha fin
			$('#sales_store_date_end_money').on('dp.change', function (e) {
			    if($("#sales_store_date_end_money").val()<=$("#sales_store_date_start_money").val()){
					value_input = $("#sales_store_date_end_money").val();
					date_end = moment(value_input).startOf('day').add(-1, 'day').format('YYYY-MM-DD'); 
					$("#sales_store_date_start_money").val(date_end); //Agrega un dia a la fecha fin
					
				}
				

			});

			
		});
		function cargar_data_sales_by_store_money(){
				date_start = $("#sales_store_date_start_money").val();
				value_input = $("#sales_store_date_end_money").val();
				date_end = moment(value_input).startOf('day').add(1, 'day').format('YYYY-MM-DD');

			    strdateto= date_start.replace("/", "-");
     			strdatefrom= date_end.replace("/", "-");
				tstrdateto= strdateto.replace("/", "-");
				tstrdatefrom= strdatefrom.replace("/", "-");
			    $.ajax({
		            method: "POST",
		            url: '<?=site_url();?>/home/get_sales_store_money/'+ tstrdateto+'/'+tstrdatefrom,
		            data: { start_date:date_start, end_date:date_end },
		            success: function(data)
		            {
						if(JSON.parse(data)!=null){
							set_sales_by_store_total_money(JSON.parse(data));
						}else{
							set_sales_by_store_total_money([]);
						}
						
					},
		        });
			}
	</script>
<?php if($this->config->item('initial_config') == 0){ ?>
 <script type="text/javascript">$(document).ready(function(){$('#language > option[value="<?php echo $this->Appconfig->get_raw_language_value(); ?>"]').attr('selected', 'selected');});</script>
<?php } ?>
<script>
      $(document).ready(function(){

      	var radio;
         $("#mostrarmodal").modal("show");
         $( "input:radio[name=cobra]" ).change(function() {
          $('#msj').html("");
          radio=$(this).val();
        if($(this).val() == "si"){
        $(".impuesto").show( "slow" );
        }
        else
        {
         $( ".impuesto" ).hide( "slow" );
         	$('#impuesto').val("");
	        $('#nombre_impuesto').val("");
		}
	});
});
</script>
<?php if(count($profiles) && !$this->config->item('Hide_panel_type_business') && $this->Employee->es_demo()){ ?>
<script type="text/javascript">
function dialogo(content){
    $.blockUI({ 
		message:content,
		css: { 
            border: 'none', 
            padding: '5px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
		} }); 
		
}


$(document).ready(function(){

	$('#language > option[value="<?php echo $this->Appconfig->get_raw_language_value(); ?>"]').attr('selected', 'selected');
	//$("#configProfileType").modal("show");
	$('#configProfileType').modal({
	    backdrop: 'static',
  		keyboard: false
	})

	$('[id^=modal-profile-box-]').hover(function(e){
			$(this).css('cursor','pointer');
		});

	$('[id^=modal-profile-box-]').click(function(e){

		var array_profile = this.id.split("-");
		var profile = array_profile[3];
		
		if(confirm("Desea cargar el perfil: "+$("#profile-name-"+array_profile[4]).html())) {
			dialogo("<div id='id_sincronizacion_offline_contenido'> <center><img src='"+BASE_URL+"/img/loading_perfil.gif' alt='' width='30%' ></center><br> <h4>Esto podría tardar varios minutos</h4> </div>");
			$('#configProfileType').modal('toggle'); 
				
			$.ajax({
			    url: "<?php echo site_url("home/cargar_perfil"); ?>",
			    data: { perfil : profile },
			    type: "post",
			    dataType:"text",
			    success: function(response, textStatus, jqXHR){
		            if(response){
		              //console.log(response);
		            	location.reload();
		            }
			    },
			    error: function(jqXHR, textStatus, errorThrown){
			        console.log("The following error occured: "+
			                    textStatus, errorThrown); //+ JSON.stringify(jqXHR)
			    }
			});
		}
	});
});
</script>
<?php } ?>

<?php if($this->config->item('initial_config') == 0){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#language > option[value="<?php echo $this->Appconfig->get_raw_language_value(); ?>"]').attr('selected', 'selected');

	var radio;
 	$("#mostrarmodal").modal("show");

 	$( "input:radio[name=cobra]" ).change(function() {
		$('#msj').html("");
		radio=$(this).val();
		if($(this).val() == "si"){
			$(".impuesto").show( "slow" );
		} else {
		 	$( ".impuesto" ).hide( "slow" );
		 	$('#impuesto').val("");
		    $('#nombre_impuesto').val("");
		}
 	});

	$("select[name=language]").change(function(){
	 	var fields = $("#confi_impuesto_form").serialize();
	 	$('#msj').html("");
		$.ajax({
		    url:"<?php echo site_url("home/view_language_currency_symbol_modal");?>",
		    data:fields,
		    type: "post",
		    dataType:"json",
		    success: function(response, textStatus, jqXHR){
		        $('#moneda').val(response.currency_symbol);
		        $('#impuesto').val(response.default_tax_1_rate);
		        $('#nombre_impuesto').val(response.default_tax_1_name);
		    },
		    error: function(jqXHR, textStatus, errorThrown){
		        console.log("The following error occured: "+
		                    textStatus, errorThrown);
		    }
		});
	});

	$('#button').click(function(e){
		if(radio != "no"){
			if($('#nombre_impuesto').val() == ""){
				$('#msj').html("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>&times;</button>Campo Nombre Impuesto esta vacio</div>");
				return false;
			}else if($('#impuesto').val() == ""){
				$('#msj').html("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>&times;</button>Campo Impuesto esta vacio</div>");
				return false;
			}
		}

		var fields = $("#confi_impuesto_form").serialize();
		$.ajax({
		    url:"<?php echo site_url("home/initial_config");?>",
		    data:fields,
		    type: "post",
		    dataType:"json",
		    success: function(response, textStatus, jqXHR){
		        $("#mostrarmodal").modal('hide');
		    },
		    error: function(jqXHR, textStatus, errorThrown){
		        console.log("The following error occured: "+ textStatus, errorThrown);
		    }
		});
	});
});
</script>

<?php } ?>

<?php $this->load->view("partial/footer"); ?>
