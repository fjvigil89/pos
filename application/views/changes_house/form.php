
<div class="row">
    <div class="col-md-8 col-lg-8">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="glyphicon glyphicon-th font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">  <?php echo $type_employee=="credito"? "Saldo: " .to_currency($balance ): "Datos"?></span>
                 </div>
                
                 <div class="actions">
                     <button  class="btn red-thunderbird"  id="cancel_sale_button">Cancelar</button>
                    <div class="btn-group">
                  
                      
                        <a class="btn btn-sm green dropdown-toggle" href="javascript:;" data-original-title="<?php echo lang('more_options_help') ?> " data-toggle="dropdown"> MÁS OPCIONES
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu pull-right">

                           <?php if ($this->config->item('track_cash')) {?>
								<li>
									<?php echo anchor(site_url('sales/closeregister?continue=home'),
                                        lang('sales_close_register'),
                                        array('class' => ''));
                                    ?>
								</li>
							<?php }?>
                            
                        </ul>
                     </div>
                </div>
            </div>
            <div class="portlet-body form" id="form">
            <?php echo form_open('sales/add_transaction/', array('id' => 'data_form', 'class' => '', 'autocomplete' => 'off')); ?>

                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label(lang('customers_customer').':', 'customer',array('class'=>'control-label ')); ?>
                                    <?php echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name' => 'customer1',
                                    'id' => 'customer1',
                                    'placeholder' => "Escribe la indentificación, # de cuenta o nombre",
                                    'value' => '')); ?>
                                   

						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label('<a class="help_config_required  tooltips" >'.lang('change_item').'</a>'.':', 'cash',array('class'=>'control-label requireds')); ?>

                                    <?php echo form_dropdown('item', $items,
                                        "", 'class="bs-select form-control" id="item"')
                                    ?>

						        </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label('<a class="help_config_required  tooltips" >'.lang('change_account_number').'</a>'.':', 'numero_cuenta',array('class'=>'control-label requireds')); ?>

							        <?php echo form_input(array(
                                    'class' => 'form-control form-inps',
                                    'name' => 'numero_cuenta',
                                    'id' => 'numero_cuenta',
                                    "maxlength" => "20",
                                    'placeholder' => lang("change_help_account_number"),
                                    'value' => '')); ?>

                                     <?php echo form_hidden('line',0); ?> 

						        </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <?php echo form_label('<a class="help_config_required  tooltips" >'.lang('change_account_type').'</a>'.':', 'tipo_cuenta',array('class'=>'control-label requireds')); ?>

                                    <?php echo form_dropdown('tipo_cuenta', array(null => "Tipo de cuenta",
                                        "Corriente" => "Corriente",
                                        "Ahorro" => "Ahorro"),
                                        "", 'class="bs-select form-control " id="tipo_cuenta"')
                                    ?>
						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label('<a class="help_config_required  tooltips" >'.lang('change_document').'</a>'.':', 'docuemento',array('class'=>'control-label requireds')); ?>

							         <div class="input-group select2-bootstrap-prepend">
                                        <?php echo form_input(array(
                                        'class' => 'form-control form-inps',
                                        'name' => 'docuemento',
                                        'id' => 'docuemento',
                                        'placeholder' => lang("change_help_document_holder"),
                                        'value' => "")); ?>
                                        <div class="input-group-btn">
                                             <?php echo form_dropdown('tipo_documento',
                                                array(
                                                     null => "Tipo",
                                                        "C.I" => "C. de Identidad",
                                                        "C.E" => "C. Extrangera",
                                                    ),
                                                "C.I", 'class="bs-select form-control" id="tipo_documento"')
                                                ?>

                                        </div>
                                    </div>
						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <?php echo form_label('<a class="help_config_required  tooltips" >'.lang('change_account_holder').'</a>'.':', 'titular_cuenta',array('class'=>'control-label requireds')); ?>


							        <?php echo form_input(array(
                                        'class' => 'form-control form-inps',
                                        'name' => 'titular_cuenta',
                                        'id' => 'titular_cuenta',
                                        'placeholder' => lang("change_help_account_holder"),
                                        'value' => "")); ?>

						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label("Celular(whatsapp):", 'celular', array('class' => 'control-label ')); ?>


                                        <?php echo form_textarea(array('name'=>'celular', 'id' => 'celular', 'class'=>'form-control form-textarea','value'=>"",'rows'=>'1',  'accesskey' => 'o')); 

                                        ?>
						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label("Observaciones para esta transferencia:", 'observaciones', array('class' => 'control-label ')); ?>


                                        <?php   echo form_textarea(array('name'=>'observaciones', 'id' => 'observaciones', 'class'=>'form-control form-textarea','value'=>"",'rows'=>'1',  'accesskey' => 'o'));

                                        ?>
						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label('<a class="help_config_required  tooltips" >Valor a transferir</a>'.':', 'cantidad_peso',array('class'=>'control-label requireds')); ?>

                                        <?php echo form_input(array(
                                             'class' => 'form-control form-inps',
                                             'name' => 'cantidad_peso',
                                             'id' => 'cantidad_peso',
                                             "step" => 1,
                                             'placeholder' => lang("change_help_value"),
                                             'value' => "0")); 
                                        ?>
                                       
						        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo form_label("Valor en ".lang("sales_".$this->config->item('divisa')) . ':', 'cantidad_divisa', array('class' => 'control-label ')); ?>


                                        <?php echo form_input(array(
                                             'class' => 'form-control form-inps',
                                             'name' => 'cantidad_divisa',
                                             'id' => 'cantidad_divisa',
                                             "step" => 1,
                                             "type"=>"number",
                                             "min"=>0.0001,
                                             'placeholder' => "Ingrese la cantidad en ".lang("sales_".$this->config->item('divisa')),
                                             'value' => "")); 
                                        ?>
						        </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <p style="color:red;" id="mensaje-erro"></p>
							         
						        </div>
                            </div>
                            <div class="col-md-12">
                                <div class="md-checkbox-inline">
                                    <div class="md-checkbox">
                                        <?= form_checkbox(array(
                                            'name'=>'generate_txt',
                                            'id'=>'generate_txt',
                                            'value'=>'1',
                                            'class'=>'md-check',
                                            'checked'=>(boolean)$generate_txt)
                                        );?>

                                        <label id="generate_txt" for="generate_txt">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        <?= lang("sales_generate_txt"); ?>
                                        </label>
                                        </div>
                                    </div>                                
                            </div>
                            <div class=" col-xs-12 col-xs-offset-4 col-md-4 col-md-offset-5 margin-top-10 ">
                                    <div id="contenedor_boton">
                                        <button id="submit-button" name="submit" class="btn green">Enviar</button>
                                        <button id="reset-button" type="button" class="btn default">Limpiar</button>
                                    </div>
								</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4" id="resumen-venta">        
       <?php $this->load->view('changes_house/resumen_venta');?>   
    
    </div>
    
