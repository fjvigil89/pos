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
    #price{
        font-weight: bold;  
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
        font-size: 15px;
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
        padding: 5px;
        font-weight: bold;
        color: #FCFAFA
    }


    #input{
        margin-bottom:4px;
        
    }
    .total {
        background-color: #565555;
        margin-right: 5px;
        padding: 5px;
        font-size: 25px;
        border-radius: 10px 10px 10px 10px;
        color: #FDFBFB;
        width: 50%;
    }
    </style>
</head>

<body>
    <br>
    <div style="display:none" id="container-1" class="container-fluid ">
        <div class="row">
            <div id="input" class="<?=false ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>  ">
                <?= form_open_multipart('all/get_item',array('id'=>'item_form','class'=>'form-horizontal', "autocomplete"=>"off")); ?>

                    <input style=" font-weight: bold; text-align:center;" maxlength="30" placeholder="Escanee el código de barra" autocomplete="off" name="item" id="item" class="form-control">
                </form>
            </div>
            <div id="info-item" style="display:none"
                class="  <?=false ? 'col-md-5 col-sm-5 col-xs-6' : 'col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-12' ?>   ">
                <div class="contenedor">
                    <div class="row" style="padding:2px;">
                        <div class="col-md-12">
                            <h1 id="name" class="title-1 text-center">
                            </h1>
                        </div>
                        <div class="col-xs-12">
                            <div class="text-center">
                                <img id="img_item" class="img-fluid img-thumbnail" style="width:60%; height:150px;"
                                    src="<?=base_url()."/img/bannerventana.png"?>" alt="">

                            </div>

                        </div>
                        <div class="col-md-12">
                            <center>
                                <h3 id="price" class="  "></h3>
                            </center>
                        </div>


                    </div>
                </div>
            </div>


            <div id="contenedor-caroousel" style="display: <?=$show_carrousel ? 'block;' :'none;'?>"
                class=" <?= false ? 'col-md-7 col-sm-7 col-xs-6':'col-xs-12'?>">
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
        code_bar = "",
        url = "<?=site_url("all/get_item")?>",
        base_url = "<?=base_url()?>",
        base_url_img = "<?=site_url("app_files/view")?>",
        code_input ="";
        height_window = $(window).height();


    change_window();
    $("#item").focus(); 
    $("#container-1").show();
    $('#item_form').submit(function(e) {

        var data_send = $('#item_form').serializeArray();
        code_input = $("#item").val();  
        $("#item").val("");  
        get_item_data(url, data_send);
        e.preventDefault();
    });

    /* $(document).ready(function() {

         $(document).keypress(function(e) {
             if (e.which == 13) 
             {
                 
             } else
                 code_bar = code_bar + e.key;
         });

     });*/

    function get_item_data(url, data_send) {
        $.post(url, data_send,
            function(data) {
                data = JSON.parse(data);
                if (data.image_id > 0)
                    $("#img_item").attr('src', base_url_img + "/" + data.image_id);
                else
                    $("#img_item").attr('src', base_url + "/img/bannerventana.png");

                $("#name").html(data.name);
                $("#price").html(data.price);

                (async function() {
                    $("#info-item").show();
                    $("#contenedor-caroousel").hide();

                    await sleep(20000);
                    $("#info-item").hide();
                    $("#contenedor-caroousel").show();
                })();

            });
    }

    function sleep(time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }

    $(window).resize(function() {
        height_window = $(window).height();
        change_window();
    });

    function change_window() {
        $(".contenedor").height(height_window - 60);
        $(".img-c").height(height_window - 60);
        $("#img_item").height(height_window /2);
    }
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

        setInterval(function() {
            $("#item").focus();            
        },3);
        $('.carousel').carousel({
            interval: <?= (int) $this->config->item("interval_img_carousel") ? $this->config->item("interval_img_carousel"):"3000"?>
        })

    });
    </script>
    <script>
    var csfrData = {};
    csfrData['<?php echo $this->security->get_csrf_token_name();?>'] =
        '<?php echo $this->security->get_csrf_hash();?>';
    $(function() {
        $.ajaxSetup({
            data: csfrData
        });
    });
    </script>
</body>

</html>