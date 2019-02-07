
<?php
session_start();
if (!isset($_SESSION['person_id']) || $_SESSION['person_id'] == false) {
    header("Location: /pos");
    die();
}?>
<html   >

<head>

    <title>Pos-offLine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <base href='../'>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- BEGIN CSS -->
    <link href="css/fonts-google.css" rel="stylesheet" type="text/css">
    <!-- IMPORTANTE -->
    <link href="css/ionicons.min.css" rel="stylesheet" type="text/css">
    <!-- IMPORTANTE -->
    <link href="bin/bin.min.css" rel="stylesheet" type="text/css">

    <!-- BEGIN JAVASCRIPT-->
    <script src="bin/bin.min.js" type="text/javascript"></script>
    <script src="js/lib/idb.js" type="text/javascript"></script>
    <script src="js/offline/indexed.js" type="text/javascript"></script>
    <script src="js/offline/language.js" type="text/javascript"></script>
    <script src="js/offline/item.js" type="text/javascript"></script>
    <script src="js/offline/item_location.js" type="text/javascript"></script>
    <script src="js/offline/appconfig.js" type="text/javascript"></script>
    <script src="js/offline/accounting.min.js" type="text/javascript"></script>
    <script src="js/offline/strtotime.js" type="text/javascript"></script>
    <script src="js/offline/item_kit.js" type="text/javascript"></script>
    <script src="js/offline/gifcard.js" type="text/javascript"></script>
    <script src="js/offline/item_kit_item.js" type="text/javascript"></script>
    <script src="js/offline/item_kit_location.js" type="text/javascript"></script>
    <script src="js/offline/employee.js" type="text/javascript"></script>
    <script src="js/offline/item_taxes_finder.js" type="text/javascript"></script>
    <script src="js/offline/items_helper.js" type="text/javascript"></script>
    <script src="js/offline/item_location_taxes.js" type="text/javascript"></script>
    <script src="js/offline/item_kit_taxes_finder.js" type="text/javascript"></script>
    <script src="js/offline/item_kits_helper.js" type="text/javascript"></script>
    <script src="js/offline/item_kit_location_taxes.js" type="text/javascript"></script>
    <script src="js/offline/item_kit_taxes.js" type="text/javascript"></script>
    <script src="js/offline/location.js" type="text/javascript"></script>
    <script src="js/offline/item_taxes.js" type="text/javascript"></script>
    <script src="js/offline/customer.js" type="text/javascript"></script>
    <script src="js/offline/tier.js" type="text/javascript"></script>
    <script src="js/offline/sale_helper.js" type="text/javascript"></script>
    <script src="js/offline/items_subcategory.js" type="text/javascript"></script>
    <script src="js/offline/helper_data_sesion.js" type="text/javascript"></script>
    <script src="js/offline/sale.js" type="text/javascript"></script>

    <script src="js/offline/sales.js" type="text/javascript"></script>

</head>