</div>
<script>
	
        function es_valida_select(){
            let mensaje="";
            let valido=true;
            if($("#item").val()==""){
                mensaje="*El banco es obligatorio";
                valido=false;

            }else if($("#tipo_cuenta").val()==""){
                mensaje="*El tipo de cuenta es obligatorio";
                valido=false;
            }
            else if($("#tipo_docuemento").val()==""){
                mensaje="*El tipo de documento es obligatorio";
                valido=false;
            }
            
            

            $("#mensaje-erro").html(mensaje);
            return valido;
        }
        
    $(document).ready(function()
	{
        $('#generate_txt').change(function()
		{
			$.post('<?php echo site_url("sales/set_generate_txt");?>', {generate_txt:$('#generate_txt').is(':checked') ? '1' : '0'});
		});
        $( function() { 
            $( "#customer1" ).autocomplete({
            minLength: 0,
            source: '<?php echo site_url("sales/items_sales_search"); ?>',
            focus: function( event, ui ) {
                return false;
            },
            select: function( event, ui ) {
                $( "#customer1" ).val( ui.item.value );
                $( "#numero_cuenta" ).val( ui.item.numero_cuenta );
                $( "#titular_cuenta" ).val( ui.item.titular_cuenta );
                $( "#celular" ).val( ui.item.celular );
                $("#docuemento").val(ui.item.value);
                $('#item > option[value="'+ ui.item.item_id +'"]').attr('selected',true);
                $('#tipo_cuenta > option[value="'+ ui.item.tipo_cuenta +'"]').attr('selected',true);
                $('#tipo_documento > option[value="'+ ui.item.tipo_documento +'"]').attr('selected',true);
                $('.bs-select').selectpicker('refresh');
                return false;
            }
            }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                return $( "<li>" )
                    .append( "<div>"+
                    "<strong>Cuenta: </strong>" + item.numero_cuenta +" - "+item.banco + "<br>"+
                    "<strong>Identificación: </strong>" + item.value +"<br>"+
                    "<strong>Titular: </strong>" + item.titular_cuenta + "</div>" )
                    .appendTo( ul );
                };
        } );
        $('#data_form').validate(
            {
			    submitHandler:function(form)
			    {
                   if(es_valida_select()){
                        //$("#contenedor_boton").hide();
                        $("#submit-button").attr("disabled", true);
                        $("#submit-button").html("Enviando...");
                        var url =$(form).attr('action');                            
			            var datos = $(form).serialize(); 
			            $.ajax({
				            type: 'POST',
				            url: url,
				            data: datos,
				            beforeSend: salesBeforeSubmit1, 
				            success: function(data){
                                $("#resumen-venta").html(data);
                            }  
			            });
                   }			                
			    },
				errorClass: "text-danger",
				errorElement: "span",
					highlight:function(element, errorClass, validClass) {
						$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
					},
				rules:
				{
                   numero_cuenta:
				    {
						required:true,
                        maxlength:20,
                        minlength:20
					},
                    docuemento:
					{
						required:true,
					},
                    titular_cuenta:
					{
						required:true,
					},
                    cantidad_peso:
					{
						required:true,
						number:true,
                        min:1,
                        <?php if($type_employee=="credito"){
                           echo "max:" . to_currency_no_money($balance).","?>
                        <?php } ?>
                       
					},item:{
                        required:true,
                    },
                   

				},
				messages:
				{
                    numero_cuenta:
					{
						required: "Número de cuenta es obligatirio.",
                        maxlength:"Número de cuenta debe tener 20 digítos.",
                        minlength:"Número de cuenta debe tener 20 digítos"

					},
                    docuemento:
					{
						required: "Documento es  obligatirio.",                       

					},
                    titular_cuenta:
					{
						required: "El titular de la cuenta es obligatorio.",                       

					},
                    cantidad_peso:
					{
                        required: "La cantidad a transferir es obligatoria.", 
                        number:"La cantidad a transferir debe ser númerica",
                        min:"El valor minimo es <?php echo to_currency(1)?>",
                        <?php if($type_employee=="credito"){
                           echo "max: 'El valor supera el saldo  de su cuenta',"; ?>
                        <?php } ?>

                    },
				}
			});

      $("#cantidad_divisa").change(function(){
          //$("#contenedor_boton").hide();
          $("#submit-button").attr("disabled", true);
          $("#submit-button").html("Enviando...");
          $("#mensaje-erro").html("");
           $.post('<?php echo site_url("changes_house/get_total_peso");?>', 
           {
               cantidad_peso: $('#cantidad_divisa').val()
            },
            function(respuesta){
               
                respuesta=JSON.parse(respuesta);
                if(respuesta.respuesta==true){
                    $("#cantidad_peso").val(respuesta.data);
                }
                //$("#contenedor_boton").show();
                $("#submit-button").removeAttr("disabled");
                $("#submit-button").html("Enviar");

            }
           );

        });

        $("#cantidad_peso").change(function(){
          //$("#contenedor_boton").hide();
          $("#submit-button").attr("disabled", true);
          $("#submit-button").html("Enviando...");
          $("#mensaje-erro").html("");
           $.post('<?php echo site_url("changes_house/get_total_divisa");?>', 
           {
               cantidad_peso: $('#cantidad_peso').val(),
               opcion_sale:"venta"
            },
            function(respuesta){
               
                respuesta=JSON.parse(respuesta);
                if(respuesta.respuesta==true){
                    $("#cantidad_divisa").val(respuesta.data);
                }
                //$("#contenedor_boton").show();
                $("#submit-button").removeAttr("disabled");
                $("#submit-button").html("Enviar");

            }
           );

        });
        
       
        $("select").change(function(){
            $("#mensaje-erro").html("");
        });
        $("#cancel_sale_button").click(function(){
				if (confirm(<?php echo json_encode(lang("sales_confirm_cancel_sale")); ?>))
				{
					$("#resumen-venta").load("<?php echo site_url("sales/cancel_sale"); ?>");
				}
		});
        $('#reset-button').click(function(){
            $('#data_form')[0].reset();
            $("input[name='line']").val(0);
		    $('#item').val("");
		    $("#tipo_cuenta").val("");
		    $("#tipo_docuemento").val("C.I");
		    $('#cantidad_peso').val(0);	
		    $('#item').prop('disabled', false);
		    $('#item').selectpicker('refresh');
		    $('#tipo_documento').selectpicker('refresh');
		    $('#tipo_cuenta').selectpicker('refresh');		
        });
        $("#cantidad_peso").click(function() {
				$(this).select();
		});

    });
</script>







