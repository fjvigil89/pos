
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
    <link href="<?php echo base_url();?>css/confirm/jquery-confirm.css" rel="stylesheet">

    <!-- BEGIN JAVASCRIPT-->
    <script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/lib/idb.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/dexie.min.js" type="text/javascript"></script>    

    <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/sale.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/location.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/appconfig.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/helper_character.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_taxes_finder.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_location.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/strtotime.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/gifcard.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit_item.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit_location.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/employee.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/items_helper.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_location_taxes.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit_taxes_finder.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kits_helper.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit_location_taxes.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_kit_taxes.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/item_taxes.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/customer.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/tier.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/points.js" type="text/javascript"></script>    
    <script src="<?php echo base_url();?>js/offline/sale_helper.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/items_subcategory.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>

    <script type="text/javascript">
			var SITE_URL= "<?php echo site_url(); ?>";
            var BASE_URL = '<?= base_url();?>';
            var objAppconfig = new AppConfig();
            var objItem = new Item();
            var objItem_location = new Item_location();
            var objItem_kit = new Item_kit();
            var objGifcard = new Gifcard();
            var objItem_kit_item = new Item_kit_item();
            var objItem_kit_location = new Item_kit_location();
            var objEmployee = new Employee();
            var objItems_helper = new Items_helper();
            var objItem_taxes_finder = new Item_taxes_finder();
            var objItem_location_taxes = new Item_location_taxes();
            var objItem_kit_taxes_finder = new Item_kit_taxes_finder();
            var objitem_kits_helper = new Item_kits_helper();
            var objItem_kit_location_taxes = new Item_kit_location_taxes();
            var objItem_kit_taxes = new Item_kit_taxes();
            var objLocation = new Location();
            var objItem_taxes = new Item_taxes();
            var objCustomer = new Customer();
            var objTier = new Tier();
            var objItems_subcategory = new Items_subcategory();
            var objSale = new Sale();
            var objPoints= new Points();

	  </script> 
