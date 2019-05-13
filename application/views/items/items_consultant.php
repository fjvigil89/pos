<?php $this->load->view("partial/header"); ?>
	<script type="text/javascript">
		$(document).ready(function()
		{
			<?php
			$has_cost_price_permission = $this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id);
			if ($has_cost_price_permission)
			{
			?>
				var table_columns = ["","item_id",'name','category','cost_price','unit_price','quantity','quantity_warehouse','','',''];
				//var table_columns = ["","item_id","item_number","",'name','category','size','','','','cost_price','unit_price','quantity','','','',''];				
			<?php	
			}
			else
			{
			?>
				var table_columns = ["","item_id",'name','category','unit_price','quantity','quantity_warehouse','','',''];	
			<?php	
			}
			?>
			enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir);?>);
		    enable_select_all();
		    enable_checkboxes();
		    enable_row_selection();
		    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
		    enable_cleanup(<?php echo json_encode(lang("items_confirm_cleanup"));?>);
			
			$('.effect, .btn-inventory, .btn-clon, .update-items').click(function()
	 	 	{
				$("#portlet-content").plainOverlay('show');
	 	 	});

		    $('#generate_barcodes').click(function()
		    {
		    	var selected = get_selected_values();
		    	if (selected.length == 0)
		    	{
		    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
		    		return false;
		    	}

		    	$(this).attr('href','<?php echo site_url("items/generate_barcodes");?>/'+selected.join('~'));
		    });

			$('#generate_barcode_labels').click(function()
		    {
		    	var selected = get_selected_values();
		    	if (selected.length == 0)
		    	{
		    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
		    		return false;
		    	}

		    	$(this).attr('href','<?php echo site_url("items/generate_barcode_labels");?>/'+selected.join('~'));
		    });
			 
			 <?php if ($this->session->flashdata('manage_success_message')) { ?>
			 	toastr.success(<?php echo json_encode($this->session->flashdata('manage_success_message')); ?>, <?php echo json_encode(lang('common_success')); ?>); 				
			 <?php } ?>
		});

		function post_bulk_form_submit(response)
		{
			window.location.reload();
		}

		function select_inv()
		{	
			if (confirm(<?php echo json_encode(lang('items_select_all_message')); ?>))
			{
				$('#select_inventory').val(1);
				$('#selectall').css('display','none');
				$('#selectnone').css('display','block');
				$.post('<?php echo site_url("items/select_inventory");?>', {'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>',select_inventory: $('#select_inventory').val()});
			}			
		}

		function select_inv_none()
		{
			$('#select_inventory').val(0);
			$('#selectnone').css('display','none');
			$('#selectall').css('display','block');
			$.post('<?php echo site_url("items/clear_select_inventory");?>', {'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>',select_inventory: $('#select_inventory').val()});	
		}
		select_inv_none();
	</script>

	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
			
				<i class="icon fa fa-table"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=gEHL69sTREw&t=2s";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("INVENTARIO");
						if($url_video!=null){
							$url_video_ver=$url_video;
							$extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;
					//<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack4"></a>
				
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


	<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>


	<div class="row">		
		<div class="col-md-12">
			<!-- BEGIN CONDENSED TABLE PORTLET-->
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
									
								</div>
							</div>
						</div>
					</div>

					<div class="row margin-bottom-10">
						
                        <?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off'),array('search_flag'=>1 , 'consultant'=>TRUE)); ?>
                            <div class="col-xs-12 col-md-4 col-lg-4 margin-bottom-05">	
                                <div class="input-group">
                                    <input type="text" name ='search' id='search' class="form-control form-inps" value="<?php echo H($search); ?>"  placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
                                    <span class="input-group-btn">
                                        <button name="submitf" class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>										
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-3 margin-bottom-05">
                                <?php echo form_dropdown('category', $categories, $category, 'id="category" class="bs-select form-control"'); ?>
                            </div>
                            <div class="col-xs-12 col-md-2 col-lg-3 margin-bottom-05">
                                <?php echo form_dropdown('order_dir', array('asc'=>lang('minor_price'),'desc'=>lang('major_price')), '','id="filter_price" class="bs-select form-control"'); ?>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-2 margin-bottom-05">
                                <?php echo form_submit('submit', lang('common_search'),'class="btn btn-primary btn-block"'); ?>
                            </div>							
                        </form>
                        <div class="col-xs-12 col-md-12 col-lg-3 margin-bottom-05 ">
                            <a href="<?php echo site_url($controller_name.'/clear_state_consultant'); ?>" class="btn btn-info btn-block clear-state pull-right effect"><?php echo lang('common_clear_search'); ?></a>
                        </div>
					<!--boton de consultor-->
                        <div class="col-xs-12 col-md-12 col-lg-3 margin-bottom-05 ">
                            <a href="<?php echo site_url('items/view_consultant'); ?>" class="btn btn-info btn-block clear-state pull-right effect"><?php echo lang('common_consultant'); ?></a>
                        </div>
						
                    </div>

					<?php if($total_rows > $per_page) { ?>
						<div id="selectall" class="selectall" onclick="select_inv()" style="text-align: center;display:none;cursor:pointer">
							<?php echo lang('items_all').' <b>'.$per_page.'</b> '.lang('items_select_inventory').' <b style="text-decoration:underline">'.$total_rows.'</b> '.lang('items_select_inventory_total'); ?>
						</div>
						<div id="selectnone" class="selectnone" onclick="select_inv_none()" style="text-align: center;display:none; cursor:pointer">
							<?php echo '<b>'.$total_rows.'</b> '.lang('items_selected_inventory_total').' '.lang('items_select_inventory_none'); ?>
						</div>
					<?php 
					}
						echo form_input(array(
						'name'=>'select_inventory',
						'id'=>'select_inventory',
						'style'=>'display:none')); 
					?>

					<div class="row">
						<div class="col-md-12">	

							<?php if($pagination) {  ?>
								<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
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
			<!-- END CONDENSED TABLE PORTLET-->
		</div>
	</div>

	<script type="text/javascript">
	  	$(document).ready( function (){
<?php if($this->config->item('hide_video_stack4') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack4').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack4")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack4').css({'display':'none'});

         }
         
     
         });
          $('#closeitems').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack1').css({'display':'none'});
               $('#maxitems').removeClass('icon fa fa-youtube-play help_button');
               $('#maxitems').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });
      
      
         $('#checkBoxStack4').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video4:$(this).is(':checked') ? '1' : '0',video4:'hide_video_stack4'});

             
               
         });

		  	$('.fancybox').fancybox({
	            'type':'image'
	        });	         	  
			
		}); 
      
  	</script>

<?php $this->load->view("partial/footer"); ?>

