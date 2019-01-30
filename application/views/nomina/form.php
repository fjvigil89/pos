  <?php $this->load->view("partial/header"); ?>
  <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
      <h1>
        <i class='fa fa-pencil'></i>
            <?php echo  lang("module_nomina")?>
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
	<div class="row">
		<div class="col-md-12 ">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
              <?php echo  lang("nomina_informacion")?>
						</span>
					</div>
				</div>
				<div class="portlet-body">
            <div id="ajax-loader" style="display: none;">
              <?php echo lang('common_wait')." ".img(array('src' => base_url().'/img/ajax-loader.gif')); ?>
            </div>

            <div class="row">
              <div class="col-md-3 "></div>
						<div class="col-md-8 ">
							<div class="pull-right margin-bottom-10">
								<div class="btn-group">

										<a style="display: none;" href="<?php echo base_url().'index.php/nomina/asig_dedu_modal/0'; ?>"id="new-signacion-btn" class="btn btn-success effect" title="<?php echo lang("nomina_nueva_asignacion"); ?>"
                       data-toggle="modal" data-target="#myModal">
                      <i title="Nuevo Asignacion_" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x">
                      </i><span class="visible-lg"><?php echo lang("nomina_nueva_asignacion"); ?></span>
                    </a>
                    <a style="display: none;" href="<?php echo base_url().'index.php/nomina/asig_dedu_modal/1'; ?>"id="new-deduccion-btn" class="btn btn-success effect" title="<?php echo lang("nomina_nueva_deduccion"); ?>"
                       data-toggle="modal" data-target="#myModal">
                      <i title="Nuevo deducción_" class="fa fa-pencil tip-bottom hidden-lg fa fa-2x">
                      </i><span class="visible-lg"><?php echo lang("nomina_nueva_deduccion"); ?></span>
                    </a>

                </div>
							</div>
						</div>
					</div>
				<div class="portlet-body form">
          <?php 	echo form_open('nomina/save/',array('id'=>'nomina_form','class'=>'form-horizontal',
        "autocomplete"=>"off")); ?>
				<!--<form class="form-horizontal" role="form">-->
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo lang("employees_employee"); ?></label>
								<div class="col-md-8">

							   <select id="id_employe"  name="id_employe" class="
						           bs-select form-control" data-live-search="true">
									<option value="-1"> <?php echo lang("employees_slect"); ?></option>
									<?php foreach ($employees as $employe) {	?>
									       <option value="<?php  echo $employe->id;	?>">
										             <?php	echo $employe->id." - ".$employe->first_name." ". $employe->last_name;	?>
									        </option>
									<?php } ?>
								</select>
							</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo lang('common_first_name'); ?></label>
								<div class="col-md-8">
                  <?php echo form_input(array(
                    'name'=>'nombre',
                    'id'=>'nombre',
                    'class'=>'fomr-control form-inps',
                    'value'=>"",
                    'readonly'=>"",
                    'placeholder'=>"Nombre"));
                  ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo lang('common_last_name') ?></label>
								<div class="col-md-8">
                  <?php echo form_input(array(
                    'name'=>'apellidos',
                    'id'=>'apellidos',
                    'class'=>'fomr-control form-inps',
                    'value'=>"",
                    'readonly'=>"",
                  'placeholder'=>"Apellidos"));
                  ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo lang('common_email'); ?></label>
								<div class="col-md-8">
                  <?php echo form_input(array(
                    'name'=>'correo',
                    'id'=>'correo',
                    'class'=>'fomr-control form-inps',
                    'value'=>"",
                    'readonly'=>"",
                    'placeholder'=>"Mail"));
                  ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo lang('common_city'); ?></label>
								<div class="col-md-8">
                  <?php echo form_input(array(
                    'name'=>'ciudad',
                    'id'=>'ciudad',
                    'class'=>'fomr-control form-inps',
                    'value'=>"",
                    'readonly'=>"",
                    'placeholder'=>"Ciudad"));
                  ?>
								</div>
							</div>
							<div class="form-group">

								<label class="col-md-3 control-label"><?php echo lang('common_address_1'); ?></label>
								<div class="col-md-8">
                  <?php echo form_input(array(
                    'name'=>'direccion',
                    'id'=>'direccion',
                    'class'=>'fomr-control form-inps',
                    'value'=>"",
                    'readonly'=>"",
                    'placeholder'=>"Direccion"));
                  ?>
								</div>
							</div>
						</div>
              
				<div class="row">
				<div class="col-md-6">
					<div class="widget-box">
						<div class="widget-title">
              <div class="portlet-title">
					           <div class="caption">
						                <!-- <i class="icon-bar-chart font-green-haze"></i>-->
						                 <span class="caption-subject bold uppercase font-green-haze"><?php echo lang("nomina_asignacion"); ?></span>
					            </div>

				      </div>
						</div>

						<div class="widget-content nopadding" >
              <div id="asignacion">
              </div>
						</div>
					</div>
				</div>

				<div class="col-md-6">



					<div class="widget-box">
						<div class="widget-title">
              <div class="caption">
                    <!--  <i class="icon-bar-chart font-green-haze"></i>-->
                      <span class="caption-subject bold uppercase font-green-haze"><?php echo lang("nomina_deduccion"); ?></span>
               </div>

						</div>

						<div class="widget-content nopadding">
              <div id="deduccion">

              </div>
						</div>
					</div>
				</div>
						</div>
            	</div>
						<div class="row text-center">
							<br>
							<div class="col-md-12">
								<button style="display: none;" type="submit" id="guardar-dedu-asig" class="btn blue"><?php echo lang("common_save"); ?></button>
								<button type="button" class="btn default"><?php echo lang("common_cancel"); ?></button>
							</div>
						</div>
					<!--</form>-->
           <?php echo form_close(); ?>
				</div>

				</div>
			</div>
		</div>
	</div>
  </div>
  <script>
  $(document).ready(function() {
    //no borrar----
        // es utilizado en asig_dedu_modal.php para cargar los datos de la fila a los inputs
    var fila_table_seleccionada=null;
    //------------------
    cargarTable(-1);
    $( "#id_employe" ).change(function() {
      get_dato_employe($( "#id_employe" ).val());
    });

  $("#nomina_form").submit(function (e) {
         e.preventDefault();
        //  if ($("#nomina_form").valid()) {
              var datos = $("#nomina_form").serializeArray();
              $.ajax({
                  data: datos,
                  url: $("#nomina_form").attr('action'),
                  dataType: "json",
                  type: 'post',
                  beforeSend: function () { },
                  success: function (response) {

                         if(response["success"]==="ERROR")
                    			{
                    				toastr.error(response["message"], "<?php echo lang('common_error'); ?>");
                    			}
                    			else if (response["success"]==="OK")
                    			{
                            	toastr.success(response["message"], "<?php echo lang('common_success'); ?>");
                              location.reload();
                    			}

                  }, error: function (error) {
                  	toastr.error("Error", "<?php echo lang('common_error'); ?>");
                  }
              });
          //}
      });

    });
    function get_dato_employe(id_employe_) {
      cargandoShow();
    $.ajax({
    type: "POST",
    url: "<?php  echo
    base_url()."index.php/nomina/data_employe/" ?>"+id_employe_,
    data: {},
    success: function( result ) {
      var obj = JSON.parse(result);
      $("#nombre" ).val(obj["first_name"]);
      $("#apellidos").val(obj["last_name"]);
      $("#correo").val(obj["email"]);
      $("#ciudad").val(obj["city"]);
      $("#direccion").val(obj["address_1"]);
      cargarTable(id_employe_);
      	$("#new-signacion-btn").show();
        $("#new-deduccion-btn").show();
        $("#guardar-dedu-asig").show();
        if(id_employe_==="-1"){
          $("#new-signacion-btn").hide();
          $("#new-deduccion-btn").hide();
          $("#guardar-dedu-asig").hide();
        }
    	$("#ajax-loader").hide();
    }, error: function (error) {
          	$("#ajax-loader").hide();
    }
    });
  }
  function cargandoShow(){
    	$("#ajax-loader").show();
  }
  function cargandoHide(){
    	$("#ajax-loader").hide();
  }

  function cargarTable(id_employe) {
    $("#asignacion").load('<?php  echo
    base_url()."index.php/nomina/getTable/0"?>'+"/"+id_employe);
   $("#deduccion").load('<?php  echo
    base_url()."index.php/nomina/getTable/1"?>'+"/"+id_employe);

  }
  function  eliminarRegistro(elemento){
          var r = confirm("<?php echo lang('nomina_eliminar_registro'); ?>");
          if (r == true) {
            id_seleccionado= $($($(elemento).closest('tr')).find("td")[0]).find("input").val();
            if($.trim(id_seleccionado)<0){
                $(elemento).closest('tr').remove();
            }else{
                $.ajax({
                    url: "<?php echo base_url().'index.php/nomina/deleted_by_id/'?>"+id_seleccionado,
                    dataType: "json",
                    type: 'post',
                    beforeSend: function () { },
                    success: function (response) {
                           if(response["success"]==="ERROR")
                            {
                              toastr.error(response["message"], "<?php echo lang('common_error'); ?>");
                            }
                            else if (response["success"]==="OK")
                            {
                                toastr.success(response["message"], "<?php echo lang('common_success'); ?>");
                                  $(elemento).closest('tr').remove();
                            }

                    }, error: function (error) {
                      toastr.error("Error", "<?php echo lang('common_error'); ?>");
                    }
                });
            }

          }
  }

  function cargarDatosInput(tr) {
    porcentaje_table= $($(tr).find("td")[1]).find("input").val();
    monto_table= $($(tr).find("td")[2]).find("input").val();
    descripcion_table= $($(tr).find("td")[3]).find("input").val();
    // se actuliza el value de los input del modal
    $("#porcentaje").val($.trim(porcentaje_table));
    $("#monton").val($.trim(monto_table));
    $("#descripcion").val($.trim(descripcion_table));

  }
  function actualizar_fila(tr) {
    $(tr).find("td")[1].innerHTML = ""+$("#porcentaje").val()+
		    "<input type='hidden' name='"+$($($(tr).find("td")[1]).find("input")).attr("name")+"' value='"+$("#porcentaje").val()+"'>";
	  $(tr).find("td")[2].innerHTML = ""+$("#monton").val()+
			"<input type='hidden' name='"+$($($(tr).find("td")[1]).find("input")).attr("name")+"' value='"+$("#monton").val()+"'>";
	  $(tr).find("td")[3].innerHTML = ""+$("#descripcion").val()+
				"<input type='hidden' name='"+$($($(tr).find("td")[1]).find("input")).attr("name")+"' value='"+$("#descripcion").val()+"'>";
        	toastr.success("<?php echo lang('nomina_actualizado'); ?>", "<?php echo lang('common_success'); ?>");
  }



function selecciona_fila(elemento) {
  fila_table_seleccionada=$(elemento).parents("tr");
}
  </script>

  <?php $this->load->view("partial/footer"); ?>
