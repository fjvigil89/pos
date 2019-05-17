<html>

<head>
    <title>Pos-offLine</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <base href='<?php echo base_url();?>'>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- BEGIN CSS -->
    <link href="<?php echo base_url();?>css/fonts-google.css" rel="stylesheet" type="text/css">
    <!-- IMPORTANTE -->
    <link href="<?php echo base_url();?>css/ionicons.min.css" rel="stylesheet" type="text/css">
    <!-- IMPORTANTE -->
    <link href="<?php echo base_url();?>bin/bin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>css/pagination.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>css/confirm/jquery-confirm.css" rel="stylesheet">

    <!-- BEGIN JAVASCRIPT-->
    <script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/jquery.cookie.min.js"></script>
    <script src="<?php echo base_url();?>js/lib/idb.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/dexie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/validador.js"></script>
    <script type="text/javascript">
    validation_indexedDB_browser();
    validation_session_employee();
    </script>
    <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/conexion_evento.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>
    <script type="text/javascript">
    var SITE_URL = "<?php echo site_url(); ?>";
    var BASE_URL = '<?= base_url();?>';
    es_db_actualizada(BASE_URL);
    </script>


</head>


<body class="page-md page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo page-sidebar-closed">
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
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <div class="page-top-left">
                <div class="page-actions">

                    <div class="btn-group pull-right">
                        <a href="javascript:sincronizar_ventas(1,1);" class="btn green" id="btn-sincronizar"
                            style="display:none">
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
                                <span class="text" id="fecha"></span>
                            </h6>
                        </li>

                        <li class="separator hide">
                        </li>

                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user dropdown-dark">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
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
                <ul class="page-sidebar-menu page-sidebar-menu-closed" data-keep-expanded="false"
                    data-auto-scroll="true" data-slide-speed="200">


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
                    <li class="">
                        <a href="<?php echo site_url("customers/customers_list") ?>">
                            <i class="fa fa-group"></i>
                            <span class="title">Clietes</span>
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

                    <div id="sale-grid-big-wrapper" class="clearfix hidden">
                        <div class="clearfix" id="category_item_selection_wrapper">
                            <div class="panel panel-success no_margin_bottom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Panel de Items</h3>
                                </div>
                                <div class="panel-body ">

                                    <div id="category_item_selection" class="row"></div>
                                    <div class=" margin-left-15  margin-top-5">
                                        <div class="pagination-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="register_container" class="sales clearfix">


                        <div class="row">
                            <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                                <div class="portlet light register-items margin-bottom-15">
                                    <div id="ajax-loader" style="display: none;">por favor espere ...
                                        <img src="img/ajax-loader.gif" alt="">
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                                <form action="" method="post" accept-charset="utf-8" id="add_item_form"
                                                    class="" autocomplete="off">

                                                    <div class="input-group">

                                                        <input type="text" name="item" id="item"
                                                            class="form-control form-inps-sale ui-autocomplete-input"
                                                            accesskey="k" tabindex="1"
                                                            placeholder="Ingresa un artículo o de código de barras escaneado"
                                                            autocomplete="off">
                                                        <input type="hidden" name="no_valida_por_id" value="0">

                                                        <span class="input-group-btn">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-success show-grid">
                                                                <i class="icon-magnifier-add hidden-lg"></i>
                                                                <span class="visible-lg">Mostrar Artículos</span>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-success hide-grid hidden">
                                                                <i class="icon-magnifier-remove hidden-lg"></i>
                                                                <span class="visible-lg">Ocultar Artículos</span>
                                                            </a>
                                                        </span>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                                <form action="#" method="post" accept-charset="utf-8" id="mode_form"
                                                    autocomplete="off">
                                                    <div style="display:none">
                                                        <input type="hidden" name="csrf_test_name"
                                                            value="a82aa4a232a0536fa224de760b2a711c">
                                                    </div>
                                                    <select name="mode" id="mode" class="bs-select form-control"
                                                        style="display: none;">
                                                        <option value="sale" selected="selected">Venta</option>
                                                        <!-- <option value="return">Devolución</option>
                                                        <option value="store_account_payment">Abono a línea de crédito</option>-->
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
                                                    <form action="javascrit:void(0)" method="post"
                                                        accept-charset="utf-8" id="cancel_sale_form" autocomplete="off">
                                                       
                                                        <div
                                                            class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
                                                            <!--<a type="button" class="btn yellow-gold letter-space  btn-sales" id="quotes">Crear Cotización</a>-->
                                                            <!--<a type="button"
                                                                class="btn yellow-gold letter-space  btn-sales"
                                                                id="suspend_sale_button">Suspender</a>-->
                                                            <a type="button" class="btn red-thunderbird  btn-sales"
                                                                id="cancel_sale_button">Cancelar</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-12 pull-right">
                                                <div class="btn-group pull-right">
                                                    <!--<select name="table_number" id="table_number" class="bs-select form-control" style="display: none;">
                                                        <option value="0" selected="selected">Seleccione Mesa</option>
                                                        <option value="1">Mesa # 1</option>

                                                    </select>-->

                                                    <button style="width: 100%"
                                                        class="btn btn-sm btn-success dropdown-toggle tooltips "
                                                        data-original-title="Dar clic para ver más opciones "
                                                        data-toggle="dropdown">Más opciones
                                                        <i class="fa fa-angle-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="<?php echo site_url("sales/closing_amount_offline") ?>"
                                                                class=""><?=lang('sales_close_register')?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:ir_offline();" id="btn-ir-offline">
                                                                Ir a ventas OnLine
                                                            </a>
                                                        </li>
                                                        <li>
                                                        <a href="javascrit:void(0)"  onclick="show_modal('sales/sale_seriales_offline_modal','Búsqueda por serial')" ><?=lang("sale_search_by_serial")?>
                                                        </a>

                                                    </li>
                                                        <li>
                                                            <a href="<?php echo site_url("sales/list_sales") ?>"
                                                                class=""><?=lang('sale_sales_day')?>
                                                            </a>
                                                        </li>                                                        

                                                        <li>
                                                            <a href="<?php echo site_url("sales/open_money_drawer_offline") ?>"
                                                                class=""><?=lang('sales_open_money_drawer')?></a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="register-items-holder">
                                        <div class="floatThead-wrapper" style="position: relative; clear:both;">

                                            <div class="table-scrollable" style="height: 410px;">
                                                <table id="register"
                                                    class="table table-advance table-bordered table-custom">


                                                    <thead id="cabeza">
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
                                                    <input type="checkbox" name="show_comment_on_receipt" value="1"
                                                        id="show_comment_on_receipt" class="md-check">
                                                    <label id="show_comment_on_receipt_label"
                                                        for="show_comment_on_receipt">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>Incluir comentarios en la
                                                        factura</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <div class="md-checkbox-inline">
                                                <div class="md-checkbox">
                                                    <input type="checkbox" name="show_receipt" value="1"
                                                        id="show_receipt" class="md-check">
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
                                                    <input type="radio" name="show_comment_ticket" value="0"
                                                        id="show_comment_ticket" class="md-check">
                                                    <label id="show_comment_ticket" for="show_comment_ticket">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <div id="topo_boleta"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-1 col-md-3 col-sm-12 col-xs-12"
                                            id="hidde_show_comment_ticket">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" name="show_comment_ticket" value="1"
                                                        checked="checked" id="show_comment_invoice" class="md-check">
                                                    <label id="show_comment_invoice" for="show_comment_invoice">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <div id="topo_factura"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row col-md-4 pull-right">
                                                <div class="sale-buttons ">
                                                    <!-- Cancel and suspend buttons -->
                                                    <form action="" method="post" accept-charset="utf-8"
                                                        id="cancel_sale_form" autocomplete="off">

                                                        <div
                                                            class="btn-group btn-group-sm btn-group-solid btn-group-justified ">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="" id="global_discount">
                                                <form action="javascrit:void(0)" method="post" accept-charset="utf-8"
                                                    id="discount_all_form" class="form-horizontal" autocomplete="off">

                                                    <div class="form-group no_margin_bottom">
                                                        <label class="col-md-7 col-sm-12 col-xs-12 control-label"
                                                            id="discount_all_percent_label"
                                                            for="discount_all_percent">Descuento en toda la Venta:
                                                        </label>
                                                        <div class="col-md-5 col-sm-12 col-xs-12">
                                                            <div class="input-group">
                                                                <div class="input-icon right">
                                                                    <i class="icon-percent"></i>
                                                                    <input type="text" name="discount_all_percent"
                                                                        value="" size="3" class="form-control"
                                                                        id="discount_all_percent">
                                                                </div>
                                                                <span class="input-group-btn">
                                                                    <button name="submit_discount_form" type="submit"
                                                                        class="btn btn-success">Aceptar</button>
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
                            <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 no-padding-left sale_register_rightbox"
                                id="box-customers">
                                <!-- BEGIN SMALL BOX CUSTOMER -->
                                <div class="portlet light no-padding-left-right-box register-customer margin-bottom-15">
                                    <div class="portlet-title padding">
                                        <div class="caption">
                                            <i class="fa fa-user"></i>
                                            <span id="info-customer" class="caption-subject bold">
                                                Seleccionar Cliente
                                            </span>
                                        </div>
                                    </div>
                                    <div class="portlet-body padding" id="data-customer">

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
                                        <!--<div class="options">
                                            <a href="" data-toggle="modal" data-target="#myModal" class="pull-right tooltips"
                                                id="opener" data-original-title="Atajos de teclado">
                                                <i class="fa fa-keyboard-o" style="font-size: 30px"></i>
                                            </a>
                                        </div>-->
                                    </div>
                                    <ul class="list-group">
                                        <li id="tiers" class="list-group-item tier-style"
                                            style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
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
                                                <span id="n-articulos"
                                                    class="pull-right badge badge-cart badge-success">
                                                </span>
                                            </div>
                                            <div class="subtotal">
                                                <span class="name-item-summary">
                                                    Subtotal:
                                                </span>
                                                <span id="subtotal" class="pull-right name-item-summary">
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
                                            <div id="total-amount" class="amount animation-count font-green-jungle"
                                                data-speed="1000" data-decimals="2"></div>
                                        </div>
                                        <div class="total">
                                            <div class="side-heading">
                                                Cantidad a pagar:
                                            </div>
                                            <div id="amount-due" class="amount animation-count font-green-jungle"
                                                data-speed="1000" data-decimals="2"></div>
                                        </div>
                                    </div>

                                    <div class="payments" id="payments">
                                        <ul class="list-group" id="payments_list">


                                        </ul>
                                    </div>

                                    <!-- BEGIN ADD PAYMENT -->
                                    <div class="add-payment">

                                        <form action="javascript:void(0)" method="post" accept-charset="utf-8"
                                            id="add_payment_form" autocomplete="off">

                                            <ul class="list-group">
                                                <li class="list-group-item no-border-top tier-style"
                                                    style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="name-addpay">
                                                                <a class="sales_help tooltips" data-placement="left"
                                                                    title=""
                                                                    data-original-title="Aquí puedes poner el tipo de pago  y puedes pagar con diferentes tipos de pagos ">Agregar
                                                                    Pago </a>:
                                                            </span>
                                                        </div>
                                                        <div class="col-md-6" id="payment_body">

                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input type="text" name="amount_tendered" value="0.00"
                                                                    id="amount_tendered"
                                                                    class="form-control form-inps-sale" accesskey="p">
                                                                <span class="input-group-btn">
                                                                    <input class="btn btn-success" type="button"
                                                                        id="add_payment_button" value="Agregar Pago">
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
                                            <li class="list-group-item no-border-top tier-style"
                                                style="border-top-left-radius: 0px; border-top-right-radius: 0px; border: 0px;">
                                                <div id="finish_sale">
                                                    <form action="#" method="post" accept-charset="utf-8"
                                                        id="finish_sale_form" autocomplete="off">

                                                        <input type="button" class="btn btn-success btn-large btn-block"
                                                            style="display: none" id="finish_sale_button"
                                                            value="Completar Venta"> </form>
                                                </div>

                                                <div id="container_comment" class="hidden">
                                                    <div class="title-heading" id="comment_label" for="comment">
                                                        Comentarios:</div>
                                                    <textarea name="comment" cols="40" rows="2" id="comment"
                                                        class="form-control form-textarea" accesskey="o"></textarea>
                                                </div>
                                                <!--<div id="mesas-2-body"  style="display: none">
                                                    <div class="title-heading" >
                                                        <label id="comment_label" for="comment">Número de Mesa:</div>
                                                    <div id="body-select">
                                                </div>-->

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
                    <div class="modal fade" role="dialog" id="modal-offline">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header modal-header-success">
                                    <button type="button" id="close-modal" class="close" data-dismiss="modal">×</button>
                                    <h4 class="modal-title" id="title-modal">Datos </h4>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript" language="javascript">
                    // permite egregar los eventos dinamicos cuando se agregan las filas de la tabla del carrito
                    function controlador_eventos() {
                        //eliminamos los eventos
                        $("#cart_contents input").off("change");
                        $("#select_customer_form").off("submit");

                        $("#customer").off("keyup");

                        // se agregan de nuevo los elementos
                        $("#customer").keyup(function() {
                            suggestions_customer();
                            //console.log(suggestions);
                        });


                        $('#select_customer_form').submit(async function(e) {
                            e.preventDefault();
                            salesBeforeSubmit();
                            await select_customer($("#customer").val());
                            salesafterSubmit();
                        });

                        $("#cart_contents input").change(function() {
                            $($(this).parent()).submit();
                        });


                    }

                    $(document).ready(function() {

                        (async function() {
                            carga_location_select();

                        })();
                        armar_vista();
                        $("#item").keyup(function() {
                            items_suggestions();
                        });
                        (async function() {
                            set_comment_ticket(await objAppconfig.item('default_sales_type'));
                        })();
                        $("#add_payment_button").click(async function() {
                            salesBeforeSubmit();
                            let data = Array();
                            data["payment_type"] = $("#payment_types").val();
                            data["amount_tendered"] = $("#amount_tendered").val();
                            await _add_payment(data);
                            salesafterSubmit();
                        });
                        $("#add_payment_form").submit(async function(event) {
                            event.preventDefault();
                            $("#add_payment_button").click();
                        });
                        if ($("#show_comment_on_receipt").is(":checked") == true) {
                            $("#container_comment").removeClass('hidden');
                        }
                        $("#show_comment_on_receipt").click(function(event) {
                            if ($(this).is(":checked")) {
                                $("#container_comment").removeClass('hidden');
                            } else {
                                $("#container_comment").addClass('hidden');
                            }
                        });
                        var size = $(this).width();
                        if (size <= 1280) {
                            $('.table-scrollable').css('height', '360px');
                        } else if (size > 1280 && size <= 1366) {
                            $('.table-scrollable').css('height', '410px');
                        }

                        if (size < 992) {
                            $('#box-customers').removeClass('no-padding-left')
                        } else {
                            $('#box-customers').addClass('no-padding-left')
                        }

                        $('#add_item_form').submit(function(e) {
                            e.preventDefault();
                            add($("#item").val());
                            $("#item").val("");

                        });

                        $("#cancel_sale_button").click(function() {
                            if (confirm(lang("sales_confirm_cancel_sale"))) {
                                clear_all();

                                location.reload();
                            }
                        });
                        $("#discount_all_form").submit(async function(event) {
                            if ($.isNumeric($("#discount_all_percent").val())) {
                                salesBeforeSubmit();
                                discount_all($("#discount_all_percent").val());
                                await armar_vista();
                                $("#discount_all_percent").val("");
                                salesafterSubmit();

                            }
                        });
                        $('#comment').change(function() {
                            set_comment($("#comment").val())
                        });

                        $('#show_comment_on_receipt').change(function() {
                            set_comment_on_receipt($('#show_comment_on_receipt').is(':checked') ? '1' :
                                '0');
                        });
                        $('#show_receipt').change(function() {
                            set_show_receipt($('#show_receipt').is(':checked') ? '1' : '0');
                        });
                        $('#show_comment_ticket').change(function() {
                            if ($('#show_comment_ticket').is(':checked') == true) {
                                set_comment_ticket(1);
                            }
                        });


                        $('#item').click(function() {
                            $(this).attr('value', '');
                        });


                        $("#finish_sale_button").click(async function() {

                            //Prevent double submission of form
                            $("#finish_sale_button").hide();
                            $("#register_container").plainOverlay('show');

                            await complete_sale();
                            // window.location= window.location;
                        });

                        $("input[type=text]").not(".description").click(function() {
                            $(this).select();
                        });
                        $('#category_item_selection_wrapper').on('click', '.category_item.category', function(
                            event) {
                            $("#category_item_selection_wrapper").plainOverlay('show');

                            event.preventDefault();
                            current_category = $(this).text();
                            item_categories(current_category)


                        });

                        $('#category_item_selection_wrapper').on('click', '.category_items.item', function(
                            event) {
                            $("#category_item_selection_wrapper").plainOverlay('show');
                            event.preventDefault();
                            $("input[name='no_valida_por_id']").val("0");
                            $("#item").val($(this).data('id'));
                            $('#add_item_form').submit();
                            $('.show-grid').addClass('hidden');
                            $('.hide-grid').removeClass('hidden');
                            $("#category_item_selection_wrapper").plainOverlay('hide');

                        });
                        $("#category_item_selection_wrapper").on('click', '#back_to_categories', function(
                        event) {
                            $("#category_item_selection_wrapper").plainOverlay('show');
                            event.preventDefault();
                            categories();
                        });

                        // load_categories();
                    });

                    // Show or hide item grid
                    $(".show-grid").on('click', function(e) {
                        e.preventDefault();
                        $("#sale-grid-big-wrapper").removeClass('hidden');
                        $("#category_item_selection_wrapper").slideDown();
                        $('.show-grid').addClass('hidden');
                        $('.hide-grid').removeClass('hidden');
                    });

                    $(".hide-grid").on('click', function(e) {
                        e.preventDefault();
                        $("#category_item_selection_wrapper").slideUp();
                        $('.hide-grid').addClass('hidden');
                        $('.show-grid').removeClass('hidden');
                    });

                    setTimeout(function() {
                        $('#item').focus();
                    }, 100);
                    $(document).focusin(function(event) {
                        last_focused_id = $(event.target).attr('id');
                    });


                    if (screen.width <= 768) //set the colspan on page load
                    {
                        jQuery('td.edit_description').attr('colspan', '2');
                        jQuery('td.edit_serialnumber').attr('colspan', '4');
                    }

                    $(window).resize(function() {
                        var wi = $(window).width();

                        if (wi <= 768) {
                            jQuery('td.edit_description').attr('colspan', '2');
                            jQuery('td.edit_serialnumber').attr('colspan', '4');
                        } else {
                            jQuery('td.edit_description').attr('colspan', '4');
                            jQuery('td.edit_serialnumber').attr('colspan', '2');
                        }
                    });
                    </script>

                </div>

                <script type="text/javascript">
                function salesBeforeSubmit() {
                    //ajax-loader
                    $("#ajax-loader").show();
                    $("#add_payment_button").hide();
                    $("#finish_sale_button").hide();

                }

                function salesafterSubmit() {
                    //ajax-loader
                    $("#ajax-loader").hide();
                    $("#add_payment_button").show();
                    $("#finish_sale_button").show();

                }

                function processItemsResult(json) {
                    $("#category_item_selection_wrapper .pagination").removeClass('categories').addClass('items');
                    $("#category_item_selection_wrapper .pagination").html(json.pagination);
                    $("#category_item_selection").html('');
                    var back_to_categories_button = $("<div/>").attr('id', 'back_to_categories').attr('class',
                        'category_item back-to-categories col-md-2 col-sm-3 col-xs-6 ').append('<p>&laquo; ' +
                        "Volver a categor\u00edas" + '</p>');
                    $("#category_item_selection").append(back_to_categories_button);
                    for (var k = 0; k < json.items.length; k++) {
                        var image_src = json.items[k].image_src ? json.items[k].image_src :
                            "<?base_url()?>img/no-photo.jpg";
                        var prod_image = "";
                        var item_parent_class = "";
                        if (image_src != '') {
                            var item_parent_class = "item_parent_class";
                            var prod_image =
                                '<a class="btn grey-gallery"><img class="img-thumbnail" style="width:100%; height:60px;" src="' +
                                image_src + '" alt="" />';
                        }
                        var item = $("<div/>").attr('class',
                            'category_items item space-item col-lg-2 col-md-2 col-sm-3 col-xs-6  ' +
                            item_parent_class).attr('data-id', json.items[k].id).append(prod_image +
                            '<p class="letter-space-item">' + json.items[k].name + '</p>' + '</a>');
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
                        var category_item = $("<a/>").attr('class',
                            'btn grey-gallery category_item category col-lg-2 col-md-2 col-sm-3 col-xs-6').append(
                            '<p>' + json.categories[k] + '</p>');
                        $("#category_item_selection").append(category_item);
                    }
                    $("#category_item_selection_wrapper").plainOverlay('hide');
                }

                var last_focused_id = null;
                setTimeout(function() {
                    $('#item').focus();
                }, 10);
                async function delete_customer_onclick(obj) {
                    salesBeforeSubmit();
                    await delete_customer_sale();
                    salesafterSubmit();

                    return false;
                }
                async function delete_item_onclick(obj, line) {
                    salesBeforeSubmit();
                    await delete_item(line);
                    salesafterSubmit();
                    return false;
                }
                async function cambia_tier() {
                    let valor = document.getElementById("tier_id").value;
                    await set_tier_id(valor);
                    armar_vista();

                }


                async function cambia_subcategoria(elemento) {
                    $($(elemento).parent()).submit();
                }

                function editar_producto_cart(elemento) {
                    salesBeforeSubmit();
                    let href = $(elemento).attr('action');
                    let line_idItem = href.split('/');
                    let line = line_idItem[0];
                    let id_item = line_idItem.length == 2 ? line_idItem[1] : false;
                    let data = $(elemento).serializeArray();
                    (async function() {
                        await editar_producto(line, id_item, data);
                        salesafterSubmit();
                    })();

                    return false;
                }

               
                
                function show_modal(url,title ="Datos") 
                {
                    $.post(SITE_URL+"/"+url, function(data) {
                        $('.modal-body').html(data);

                        $('#modal-offline').modal({
                            show: true
                        });
                        $("#title-modal").html(title);
                        
                    });

                    return false;
                }
                </script>
                <script>
                jQuery(document).ready(function() {
                    Metronic.init(); // init metronic core componets
                    Layout.init(); // init layout
                    Demo.init(); // init demo features
                    ComponentsDropdowns.init();

                    if (verificar_conexion_internet()) {
                        document.getElementById("btn-sincronizar").style.display = "block";
                    }

                    window.addEventListener('online', handleConnectionChange);

                });

                function ir_offline() {
                    sincronizar_ventas(1, 1, 1);
                }
                </script>

                <script>
                var csfrData = {};
                csfrData['<?php echo $this->security->get_csrf_token_name();?>'] =
                    $.cookie('csrf_test_name') === undefined ? $.cookie('csrf_cookie_name') : $.cookie(
                    'csrf_test_name');
                $(function() {
                    $.ajaxSetup({
                        data: csfrData
                    });
                });
                </script>

                <script src="<?php echo base_url();?>js/offline/helper_moneda.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/helper_character.js" type="text/javascript"></script>


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
                <script src="<?php echo base_url();?>js/offline/item_kit_taxes_finder.js" type="text/javascript">
                </script>
                <script src="<?php echo base_url();?>js/offline/item_kits_helper.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/item_kit_location_taxes.js" type="text/javascript">
                </script>
                <script src="<?php echo base_url();?>js/offline/additional_item_seriales.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/item_kit_taxes.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/location.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/item_taxes.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/customer.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/tier.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/points.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/sale_helper.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/items_subcategory.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/sale.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/register.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/register_movement.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/cajas_empleados.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/sales.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/data_navbar.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/process_sales.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/pagination.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>js/offline/panel_categories.js" type="text/javascript"></script>
                <script>
                (async function() {

                    iniciar_register();
                })();
                </script>

                <?php $this->load->view("partial/footer_Offline"); ?>