<div class="col-sm-9">
	<div class="portlet light form-fit bordered">
		<div class="portlet-title">
                    <div class="caption" style="font-weight: 700;">
				<i class="icon-settings font-dark"></i>
				<span
					class="caption-subject font-dark sbold uppercase"><?php echo lang("technical_supports_information"); ?></span>
			</div>
			<div class="caption pull-right">
				<h4><?php echo lang('common_fields_required_message'); ?></h4>
			</div>
		</div>
		<div class="portlet-body form">
			<!-- BEGIN FORM-->
			<?php echo form_open_multipart('technical_supports/save/' . $Id_support, array('id' => 'support_form', 'class' => 'form-horizontal form-row-seperated', 'autocomplete' => 'off')); ?>

			<div class="form-body">
                            <input type="text" class="hidden" id="account_number" name="account_number" value="<?php echo $customer->person_id; ?>"> 
				<input type="text" class="hidden" id="first_name" name="first_name"
					   value="<?php echo $customer->first_name ?>">
				<input type="text" class="hidden" id="last_name" name="last_name"
					   value="<?php echo $customer->last_name ?>">
				<input type="text" class="hidden" id="email" name="email" value="<?php echo $customer->email ?>">
				<input type="text" class="hidden" id="phone_number" name="phone_number"
					   value="<?php echo $customer->phone_number ?>">
                                <div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="required control-label">
							<a class="help_config_required tooltips" data-placement="left"
							   title="<?php echo lang("common_id_employee_help") ?>"><?php echo lang('technical_supports_employee_receives') ?> </a>
						</label>
						<?php echo form_dropdown('id_employee', $employees, $support->id_employee_receive ? $support->id_employee_receive : $id_employee_login, 'id="id_employee" class="bs-select form-control "'); ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="required control-label">
							<a class="help_config_required tooltips" data-placement="left"
							   title="<?php echo lang("common_type_team_help") ?>"><?php echo lang('common_type_team_help') ?> </a>
						</label>
                                            <div class="col-sm-12">
						<?php //echo form_dropdown('team_type', $teams, $support->type_team, 'id="team_type" class="bs-select form-control "'); ?>
                                                <select class="bs-select form-control" style="width: 80%;float: left;" data-show-subtext="true" id="team_type" name="team_type" required>
                                                    <option value="">Seleccione </option>
                                                    <?php  
                                                    foreach($tservice->result() as $tservice) {
                                                        ?><option value="<?php echo $tservice->tservicios; ?>"><?php echo $tservice->tservicios; ?> </option><?php
                                                    }                                                    
                                                    ?>
                                                </select>
                                                <a href="javascript:void(0);" title="Pulse para agregar nuevos registro" onclick="controler('<?php echo site_url() ?>/config/tservicios/','hever','ventanaVer','');">
                                                    <span class="btn btn-primary" style="margin-left: 5px;float: left;"><span class="icon"><i class="fa fa-save"></i></span></span>
                                                </a>
                                            </div> 
					</div>
				</div>
                                </div>
                                <div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="required control-label">
							<a class="help_config_required tooltips" data-placement="left"
							   title="<?php echo lang("common_type_falla_help") ?>"><?php echo lang('technical_supports_falla_tecnica') ?> </a>
						</label>
                                            <div class="col-sm-12">
						<?php //echo form_dropdown('damage_failure', $fallas_comunes, $support->damage_failure, ' id="damage_failure" class="bs-select form-control select_custom_subcategory"'); ?>
                                                <select data-placeholder="Choose a Country..."  style="width: 80%;float: left;" class="bs-select form-control chosen-select" id="damage_failure" name="damage_failure"  tabindex="2" required>
                                                    <option value="">Seleccione </option>
                                                    <?php  
                                                    foreach($tfallas->result() as $tfallas) {
                                                        ?><option value="<?php echo $tfallas->tfallas; ?>"><?php echo $tfallas->tfallas; ?> </option><?php
                                                    }                                                    
                                                    ?>
                                                </select>
                                                <a href="javascript:void(0);" title="Pulse para agregar nuevas fallas" onclick="controler('<?php echo site_url() ?>/config/tfallas/','hever','ventanaVer','');"> 
                                                    <span class="btn btn-primary" style="margin-left: 5px;float: left;"><span class="icon"><i class="fa fa-save"></i></span></span>
                                                </a>
                                            </div> 
					</div>
				</div>
                                <div class="col-sm-6" style="margin-top: -14px">
					<div class="form-group">
                                            <label class="control-label">
                                                <label class="control-label"><?php echo lang("technical_supports_ubi_equipos") ?></label>
                                            </label>
                                            <div class="col-sm-12">
						<?php //echo form_dropdown('team_type', $teams, $support->type_team, 'id="team_type" class="bs-select form-control "'); ?>
                                                <select class="bs-select form-control" style="width: 80%;float: left;" data-show-subtext="true" id="ubi_equipo" name="ubi_equipo">
                                                    <option value="">No Aplica </option>
                                                    <?php  
                                                    foreach($ubiequipos->result() as $ubiequipos) {
                                                        ?><option value="<?php echo $ubiequipos->ubicacion; ?>"><?php echo $ubiequipos->ubicacion; ?> </option><?php
                                                    }                                                    
                                                    ?>
                                                </select>
                                                <a href="javascript:void(0);" title="Pulse para agregar nuevos registro" onclick="controler('<?php echo site_url() ?>/config/ubica_equipo/','equipo','ventanaVer','');">
                                                    <span class="btn btn-primary" style="margin-left: 5px;float: left;"><span class="icon"><i class="fa fa-save"></i></span></span>
                                                </a>
                                            </div> 
					</div>
				</div> 
                                </div>
                                <div class="col-sm-12">
                                <div class="col-sm-6">
					<div class="form-group">
						<label
							class="control-label"><?php echo lang('technical_supports_employee_asignado') . ':' ?> </label>
						<?php echo form_dropdown('id_technical', $employees, $support->id_employee_register, 'id="id_technical" class="bs-select form-control "'); ?>
					</div>
				</div>
				<?php /*<div class="col-sm-12">
					<div class="form-group " id="div_falla" style="display:none">
						<?php echo form_label(lang('technical_supports_falla_tecnica_peronalizada') . ':', 'damage_failure_label', array('class' => 'control-label ')); ?>

						<?php
						$data_test = array(
							'name' => 'damage_failure_peronalizada',
							'id' => 'damage_failure_peronalizada',
							'value' => $support->damage_failure_peronalizada,
							'rows' => '2',
							'cols' => '10',
							'style' => 'width:100%',
							'class' => 'form-control'
						);
						echo form_textarea($data_test);
						?>

					</div>
				</div> */ ?>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label"><?php echo lang('technical_supports_stat') ?></label>
						<?php echo form_dropdown('state', $states, $support->state, ' id="damage_failure" class="bs-select form-control select_custom_subcategory"'); ?>
					</div>
				</div>
                                </div>
                                <div class="col-sm-12">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">
						<a class="help_config_required tooltips" data-placement="left"
							   title=""><?php echo lang('technical_supports_marca') ?> </a>
						
						</label>
						<input type="text" class="form-control" 
							   placeholder="<?php echo lang('technical_supports_marca') ?>" name="marca" id="marca"
							   value="<?php echo $support->marca ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="required control-label">
							<a class="help_config_required tooltips" data-placement="left"
							   title="<?php echo lang("common_model_help") ?>"><?php echo lang('technical_supports_model') ?> </a>
						</label>
						<input type="text" class="form-control" placeholder="<?php echo lang("common_model_help") ?>"
							   name="model" id="model" value="<?php echo $support->model ?>">
					</div>
				</div>
                                </div>
                                <div class="col-sm-12">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label"><?php echo lang('technical_supports_color') ?></label>
						<input type="text" class="form-control" placeholder="<?php echo lang('technical_supports_color') ?>"
							   name="color" id="color" value="<?php echo $support->color ?>">
					</div>
				</div>
                                
				<div class="col-md-6">
					<div class="form-group">
						<label
							class="control-label"><?php echo ($this->config->item("custom1_support_name") ? $this->config->item("custom1_support_name") : "Personalizado 1") . ':' ?> </label>
						<input id="custom1_support_name" name="custom1_support_name" type="text" class="form-control"
							   value="<?php echo $support->custom1_support_name ?>">
					</div>
				</div>
                                </div>
                                <div class="col-sm-12">
				<div class="col-md-6">
					<div class="form-group">
						<label
							class="control-label"><?php echo ($this->config->item("custom2_support_name") ? $this->config->item("custom2_support_name") : "Personalizado 2") . ':' ?> </label>
						<input id="custom2_support_name" name="custom2_support_name" type="text" class="form-control"
							   value="<?php echo $support->custom2_support_name ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label"><?php echo lang("technical_supports_abono") ?></label>
						<input id="payments" name="payments" type="number" class="form-control"
							   value="<?php echo $payment ?>">
					</div>
				</div>
                                </div>
                                <div class="col-sm-12">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label"><?php echo lang("technical_supports_repair_cost") ?></label>
						<input id="repair_cost" name="repair_cost" type="number" class="form-control"
							   value="<?php echo $support->repair_cost != "" ? $support->repair_cost : 0 ?>">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label"><?php echo lang('technical_supports_accessory') ?></label>

						<div>
							<?php
							echo '<div class="text-right md-radio-inline col-sm-6">';
							echo '<div class="md-radio">';
							echo form_radio(array(
									'name' => 'do_have_accessory',
									'id' => 'do_have_accessory_yes',
									'value' => '1',
									'class' => 'md-check',
									'checked' => $support->do_have_accessory != 1 ? 1 : 1)
							);
							echo '<label id="show_comment_invoice" for="do_have_accessory_yes">';
							echo '<span></span>';
							echo '<span class="check"></span>';
							echo '<span class="box"></span>';
							echo lang('technical_supports_yes');
							echo '</label>';
							echo '</div>';
							echo '</div>';
							?>
							<?php
							echo '<div class="md-radio-inline col-sm-6">';
							echo '<div class="md-radio">';
							echo form_radio(array(
									'name' => 'do_have_accessory',
									'id' => 'do_have_accessory_no',
									'value' => '0',
									'class' => 'md-check',
									'checked' => $support->do_have_accessory != 0 ? 0 : 1)
							);
							echo '<label  for="do_have_accessory_no">';
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
				<div class="col-sm-12">
					<div class="form-group" id="accessory" style="display:none">
						<label class="control-label">
							<a class="help_config_required tooltips" data-placement="left"
							   title="<?php echo lang("common_accessory_help") ?>"><?php echo lang('common_accessory') ?></a>
						</label>
						<textarea name="accessory" id="accessory"
								  class="form-control"><?php echo $support->accessory ?></textarea>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-md-offset-3 col-md-8" id="errores"></div>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn green" name="submitf" id="submitf">
							<i class="fa fa-check"></i> <?php echo lang('common_submit') ?>
						</button>
						<button type="button" class="btn grey-salsa btn-outline" id="cancel"
								name="cancel"><?php echo lang('common_cancel') ?></button>
					</div>
				</div>
			</div>
			</form>
			<!-- END FORM-->
		</div>
	</div>
