<?php $this->load->view("partial/header"); ?>


<div class="portlet light no-padding-general hidden-print">
    <div class="portlet-body">
        <div class="row margin-top-15 hidden-print">
            <div class="col-lg-2 col-md-2 col-sm-6">
                <button type="button" class="btn btn-warning btn-block hidden-print" id="print_button"
                    onclick="print_receipt()">
                    <?php echo lang('sales_print'); ?>
                </button>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6">
                <button class="btn btn-success btn-block hidden-print" id="new_sale_button_1"
                    onclick="window.location='<?php echo site_url('sales'); ?>'">
                    <?php echo lang('sales_new_sale'); ?>
                </button>
            </div>

        </div>
    </div>
</div>

<div class="portlet light no-padding-general">
    <div class="portlet-body">
        <div id="receipt_wrapper" style="padding:<?php echo $this->config->item('padding_ticket')?>px !important;"
            class="receipt_<?php echo $this->config->item('receipt_text_size')?>" ;>
            <div id="receipt_header">
                <div id="company_name">
                    <?php echo $this->Location->get_info_for_key('overwrite_data')==1 ? $this->Location->get_info_for_key('name') : $this->config->item('company') ; ?>
                </div>
               
                <div id="sale_time">
                    <?php echo date(get_date_format().' '.get_time_format(), strtotime(date('Y-m-d H:m:s'))); ?>
                </div>

            </div>

            <div id="receipt_general_info">


                <div id="employee">
                    <?php //echo lang('employees_employee').": ".$vendedor->first_name." ".$vendedor->last_name;; ?>
                </div>
				<div id="employee">
                    <?php echo"<strong>". lang('ntable')."</strong> : ".$ntabe ?>
                </div>


            </div>

            <table id="receipt_items">
                <tr style="border-bottom: 1px solid #000000;border-top: 1px solid #000000;">
                    <th class="left_text_align"><?php echo lang('items_item'); ?></th>
                    <!--<th class="text-center"><?php echo lang ("items_item_number"); ?></th>
                    --><th class="text-center"><?php // echo lang('sales_quantity_short'); ?></th>
                </tr>
                <?php foreach($items as $item) { ?>
                <tr style="border-top: 1px dashed #000000;">
                    <td class="left_text_align">
                        <?php echo character_limiter(H($item["name"]), $this->config->item('show_fullname_item') ? 200 : 25); ?>
                    </td>
                    <!--<td class="text-center">
                        <?php echo isset($item["item_number"])? $item["item_number"]:"";	 ?>
                    </td>-->
                    <td class="text-center">
                        <?php echo to_quantity($item["quantity"]); ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <div>
                <?php  if(!empty($description)):?>
                <strong><?= lang("items_description")." : ";?></strong><br> <?php echo  $description?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


<?php if ($this->config->item('print_after_sale')) { ?>
<script type="text/javascript">
$(window).bind("load", function() {
    print_receipt();
    //window.location = '<?php// echo site_url("sales"); ?>';
});
</script>
<?php } ?>

<script type="text/javascript">
$("#tawkchat-minified-box").hide();

function print_receipt() {
    <?php if ($this->config->item('receipt_copies') > 1): ?>
    var receipt_amount = <?php echo $this->config->item('receipt_copies');?> - 2;
    var receipt = $('#receipt_wrapper').clone();
    $('#duplicate_receipt_holder').html(receipt);
    $("#duplicate_receipt_holder").addClass('active');

    for (i = 0; i < receipt_amount; i++) {
        $('<div id="duplicate_receipt_holder_' + i + '"></div>').insertAfter("#receipt_wrapper");
        $('#duplicate_receipt_holder_' + i).html(receipt.html());
        $("#duplicate_receipt_holder_" + i).addClass('active');
    }

    window.print();
    <?php else: ?>
    window.print();
    <?php endif; ?>
}

function toggle_gift_receipt() {
    var gift_receipt_text = <?php echo json_encode(lang('sales_gift_receipt')); ?>;
    var regular_receipt_text = <?php echo json_encode(lang('sales_regular_receipt')); ?>;

    if ($("#gift_receipt_button").hasClass('regular_receipt')) {
        $('#gift_receipt_button').addClass('gift_receipt');
        $('#gift_receipt_button').removeClass('regular_receipt');
        $("#gift_receipt_button").text(gift_receipt_text);
        $('.gift_receipt_element').show();
    } else {
        $('#gift_receipt_button').removeClass('gift_receipt');
        $('#gift_receipt_button').addClass('regular_receipt');
        $("#gift_receipt_button").text(regular_receipt_text);
        $('.gift_receipt_element').hide();
    }
}
</script>



<!-- This is used for mobile apps to print receipt-->



<?php $this->load->view("partial/footer"); ?>