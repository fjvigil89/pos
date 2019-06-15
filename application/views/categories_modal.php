<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        Perzonalizar Categorías
                    </span>
                </div>
                <div class="tools">
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <?php echo form_open('config/save_catebory',array('id'=>'form_category','class'=>'')); ?>
                                <div class="portlet-body form ">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12 ">
                                                    <div class="form-group">
                                                        <label for="conetario"
                                                            class="col-md-12 control-label requireds ">
                                                            <a class="help_config_required   tooltips"
                                                                data-placement="left" title="">Nombre:</a>
                                                        </label>

                                                        <div class="col-md-12">
                                                            <?php echo form_input(array(
															'name'=>'name',
															'id'=>'name',
															"value"=>"",
															'class' => ' form-control'));
														?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 ">
                                                    <div class="form-group">
                                                        <label for="conetario" class="col-md-12 control-label ">
                                                            <?=lang("items_image")?>:
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
															'value'=>""));
														?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 ">
                                                    <p style="color:red">
                                                        *Tamaño máximo por imagen 5MG. - Tipo permitido: .gif, .jpg, .jpeg, o ,png<br>
                                                        *Ancho y alto máximo 2000px x 2000px
                                                    </p>
                                                </div>

                                                <div class="   col-md-4 col-md-offset-5 margin-top-10 ">
                                                    <button id="submit-button" name="submit"
                                                        class="btn green">Guardar</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-body">
                                <table class="table table-bordered table-striped " id="table-categories">
                                    <thead>
                                        <tr>
                                            <th class="numeric"> Nombre </th>
                                            <th class="numeric"> Image </th>
                                            <th class="numeric"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category) { 
											$img = 	$category["img"] ? $path_img."/".$category["img"] :"img/no-photo.jpg";
										?>

                                        <tr>
                                            <td width="20%"> <?=$category["name"]?> </td>
                                            <td width="15%" align="center">
                                                <a href="<?=base_url().$img?>" class=""
                                                    title="<?=$category["name_img_original"]?>">
                                                    <img id="" src="<?=base_url().$img?>" width="40" height="35"
                                                        class="img-polaroid">
                                                </a>
                                            </td>
                                            <td width="12%" class="text-center">
                                                <a href="javascript:void(0)"
                                                    onclick="editar_category(<?=$category['id']?>,this)"
                                                    class="btn btn-xs  default update-items"
                                                    title="Actualizar Artículo"><i class="fa fa-pencil"></i>Editar</a>
                                                <a onclick=" delete_category(<?=$category['id']?>,this)"
                                                    href="javascript:void(0)" class="btn btn-xs   btn-danger"
                                                    title="Actualizar Artículo"><i class="fa fa-remove"></i>Eliminar</a>

                                            </td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 text-center">
                        <div class="form-actions ">

                            <?php echo form_button(array(
								'type'=>'button',								
								'id'=>'cancelar',
								'content'=>"Cancelar",
								'class'=>'btn red-haze')
							);?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$("#cancelar").click(function() {
    $("#close").click();
});

$("#image").fileinput({
    initialPreview: [],
    overwriteInitial: true,
    initialCaption: ""
});
var url_img = "<?=base_url().$path_img?>";
var url_2 = '<?=site_url("config/save_catebory")?>';
$('#table-categories').DataTable();


function delete_category(id, elemento) {
    var url = '<?=site_url("config/delete_category")?>/' + id;
    if (confirm("¿Desea eliminar esta categoría?")) {
        $.post(url, {}, function(data) {
            $(elemento).closest('tr').remove();
        });
    }

}

function send_form_category() {
    $("#submit-button").html("Guardando...");

    $("#submit-button").prop('disabled', true);
        
    var data = new FormData();
    jQuery.each($('input[type=file]')[0].files, function(i, file) {
        data.append('image', file);
    });
    var other_data = $("#form_category").serializeArray();
    $.each(other_data, function(key, input) {
        data.append(input.name, input.value);
    });
    jQuery.ajax({
        url: $("#form_category").attr('action'),
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
            $("#submit-button").html("Guardar");
            $("#submit-button").prop('disabled', false);
            data = JSON.parse(data);

            if (data.success == true) {

                if (data["data"].is_new) {
                    var _img
                    if (data["data"].img != "" && data["data"].img != null) {
                        _img = url_img + "/" + data["data"].img
                    } else {
                        _img = "<?=base_url()?>img/no-photo.jpg"
                    }
                    $('#table-categories').dataTable().fnAddData(
                        [
                            data["data"].name,
                            `<div class="text-center"> <img width="40" height="35" class="img-polaroid" src="${_img}"></div>`,
                            ""
                        ]
                    );
                    toastr.success("Registro Guardado...");

                } else
                    toastr.success("Registro actualizado...");

                $('#form_category').attr('action', url_2);
                $('#form_category')[0].reset();
            } else {
                toastr.error(data.message, <?=json_encode(lang('common_error'))?>);

                $("#submit-button").html("Guardar");
                $("#submit-button").prop('disabled', false)
            }
        }
    });
}

function editar_category(id, elemento) {
    var url = '<?=site_url("config/get_category")?>/' + id;

    $.get(url, {}, function(data) {
        data = JSON.parse(data);

        if (data.existe) {
            $('#form_category').attr('action', url_2 + "/" + id);
            $("#name").val(data.data.name);
            $("#name").focus();
        } else
            toastr.error("Categoría no no existe ", <?=json_encode(lang('common_error'))?>);
    });
}

$('#form_category').validate({
    submitHandler: function(form) {
        send_form_category()

    },
    errorClass: "text-danger",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
    },
    rules: {
        name: {
            required: true,
        }
    },
    messages: {

        name: {
            required: "Nombre es requerido",
        }
    }
});
</script>