</head>
<style>
.tamano_mm58{
	font-size:10px!important
}
.tamano_small{
	font-size:12px!important
}
.tamano_medium{
	font-size:14px!important
}
.tamano_large{
	font-size:16px!important
}
.tamano_extra_large{
	font-size:18px!important
}
</style>

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
                                <span class="text" id="fecha">
                                     </span>
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
                    <li>
                        <a href="<?php echo site_url("sales/list_sales") ?>">
                            <i class="fa fa-list"></i>
                            <span class="title">Listar Ventas</span>
                        </a>
                    </li>
                    <li>
                        
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

				<div class="portlet light no-padding-general hidden-print">
					<div class="portlet-body">
						<div class="row margin-top-15 hidden-print">
							<div class="col-lg-2 col-md-2 col-sm-6">
								<button type="button" class="btn btn-warning btn-block hidden-print" id="print_button" onclick="print_receipt()" > 
									Imprimir
								</button>
							</div>			
							<div class="col-lg-2 col-md-2 col-sm-6">
								<button class="btn btn-success btn-block hidden-print" id="new_sale_button_1" onclick="window.location='/pos/index.php/sales/offline'" >
								Nueva venta
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="portlet light no-padding-general">
					<div class="portlet-body">
						<div id="receipt_wrapper" style=" padding:<?php echo $this->config->item('padding_ticket')?>px !important;" 	>
							<div id="receipt_header">
								<div id="company_name"></div>
								<?php if($this->config->item('company_logo')) {?>
									<div id="company_logo">
										<?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?>
									</div>
								<?php } ?>
								<div id="company_dni"></div>
								<div id="company_address"></div>
								<div id="company_phone"></div>					
								<div id="website"></div>					
								<div id="sale_receipt"></div>
								<div id="sale_time"></div>					
							</div>
							<div id="receipt_general_info">								
							</div>

							<table id="receipt_items" style="font-size:10px">
								<tr id="cabeta-tabla-producto" style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
								</tr>	
							</table>

							<table id="details_taxes">
								<tr>
									<th class="text-center" style="width:100%" colspan="6"><?php echo lang('sales_details_tax'); ?></th>						
								</tr>
								<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">
									<th class="text-center" colspan="2"><?php echo lang('sales_type_tax'); ?></th>
									<th class="right_text_align" style="width:20%"><?php echo lang('sales_base_tax'); ?></th>
									<th class="right_text_align" style="width:25%"><?php echo lang('sales_price_tax'); ?></th>						
									<th class="right_text_align" style="width:18%" colspan="2"><?php echo lang('sales_value_receiving'); ?></th>
								</tr>
							</table>						
							<table id="details_payments">
								<tr>
									<th class="text-center" style="width:100%" colspan="7"><?php echo lang('sales_details_payments'); ?></th>						
								</tr>
								<tr style="border-bottom: 1px dashed #000000;border-top: 1px dashed #000000;">
									<th class="left_text_align" style="width:30%" colspan="3"><?php echo lang('sales_payment_date'); ?></th>
									<th class="right_text_align" style="width:45%" colspan="3"><?php echo lang('sales_payment_type'); ?></th>											
									<th class="right_text_align" style="width:2%" colspan="2"><?php echo lang('sales_payment_value'); ?></th>
								</tr>
							</table>		

							<table id="details_options">					
							</table>
							
							<div id="sale_company_regimen">
								<?php echo nl2br($this->config->item('company_regimen')); ?>
								<br />   
							</div>
							<div id="sale_resolution">
								<?php echo nl2br($this->config->item('resolution')); ?>
								<br />   
							</div>
                            <?php if($this->config->item('no_print_return_policy')){?>
                                <div id="sale_return_policy">
                                    <?php echo nl2br($this->config->item('return_policy')); ?>
                                    <br />   
                                </div>
                            <?php } ?>
							<div id='barcode'>
								<svg id="barcode_img"></svg>
							</div>							
							<div id="signature">								
							</div>
							<div id="domain_ing">						
								<br/>
							</div>			
						</div>
						<div id="duplicate_receipt_holder">	</div>
					</div>
				</div>

				

				<script type="text/javascript">
				
					$(document).ready(function(){
                        (async function(){
							let data =(await objSale.get_info_offline(Number(localStorage.getItem("data_imprimir")))).dato_imprimir; 
							await cargar_lang();
                            cargar_datos_compania(data);
							cargar_datos_factura(data);
                            cagar_datos_general(data);                            
							await cargar_tabla_producto(data);
							await cargar_tabla_impuesto(data),
							await cargar_tabla_forma_pago(data);
                            await cargar_detalles_pagos(data);
                            await cargar_barcode(data);
							if (!data.store_account_payment==1){
								$("#sale_company_regimen").hide();
							}
                           
                            <?php if ($this->config->item('print_after_sale')) { ?>					
							    print_receipt();
							    window.location = '<?php echo site_url("sales/offline"); ?>';					
				            <?php } ?>
						})();

							
						$("table").addClass("tamano_<?php echo $this->config->item('receipt_text_size');?>");
						$("#barcode").addClass("tamano_<?php echo $this->config->item('receipt_text_size');?>");

						

					});
						

					function print_receipt()
					{
						<?php if ($this->config->item('receipt_copies') > 1): ?>
							var receipt_amount = <?php echo $this->config->item('receipt_copies');?> - 2;
							var receipt = $('#receipt_wrapper').clone();
						$('#duplicate_receipt_holder').html(receipt);
							$("#duplicate_receipt_holder").addClass('active');
							
							for ( i = 0; i < receipt_amount; i++ )
							{
								$('<div id="duplicate_receipt_holder_'+i+'"></div>').insertAfter( "#receipt_wrapper" );
								$('#duplicate_receipt_holder_'+i).html(receipt.html());
								$("#duplicate_receipt_holder_"+i).addClass('active');
							}
							
							window.print();
						<?php else: ?>
							window.print();
						<?php endif; ?>
					}
			
					function toggle_gift_receipt()
					{
						var gift_receipt_text = <?php echo json_encode(lang('sales_gift_receipt')); ?>;
						var regular_receipt_text = <?php echo json_encode(lang('sales_regular_receipt')); ?>;
				
						if ($("#gift_receipt_button").hasClass('regular_receipt'))
						{
							$('#gift_receipt_button').addClass('gift_receipt');	 	
							$('#gift_receipt_button').removeClass('regular_receipt');
							$("#gift_receipt_button").text(gift_receipt_text);	
							$('.gift_receipt_element').show();	
						}
						else
						{
							$('#gift_receipt_button').removeClass('gift_receipt');	 	
							$('#gift_receipt_button').addClass('regular_receipt');
							$("#gift_receipt_button").text(regular_receipt_text);
							$('.gift_receipt_element').hide();	
						} 	
					}
				</script>

                   
    		</div>
 		</div>
            <!-- END CONTENT -->
		</div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <?php $this->load->view("partial/footer_Offline"); ?>        <!-- END FOOTER -->
    </div>
   

    <script>
        jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        Demo.init(); // init demo features
        ComponentsDropdowns.init();
        });
    </script>
        <script src="<?php echo base_url();?>js/offline/helper_moneda.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/offline/data_navbar.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/imprimir.js" type="text/javascript"></script>	
	<script src="<?php echo base_url();?>js/JsBarcode.all.min.js"></script>
    <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>
    <script src="<?php echo base_url();?>js/offline/validador.js"></script>

    <script>
        jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        Demo.init(); // init demo features
        ComponentsDropdowns.init();
        validation_indexedDB_browser();
        });
    </script>

</body>

</html>