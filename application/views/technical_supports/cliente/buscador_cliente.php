<div id="buscador_cliente">
    <div class="">
        <a title="<?php echo lang('sales_new_customer') ?>" data-original-title="<?php echo lang('sales_new_item') ?>"
            id="new-customer" href="javascript:void(0);"
            onclick="controler('<?php echo site_url() ?>/technical_supports/registrarCliente/','d=1','nuevo','');"
            class="btn btn-success btn-block">
            <i class="icon-user-follow hidden-lg"></i>
            <span class="visible-lg"><?php echo lang('sales_new_customer') ?></span>
        </a>
    </div>
    <br>
    <!-- BUSCADOR -->
    <div class="portlet box blue-ebonyclay">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject fa fa-search">
                    <small><?php echo lang("technical_supports_customer"); ?></small></span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="form-body">
                <input type="text" accesskey="c" name="customer" id="customer" class="form-control ciContc"
                    placeholder="<?php echo lang('common_search'); ?>" autocomplete="off" value="">
                <div class="col-sm-12" id="resultado"
                    style="position: absolute;margin-top: 1px;margin-left: 0px;z-index: 9000;"></div>
            </div>
        </div>
    </div>
</div>


<script>
let support_id = '<?=$support_id?>';
$("#customer").autocomplete({
    source: '<?php echo site_url("sales/customer_search"); ?>',
    delay: 300,
    autoFocus: false,
    minLength: 1,
    select: function(event, ui) {

        $('#nuevo').load("<?php echo site_url() ?>/technical_supports/get_form_servicio/" + ui.item.value +
            "/" + support_id);
        $('#info_cliente').load("<?php echo site_url() ?>/technical_supports/get_info_cliente/" + ui.item
            .value + "/" + support_id);

        $("#customer").val(ui.item.label);
        //$('#select_customer_form').ajaxSubmit({target: "#register_container", beforeSubmit: salesBeforeSubmit});
    }
});
</script>