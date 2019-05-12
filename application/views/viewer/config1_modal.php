<div class="modal-dialog modal-md ">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog"></i>
                    <span class="caption-subject bold">
                        <?=lang("common_settings")?>
                    </span>
                </div>
                <div class="tools">
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-sm-12 ">
                        <?php echo form_open_multipart('viewers/save_config1/',array('id'=>'config_form','class'=>'form-horizontal')); ?>
                            <div class="form-body">
                                <div class="panel-group accordion" id="accordion3">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle accordion-toggle-styled collapsed"
                                                    data-toggle="collapse" data-parent="#accordion3" href="#collapse_1"
                                                    aria-expanded="false"> <?=lang("viewer_carousel")?> </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_1" class="panel-collapse collapse" aria-expanded="false"
                                            style="height: 0px;">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <?= form_label('<a class="help_config_options tooltips"  data-placement="left"  title="'.lang("viewer_transition_time").'" >'.lang("viewer_transition_time").'</a>'.':', 'interval_img_carousel',array('class'=>'col-md-7 control-label required')); ?>
                                                    <div class="col-md-3">
                                                        <?= form_dropdown('interval_img_carousel', 
                                                            array(
                                                                "1000"=>"1sg",
                                                                "2000"=>"2sg",
                                                                "3000"=>"3sg",
                                                                "4000"=>"4sg",
                                                                "5000"=>"5sg",
                                                                "6000"=>"6sg",
                                                                "7000"=>"7sg",
                                                                "8000"=>"8sg",
                                                                "9000"=>"9sg",
                                                                "10000"=>"10sg"
                                                            ),
                                                            $this->config->item('interval_img_carousel') ?  $this->config->item('interval_img_carousel'): 3, 
                                                            'class="bs-select form-control"')
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle accordion-toggle-styled collapsed"
                                                    data-toggle="collapse" data-parent="#accordion3" href="#collapse_2"
                                                    aria-expanded="false"> <?= lang('module_viewers'); ?> </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_2" class="panel-collapse collapse" aria-expanded="false"
                                            style="height: 0px;">
                                            <div class="panel-body ">                                            
                                                <div class="form-group">
                                                    <?= form_label(lang("viewer_change_msg").':', 'msg_cange_cart_viewer',array('class'=>'col-md-4 control-label ')); ?>
                                                    <div class="col-md-8">
                                                        <?= form_input(array(
                                                            'class'=>'form-control',
                                                            'name'=>'msg_cange_cart_viewer',
                                                            'id'=>'msg_cange_cart_viewer',
                                                            'value'=>$this->config->item('msg_cange_cart_viewer') ?$this->config->item('msg_cange_cart_viewer') : "Su cambio es "));
                                                        ?>
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                    <?=form_label(lang("viewer_thanks_msg").':', 'msg_thank_cart_viewer',array('class'=>'col-md-4 control-label ')); ?>
                                                    <div class="col-md-8">
                                                        <?= form_input(array(
                                                                'class'=>'form-control',
                                                                'name'=>'msg_thank_cart_viewer',
                                                                'id'=>'msg_thank_cart_viewer',
                                                                'value'=>$this->config->item('msg_thank_cart_viewer') ?$this->config->item('msg_thank_cart_viewer') : "Gracias por su compra"));
                                                            ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=" col-md-4 col-md-offset-5  col-sm-4 col-sm-offset-8 margin-top-10 ">
                                    <button id="submit-button" name="submit" class="btn green">Guardar</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$("#config_form").submit(function(e) {
    e.preventDefault();
    $("#submit-button").html("Guardando...");

    $("#submit-button").prop('disabled', true);

    var data = $("#config_form").serializeArray();
    console.log(data);

    jQuery.ajax({
        url: $("#config_form").attr('action'),
        data: data,
        type: 'POST',
        success: function(data) {
            data = JSON.parse(data);
            if (data.success == true) 
            {
                $("#close").click();
            } 
            else {
                toastr.error(data.message, <?=json_encode(lang('common_error'))?>);

                $("#submit-button").html("Guardar");
                $("#submit-button").prop('disabled', false)
            }
        }
    });
});
</script>