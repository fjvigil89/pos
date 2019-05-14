<div class="modal-dialog modal-md ">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-camera"></i>
                    <span class="caption-subject bold">
                        <?=lang("items_image")?>
                    </span>
                </div>
                <div class="tools">
                    <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-sm-12 ">
                        <?php echo form_open_multipart('viewers/save_img/'.$id,array('id'=>'item_form')); ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="form-group">
                                        <label for="title" class="col-md-12 control-label wide">
                                            <a class="help_config_options tooltips" data-placement="left"
                                                title=""><?=lang("common_title")?></a>
                                        </label>
                                        <div class="col-md-12">
                                            <?php echo form_input(array(
                                                    'name'=>'title',
                                                    'id'=>'title',
                                                    "value"=> $image_data->title,
                                                    'class' => ' form-control'));
                                                ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group">
                                        <label for="conetario"
                                            class="col-md-12 control-label wide"><?=lang("items_description")?>:</label>
                                        <div class="col-md-12">
                                            <textarea id="description" name="description"
                                                class="form-control form-textarea" row="50" cols="17"><?=$image_data->description?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group">
                                        <label for="conetario" class="col-md-12 control-label requireds ">
                                            <a class="help_config_required   tooltips" data-placement="left"
                                                title=""><?=lang("items_image")?>:</a>
                                        </label>
                                        <div class="col-md-12">
                                            <?php echo form_upload(array(
                                                    'name'=>'image',
                                                    'id'=>'image',
                                                    "accept"=> "image/png, .jpeg, .jpg, image/gif",
                                                    'class' => 'file form-control',
                                                    'multiple' => "false",
                                                    'data-show-upload' => 'false',
                                                    'data-show-remove' => 'false',
                                                    'value'=>$image_data->original_name));
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="   col-md-4 col-md-offset-5 margin-top-10 ">
                                    <button id="submit-button" name="submit" class="btn green">Guardar</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        $("#image").fileinput({
        initialPreview: [
            <?php if($image_data->id > 0):?>
                "<img src='<?= $image_data->id ? base_url(). PATH_RECUSE."/".$store."/img/$image_data->new_name": base_url().'img/no-photo.jpg'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"
            <?php endif;?>
        ],
        overwriteInitial: true,
        initialCaption: "<?=$image_data->original_name? $image_data->original_name :""?>"
        });
        $("#item_form").submit(function(e) {
            e.preventDefault();
            $("#submit-button").html("Guardando...");

            $("#submit-button").prop('disabled', true);
            var data = new FormData();
            jQuery.each($('input[type=file]')[0].files, function(i, file) {
                data.append('image', file);
            });
            var other_data = $("#item_form").serializeArray();
            $.each(other_data, function(key, input) {
                data.append(input.name, input.value);
            });
            jQuery.ajax({
                url: $("#item_form").attr('action'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) 
                { 
                    data = JSON.parse(data);
                    if (data.success == true) 
                    {
                        /*$.get( "<?=site_url("viewers/table_manage")?>", function( data ) {
                            $( "#manage_table" ).html(data); */ 
                            location.reload();                         
                            $("#close").click();
                        //});                      
                    } 
                    else 
                    {
                        toastr.error(data.message, <?=json_encode(lang('common_error'))?>);

                        $("#submit-button").html("Guardar");
                        $("#submit-button").prop('disabled', false)
                    }
                }
            });
        });
</script>