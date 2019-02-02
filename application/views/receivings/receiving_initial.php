<?php $this->load->view("partial/header"); ?>
		
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-cloud-download"></i>
				<?php echo lang('receivings_register'); ?>	
				<?php 
					$extra=" style='display: none; '";
					$url_video_ver="#";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("COMPRAS DE PRODUCTOS");
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

	<div id="sale-grid-big-wrapper" class="clearfix">
		<div class="clearfix" id="category_item_selection_wrapper">
			<div class="panel panel-success no_margin_bottom">
				<div class="panel-heading">
					<h3 class="panel-title">Panel de Items</h3>
				</div>
				<div class="panel-body">
					<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar"></div> 
					<div id="category_item_selection" class="row"></div>
					<div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar"></div>
				</div>
			</div>				  
		</div>
	</div>


	<div id="register_container" class="receiving">
		<?php $this->load->view("receivings/receiving"); ?>
	</div>


	<script type="text/javascript">
		$(document).ready(function()
		{			

			<?php if ($this->config->item('always_show_item_grid')) { ?>
				$(".show-grid").click();
			<?php } ?>

			var current_category = null;

			function load_categories()
			{
				$.get('<?php echo site_url("sales/categories");?>', function(json)
				{
					processCategoriesResult(json);
				}, 'json');	
			}

			$(document).on('click', ".pagination.categories a", function(event)
			{
				$("#category_item_selection_wrapper").plainOverlay('show');
				event.preventDefault();
				var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);
			
				$.get('<?php echo site_url("sales/categories");?>/'+offset, function(json)
				{
					processCategoriesResult(json);

				}, "json");
			});

			$(document).on('click', ".pagination.items a", function(event)
			{
				$("#category_item_selection_wrapper").plainOverlay('show');
				event.preventDefault();
				var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);
			
				$.post('<?php echo site_url("sales/items");?>/'+offset, {category: current_category}, function(json)
				{
					processItemsResult(json);
				}, "json");
			});

			$('#category_item_selection_wrapper').on('click','.category_item.category', function(event)
			{
				$("#category_item_selection_wrapper").plainOverlay('show');
				
				event.preventDefault();
				current_category = $(this).text();
				$.post('<?php echo site_url("sales/items");?>', {category: current_category}, function(json)
				{
					processItemsResult(json);
				}, "json");
			});

			$('#category_item_selection_wrapper').on('click','.category_items.item', function(event)
			{		
				$("#category_item_selection_wrapper").plainOverlay('show');
				event.preventDefault();
				$( "#item" ).val($(this).data('id'));
				$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success:function(response)
				{
					<?php
						echo "toastr.success(".json_encode(lang('items_successful_adding')).", ".json_encode(lang('common_success')).");";					
					?>
					$("#category_item_selection_wrapper").plainOverlay('hide');	
					$('.show-grid').addClass('hidden');
					$('.hide-grid').removeClass('hidden');
				}});	
			});

			$("#category_item_selection_wrapper").on('click', '#back_to_categories', function(event)
			{
				$("#category_item_selection_wrapper").plainOverlay('show');
				
				event.preventDefault();
				load_categories();
			});

			function processCategoriesResult(json)
			{	
				$("#category_item_selection_wrapper .pagination").removeClass('items').addClass('categories');
				$("#category_item_selection_wrapper .pagination").html(json.pagination);
			
				$("#category_item_selection").html('');
			
				for(var k=0;k<json.categories.length;k++)
				{
					var category_item = $("<a/>").attr('class', 'btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6').append('<p>'+json.categories[k]+'</p>');
					$("#category_item_selection").append(category_item);
				}
				
				$("#category_item_selection_wrapper").plainOverlay('hide');
			}

			function processItemsResult(json)
			{
				$("#category_item_selection_wrapper .pagination").removeClass('categories').addClass('items');
				$("#category_item_selection_wrapper .pagination").html(json.pagination);

				$("#category_item_selection").html('');
			
				var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class', 'category_item back-to-categories col-md-2 col-sm-3 col-xs-6 ').append('<p>&laquo; '+<?php echo json_encode(lang('sales_back_to_categories')); ?>+'</p>');
				$("#category_item_selection").append(back_to_categories_button);

				for(var k=0;k<json.items.length;k++)
				{
					var image_src = json.items[k].image_src ? json.items[k].image_src : "<?php echo base_url().'img/no-photo.jpg'?>";
					var prod_image = "";
					var item_parent_class = "";
					if (image_src != '' ) {
						var item_parent_class = "item_parent_class";
						var prod_image = '<a class="btn grey-gallery"><img class="img-thumbnail" style="width:100%; height:60px;" src="'+image_src+'" alt="" />';
					}
					
					var item = $("<div/>").attr('class', 'category_items item space-item col-lg-2 col-md-2 col-sm-3 col-xs-6  '+item_parent_class).attr('data-id', json.items[k].id).append(prod_image+'<p class="letter-space-item">'+json.items[k].name+'</p>'+'</a>');
					$("#category_item_selection").append(item);
				}
				
				$("#category_item_selection_wrapper").plainOverlay('hide');
			
			}
			load_categories();
		});

		<?php if (!$this->agent->is_mobile()) { ?>
			var last_focused_id = null;
			setTimeout(function(){$('#item').focus();}, 10);
		<?php } ?>
	</script>

<?php $this->load->view("partial/footer"); ?>