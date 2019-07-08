<div class="portlet light">
    <input type="text" name="item" autocomplete="off" id="item" class="form-control" placeholder="<?php echo lang('common_search'); ?>">
    <div class="col-sm-10 no-padding" id="resultado_repuesto" style="position: absolute; z-index: 9000;"></div>
</div>
<div class="portlet light table-responsive">
    <div id="tala_carrito">
      <?php $this->load->view("technical_supports/carrito/table_items");?>
      
    </div>
</div>

<script type="text/javascript">
$('.animation-count').data('countToOptions', {
    formatter: function(value, options) {
        return value.toFixed(options.decimals).replace('',
            '<?php echo $this->config->item("currency_symbol")?>').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
});




$("#item").keyup(function() {
    var value = $(this).val();
    if (value == '') {
        $('#resultado_repuesto').html('');
    } else {
        $('#resultado_repuesto').load("<?php echo site_url("technical_supports/buscarItems")?>",
            "idSupport=<?php echo $support_id ?>&buscar=" + value);
    }
    return false;
});
$(document.body).click(function() {
    $('#item').val('');
    $('#resultado_repuesto').html('');
});
</script>