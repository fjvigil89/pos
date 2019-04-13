<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-pencil'></i>
				<?php  if(!$person_info->person_id) { echo lang('employees_new'); } else { echo lang('employees_update'); }?>
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

	<div class="clear"></div>
	
	<div class="" id="form">
		<?php 	$current_employee_editing_self = $this->Employee->get_logged_in_employee_info()->person_id == $person_info->person_id;
			echo form_open('employees/save/'.$person_info->person_id,array('id'=>'employee_form','class'=>'form-horizontal'));
		?>
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open"></i>
						<span class="caption-subject bold">
							<?php echo lang("employees_basic_information"); ?>
						</span>
					</div>		
				</div>
				<div class="portlet-body">
					<div class="tabbable-custom ">
						<ul class="nav nav-tabs ">
							<li class="active">
								<a href="#tab_5_1" data-toggle="tab" aria-expanded="false">
									<?php echo lang("employees_basic_information"); ?>
								</a>
							</li>
    							<li class="">
    								<a href="#tab_5_2" data-toggle="tab" aria-expanded="false">
    									<?php echo lang("employees_login_info"); ?>
    								</a>
    							</li>
							<li class="">
								<a href="#tab_5_3" data-toggle="tab" aria-expanded="true">
									<?php echo lang("employees_permission_info"); ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_5_1">
								<br/>

								<?php $this->load->view("people/form_basic_info"); ?>

								<div class="form-group">	
									<?php echo form_label('<a class="help_config_options  tooltips"  title="'.lang("common_commission_help1").'">'.lang('config_commission_default_rate').' ('.lang('common_commission_help').')</a>'.':', 'commission_default_rate',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<div class="input-group">
											<?php echo form_input(array(
												'name'=>'commission_percent',
												'id'=>'commission_percent',
												'class'=>'fomr-control form-inps',
												'value'=>to_quantity($person_info->commission_percent,FALSE)));
											?>
											<span class="input-group-addon">%</span>
										</div>
									</div>
								</div>
								<?php if( $this->config->item('activar_casa_cambio')==true):?>
									<div class="form-group">	
										<?php echo form_label('<a class="help_config_options  tooltips"  title="Tasa venta/compra">Tasa venta/compra</a>'.':', 'id_rate',array('class'=>'col-md-3 control-label')); ?>
										<div class="col-md-8"> 
											
										<?php echo form_dropdown('id_rate', $rates,
											$person_info->id_rate, 'class="bs-select form-control" id="id_rate"')
										?>
												
										</div>
									</div>
									<div class="form-group">	
										<?php echo form_label('<a class="help_config_options  tooltips"  title="Tipo de empleado">Tipo de empleado</a>'.':', 'type',array('class'=>'col-md-3 control-label')); ?>
										<div class="col-md-8">
											
										<?php echo form_dropdown('type', array("credito"=>"Tasa libre","debito"=>"Tasa fija"),
											$person_info->type, 'class="bs-select form-control" id="type"')
										?>
												
										</div>
									</div>
									<div class="form-group">	
									<?php echo form_label('<a class="help_config_options  tooltips"  title="Saldo de la cuenta">Saldo de la cuenta</a>'.':', 'account_balance',array('class'=>'col-md-3 control-label')); ?>
										<div class="col-md-8">											
												<?php
												$arr= array(
													'name'=>'account_balance',
													'id'=>'account_balance',
													'class'=>'fomr-control form-inps',												
													'value'=>to_currency($person_info->account_balance));
													if($person_info->id!=""){
														$arr["disabled"]=1;	
													}
												echo form_input($arr);
												?>
												
										</div>
									</div>							
								<?php endif ?>
							</div>

							<div class="tab-pane" id="tab_5_2">	
								<br/>						
								<div class="form-group">	
									<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("employees_username_help").'">'.lang('employees_username').'</a>'.':', 'username',array('class'=>'col-md-3 control-label requireds')); ?>
									<div class="col-md-8">
										<?php echo form_input(array(
											'name'=>'username',
											'id'=>'username',
											'class'=>'form-control form-inps',
											'value'=>$person_info->username));
										?>
									</div>
								</div>

								<div class="form-group">	
								<?php echo form_label('<a class="help_config_options  tooltips" title="'.lang("employees_password_help").'">'.lang('employees_password').'</a>'.':', 'password',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<?php echo form_password(array(
											'name'=>'password',
											'id'=>'password',
											'class'=>'form-control form-inps',
											'autocomplete'=>'off',));
										?>
									</div>
								</div>

								<div class="form-group">	
									<?php echo form_label('<a class="help_config_options  tooltips"  title="'.lang("employees_repeat_password_help").'">'.lang('employees_repeat_password').'</a>'.':', 'repeat_password',array('class'=>'col-md-3 control-label')); ?>
									<div class="col-md-8">
										<?php echo form_password(array(
											'name'=>'repeat_password',
											'id'=>'repeat_password',
											'class'=>'form-control form-inps',
											'autocomplete'=>'off',));
										?>
									</div>
								</div>

								<div class="form-group">	
									<?php echo form_label(lang('config_language').':', 'language',array('class'=>'col-md-3 control-label requireds')); ?>
									<div class="col-md-8">
										<select class="bs-select form-control" data-show-subtext="true" name="language" onmouseover="this.disabled=true;" onmouseout="this.disabled=false;">	
										
				                         <!--   <option data-icon="flagstrap-icon flagstrap-co" value="spanish" <?php if($this->Appconfig->get_raw_language_value()=="spanish") echo "selected";?>>Colombia - Español</option>		                            
				                            <option data-icon="flagstrap-icon flagstrap-us" value="english" <?php if($this->Appconfig->get_raw_language_value()=="english") echo "selected";?>>Estados Unidos - Ingles</option>
				                            <option data-icon="flagstrap-icon flagstrap-pe" value="spanish_peru" <?php if($this->Appconfig->get_raw_language_value()=="spanish_peru") echo "selected";?>>Peru - Español</option>
				                            <option data-icon="flagstrap-icon flagstrap-uy" value="spanish_uruguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_uruguay") echo "selected";?>>Uruguay - Español</option>
				                            <option data-icon="flagstrap-icon flagstrap-py" value="spanish_paraguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_paraguay") echo "selected";?>>Paraguay - Español</option>
				                            <option data-icon="flagstrap-icon flagstrap-cl" value="spanish_chile" <?php if($this->Appconfig->get_raw_language_value()=="spanish_chile") echo "selected";?>>Chile - Español</option>
				                            <option data-icon="flagstrap-icon flagstrap-ve" value="spanish_venezuela" <?php if($this->Appconfig->get_raw_language_value()=="spanish_venezuela") echo "selected";?>>Venezuela - Español</option>																		
										-->
											<option data-icon="flagstrap-icon flagstrap-ar" value="spanish_argentina" <?php if($this->Appconfig->get_raw_language_value()=="spanish_argentina") echo "selected"; else echo "disabled";?>>Argentina - Español</option>                                   
											<option data-icon="flagstrap-icon flagstrap-bo" value="spanish_bolivia" <?php if($this->Appconfig->get_raw_language_value()=="spanish_bolivia") echo "selected"; else echo "disabled";?>>Bolivia - Español</option>
											<option data-icon="flagstrap-icon flagstrap-bo" value="spanish_bolivia" <?php if($this->Appconfig->get_raw_language_value()=="spanish_bolivia") echo "selected"; else echo "disabled"; ?>>Bolivia - Español</option>
											<option data-icon="flagstrap-icon flagstrap-br" value="portugues_brasil" <?php if($this->Appconfig->get_raw_language_value()=="portugues_brasil") echo "selected"; else echo "disabled";?>>Brasil - Portugues</option>
											<option data-icon="flagstrap-icon flagstrap-cl" value="spanish_chile" <?php if($this->Appconfig->get_raw_language_value()=="spanish_chile") echo "selected"; else echo "disabled";?>>Chile - Español</option>
											<option data-icon="flagstrap-icon flagstrap-co" value="spanish" <?php if($this->Appconfig->get_raw_language_value()=="spanish") echo "selected"; else echo "disabled";?>>Colombia - Español</option>  
											<option data-icon="flagstrap-icon flagstrap-cr" value="spanish_costarica" <?php if($this->Appconfig->get_raw_language_value()=="spanish_costarica") echo "selected"; else echo "disabled";?>>Costa Rica - Español</option>
											<option data-icon="flagstrap-icon flagstrap-ec" value="spanish_ecuador" <?php if($this->Appconfig->get_raw_language_value()=="spanish_ecuador") echo "selected"; else echo "disabled";?>>Ecuador - Español</option>
											<option data-icon="flagstrap-icon flagstrap-sv" value="spanish_elsalvador" <?php if($this->Appconfig->get_raw_language_value()=="spanish_elsalvador") echo "selected"; else echo "disabled";?>>El Salvador - Español</option>
											<option data-icon="flagstrap-icon flagstrap-es" value="spanish_spain" <?php if($this->Appconfig->get_raw_language_value()=="spanish_spain") echo "selected"; else echo "disabled";?>>España - Español</option>
											<option data-icon="flagstrap-icon flagstrap-us" value="english" <?php if($this->Appconfig->get_raw_language_value()=="english") echo "selected"; else echo "disabled";?>>Estados Unidos - Ingles</option>
											<option data-icon="flagstrap-icon flagstrap-gt" value="spanish_guatemala" <?php if($this->Appconfig->get_raw_language_value()=="spanish_guatemala") echo "selected"; else echo "disabled";?>>Guatemala - Español</option>
											<option data-icon="flagstrap-icon flagstrap-hn" value="spanish_honduras" <?php if($this->Appconfig->get_raw_language_value()=="spanish_honduras") echo "selected"; else echo "disabled";?>>Honduras - Español</option>
											<option data-icon="flagstrap-icon flagstrap-mx" value="spanish_mexico" <?php if($this->Appconfig->get_raw_language_value()=="spanish_mexico") echo "selected"; else echo "disabled";?>>México - Español</option>
											<option data-icon="flagstrap-icon flagstrap-ni" value="spanish_nicaragua" <?php if($this->Appconfig->get_raw_language_value()=="spanish_nicaragua") echo "selected"; else echo "disabled";?>>Nicaragua - Español</option>
											<option data-icon="flagstrap-icon flagstrap-pa" value="spanish_panama" <?php if($this->Appconfig->get_raw_language_value()=="spanish_panama") echo "selected"; else echo "disabled";?>>Panamá - Español</option>
											<option data-icon="flagstrap-icon flagstrap-py" value="spanish_paraguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_paraguay") echo "selected"; else echo "disabled";?>>Paraguay - Español</option>
											<option data-icon="flagstrap-icon flagstrap-pe" value="spanish_peru" <?php if($this->Appconfig->get_raw_language_value()=="spanish_peru") echo "selected"; else echo "disabled";?>>Perú - Español</option>
											<option data-icon="flagstrap-icon flagstrap-pr" value="spanish_puertorico" <?php if($this->Appconfig->get_raw_language_value()=="spanish_puertorico") echo "selected"; else echo "disabled";?>>Puerto Rico - Español</option>
											<option data-icon="flagstrap-icon flagstrap-do" value="spanish_republicadominicana" <?php if($this->Appconfig->get_raw_language_value()=="spanish_republicadominicana") echo "selected"; else echo "disabled";?>>República Dominicana - Español</option>
											<option data-icon="flagstrap-icon flagstrap-uy" value="spanish_uruguay" <?php if($this->Appconfig->get_raw_language_value()=="spanish_uruguay") echo "selected"; else echo "disabled";?>>Uruguay - Español</option>
											
										</select>																			
									</div>
								</div>
							<?php foreach($locations_list as $location){ ?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title font-green-seagreen"><strong><i class="fa fa-unlock-alt"></i> <?php echo lang("employee_access_control").' - '.$location->name; ?> </strong></h3>
									</div>
									<div class="panel-body">
										<div class="row">
										<div class="col-md-12">
										<div class=" table-responsive">
                      						<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th> <?php echo lang("hours"); ?> </th>
														<th> <?php echo lang("day_1"); ?> </th>
														<th> <?php echo lang("day_2"); ?> </th>
														<th> <?php echo lang("day_3"); ?> </th>
														<th> <?php echo lang("day_4"); ?> </th>
														<th> <?php echo lang("day_5"); ?> </th>
														<th> <?php echo lang("day_6"); ?> </th>
														<th> <?php echo lang("day_7"); ?> </th>

													</tr>
												</thead>
												<tbody>
												<?php
												for ($i=0; $i < 24 ; $i++) { 
													$j=0;
													?>
													<tr>
														<td> <?php  echo date(get_time_format(),strtotime($i.':00')); ?> </td>
													<?php
													while($j<7){
													?>
													<td align="center"> 
															<div class="md-checkbox-inline ">
																<div class="md-checkbox " >		
																	<?php echo form_checkbox(array('name'=>$location->location_id.'_day_'.$j.'[]', 'id'=>'checkbox'.$i.$j, 'value'=>$i, 'class'=>'md-check','checked'=> false));?>																		
																	<label for="<?php echo 'checkbox'.$i.$j;?>">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	</label>
																</div>									
															</div>
													    </td>
													<?php
													$j++;
													}
													?>
													</tr>
													<?php
												} ?>
													
												</tbody>
              								</table>
                    					</div>
									</div>
								</div>
							    <?php }?>
							</div>
						</div>

								<?php if (count($locations) == 1) { 						
									echo form_hidden('locations[]', current(array_keys($locations)));
								?>
								<?php } else { ?>
									<div class="form-group">	
										<?php echo form_label(lang('employees_locations').':', 'location',array('class'=>'col-md-3 control-label  requireds')); ?>
										<div class="col-md-8">
											<ul id="locations_list" class="list-inline">
											<?php
												foreach($locations as $location_id => $location) 
												{
													$checkbox_options = array(
													'name' => 'locations[]',
													'id'=>$location['name'],
													'value' => $location_id,
													'checked' => $location['has_access'],
													);
													//var_dump($locations);
													if (!$location['can_assign_access'])
													{
														$checkbox_options['disabled'] = 'disabled';
														
														//Only send permission if checked
														if ($checkbox_options['checked'])
														{
															echo form_hidden('locations[]', $location_id);
														}
													}
																				
													echo '<li><div class="md-checkbox-inline">
															<div class="md-checkbox">'.form_checkbox($checkbox_options).' <label for="'.$location['name'].'"><span></span><span class="check"></span><span class="box"></span>'.$location['name'].'</label></div></div></li>';
												}
											?>
											</ul>
										</div>
									</div>
								<?php } ?>
							</div>

							<div class="tab-pane " id="tab_5_3">
								<div class="alert alert-success alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									<strong><?php echo lang("employees_permission_desc"); ?></strong>
								</div>								

								<div class="portlet solid grey-cararra">
									<?php foreach($all_modules->result() as $module)
									{
										$checkbox_options = array(
											'name' => 'permissions[]',
											'id' => 'checkbox_prueba',
											'value' => $module->module_id,
											'checked' => $this->Employee->has_module_permission($module->module_id,$person_info->person_id),
											'class' => 'module_checkboxes make-switch ',
											'data-size' => 'small',
											'data-on-text' => 'Si',
											'data-off-text' => 'No',
											'data-on-color' => 'success',
											'data-off-color' => 'danger'
	 									);
										
										if ($logged_in_employee_id != 1)
										{
											if(($current_employee_editing_self && $checkbox_options['checked']) || !$this->Employee->has_module_permission($module->module_id,$logged_in_employee_id))
											{
												$checkbox_options['disabled'] = 'disabled';
												
												//Only send permission if checked
												if ($checkbox_options['checked'])
												{
													echo form_hidden('permissions[]', $module->module_id);
												}
											}
										}
										//var_dump($all_modules); exit();
										?>

										<div class="portlet-body">	
											<hr/>
											<div class="row">
												<div class="col-sm-10 col-md-10">															
													<i class="fa fa-<?php echo $module->icon; ?>"></i>		
													<span class="text-permissions">						
														<?php echo $this->lang->line('module_'.$module->module_id);?>:												
														<?php echo $this->lang->line('module_'.$module->module_id.'_desc');?>
													</span>
												</div>
												<div class="col-sm-2 col-md-2">
													<div class="pull-right">
													<?php echo form_checkbox($checkbox_options); ?>	
													</div>
												</div>
												
											</div>
											<br/>
											<div class="form-group">
												<?php foreach($this->Module_action->get_module_actions($module->module_id)->result() as $module_action)
												{
													$checkbox_options = array(
													'name' => 'permissions_actions[]',
													'id' => $module_action->module_id."|".$module_action->action_id,
													'value' => $module_action->module_id."|".$module_action->action_id,
													'checked' => $this->Employee->has_module_action_permission($module->module_id, $module_action->action_id, $person_info->person_id)
													);
							
													if ($logged_in_employee_id != 1)
													{
														if(($current_employee_editing_self && $checkbox_options['checked']) || (!$this->Employee->has_module_action_permission($module->module_id,$module_action->action_id,$logged_in_employee_id)))
														{
															$checkbox_options['disabled'] = 'disabled';
															
															//Only send permission if checked
															if ($checkbox_options['checked'])
															{
																echo form_hidden('permissions_actions[]', $module_action->module_id."|".$module_action->action_id);
															}
														}							
													}
													?>
													<div class="col-md-4">
														<div class="md-checkbox-inline">
															<div class="md-checkbox">
																<?php echo form_checkbox($checkbox_options); ?>															
																<label for="<?php echo $module_action->module_id."|".$module_action->action_id?>">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo $this->lang->line($module_action->action_name_key);?>
																</label>
															</div>									
														</div>
													</div>
													<!-- <?php var_dump($module_action->action_name_key); ?> -->
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								</div>							

								<div class="alert alert-success alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									<strong>Activa las cajas debajo para permitir el acceso a las cajas registradoras</strong>
								</div>	

							<div class="portlet solid grey-cararra">
									<?php foreach($locations_list as $location)
									{
										$checkbox_options = array(
											'name' => 'permissions_locations[]',
											'id' => 'permisos',
											'value' => $location->location_id,
											'checked' =>$this->Employee->tiene_caja_en_tienda($person_info->person_id,$location->location_id),
											'class' => 'module_checkboxes make-switch ',
											'data-size' => 'small',
											'data-on-text' => 'Si',
											'data-off-text' => 'No',
											'data-on-color' => 'success',
											'data-off-color' => 'danger'
	 									);
																				
										?>

										<div class="portlet-body">	
											<hr/>
											<div class="row">
												<div class="col-sm-10 col-md-10">															
													<i class="fa fa-"></i>		
													<span class="text-permissions">						
														<?php echo $location->name?>:												
														
													</span>
												</div>
												<div class="col-sm-2 col-md-2">
													<div class="pull-right">
													<?php echo form_checkbox($checkbox_options); ?>	
													</div>
												</div>
												
											</div>
											<br/>
											<div class="form-group">
												<?php foreach($cajas as $caja)
												{
													if($caja->location_id==$location->location_id){
														$checkbox_options = array(
														'name' => 'permiso_cajas[]',
														'id' => $caja->register_id,
														'value' => $caja->register_id,
														'checked' => $this->Employee->tiene_caja($caja->register_id, $person_info->person_id)
														);
							
													
														?>
														<div class="col-md-4">
															<div class="md-checkbox-inline">
																<div class="md-checkbox">
																	<?php echo form_checkbox($checkbox_options); ?>															
																	<label for="<?php echo $caja->register_id?>">
																		<span></span>
																		<span class="check"></span>
																		<span class="box"></span>
																		<?php echo $caja->name;?>
																	</label>
																</div>									
															</div>
														</div>
													<?php } }?>
												</div>
											</div>
									<?php }?>
								</div>



							</div>
						</div>
					</div>

					<?php echo form_hidden('redirect_code', $redirect_code); ?>
					<div class="row">	
						<div class="col-md-12">
							<div class="pull-right">
								<?php echo form_button(array(
									'type'=>'submit',
									'name'=>'submitf',
									'id'=>'submitf',
									'content'=>lang('common_submit'),
									'class'=>'btn btn-primary')
								);?>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		<?php echo form_close();?>
	</div>

	<script type='text/javascript'>
		
		var imagen = "<?php echo site_url('app_files/view/'.$person_info->image_id); ?>"
		console.log(imagen);
		$("#image_id").fileinput({
		    initialPreview: [
		        "<img src='<?php echo $person_info->image_id ? site_url('app_files/view/'.$person_info->image_id) : base_url().'img/avatar.png'; ?>' class='file-preview-image' alt='Avatar' title='Avatar'>"		        
		    ],
		    overwriteInitial: true,
		    initialCaption: "Avatar"
		});

		//validation and submit handling
		$(document).ready(function()
		{
		    setTimeout(function(){$(":input:visible:first","#employee_form").focus();},100);
			
			$('.module_checkboxes').on('switchChange.bootstrapSwitch', function(event, state) 
			{
			  	if (state==true)
				{
					$(this).parent().parent().parent().parent().parent().parent().find('input[type=checkbox]').not(':disabled').prop('checked', true);
				}
				else
				{
					$(this).parent().parent().parent().parent().parent().parent().find('input[type=checkbox]').not(':disabled').prop('checked', false);			
				}
			  	console.log(state); // true | false
			});
			/*
			$(".module_checkboxes").change(function()
			{
				if ($(this).prop('checked'))
				{
					$(this).parent().find('input[type=checkbox]').not(':disabled').prop('checked', true);
				}
				else
				{
					$(this).parent().find('input[type=checkbox]').not(':disabled').prop('checked', false);			
				}
			});*/

			$('#employee_form').validate({
				submitHandler:function(form)
				{
					$.post('<?php echo site_url("employees/check_duplicate");?>', {term: $('#first_name').val()+' '+$('#last_name').val(), email: $("#email").val(), person_id : "<?=$person_info->person_id;?>"},function(data) {
					
					if (data.duplicate_email) {

						alert("¡El email introducido ya existe!");
						return false;
					}	

					<?php if(!$person_info->person_id) { ?>

						if(data.duplicate)
						{
							if(!confirm(<?php echo json_encode(lang('employees_duplicate_exists'));?>))
							{
								alert("cancelar");
								return false;
							}
						}

					<?php } ?>

					doEmployeeSubmit(form);
					} , "json")
						.error(function() { 
						});
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
					first_name: "required",
					
					username:
					{
						<?php if(!$person_info->person_id) { ?>
						remote: 
					    { 
							url: "<?php echo site_url('employees/exmployee_exists');?>", 
							type: "post"
					    }, 
						<?php } ?>
						required:true,
						minlength: 5
					},

					password:
					{
						<?php
						if($person_info->person_id == "")
						{
						?>
						required:true,
						<?php
						}
						?>
						minlength: 8
					},	
					repeat_password:
					{
		 				equalTo: "#password"
					},
		    		email: {
						"required": true
					},
					"locations[]": "required"
		   		},
				messages: 
				{
		     		first_name: <?php echo json_encode(lang('common_first_name_required')); ?>,
		     		last_name: <?php echo json_encode(lang('common_last_name_required')); ?>,
		     		username:
		     		{
						<?php if(!$person_info->person_id) { ?>
			     			remote: <?php echo json_encode(lang('employees_username_exists')); ?>,
						<?php } ?>
		     			required: <?php echo json_encode(lang('employees_username_required')); ?>,
		     			minlength: <?php echo json_encode(lang('employees_username_minlength')); ?>
		     		},
					password:
					{
						<?php
						if($person_info->person_id == "")
						{
						?>
						required:<?php echo json_encode(lang('employees_password_required')); ?>,
						<?php
						}
						?>
						minlength: <?php echo json_encode(lang('employees_password_minlength')); ?>
					},
					repeat_password:
					{
						equalTo: <?php echo json_encode(lang('employees_password_must_match')); ?>
		     		},
		     		email: <?php echo json_encode(lang('common_email_invalid_format')); ?>,
					"locations[]": <?php echo json_encode(lang('employees_one_location_required')); ?>
				}
			});

			check_access();
		});

		var submitting = false;

		function doEmployeeSubmit(form)
		{
			$("#form").plainOverlay('show');
			if (submitting) return;
			submitting = true;

			$(form).ajaxSubmit({
			success:function(response)
				{
					$("#form").plainOverlay('hide');
					submitting = false;
					if(response.redirect_code==1 && response.success)
					{
						if(response.success)
						{
							toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?> +' #' + response.person_id);											
						}
						else
						{
							toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
						}					
					}
					else if(response.redirect_code==2 && response.success)
					{
						window.location.href = '<?php echo site_url('employees'); ?>'
					}
					else if(response.success)
					{
						toastr.success(response.message, <?php echo json_encode(lang('common_success')); ?>);						
					}
					else
					{
						toastr.error(response.message, <?php echo json_encode(lang('common_error')); ?>);
					}
				},
			<?php if(!$person_info->person_id) { ?>
			resetForm: true,
			<?php } ?>
			dataType:'json'
			});
		}


		function check_access(){
			<?php
				foreach ($hour_access_employee as  $value) {
					$array=$value['id_day_access'];
				
			?>
				$("#checkbox<?php echo $value['id_hour_access'].$value['id_day_access'] ?>").attr('checked', true);
				
				
			<?php 
				if($person_info->person_id==1){
				?>
				document.getElementById("checkbox<?php echo $value['id_hour_access'].$value['id_day_access'] ?>").disabled = true;
			<?php 
				}
			}
			?>
		}
	</script>

<?php $this->load->view("partial/footer"); ?>