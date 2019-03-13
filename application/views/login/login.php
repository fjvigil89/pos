<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <?php $this->load->view("partial/head"); ?>

        <script type="text/javascript">
            $(document).ready(function()
            {
                //If we have an empty username focus
                if ($("#username").val() == '')
                {
                    $("#username").focus();                   
                }
                else
                {
                    $("#password").focus();
                }
            });
        </script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="page-md login">

        <?php if ($application_mismatch) {?>
            <div class="alert alert-danger">
                <strong><?php echo json_encode(lang('common_error')); ?></strong> <?php echo $application_mismatch; ?>
            </div>
        <?php exit; }?>

        <?php if ($ie_browser_warning) { ?>
            <div class="alert alert-danger">
                <?php 
                echo lang('login_unsupported_browser');
                ?>
            </div>
        <?php } ?>

        <!-- BEGIN LOGO URL_POS --> 
        <?php
            $url= URL_COMPANY;
            $url_comp= URL_COMPANY;
            $name_comp= NAME_COMPANY;

            $imagen =  $this->Appconfig->get_logo_image();           
            if($es_franquicia){
                $url =$data_company->domain; 
                $url_comp=$data_company->url_website;
                $name_comp= $data_company->short_name;
                $imagen =base_url()."index.php/app_files/view_logo_store/".$data_company->id; 
            }
        
        ?>
        <div class="logo">
            <a href="<?php echo $url ; ?>">
                <?php echo img(
                array(
                    'src' =>  $imagen,
            )); ?>
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <?php echo form_open('login', array('class' => 'login-form', 'id'=>'loginform', 'autocomplete'=> 'off')) ?>
            
                <h4 class="form-title"><?php echo lang('login_welcome_message'); ?></h4>
                
                <?php if (validation_errors()) {?>         
                    <div class="alert alert-danger">          
                        <strong><?php echo lang('common_error'); ?></strong>
                        <?php echo validation_errors(); ?>         
                    </div>         
                <?php }else{ ?>
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span>Ingrese su  nombre de usuario,tienda y clave</span>
                    </div>
                <?php } ?>
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?php echo lang('login_username');?></label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" id="username" autocomplete="off" placeholder="<?php echo lang('login_username')?>" name="username" value="<?php echo $user;?>"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?php echo lang('login_store');?></label>
                    <div class="input-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <input class="form-control placeholder-no-fix" type="text" id="store" autocomplete="off" placeholder="<?php echo lang('login_store')?>" name="store" required title="Nombre de la tienda es requerida" value="<?php echo $store;?>"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo lang('login_password');?></label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i> 
                        <?php
                        $passw= (isset($demo) && $demo==1  )? $pass_:((isset( $_GET['password']))  ? $_GET['password'] : '' );

                         ?>
                        <input class="form-control placeholder-no-fix" type="password"  id="password" value='<?php  echo $passw;?>'
                         autocomplete="off" placeholder="<?php echo lang('login_password');?>" name="password"/>
                    </div>
                </div>
                
                <?php if (0): ?>
                    <div class="g-recaptcha" data-sitekey="6LdGqCcTAAAAALX9NK3GQUg5j0zgkRpDRkvHI91Z"></div>';
                <?php endif;?>
                                  
                <div class="form-actions">
                    <label style="font-size: 16px;"><input type="checkbox" checked name="remember_user" value="1"><?php echo lang('login_remember_user');?></label>
                </div>
                
                <div class="form-actions">
                    <!--<label class="checkbox">
                    <input type="checkbox" name="remember" value="1"/> Recuérdame </label> -->
                    <button type="submit" class="btn blue btn-block " >
                        <?php echo lang('login_login');?>
                    </button>
                    <?php if(isset($demo) and  $demo==0):?>
                        <br>
                        <div style="text-align:center; color: #ffffff">        
                            <p>
                                <?php echo "Ir al demo";?>
                                <a href="javascript:;" id="demo"><?php echo anchor( base_url()."index.php/login/index/1","Aquí",'class="link-custom-login"'); ?></a>                        
                            </p>
                        </div>  
                    <?php endif;?>                 
                </div>
                <div >
                    <h4><?php echo lang('login_reset_password').'?'; ?></h4>
                    <p> 
                        <?php echo lang('login_info_reset_password1'); ?>
                        <a href="javascript:;" id="forget-password"><?php echo anchor(URL_LICENCIA."/passwordReset", lang('login_reset_password_link'), 'class="link-custom-login"'); ?></a>
                        <?php echo lang('login_info_reset_password2'); ?>
                    </p>
                </div>
            </form>
            <!-- END LOGIN FORM -->
        </div>
        <!-- BEGIN COPYRIGHT -->
        <div class="copyright">
            <?php echo YEAR; ?>  &copy;  <a target="_blank" href="<?php echo  $url_comp ?>"><?php echo  $name_comp ?></a> Versión <span class="label label-info"> <?php echo APPLICATION_VERSION; ?></span>
        </div>
        <!-- END COPYRIGHT -->
        
        <script>

            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();   
            });

            jQuery(document).ready(function() {     
                Metronic.init(); // init metronic core components
                Layout.init(); // init current layout
                Login.init();
                Demo.init();
                ComponentsDropdowns.init();
                // init background slide images
                $.backstretch([                
                    "<?php echo base_url();?>assets/admin/pages/media/bg/10.png"
                    /*"<?php echo base_url();?>assets/admin/pages/media/bg/2.jpg",
                    "<?php echo base_url();?>assets/admin/pages/media/bg/3.jpg",
                    "<?php echo base_url();?>assets/admin/pages/media/bg/4.jpg",
                    "<?php echo base_url();?>assets/admin/pages/media/bg/7.jpg",
                    "<?php echo base_url();?>assets/admin/pages/media/bg/8.jpg",
                    "<?php echo base_url();?>assets/admin/pages/media/bg/9.jpg"*/
                    ],  {
                        fade: 1000,
                        duration: 8000
                    }
                );
            });
        </script>
        
        <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
</html>
