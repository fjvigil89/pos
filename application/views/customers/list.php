<html>

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
    <script src="<?php echo base_url();?>js/offline/validador.js"></script>
    <script src="<?php echo base_url();?>js/offline/conexion_evento.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/confirm/jquery-confirm.js"></script>
    <script src="<?php echo base_url();?>js/offline/helper_moneda.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/helper_character.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/offline/language.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/location.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/appconfig.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/strtotime.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/employee.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/customer.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>

    <script type="text/javascript">
    var SITE_URL = "<?php echo site_url(); ?>";
    var BASE_URL = '<?= base_url();?>';
    var objAppconfig = new AppConfig();
    var objEmployee = new Employee();
    var objLocation = new Location();
    var objCustomer = new Customer();
    </script>



    <script type="text/javascript">
    validation_indexedDB_browser();
    validation_session_employee();
    </script>
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

                    <!-- Button offline -->
                    <div class="btn-group pull-right">
                        <a href="javascript:sincronizar_ventas(1,1);" class="btn green" id="btn-sincronizar"
                            style="display:none;">
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

                    <li class="">
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
                    <li class="active">
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
                    <div id="register_container" class="sales clearfix">

                        <div class="page-head">
                            <!-- PAGE TOOLBAR -->
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>
                                    <i class="icon fa fa-barcode "></i>
                                    Ventas <a target="_blank" href="" style="display: none; "
                                        class="icon fa fa-youtube-play help_button"></a>
                                </h1>
                            </div>
                            <!-- END PAGE TITLE -->
                        </div>

                        <div id="breadcrumb" class="hidden-print">
                            <a href="<?php echo site_url("sales/") ?>"><i class="fa fa-home"></i> Panel</a><a
                                class="current" href="<?php echo site_url("sales/list_sales") ?>">Listar Clientes</a>
                        </div>

                        <div class="clear"></div>

                        <div class="portlet box green" id="portlet-content">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="icon">
                                        <i class="fa fa-th"></i>
                                    </span> lista de clientes
                                    
                                </div>
                                <div class="pull-right" style="padding-top:10px">
                                   <button onclick="show_modal_customer('customers/new_modal',-1)" class="btn btn-xs btn-block default btn-editable">Nuevo</button>

                                </div>
                                
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="table-list_sales"
                                                class="table tablesorter table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Identificacíon</th>
                                                        <th><?= lang('common_last_name')?></th>
                                                        <th><?= lang('common_first_name')?></th>
                                                        <th><?= lang('common_email')?></th>
                                                        <th><?= lang('common_phone_number')?></th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBody-list">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal fade" role="dialog" id="modal-offline">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header modal-header-success">
                                    <button type="button" id="close-modal" class="close" data-dismiss="modal">×</button>
                                    <h4 class="modal-title">Datos </h4>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                        </div>
                    </div>


                        <script>
                        carga_body();

                        async function carga_body() 
                        {
                            $("#tableBody-list").html("");

                            var listn = await objCustomer.lits_new(),
                                html = "";

                            listn.forEach(customer => {
                                html = '<tr>';

                                html += '<td style="width: 2%">' + customer.person_id + '</td>';
                                html += '<td style="width: 5%">' + customer.account_number + '</td>';
                                html += '<td style="width: 15%">' + customer.last_name + '</td>';
                                html += '<td style="width: 14%">' + customer.first_name + '</td>';
                                html += '<td style="width: 10%">' + customer.email + '</td>';
                                html += '<td style="width: 10%">' + customer.phone_number + '</td>';
                                html += '<td style="width: 2%">';
                                html +='<a ref="javascrit:void(0)" onclick="show_modal_customer(\'customers/new_modal\','+customer.person_id+')"  class="btn btn-xs btn-block default btn-editable update-person" title="Actualizar Cliente"><i class="fa fa-pencil"></i>Editar</a></td>';

                                html += '</tr>';

                                $("#tableBody-list").append(html);

                            });
                        }
                       
                        function show_modal_customer(url,person_id = -1) 
                        {
                            $.post(SITE_URL+"/"+url, function(data) {
                                $('.modal-body').html(data);
                                
                                (async function(){                                                             
                                   await  get_customer(person_id); // esta en new_modal
                                    $('#modal-offline').modal({
                                        show: true
                                    });

                                })();
                            });

                            return false;
                        }
                       
                        $(document).ready(function() {
                            (async function() {
                                carga_location_select();
                            })();

                            document.getElementById("btn-sincronizar").style.display = "none";

                        });
                        window.addEventListener('online', handleConnectionChange);
                        jQuery(document).ready(function() {
                            Metronic.init(); // init metronic core componets
                            Layout.init(); // init layout
                            Demo.init(); // init demo features
                            ComponentsDropdowns.init();

                        });
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
                        <script src="<?php echo base_url();?>js/offline/data_navbar.js" type="text/javascript"></script>



                        <?php $this->load->view("partial/footer_Offline"); ?>