<?php $this->load->view("partial/header"); ?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			<?php if($this->config->item('hide_video_stack6') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack6').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack6")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack6').css({'display':'none'});

         }
         
     
         });
         $('#closestore').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack6').css({'display':'none'});
            	$('#maxstore').removeClass('icon fa fa-youtube-play help_button');
               	 $('#maxstore').html("<a href='javascript:;' id='maxstore' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
            	
               
              

         });
      
         $('#checkBoxStack6').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video6:$(this).is(':checked') ? '1' : '0',video6:'hide_video_stack6'});
               
         });
			var table_columns = ["","location_id","name",'','phone','email',''];
			enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
		    enable_select_all();
		    enable_checkboxes();
		    enable_row_selection();
		    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
		    enable_cleanup(<?php echo json_encode(lang("items_confirm_cleanup"));?>);

		    $('.update-locations').click(function()
	 	 	{
				$("#portlet-content").plainOverlay('show');
	 	 	});

			<?php if ($this->session->flashdata('manage_success_message')) { ?>
				toastr.success(<?php echo json_encode($this->session->flashdata('manage_success_message')); ?>, <?php echo json_encode(lang('common_success')); ?>);
			<?php } ?>
		});
	</script>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-home"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<?php 
                    $extra="";
					$url_video_ver="https://www.youtube.com/watch?v=X7vpvIqo8Ww";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("TIENDAS");
						if($url_video!=null){
							$url_video_ver=$url_video;
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;
					?>

				<!--<a class="icon fa fa-youtube-play help_button" id='maxstore' rel='0' data-toggle="modal" data-target="#stack6"></a>-->
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

	<div class="row">		
		<div class="col-md-12">			
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">
						<span class="icon">
							<i class="fa fa-th"></i>
						</span>
						<?php echo lang('common_list_of').' '.lang('module_'.$controller_name); ?>

					</div>
					<div class="tools">
						<span data-original-title="<?php echo $total_rows; ?> total <?php echo $controller_name?>" class="label label-primary tip-left tooltips"><?php echo $total_rows; ?></span>
						
					</div>
				</div>
				<div class="portlet-body">					
					<div class="row">
						<div class="col-md-12 ">
							<div class="pull-right margin-bottom-10">
								<div class="btn-group">
									<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>												
										<?php echo anchor("$controller_name/view/-1/",
											'<i title="'.lang($controller_name.'_new').'" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang($controller_name.'_new').'</span>',
												array('class'=>'btn btn-medium btn-success', 
												'title'=>lang($controller_name.'_new'),
												'id' => 'new_location_btn'));
										?>
									<?php } ?>
									
									<?php echo anchor("locations/buy_register",
										'<i title="Comprar cajas" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">Comprar cajas</span>',
											array('class'=>'btn btn-medium btn-success', 
											'title'=>"Comprar cajas"
											));
									?>
									
									<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>													
										<?php /* echo anchor("$controller_name/delete",
											'<i title="'.lang('common_delete').'" class="fa fa-trash-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang('common_delete').'</span>',
												array('id'=>'delete', 
												'class'=>'btn btn-danger tip-bottom disabled','title'=>lang("common_delete"))); 
										*/?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="row margin-bottom-10">
						<div class="col-xs-12 col-md-6 col-lg-4">
							<?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
								<div class="input-group">								
									<input type="text" class="form-control form-inps" name ='search' id='search' value="<?php echo H($search);  ?>" placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
									<span class="input-group-addon">
										<i class="fa fa-search"></i>
									</span>
								</div>
							</form>
						</div>
						<div class="col-xs-12 col-md-6 col-lg-6 pull-right">
							<?php if (!is_on_demo_host()) { ?>
								<div class="col-md-12" style="text-align: right;">
									<strong><a href="<?php echo base_url();?>index.php/locations" target="_blank"><?php echo lang('locations_adding_location_requires_addtional_license'); ?></a></strong>
								</div>
							<?php } ?>
						</div>
					</div>				

					<div class="row">
						<div class="col-md-12">	
							<?php if($pagination) {  ?>
								<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top">
									<?php echo $pagination;?>
								</div>
							<?php } ?>		
							
							<div class="table-responsive" >
								<?php echo $manage_table; ?>			
							</div>												
							
							<?php if($pagination) {  ?>
								<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
									<?php echo $pagination;?>
								</div>
							<?php } ?>										
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>

	<?php if (!is_on_demo_host()) { ?>
		<script type="text/javascript">
			$('#new_location_btn').click(function()
			{
				if (!confirm(<?php echo json_encode(lang('locations_confirm_purchase')); ?>))
				{
					window.location='<?php echo base_url();?>index.php/locations';
					return false;
				}
			})
		</script>	
	<?php } ?>
			
<?php $this->load->view("partial/footer"); ?>