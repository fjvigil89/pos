<?php $this->load->view("partial/header"); ?>


<!-- BEGIN PAGE TITLE -->
<div class="page-title">
	<h1>
		<i class="icon fa fa-wrench"></i>
		<?php echo lang('module_' . $controller_name); ?> 
                <a target="_blank" href="https://www.youtube.com/watch?v=SzJnORvT2vY&feature=youtu.be" class="icon fa fa-youtube-play help_button" id='maxitems' ></a>
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

<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>
<div class="row">
    <div class="col-sm-12">
    <div id="contResumen" class="col-lg-12" style="height: auto;overflow: hidden;">
         <?php $this->load->view("technical_supports/manage_resumen"); ?>
    </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12"> 
        <!-- BUSCADOR -->
        <div class="portlet box blue-ebonyclay">
            <div class="portlet-title">
                <div class="caption">
                        <span class="caption-subject fa fa-search"> <small><?php echo lang("technical_supports_customer_serv"); ?></small></span>
                </div>
            </div>
            <div class="portlet-body">                
                <div class="form-body">
                        <input type="text" accesskey="c" name="customer" id="customer" class="form-control ciContc" placeholder="<?php echo lang('common_search'); ?>" value="">
                        <div class="col-sm-10" id="resultado" style="position: absolute;margin-top: 1px;margin-left: 2px;z-index: 9000;"></div>
                </div>              
            </div>
        </div> 
    </div> 
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <span class="icon"><i class="fa fa-th"></i></span>
                    <?php echo lang('technical_supports_list'); ?>
                </div>
                <div class="tools">
                    <div class="">
                        <?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)): ?>
                            <a href="<?php echo site_url($controller_name) ?>/nuevo-servicio/-1" class="btn btn-primary">
                                    <i class="fa fa-plus hidden-lg fa fa-2x tip-bottom"
                                       data-original-title="<?php echo lang($controller_name . '_new'); ?>"></i>
                                    <span class="visible-lg"><?php echo lang($controller_name . '_new') ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="contTabla" class="portlet-body ">
                 <?php $this->load->view("technical_supports/manage_tabla"); ?>
            </div>
        </div>
                 
        
    </div>
</div>

<script type="text/javascript">
    $(".ciContc").keyup(function () { // function(event)
        var ciCont = $(this).val();
        if (ciCont == '') {
            $('div[id^="resultado"]').html('');
        } else {
            $('#resultado').load("<?php echo site_url() ?>/technical_supports/buscar_cliente_serv_tecnico/","ciCont="+ciCont); 
        } 
        return false;
    });
</script>


<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>

<?php
$this->load->view("partial/footer"); 

