<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <?php $this->load->view("partial/head"); ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="page-md login">

    
        <!-- BEGIN LOGO URL_POS --> 
        <?php
            $url= URL_COMPANY;
            $url_comp= URL_COMPANY;
            $name_comp= NAME_COMPANY;

            $imagen =  $this->Appconfig->get_logo_image(); 
        
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
        
            
                
                      
                    <div class="alert alert-danger">          
                        <strong><?php echo lang('common_error'); ?> Usted No tiene Permisos para ingresar al sistema a esta hora: <?php echo $hour; ?></strong>       
                    </div>        
                    
                
                <div class="form-actions">
                   <?php echo anchor("home/logout",'<i class="btn blue btn-block"> Volver al Login</i>');?>
                </div>
            <!-- END LOGIN FORM -->
        </div>
        <!-- BEGIN COPYRIGHT -->
        
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
