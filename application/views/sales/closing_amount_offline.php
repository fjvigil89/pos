
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

    <script type="text/javascript">
        validation_indexedDB_browser();
        validation_session_employee();
    </script>
      <script type="text/javascript">
			var SITE_URL= "<?php echo site_url(); ?>";
            var BASE_URL = '<?= base_url();?>';
            es_db_actualizada(BASE_URL);
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
                  <div class="btn-group pull-right">
                        <a href="javascript:sincronizar_ventas();" class="btn green" id="btn-sincronizar" style="display:none">
                            <span class="hidden-sm hidden-xs">SINCRONIZAR VENTAS&nbsp;</span>
                            <i class="fa fa-plug"></i>
                        </a>
                    
                  </div>
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
            <li >
                    <a href="<?php echo site_url("home") ?>">
								<i class="fa fa-home"></i>
								<span class="title">Inicio</span>
                    </a>
                </li >    
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
                        <h5>
                        <?php echo lang('sales_closing_amount_desc'); ?>
                         </h5>
                        <ul class="text-error" id="error_message_box"></ul>
                        <form action="#" method="post" accept-charset="utf-8" id="closing_amount_form" class="" novalidate="novalidate">
                           <div style="display:none">
                              <input type="hidden" name="csrf_test_name" value="">
                           </div>
                           <h3 class="text-success text-center" id="mostrar_valor">
                              Usted debe tener $0 en el registro.						
                           </h3>
                           <br>
                           <div class="row">
                              <!--<div class="col-md-6">
                                 <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped text-center">
                                       <thead>
                                            <tr>
												<th style="text-align: center;"><?php echo lang('sales_denomination');?></th>
												<th style="text-align: center;"><?php echo lang('reports_count');?></th>
												<th style="text-align: center;"><?php echo lang('config_currency');?></th>
											</tr>
                                       </thead>
                                       <tbody>

                                       </tbody>
                                    </table>
                                 </div>
                              </div>-->
                              <div class="col-md-6">
                                 <div class="form-group">
                                 <?php echo form_label(lang('sales_closing_amount').':', 'closing_amount',array('class'=>'control-label')); ?>									
                                 <div class="input-group">
										<span class="input-group-addon">
										<i class="fa fa-dollar"></i>
										</span>
										<?php echo form_input(array(
									        'name'=>'closing_amount',
                                            'id'=>'closing_amount',
                                            "type"=>"Number",
                                            "required"=>"",
									        'class'=>'form-control form-inps',
									        'value'=>'')
									    );?>
									    <span class="input-group-btn">
											<?php echo form_button(array(
												'name'=>'submit',
												'type'=>'button',
                                                'id'=>'close_submit',
                                                "onclick"=>"_close_submit(this)",
												'content'=>lang('common_submit'),
												'class'=>'btn btn-success')
											);?>
										</span>
									</div>
                                    <span id="error"></span>
                                 </div>
                                 
                                 <div style="clear:both;"></div>
                                 <div style="text-align: center;">
                                 <h1><?php echo lang('common_or'); ?></h1>					
									<input type="button" id="logout_without_closing" class="btn btn-primary" value="<?php echo lang('sales_logout_without_closing_register'); ?>">
									<br/><br/>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               
               
    
      <script>
         $(document).ready(function () {
             (async function (){
                  carga_location_select();                                    
             })();
         
         });
      </script>

      <script>
         jQuery(document).ready(function () {
         Metronic.init(); // init metronic core componets
         Layout.init(); // init layout
         Demo.init(); // init demo features
         ComponentsDropdowns.init();
         
         if(verificar_conexion_internet()){
            document.getElementById("btn-sincronizar").style.display = "block";
         }
         });
      </script>
      <script> var csfrData={};csfrData['<?php echo $this->security->get_csrf_token_name();?>']=
         $.cookie('csrf_test_name')===undefined ?  $.cookie('csrf_cookie_name'):$.cookie('csrf_test_name') ;$(function(){$.ajaxSetup({data: csfrData});});</script>	
      <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_location.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/appconfig.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/strtotime.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/gifcard.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit_item.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit_location.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/employee.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_taxes_finder.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/items_helper.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_location_taxes.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit_taxes_finder.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kits_helper.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit_location_taxes.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_kit_taxes.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/location.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/item_taxes.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/customer.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/tier.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/points.js" type="text/javascript"></script>    
      <script src="<?php echo base_url();?>js/offline/register_movement.js" type="text/javascript"></script>        
    <script src="<?php echo base_url();?>js/offline/cajas_empleados.js" type="text/javascript"></script> 
    
      <script src="<?php echo base_url();?>js/offline/sale_helper.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/items_subcategory.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/sale.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/register.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/sales.js" type="text/javascript"></script>    
      <script>
      var cash_register=null;
      var  cash_close=0;
      var cash_close_forma=0;
      close();
      
      async function close(){
            if (!await objSale.is_register_log_open()) {
                location.href= SITE_URL +"/sales/offline";        
                return 0;
            }
             cash_register = await objSale.get_current_register_log();
             cash_close =cash_register.cash_sales_amount;
             cash_close_forma= await to_currency(cash_close);
            document.getElementById('mostrar_valor').innerHTML="Usted debe tener "+cash_close_forma+" en el registro.";
        }
        function _close_submit(elemento){
          
            if($.isNumeric($("#closing_amount").val())){
                if(Number($('#closing_amount').val())==Number(cash_close) || $('#closing_amount').val()== cash_close_forma)
			    {                
                    (async function(){
                        await objSale.close_register(cash_register,$('#closing_amount').val());	
                        clear_all();
                        location.href= SITE_URL +"/sales/offline";
                    })()
                   
		    	}
			    else
			    {
                    $.confirm({
                        title: 'Confirmar!',
                        content: 'La cantidad de cierre no coincide con el saldo de registro, ¿Estás seguro de continuar?',
                        buttons: {                            
                            confirmar: function () {
                                (async function(){
                                   await  objSale.close_register(cash_register,$('#closing_amount').val());	
                                   clear_all();
                                    location.href= SITE_URL +"/sales/offline";
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
      </script>
      <script src="<?php echo base_url();?>js/offline/data_navbar.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/offline/process_sales.js" type="text/javascript"></script>
      <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>

      <script>
        window.addEventListener('online', handleConnectionChange);
    </script>

<?php $this->load->view("partial/footer_Offline"); ?>