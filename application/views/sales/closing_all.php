
<html   >
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
      <script src="<?php echo base_url();?>js/jquery.cookie.min.js"></script>
      <script src="<?php echo base_url();?>js/lib/idb.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/dexie.min.js" type="text/javascript"></script>    
      <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/validador.js"></script>
      <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>
      <script src="<?php echo base_url();?>js/offline/conexion_evento.js" type="text/javascript"></script>			
      <script src="<?php echo base_url();?>js/offline/helper_moneda.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/register_movement.js" type="text/javascript"></script>        
    
      <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/sale.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/register.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/employee.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/location.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/appconfig.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>



    <script type="text/javascript">
        validation_indexedDB_browser();
        validation_session_employee();
    
			var SITE_URL= "<?php echo site_url(); ?>";
            var BASE_URL = '<?= base_url();?>';
            es_db_actualizada(BASE_URL);
            var objSale = new Sale();
            var objAppconfig = new AppConfig();
            var objRegister = new Register();
            var objEmployee = new Employee();
            var objLocation = new Location();
            var objRegister_movement= new Register_movement();


	  </script>
   </head>
   <body class="page-md page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo page-sidebar-closed">
      <div class="modal fade in" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>
      <!-- BEGIN HEADER -->
      <div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
         <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
               <a href="#">
               <img src="" class="logo-default" id="header-logo" alt=""> </a>
               <div class="menu-toggler sidebar-toggler">
                  <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
               </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <div class="page-top-left">
               <div class="page-actions">
                  <!-- Button offline -->
                  
                  <!-- Dropdown Locations -->
                  <div class="btn-group" id="div_Locations">
                  </div>
               </div>
            </div>
            <!-- BEGIN PAGE TOP -->
            <div class="page-top">
               <!-- BEGIN TOP NAVIGATION MENU -->
               <div class="top-menu">
                  <ul class="nav navbar-nav pull-right">
                     <li class="flag-custom">
                        <h3 id="">
                           <span> colombia</span>
                        </h3>
                     </li>
                     <li class="info-time">
                        <h6 id="time">
                           <i class="icon fa fa-clock-o"></i>
                           <span class="text" id="fecha"> </span>
                        </h6>
                     </li>
                     <li class="separator hide">
                     </li>
                     <!-- BEGIN USER LOGIN DROPDOWN -->
                     <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                           <span id="username" class="username username-hide-on-mobile">
                           </span>
                           <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                           <li class="divider">
                           </li>
                           <li>
                              <a href="javascript:elimiar_sesion();">
                              <i class="fa fa-power-off"></i>Salir</a>
                           </li>
                        </ul>
                     </li>
                     <!-- END USER LOGIN DROPDOWN -->
                  </ul>
               </div>
               <!-- END TOP NAVIGATION MENU -->
            </div>
         </div>
      </div>
      <!-- END HEADER -->
      <div class="clearfix">
      </div>
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
      <!-- BEGIN SIDEBAR -->
      <div class="page-sidebar-wrapper">
         <div class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            
            <li class="active">
                  <a href="<?php echo site_url("sales/offline") ?>">
                  <i class="fa fa-shopping-cart"></i>
                  <span class="title">Ventas</span>
                  </a>
               </li>
               <li class="">
                  <a href="<?php echo site_url("sales/list_sales") ?>">
                  <i class="fa fa-list"></i>
                  <span class="title">Listar Ventas</span>
                  </a>
               </li>
               <li>
                  <a href="javascript:elimiar_sesion();">
                  <i class="fa fa-power-off"></i>
                  <span class="title">Salir</span>
                  </a>
               </li>
            </ul>
            <!-- END SIDEBAR MENU -->
         </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
         <div class="page-content" id="content-page">
            <div class="page-head">
               <!-- PAGE TOOLBAR -->
               <!-- BEGIN PAGE TITLE -->
               <div class="page-title">
                  <h1>
                     <i class="fa fa-lock"></i>
                     <?php echo lang('sales_closing_amount')?>			
                  </h1>
               </div>
               <!-- END PAGE TITLE -->		
            </div>
            <!-- END PAGE HEAD -->
            <!-- BEGIN PAGE BREADCRUMB -->
            <div id="breadcrumb" class="hidden-print">
               <a href="<?php echo site_url("sales/offline") ?>"><i class="fa fa-home"></i> Panel</a><a href="<?php echo site_url("sales/closing_amount_offline") ?>">Ventas</a>	
            </div>
            <!-- END PAGE BREADCRUMB -->
            <div class="clear"></div>
                <div class="row">
                    <div class="col-md-12">
                         <!-- BEGIN CONDENSED TABLE PORTLET-->
                        <div class="portlet box green" id="portlet-content">
                            <div class="portlet-title">
                                <div class="caption">						
                                    <?php echo lang('sales_closing_amount')?>					
                                </div>
                            </div>
                            <div class="portlet-body">
                                <h5> <strong>*Antes de sincronizar las ventas OffLine debe de cerrar todas las cajas abiertas desde el módulo de ventas OffLine.</strong>
                                </h5>
                                <div class="row">                              
                                    <div class="col-md-6" id="contenedor">  
                                     </div>
                                     <div class="col-md-12" >  
                                        <button id="enviar" onclick="sincronizar_ventas(1,1,1)" style=" display: none" class="btn" >Sincronizar e ir a ventas OffLine </button>
                                     </div>
                                </div>
                       
                            </div>
                        </div>
               
      

      <script>
         jQuery(document).ready(function () {
         Metronic.init(); // init metronic core componets
         Layout.init(); // init layout
         Demo.init(); // init demo features
         ComponentsDropdowns.init();
         
         
         });
      </script>

      <script>
            window.addEventListener('online', handleConnectionChange);
            var csfrData={};csfrData['<?php echo $this->security->get_csrf_token_name();?>']=
            $.cookie('csrf_test_name')===undefined ?  $.cookie('csrf_cookie_name'):$.cookie('csrf_test_name') ;$(function(){$.ajaxSetup({data: csfrData});});	
      

         $(document).ready(function () {
             (async function (){
                  carga_location_select();  
                
            
            })(); 
           
         });
         mostar_cajas_abiertas();
     
      var cash_register;
      async function _close_submit(elemento, register_id,register_log_id){
          let cash_close_forma= $("#closing_amount"+register_id).val();
          
           cash_register= await objSale.get_register_log_by_id(register_log_id);
           let cash_close =cash_register.cash_sales_amount;
              
          if($.isNumeric(cash_close_forma)){
                if(Number(cash_close_forma)==Number(cash_close) || (await to_currency(cash_close))== cash_close_forma)
			    {                
                    (async function(){
                        await objSale.close_register(cash_register,cash_close_forma,register_id);	
                        localStorage.removeItem("cart");
                        localStorage.removeItem("payments");
                        localStorage.removeItem("customer");
                        localStorage.removeItem("sold_by_employee_id");
                        await mostar_cajas_abiertas();
                    })();
                   
		    	}
			    else
			    {
                    $.confirm({
                        title: 'Confirmar!',
                        content: 'La cantidad de cierre no coincide con el saldo de registro, ¿Estás seguro de continuar?',
                        buttons: {                            
                            confirmar: function () {
                                (async function(){
                                   await  objSale.close_register(cash_register,cash_close_forma,register_id);	
                                   localStorage.removeItem("cart");
                                   localStorage.removeItem("payments");
                                   localStorage.removeItem("customer");
                                   localStorage.removeItem("sold_by_employee_id");
                                   await mostar_cajas_abiertas();
                                   
                                 })()
                            },
                            cancelar: function () { },
                            
                        }
                     });
									
			    }
                }else{
                    $.alert({
                        title: 'Error!',
                        content: 'Por favor, introduzca un valor.!',
                    });
                }
            
      }
      async function  mostar_cajas_abiertas(){
        $("#contenedor").html("");
        var html="";
               let register_open=  await objSale.get_register_log_open_offline();
                if(register_open.length>0){
                    for(let i in register_open ){
                        let cash_close =register_open[i].cash_sales_amount;
                        let cash_close_forma= await to_currency(cash_close);
                        let nombre_caja= await objRegister.get_register_name(register_open[i].register_id);
                        
                        html='<div class ="contenedor" >'+
                            //'<form action="#" method="post" >'+
                                '<h2 class="text-success " >CAJA: '+nombre_caja+'</h2>'+
                                    '<h3 class="text-success " > Usted debe tener '+cash_close_forma +' en el registro.'+'</h3>'+
                                    ' <div class="form-group">'+
                                        ' <label for="closing_amount" class="control-label">Monto de cierre:</label>'+									
                                            '<div class="input-group">'+
                                                '<span class="input-group-addon">'+
                                                    '<i class="fa fa-dollar"></i>'+
                                                '</span>'+
                                                '<input type="Number" name="closing_amount" id="closing_amount'+register_open[i].register_id+'" value=""  required="" class="form-control form-inps">   '  +                                    
                                                    '<span class="input-group-btn">'+
                                                            '<button name="submit" type="button"  onclick="_close_submit(this,'+register_open[i].register_id+','+register_open[i].register_log_id+')" class="btn btn-success">Aceptar</button>   '   +                                          
                                                    '</span>'+
                                            '</div>' +                                  
                                        '</div>'+                                 
                                '</div>'+
                                //'</form>'+
                        '</div>';
                        $("#contenedor").append(html);

                    }
                }else{
                    $("#enviar").show();
                }
      }
      </script>
      <script src="<?php echo base_url();?>js/offline/data_navbar.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>

      

<?php $this->load->view("partial/footer_Offline"); ?>