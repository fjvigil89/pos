<?php $this->load->view("partial/header"); ?>
	<script type="text/javascript">
		$(document).ready(function() 
		{ 
			<?php if ($controller_name == 'suppliers') { ?>
				var table_columns = ['','company_name','last_name','first_name','email','phone_number'];
				
			<?php } else { ?>
				var table_columns = ['','<?php echo $this->db->dbprefix('orders'); ?>'+'.order_id','last_name','first_name','email','phone'];
			<?php } ?>
			enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
		    enable_select_all();
		    enable_checkboxes();
		    enable_row_selection();
		    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		    enable_email('<?php echo site_url("$controller_name/mailto")?>');
		    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
		 	enable_cleanup(<?php echo json_encode(lang($controller_name."_confirm_cleanup"));?>);
		 	$('.effect, .update-person, .update-supplier').click(function()
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
				<i class="icon fa fa-<?php echo $controller_name=="customers" ? "group" : "user"; ?>"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<a class="icon fa fa-youtube-play help_button" id='maxsuppliers' data-toggle="modal" data-target="#stack3"></a>
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
						<div class="col-xs-12 col-md-5 col-lg-4 pull-right">
							<a href="<?php echo site_url($controller_name.'/clear_state'); ?>" class="btn btn-info btn-block clear-state pull-right effect"><?php echo lang('common_clear_search'); ?></a>
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


<script type="text/javascript">
  	$(document).ready( function (){
	<?php if($this->config->item('hide_video_stack3') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack3').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack3")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack3').css({'display':'none'});

         }
         
     
         });
          $('#closesuppliers').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack3').css({'display':'none'});
               $('#maxsuppliers').removeClass('icon fa fa-youtube-play help_button');
               $('#maxsuppliers').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });
      
         $('#checkBoxStack3').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video3:$(this).is(':checked') ? '1' : '0',video3:'hide_video_stack3'});

             
               
         });
	  	$('.fancybox').fancybox({
            'type':'image'
        });	         	  
		
	});
      
  </script>
	
<?php $this->load->view("partial/footer"); ?>