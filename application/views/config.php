<?php $this->load->view("partial/header"); ?>
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-cogs"></i> 
				<?php echo lang('module_'.$controller_name); ?>
				<?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=NE4IWySqQc8&t=2s";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("CONFIGURACIÓN");
						if($url_video!=null){
							$url_video_ver=$url_video;
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				?>
			<!--<a class="icon fa fa-youtube-play help_button" id='maxconfig' rel='0' data-toggle="modal" data-target="#stack2"></a>-->
			</h1>

		</div>
		<div class="page-search">
			<?php echo form_label('Buscar:', 'search', array('class'=>'col-md-3 control-label')); ?>
			<div class="col-md-9">
				<?php echo form_input(array(
					'class'=>'form-control',
					'name'=>'search',
					'data-toggle'=>'hideseek',
					'data-list'=>'#company_info, #tax_currency_info, #sales_receipt_info, #suspended_sales_layaways_info, #interface_personalization, #application_settings_info',
					'id'=>'search'));
				?>
			</div>
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
   	

	<!-- BEGIN  -->
	<div class="row" id="sieve-container">
		<?php echo form_open_multipart('config/save/',array('id'=>'config_form','class'=>'form-horizontal', 'autocomplete'=> 'off'));   ?>
			
			<!-- Company Information -->
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_company_info_help') ?>">
								<a class="help_config tooltips"><?php echo lang("config_company_info"); ?></a>
							</span>
							<span class="caption-helper">
								<a class="option_company_info"><?php echo lang("config_more_options") ?></a>
							</span>
							<?php 
								$extra="";
								$url_video_ver="https://www.youtube.com/watch?v=A7AGsLGGLso&feature=youtu.be";
								if($this->Appconfig->es_franquicia()){
									$url_video=	$this->Appconfig->get_video("INFORMACIÓN DE LA COMPAÑÍA");
									if($url_video!=null){
										$url_video_ver=$url_video;
									}else{
										$extra=" style='display: none; '";
									}
								}
								$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_compania_video" >Ver Vídeo</a>';
								echo $a_video;				
							?>							
						
						</div>
						<div class="">
							<a id="icon_company_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
						
						
					</div>
				
					<div id="company_info" class="portlet-body form">						
						<div class="form-group ">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_company_info_logo_help").'">'.lang('config_company_logo').'</a>'.':', 'company_logo', array('class'=>'col-md-3 control-label')); ?> 						
							<div class="col-md-9">
								<?php echo form_upload(array(
									'name'=>'company_logo',
									'id'=>'company_logo',
									'class' => 'file form-control',
									'data-show-upload' => 'false',
									'data-show-remove' => 'false',
									'value'=>$this->config->item('company_logo')));
								?>	
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label(lang('config_delete_logo').':', 'delete_logo',array('class'=>'col-md-3 control-label')); ?>							
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">		
										<?php echo form_checkbox(array('name'=>'delete_logo', 'id'=>'checkbox30', 'value'=>'1', 'class'=>'md-check'));?>																		
										<label for="checkbox30">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>									
								</div>
							</div>
						</div>	


						<div class="form-group">	
							<?php echo form_label(lang('config_company').':', 'company',array('class'=>'col-md-3 control-label')); ?>						
							<div class="col-md-9">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'company',
									'id'=>'company',
									'value'=>$this->config->item('company')));
								?>								
							</div>
						</div>


						<div class="form-group">					
							<?php echo form_label(lang('config_company_dni').':', 'company_dni',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'company_dni',
									'id'=>'company_dni',
									'value'=>$this->config->item('company_dni')));
								?>
							</div>
						</div>
						<?php if($this->config->item('language')=="spanish_chile"):?>
							<div class="form-group">					
								<?php echo form_label(lang('config_giros').':', 'company_giros',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<?php echo form_input(array(
										'class'=>'form-control',
										'name'=>'company_giros',
										'id'=>'company_giros',
										"placeholder"=>"Ejemplo: Botillerías, Almacén, Verdulería, ...",
										'value'=>$this->config->item('company_giros')));
									?>
								</div>
							</div>
						<?php endif; ?>

						<div class="form-group">	
							<?php echo form_label(' <a class="help_config_options tooltips" data-placement="left"  title="'.lang("config_company_regimen_help").'">'. lang('config_company_regimen').'</a>'.':','company_dni',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'company_regimen',
									'id'=>'company_regimen',
									'value'=>$this->config->item('company_regimen')));
								?>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_website').':', 'website',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
							<?php echo form_input(array(
								'class'=>'form-control form-inps',
								'name'=>'website',
								'id'=>'website',
								'value'=>$this->config->item('website')));?>
							</div>
						</div>
						<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("locations_phone_help").'">'.lang('locations_phone').'</a>'.':', 'phone',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-9">
									<?php echo form_input(array(
										'class'=>'form-control form-inps',
										'name'=>'phone',
										'id'=>'phone',
										'value'=>$this->config->item('phone'))
									);?>
								</div>
							</div>
						<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("locations_address_help").'">'.lang('locations_address').'</a>'.':', 'address',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-9">									
									<?php echo form_textarea(array(
										'name'=>'address',
										'id'=>'address',
										'class'=>'form-textarea',
										'rows'=>'2',
										'cols'=>'30',
										'value'=>$this->config->item('address'))
									);?>								
								</div>
							</div> 
							
							
					</div>
				</div>
			</div>
			<!-- Taxes & Currency -->
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips"  data-placement="right"  title="<?php  echo lang('config_tax_currency_info_help') ?>">
								<a class="help_config"><?php echo lang("config_tax_currency_info"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_tax_currency_info"><?php echo lang("config_more_options") ?></a></span>
							<?php 
								$extra="";
								$url_video_ver="https://www.youtube.com/watch?v=YN1XPoJ5hBw&feature=youtu.be";
								if($this->Appconfig->es_franquicia()){
									$url_video=	$this->Appconfig->get_video("IMPUESTOS Y MONEDA");
									if($url_video!=null){
										$url_video_ver=$url_video;
									}else{
										$extra=" style='display: none; '";
									}
								}
								$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_impuesto_moneda" >Ver Vídeo</a>';
								echo $a_video;				
							?>	
						</div>
						<div class="tools">
							<a id="icon_tax_currency_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="tax_currency_info" class="portlet-body form">						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left"  title="'.lang("config_tax_currency_prices_include_help").'">'.lang('common_prices_include_tax').'</a>'.':', 'prices_include_tax',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">		
										<?php echo form_checkbox(array(
											'name'=>'prices_include_tax', 
											'id'=>'prices_include_tax', 
											'value'=>'prices_include_tax', 
											'class'=>'md-check', 
											'checked'=>$this->config->item('prices_include_tax')));
										?>
										<label for="prices_include_tax">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>									
								</div>							
							</div>
						</div>	
						<div class="form-group">	
							<?php echo form_label(lang('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-5">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'default_tax_1_name',
									'placeholder' => lang('common_tax_name'),
									'id'=>'default_tax_1_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_1_name')!==FALSE ? $this->config->item('default_tax_1_name') : lang('items_sales_tax_1')));
								?>
							</div>
							
							<div class="col-md-4">
								<div class="input-group">
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_1_rate',
										'id'=>'default_tax_1_rate',
										'size'=>'4',
										'value'=>$this->config->item('default_tax_1_rate')));
									?>
									<span class="input-group-addon">%</span>
								</div>
							</div>							
						</div>	
						<div class="form-group">	
							<?php echo form_label(lang('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-5">
								<?php echo form_input(array(
								'class'=>'form-control',
								'name'=>'default_tax_2_name',
								'placeholder' => lang('common_tax_name'),
								'id'=>'default_tax_2_name',
								'size'=>'10',
								'value'=>$this->config->item('default_tax_2_name')!==FALSE ? $this->config->item('default_tax_2_name') : lang('items_sales_tax_2')));?>
							</div>

							<div class="col-md-4">
								<div class="input-group">
									<?php echo form_input(array(
									'class'=>'form-control',	
									'name'=>'default_tax_2_rate',
									'placeholder' => lang('items_tax_percent'),
									'id'=>'default_tax_2_rate',
									'size'=>'4',
									'value'=>$this->config->item('default_tax_2_rate')));?>
									<span class="input-group-addon">%</span>		    							
								</div>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'default_tax_2_cumulative', 
											'id'=>'default_tax_2_cumulative', 
											'value'=>'1', 
											'class'=>'md-check cumulative', 
											'checked'=>$this->config->item('default_tax_2_cumulative') ? true : false));
										?>
										<label for="default_tax_2_cumulative">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										<?php echo lang('common_cumulative'); ?>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-offset-3" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'none' : 'block';?>">
							<button href="javascript:void(0);" class="btn btn-circle btn-xs btn-warning show_more_taxes"><?php echo lang('common_show_more');?> &raquo;</button>
						</div>
					
						<div class="more_taxes_container" style="display: <?php echo $this->config->item('default_tax_3_rate') ? 'block' : 'none';?>">
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_3').':', 'default_tax_3_rate',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-5">
									<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'default_tax_3_name',
									'placeholder' => lang('common_tax_name'),
									'id'=>'default_tax_3_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_3_name')!==FALSE ? $this->config->item('default_tax_3_name') : lang('items_sales_tax_3')));?>
								</div>							
								<div class="col-md-4">
									<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_3_rate',
										'id'=>'default_tax_3_rate',
										'size'=>'4',
										'value'=>$this->config->item('default_tax_3_rate')));?>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>								
							
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_4').':', 'default_tax_4_rate',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-5">
									<?php echo form_input(array(
									'class'=>'form-control',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_4_name',
									'id'=>'default_tax_4_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_4_name')!==FALSE ? $this->config->item('default_tax_4_name') : lang('items_sales_tax_4')));?>
								</div>							
								<div class="col-md-4">
									<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_4_rate',
										'id'=>'default_tax_4_rate',
										'size'=>'4',
										'value'=>$this->config->item('default_tax_4_rate')));?>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>
							
							<div class="form-group">	
								<?php echo form_label(lang('config_default_tax_rate_5').':', 'default_tax_5_rate',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-5">
									<?php echo form_input(array(
									'class'=>'form-control',
									'placeholder' => lang('common_tax_name'),
									'name'=>'default_tax_5_name',
									'id'=>'default_tax_5_name',
									'size'=>'10',
									'value'=>$this->config->item('default_tax_5_name')!==FALSE ? $this->config->item('default_tax_5_name') : lang('items_sales_tax_5')));?>
								</div>							
								<div class="col-md-4">
									<div class="input-group">
										<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' => lang('items_tax_percent'),
										'name'=>'default_tax_5_rate',
										'id'=>'default_tax_5_rate',
										'size'=>'4',
										'value'=>$this->config->item('default_tax_5_rate')));?>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>
						</div>	
						<br/>
						<div class="form-group">	
							<?php echo form_label(lang('config_barcode_price_include_tax').':', 'barcode_price_include_tax',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">		
										<?php echo form_checkbox(array(
											'name'=>'barcode_price_include_tax', 
											'id'=>'barcode_price_include_tax', 
											'value'=>'barcode_price_include_tax', 
											'class'=>'md-check', 'checked'=>$this->config->item('barcode_price_include_tax')));
										?>
										<label for="barcode_price_include_tax">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>									
								</div>								
							</div>
						</div>		
						<div class="form-group">	
							<?php echo form_label(lang('config_currency_symbol').':', 'currency_symbol',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'currency_symbol',
									'id'=>'currency_symbol',
									'value'=>$this->config->item('currency_symbol')));
								?>
							</div>
						</div>			
					</div>
				</div>
			</div>
			<!-- Sales & Receipt -->
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_sales_receipt_help') ?>">
								<a class="help_config"><?php echo lang("config_sales_receipt_info"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_sales_receipt_info"><?php echo lang("config_more_options") ?></a></span>
							<?php 
								$extra="";
								$url_video_ver="https://www.youtube.com/watch?v=n0Gm7IPc790&feature=youtu.be";
								if($this->Appconfig->es_franquicia()){
									$url_video=	$this->Appconfig->get_video("VENTAS Y RECIBO");
									if($url_video!=null){
										$url_video_ver=$url_video;
									}else{
										$extra=" style='display: none; '";
									}
								}
								$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_ventas_recibo" >Ver Vídeo</a>';
								echo $a_video;				
							?>	
						</div>
						<div class="tools">
							<a id="icon_sales_receipt_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="sales_receipt_info" class="portlet-body form">						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left" title="'.lang("config_sales_receipt_prefix_help"). '">'.lang('config_prefix').'</a>'.':', 'sale_prefix',array('class'=>'col-md-3 control-label  required')); ?>
							<div class="col-md-9 ">
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'sale_prefix',
									'id'=>'sale_prefix',
									'value'=>$this->config->item('sale_prefix')));
								?>
							</div>
						</div>
                        
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_sales_receipt_id_interface_help"). '">'.lang('config_id_to_show_on_sale_interface').'</a>'.':', 'language',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">								
								<?php echo form_dropdown('id_to_show_on_sale_interface', array(
									'number' => lang('items_item_number'),
									'product_id' => lang('items_product_id'),
									'id' => lang('items_item_id')),
									$this->config->item('id_to_show_on_sale_interface'), 'class="bs-select form-control"')
								?>
							</div>
						</div>
                        
                        <div class="form-group">	
                        	<?php echo form_label('Copias de factura en impresión'.':', 'sale_prefix',array('class'=>'col-md-3 control-label  required')); ?>
                        	<div class="col-md-9 ">
                        		<?php echo form_input(array(
                        			'class'=>'form-control',
                        			'name'=>'receipt_copies',
                        			'id'=>'receipt_copies',
                        			'value'=>$this->config->item('receipt_copies')));
                        		?>
                        	</div>
                        </div>
                        
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_sales_receipt_print_after_help"). '">'.lang('config_print_after_sale').'</a>'.':', 'print_after_sale',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">								
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'print_after_sale',
											'id'=>'print_after_sale',
											'value'=>'print_after_sale',
											'class'=>'md-check',
											'checked'=>$this->config->item('print_after_sale')));
										?>
										<label for="print_after_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_print_after_receiving').':', 'print_after_receiving',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'print_after_receiving',
											'id'=>'print_after_receiving',
											'value'=>'print_after_receiving',
											'class'=>'md-check',
											'checked'=>$this->config->item('print_after_receiving')));
										?>
										<label for="print_after_receiving">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_enabled_for_Restaurant').':', 'enabled_for_Restaurant',array('class'=>'col-md-3 control-label','style'=>"font-size:17px ; color:black")); ?>
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'enabled_for_Restaurant',
											'id'=>'enabled_for_Restaurant',
											'value'=>'enabled_for_Restaurant',
											'class'=>'md-check',
											'checked'=>$this->config->item('enabled_for_Restaurant')));
										?>
										<label for="enabled_for_Restaurant">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-8 ">	
								<?php 
									$extra="";
									$url_video_ver="https://www.youtube.com/watch?v=sxTHNU7TLk8";
									if($this->Appconfig->es_franquicia()){
										$url_video=	$this->Appconfig->get_video("RESTAURANTE");
										if($url_video!=null){
											$url_video_ver=$url_video;
										}else{
											$extra=" style='display: none; '";
										}
									}
									$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_subcategoria" >Ver Vídeo</a>';
									echo $a_video;				
								?>
							
									<!--<a class="icon fa fa-youtube-play help_button" id='subcategory_video' rel='0' data-toggle="modal" data-target="#stack9">Ver Vídeo</a>	-->							
							</div>
						</div>
						 <div class="form-group">	
                        	<?php echo form_label(lang('config_table_acount').':', 'table_acount',array('class'=>'col-md-3 control-label')); ?>
                        	<div class="col-md-9 ">
                        		<?php echo form_input(array(
                        			'class'=>'form-control',
                        			'name'=>'table_acount',
                        			'id'=>'table_acount',
                        			'value'=>$this->config->item('table_acount')));
                        		?>
                        	</div>
                        </div>
						<div class="form-group">	
							<?php echo form_label(lang('config_auto_focus_on_item_after_sale_and_receiving').':', 'auto_focus_on_item_after_sale_and_receiving',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'auto_focus_on_item_after_sale_and_receiving',
											'id'=>'auto_focus_on_item_after_sale_and_receiving',
											'value'=>'auto_focus_on_item_after_sale_and_receiving',
											'class'=>'md-check',
											'checked'=>$this->config->item('auto_focus_on_item_after_sale_and_receiving')));
										?>
										<label for="auto_focus_on_item_after_sale_and_receiving">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
							<div class="form-group">	
							<?php echo form_label(lang('config_ticket_hide').':', 'hide_ticket',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_ticket',
											'id'=>'hide_ticket',
											'value'=>'hide_ticket',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_ticket')));
										?>
										<label for="hide_ticket">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
                        
                        <div class="form-group">	
                            <?php echo form_label(lang('config_default_sales_type').':', 'config_default_sales_type',array('class'=>'col-md-3 control-label ')); ?>
                            <div class="col-md-9">
                                <div class="md-radio-inline">
                                    <div class="md-radio">
                                        <?php  
                                            echo '<div class="md-radio-inline">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                'name'=>'default_sales_type',
                                                'id'=>'default_sales_type_invoice',
                                                'value'=>'0',
                                                'class'=>'md-check',
                                                'checked'=>$this->config->item('default_sales_type') ? 0 : 1)
                                            );
                                    
                                            echo '<label id="show_comment_invoice" for="default_sales_type_invoice">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo lang('sales_invoice');
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                        ?>
                                        
                                        <?php  
                                            echo '<div class="md-radio-inline">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                'name'=>'default_sales_type',
                                                'id'=>'default_sales_type_ticket',
                                                'value'=>'1',
                                                'class'=>'md-check',
                                                'checked'=> $this->config->item('default_sales_type') ? 1 : 0)
                                            );
                                    
                                            echo '<label id="show_comment_ticket" for="default_sales_type_ticket">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo lang('sales_ticket_on_receipt');
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
						<div class="form-group">	
							<?php echo form_label(lang('config_ticket_hide_taxes').':', 'hide_ticket_taxes',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_ticket_taxes',
											'id'=>'hide_ticket_taxes',
											'value'=>'hide_ticket_taxes',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_ticket_taxes')));
										?>
										<label for="hide_ticket_taxes">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_invoice_hide_taxes').':', 'hide_invoice_taxes_details',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_invoice_taxes_details',
											'id'=>'hide_invoice_taxes_details',
											'value'=>'hide_invoice_taxes_details',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_invoice_taxes_details')));
										?>
										<label for="hide_invoice_taxes_details">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_ocultar_forma_pago').':', 'ocultar_forma_pago',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'ocultar_forma_pago',
											'id'=>'ocultar_forma_pago',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('ocultar_forma_pago')));
										?>
										<label for="ocultar_forma_pago">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_hide_signature').':', 'hide_signature',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_signature',
											'id'=>'hide_signature',
											'value'=>'hide_signature',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_signature')));
										?>
										<label for="hide_signature">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_hide_customer_recent_sales').':', 'hide_customer_recent_sales',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_customer_recent_sales',
											'id'=>'hide_customer_recent_sales',
											'value'=>'hide_customer_recent_sales',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_customer_recent_sales')));
										?>
										<label for="hide_customer_recent_sales">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_hide_balance_receipt_payment').':', 'hide_balance_receipt_payment',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_balance_receipt_payment',
											'id'=>'hide_balance_receipt_payment',
											'value'=>'hide_balance_receipt_payment',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_balance_receipt_payment')));
										?>
										<label for="hide_balance_receipt_payment">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_sales_receipt_confirmation_sale_help"). '">'.lang('disable_confirmation_sale').'</a>'.':', 'disable_confirmation_sale',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'disable_confirmation_sale',
											'id'=>'disable_confirmation_sale',
											'value'=>'disable_confirmation_sale',
											'class'=>'md-check',
											'checked'=>$this->config->item('disable_confirmation_sale')));
										?>
										<label for="disable_confirmation_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_round_cash_on_sales').':', 'round_cash_on_sales',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'round_cash_on_sales',
											'id'=>'round_cash_on_sales',
											'value'=>'round_cash_on_sales',
											'class'=>'md-check',
											'checked'=>$this->config->item('round_cash_on_sales')));
										?>
										<label for="round_cash_on_sales">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_automatically_email_receipt').':', 'automatically_email_receipt',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'automatically_email_receipt',
											'id'=>'automatically_email_receipt',
											'value'=>'automatically_email_receipt',
											'class'=>'md-check',
											'checked'=>$this->config->item('automatically_email_receipt')));
										?>
										<label for="automatically_email_receipt">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_automatically_show_comments_on_receipt').':', 'automatically_show_comments_on_receipt',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'automatically_show_comments_on_receipt',
											'id'=>'automatically_show_comments_on_receipt',
											'value'=>'automatically_show_comments_on_receipt',
											'class'=>'md-check',
											'checked'=>$this->config->item('automatically_show_comments_on_receipt')));
										?>
										<label for="automatically_show_comments_on_receipt">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_automatically_calculate_average_cost_price_from_receivings').':', 'calculate_average_cost_price_from_receivings',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'calculate_average_cost_price_from_receivings',
											'id'=>'calculate_average_cost_price_from_receivings',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('calculate_average_cost_price_from_receivings')));
										?>
										<label for="calculate_average_cost_price_from_receivings">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div id="average_cost_price_from_receivings_methods">
							<div class="form-group">	
							<?php echo form_label($this->lang->line('config_averaging_method').':', 'averaging_method',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">								
									<?php echo form_dropdown('averaging_method', array(									
									'moving_average' => lang('config_moving_average'), 
									'price_average' => lang('config_price_average'),
									'historical_average' => lang('config_historical_average')), 
									$this->config->item('averaging_method'), 'class="bs-select form-control"'); ?>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_sales_receipt_track_cash_help"). '">'.lang('config_track_cash').'</a>'.':', 'track_cash',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'track_cash',
											'id'=>'track_cash',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('track_cash')));
										?>
										<label for="track_cash">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left" >¿Mostrar reporte del seguimiento de la caja al cerrarla?</a>'.':', 'show_report_register_close',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_report_register_close',
											'id'=>'show_report_register_close',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_report_register_close')));
										?>
										<label for="show_report_register_close">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_disable_giftcard_detection').':', 'disable_giftcard_detection',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'disable_giftcard_detection',
											'id'=>'disable_giftcard_detection',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('disable_giftcard_detection')));
										?>
										<label for="disable_giftcard_detection">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_always_show_item_grid').':', 'always_show_item_grid',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'always_show_item_grid',
											'id'=>'always_show_item_grid',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('always_show_item_grid')));
										?>
										<label for="always_show_item_grid">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_hide_barcode_on_sales_and_recv_receipt').':', 'hide_barcode_on_sales_and_recv_receipt',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_barcode_on_sales_and_recv_receipt',
											'id'=>'hide_barcode_on_sales_and_recv_receipt',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_barcode_on_sales_and_recv_receipt')));
										?>
										<label for="hide_barcode_on_sales_and_recv_receipt">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_round_tier_prices_to_2_decimals').':', 'round_tier_prices_to_2_decimals',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'round_tier_prices_to_2_decimals',
											'id'=>'round_tier_prices_to_2_decimals',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('round_tier_prices_to_2_decimals')));
										?>
										<label for="round_tier_prices_to_2_decimals">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_group_all_taxes_on_receipt').':', 'group_all_taxes_on_receipt',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'group_all_taxes_on_receipt',
											'id'=>'group_all_taxes_on_receipt',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('group_all_taxes_on_receipt')));
										?>
										<label for="group_all_taxes_on_receipt">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_require_customer_for_sale').':', 'require_customer_for_sale',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'require_customer_for_sale',
											'id'=>'require_customer_for_sale',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('require_customer_for_sale')));
										?>
										<label for="require_customer_for_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_sales_stock_inventory').':', 'sales_stock_inventory',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'sales_stock_inventory',
											'id'=>'sales_stock_inventory',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('sales_stock_inventory')));
										?>
										<label for="sales_stock_inventory">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("show_payments_ticket_help"). '">'.lang('show_payments_ticket').'</a>'.':', 'show_payments_ticket',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_payments_ticket',
											'id'=>'show_payments_ticket',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_payments_ticket')));
										?>
										<label for="show_payments_ticket">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_payment_types').':', 'additional_payment_types',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php echo lang('sales_cash'); ?>, 
								<?php echo lang('sales_check'); ?>, 
								<?php echo lang('sales_giftcard'); ?>, 
								<?php echo lang('sales_debit'); ?>, 
								<?php echo lang('sales_credit'); ?>,
								<?php echo lang('sales_supplier_credit'); ?>
								<?php echo form_input(array(
									'class'=>'form-control',
									'name'=>'additional_payment_types',
									'id'=>'additional_payment_types',
									'size'=> 40,
									'value'=>$this->config->item('additional_payment_types')));
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_default_payment_type').':', 'default_payment_type',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">							
								<?php echo form_dropdown('default_payment_type', $payment_options, $this->config->item('default_payment_type'),'class="bs-select form-control"'); ?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_receipt_text_size').':', 'receipt_text_size',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('receipt_text_size', $receipt_text_size_options, $this->config->item('receipt_text_size'),'class="bs-select form-control"'); ?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="Espacio que separa el contenido del ticket y los bordes del papel">Border del ticket</a>'.':', 'padding_ticket', array('class'=>'col-md-3 control-label')); ?> 						

							<div class="col-md-9">
								<?php echo form_dropdown('padding_ticket',array(
									"0"=>"0 píxeles",
									"2"=>"1 píxeles",
									"2"=>"2 píxeles",
									"3"=>"3 píxeles",
									"4"=>"4 píxeles",
									"5"=>"5 píxeles",
									"6"=>"6 píxeles",
									"7"=>"7 píxeles",
									"8"=>"8 píxeles",
									"9"=>"9 píxeles",
									"10"=>"10 píxeles",
									"11"=>"11 píxeles",
									"12"=>"12 píxeles",
									"13"=>"13 píxeles",
									"14"=>"14 píxeles",
									"15"=>"15 píxeles",
								) , $this->config->item('padding_ticket'),'class="bs-select form-control"'); ?>
							</div>
						</div>
						







						  <div class="form-group">	
                            <?php echo form_label('Registrar venta al empledo que :', 'registro_venta',array('class'=>'col-md-3 control-label ')); ?>
                            <div class="col-md-9">
                                <div class="md-radio-inline">
                                    <div class="md-radio">
                                        <?php  
                                            echo '<div class="md-radio-inline">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                'name'=>'select_sales_person_during_sale',
                                                'id'=>'select_sales_person_during_sale_no',
                                                'value'=>'0',
                                                'class'=>'md-check',
                                                'checked'=>$this->config->item('select_sales_person_during_sale') ? 0 : 1)
                                            );
                                    
                                            echo '<label id="confi_sale_vende" for="select_sales_person_during_sale_no">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo "Completa la venta";
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                        ?>
                                        
                                        <?php  
                                            echo '<div class="md-radio-inline">';
                                            echo '<div class="md-radio">';
                                            echo form_radio(array(
                                                'name'=>'select_sales_person_during_sale',
                                                'id'=>'select_sales_person_during_sale_si',
                                                'value'=>'1',
                                                'class'=>'md-check',
                                                'checked'=> $this->config->item('select_sales_person_during_sale') ? 1 : 0)
                                            );
                                    
                                            echo '<label id="confi_sale_suspende" for="select_sales_person_during_sale_si">';
                                            echo '<span></span>';
                                            echo '<span class="check"></span>';
                                            echo '<span class="box"></span>';
                                            echo "Suspende la venta";
                                            echo '</label>';
                                            echo '</div>';
                                            echo '</div>';
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
							<?php echo form_input(array(
										'type'=>"hidden",
										'class'=>'form-control',
										'name'=>'default_sales_person',
										'id'=>'default_sales_person',
										'value'=> lang('employees_logged_in_employee')));
									?>
                        </div>









						<!--<div class="form-group">	
							<?php //echo form_label(lang('config_select_sales_person_during_sale').':', 'select_sales_person_during_sale',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php/* echo form_checkbox(array(
											'name'=>'select_sales_person_during_sale',
											'id'=>'select_sales_person_during_sale',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('select_sales_person_during_sale')));
										*/?>
										<label for="select_sales_person_during_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>-->
					<!--	<div class="form-group">	
						
							<?php //echo form_label(lang('config_default_sales_person').':', 'default_sales_person',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php //echo form_dropdown('default_sales_person', array('logged_in_employee' => lang('employees_logged_in_employee'), 'not_set' => lang('common_not_set')), $this->config->item('default_sales_person'),'class="bs-select form-control"'); ?>
							</div>
						</div>-->
						<div class="form-group">	
							<?php echo form_label(lang('config_commission_default_rate').' ('.lang('common_commission_help').'):', 'commission_default_rate',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input(array(
										'class'=>'form-control',
										'name'=>'commission_default_rate',
										'id'=>'commission_default_rate',
										'value'=>$this->config->item('commission_default_rate')));
									?>
									<span class="input-group-addon">%</span>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_disable_sale_notifications').':', 'disable_sale_notifications',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'disable_sale_notifications',
											'id'=>'disable_sale_notifications',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('disable_sale_notifications')));
										?>
										<label for="disable_sale_notifications">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
                        
						<div class="form-group">	
							<?php echo form_label(lang('config_due_date_alarm').':', 'config_due_date_alarm',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'due_date_alarm',
											'id'=>'due_date_alarm',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('due_date_alarm')));
										?>
										<label for="due_date_alarm">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
                        
						<div class="form-group">	
							<?php echo form_label(lang('config_show_fullname_item').':', 'show_fullname_item',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_fullname_item',
											'id'=>'show_fullname_item',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_fullname_item')));
										?>
										<label for="show_fullname_item">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="No permite agregar productos al carrito ingresando su id. Si el UPC/EAN/ISBN de los productos tiene menos de 6 caracteres,se recomienda activar esta opción  ">No dejar agregar productos al carrito por su id</a>'.':', 'add_item_by_id', array('class'=>'col-md-3 control-label')); ?> 						
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'add_cart_by_id_item',
											'id'=>'add_cart_by_id_item',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('add_cart_by_id_item')));
										?>
										<label for="add_cart_by_id_item">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
					<!--	<div class="form-group">
							<?php// echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="Si activa esta opción se descontara el número serial del producto cuando realice una venta ">Activar ventas por  seriales recargados</a>'.':', 'activate_sale_by_serial', array('class'=>'col-md-3 control-label')); ?> 						
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php /*echo form_checkbox(array(
											'name'=>'activate_sale_by_serial',
											'id'=>'activate_sale_by_serial',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('activate_sale_by_serial')));
										*/?>
										<label for="activate_sale_by_serial">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>-->
                        <div class="form-group">
						<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="Mostar UPC/EAN/ISBN en el ticket  ">Mostrar UPC/EAN/ISBN en el ticket</a>'.':', 'show_number_item', array('class'=>'col-md-3 control-label')); ?> 						
						<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_number_item',
											'id'=>'show_number_item',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_number_item')));
										?>
										<label for="show_number_item">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
                        
                        <!-- Enviar factura como txt-->
                        <div class="form-group">	
                            <?php echo form_label(lang('config_send_txt_invoice').':', 'send_txt_invoice',array('class'=>'col-md-3 control-label')); ?>
                            <div class="col-md-2">
                                <div class="md-checkbox-inline">
                                    <div class="md-checkbox">
                                        <?php echo form_checkbox(array(
                                            'name'=>'send_txt_invoice',
                                            'id'=>'send_txt_invoice',
                                            'value'=>'send_txt_invoice',
                                            'checked'=>$this->config->item('send_txt_invoice')));
                                        ?>
                                        <label for="send_txt_invoice">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        
                            <div id="container_ftp_hostname">
                                <?php echo form_label(lang('config_ftp_hostname').':', 'value_max_cash_flow',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'value_ftp_hostname"',
                                        'id'=>'value_ftp_hostname"',
                                        'class'=>'form-control',
                                        'size'=>'value_ftp_hostname"',
                                        'value'=> $this->config->item('ftp_hostname') ? $this->config->item('ftp_hostname') : "" ));
                                    ?> 
                                </div>
                            </div>
                            
                            <div id="container_ftp_username">
                                <?php echo form_label(lang('config_ftp_username').':', 'value_max_cash_flow',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'value_ftp_username',
                                        'id'=>'value_ftp_username',
                                        'class'=>'form-control',
                                        'size'=>'value_ftp_username',
                                        'value'=> $this->config->item('ftp_username') ? $this->config->item('ftp_username') : "" ));
                                    ?> 
                                </div>
                            </div>
                            
                            <div id="container_ftp_password">
                                <?php echo form_label(lang('config_ftp_password').':', 'value_max_cash_flow',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'value_ftp_password',
                                        'id'=>'value_ftp_password',
                                        'class'=>'form-control',
                                        'size'=>'value_ftp_password',
                                        'value'=> $this->config->item('ftp_password') ? $this->config->item('ftp_password') : "" ));
                                    ?> 
                                </div>
                            </div>
                            
                            <div id="container_ftp_route">
                                <?php echo form_label(lang('config_ftp_route').':', 'value_max_cash_flow',array('class'=>'col-md-3 control-label')); ?>
                                <div class="col-md-4">
                                    <?php echo form_input(array(
                                        'name'=>'value_ftp_route',
                                        'id'=>'value_ftp_route',
                                        'class'=>'form-control',
                                        'size'=>'value_ftp_route',
                                        'value'=> $this->config->item('ftp_route') ? $this->config->item('ftp_route') : "" ));
                                    ?> 
                                </div>
                            </div>
                            
                        </div>
                        
					</div>
				</div>
			</div>
			<!-- Suspended Sales/Layaways -->
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_sales_receipt_help') ?>">
								<a class="help_config"><?php echo lang("config_suspended_sales_layaways_info"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_suspended_sales_layaways_info"><?php echo lang("config_more_options") ?></a></span>
						</div>
						<div class="tools">
							<a id="icon_suspended_sales_layaways_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="suspended_sales_layaways_info" class="portlet-body form">	
						<div class="form-group">	
							<?php echo form_label(lang('sales_hide_layaways_sales_in_reports').':', 'hide_layaways_sales_in_reports',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_layaways_sales_in_reports',
											'id'=>'hide_layaways_sales_in_reports',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_layaways_sales_in_reports')));
										?>
										<label for="hide_layaways_sales_in_reports">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_hide_store_account_payments_in_reports').':', 'hide_store_account_payments_in_reports',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_store_account_payments_in_reports',
											'id'=>'hide_store_account_payments_in_reports',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_store_account_payments_in_reports')));
										?>
										<label for="hide_store_account_payments_in_reports">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_hide_store_account_payments_from_report_totals').':', 'hide_store_account_payments_from_report_totals',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_store_account_payments_from_report_totals',
											'id'=>'hide_store_account_payments_from_report_totals',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_store_account_payments_from_report_totals')));
										?>
										<label for="hide_store_account_payments_from_report_totals">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_change_sale_date_when_suspending').':', 'change_sale_date_when_suspending',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'change_sale_date_when_suspending',
											'id'=>'change_sale_date_when_suspending',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('change_sale_date_when_suspending')));
										?>
										<label for="change_sale_date_when_suspending">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("Generate_simplified_order_help").'">'.lang('Generate_simplified_order').'</a>'.':', 'Generate_simplified_order', array('class'=>'col-md-3 control-label')); ?> 						
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'Generate_simplified_order',
											'id'=>'Generate_simplified_order',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('Generate_simplified_order')));
										?>
										<label for="Generate_simplified_order">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'."Reproducir sonidno despues de crear una oreden de pedido".'">'."Reproducir sonido cuando se cree una nueva orden".'</a>'.':', 'reproducrir_sonido_orden', array('class'=>'col-md-3 control-label')); ?> 						
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'reproducrir_sonido_orden',
											'id'=>'reproducrir_sonido_orden',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('reproducrir_sonido_orden')));
										?>
										<label for="reproducrir_sonido_orden">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_change_sale_date_when_completing_suspended_sale').':', 'change_sale_date_when_completing_suspended_sale',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'change_sale_date_when_completing_suspended_sale',
											'id'=>'change_sale_date_when_completing_suspended_sale',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('change_sale_date_when_completing_suspended_sale')));
										?>
										<label for="change_sale_date_when_completing_suspended_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_show_receipt_after_suspending_sale').':', 'show_receipt_after_suspending_sale',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_receipt_after_suspending_sale',
											'id'=>'show_receipt_after_suspending_sale',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_receipt_after_suspending_sale')));
										?>
										<label for="show_receipt_after_suspending_sale">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_disable_subtraction_of_giftcard_amount_from_sales').':', 'disable_subtraction_of_giftcard_amount_from_sales',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'disable_subtraction_of_giftcard_amount_from_sales',
											'id'=>'disable_subtraction_of_giftcard_amount_from_sales',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('disable_subtraction_of_giftcard_amount_from_sales')));
										?>
										<label for="disable_subtraction_of_giftcard_amount_from_sales">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Interface Personalization -->
            <div class="col-md-12">
	            <div class="portlet light">
	                <div class="portlet-title">
	                    <div class="caption">
	                        <i class="icon-speech"></i>
	                         <span class="caption-subject bold uppercase tooltips"  data-placement="right"  title="<?php  echo lang('config_interface_personalization_help')?>"> 
	                        	<a class="help_config"><?php echo lang("config_interface_personalization"); ?></a>
	                        </span>
	                        <span class="caption-helper"><a class="option_interface_personalization"><?php echo lang("config_more_options") ?></a></span>
	                    </div>

	                    <div class="tools">
	                        <a id="icon_interface_personalization" href="" class="expand" data-original-title="" title=""></a>							
	                        <a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
	                    </div>
	                </div>	                
                	<div id="interface_personalization" class="portlet-body form">
                		<div class="row">
                			<!-- Sales -->
                			<div class="col-lg-4 col-md-12">
		                		<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title font-green-seagreen"><strong><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo lang('module_sales')?></strong></h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_num_item').':', 'Show sales number item',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_num_item', 
									                                'id'=>'show_sales_num_item', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_num_item')));
									                            ?>
									                            <label for="show_sales_num_item">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_inventory').':', 'Show sales inventory',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_inventory', 
									                                'id'=>'show_sales_inventory', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_inventory')));
									                            ?>
									                            <label for="show_sales_inventory">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_price_without_tax').':', 'Show sales price without tax',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_price_without_tax', 
									                                'id'=>'show_sales_price_without_tax', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_price_without_tax')));
									                            ?>
									                            <label for="show_sales_price_without_tax">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>											
					                    
											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
							                    <div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_price_iva').':', '',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_price_iva', 
									                                'id'=>'show_sales_price_iva', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_price_iva')));
									                            ?>
									                            <label for="show_sales_price_iva">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
							            	</div>

							            	<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_discount').':', 'Show sales discount',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_discount', 
									                                'id'=>'show_sales_discount', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_discount')));
									                            ?>
									                            <label for="show_sales_discount">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_sales_description').':', 'Show sales description',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_sales_description', 
									                                'id'=>'show_sales_description', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_sales_description')));
									                            ?>
									                            <label for="show_sales_description">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

										</div>
									</div>
								</div>
							</div>

							<!-- Receivings -->
							<div class="col-lg-4 col-md-12">
		                		<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title font-green-seagreen"><strong><i class="fa fa-cloud-download"></i>&nbsp;<?php echo lang('module_receivings')?></strong></h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_num_item').':', 'Show Receivings number item',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_num_item', 
									                                'id'=>'show_receivings_num_item', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_num_item')));
									                            ?>
									                            <label for="show_receivings_num_item">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_cost_without_tax').':', 'Show Receivings cost without tax',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_cost_without_tax', 
									                                'id'=>'show_receivings_cost_without_tax', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_cost_without_tax')));
									                            ?>
									                            <label for="show_receivings_cost_without_tax">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>
						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label('Precio de Venta:', 'Show Receivings cost without tax',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_price_sales', 
									                                'id'=>'show_receivings_price_sales', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_price_sales')));
									                            ?>
									                            <label for="show_receivings_price_sales">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_cost_iva').':', 'Show Receivings cost iva',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_cost_iva', 
									                                'id'=>'show_receivings_cost_iva', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_cost_iva')));
									                            ?>
									                            <label for="show_receivings_cost_iva">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_discount').':', 'Show Receivings discount',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_discount', 
									                                'id'=>'show_receivings_discount', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_discount')));
									                            ?>
									                            <label for="show_receivings_discount">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_inventory').':', 'Show Receivings inventory',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_inventory', 
									                                'id'=>'show_receivings_inventory', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_inventory')));
									                            ?>
									                            <label for="show_receivings_inventory">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_cost_transport').':', 'Show Receivings transport',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_cost_transport', 
									                                'id'=>'show_receivings_cost_transport', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_cost_transport')));
									                            ?>
									                            <label for="show_receivings_cost_transport">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_receivings_description').':', 'Show Receivings description',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_receivings_description', 
									                                'id'=>'show_receivings_description', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_receivings_description')));
									                            ?>
									                            <label for="show_receivings_description">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>
										</div>
									</div>
								</div>
							</div>

							<!-- Inventory -->
							<div class="col-lg-4 col-md-12">
		                		<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title font-green-seagreen"><strong><i class="fa fa-table"></i>&nbsp;<?php echo lang('module_items')?></strong></h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_isbn').':', 'Show Inventory UPC/ISBN',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_isbn', 
									                                'id'=>'show_inventory_isbn', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_isbn')));
									                            ?>
									                            <label for="show_inventory_isbn">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_image').':', 'Show Inventory image',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_image', 
									                                'id'=>'show_inventory_image', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_image')));
									                            ?>
									                            <label for="show_inventory_image">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_size').':', 'Show Inventory size',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_size', 
									                                'id'=>'show_inventory_size', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_size')));
									                            ?>
									                            <label for="show_inventory_size">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_model').':', 'Show Inventory model',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_model', 
									                                'id'=>'show_inventory_model', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_model')));
									                            ?>
									                            <label for="show_inventory_model">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_colour').':', 'Show Inventory colour',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_colour', 
									                                'id'=>'show_inventory_colour', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_colour')));
									                            ?>
									                            <label for="show_inventory_colour">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

						                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">	
							                        <?php echo form_label(lang('config_show_inventory_brand').':', 'Show Inventory brand',array('class'=>'col-md-10 col-sm-10 control-label ')); ?>
							                        <div class="col-md-2 col-sm-2">
								                        <div class="md-checkbox-inline">
									                        <div class="md-checkbox">		
									                            <?php echo form_checkbox(array(
									                                'name'=>'show_inventory_brand', 
									                                'id'=>'show_inventory_brand', 
									                                'value'=>'1', 
									                                'class'=>'md-check', 
									                                'checked'=>$this->config->item('show_inventory_brand')));
									                            ?>
									                            <label for="show_inventory_brand">
									                                <span></span>
									                                <span class="check"></span>
									                                <span class="box"></span>
									                            </label>
									                        </div>									
								                        </div>							
							                        </div>
							                    </div>
						                    </div>

										</div>
									</div>
								</div>
							</div>

	                    </div>
               		</div>
            	</div>
            </div>
            <!--END Interface Personalization -->

			<!-- Application Settings -->
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_application_settings_help')?>">
								<a class="help_config"><?php echo lang("config_application_settings_info"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_application_settings_info"><?php echo lang("config_more_options") ?></a></span>
						</div>
						<div class="tools">
							<a id="icon_application_settings_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="application_settings_info" class="portlet-body form">
						<!-- 
						<div class="form-group">	
							<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('language', array(
									'english' => 'English',
									'indonesia' => 'Indonesia',
									'spanish' => 'Spanish', 
									'french' => 'French',
									'italian' => 'Italian',
									'dutch' => 'Dutch',
									'portugues' => 'Portugues',
	                       			'arabic' => 'Arabic'),
									$this->Appconfig->get_raw_language_value(), 'class="bs-select form-control"');
								?>
							</div>
						</div>
						-->						
						
						<div class="form-group">	
							<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<select class="bs-select form-control" data-show-subtext="true" name="language">	
								<?php $lenguge=$this->Appconfig->get_raw_language_value();?>								
		                            <option data-icon="flagstrap-icon flagstrap-ar" value="spanish_argentina" <?php if($lenguge=="spanish_argentina") echo "selected";?>>Argentina - Español</option>                                   
		                            <option data-icon="flagstrap-icon flagstrap-bo" value="spanish_bolivia" <?php if($lenguge=="spanish_bolivia") echo "selected";?>>Bolivia - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-br" value="portugues_brasil" <?php if($lenguge=="portugues_brasil") echo "selected";?>>Brasil - Portugues</option>
		                            <option data-icon="flagstrap-icon flagstrap-cl" value="spanish_chile" <?php if($lenguge=="spanish_chile") echo "selected";?>>Chile - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-co" value="spanish" <?php if($lenguge=="spanish") echo "selected";?>>Colombia - Español</option>  
		                            <option data-icon="flagstrap-icon flagstrap-cr" value="spanish_costarica" <?php if($lenguge=="spanish_costarica") echo "selected";?>>Costa Rica - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-ec" value="spanish_ecuador" <?php if($lenguge=="spanish_ecuador") echo "selected";?>>Ecuador - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-sv" value="spanish_elsalvador" <?php if($lenguge=="spanish_elsalvador") echo "selected";?>>El Salvador - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-es" value="spanish_spain" <?php if($lenguge=="spanish_spain") echo "selected";?>>España - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-us" value="english" <?php if($lenguge=="english") echo "selected";?>>Estados Unidos - Ingles</option>
		                            <option data-icon="flagstrap-icon flagstrap-gt" value="spanish_guatemala" <?php if($lenguge=="spanish_guatemala") echo "selected";?>>Guatemala - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-hn" value="spanish_honduras" <?php if($lenguge=="spanish_honduras") echo "selected";?>>Honduras - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-mx" value="spanish_mexico" <?php if($lenguge=="spanish_mexico") echo "selected";?>>México - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-ni" value="spanish_nicaragua" <?php if($lenguge=="spanish_nicaragua") echo "selected";?>>Nicaragua - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-pa" value="spanish_panama" <?php if($lenguge=="spanish_panama") echo "selected";?>>Panamá - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-py" value="spanish_paraguay" <?php if($lenguge=="spanish_paraguay") echo "selected";?>>Paraguay - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-pe" value="spanish_peru" <?php if($lenguge=="spanish_peru") echo "selected";?>>Perú - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-pr" value="spanish_puertorico" <?php if($lenguge=="spanish_puertorico") echo "selected";?>>Puerto Rico - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-do" value="spanish_republicadominicana" <?php if($lenguge=="spanish_republicadominicana") echo "selected";?>>República Dominicana - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-uy" value="spanish_uruguay" <?php if($lenguge=="spanish_uruguay") echo "selected";?>>Uruguay - Español</option>
		                            <option data-icon="flagstrap-icon flagstrap-ve" value="spanish_venezuela" <?php if($lenguge=="spanish_venezuela") echo "selected";?>>Venezuela - Español</option>
								</select>
							</div>
						</div>						

						<div class="form-group">	
							<?php echo form_label(lang('config_date_format').':', 'date_format',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('date_format', array(
									'middle_endian' => '12/30/2000',
									'little_endian' => '30-12-2000',
									'big_endian' => '2000-12-30'), $this->config->item('date_format'), 'class="bs-select form-control"');
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_time_format').':', 'time_format',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('time_format', array(
									'12_hour'    => '1:00 PM',
									'24_hour'  => '13:00'), 
									$this->config->item('time_format'), 'class="bs-select form-control"');
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_application_settings_store_accounts_help"). '">'.lang('config_customers_store_accounts').'</a>'.':', 'customers_store_accounts',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'customers_store_accounts',
											'id'=>'customers_store_accounts',
											'value'=>'customers_store_accounts',
											'class'=>'md-check',
											'checked'=>$this->config->item('customers_store_accounts')));
										?>
										<label for="customers_store_accounts">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_application_settings_show_image_help"). '">'.lang('config_show_image').'</a>'.':', 'show_image',array('class'=>'col-md-3 control-label ')); ?>
							<div class='col-md-9'>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_image',
											'id'=>'show_image',
											'value'=>'value_point',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_image')));
										?>
										<label for="show_image">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">		
							<?php echo form_label(lang('config_round_value'), 'round_value',array('class'=>'col-md-3 control-label')); ?>
							<div class='col-md-9'>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'round_value',
											'id'=>'round_value',
											'value'=>'round_value',
											'class'=>'md-check',
											'checked'=>$this->config->item('round_value')));
										?>
										<label for="round_value">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_application_settings_system_point_help"). '">'.lang('config_system_point').'</a>'.':', 'system_point',array('class'=>'col-md-3 control-label ')); ?>
							<div class='col-md-9'>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'system_point',
											'id'=>'system_point',
											'value'=>'value_point',
											'class'=>'md-check',
											'checked'=>$this->config->item('system_point')));
										?>
										<label for="system_point">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div id="container_points">
							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_application_settings_show_point_help"). '">'.lang('config_show_point').'</a>'.':', 'show_point',array('class'=>'col-md-3 control-label ')); ?>
								<div class='col-md-9'>
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
												'name'=>'show_point',
												'id'=>'show_point',
												'value'=>'show_point',
												'class'=>'md-check',
												'checked'=>$this->config->item('show_point')));
											?>
											<label for="show_point">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">	
								<?php echo form_label(lang('config_value_point'), 'value_point',array('class'=>'col-md-3 control-label ')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'value_point',
										'id'=>'value_point',
										'class'=>'form-control',
										'size'=>'value_point',
										'value'=>$this->config->item('value_point')));
									?> Ejemplo: Por cada <strong>1000</strong> de dinero generar <strong>1</strong> punto
								</div>
							</div>												
								
							<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("config_categoria_gastos_help"). '">'.lang('config_categoria_gastos').'</a>'.':', 'categoria_gastos',array('class'=>'col-md-3 control-label ')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'categoria_gastos',
										'id'=>'categoria_gastos',
										'class'=>'form-control',
										"placeholder"=>"Ingrese las categorías separadas por coma",
										'size'=>'categoria_gastos',
										'value'=>$this->config->item('categoria_gastos')));
									 
										$categorys="";
										foreach($expense_category as $category){
											$categorys .=$category.", ";
										}
										$categorys.="Ventas, Devolución, Abono a línea de crédito, Apertura de caja, Cierre de caja";
										echo $categorys;
									?>
								</div>
							</div>
							<div class="form-group">	
								<?php echo form_label(lang('config_percent_point'), 'percent_point',array('class'=>'col-md-3 control-label ')); ?>							
								<div class='col-md-9'>
									<div class="input-group">
										<?php echo form_input(array(
											'name'=>'percent_point',
											'id'=>'percent_point',
											'class'=>'form-control',
											'size'=>'percent_point',
											'value'=>$this->config->item('percent_point')));
										?> 
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_thousand_separator'), 'thousand_separator',array('class'=>'col-md-3 control-label ')); ?>
							<div class='col-md-9'>
								<?php echo form_input(array(
									'name'=>'thousand_separator',
									'id'=>'thousand_separator',
									'class'=>'form-control',
									'size'=>'thousand_separator',
									'value'=>$this->config->item('thousand_separator')));
								?> 
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_remove_decimals'), 'remove_decimals',array('class'=>'col-md-3 control-label ')); ?>
							<div class='col-md-9'>
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'remove_decimals',
											'id'=>'remove_decimals',
											'value'=>'remove_decimals',
											'class'=>'md-check',
											'checked'=>$this->config->item('remove_decimals')));
										?>
										<label for="remove_decimals">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_decimal_separator'), 'decimal_separator',array('class'=>'col-md-3 control-label ')); ?>
							<div class='col-md-9'>
								<?php echo form_input(array(
									'name'=>'decimal_separator',
									'id'=>'decimal_separator',
									'class'=>'form-control',
									'size'=>'decimal_separator',
									'value'=>$this->config->item('decimal_separator')));
								?> 
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_number_of_items_per_page').':', 'number_of_items_per_page',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('number_of_items_per_page', array(																												
									'20'=>'20',
									'50'=>'50',
									'100'=>'100',
									'200'=>'200',
									'500'=>'500'), 
								 	$this->config->item('number_of_items_per_page') ? $this->config->item('number_of_items_per_page') : '20', 'class="bs-select form-control"');
								?>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_sip_account'), 'sip_account',array('class'=>'col-md-3 control-label')); ?>
							<div class='col-md-9'>
								<?php echo form_input(array(
									'name'=>'sip_account',
									'id'=>'sip_account',
									'class'=>'form-control',
									'size'=>'sip_account',
									'value'=>$this->config->item('sip_account')));
								?> 
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_sip_password'), 'sip_password',array('class'=>'col-md-3 control-label')); ?>
							<div class='col-md-9'>
								<?php echo form_input(array(
									'name'=>'sip_password',
									'id'=>'sip_password',
									'class'=>'form-control',
									'size'=>'sip_password',
									'value'=>$this->config->item('sip_password')));
								?> 
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_hide_dashboard_statistics').':', 'hide_dashboard_statistics',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_dashboard_statistics',
											'id'=>'hide_dashboard_statistics',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('hide_dashboard_statistics')));
										?>
										<label for="hide_dashboard_statistics">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<!--<div class="form-group">	
							<?php echo form_label(lang('config_hide_support_chat').':', 'hide_support_chat',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_support_chat',
											'id'=>'hide_support_chat',
											'value'=>'hide_support_chat',
											'checked'=>$this->config->item('hide_support_chat')));
										?>
										<label for="hide_support_chat">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>-->
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options tooltips"  data-placement="left"  title="Activa las ventas sin conexión a internet para todas las tiendas:">Activar ventas OffLine</a>'.':', 'offline_sales',array('class'=>'col-md-3 control-label required')); ?>

							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'offline_sales',
											'id'=>'offline_sales',
											'value'=>1,
											'checked'=>$this->config->item('offline_sales')));
										?>
										<label for="offline_sales">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_active_keyboard').':', 'active_keyboard',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'active_keyboard',
											'id'=>'active_keyboard',
											'value'=>'active_keyboard',
											'checked'=>$this->config->item('active_keyboard')));
										?>
										<label for="active_keyboard">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_hide_description').':', 'hide_hide_description',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'hide_description',
											'id'=>'hide_description',
											'value'=>'hide_description',
											'checked'=>$this->config->item('hide_description')));
										?>
										<label for="hide_description">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">	
							<?php echo form_label(lang('config_in_suppliers').':', 'in_suppliers',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-8">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'in_suppliers',
											'id'=>'in_suppliers',
											'value'=>'in_suppliers',
											'checked'=>$this->config->item('in_suppliers')));
										?>
										<label for="in_suppliers">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>		

						<!-- RESTRINGIR MONTO EN CAJA -->
						<div class="form-group">	
							<?php echo form_label(lang('config_limit_cash_flow').':', 'limit_cash_flow',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-2">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'limit_cash_flow',
											'id'=>'limit_cash_flow',
											'value'=>'limit_cash_flow',
											'checked'=>$this->config->item('limit_cash_flow')));
										?>
										<label for="limit_cash_flow">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>

							<div id="container_limit_cash_flow">
								<?php echo form_label(lang('config_value_max_cash_flow').':', 'value_max_cash_flow',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-4">
									<?php echo form_input(array(
									'name'=>'value_max_cash_flow',
									'id'=>'value_max_cash_flow',
									'class'=>'form-control',
									'size'=>'value_max_cash_flow',
									'value'=>$this->config->item('value_max_cash_flow')));
								?> 
								</div>
							</div>
						</div>					
						
						<div class="form-group">	
							<?php echo form_label(lang('config_legacy_detailed_report_export').':', 'legacy_detailed_report_export',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'legacy_detailed_report_export',
											'id'=>'legacy_detailed_report_export',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('legacy_detailed_report_export')));
										?>
										<label for="legacy_detailed_report_export">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">	
							<?php echo form_label(lang('common_resolution').':', 'resolution',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php echo form_textarea(array(
									'name'=>'resolution',
									'id'=>'resolution',
									'class'=>'form-textarea form-control',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$this->config->item('resolution')));
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('common_return_policy').':', 'return_policy',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_textarea(array(
									'name'=>'return_policy',
									'id'=>'return_policy',
									'class'=>'form-textarea form-control',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$this->config->item('return_policy')));
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("show_return_policy_credit_help").'">'.lang('show_return_policy_credit').'</a>'.':', 'show_return_policy_credit', array('class'=>'col-md-3 control-label')); ?> 						
						
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'show_return_policy_credit',
											'id'=>'show_return_policy_credit',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('show_return_policy_credit')));
										?>
										<label for="show_return_policy_credit">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
							
					    </div>
						<div class="form-group">	
							<?php echo form_label(lang('common_return_policy').':', 'return_policy',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_textarea(array(
									'name'=>'return_policy_credit',
									'id'=>'return_policy_credit',
									'class'=>'form-textarea form-control',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$this->config->item('return_policy_credit')));
								?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_spreadsheet_format').':', 'spreadsheet_format',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('spreadsheet_format', array(
								lang('config_csv') => lang('config_csv'), 
								lang('config_xlsx') => lang('config_xlsx')), 
								$this->config->item('spreadsheet_format'), 'class="bs-select form-control"'); ?>
							</div>
						</div>
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_subcategory_store_help").'">'.lang('config_subcategory_store').'</a>'.':', 'subcategory_of_items', array('class'=>'col-md-3 control-label')); ?> 						
						
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'subcategory_of_items',
											'id'=>'subcategory_of_items',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('subcategory_of_items')));
										?>
										<label for="subcategory_of_items">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-8 ">	
								<?php 
									$extra="";
									$url_video_ver="https://www.youtube.com/watch?v=DJ3UH_TW_S4&t=6s";
									if($this->Appconfig->es_franquicia()){
										$url_video=	$this->Appconfig->get_video("SUBCATEGORÍA DE PRODUCTOS");
										if($url_video!=null){
											$url_video_ver=$url_video;
										}else{
											$extra=" style='display: none; '";
										}
									}
									$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_subcategoria" >Ver Vídeo</a>';
									echo $a_video;				
								?>
							
									<!--<a class="icon fa fa-youtube-play help_button" id='subcategory_video' rel='0' data-toggle="modal" data-target="#stack9">Ver Vídeo</a>	-->							
							</div>
						</div>
						<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_custom1_subcategory_help").'">'.lang('config_custom1_subcategory').'</a>'.':', 'custom_subcategory1_name', array('class'=>'col-md-3 control-label')); ?> 						

								<div class='col-md-4'>
									<?php echo form_input(array(
										'name'=>'custom_subcategory1_name',
										'id'=>'custom_subcategory1_name',
										'class'=>'form-control',
										'value'=>$this->config->item('custom_subcategory1_name')));
									?> 
								</div>
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="Si activa esta opción Inhabilitar esta subcategoría, no se tendrá en cuenta en facturación">Inhabilitar</a>'.':', 'inhabilitar_subcategory1', array('class'=>'col-md-2 control-label')); ?> 						
								<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'inhabilitar_subcategory1',
											'id'=>'inhabilitar_subcategory1',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('inhabilitar_subcategory1')));
										?>
										<label for="inhabilitar_subcategory1">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div>

							</div>
							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_custom1_subcategory_help").'">'.lang('config_custom2_subcategory').'</a>'.':', 'custom_subcategory2_name', array('class'=>'col-md-3 control-label')); ?> 						
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom_subcategory2_name',
										'id'=>'custom_subcategory2_name',
										'class'=>'form-control',
										'value'=>$this->config->item('custom_subcategory2_name')));
									?> 
								</div>
							</div>
							<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="Cantidad máxima de subcategoría por producto">Catidad máxima de subcategoría</a>'.':', 'quantity_subcategory_of_items', array('class'=>'col-md-3 control-label')); ?> 						
								<div class='col-md-9'>
									<?php echo form_dropdown('quantity_subcategory_of_items',
										array(
										"1" => 1,
										"2" => 2, 
										"5" => 5,
										"10" => 10,
										"12" => 12,
										"15" =>15,
										"30"=>30,
										"40"=>40,
										"50"=>50,
										"100"=>100),
										$this->config->item('quantity_subcategory_of_items')?$this->config->item('quantity_subcategory_of_items'):5, 'class="bs-select form-control"'); 
									?>
							</div>						
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_price_tiers').':', 'tiers',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-9 table-responsive">
								<table id="price_tiers" class="table">
									<thead>
										<tr>
											<th><?php echo lang('items_tier_name'); ?></th>
											<th><?php echo lang('common_delete'); ?></th>
										</tr>
									</thead>								
									<tbody>
										<?php foreach($tiers->result() as $tier) { ?>
											<tr><td><input type="text" class="form-control"  name="tiers_to_edit[<?php echo $tier->id; ?>]" value="<?php echo H($tier->name); ?>" /></td><td>
											<?php if ($this->Employee->has_module_action_permission('items', 'delete', $this->Employee->get_logged_in_employee_info()->person_id) || $this->Employee->has_module_action_permission('item_kits', 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
											<a class="btn btn-block delete_tier" href="javascript:void(0);" data-tier-id='<?php echo $tier->id; ?>'><?php echo lang('common_delete'); ?></a>
											<?php }else { ?>
												&nbsp;
											<?php } ?>
											</td><tr>
										<?php } ?>
									</tbody>
								</table>							
								<a href="javascript:void(0);" id="add_tier" class="btn btn-circle default btn-xs"><?php echo lang('config_add_tier'); ?></a>
								<br/><br/>
							</div>
						</div>
							
						<div class="form-group">	
							<?php echo form_label(lang('config_denomination').':', 'currency',array('class'=>'col-md-3 control-label ')); ?>
							<div class="col-md-9 table-responsive">
								<table id="denomination_currency" class="table">
									<thead>
										<tr>
											<th><?php echo lang('config_value_denomination'); ?></th>
											<th><?php echo lang('config_type_denomination'); ?></th>
											<th><?php echo lang('common_delete'); ?></th>
										</tr>
									</thead>
									<tbody>									
										<?php 
											$i=0;
									    	foreach($currency->result() as $currency) { ?>
												<tr>
													<td>
														<input type="text" class="form-control" name="currency_to_edit[<?php echo $currency->id; ?>]" value="<?php echo H($currency->name); ?>" />
													</td>
			                                  		<td>                                 
			                                			<?php echo form_dropdown("type_currency[$currency->id]", array(
		                                				lang('config_currency1') => lang('config_currency1'), 
		                                				lang('config_currency2') => lang('config_currency2')), 
		                                				$this->Denomination_currency->get_info($currency->id)->type_currency, 'class="bs-select form-control"'); ?>
							                    	</td> 
							                    	<td> 
							          					<?php if ($this->Employee->has_module_action_permission('items', 'delete', $this->Employee->get_logged_in_employee_info()->person_id) || $this->Employee->has_module_action_permission('item_kits', 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
															<a class="btn btn-block delete_currency" href="javascript:void(0);" data-currency-id='<?php echo $currency->id; ?>'><?php echo lang('common_delete'); ?></a>
												
														<?php }else { ?>
															&nbsp; 
														<?php } $i++; ?>
													</td>
												<tr>
	                                   	<?php } ?>										 
									</tbody>
								</table>
								<a href="javascript:void(0);" id="add_currency" class="btn btn-circle default btn-xs"><?php echo lang('config_add_currency'); ?></a>
								<br/><br/>
							</div>
							
							<div class="form-group">	
								<?php echo form_label('Campo personalizado 1', 'custom_field',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom1_name',
										'id'=>'custom_field',
										'class'=>'form-control',
										'size'=>'custom_field',
										'value'=>$this->config->item('custom1_name')));
									?> 
								</div>
							</div>
							
							<div class="form-group">	
								<?php echo form_label('Campo personalizado 2', 'custom_field',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom2_name',
										'id'=>'custom_field',
										'class'=>'form-control',
										'size'=>'custom_field',
										'value'=>$this->config->item('custom2_name')));
									?> 
								</div>
							</div>
							
							<div class="form-group">	
								<?php echo form_label('Campo personalizado 3', 'custom_field',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom3_name',
										'id'=>'custom_field',
										'class'=>'form-control',
										'size'=>'custom_field',
										'value'=>$this->config->item('custom3_name')));
									?> 
								</div>
                                                        </div>
							
						</div>


						<div class="form-actions right">
							<?php echo form_button(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'type' => 'submit',	
								'class'=>'btn btn-primary pull-right'),
								lang('common_submit')); 
							?>							   
						</div>	
					</div>
				</div>
			</div> 