<body class="page-md page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo page-sidebar-closed">
    <div class="modal fade in" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>
    <!-- BEGIN HEADER -->
    <div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="/test/index.php/home">
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
                        <button class="btn green" id="btn-offline">
                            <span class="hidden-sm hidden-xs">Offline&nbsp;</span>
                            <i class="fa fa-plug"></i>
                        </button>
                    </div>

                    <!-- Dropdown Locations -->
                    <div class="btn-group">

                        <form action="" method="post" accept-charset="utf-8"
                            id="form_set_employee_current_location_id">

                            <select name="employee_current_location_id" id="employee_current_location_id" class="bs-select form-control"
                                style="display: none;">
                                <option value="1" selected="selected">Tienda Principal</option>
                                <option value="2">Tienda2</option>
                                <option value="3">Tienda 3</option>
                            </select>

                        </form>

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
                                <span class="flag-icon flag-icon-pa" title="Panamá"></span>
                            </h3>
                        </li>

                        <li class="info-time">
                            <h6 id="time">
                                <i class="icon fa fa-clock-o"></i>
                                <span class="text">
                                    09:55 07/18/2018 </span>
                            </h6>
                        </li>

                        <li class="separator hide">
                        </li>

                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user dropdown-dark">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span class="username username-hide-on-mobile">
                                    Fredys jose Cardenas </span>
                                <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">


                                <li class="divider">
                                </li>
                                <li>
                                    <a href="">
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
                        <a href="index.php/sales">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="title">Ventas</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php/home/logout">
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

                    <div id="sale-grid-big-wrapper" class="clearfix hidden">
                        <div class="clearfix" id="category_item_selection_wrapper">
                            <div class="panel panel-success no_margin_bottom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Panel de Items</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar categories">&nbsp;
                                        <strong>1</strong>&nbsp;
                                        <a href="http://localhost/pos/index.php/sales/categories/15">2</a>&nbsp;
                                        <a href="http://localhost/pos/index.php/sales/categories/15">&gt;</a>&nbsp;</div>
                                    <div id="category_item_selection" class="row">
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p></p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>1</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>433</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>999</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>8888</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>23424</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>33443</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>44554</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>45454</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>455445</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>Carnes</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>Dfgf</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>Frutas</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>Ghhghghhghg</p>
                                        </a>
                                        <a class="btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                            <p>Granos</p>
                                        </a>
                                    </div>
                                    <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar categories">&nbsp;
                                        <strong>1</strong>&nbsp;
                                        <a href="http://localhost/pos/index.php/sales/categories/15">2</a>&nbsp;
                                        <a href="http://localhost/pos/index.php/sales/categories/15">&gt;</a>&nbsp;</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="register_container" class="sales clearfix">


                        <div class="row">
                            <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                                <div class="portlet light register-items margin-bottom-15">
                                    <div id="ajax-loader" style="display: none;">por favor espere ...
                                        <img src="" alt="">
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                                <form action="" method="post" accept-charset="utf-8" id="add_item_form" class=""
                                                    autocomplete="off">

                                                    <div class="input-group">

                                                        <input type="text" name="item" value="Ingresa un artículo o de código de barras escaneado" id="item" class="form-control form-inps-sale ui-autocomplete-input"
                                                            accesskey="k" tabindex="1" placeholder="Ingresa un artículo o de código de barras escaneado"
                                                            autocomplete="off">
                                                        <span class="input-group-btn">
                                                            <a href="javascript:void(0);" class="btn btn-success show-grid">
                                                                <i class="icon-magnifier-add hidden-lg"></i>
                                                                <span class="visible-lg">Mostrar Artículos</span>
                                                            </a>
                                                            <a href="javascript:void(0);" class="btn btn-success hide-grid hidden">
                                                                <i class="icon-magnifier-remove hidden-lg"></i>
                                                                <span class="visible-lg">Ocultar Artículos</span>
                                                            </a>
                                                        </span>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <form action="http://localhost/pos/index.php/sales/change_mode" method="post" accept-charset="utf-8" id="mode_form" autocomplete="off">
                                                    <div style="display:none">
                                                        <input type="hidden" name="csrf_test_name" value="a82aa4a232a0536fa224de760b2a711c">
                                                    </div>
                                                    <select name="mode" id="mode" class="bs-select form-control" style="display: none;">
                                                        <option value="sale" selected="selected">Venta</option>
                                                        <option value="return">Devolución</option>
                                                        <option value="store_account_payment">Abono a línea de crédito</option>
                                                    </select>

                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="portlet light register-items-table margin-bottom-15">

                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-12">
                                                <div class="sale-buttons" id="sale-buttons" style="display:none">
                                                    <!-- Cancel and suspend buttons -->
                                                    <form action="javascrit:void(0)" method="post" accept-charset="utf-8" id="cancel_sale_form"
                                                        autocomplete="off">
                                                        <div style="display:none">
                                                            <input type="hidden" name="csrf_test_name" value="a82aa4a232a0536fa224de760b2a711c">
                                                        </div>
                                                        <div class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
                                                            <!--<a type="button" class="btn yellow-gold letter-space  btn-sales" id="quotes">Crear Cotización</a>-->
                                                            <a type="button" class="btn yellow-gold letter-space  btn-sales" id="suspend_sale_button">Suspender</a>
                                                            <a type="button" class="btn red-thunderbird  btn-sales" id="cancel_sale_button">Cancelar</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-12 pull-right">
                                                <div class="btn-group pull-right">
                                                    <select name="table_number" id="table_number" class="bs-select form-control" style="display: none;">
                                                        <option value="0" selected="selected">Seleccione Mesa</option>
                                                        <option value="1">Mesa # 1</option>

                                                    </select>

                                                    <button style="width: 100%" class="btn btn-sm btn-success dropdown-toggle tooltips "
                                                        data-original-title="Dar clic para ver más opciones " data-toggle="dropdown">Más opciones
                                                        <i class="fa fa-angle-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="http://localhost/pos/index.php/sales/suspended" class="" title="Ventas Suspendidas">Ventas Suspendidas</a>
                                                        </li>
                                                        <li>
                                                            <a href="http://localhost/pos/index.php/sales/quoteses" class="" title="Cotizaciones">Cotizaciones</a>
                                                        </li>
                                                        <li>
                                                            <a href="http://localhost/pos/index.php/reports/detailed_sales/2018-07-18 00:00:00/2018-07-18 23:59:59/all/0/-1" class=""
                                                                target="_blank" title="Ventas realizadas hoy">Ventas del día</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="register-items-holder">
                                        <div class="floatThead-wrapper" style="position: relative; clear:both;">

                                            <div class="table-scrollable" style="height: 410px;">
                                                <table id="register" class="table table-advance table-bordered table-custom">


                                                    <thead id ="cabeza">
                                                        <tr class="size-row" style="height: 36px;">

                                                        </tr>
                                                    </thead>
                                                    <tbody id="cart_contents" class="register-item-content">

                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <div class="md-checkbox-inline">
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="show_comment_on_receipt" value="1" id="show_comment_on_receipt"
                                                        class="md-check">
                                                    <label id="show_comment_on_receipt_label" for="show_comment_on_receipt">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>Incluir comentarios en la factura</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <div class="md-checkbox-inline">
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="show_receipt" value="1" id="show_receipt" class="md-check">
                                                    <label id="show_receipt_label" for="show_receipt">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>No imprimir factura</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" name="show_comment_ticket" value="0" id="show_comment_ticket"
                                                        class="md-check">
                                                    <label id="show_comment_ticket" for="show_comment_ticket">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span><div id="topo_boleta"></div></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-1 col-md-3 col-sm-12 col-xs-12" id="hidde_show_comment_ticket">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" name="show_comment_ticket" value="1" checked="checked"
                                                        id="show_comment_invoice" class="md-check">
                                                    <label id="show_comment_invoice" for="show_comment_invoice">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span><div id="topo_factura"></div></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row col-md-4 pull-right">
                                                <div class="sale-buttons ">
                                                    <!-- Cancel and suspend buttons -->
                                                    <form action="http://localhost/pos/index.php/sales/cancel_sale" method="post" accept-charset="utf-8" id="cancel_sale_form"
                                                        autocomplete="off">

                                                        <div class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="" id="global_discount">
                                                <form action="javascrit:void(0)" method="post" accept-charset="utf-8" id="discount_all_form"
                                                    class="form-horizontal" autocomplete="off">

                                                    <div class="form-group no_margin_bottom">
                                                        <label class="col-md-7 col-sm-12 col-xs-12 control-label" id="discount_all_percent_label"
                                                            for="discount_all_percent">Descuento en toda la Venta: </label>
                                                        <div class="col-md-5 col-sm-12 col-xs-12">
                                                            <div class="input-group">
                                                                <div class="input-icon right">
                                                                    <i class="icon-percent"></i>
                                                                    <input type="text" name="discount_all_percent" value=""
                                                                        size="3" class="form-control" id="discount_all_percent">
                                                                </div>
                                                                <span class="input-group-btn">
                                                                    <button name="submit_discount_form" type="submit" class="btn btn-success">Aceptar</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                            <!-- BEGIN RIGHT SMALL BOX  -->
                            <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 no-padding-left sale_register_rightbox" id="box-customers">
                                <!-- BEGIN SMALL BOX CUSTOMER -->
                                <div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
                                    <div class="portlet-title padding">
                                        <div class="caption">
                                            <i class="fa fa-user"></i>
                                            <span class="caption-subject bold">
                                                Seleccionar Cliente (Opcional) </span>
                                            <a class="icon fa fa-youtube-play help_button" id="maxsales" data-toggle="modal" data-target="#stack5"></a>
                                        </div>

                                    </div>
                                    <div class="portlet-body padding">
                                        <div class="customer-search">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a href="javascrit:void(0)" class="btn btn-success tooltips" data-original-title="Cliente"
                                                        title="" id="new-customer">
                                                        <i class="icon-user-follow hidden-lg"></i>
                                                        <span class="visible-lg">Cliente </span>
                                                    </a>
                                                </span>
                                                <form action="javascrit:void(0)" method="post" accept-charset="utf-8" id="select_customer_form"
                                                    autocomplete="off">

                                                    <input type="text" name="customer" value="Empieza a escribir el nombre del cliente..."
                                                        id="customer" class="form-control form-inps-sale ui-autocomplete-input"
                                                        size="30" placeholder="Empieza a escribir el nombre del cliente..." accesskey="c"
                                                        autocomplete="off"> </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- END SMALL BOX CUSTOMERS -->

                                <!-- BEGIN SMALL BOX SALE SUMMARY-->
                                <div class="portlet light margin-top-15 no-padding">
                                    <div class="portlet-title padding">
                                        <div class="caption">
                                            <i class="icon-basket-loaded"></i>
                                            <span class="caption-subject bold">
                                                Resumen de la venta </span>
                                        </div>
                                        <div class="options">
                                            <a href="" data-toggle="modal" data-target="#myModal" class="pull-right tooltips"
                                                id="opener" data-original-title="Atajos de teclado">
                                                <i class="fa fa-keyboard-o" style="font-size: 30px"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <ul class="list-group">
                                        <li id="tiers" class="list-group-item tier-style" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="name-tier">Tiers artículo</span>
                                                </div>
                                                <div class="col-md-6" id="tiers-body">

                                                </div>
                                            </div>
                                        </li>
                                        <div class="item-subtotal-block">
                                            <div class="num_items amount">
                                                <span class="name-item-summary">
                                                    N° de artículos:
                                                </span>
                                                <span id="n-articulos" class="pull-right badge badge-cart badge-success">
                                                     </span>
                                            </div>
                                            <div class="subtotal">
                                                <span class="name-item-summary">
                                                    Subtotal:
                                                </span>
                                                <span id="subtotal"  class="pull-right name-item-summary">
                                                    <strong></strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div id="ivas">

                                        </div>
                                    </ul>


                                    <div class="amount-block">
                                        <div class="total amount">
                                            <div class="side-heading">
                                                Total:
                                            </div>
                                            <div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2"></div>
                                        </div>
                                        <div class="total">
                                            <div class="side-heading">
                                                Cantidad a pagar:
                                            </div>
                                            <div id="amount-due" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2"></div>
                                        </div>
                                    </div>

                                    <div class="payments" id="payments">
                                        <ul class="list-group" id="payments_list">


                                        </ul>
                                    </div>

                                    <!-- BEGIN ADD PAYMENT -->
                                    <div class="add-payment">

                                        <form action="javascript:void(0)" method="post" accept-charset="utf-8" id="add_payment_form"
                                            autocomplete="off">

                                            <ul class="list-group">
                                                <li class="list-group-item no-border-top tier-style" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="name-addpay">
                                                                <a class="sales_help tooltips" data-placement="left" title="" data-original-title="Aquí puedes poner el tipo de pago  y puedes pagar con diferentes tipos de pagos ">Agregar Pago </a>:
                                                            </span>
                                                        </div>
                                                        <div class="col-md-6" id="payment_body">

                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input type="text" name="amount_tendered" value="0.00" id="amount_tendered" class="form-control form-inps-sale" accesskey="p">
                                                                <span class="input-group-btn">
                                                                    <input class="btn btn-success" type="button" id="add_payment_button" value="Agregar Pago">
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </li>
                                            </ul>
                                        </form>
                                    </div>
                                    <!-- END ADD PAYMENT -->

                                    <div class="comment-sale">
                                        <ul class="list-group">
                                            <li class="list-group-item no-border-top tier-style" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
                                                <div id="finish_sale">
                                                    <form action="#" method="post" accept-charset="utf-8" id="finish_sale_form" autocomplete="off">

                                                        <input type="button" class="btn btn-success btn-large btn-block" style="display: none"
                                                            id="finish_sale_button" value="Completar Venta"> </form>
                                                </div>

                                                <div id="container_comment" class="hidden">
                                                    <div class="title-heading" <label="" id="comment_label" for="comment">Comentarios:</div>
                                                    <textarea name="comment" cols="40" rows="2" id="comment"
                                                        class="form-control form-textarea" accesskey="o"></textarea>
                                                </div>
                                                <div id="mesas-2-body"  style="display: none">
                                                <div class="title-heading" >
                                                 <label id="comment_label" for="comment">Número de Mesa:</div>
                                                 <div id="body-select">

                                                     </div>



                                                </select>
                                                </div>


                                            </li>
                                        </ul>
                                    </div>
                                    <div id="finish_sale" class="finish-sale">

                                    </div>
                                </div>
                                <!-- END SMALL BOX SALE SUMMARY -->

                            </div>
                            <!-- END RIGHT SMALL BOX -->

                        </div>

                        <script type="text/javascript" language="javascript">


                        // permite egregar los eventos dinamicos cuando se agregan las filas de la tabla del carrito
                        function controlador_eventos() {
                           //eliminamos los eventos
                            $('.delete_item').off('click');
                            $('#tier_id').off('change');
                            $('#add_payment_button').off('click');
                            $('.line_item_form').off('submit');
                            $('#add_payment_form').off('submit');
                            $("#cart_contents input").off("change");

                            $( ".select_custom_subcategory" ).off("change");
                            // se agregan de nuevo los elementos

                            $('.delete_item').click(function (event) {
                                    event.preventDefault();
                                    var line=$(this).attr('href');
                                    delete_item(line);
                            }  );
                            $("#tier_id").change(async function () {
                                await set_tier_id($("#tier_id").val());
                                armar_vista()

                                });

                                $("#add_payment_button").click(async function()
			                    {
                                    salesBeforeSubmit();
                                  let data= Array();
                                  data["payment_type"]=$("#payment_types").val();
                                  data["amount_tendered"]=$("#amount_tendered").val();
                                  await _add_payment(data);
                                  salesafterSubmit();

                                });
                                $("#add_payment_form").submit(async function (event) {
                                    event.preventDefault();
                                    $("#add_payment_button").click();
                                });
                                $(".line_item_form").submit(async function (event) {
                                    event.preventDefault();

                                    salesBeforeSubmit();
                                    let href=$(this).attr('action');
                                    let line_idItem=href.split('/');
                                    let line = line_idItem[0];
                                    let id_item = line_idItem.length==2 ? line_idItem[1]:false;
                                    let data=$(this).serializeArray();
                                    await editar_producto(line, id_item,data);
                                    salesafterSubmit();

                                });
                                $("#cart_contents input").change(function()
			                    {
                                    $($(this).parent()).submit();
			                    });
                                $( ".select_custom_subcategory" ).change(function() {

                                    $($(this).parent()).submit();
                                });

                        }
                        //id de la tienda en donde se incio sesion o cuando bambia
                        (async function (){
                            await set_person_id(1);
                            await set_ubicacion_id(1);
                        })()



                            /*function count(options) {
                                var $this = $(this);
                                options = $.extend({}, options || {}, $this.data('countToOptions') || {});
                                $this.countTo(options);
                            }*/

                            //Remove attr small box sale summary
                            //$('.list-group-item:first-child').css('border-top-left-radius', '0').css('border-top-right-radius', '0');
                            //$('.list-group-item').css('border', '0');

                            //
                            $(document).ready(function () {


                                armar_vista();
                                (async function(){
                                    set_comment_ticket(await  objAppconfig.item('default_sales_type'));
                                })()



                                if ($("#show_comment_on_receipt").is(":checked") == true) {
                                    $("#container_comment").removeClass('hidden');
                                }
                                $("#show_comment_on_receipt").click(function (event) {

                                    if ($(this).is(":checked")) {
                                        $("#container_comment").removeClass('hidden');
                                    }
                                    else {
                                        $("#container_comment").addClass('hidden');
                                    }
                                });

                                //Acomodar la tabla dependiendo la resolucion y el contenedor de customers en el padding izquierdo

                                //$(window).bind("resize",function(){
                                var size = $(this).width();
                                if (size <= 1280) {
                                    $('.table-scrollable').css('height', '360px');
                                }
                                else if (size > 1280 && size <= 1366) {
                                    $('.table-scrollable').css('height', '410px');
                                }

                                if (size < 992) {
                                    $('#box-customers').removeClass('no-padding-left')
                                }
                                else {
                                    $('#box-customers').addClass('no-padding-left')
                                }

                                $('#add_item_form').submit(function( e ) {
                                    e.preventDefault();
                                    add($("#item").val());

                                });

                                 $("#cancel_sale_button").click( function()
                                {
                                    if (confirm(lang("sales_confirm_cancel_sale")))
                                    {
                                        clear_all();

                                        location.reload();
                                    }
                                });
                                $("#discount_all_form").submit( async function (event) {
                                    if ($.isNumeric($("#discount_all_percent").val())) {
                                        salesBeforeSubmit();
                                        discount_all($("#discount_all_percent").val());
                                        await armar_vista();
                                        $("#discount_all_percent").val("");
                                        salesafterSubmit();

                                    }
                                });
                                $('#comment').change(function()
			                    {
                                    set_comment($("#comment").val())
                                });

                                $('#show_comment_on_receipt').change(function()
	                            {
		                    		set_comment_on_receipt($('#show_comment_on_receipt').is(':checked') ? '1' : '0');
                                });
                                $('#show_receipt').change(function()
			                    {
			                        set_show_receipt($('#show_receipt').is(':checked') ? '1' : '0');
                                });
                                $('#show_comment_ticket').change(function()
                                {
                                   if($('#show_comment_ticket').is(':checked')==true){
                                        set_comment_ticket(1);
                                    }
                                });


                                $("#item").autocomplete({
                                    source: ["prueba","prueb2"],
                                    delay: 300,
                                    autoFocus: false,
                                    minLength: 1,
                                    select: function (event, ui) {

                                        event.preventDefault();
                                        $("#item").val(ui.item.value);
                                        $('#add_item_form').submit();
                                    }
                                });

                                $('#item,#customer').click(function () {
                                    $(this).attr('value', '');
                                });

                                $("#customer").autocomplete({
                                    source: ["prueba","prueb2"],
                                    delay: 300,
                                    autoFocus: false,
                                    minLength: 1,
                                    select: function (event, ui) {
                                        $("#customer").val(ui.item.value);
                                    }
                                });

                                $('#customer').blur(function () {
                                    $(this).attr('value', "Empieza a escribir el nombre del cliente...");
                                });

                                $('#item').blur(function () {
                                    $(this).attr('value', "Ingresa un art\u00edculo o de c\u00f3digo de barras escaneado");
                                });


                                $("#finish_sale_button").click(function () {


                                    //Prevent double submission of form
                                    $("#finish_sale_button").hide();
                                    $("#register_container").plainOverlay('show');

                                        alert("completar venta")
                                        window.location= window.location;
                                });

                                $("input[type=text]").not(".description").click(function () {
                                    $(this).select();
                                });




                                var current_category = null;

                                function load_categories() {
                                    $.get('', function (json) {
                                        processCategoriesResult(json);
                                    }, 'json');
                                }

                                $(document).on('click', ".pagination.categories a", function (event) {
                                    $("#category_item_selection_wrapper").plainOverlay('show');
                                    event.preventDefault();
                                    var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);

                                    $.get('' + offset, function (json) {
                                        processCategoriesResult(json);

                                    }, "json");
                                });

                                $(document).on('click', ".pagination.items a", function (event) {
                                    $("#category_item_selection_wrapper").plainOverlay('show');
                                    event.preventDefault();
                                    var offset = $(this).attr('href').substring($(this).attr('href').lastIndexOf('/') + 1);

                                    $.post('' + offset, { category: current_category }, function (json) {
                                        processItemsResult(json);
                                    }, "json");
                                });

                                $('#category_item_selection_wrapper').on('click', '.category_item.category', function (event) {
                                    $("#category_item_selection_wrapper").plainOverlay('show');

                                    event.preventDefault();
                                    current_category = $(this).text();
                                    $.post('', { category: current_category }, function (json) {
                                        processItemsResult(json);
                                    }, "json");
                                });

                                $('#category_item_selection_wrapper').on('click', '.category_items.item', function (event) {
                                    $("#category_item_selection_wrapper").plainOverlay('show');
                                    event.preventDefault();
                                    $("#item").val($(this).data('id'));

                                            toastr.success("Se ha agregado correctamente el art\u00edculo", "\u00c9xito"); $("#category_item_selection_wrapper").plainOverlay('hide');
                                            $('.show-grid').addClass('hidden');
                                            $('.hide-grid').removeClass('hidden');

                                });

                                $("#category_item_selection_wrapper").on('click', '#back_to_categories', function (event) {
                                    $("#category_item_selection_wrapper").plainOverlay('show');

                                    event.preventDefault();
                                    load_categories();
                                });

                                $("#new-customer").click(function () {
                                    $("body").plainOverlay('show');
                                });
                            // load_categories();
                            });
                            //
                            $(document).keydown(function (event) {
                                var mycode = event.keyCode;

                                if (mycode == 113) {
                                    $("#item").focus();
                                }

                                //F4
                                if (mycode == 115) {
                                    event.preventDefault();
                                    $("input[name='quantity']").focus();
                                    event.originalEvent.keyCode = 0;
                                }

                                //F7
                                if (mycode == 118) {
                                    event.preventDefault();
                                    $("#amount_tendered").focus();
                                    $("#amount_tendered").select();
                                }

                                //F8
                                if (mycode == 119) {
                                    $("#add_payment_button").click();
                                }

                                //F9
                                if (mycode == 120) {
                                    event.preventDefault();
                                    $("#finish_sale_button").click();
                                    event.originalEvent.keyCode = 0;
                                }

                                //F10
                                if (mycode == 121) {
                                    event.preventDefault();
                                    $("#customer").focus();
                                    $("#customer").select();
                                    event.originalEvent.keyCode = 0;
                                }

                                //ESC
                                if (mycode == 27) {
                                    $("#cancel_sale_button").click();
                                }


                            });

                                // Show or hide item grid
                                $(".show-grid").on('click', function (e) {
                                e.preventDefault();
                                $("#sale-grid-big-wrapper").removeClass('hidden');
                                $("#category_item_selection_wrapper").slideDown();
                                $('.show-grid').addClass('hidden');
                                $('.hide-grid').removeClass('hidden');
                            });

                            $(".hide-grid").on('click', function (e) {
                                e.preventDefault();
                                $("#category_item_selection_wrapper").slideUp();
                                $('.hide-grid').addClass('hidden');
                                $('.show-grid').removeClass('hidden');
                            });



                                setTimeout(function () { $('#item').focus(); }, 10);
                                $(document).focusin(function (event) {
                                    last_focused_id = $(event.target).attr('id');
                                });


                                if (screen.width <= 768) //set the colspan on page load
                                {
                                    jQuery('td.edit_description').attr('colspan', '2');
                                    jQuery('td.edit_serialnumber').attr('colspan', '4');
                                }

                                $(window).resize(function () {
                                    var wi = $(window).width();

                                    if (wi <= 768) {
                                        jQuery('td.edit_description').attr('colspan', '2');
                                        jQuery('td.edit_serialnumber').attr('colspan', '4');
                                    }
                                    else {
                                        jQuery('td.edit_description').attr('colspan', '4');
                                        jQuery('td.edit_serialnumber').attr('colspan', '2');
                                    }
                                });

                        </script>

                    </div>


                    <script type="text/javascript">

                    	function salesBeforeSubmit()
		                {
                            //ajax-loader
                            $("#ajax-loader").show();
                            $("#add_payment_button").hide();
                            $("#finish_sale_button").hide();

                        }
                        function salesafterSubmit()
		                {
                            //ajax-loader
                            $("#ajax-loader").hide();
                            $("#add_payment_button").show();
                            $("#finish_sale_button").show();

                        }
                        function processItemsResult(json) {
                            $("#category_item_selection_wrapper .pagination").removeClass('categories').addClass('items');
                            $("#category_item_selection_wrapper .pagination").html(json.pagination);
                            $("#category_item_selection").html('');
                            var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class', 'category_item back-to-categories col-md-2 col-sm-3 col-xs-6 ').append('<p>&laquo; ' + "Volver a categor\u00edas" + '</p>');
                            $("#category_item_selection").append(back_to_categories_button);
                            for (var k = 0; k < json.items.length; k++) {
                                var image_src = json.items[k].image_src ? json.items[k].image_src : "http://localhost/pos/img/no-photo.jpg";
                                var prod_image = "";
                                var item_parent_class = "";
                                if (image_src != '') {
                                   var item_parent_class = "item_parent_class";
                                    var prod_image = '<a class="btn grey-gallery"><img class="img-thumbnail" style="width:100%; height:60px;" src="' + image_src + '" alt="" />';
                                }
                                var item = $("<div/>").attr('class', 'category_items item space-item col-lg-2 col-md-2 col-sm-3 col-xs-6  ' + item_parent_class).attr('data-id', json.items[k].id).append(prod_image + '<p class="letter-space-item">' + json.items[k].name + '</p>' + '</a>');
                                $("#category_item_selection").append(item);
                                var d_id = json.items[k].id;
                            }
                            $("#category_item_selection_wrapper").plainOverlay('hide');
                        }
                        function processCategoriesResult(json) {
                           $("#category_item_selection_wrapper .pagination").removeClass('items').addClass('categories');
                            $("#category_item_selection_wrapper .pagination").html(json.pagination);
                            $("#category_item_selection").html('');
                            for (var k = 0; k < json.categories.length; k++) {
                                var category_item = $("<a/>").attr('class', 'btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6').append('<p>' + json.categories[k] + '</p>');
                                $("#category_item_selection").append(category_item);
                            }
                            $("#category_item_selection_wrapper").plainOverlay('hide');
                        }

                        var last_focused_id = null;
                        setTimeout(function () { $('#item').focus(); }, 10);
                    </script>

                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">
                Todos los derechos reservados POS Ingeniando Web
                <a href="http://www.ingeniandoweb.com" target="_blank">
                </a>
                para más información visita nuestra página:
                <a target="_blank" href="http://easypos.com/">easypos.com</a>.
                <span class="text-primary">Estás usando Punto de venta POS Ingeniando Web Versión
                    <span class="label label-info"> 4.6.0</span>
                </span>
            </div>
            <div class="scroll-to-top" style="display: none;">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->




    </div>
    <!-- <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-7" tabindex="0" style="display: none;"></ul>
    <span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span>
    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-8" tabindex="0" style="display: none;"></ul>
    <span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></span>
    <div class="KeyTips__popup" style="left: 237px; top: 69px;">k</div>
    <div class="KeyTips__popup" style="left: 1061px; top: 96px;">c</div>
    <div class="KeyTips__popup" style="left: 936px; top: 505px;">p</div>-->


    <script>
        jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        Demo.init(); // init demo features
        ComponentsDropdowns.init();
        });
    </script>
</body>

</html>