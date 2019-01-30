<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<div class="page-title">
   <h1>
      <i class="fa fa-cloud-download"></i>
      <?php echo lang('offline_title'); ?>	
      <?php 
         $extra=" style='display: none; '";
         $url_video_ver="#";
         if($this->Appconfig->es_franquicia()){
         	$url_video=	$this->Appconfig->get_video("OFFLINE");
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
<div id="register_container" class="offline">
   <div id="form">
      <div class="portlet box green">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-book-open"></i>
               <span class="caption-subject bold">
               <?php echo lang('Offline_configuration'); ?>
               </span>
            </div>
         </div>
         <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php echo form_open('offline/save/',array('id'=>'offline_form','class'=>'form-horizontal', 'autocomplete'=> 'off'));   ?>

              
               <div class="form-body"> 
			           <h4><?php echo lang('common_fields_required_message'); ?></h4>
                  <div class="form-group">
                     <label class="requireds control-label col-md-3">
                        <a class="help_config_required  tooltips " data-placement="left" title="" data-original-title="<?php echo lang('data_password_title'); ?> "><?php echo lang('Password_Login'); ?></a>:
							<!--<span class="required">* </span>-->
                     </label>
                     <div class="col-md-8">
                     <?php echo form_input(array(
					        'class'=>'form-control',
                            'name'=>'password',
                            "type"=>"password",          
					        'id'=>'password'));
	                  	?>
						<span class="required">*<?php echo lang('password_mensaje'); ?>  </span>			
                     </div>
                  </div>
                 <!-- <div class="form-group">
                     <label for="last_name" class="col-md-3 control-label"><?php echo lang('time_sincronizacion'); ?> :</label>				
                     <div class="col-md-8">                     
                     <?php //echo form_dropdown('time_update_db',$array_time ,$this->config->item('time_update_db'),'class="bs-select form-control"')?>
                        
                      </div>
                  </div>-->
                 
                  <input type="hidden" name="redirect_code" value="0">
               </div>
               <div class="form-actions">
                  <div class="row">
                     <div class="col-md-offset-3 col-md-9">							
                        <button name="submitf" type="submit" id="submitf" class="btn btn-primary"><?php echo lang('button_save'); ?></button>															
                     </div>
                  </div>
               </div>
            </form>
            <!-- END FORM-->
         </div>
      </div>
   </div>
</div>
<?php $this->load->view("partial/footer"); ?>

<script>
    jQuery(document).ready(function() {
        $("#offline_form").submit(function(e){
            e.preventDefault(); 
            $("#submitf").attr("disabled", true);
            $.post('<?php echo site_url("offline/save");?>',$("#offline_form").serializeArray() , function(data) {
                data= JSON.parse(data);
                if(data.success==false){
                    toastr.error(data.message, <?php echo json_encode(lang('common_error'))?> );
                }  else{
                    toastr.success(data.message, <?php echo json_encode(lang('common_success'))?> );
                }   
                $("#submitf").removeAttr("disabled");  
            });
        });      
            
        });
</script>