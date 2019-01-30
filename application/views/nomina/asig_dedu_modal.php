<style>
.error {
  color: #F00;

}</style>
<div class="modal-dialog modal-xl">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
											<?php if ($type==="0"){
												echo lang("nomina_informacion_dedu_asig");
											}elseif ($type==="1"){
												echo lang("nomina_informacion_dedu_asig");
											}
                      elseif ($type==="2"){
												echo lang("nomina_informacion_dedu_asig");
											}?>


                        <?php //echo lang("sales_details"); ?>
                    </span>
                </div>
                <div class="tools">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
							<div class="portlet-body form">
				          <?php 	echo form_open('',array('id'=>'datos','class'=>'form-horizontal')); ?>
										<div class="form-body">
											<div class="form-group">
												<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_porcentaje_help").'">'.lang('nomina_porcentaje').'</a>'.':', 'porcentaje',array('class'=>'col-md-3 control-label requireds')); ?>

												<div class="col-md-8">
				                  <?php echo form_input(array(
				                    'name'=>'porcentaje',
				                    'id'=>'porcentaje',
														'type'=>'number',
				                    'class'=>'fomr-control form-inps',
				                    'value'=>"",
				                  'placeholder'=>"",
                          'onkeydown'=>"limpiar_monto()"));
				                  ?>
												</div>
											</div>
											<div class="form-group">
												<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_monton_help").'">'.lang('nomina_monto').'</a>'.':', 'monton',array('class'=>'col-md-3 control-label requireds')); ?>

												<div class="col-md-8">
				                  <?php echo form_input(array(
				                    'name'=>'monton',
				                    'id'=>'monton',
														'type'=>'number',
				                    'class'=>'fomr-control form-inps',
				                    'value'=>"",
                            'onkeydown'=>"limpiar_porcentaje()",
				                  'placeholder'=>""));
				                  ?>
												</div>
											</div>
											<div class="form-group">
												<?php echo form_label('<a class="help_config_required  tooltips " data-placement="left" title="'.lang("common_descripcion_help").'">'.lang('nomina_descripcion').'</a>'.':', 'descripcion',array('class'=>'col-md-3 control-label requireds')); ?>

												<div class="col-md-8">
				                  <?php echo form_input(array(
				                    'name'=>'descripcion',
				                    'id'=>'descripcion',
				                    'class'=>'fomr-control form-inps',
				                    'value'=>"",
				                  'placeholder'=>""));
				                  ?>
												</div>
											</div>
											<div class="row text-center">
												<br>
												<div class="col-md-12">
													<?php if ($type==="0" || $type==="1"): ?>
														<button id="agregar" type="button" class="btn btn-primary"><?php echo lang("common_add"); ?></button>
													<?php endif; ?>
                          <?php if ($type==="2" ): ?>
                            <button id="editar-fila" type="button" class="btn btn-primary"><?php echo lang("common_update")?></button>
                          <?php endif; ?>


												</div>
											</div>

										</div>
									 <?php echo form_close(); ?>
							 </div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		addvalida() ;
    if(<?php echo $type ?>===2){
      //fila_table_seleccionada esta en form.php
      //esta function esta en form.php
      cargarDatosInput(fila_table_seleccionada);
    }
    $("#editar-fila").click(function(){
      if ($("#datos").valid()) {
      actualizar_fila(fila_table_seleccionada);
    }
    });
		$("#agregar").click(function(){
			// asignacion
			if(<?php echo $type ?>===0){
				if ($("#datos").valid()) {
					creafila("asignacion[]","table-asignacion");
				}
			}
			if(<?php echo $type ?>===1){
					if ($("#datos").valid()) {
						creafila("deduccion[]","table-deduccion");
					}
			}
		});
	});

	function addvalida() {
		jQuery.validator.setDefaults({
  		debug: true,
  		success: "valid"
		});
		$("#datos").validate({
			errorClass: 'errors',
				rules: {
					porcentaje: {
		        required: true,
						number: true,
						range: [0, 100]
					},
					monton: {
		        required: true,
						number: true,
						min: 0
					},
					descripcion: {
		        required: true,
					  maxlength: 150
					}
	      },
				messages :
				{
					porcentaje: {
			        required: "El procentaje es requerido",
							required: "Solo se permite número",
							range:"Solo se permite número entre [0 y 100]"
					},
					monton: {
						required: "El monton es requerido",
						required: "Solo se permite número",
						min: "El monton no puede ser negativo"
					},
					descripcion: {
						required: "La descripción es requerida",
						maxlength:"Máximo 150 caracteres"
					}
		   },
            highlight: function (element) {
                $(element).parent().addClass('error')
            },
            unhighlight: function (element) {
                $(element).parent().removeClass('error')
            }
		});
	}
	function creafila(name,tableId) {
    //nuevo_id=nuevo_id-1;
		var fecha="<?php echo date("Y-m-d") ; ?>";
		var table = document.getElementById(tableId);
    var id_fila=((table.rows.length+1)*-1);

    var nuevaFila="<tr> ";
    nuevaFila +="<td  class='hidden-print' style='display: none;'> <input type='checkbox' class='css-checkbox'  name='eliminar_registro'  value='"+id_fila+"'>"+
    "<label for='select_all' class='css-label cb0'></label>"+
    "<input type='hidden' name='"+name+"' value='"+id_fila+"'></td>";

	nuevaFila += "<td>"+$("#porcentaje").val()+
		"<input type='hidden' name='"+name+"' value='"+$("#porcentaje").val()+"'></td>";

		nuevaFila +=  "<td>"+$("#monton").val()+
			"<input type='hidden' name='"+name+"' value='"+$("#monton").val()+"'></td>";

		nuevaFila +=  "<td>"+$("#descripcion").val()+
				"<input type='hidden' name='"+name+"' value='"+$("#descripcion").val()+"'> </td>";
  	nuevaFila +="<td>" +fecha+"</td>";

		nuevaFila += " <td> <a onclick='selecciona_fila(this)' class='btn btn-xs btn-primary btn-editable'"+
       " href='<?php echo base_url().'index.php/nomina/asig_dedu_modal/2'; ?>'"+
       "data-toggle='modal' data-target='#myModal'> <i class='fa fa-pencil'></i> <?php echo lang("common_edit"); ?></a>"+
       "  <button type='button' onclick='eliminarRegistro(this)' class='btn btn-xs btn-danger btn-editable '  >"+
			  " <i class='fa fa-trash-o'></i><?php echo lang("common_delete"); ?></button> </td>";
         nuevaFila+="</tr>";
        $("#"+tableId).append(nuevaFila);
        	toastr.success("<?php echo lang("nomina_agregado"); ?>", "<?php echo lang('common_success'); ?>");
	}
  function limpiar_monto() {
    $("#monton").val("0");
  }
  function limpiar_porcentaje() {
    $("#porcentaje").val("0");
  }
</script>