</div>



<script type='text/javascript'>
    
	function es_valido_select() {
		var error = true;

		if ($("#team_type").val() == "" || $("#team_type").val() == null) {
			$("#errores").html("<p style='color:red'>*Debe seleccionar el tipo de equipo</p> ");
			error = false;
		}
		else if ($("#damage_failure").val() == "" || $("#team_type").val() == null) {
			$("#errores").html("<p style='color:red'>*Debe seleccionar Falla o daño </p> ");
			error = false;
		}
		return error;
	}


	$('#support_form').validate({
		submitHandler: function (form) {
			$("#errores").html("");

			if (es_valido_select()) {
				$("#form").plainOverlay('show');
				form.submit();

			}

		},
		errorClass: "text-danger",
		errorElement: "span",
		highlight: function (element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
		},
		rules:
			{
				id_employee: {
					required: true,
				},
				account_number: {
					required: true,
				},
				team_type: {
					required: true,
				},
				model: {
					required: true,
				},
				accessory: {
					required: true,
				},
				first_name: {
					required: true,
				},
				last_name: {
					required: true,
				},
				marca:{
					required: true,
				}
			},
		messages:
			{

				id_employee: {
					required: "Este dato es requerido ",
				},
				account_number: {
					required: "Este dato es requerido ",
				},
				team_type: {
					required: "Este dato es requerido ",
				},
				model: {
					required: "Este dato es requerido ",
				},
				accessory: {
					required: "Este dato es requerido ",
				},
				first_name: {
					required: "Este dato es requerido ",
				},
				last_name: {
					required: "Este dato es requerido ",
				},
				marca:{
					required: "Este dato es requerido ",
				}

			}
	});

	function get_info_customer(id_customer) {
		$.post('<?php echo site_url("technical_supports/get_info_customer");?>', {customer: id_customer}, function (data) {
				data = JSON.parse(data);
				var url_base = ' <?php echo site_url("customers/view");?>';
				$("#account_number").val(data["account_number"]);
				$("#first_name").val(data["first_name"]);
				$("#last_name").val(data["last_name"]);
				$("#email").val(data["email"]);
				$("#phone_number").val(data["phone_number"]);
				$("#new-edit").attr("href", url_base + "/" + id_customer + "/3")
				$("#new-edit").removeAttr('disabled');
				// $("#last_name").val(data["last_name"]);


			}
		);
	}

	$(document).ready(function () {

		<?php if ($customer->person_id != "") {
		echo '$("#new-edit").removeAttr("disabled");';
	}?>
		$("#account_number").blur(function () {
			if ($("#account_number").val() != "") {

				$.post('<?php echo site_url("technical_supports/get_info_customer_by_account_number");?>', {account_number: $("#account_number").val()}, function (data) {
					data = JSON.parse(data);
					var url_base = ' <?php echo site_url("customers/view");?>';
					if (data["account_number"] != "") {
						$("#first_name").val(data["first_name"]);
						$("#last_name").val(data["last_name"]);
						$("#email").val(data["email"]);
						$("#phone_number").val(data["phone_number"]);
						$("#new-edit").attr("href", url_base + "/" + data["id_customer"] + "/3")
						$("#new-edit").removeAttr('disabled');

					} else {
						$("#new-edit").attr('disabled', 'disabled');
					}
				});
			}
		});
		$("#cancel").click(function (event) {
			window.location = "<?php echo site_url() . "/technical_supports"?>";
		});


		hide_show(<?php echo $support->do_have_accessory != "" ? $support->do_have_accessory : 0?>, "accessory");
		hide_show(<?php echo $support->damage_failure == "Otro" ? 1 : 0 ?>, "div_falla");
		$('#do_have_accessory_yes').change(function () {
			hide_show(1, "accessory");
		});
		$('#do_have_accessory_no').change(function () {
			hide_show(0, "accessory");
		});
		$('#damage_failure').change(function () {
			if ($("#damage_failure").val() == "Otro")
				hide_show(1, "div_falla");
			else
				hide_show(0, "div_falla");
		});
	});

	function hide_show(opcion, id_div) {
		if (opcion == 1) {
			$('#' + id_div).show(500);
			$('#' + id_div).show("slow");
		} else {
			$('#' + id_div).hide(500);
			$('#' + id_div).hide("fast");
		}
	}


</script>
<script src="js/publics.js"></script>
<script src="js/confirm/jquery-confirm.js"></script>