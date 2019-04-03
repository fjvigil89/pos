<!--<div class="row">
    <div class="col-md-12 ">

        <div class="pull-right margin-bottom-10">
            <div class="btn-group">
                <?php if($support->state!=lang("technical_supports_rechazado") && $support->state!=lang("technical_supports_retirado") && $support->state!=lang("technical_supports_reparado") ) echo 
										anchor("$controller_name/rechazar/".$support->Id_support,
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang($controller_name.'_rechazar').'"></i> <span class="visible-lg">'.lang($controller_name.'_rechazar').'</span>',
											array('class'=>'btn btn-medium green-seagreen effect', "id"=>"rechazar",
												'title'=>lang($controller_name.'_rechazar')));
									?>
            </div>
        </div>
        <div class="pull-right margin-bottom-10">
            <div class="btn-group">
                <?php if($support->state==lang("technical_supports_diagnosticado")) echo 
										anchor("$controller_name/aceptar/".$support->Id_support,
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang($controller_name.'_aprobar').'"></i> <span class="visible-lg">'.lang($controller_name.'_aprobar').'</span>',
											array('class'=>'btn btn-medium green-seagreen effect', "id"=>"aprobar",
												'title'=>lang($controller_name.'_rechazar')));
									?>
            </div>
        </div>
        <?php if($support->state==lang("technical_supports_diagnosticado") || $support->state==lang("technical_supports_aprobado")){?>
        <div class="pull-right margin-bottom-10">
            <div class="btn-group">
                <?php echo 
										anchor("$controller_name/return_state/".$support->Id_support."/".lang("technical_supports_recibido"),
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang($controller_name.'_rechazar').'"></i> <span class="visible-lg">Volver a diagnaosticar</span>',
											array('class'=>'btn btn-medium green-seagreen retunrn effect',
												'title'=>""));
									?>
            </div>
        </div>
        <?php } ?>
        <?php if($support->state==lang("technical_supports_reparado") ){?>
        <div class="pull-right margin-bottom-10">
            <div class="btn-group">
                <?php echo 
										anchor("$controller_name/return_state/".$support->Id_support."/".lang("technical_supports_aprobado") ,
											'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title=""></i> <span class="visible-lg">Volver a reparación</span>',
											array('class'=>'btn btn-medium green-seagreen effect retunrn', 
												'title'=>""));
									?>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="col-md-12 ">
        <div class="portlet-body form">
            <div id="ajax-loader" style="display: none;">
                <?php echo lang('common_wait')." ".img(array('src' => base_url().'/img/ajax-loader.gif')); ?></div>

            <?php echo form_open('technical_supports/repair_save/'.$support->Id_support,array('id'=>'support_form','class'=>'form-horizontal')); 	?>
            <div class="form-body">
                <div class="form-group">
                    <label class="  control-label col-md-3"><?php echo lang('technical_supports_order_n').':'?> </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'oder',
													'id'=>'order',
													"disabled"=>"",
													'value'=>$support->order_support)
												);?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="  control-label col-md-3"><?php echo lang('common_first_name').':'?> </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'name',
													'id'=>'name',
													"disabled"=>"",
													'value'=>$customer->first_name." ".$customer->last_name)
												);?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="  control-label col-md-3"><?php echo lang('common_type_team_help').':'?> </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'team',
													'id'=>'team',
													"disabled"=>"",
													'value'=>$support->type_team)
												);?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="  control-label col-md-3"><?php echo lang('technical_supports_model').':'?> </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'model',
													'id'=>'model',
													"disabled"=>"",
													'value'=>$support->model)
												);?>
                    </div>
                </div>
                <div class="form-group">
                    <label
                        class=" control-label col-md-3"><?php echo ($this->config->item("custom1_support_name")? $this->config->item("custom1_support_name"):"Personalizado 1").':'?>
                    </label>

                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'custom1_support_name',
													'id'=>'custom1_support_name',
													"disabled"=>"",
													'value'=>$support->custom1_support_name));?>
                    </div>
                </div>
                <div class="form-group">
                    <label
                        class=" control-label col-md-3"><?php echo ($this->config->item("custom2_support_name")? $this->config->item("custom2_support_name"):"Personalizado 2").':'?>
                    </label>

                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'custom2_support_name',
													'id'=>'custom2_support_name',
													"disabled"=>"",
													'value'=> $support->custom2_support_name));?>
                    </div>
                </div>
                <div class="form-group">
                    <label class=" control-label col-md-3"><?php echo    lang("technical_supports_estado").':'?>
                    </label>

                    <div class="col-md-8">
                        <?php echo form_input(array(
													'class'=>'form-control form-inps',
													'name'=>'state',
													'id'=>'estate',
													"disabled"=>"",
													'value'=> $support->state));?>
                    </div>
                </div>
                <div class="form-group ">
                    <label class=" control-label col-md-3"><?php echo lang("technical_supports_falla_tecnica").':'?>
                    </label>
                    <div class="col-md-8">
                        <?php 
												$damage_failure=$support->damage_failure;
													if($support->damage_failure=="Otro"){
														$damage_failure=$support->damage_failure_peronalizada;
													}
													$data_test = array(
														'name'        => 'damage_failure',
														'id'          => 'damage_failure',
														'value'       => $damage_failure,
														'rows'        => '2',
														'cols'        => '10',
														"disabled"=>"",
														'style'       => 'width:100%',
														'class'       => 'form-control'
													);							
													echo form_textarea($data_test);
												?>
                    </div>
                </div>
                <?php if($support->state==lang("technical_supports_diagnosticado") || $support->state==lang("technical_supports_reparado") || $support->state==lang("technical_supports_aprobado")):?>
                <div class="form-group ">
                    <label class=" control-label col-md-3"><?php echo lang("technical_supports_falla_tecnica2").':'?>
                    </label>
                    <div class="col-md-8">
                        <?php 
													
														$data_test = array(
															'name'        => 'damage_failure_tecnica_',
															
															'value'       => $support->technical_failure,
															'rows'        => '2',
															'cols'        => '10',
															"disabled"=>"",
															'style'       => 'width:100%',
															'class'       => 'form-control'
														);							
														echo form_textarea($data_test);
													?>
                    </div>
                </div>
                <?php endif?>
                <?php if($support->state!=lang("technical_supports_recibido")):?>

                <div class="form-group ">
                    <label class=" control-label col-md-3"> </label>
                    <div class="col-md-8">
                        <table id="respuestos_agregados" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:100%" colspan="7"><?php echo "Respuestos"; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <?php if($support->state!=lang("technical_supports_reparado") && $support->state!=lang("technical_supports_retirado") ):?>
                                    <th></th>
                                    <?php endif?>
                                    <th>Serie</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <?php foreach($spare_parts->result()  as $spare_part): ?>
                            <tr>
                                <?php if($support->state!=lang("technical_supports_reparado")&& $support->state!=lang("technical_supports_retirado")  ):?>

                                <td class="text-center">
                                    <?php echo anchor("technical_supports/delete_spare/$spare_part->id",'<i class="fa fa-trash-o fa fa-2x font-red"></i>', array('class' => 'spare_part'));?>
                                </td>
                                <?php endif?>
                                <td class="text-center"><?php  echo $spare_part->serie ?></td>
                                <td class="text-center"><?php  echo $spare_part->name ?></td>
                                <td class="text-center"><?php  echo $spare_part->quantity ?></td>


                            </tr>
                            <?php endforeach;?>
                            <?php if($spare_parts->num_rows()==0): ?>
                            <tr>
                                <th class="text-center" style="width:100%" colspan="7">
                                    <?php echo "No hay respuestos para mostrar"; ?></th>
                            </tr>
                            <?php endif;?>

                        </table>
                    </div>
                </div>
                <?php endif?>
                <?php if($support->state==lang("technical_supports_retirado")  ):?>

                <div class="form-group ">
                    <label class="  control-label col-md-3"><?php echo lang('technical_supports_garantia').'</a>'.':'?>
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
														'class'=>'form-control form-inps',
														'name'=>'date_garantia_',
														"disabled"=>"",
														'value'=>$support->date_garantia!="0000-00-00" ? date(get_date_format(), strtotime($support->date_garantia)) : 'No tiene garantía'));
													?>

                    </div>
                </div>
                <div class="form-group ">
                    <label
                        class="  control-label col-md-3"><?php echo lang('technical_supports_observaciones').'</a>'.':'?>
                    </label>
                    <div class="col-md-8">
                        <?php 												
														$data_test = array(
															'name'        => 'observacion_entrega_',
															'value'       => $support->observaciones_entrega,
															'rows'        => '2',
															'cols'        => '10',
															"maxlength"	  =>"200",
															"disabled"=>"",
															'style'       => 'width:100%',
															'class'       => 'form-control'
														);							
														echo form_textarea($data_test);
													?>


                    </div>
                </div>
                <div class="form-group">
                    <label class=" control-label col-md-3"><?php echo lang("technical_supports_repair_cost").':'?>
                    </label>

                    <div class="col-md-8">
                        <?php echo form_input(array(
														'class'=>'form-control form-inps',
														'name'=>'repair_cost_',
														"type"=>"number",
														"disabled"=>"",
														'value'=>$support->repair_cost !="" ? $support->repair_cost:0));?>
                    </div>
                </div>
                <?php endif?>

                <div class="form-group ">
                    <hr class="col-md-8 col-md-offset-3">
                </div>
                <?php if($support->state==lang("technical_supports_recibido")):?>
                <div class="form-group ">
                    <label
                        class="required  control-label col-md-3"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("technical_supports_falla_tecnica2").'">'.lang('technical_supports_falla_tecnica2').'</a>'.':'?>
                    </label>

                    <div class="col-md-8">
                        <?php 												
														$data_test = array(
															'name'        => 'damage_failure',
															'id'          => 'damage_failure',
															'value'       => $support->technical_failure,
															'rows'        => '2',
															'cols'        => '10',
															"maxlength"	  =>"200",
															"required"	  =>"required",
															'style'       => 'width:100%',
															'class'       => 'form-control'
														);							
														echo form_textarea($data_test);
													?>
                    </div>
                </div>
                <?php endif?>
                <?php if($support->state==lang("technical_supports_diagnosticado") || $support->state==lang("technical_supports_aprobado") ):?>
                <div class="form-group ">
                    <label
                        class="required  control-label col-md-3"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("technical_supports_falla_respuestos").'"></a>'?>
                    </label>

                    <div class="col-md-8">
                        <table id="respuestos_agregados" class="table table-advance table-bordered ">

                            <tr>

                                <th>Serie</th>
                                <th>Nombre</th>
                                <th>Cantida</th>
                            </tr>
                            <tr>

                                <td class="text-center"><input value="" class="form-control form-inps" id="serie"></td>
                                <td class="text-center"><input value="" class="form-control form-inps"
                                        id="name_spare_part" </td> <td class="text-center"><input value="1"
                                        class="form-control form-inps" id="quantity" type="number" </td> </tr> <tr>
                                <td class="text-center" style="width:100%" colspan="7">
                                    <a href="#" id="add_respuesto" class="btn   default btn-clon">Agregar respuesto</a>


                            </tr>

                        </table>

                    </div>
                </div>
                <?php endif?>

                <?php if($support->state==lang("technical_supports_reparado")  ):?>

                <div class="form-group">
                    <?php echo form_label("Tiene Grarantía?".':', '',array('class'=>'col-md-3 control-label ')); ?>
                    <div class="col-md-9">
                        <div class="md-radio-inline">
                            <div class="md-radio">
                                <?php  
																	echo '<div class="md-radio-inline">';
																	echo '<div class="md-radio">';
																	echo form_radio(array(
																		'name'=>'do_have_guarantee',
																		'id'=>'do_have_guarantee_yes',
																		'value'=>'1',
																		'class'=>'md-check',
																		'checked'=>"1"
																		)
																	);                                    
																	echo '<label id="show_comment_invoice" for="do_have_guarantee_yes">';
																	echo '<span></span>';
																	echo '<span class="check"></span>';
																	echo '<span class="box"></span>';
																	echo lang('technical_supports_yes');
																	echo '</label>';
																	echo '</div>';
																	echo '</div>';
																?>
                                <?php  
																	echo '<div class="md-radio-inline">';
																	echo '<div class="md-radio">';
																	echo form_radio(array(
																		'name'=>'do_have_guarantee',
																		'id'=>'do_have_guarantee_no',
																		'value'=>'0',
																		'class'=>'md-check',
																		'checked'=> $support->do_have_guarantee!=0?0:1)
																	);                                    
																	echo '<label  for="do_have_guarantee_no">';
																	echo '<span></span>';
																	echo '<span class="check"></span>';
																	echo '<span class="box"></span>';
																	echo lang('technical_supports_no');
																	echo '</label>';
																	echo '</div>';
																	echo '</div>';
																?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group garantia_ocultar" style="display:none">
                    <label
                        class="required  control-label col-md-3"><?php echo '<a class="help_config_required  tooltips " data-placement="left" title="'.lang("technical_supports_garantia_help").'">'.lang('technical_supports_garantia').'</a>'.':'?>
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input(array(
														'class'=>'form-control form-inps',
														'name'=>'date_garantia',
														"required"	  =>"required",
														'id'=>'date_garantia',
														'value'=>$support->date_garantia!="0000-00-00" ? date(get_date_format(), strtotime($support->date_garantia)) : ''));
													?>

                    </div>
                </div>
                <div class="form-group ">
                    <label
                        class="  control-label col-md-3"><?php echo lang('technical_supports_observaciones').'</a>'.':'?>
                    </label>
                    <div class="col-md-8">
                        <?php 												
														$data_test = array(
															'name'        => 'observacion_entrega',
															'id'          => 'observacion_entrega',
															'value'       => $support->observaciones_entrega,
															'rows'        => '2',
															'cols'        => '10',
															"maxlength"	  =>"200",
															'style'       => 'width:100%',
															'class'       => 'form-control'
														);							
														echo form_textarea($data_test);
													?>


                    </div>
                </div>
                <div class="form-group">
                    <label class=" control-label col-md-3"><?php echo lang("technical_supports_repair_cost").':'?>
                    </label>

                    <div class="col-md-8">
                        <?php echo form_input(array(
														'class'=>'form-control form-inps',
														'name'=>'repair_cost',
														'id'=>'repair_cost',
														"type"=>"number",
														'value'=>$support->repair_cost !="" ? $support->repair_cost:0));?>
                    </div>
                </div>
                <?php endif?>

            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-9">
                        <?php
												
													$valor=$support->state;
													switch ($valor) {
														case lang("technical_supports_recibido"):
															$valor=lang("technical_supports_diagnose");
														break;
														case lang("technical_supports_diagnosticado"):
															$valor=lang("technical_supports_reparar");
														break;
														case lang("technical_supports_aprobado"):
															$valor=lang("technical_supports_reparar");
														break;
														case lang("technical_supports_retirado"):
															$valor="Imprimir";
														break;
														default:
															$valor=lang("technical_supports_entregar");
													}
													if( lang("technical_supports_retirado")!=$support->state){
														echo form_button(array(
															'name'=>'submitf',
															'id'=>'submitf',
															'type' => 'button',
															"value"=>$valor,
															'class'=>'btn btn-primary'),
															$valor);
													}
													if( lang("technical_supports_retirado")==$support->state){
														echo anchor("$controller_name/imprime_order/".$support->Id_support ,
														'<i class="fa fa-pencil hidden-lg fa fa-2x tip-bottom" data-original-title=""></i> <span class="visible-lg">Imprimir</span>',
														array('class'=>'btn btn-medium green-seagreen effect ', 
															'title'=>""));
													}
																			
													echo form_button(array(
													'name' => 'cancel',
													'id' => 'cancel',
													'class' => 'btn  btn-danger',
													'value' => 'true',
													'content' => "Listar"
													));	
																					
												?>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>



