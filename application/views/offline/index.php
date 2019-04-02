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
                  <div class="form-group ">
                     <label class="requireds control-label col-md-3">
                        <a class="help_config_required  tooltips " data-placement="left" title="" data-original-title="<?php echo lang('data_password_title'); ?> "><?php echo lang('Password_Login'); ?></a>:
							<!--<span class="required">* </span>-->
                     </label>
                     <div class="col-md-8">
                     <?php echo form_input(array(
					        'class'=>'form-control',
                            'name'=>'password',
                            "type"=>"password", 
                            "min"=>6,         
					        'id'=>'password'));
	                  	?>
								
                     </div>
                     </div>
                     <div class="form-group">
                     <label class="requireds control-label col-md-3">
                        <a class="help_config_required  tooltips " data-placement="left" title="" data-original-title="<?php echo lang('data_password_title_again'); ?> "><?php echo lang('Password_Again'); ?></a>:
							<!--<span class="required">* </span>-->
                     </label>
                     <div class="col-md-8">
                     <?php echo form_input(array(
					        'class'=>'form-control',
                            'name'=>'password_again',
                            "type"=>"password", 
                            "min"=>6,         
					        'id'=>'password_again'));
	                  	?>	
                        <span class="required">*<?php echo lang('password_mensaje'); ?>  </span>			
                     </div>
                     <div class="col-md-12">
                        <ul>
                        <h4><?php echo lang('Password_creation'); ?></h4>
                        <ol>
                           <li>1. <?php echo lang('Password_creation_step_1'); ?></li>
                           <li>2. <?php echo lang('Password_creation_step_2'); ?></li>
                           <li>3. <?php echo lang('Password_creation_step_3').' '.site_url('sales/offline'); ?></li>
                           
                        </ol>
                        </ul>
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

<script>
    jQuery(document).ready(function() {
        $("#offline_form").submit(function(e){
            e.preventDefault(); 
            if($("#password").val().length<6){
               toastr.error("Contraseña muy corta, debe tener mínimo 6 caracteres.", <?php echo json_encode(lang('common_error'))?> );
               return false;
            }else if($("#password").val()!=$("#password_again").val()){
               toastr.error("las contraseñas no coinciden.", <?php echo json_encode(lang('common_error'))?> );
               return false;
            }
            $("#submitf").attr("disabled", true);
            $.post('<?php echo site_url("offline/save");?>',$("#offline_form").serializeArray() , function(data) {
                data= JSON.parse(data);
                if(data.success==false){
                    toastr.error(data.message, <?php echo json_encode(lang('common_error'))?> );
                }  else{
                    toastr.success(data.message, <?php echo json_encode(lang('common_success'))?> );
                    console.log(update_password_sesion(data.password_encriptada,<?php echo $imployee_info->person_id; ?>));
                }   
                $("#submitf").removeAttr("disabled");  
            });
        });      
            
        });
</script>
<script src="<?php echo base_url();?>js/offline/validador.js" type="text/javascript"></script>
<?php $this->load->view("partial/footer"); ?>