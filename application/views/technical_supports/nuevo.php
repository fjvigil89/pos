<?php $this->load->view("partial/header"); ?>

<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class='fa fa-pencil'></i>
        <?php echo lang("customers_basic_information"); ?>
    </h1>
</div>
<!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD -->
<!-- BEGIN PAGE BREADCRUMB -->
<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->

<div class="clear"></div>

<div class="row">
    <div class="col-sm-3">
        <?php
            if($support_id<=0){           
                $this->load->view("technical_supports/cliente/buscador_cliente");
            }
         ?>
        <div id="info_cliente"></div>
    </div>
    <div id="nuevo">
        <?php 
        if($support_id<=0){
            $this->load->view("technical_supports/cliente/nuevo");
            
        }else{
           echo'<img src="img/loading_sale.gif" alt="" style="padding-left: 250px;"  class="loaded">';
        }
        ?>
    </div>

</div>


<?php if($support_id>0): ?>
    <script type='text/javascript'>
    // $("#nuevo").plainOverlay('show');
    $('#info_cliente').load("<?php echo site_url() ?>/technical_supports/get_info_cliente/" + <?=$customer_id?> + "/" +
            <?=$support_id?>);
        $('#nuevo').load("<?php echo site_url() ?>/technical_supports/get_form_servicio/" + <?=$customer_id?> + "/" +
            <?=$support_id?>);
        
    </script>
<?php endif; ?>

<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>

<?php
$this->load->view("partial/footer"); 