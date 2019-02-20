<?php echo form_open_multipart('config/save_language/',array('id'=>'config_form','class'=>'form-horizontal', 'autocomplete'=> 'off'));   ?>
	<div class="modal show" id="myModal" tabindex="-1" role="myModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button">×</button>
					<h4><strong><?php echo lang('login_switch_user'); ?></strong></h4>
				</div>

				<div class="modal-body ">				
					<div class="form-group">	
						<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-md-3 control-label required')); ?>
						<div class="col-md-9">
							<select class="bs-select form-control" data-show-subtext="true" name="language">									
	                            <option data-icon="flagstrap-icon flagstrap-co" value="spanish" <?php if($this->Appconfig->get_raw_language_value()=="spanish") echo "selected";?>>Colombia - Español</option>		                            
	                            <option data-icon="flagstrap-icon flagstrap-us" value="english" <?php if($this->Appconfig->get_raw_language_value()=="english") echo "selected";?>>Estados Unidos - Ingles</option>
	                            <option data-icon="flagstrap-icon flagstrap-pe" value="spanish_peru" <?php if($this->Appconfig->get_raw_language_value()=="spanish_peru") echo "selected";?>>Peru - Español</option>
	                            <option data-icon="flagstrap-icon flagstrap-uy" value="spanish_uruguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_uruguay") echo "selected";?>>Uruguay - Español</option>
	                            <option data-icon="flagstrap-icon flagstrap-py" value="spanish_paraguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_paraguay") echo "selected";?>>Paraguay - Español</option>
	                            <option data-icon="flagstrap-icon flagstrap-cl" value="spanish_chile" <?php if($this->Appconfig->get_raw_language_value()=="spanish_chile") echo "selected";?>>Chile - Español</option>
	                            <option data-icon="flagstrap-icon flagstrap-ve" value="spanish_venezuela" <?php if($this->Appconfig->get_raw_language_value()=="spanish_venezuela") echo "selected";?>>Venezuela - Español</option>																		
							</select>
						</div>
					</div>				
				</div>

				<div class="modal-footer">
					<div class="form-actions">					
						<?php
						echo form_button(array(
							'name'=>'submit',
							'id'=>'submit',
							'type' => 'submit',						
							'class'=>'btn blue btn-block'), lang('common_submit')
						);
						?>
						<i id="spin" class="fa fa-spinner fa fa-spin fa fa-2x hidden"></i>
						<span id="error_message" class="text-danger">&nbsp;</span>
					</div>			
				</div>			
			</div>
		</div>
	</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    $("#config_form").submit(function(e) {

	    var url = '<?php echo site_url("config/save_language/"); ?>'; 

	    $.ajax({
	       	type: "POST",
	       	url: url,
	       	data: $("#config_form").serialize(),
	       	dataType:'json', 
	       	success: function(data)
	       	{
	           	if(data.success)
				{ 																	
				    toastr.success(data.message, <?php echo json_encode(lang('common_success')); ?>);
				    $('#myModal').modal('hide');			    
				}
				else
				{	
					toastr.error(data.message, <?php echo json_encode(lang('common_error')); ?>);
				}
	       	}
	    });

	    e.preventDefault(); 
	});

</script>