<!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <title>Pos-offLine</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <base href='<?php echo base_url();?>'>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <!-- BEGIN CSS -->
        <link href="<?php echo base_url();?>css/fonts-google.css" rel="stylesheet" type="text/css">
        <!-- IMPORTANTE -->
        <link href="<?php echo base_url();?>css/ionicons.min.css" rel="stylesheet" type="text/css">
        <!-- IMPORTANTE -->
        <link href="<?php echo base_url();?>bin/bin.min.css" rel="stylesheet" type="text/css">
        <!-- <link href="<?php echo base_url();?>css/pagination.css" rel="stylesheet" type="text/css">    -->
        <link href="<?php echo base_url();?>css/confirm/jquery-confirm.css" rel="stylesheet">

        <!-- BEGIN JAVASCRIPT-->
        <script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/lib/idb.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/offline/dexie.min.js" type="text/javascript"></script>    

        <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/offline/appconfig.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>
       <script src="<?php echo base_url();?>js/offline/employee.js" type="text/javascript"></script>

         <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>
        <script src="<?php echo base_url();?>js/offline/validador.js"></script>
        <script src="<?php echo base_url();?>js/offline/md5.js"></script>
        <script type="text/javascript">
			var SITE_URL= "<?php echo site_url(); ?>";
            var BASE_URL = '<?= base_url();?>';
            var objEmployee = new Employee();
            es_login();
            es_db_actualizada(BASE_URL);
            
	</script>
        
        <script>           
             validation_indexedDB_browser();
        </script>
    </head>
    <!-- END HEAD -->

    <body class="login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.html">
                <img src="" alt="" /> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content" style="background-color:#ffffff">
            <!-- BEGIN LOGIN FORM -->
            <form class="" id="datos-login" action="index.html" method="post">
            <h4 class="form-title" style="color:#000000" ><?php echo lang('login_welcome_message'); ?></h4>
                    <div class="alert alert-danger display-hide" id="div_mensaje">
                        <button class="close" data-close="alert"></button>
                        <span id="mensaje">Ingrese su  nombre de usuario,tienda y clave</span>
                    </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" id="username" autocomplete="off" placeholder="<?php echo lang('login_username')?>" required name="username" value=""/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <input class="form-control placeholder-no-fix" type="text" id="store" autocomplete="off" placeholder="<?php echo lang('login_store')?>" name="store" required title="Nombre de la tienda es requerida" value=""/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <input class="form-control placeholder-no-fix" type="password" required id="password" value='<?php  echo $passw;?>'
                         autocomplete="off" placeholder="<?php echo lang('login_password');?>" name="password"/>
                    </div> 
                </div>   

                <div class="form-actions">
                <button type="submit" class="btn blue btn-block " >
                        <?php echo lang('login_login');?>
                    </button>
                </div>  
              
        </div>
        <!--<div class="copyright"> 2014 © Metronic. Admin Dashboard Template. </div>-->


    <script>
        
        jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        Demo.init(); // init demo features
        ComponentsDropdowns.init();

        });
        
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    $('#datos-login').submit(function(e) {
        e.preventDefault();
        let user =$("#username").val(); 
        let store =$("#store").val(); 
        let password =$("#password").val(); 
        if(user!="" && store!="" && password!="" ){
            (async function(){
                if(!await db_existe(store)){
                    $("#mensaje").html("Tienda no éxiste.");
                    $("#div_mensaje").show();
                }else{
                    if(await iniciar_sesion(user,md5(password), store)==true){
                        window.location ="index.php/sales/offline";
                    }else{
                        $("#mensaje").html("Datos incorrectos.");
                        $("#div_mensaje").show();
                    }
                }
            })();
            
        }      
    });
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
                
</body>


</html>