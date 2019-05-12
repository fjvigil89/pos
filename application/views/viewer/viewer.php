<!DOCTYPE html>
<html lang="es">

<head>
    <title>Visor</title>
    <meta charset="UTF-8">
    <meta name="title" content="Visor">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <base href="<?php echo base_url();?>" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />

    <link href="css/confirm/jquery-confirm.css" rel="stylesheet">
    <link href="js/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>bin/bin.min.css" rel="stylesheet" type="text/css" /> <!-- IMPORTANTE -->


    <!-- END CSS -->

    <!-- BEGIN JAVASCRIPT-->

    <script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/accounting.min.js" type="text/javascript"></script>
    <style>
    .fotn-total {
        font-size: 25px;
        margin-left: 8px;
        margin-right: 8px
    }

    .contenedor {
        background: rgba(253, 255, 255);
        height: 450px;
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }

    .panel-t {
        margin-top: 4px;
    }

    .b1 {
        font-weight: bold;
        font-size: 16px; 
        padding-left:30px;
        padding-right:30px;
    }
    .b2 {
        font-weight: bold;
        font-size: 16px; 
        padding-left:28px;
        padding-right:28px;
    }

    table thead {
        color: #fff;
        background-color: #26C281;
    }

    table tr {
        font-size: 16px;
    }

    body {
        background-color: #E5E5E5;
    }

    .carousel {
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }

    .opacity-1 {
        background-color: #565555;
        opacity: 0.85;
        margin-top: 4px;
        padding-top: 4px;
        border-radius: 5px 5px 5px 5px;
    }

    .title-1 {
        background-color: #3E3E3E;
        border-radius: 5px 5px 5px 5px;
        padding: 4px;
        font-weight: bold;
        color: #FCFAFA;
        margin: 2px;
        font-size:25px;
    }

    .title-2 {

        font-weight: bold;
    }

    .total {
        background-color: #565555;
        margin-right: 5px;
        padding: 5px;
        font-size: 25px;
        border-radius: 10px 10px 10px 10px;
        color: #FDFBFB;
    }

    hr {
        border: 0;
        border-bottom: 1px dashed #ccc;
        background: #999;
        margin-top: 0px;
        margin-bottom: 5px;
    }

    .f-total {
        overflow: hidden;
        position: fixed;
        bottom: 30px;
        margin-left: 5px;
    }
    .panel-input{
        margin:2px;
        padding:3px;
    }
    </style>
</head>

