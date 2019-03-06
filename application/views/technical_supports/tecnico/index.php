<?php foreach ($result->result() as $result) {  ?>
<div class="portlet box">
    <div class="portlet-body">
        <div class="profile">
            <div class="row">
                <div class="col-md-3">
                    <ul class="list-unstyled profile-nav text-center">
                        <?php
                        if($result->image_id !='') { 
                            $foto="site_url()/app_files/view/$result->image_id ";
                        }  else { 
                            $foto="assets/template/images/perfil.JPG"; 
                         } ?>
                        <li class="text-center" style="background: url('<?php echo $foto; ?>') center center no-repeat; background-size: cover;height: 37px;"> 
                        </li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <h4 class="font-green sbold uppercase"><?php echo $result->first_name ." ". $result->last_name ?></h4>
                    <p>
                    </p>
                </div>
                <div class="col-md-12">
                    <ul class="list-inline">
                        <?php /*<li>
                            <i class="fa fa-map-marker"></i> <?php echo $result->country ?>
                        </li> */ ?>
                        <li>
                            <i class="fa fa-phone"></i> <?php echo $result->phone_number ?>
                        </li>
                        <li>
                            <i class="fa fa-inbox"></i> <?php echo $result->email ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $abonos=''; foreach ($abono->result() as $abono) { $abonos=$abono->payment; } if($abonos=='') { $abonos=0; } ?>
<div class="portlet sale-summary box">
    <div class="portlet-title">
        <div class="caption font-red sbold"> <?php echo lang("technical_supports_pagos_y_abonos"); ?> </div>
        <div class="tools">
                <a class="reload" href="javascript:;" data-original-title=""
                   title=""> </a>
        </div>
    </div>
    <div class="portlet-body">
        <ul class="list-unstyled">
            <li>
                <span class="sale-info"> <?php echo lang("technical_supports_abono"); ?> <i class="fa fa-img-up"></i></span>
                <span class="sale-num"> <?php echo $abonos ?> </span>
            </li>
            <li>
                <span class="sale-info"> <?php echo lang("technical_supports_repair_cost"); ?> <i class="fa fa-img-down"></i></span>
                <span class="sale-num">  <?php echo $result->repair_cost ?></span>
            </li>
            <?php $resta= $result->repair_cost - $abonos; ?>
            <li>
                <span class="sale-info"> <?php echo lang("technical_supports_diferencia"); ?> </span>
                <span class="sale-num"> <?php echo $resta; ?> </span>
            </li>
        </ul>
    </div>
</div>
<div class="portlet light box green">
    <div class="portlet-title padding">
        <div class="caption">
                <i class="fa fa-tablet"></i>
                <span class="caption-subject bold"><?php echo lang("technical_supports_information"); ?></span>
        </div>
    </div>
    <li class="list-group-item">
        <span class="name-item-summary"><?php echo lang("technical_supports_serial"); ?>:</span>
        <span class="pull-right"><?php echo $result->Id_support ?></span>
    </li>
    <li class="list-group-item">
        <span class="name-item-summary"><?php echo lang("technical_supports_marca"); ?>:</span>
        <span class="pull-right"><?php echo $result->marca ?></span>
    </li>
    <li class="list-group-item">
        <span class="name-item-summary"><?php echo lang("technical_supports_model"); ?>:</span>
        <span class="pull-right"><?php echo $result->model ?></span>
    </li>
    <li class="list-group-item">
        <span class="name-item-summary"><?php echo lang("technical_supports_color"); ?>:</span>
        <span class="pull-right"><?php echo $result->color ?></span>
    </li>
</div>
<?php } ?>