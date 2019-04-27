<?php $this->load->view("partial/header"); ?>

	<script type="text/javascript">
		$(document).ready(function()
		{
		    var table_columns = ["",'giftcard_number',"value",'',''];
			enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
			enable_select_all();
		    enable_checkboxes();
		    enable_row_selection();
		    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
			
		    $('.effect, .update-giftcards').click(function()
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

		    	$(this).attr('href','<?php echo site_url("giftcards/generate_barcodes");?>/'+selected.join('~'));
		    });

			$('#generate_barcode_labels').click(function()
		    {
		    	var selected = get_selected_values();
		    	if (selected.length == 0)
		    	{
		    		alert(<?php echo json_encode(lang('items_must_select_item_for_barcode')); ?>);
		    		return false;
		    	}

		    	$(this).attr('href','<?php echo site_url("giftcards/generate_barcode_labels");?>/'+selected.join('~'));
		    });
			 
			<?php if ($this->session->flashdata('manage_success_message')) { ?>
				toastr.success(<?php echo json_encode($this->session->flashdata('manage_success_message')); ?>, <?php echo json_encode(lang('common_success')); ?>);				
			<?php } ?>

		});

		function init_table_sorting()
		{
			//Only init if there is more than one row
			if($('.tablesorter tbody tr').length >1)
			{
				$("#sortable_table").tablesorter(
				{
					sortList: [[1,0]],
					headers:
					{
						0: { sorter: false},
						3: { sorter: false}
					}
				});
			}
		}
	</script>


		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-home"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<?php 
                    $extra="style='display: none; '";
					$url_video_ver="";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("TARJETA DE REGALO");
						if($url_video!=null){
							$url_video_ver=$url_video;
							$extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;
				
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
									<?php echo anchor("$controller_name/view/-1/",
										'<i title="'.lang($controller_name.'_new').'" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang($controller_name.'_new').'</span>',
										array('class'=>'btn btn-success new effect', 
											'title'=>lang($controller_name.'_new')));
									?>
									<?php echo anchor("$controller_name/generate_barcode_labels",
										'<i title="'.lang("common_barcode_labels").'" class="fa fa-barcode tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang("common_barcode_labels").'</span>',
										array('id'=>'generate_barcode_labels', 
											'class' => 'btn hidden-xs btn-success disabled',
											'target' =>'_blank',
											'title'=>lang('common_barcode_labels'))); 
									?>
									<?php echo anchor("$controller_name/generate_barcodes",
										'<i title="'.lang("common_barcode_sheet").'" class="fa fa-barcode tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang("common_barcode_sheet").'</span>',
										array('id'=>'generate_barcodes', 
											'class' => 'btn hidden-xs btn-success disabled',
											'target' =>'_blank',
											'title'=>lang('common_barcode_sheet'))); 
									?>
									<?php echo anchor("$controller_name/delete",
										'<i title="'.lang("common_delete").'" class="fa fa-trash-o tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang("common_delete").'</span>',
										array('id'=>'delete', 
											'class'=>'btn btn-danger disabled')); 
									?>
									<?php echo anchor("$controller_name/excel_export",
										'<i title="'.lang("common_excel_export").'" class="fa fa-download tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">'.lang("common_excel_export").'</span>',
										array('class'=>'btn hidden-xs btn-success'));
									?>
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

			
<?php $this->load->view("partial/footer"); ?>
	