<body>
    <br>
    <div style="display:none" id="container-1" class="container-fluid ">
        <div class="row">
            <?php if(!$this->config->item('activar_casa_cambio') and $show_viewer ) {?>
            <div id="payment" style="display:none"
                class=" <?=$show_carrousel ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>   ">
                <div class="contenedor"><br><br><br><br>
                    <h1 class="text-center"><strong><?=$this->config->item("msg_cange_cart_viewer") ? $this->config->item("msg_cange_cart_viewer"):"Su cambio es" ?></strong></h1><br>
                    <h1 class="text-center"> <span id="change" style=" background-color: #010101;"
                            class="label label-default">0.0</span></h1>

                </div>
            </div>
            <div id="finish" style="display:none"
                class=" <?=$show_carrousel ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>   ">
                <div class="contenedor"><br>
                    <h1 class="text-center"><strong><?=$this->config->item("msg_thank_cart_viewer") ? $this->config->item("msg_thank_cart_viewer"):"Gracias por su compra" ?></strong></h1><br>
                    <center>
                        <img style="width:200px; height:200px" src="<?=base_url()."/img/smile.png"?>">
                    </center>
                    <h3 class="text-center">
                        <strong>Visita :
                            <?php if(($this->Location->get_info_for_key('overwrite_data')==1 and  $this->Location->get_info_for_key('website') ) or $this->config->item('website')) { 
						
							 echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('website') : $this->config->item('website') ; 
						
                         } ?>
                        </strong></h3>
                </div>
            </div>
            <div id="cart"
                class="  <?=$show_carrousel ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>   ">
                <div class="contenedor">
                    <!--Table-->
                    <table id="table-cart" class="table table-hover  table-striped table-borderless">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th style="width:65%;"><?=lang("sales_item_name")?></th>
                                <th style="width:2%;"><?=lang("sales_quantity")?></th>
                                <th style="width:30%;"> <?= lang("sales_total")?></th>
                            </tr>
                        </thead>
                        <tbody id="body-table">
                        </tbody>
                    </table>
                    <!--Table-->
                    <hr>
                    <div id="total" class="f-total total pull-right">0.0</div>
                </div>
            </div>
            <?php }
            else if($show_viewer) { ?>
            <div id="cart"
                class="  <?=$show_carrousel ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>   ">
                <div class="contenedor">
                    <div class="row" style="padding:2px;">
                        <div class="col-md-12">
                            <h1 class="title-1 text-center">Tasa de hoy
                                <span><?=$rate?></span>
                            </h1>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-xs-6">
                            <div class="panel-input form-group">
                                <label for="email" class="title-2">Cantidad en pesos:</label>
                                <input style=" font-weight: bold; font-size:19px; text-align: center"
                                    placeholder="Ingrese cantidad $" type="number" class="form-control" readonly
                                    id="catidad">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="panel-input form-group">
                                <label for="email" class="title-2">Cantida de BS:</label>
                                <input value="" style=" font-size:19px; font-weight: bold; text-align: center"
                                    type="number" class="form-control" readonly id="convertido">
                            </div>
                        </div>
                        <div class="<?=$show_carrousel ?'col-xs-12': 'col-xs-12'?>">
                            <center>
                                <div>
                                    <input class="btn b1 " name="7" type="button" value="7" />
                                    <input class="btn b1" name="8" type="button" value="8" />
                                    <input class="btn b1 " name="9" type="button" value="9" />
                                    
                                </div>
                                <div class="panel-t">
                                    <input class="btn b1" name="4" type="button" value="4" />
                                    <input class="btn b1" name="5" type="button" value="5" />
                                    <input class="btn b1" name="6" type="button" value="6" />
                                    
                                </div>
                                <div class="panel-t">
                                    <input class="btn b1" name="1" type="button" value="1" />
                                    <input class="btn b1" name="2" type="button" value="2" />
                                    <input class="btn b1" name="3" type="button" value="3" />                        
                                </div>
                                <div class="panel-t">
                                    <input class="btn b2" name="C" type="button" value="C" />
                                    <input class="btn b1" name="0" type="button" value="0" />                                    
                                    <input class="btn b2" name="accent" type="button"
                                        value="←" />
                                </div>
                            </center>
                        </div>
                    </div>

                </div>
            </div>
            <?php }?>
            <div id="contenedor-caroousel" style="display: <?=$show_carrousel ? 'block;' :'none;'?>"
                class=" <?= $show_viewer ? 'col-md-7 col-sm-7 col-xs-6':'col-xs-12'?>">
                <center>
                    <div id=" carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <?php foreach ($data_list as $key => $data): ?>
                            <li data-target="#carousel-example-generic" data-slide-to="<?=$key?>"
                                class="<?=$key==0 ? 'active':''?>"></li>
                            <?php endforeach;?>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <?php foreach ($data_list as $key => $data): ?>
                            <div class="item <?=$key==0 ? 'active':''?>">
                                <img class="img-c" style="width:1200px; height:450px"
                                    src="<?=$path_long."/".$data->new_name?>">
                                <?php if(!empty($data->title) || !empty($data->description)): ?>
                                <div class="opacity-1 carousel-caption">
                                    <h4><strong><?=H($data->title)?></strong></h4>
                                    <p><?=H($data->description)?></p>
                                </div>
                                <?php endif;?>
                            </div>
                            <?php endforeach;?>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button"
                            data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </div> <!-- Carousel -->

                </center>
            </div>
        </div>
    </div>


    <script>
    var show_carrousel = "<?=$this->config->item("show_carrousel")?>",
        show_viewer = "<?=$this->config->item("show_viewer")?>",
        updated = "0000-00-00 00:00:00",
        height_window = $(window).height();

    change_window();

    $("#container-1").show();

    <?php if(!$this->config->item('activar_casa_cambio')){?>
    if (show_viewer == 1) {
        download();
        setInterval('download()', 3000);
    }



    function download() {

        $.ajax({
            type: "GET",
            url: '<?=site_url("all/get_data/".$id)?>/' + updated,
            data: {},
            async: false,
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    updated = data["updated"] != undefined ? data["updated"] : updated;

                    if (data["show_viewer"] != show_viewer)
                        location.reload();

                    if (data["show_carrousel"] != show_carrousel)
                        location.reload();

                    if (data["is_updated"] == true) {
                        if (data["is_cart"] == 1) {
                            create_table(data);
                            $("#cart").show();
                            $("#finish").hide();
                            $("#payment").hide();
                        } else if (data["is_cart"] == 2) {

                            $("#cart").hide();
                            $("#finish").hide();
                            $("#payment").show();
                            $("#change").html("<strong>" + data["change"] + "</strong>");

                        } else if (data["is_cart"] == 3) {
                            $("#cart").hide();
                            $("#finish").show();
                            $("#payment").hide();
                        }

                    }
                } catch (e) {
                    location.reload();
                }
            }
        });

    }

    function create_table(data) {
        var items = data["cart_data"] != undefined ? data["cart_data"] : {},
            html = '';

        $("#body-table").html("");

        const key = Object.keys(items).reverse();
        if (key.length <= 0) {
            html = '<tr class="cart_content_area">';
            html += '<td colspan="3">';
            html += '<div class="text-center text-danger" >'
            html += '<h3><?=lang('sales_no_items_in_cart'); ?>';
            html += '</div>';
            html += '</td>';
            html += '</tr>';

            $("#body-table").html(html);
        }
        var quantity = 0;

        for (var i in key) {

            let item = items[key[i]];

            html = '<tr>';
            html += '<td class="itm_name_heading">' + item["name"] + '</td>';
            html += '<td class="text-center" >' + item['quantity'] + '</td>';
            // html += '<td class="text-center" >' + item['discount'] + '%</td>';
            html += '<td class="text-center"><strong>' + item['total_tax'] + '</strong></td>';
            html += '</tr>';

            $("#body-table").append(html);
        }
        flota();
        $("#total").html("<strong>Total: " + data["total"] + "</strong>");
    }
    <?php } 
        else {?>
    var rate = "<?=$rate?>";
    $("input[type='button'").click(function(e) {
        var cant = $("#catidad").val();
        var value = $(this).val();

        if ($.isNumeric(value))
            $("#catidad").val(cant + value)

        else if (value == "C"){
            $("#catidad").val("");
            $("#convertido").val(0);
        }

        else if (cant.length > 0) {
            $("#catidad").val(cant.substr(0, cant.length - 1));
            
            if(cant.length == 0)
                $("#convertido").val(0);
        }

        if ($("#catidad").val() > 0) {
            var total = convert($("#catidad").val(), rate);
            total = accounting.formatNumber(total, 2, "");
            $("#convertido").val(total);
        }

    });

    function convert(cant, tasa = 1) {
        return cant / tasa;
    }

    <?php }?>

    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Login.init();
        Demo.init();
        ComponentsDropdowns.init();

        //Keep session alive by sending a request every 5 minutes
        setInterval(function() {
            $.get('<?php echo site_url('home/keep_alive'); ?>');
        }, 300000);

        $('.carousel').carousel({
            interval: <?= (int) $this->config->item("interval_img_carousel") ? $this->config->item("interval_img_carousel"):"3000"?>
        })

    });

    $(window).resize(function() {
        height_window = $(window).height();
        change_window();
        flota();
    });

    function flota() {
        // se valida si la tabla a pasado los limites, y se flota el total
        if ($("#table-cart").height() + $("#total").height() + 60 > (height_window))
            $("#total").addClass("f-total");
        else
            $("#total").removeClass("f-total");

    }

    function change_window() {
        $(".contenedor").height(height_window - 30);
        $(".img-c").height(height_window - 30);
    }
    </script>
<script> var csfrData={};csfrData['<?php echo $this->security->get_csrf_token_name();?>']= '<?php echo $this->security->get_csrf_hash();?>';$(function(){$.ajaxSetup({data: csfrData});});</script>	
</body>

</html>