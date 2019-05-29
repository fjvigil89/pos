<?php $this->load->view("partial/header"); ?>



	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-shopping-cart'></i>
				<?php echo lang('sales_opening_amount');?>
				<a class="icon fa fa-youtube-play help_button" id='maxamount' rel='0' data-toggle="modal" data-target="#stack8"></a>
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
			<!-- BEGIN CONDENSED TABLE PORTLET-->
			<div class="portlet box green" id="portlet-content">
				<div class="portlet-title">
					<div class="caption">						
                        <i class=" icon-layers "></i>
                        <span class="caption-subject sbold uppercase">Calendar</span>
					</div>					
				</div>
				<div class="portlet-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <!-- BEGIN DRAGGABLE EVENTS PORTLET-->
                            <h3 class="event-form-title margin-bottom-20">Draggable Events</h3>
                            <div id="external-events">
                                <form class="inline-form">
                                    <input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title">
                                    <br>
                                    <a href="javascript:;" id="event_add" class="btn green"> Add Event </a>
                                </form>
                                <hr>
                                <div id="event_box" class="margin-bottom-10">
								
								</div>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" for="drop-remove"> remove after drop
                                    <input type="checkbox" class="group-checkable" id="drop-remove">
                                    <span></span>
                                </label>
                                <hr class="visible-xs"> </div>
                            <!-- END DRAGGABLE EVENTS PORTLET-->
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div id="calendar" class="has-toolbar fc fc-ltr fc-unthemed">
							 </div>
                        </div>
                    </div>
        
                    
				</div>
			</div>
		</div>
	</div>


	<script type='text/javascript'>
		//validation and submit handling
		$(document).ready(function()
		{

         <?php if($this->config->item('hide_video_stack8') == '0'){?>
         $('.modal.fade').addClass('in');
         $('#stack8').css({'display':'block'});
         <?php } ?>
         $('.modal.fade.in').click(function(e){
       
         if($(e.target)[0].id == "stack8")
         {
               $('.modal.fade.in').removeClass('in');
               $('#stack8').css({'display':'none'});

         }
         
     
         });
         $('#closeamount').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack8').css({'display':'none'});
            	$('#maxamount').removeClass('icon fa fa-youtube-play help_button');
               	 $('#maxamount').html("<a href='javascript:;' id='maxamount' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
            	
               
              

         });
      
         $('#checkBoxStack8').click(function(e){
             
             $.post('<?php echo site_url("config/show_hide_video_help");?>',
             {show_hide_video8:$(this).is(':checked') ? '1' : '0',video8:'hide_video_stack8'});
          });
      	 $('#closamount').click(function(){
         	
               $('.modal.fade.in').removeClass('in');
               $('#stack2').css({'display':'none'});
               $('#maxamount').removeClass('icon fa fa-youtube-play help_button');
               $('#maxamount').html("<a href='javascript:;' id='maxamount' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
         });    
               
         
			$("#opening_amount").focus();
			
			var submitting = false;

			$('#opening_amount_form').validate({
				submitHandler:function(form)
				{	
					var submitting = false;
				 	if (submitting) return;
					submitting = true;
					$("#portlet-content").plainOverlay('show');

					$(form).ajaxSubmit({
						success:function(response)
						{
							console.log(response);
							$("#portlet-content").plainOverlay('hide');
							if(response.success)
							{ 																	
							    window.location = '<?php echo site_url('sales'); ?>';									
							}
							else
							{	
								toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
							}

							submitting = false;
						},
						dataType:'json',
						resetForm: false
					});
			    },				    

				rules:
				{
					opening_amount: {
						required: true,
						number: true
					},
					<?php if(isset($items ) and count($items)>0 and $this->config->item("monitor_product_rank")==1){
					
					foreach($items as $item) { ?>
						"item_rango[<?php echo $item->item_id ?>]": {
							required: true,
							number: true,
							min:0
						},
				<?php }}?>
		   		},
				
				errorClass: "text-danger",
				errorElement: "span",
				errorLabelContainer: "#error",
				highlight:function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
				},
				messages: {
			   		opening_amount: {
						required: <?php echo json_encode(lang('sales_amount_required')); ?>,
						number: <?php echo json_encode(lang('sales_amount_number')); ?>
					},
					<?php if(isset($items ) and count($items)>0 and $this->config->item("monitor_product_rank")==1){
					
					foreach($items as $item) { ?>
						"item_rango[<?php echo $item->item_id; ?>]": {
						required: " Ingrese todos los rangos. ",
						number: " Los rangos deben de ser numerico. ",
						min: " El valor minimo del ranfo es 0. ",
						},
					<?php }}?>
		   		}
			});
			
			function calculate_total()
			{
				var add = 0;
		   		var total=0;
		    	var contar=$('#grupo > input').length;

				for(var i=0; i<contar; i++)
				{
					currency = $("#currency" +i).val();
					quantity= $("#quantity" +i ).val();
				    total+=quantity*currency;
			 	}
				$("#opening_amount").val(parseFloat(Math.round(total * 100) / 100).toFixed(2));			
			}

			var contar=$('#grupo > input').length;
			for(var i=0; i<contar; i++)
			{
				$("#currency"+i).change(calculate_total);
				$("#currency"+i).keyup(calculate_total);
		    }
		 });
	</script>

<?php $this->load->view('partial/footer.php'); ?>