<div class="col-sm-9">
    <div id="form">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-book-open"></i>
                    <span class="caption-subject bold">
                        <?php echo lang("customers_basic_information"); ?>
                    </span>
                </div>

            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php echo form_open_multipart('customers/save/' . $person_info->person_id, array('id' => 'customer_form', 'autocomplete' => 'off')); ?>

                <div class="form-body">
                    <h4><?php echo lang('common_fields_required_message'); ?></h4>
                    <div class="row">

                        <?php $this->load->view("technical_supports/cliente/basic_info_customer"); ?>

                        <div class="col-sm-12">

                            <div class="row">
                                <?php if ($this->config->item('customers_store_accounts') && $this->Employee->has_module_action_permission('customers', 'edit_store_account_balance', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>

                                <div class="col-sm-6 col-md-6">

                                    <div class="form-group">
                                        <?= form_label(lang('customers_store_account_balance') . ':', 'balance', array('class' => ' control-label')); ?>
                                        <?=form_input(array(
                                            'name' => 'balance',
                                            'id' => 'balance',
                                            'class' => 'balance form-control form-inps',
                                            'value' => $person_info->balance ? to_currency_no_money($person_info->balance) : '0.00'));
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">

                                    <div class="form-group">
                                        <?php echo form_label(lang('customers_credit_limit') . ':', 'credit_limit', array('class' => 'control-label')); ?>
                                        <?= form_input(array(
                                            'name' => 'credit_limit',
                                            'id' => 'credit_limit',
                                            'class' => 'credit_limit form-control form-inps',
                                            'value' => $person_info->credit_limit ? to_currency_no_money($person_info->credit_limit) : ''));
                                    ?>

                                    </div>
                                </div>
                                <?php } ?>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= form_label(lang('config_company') . ':', 'company_name', array('class' => 'control-label')); ?>
                                        <?= form_input(array(
                                            'name' => 'company_name',
                                            'id' => 'customer_company_name',
                                            'class' => 'company_names form-control',
                                            'value' => $person_info->company_name));
                                        ?>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <?php if ($this->config->item('system_point') && isset($person_info_point)) {
                                            ?>

                                    <div class="form-group">
                                        <?= form_label(lang('config_value_point_accumulated') . ':', 'value_point', array('class' => 'control-label ')); ?>

                                        <?= form_input(array(
                                            'name' => 'value_point',
                                            'id' => 'value_point',
                                            'class' => 'value_point form-control form-inps',
                                            'value' => $person_info_point));
                                        ?>

                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php echo form_label(lang('customers_taxable') . ':', 'taxable', array('class' => 'control-label')); ?>
                                        <div class="md-checkbox-inline">
                                            <div class="md-checkbox">
                                                <?php echo form_checkbox('taxable', '1', $person_info->taxable == '' ? TRUE : (boolean)$person_info->taxable, 'id="noreset" class="md-check"'); ?>
                                                <label for="noreset">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($tiers)) { ?>
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <?php echo form_label(lang('customers_tier_type') . ':', 'tier_type', array('class' => 'control-label')); ?>

                                        <?php echo form_dropdown('tier_id', $tiers, $person_info->tier_id, 'class="bs-select form-control"'); ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($person_info->cc_token && $person_info->cc_preview) { ?>
                    <div class="control-group">
                        <?php echo form_label(lang('customers_delete_cc_info') . ':', 'delete_cc_info', array('class' => 'col-md-3 control-label ')); ?>
                        <div class="col-md-8">
                            <?php echo form_checkbox('delete_cc_info', '1'); ?>
                        </div>
                    </div>
                    <?php } ?>


                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?=form_button(array(
                                'name' => 'submitf',
                                'id' => 'submitf',
                                'type' => 'submit',
                                'class' => 'btn btn-primary'),
                                lang('common_submit'));
                             ?>
                            <?= form_button(array(
                                    'name' => 'cancel',
                                    'id' => 'cancel',
                                    'class' => 'btn  btn-danger',
                                    'value' => 'true',
                                    'content' => lang('common_cancel')
                                ));

                            ?>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
$('#vista').html('');

var imagen = "<?php echo site_url('app_files/view/' . $person_info->image_id); ?>"
console.log(imagen);
$("#image_id").fileinput({
    initialPreview: [
        "<img src='<?php echo $person_info->image_id ? site_url('app_files/view/' . $person_info->image_id) : base_url() . 'img/avatar.png'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
    ],
    overwriteInitial: true,
    initialCaption: "Avatar"
});
//
$("#phone_number").inputmask({
    "mask": "9",
    "repeat": 12,
    "greedy": false
});
//


$(document).ready(function() {

    //validation and submit handling
    $("#cancel").click(cancelCustomerAddingFromSale);
    setTimeout(function() {
        $(":input:visible:first", "#customer_form").focus();
    }, 100);
    var submitting = false;
    $('#customer_form').validate({
        submitHandler: function(form) {
            $.post('<?php echo site_url("customers/check_duplicate");?>', {
                    term: $('#first_name').val() + ' ' + $('#last_name').val()
                }, function(data) {

                    <?php if(!$person_info->person_id) { ?>
                    if (data.duplicate) {

                        if (confirm(
                                <?php echo json_encode(lang('customers_duplicate_exists'));?>
                            )) {
                            doCustomerSubmit(form);
                        } else {
                            return false;
                        }
                    }
                    <?php } else ?> {
                        doCustomerSubmit(form);
                    }
                }, "json")
                .error(function() {});

        },
        rules: {
            <?php if(!$person_info->person_id) { ?>
            account_number: {
                remote: {
                    url: "<?php echo site_url('customers/account_number_exists');?>",
                    type: "post"

                }
            },
            <?php } ?>
            first_name: "required"
        },
        errorClass: "text-danger",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
        },
        messages: {
            <?php if(!$person_info->person_id) { ?>
            account_number: {
                remote: <?php echo json_encode(lang('common_account_number_exists')); ?>
            },
            <?php } ?>
            first_name: <?php echo json_encode(lang('common_first_name_required')); ?>,
            last_name: <?php echo json_encode(lang('common_last_name_required')); ?>
        }
    });
});

var submitting = false;

function get_info_customer(id_customer) {
    $('#vista').load("<?php echo site_url() ?>/technical_supports/getClientsInfo/", "hc=" + id_customer);
    $('#vista2').load("<?php echo site_url() ?>/technical_supports/getClientsInfo2/", "hc=" + id_customer);
}


function doCustomerSubmit(form) {
    $("#form").plainOverlay('show');
    if (submitting) return;
    submitting = true;

    $(form).ajaxSubmit({
        success: function(response) {
            //$( "#customer" ).val(response.person_id);

            get_info_customer(response.person_id);

            $("#form").plainOverlay('hide');
            submitting = false;
            if (response.success) {
                toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> + ' #' +
                    response.person_id);
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
            } else {
                toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
            }
            /* if (response.redirect_code == 1 && response.success) {
                 $.post('<?php echo site_url("sales/select_customer");?>', {
                     customer: response.person_id
                 }, function() {
                     window.location.href = '<?php echo site_url('sales'); ?>'
                 });
             } else if (response.redirect_code == 2 && response.success) {
                 window.location.href = '<?php echo site_url('customers'); ?>'
             }*/

        },
        <?php if(!$person_info->person_id) { ?>
        resetForm: true,
        <?php } ?>
        dataType: 'json'
    });
}

function cancelCustomerAddingFromSale() {
    if (confirm(<?php echo json_encode(lang('customers_are_you_sure_cancel')); ?>)) {
        window.location = <?php echo json_encode(site_url('technical_supports')); ?>;
    }
}
</script>