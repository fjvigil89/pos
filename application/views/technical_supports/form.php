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
		<div class="">
			<a title="<?php echo lang('sales_new_customer') ?>" data-original-title="<?php echo lang('sales_new_item') ?>"  id="new-customer"  href="javascript:void(0);" onclick="controler('<?php echo site_url() ?>/technical_supports/registrarCliente/','d=1','vista2','');" class="btn btn-success btn-block">
				<i class="icon-user-follow hidden-lg"></i>
				<span class="visible-lg"><?php echo lang('sales_new_customer') ?></span>
			</a>
		</div>
		<br>
		<!-- BUSCADOR -->
		<div class="portlet box blue-ebonyclay">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject fa fa-search"> <small><?php echo lang("technical_supports_customer"); ?></small></span>
				</div>
			</div>
			<div class="portlet-body">                             
                            <div class="form-body">
                                <input type="text" accesskey="c" name="customer" id="customer" class="form-control ciContc" placeholder="<?php echo lang('common_search'); ?>" autocomplete="off" value="">
                                <div class="col-sm-12" id="resultado" style="position: absolute;margin-top: 1px;margin-left: 0px;z-index: 9000;"></div>
                            </div>                            
			</div>
		</div>
		<!-- FIN DE BISCADOR -->
		<div id="vista"></div>
	</div>
	<div id="vista2">   </div>
</div>

<script type="text/javascript">
    $(".ciContc").keyup(function () { // function(event)
        var ciCont = $(this).val();
        if (ciCont == '') {
            $('div[id^="resultado"]').html('');
        } else {
            $('#resultado').load("<?php echo site_url() ?>/technical_supports/buscar_cliente_serv_tecnico/","bas=1&ciCont="+ciCont); 
        } 
        return false;
    });
</script>

<script type='text/javascript'>
        $('#vista2').load("<?php echo site_url() ?>/technical_supports/registrarCliente/","");
</script>

<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>

<?php
$this->load->view("partial/footer"); 