<script>
function ocultar(opcion) {
    if (opcion == 1) {
        $('.garantia_ocultar').show(500);
        $('.garantia_ocultar').show("slow");
    } else {
        $('.garantia_ocultar').hide(500);
        $('.garantia_ocultar').hide("fast");
    }
}
$(document).ready(function() {
    ocultar(<?php echo $support->do_have_guarantee ?>);


    $('#do_have_guarantee_yes').change(function() {
        ocultar(1);
    });
    $('#do_have_guarantee_no').change(function() {
        ocultar(0);
    });

    $('#date_garantia').datetimepicker({
        format: <?php echo json_encode(strtoupper(get_js_date_format())); ?>,
        locale: "es"
    });
    $("#submitf").click(function(event) {
        <?php if($support->state==lang("technical_supports_reparado")  ):?>
        if ($('input:radio[name=do_have_guarantee]:checked').val() == 1 && $("#date_garantia").val() ==
            "") {
            <?php  	echo "toastr.error('Fecha  incorrecta', ".json_encode(lang('common_error')).");"; ?>
            return false;
        }
        <?php endif?>

        $('#support_form').submit();
    });
    $(".spare_part").click(function(event) {
        $("#ajax-loader").show();
        $.post('' + $(this).attr('href'), function(data) {
            $("#ajax-loader").hide();
            if (data == 0) {
                <?php  	echo "toastr.error('No fue posible eliminar el respuesto ', ".json_encode(lang('common_error')).");"; ?>
            }

        });
        $(this).closest('tr').remove();
        event.preventDefault();
    });
    $(".retunrn").click(function(event) {
        if (confirm('¿Desea cambiar de estado?')) {
            $("#ajax-loader").show();
            $.post('' + $(this).attr('href'), function(data) {
                $("#ajax-loader").hide();
                $("#contenedor").html(data);

            });
        }
        event.preventDefault();
    });

    $("#add_respuesto").click(function(event) {
        if ($("#name_spare_part").val() != "" && $("#quantity").val() > 0 && $("#serie").val() != "") {
            $("#ajax-loader").show();
            $("#add_respuesto").hide();
            $.post('<?php echo site_url("technical_supports/add_spare_part");?>', {
                    id_support: <?php  echo $support->Id_support?>,
                    serie: $("#serie").val(),
                    name_spare_part: $("#name_spare_part").val(),
                    quantity: $("#quantity").val()
                },
                function(data) {

                    $("#contenedor").html(data);

                });

        } else {
            <?php  	echo "toastr.error('Datos incorrecotos', ".json_encode(lang('common_error')).");"; ?>
        }
        event.preventDefault();
    });


    function supportBeforeSubmit(formData, jqForm, options) {
        $("#ajax-loader").show();
        $("#submitf").hide();

    }
    $('#support_form').ajaxForm({
        target: "#contenedor",
        beforeSubmit: supportBeforeSubmit
    });

    $("#rechazar").click(function(event) {
        if (!confirm('¿Desea rechacar la orden?')) {
            event.preventDefault();
        }

    });
    $("#aprobar").click(function(event) {
        if (!confirm('¿Desea aceptar la orden?')) {
            event.preventDefault();
        }

    });
    $("#cancel").click(function(event) {

        window.location = "<?php echo site_url()."/technical_supports"?>";

    });
});
</script> -->