<?php ///////////////////////Panel de config de Servicio Tecnico ?>
			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_application_settings_help_st')?>">
								<a class="help_config"><?php echo lang("config_application_settings_info_st"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_application_settings_info_st"><?php echo lang("config_more_options") ?></a></span>
						</div>
						<div class="tools">
							<a id="icon_application_settings_info_st" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="application_settings_info_st" class="portlet-body form">
						<!-- 
						<div class="form-group">	
							<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_dropdown('language', array(
									'english' => 'English',
									'indonesia' => 'Indonesia',
									'spanish' => 'Spanish', 
									'french' => 'French',
									'italian' => 'Italian',
									'dutch' => 'Dutch',
									'portugues' => 'Portugues',
	                       			'arabic' => 'Arabic'),
									$this->Appconfig->get_raw_language_value(), 'class="bs-select form-control"');
								?>
							</div>
						</div>
						-->				
 
						<div class="form-group">	
							<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("config_st_correo_help").'">'.lang('config_st_correo').'</a>'.':', 'st_correo_of_items', array('class'=>'col-md-3 control-label')); ?> 						
						
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'st_correo_of_items',
											'id'=>'st_correo_of_items',
											'value'=>'1',
											'class'=>'md-check',
											'checked'=>$this->config->item('st_correo_of_items')));
										?>
										<label for="st_correo_of_items">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										</label>
									</div>
								</div>
							</div> 
						</div> 
							<?PHP //////////////CONFG. TIPOS DE SERVICIOS Y FALLAS ?>
                                                    <div style="margin: 7px 7px 20px 7px;border: 1px solid #DDDDDD;padding: 5px;">
                                                        <div style="border-bottom: 1px solid #DDDDDD;padding: 9px;background: #FAFAFA;margin-bottom: 15px;font-weight: 700;"><?php echo lang('config_company_etqservicio'); ?></div>
							<div class="form-group">	
                                                            <?php echo form_label(lang('config_company_tservicio').':', 'date_format',array('class'=>'col-md-3 control-label')); ?>
                                                            <div class="col-md-9">
                                                                <div class="dropdown"> 
                                                                <a class="dropdown-toggle text-left" data-toggle="dropdown" href="#">
                                                                    <button class="btn btn-block text-left" style="text-align: left;color: #333333;">
                                                                        <div class="col-md-11">
                                                                        <?php echo lang('config_company_labeltservicio'); ?> 
                                                                        </div>
                                                                        <div class="col-md-1" style="text-align: right;">
                                                                        <i class="icon icon-arrow-down"></i>
                                                                        </div>
                                                                    </button>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                <?php  
                                                                foreach($tservice->result() as $tservice) { 
                                                                    ?> <li><a><?php echo $tservice->tservicios; ?></a></li><?php                                                                    
                                                                } ?>
                                                                </ul>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-3"></div>
                                                            <div class="col-md-9">
                                                                <a href="javascript:void(0);"  onclick="controler('<?php echo site_url() ?>/config/tservicios/','hever','ventanaVer','');" class="btn btn-circle default btn-xs"><?php echo lang('config_company_addtservicio'); ?></a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">	
                                                            <?php echo form_label(lang('config_company_tfallas').':', 'date_format',array('class'=>'col-md-3 control-label')); ?>
                                                            <div class="col-md-9">
                                                                <div class="dropdown">
                                                                <a class="dropdown-toggle text-left" data-toggle="dropdown" href="#">
                                                                    <button class="btn btn-block text-left" style="text-align: left;color: #333333;">
                                                                        <div class="col-md-11">
                                                                        <?php echo lang('config_company_labeltfallas'); ?>
                                                                        </div>
                                                                        <div class="col-md-1" style="text-align: right;">
                                                                        <i class="icon icon-arrow-down"></i>
                                                                        </div>
                                                                    </button>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                <?php  
                                                                foreach($tfallas->result() as $tfallas) { 
                                                                    ?> <li><a><?php echo $tfallas->tfallas; ?></a></li><?php                                                                    
                                                                } ?>
                                                                </ul>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-md-3"></div>
                                                            <div class="col-md-9">
                                                            <a href="javascript:void(0);" onclick="controler('<?php echo site_url() ?>/config/tfallas/','hever','ventanaVer','');" class="btn btn-circle default btn-xs"><?php echo lang('config_company_addtfallas'); ?></a>
                                                            </div>
                                                        </div>
                                                </div>
                                                    <?PHP ///////// ?>
                                                <?PHP //////////////CONFG. UBICACION DEL EQUIPO ?>
                                                    <div style="margin: 7px 7px 20px 7px;border: 1px solid #DDDDDD;padding: 5px;">
                                                        <div style="border-bottom: 1px solid #DDDDDD;padding: 9px;background: #FAFAFA;margin-bottom: 15px;font-weight: 700;"><?php echo lang('config_company_ubi_equipo_tuti_conf'); ?></div>
							<div class="form-group">	
                                                            <?php echo form_label(lang('config_company_addlabelubiequipo').':', 'date_format',array('class'=>'col-md-3 control-label')); ?>
                                                            <div class="col-md-9">
                                                                <div class="dropdown"> 
                                                                <a class="dropdown-toggle text-left" data-toggle="dropdown" href="#">
                                                                    <button class="btn btn-block text-left" style="text-align: left;color: #333333;">
                                                                        <div class="col-md-11">
                                                                        <?php echo lang('config_company_addselecubiequipo'); ?> 
                                                                        </div>
                                                                        <div class="col-md-1" style="text-align: right;">
                                                                        <i class="icon icon-arrow-down"></i>
                                                                        </div>
                                                                    </button>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                <?php  
                                                                foreach($tubi->result() as $tubi) { 
                                                                    ?> <li><a><?php echo $tubi->ubicacion; ?></a></li><?php                                                                    
                                                                } ?>
                                                                </ul>
                                                                </div> 
                                                            </div>
                                                        </div> 
                                                        
                                                        <div class="form-group">
                                                            <div class="col-md-3"></div>
                                                            <div class="col-md-9">
                                                            <a href="javascript:void(0);" onclick="controler('<?php echo site_url() ?>/config/ubica_equipo/','hever','ventanaVer','');" class="btn btn-circle default btn-xs"><?php echo lang('config_company_addubiequipo'); ?></a>
                                                            </div>
                                                        </div>
                                                </div>
                                                    <?PHP ///////// ?>
							<div class="form-group">	
								<?php echo form_label('Campo personalizado 1', 'custom1_support_name',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom1_support_name',
										'id'=>'custom1_support_name',
										'class'=>'form-control',
										'value'=>$this->config->item('custom1_support_name')));
									?> 
								</div>
							</div>
							<div class="form-group" >	
								<?php echo form_label('Campo personalizado 2', 'custom2_support_name',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'custom2_support_name',
										'id'=>'custom2_support_name',
										'class'=>'form-control',
										'value'=>$this->config->item('custom2_support_name')));
									?> 
								</div>
							</div>
							<div class="form-group" >	
								<?php echo form_label("Número inicial de ordenes para soportes", 'order_star',array('class'=>'col-md-3 control-label')); ?>
								<div class='col-md-9'>
									<?php echo form_input(array(
										'name'=>'order_star',
										'id'=>'order_star',
										"type"=>"number",
										'class'=>'form-control',
										'value'=>$this->config->item('order_star')==false? 0:$this->config->item('order_star')));
									?> 
								</div>
							</div>
							<div class="form-group">	
							<?php echo form_label(lang('common_return_policy').' :', 'return_policy_support',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-9">
								<?php echo form_textarea(array(
									'name'=>'return_policy_support',
									'id'=>'return_policy_support',
									'class'=>'form-textarea form-control',
									'rows'=>'4',
									'cols'=>'30',
									'value'=>$this->config->item('return_policy_support')));
								?>
							</div>
						</div>
							
						


						<div class="form-actions right">
							<?php echo form_button(array(
								'name'=>'submitf',
								'id'=>'submitf',
								'type' => 'submit',	
								'class'=>'btn btn-primary pull-right'),
								lang('common_submit')); 
							?>							   
						</div>	
					</div>
				</div>
			</div>                                                

                        <?php ///////////////////////FIN Panel de config de Servicio Tecnico ?>











			<div class="col-md-12">
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-speech"></i>
							<span class="caption-subject bold uppercase tooltips" data-placement="right"  title="<?php  echo lang('config_application_divice_help')?>">
								<a class="help_config"><?php echo lang("config_application_divice"); ?></a>
							</span>
							<span class="caption-helper"><a class="option_application_divice"><?php echo lang("config_more_options") ?></a></span>
						</div>
						<div class="tools">
							<a id="icon_application_divice_info" href="" class="expand" data-original-title="" title=""></a>							
							<a href="javascript:;" class="fullscreen" data-original-title="" title=""></a>								
						</div>
					</div>
					<div id="application_divices" class="portlet-body form">

						<div class="form-group">	
							<?php echo form_label(lang('config_activar_casa_cambio').':', 'activar_casa_cambio',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'activar_casa_cambio',
											'id'=>'activar_casa_cambio',
											'value'=>'1',
											'checked'=>$this->config->item('activar_casa_cambio')));
										?>
										<label for="activar_casa_cambio">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-8 ">	
								<?php 
									$extra="";
									$url_video_ver="https://www.youtube.com/watch?v=OkF8D_69e1E";
									if($this->Appconfig->es_franquicia()){
										$url_video=	$this->Appconfig->get_video("CASA DE CAMBIO");
										if($url_video!=null){
											$url_video_ver=$url_video;
										}else{
											$extra=" style='display: none; '";
										}
									}
									$a_video= '<a href="'.$url_video_ver.'" '.$extra.' target="_blank" style="font-size:12px; margin-left:10px"class="icon fa fa-youtube-play help_button" id="info_subcategoria" >Ver Vídeo</a>';
									echo $a_video;				
								?>
							
									<!--<a class="icon fa fa-youtube-play help_button" id='subcategory_video' rel='0' data-toggle="modal" data-target="#stack9">Ver Vídeo</a>	-->							
							</div>
						</div>	
						<hr>
						<div class="form-group">	
							<?php echo form_label("Activar pago con una segunda maneda".':', 'activar_pago_segunda_moneda',array('class'=>'col-md-3 control-label')); ?>
							<div class="col-md-1">
								<div class="md-checkbox-inline">
									<div class="md-checkbox">
										<?php echo form_checkbox(array(
											'name'=>'activar_pago_segunda_moneda',
											'id'=>'activar_pago_segunda_moneda',
											'value'=>'1',
											'checked'=>$this->config->item('activar_pago_segunda_moneda')));
										?>
										<label for="activar_pago_segunda_moneda">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
										</label>
									</div>
								</div>
							</div>
							<?php echo form_label("Moneda".':', 'moneda',array('class'=>'col-md-1 control-label required')); ?>
							<div class="col-md-2">
								<?php echo form_dropdown('moneda', array(
									'USD' => 'USD',
									'EUR' => 'EUR'), $this->config->item('moneda'), 'class="bs-select form-control" id="moneda"');
								?>
							</div>
							
						</div>
						<div class="form-group">	
							<?php echo form_label(':', 'equivalencia',array('class'=>'col-md-3 control-label ',"id"=>"equivalencia_lab")); ?>
							
							<div class="col-md-3">								
									<?php
									echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ingrese valor",
										'name'=>'equivalencia',
										'id'=>'equivalencia',
										'type'=>'number',
										'value'=> $this->config->item('equivalencia')));
									?>									
							</div>							
						</div>
						<hr>
						<div class="form-group">	
							<?php echo form_label(lang('config_divisa').':', 'divisa',array('class'=>'col-md-3 control-label required')); ?>
							<div class="col-md-3">
								<?php echo form_dropdown('divisa', array(
									'VEF' => 'Bolivar Venezuela',
									'USD' => 'Dólar estadounidense',
									'EUR' => 'EURO'), $this->config->item('divisa'), 'class="bs-select form-control"');
								?>
							</div>
						</div>	
						<div class="form-group">	
							<?php echo form_label('Nombre de tasa 1:', 'nombre_tasa1',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ingrese nombre",
										'name'=>'nombre_tasa[]',
										"readonly"=>"readonly",
										'id'=>'nombre_tasa1',
										"style"=>"font-weight: bold",
									'value'=>"Casa de cambio"/*$rates[0]["name"]*/));
									?>									
							</div>							
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_compra').':', 'tasa_compra1',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php
									echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_compra[]',
										'id'=>'tasa_compra1',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[0]["rate_buy"]));
									?>									
							</div>							
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_venta').':', 'tasa_venta1',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_venta[]',
										'id'=>'tasa_venta1',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[0]["sale_rate"]));
									?>									
							</div>							
						</div>				

						<div class="form-group">	
							<?php echo form_label('Nombre de tasa 2:', 'nombre_tasa2',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ingrese nombre",
										'name'=>'nombre_tasa[]',
										'id'=>'nombre_tasa2',
										"readonly"=>"readonly",
										"style"=>"font-weight: bold",
									'value'=>"Distribuidor"/*$rates[1]["name"]*/));
									?>									
							</div>	
							<?php echo form_label('Ganacia distribuidor:', 'ganancia_distribuidor',array('class'=>'col-md-3 control-label ')); ?>
								<div class="col-md-2">	
									<div class="input-group">							
										<?php echo form_input(array(
											'class'=>'form-control',
											'placeholder' =>"Ingrese ganancia",
											'name'=>'ganancia_distribuidor',
											'id'=>'ganancia_distribuidor',
											"type"=>"Number",
											'value'=>$this->config->item('ganancia_distribuidor')/*$rates[1]["name"]*/));
										?>	
										<span class="input-group-addon">%</span>
									</div>								
								</div>
							</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_compra').':', 'tasa_compra2',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_compra[]',
										'id'=>'tasa_compra2',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[1]["rate_buy"]));
									?>									
							</div>							
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_venta').':', 'tasa_venta2',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_venta[]',
										'id'=>'tasa_venta2',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[1]["sale_rate"]));
									?>									
							</div>							
						</div>	

						<div class="form-group">	
							<?php echo form_label('Nombre de tasa 3:', 'nombre_tasa3',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ingrese nombre",
										'name'=>'nombre_tasa[]',
										'id'=>'nombre_tasa3',
										"readonly"=>"readonly",
										"style"=>"font-weight: bold",
									'value'=>"Bolívares"/*$rates[2]["name"]*/));
									?>									
							</div>							
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_compra').':', 'tasa_compra3',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_compra[]',
										'id'=>'tasa_compra3',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[2]["rate_buy"]));
									?>									
							</div>							
						</div>
						<div class="form-group">	
							<?php echo form_label(lang('config_tasa_venta').':', 'tasa_venta3',array('class'=>'col-md-3 control-label ')); ?>
							
							<div class="col-md-3">								
									<?php echo form_input(array(
										'class'=>'form-control',
										'placeholder' =>"Ejemplo:  0.0005",
										'name'=>'tasa_venta[]',
										'id'=>'tasa_venta3',
										'type'=>'number',
										'size'=>'4',
										'value'=>(double)$rates[2]["sale_rate"]));
									?>									
							</div>							
						</div>					
					</div>										
				</div>
			</div> 





			<!-- Float Button -->
			<button href="javascript:;" class="btn btn-icon-only btn-circle green button-submit tooltips" data-original-title="Guardar cambios">
				<i class="fa fa-check fa-2x-custom"></i>
			</button>
			
		<?php ?>
	</div>
	<!-- END -->


	<script type='text/javascript'>
	<?php if($this->config->item('company_logo')!=0):?>

		$("#company_logo").fileinput({
			initialPreview: [
				"<img src='<?php echo  site_url('app_files/view/'.$this->config->item('company_logo')) ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
			],
			overwriteInitial: true,
			initialCaption: "Imagen"
		});
	<?php endif; ?>
	
		$(document).ready(function()
		{	
			$("#inhabilitar_subcategory1").change( function(){
			
   			if( $(this).is(':checked') ){
			    $('#custom_subcategory1_name').attr('readonly', true);
				$('#custom_subcategory1_name').val("inhabilitado");
				
			   }else{
				$('#custom_subcategory1_name').attr('readonly', false);
				$('#custom_subcategory1_name').val("");
			   }
		});				
			<?php if($this->config->item('hide_video_stack2') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack2').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack2")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack2').css({'display':'none'});

         }
         
     
         });
          $('#closeconfig').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack3').css({'display':'none'});
               $('#maxconfig').removeClass('icon fa fa-youtube-play help_button');
               $('#maxconfig').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });
      
         $('#checkBoxStack2').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video2:$(this).is(':checked') ? '1' : '0',video2:'hide_video_stack2'});
               
         });
      	 $('#closeconfig').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack2').css({'display':'none'});
               $('#maxconfig').removeClass('icon fa fa-youtube-play help_button');
               $('#maxconfig').html("<a href='javascript:;' id='maxconfig' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });

			$(".portlet-body").css("display","none");

			var more_options = "<?php echo lang('config_more_options'); ?>"
			var hide_options = "<?php echo lang('config_hide_options'); ?>"

			$(".option_company_info").click(function() {  
	        	$("#company_info").slideToggle({duration:200}); 
	        	$("#icon_company_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });

		    $(".option_tax_currency_info").click(function() {  
	        	$("#tax_currency_info").slideToggle({duration:200}); 
	        	$("#icon_tax_currency_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });
	            
            $(".option_interface_personalization").click(function() {  
	        	$("#interface_personalization").slideToggle({duration:200}); 
	        	$("#icon_interface_personalization").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });
		    
		    $(".option_sales_receipt_info").click(function() {  
	        	$("#sales_receipt_info").slideToggle({duration:200}); 
	        	$("#icon_sales_receipt_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });

		    $(".option_suspended_sales_layaways_info").click(function() {  
	        	$("#suspended_sales_layaways_info").slideToggle({duration:200}); 
	        	$("#icon_suspended_sales_layaways_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });

		    $(".option_application_settings_info").click(function() {  
	        	$("#application_settings_info").slideToggle({duration:200}); 
	        	$("#icon_application_settings_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });
			$(".option_application_divice").click(function() {  
	        	$("#application_divices").slideToggle({duration:200}); 
	        	$("#icon_application_divice_info").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });
                    
		    $(".option_application_settings_info_st").click(function() {  
	        	$("#application_settings_info_st").slideToggle({duration:200}); 
	        	$("#icon_application_settings_info_st").toggleClass("collapse"); 
	        	$(this).text( $(this).text() == more_options ? hide_options : more_options);          
		    });

		    //
			$(".delete_tier").click(function()
			{
				$("#config_form").append('<input type="hidden" name="tiers_to_delete[]" value="'+$(this).data('tier-id')+'" />');
				$(this).parent().parent().remove();
			});	
			$("#add_tier").click(function()
			{
				$("#price_tiers tbody").append('<tr><td><input type="text" class="form-control" name="tiers_to_add[]" value="" /></td><td>&nbsp;</td></tr>');
			});

			$(".delete_currency").click(function()
			{
				$("#config_form").append('<input type="hidden" name="currency_to_delete[]" value="'+$(this).data('currency-id')+'" />');
				$(this).parent().parent().remove();
			});
		 	$("#add_currency").click(function()
		 	{
				$("#denomination_currency tbody").append('<tr><td><input type="text" class="currency_to_add form-control" name="currency_to_add[]" value="" /></td><td> <select name="type_currenc[]" class="bs-select form-control"><option value="Billete"> Billete</option><option value="Moneda"> Moneda</option></select> </td></tr>');
		 	});
		
			$(".dbOptimize").click(function(event)
			{
				event.preventDefault();
				$('#spin').removeClass('hidden');
				
				$.getJSON($(this).attr('href'), function(response) 
				{
					$('#spin').addClass('hidden');
					alert(response.message);
				});
			});
			
			//Container points
			var s1 = "<?php echo $this->config->item('system_point') ?>";
			//console.log(s1);

			if (s1=="0")
			{
				$("#container_points").addClass('hidden');
			} 
			else if (s1=="1") 
			{
				$("#container_points").show()
			};

			$('#system_point').change(function()
			{
				if ($(this).prop('checked')) 
				{
			        $("#container_points").removeClass('hidden');
			    } 
			    else 
			    {
			        $("#container_points").addClass('hidden');
			    }
			});

			//Container limit_cash_flow
			var s2 = "<?php echo $this->config->item('limit_cash_flow') ?>";
			//console.log(s2);

			if (s2=="0")
			{
				$("#container_limit_cash_flow").addClass('hidden');
			} 
			else if (s2=="1") 
			{
				$("#container_limit_cash_flow").show()
			};

			$('#limit_cash_flow').change(function()
			{
				if ($(this).prop('checked')) 
				{
			        $("#container_limit_cash_flow").removeClass('hidden');
			    } 
			    else 
			    {
			        $("#container_limit_cash_flow").addClass('hidden');
			    }
			});

			//
			var submitting = false;
			$('#config_form').validate({
				submitHandler:function(form)
				{
					if (submitting) return;
					submitting = true;
					$(form).ajaxSubmit({
					success:function(response)
					{
						//Don't let the tiers be double submitted, so we change the name
						$(".tiers_to_add").attr('name', 'tiers_added[]');
						$(".currency_to_add").attr('name', 'currency_added[]');
						if(response.success)
						{
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);						
							$("html, body").animate({ scrollTop: 0 }, "slow");
						}
						else
						{
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
						}
						submitting = false;
					},
					dataType:'json'
				});

				},
				errorClass: "text-danger",
				errorElement: "span",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
				},
				rules: 
				{				
		    		company: "required",
		    		sale_prefix: "required",
					return_policy:
					{
						required: true
					},
					value_point: {
				      	digits: true
				    },			    
				    percent_point: {
				      	digits: true
				    },
				    value_max_cash_flow: {
				    	digits: true
				    }
		   	},
				messages: 
				{
		     		company: <?php echo json_encode(lang('config_company_required')); ?>,
		     		sale_prefix: <?php echo json_encode(lang('config_sale_prefix_required')); ?>,
					return_policy:
					{
						required:<?php echo json_encode(lang('config_return_policy_required')); ?>
					},
					value_point: {			      	
				      	digits: <?php echo json_encode(lang('config_error_value_points')); ?>
				    },	
				    percent_point: {			      	
				      	digits: <?php echo json_encode(lang('config_error_percent_points')); ?>
				    },	
				    value_max_cash_flow: {
				    	digits: <?php echo json_encode(lang('config_error_percent_points')); ?>
				    }
				}
			});
			
		
			$("#calculate_average_cost_price_from_receivings").change(check_calculate_average_cost_price_from_receivings).ready(check_calculate_average_cost_price_from_receivings);

			function check_calculate_average_cost_price_from_receivings()
			{
				if($("#calculate_average_cost_price_from_receivings").prop('checked'))
				{
					$("#average_cost_price_from_receivings_methods").show();
				}
				else
				{
					$("#average_cost_price_from_receivings_methods").hide();
				}
			}

		    $( "#search" ).focus(function() {
		    	if(!$("#icon_company_info").hasClass("collapse"))
		    	{
		    		$("#company_info").slideToggle({duration:200})
	    			$("#icon_company_info").toggleClass("collapse"); 
	    			$(".option_company_info").text( $(".option_company_info").text() == more_options ? hide_options : more_options);
		    	} 
		    	if(!$("#icon_tax_currency_info").hasClass("collapse"))
		    	{
			    	$("#tax_currency_info").slideToggle({duration:200}); 
		        	$("#icon_tax_currency_info").toggleClass("collapse"); 
		        	$(".option_tax_currency_info").text( $(".option_tax_currency_info").text() == more_options ? hide_options : more_options);
 				}
 				if(!$("#icon_interface_personalization").hasClass("collapse"))
		    	{
	 				$("#interface_personalization").slideToggle({duration:200}); 
		        	$("#icon_interface_personalization").toggleClass("collapse"); 
		        	$(".option_interface_personalization").text( $(".option_interface_personalization").text() == more_options ? hide_options : more_options);
		        }
		        if(!$("#icon_sales_receipt_info").hasClass("collapse"))
		    	{
		        	$("#sales_receipt_info").slideToggle({duration:200}); 
		        	$("#icon_sales_receipt_info").toggleClass("collapse"); 
		        	$(".option_sales_receipt_info").text( $(".option_sales_receipt_info").text() == more_options ? hide_options : more_options);
		        }
		        if(!$("#icon_suspended_sales_layaways_info").hasClass("collapse"))
		    	{
		        	$("#suspended_sales_layaways_info").slideToggle({duration:200}); 
	        		$("#icon_suspended_sales_layaways_info").toggleClass("collapse"); 
	        		$(".option_suspended_sales_layaways_info").text( $(".option_suspended_sales_layaways_info").text() == more_options ? hide_options : more_options);
	        	}
	        	if(!$("#icon_application_settings_info").hasClass("collapse"))
		    	{
	        		$("#application_settings_info").slideToggle({duration:200}); 
	        		$("#icon_application_settings_info").toggleClass("collapse"); 
	        		$(".option_application_settings_info").text( $(".option_application_settings_info").text() == more_options ? hide_options : more_options);
	        	}
				

				if(!$("#icon_application_divice_info").hasClass("collapse"))
		    	{
	        		$("#application_divices").slideToggle({duration:200}); 
	        		$("#icon_application_divice_info").toggleClass("collapse"); 
	        		$(".option_application_divice").text( $(".option_application_divice").text() == more_options ? hide_options : more_options);
	        	}
			
			});
			

			$('#search').hideseek({
		 	 	highlight: true,
		 	 	nodata: 'No se encontraron resultados',
		 	 	ignore_accents: true
			});
			$('#moneda').change(function()
			{
				equivalencia();
			});
		    
	    });
		function equivalencia(){
			
			let moneda= $("#moneda").val();
			let currency_symbol= $("#currency_symbol").val();
			let value="1 "+moneda+" equivale a "+currency_symbol+" :";
			$("#equivalencia_lab").html(value);
		}
		equivalencia();

	</script>
<?php $this->load->view("partial/footer"); ?>


