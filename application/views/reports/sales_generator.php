<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-bar-chart-o"></i>
				<?php echo lang('reports_reports'); ?> - <?php echo $title ?>
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

	<div class="row" id="form">
		<div class="col-md-12">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-align-justify"></i>
						<span class="caption-subject bold">
							<?php echo lang('reports_date_range'); ?>
						</span>
					</div>					
				</div>
				<div class="portlet-body form">
					<?php echo form_open('reports/sales_generator',['name'=>'saleReportGenerator', 'method'=>'GET', 'class'=>'form-horizontal']);?> 
						<div class="form-body">
							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("reports_fixed_range_help").'">'.lang('reports_date_range').'</a>'.":", 'report_date_range_label', array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 align-vertical">
									<div class="md-radio">
										<input type="radio" id="simple_radio" name="report_type" value="simple" class="md-radiobtn" <?php if ($report_type != 'complex') { echo " checked='checked'"; }?> />
										<label for="simple_radio">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
										</label>
									</div>
									<br/>				
									<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, $sreport_date_range_simple, 'id="report_date_range_simple" class="form-control"'); ?>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips" data-placement="left" title="'.lang("reports_custom_range_help").'">'.lang('reports_custom_range').'</a>'.':', 'range',array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9 align-vertical">
									<div class="md-radio">
										<input type="radio" id="complex_radio" name="report_type" class="md-radiobtn" value='complex' <?php if ($report_type == 'complex') { echo " checked='checked'"; }?> />
										<label for="complex_radio">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>										
										</label>
									</div>
									<br/>
									<div class="row">
										<div class="col-md-6 margin-bottom-05">
											<div class="input-group input-daterange" id="reportrange">
			                                    <span class="input-group-addon">
						                        	Desde					                       	
					                           	</span>
			                                    <input type="text" class="form-control start_date" name="start_date" id="start_date" placeholder="Selecciona una fecha">
			                                </div>
										</div>
										<div class="col-md-6">
											<div class="input-group input-daterange" id="reportrange1">
		                                    	<span class="input-group-addon">
			                                   		Hasta
			                                    </span>
		                                    	<input type="text" class="form-control end_date" name="end_date" id="end_date" placeholder="Selecciona una fecha">
		                                	</div>	
										</div>
									</div>									
								</div>
							</div>

							<hr/>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_required  tooltips" data-placement="left" title="'.lang("reports_sales_generator_matchType_help").'">'.lang('reports_sales_generator_matchType').'</a>'.':', 'matchType', array('class'=>'col-md-3 control-label requireds')); ?>
								<div class="col-md-9">
									<select name="matchType" id="matchType" class="form-control">
										<option value="matchType_All"<?php if ($matchType != 'matchType_All') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_All')?></option>
										<option value="matchType_Or"<?php if ($matchType == 'matchType_Or') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_Or')?></option>
									</select>
									<span class="help-block text-justify">
										<?php echo lang('reports_sales_generator_matchType_Help')?>
									</span>
								</div>
							</div>

							<hr/>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_sales_generator_show_only_matched_items_help").'">'.lang('reports_sales_generator_show_only_matched_items').'</a>', 'matched_items_only', array('class'=>'col-md-3 control-label')); ?>
								<div class="col-md-9">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php
												$matched_items_checkbox =	array(
											    'name'        => 'matched_items_only',
											    'id'          => 'matched_items_only',
											    'value'       => '1',
											    'checked'     => $matched_items_only,
										    	);
												
												if ($matchType == 'matchType_Or')
												{
													$matched_items_checkbox['disabled'] = 'disabled';
												}

												echo form_checkbox($matched_items_checkbox); 
											?>
											<label for="matched_items_only">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>									
									</div>
								</div>
							</div>

							<div class="form-group">
								<?php echo form_label('<a class="help_config_options  tooltips " data-placement="left" title="'.lang("reports_export_to_excel_help").'">'.lang('reports_export_to_excel').'</a>', 'reports_export_to_excel', array('class'=>'col-md-3 control-label')); ?> 
								<div class="col-md-9">
									<div class="md-checkbox-inline">
										<div class="md-checkbox">
											<?php echo form_checkbox(array(
											    'name' => 'export_excel',
											    'id'   => 'export_excel',
											    'value'=> '1'
									    	)); ?>
										<label for="export_excel">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
											</label>
										</div>									
									</div>
								</div>
							</div>

							<hr/>

							<div class="table-responsive">
								<table class="table conditions custom-report">
									<?php
										if (isset($field) and $field[0] > 0) {
											foreach ($field as $k => $v) {
									?>
									<tr class="duplicate">
										<td class="field">
											<select name="field[]" class="selectField ">
												<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
												<option value="1" rel="customers"<?php if($field[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_1") ?></option>
												<option value="2" rel="itemsSN"<?php if($field[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_2") ?></option>
												<option value="3" rel="employees"<?php if($field[$k] == 3) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_3") ?></option>
												<option value="4" rel="itemsCategory"<?php if($field[$k] == 4) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_4") ?></option>
												<option value="5" rel="suppliers"<?php if($field[$k] == 5) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_5") ?></option>
												<option value="6" rel="saleType"<?php if($field[$k] == 6) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_6") ?></option>
												<option value="7" rel="saleAmount"<?php if($field[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_7") ?></option>
												<option value="8" rel="itemsKitName"<?php if($field[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_8") ?></option>
												<option value="9" rel="itemsName"<?php if($field[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_9") ?></option>
												<option value="10" rel="saleID"<?php if($field[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_10") ?></option>
												<option value="11" rel="paymentType"<?php if($field[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_11") ?></option>
												<option value="12" rel="saleItemDescription"<?php if($field[$k] == 12) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_12") ?></option>
												<option value="13" rel="salesPerson"<?php if($field[$k] == 13) echo " selected='selected'";?>><?php echo lang("sales_sales_person") ?></option>
											</select>
										</td>
										<td class="condition">
											<select name="condition[]" class="selectCondition ">
												<option value="1"<?php if($condition[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
												<option value="2"<?php if($condition[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
												<option value="7"<?php if($condition[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
												<option value="8"<?php if($condition[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
												<option value="9"<?php if($condition[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
												<option value="10"<?php if($condition[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
												<option value="11"<?php if($condition[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
											</select>
										</td>
										<td class="value">
											<input type="text" name="value[]" w="" value="<?php echo $value[$k]; ?>"/>
										</td>
										<td class="actions">
											<span class="actionCondition">
											<?php 
												if ($matchType == 'matchType_Or') {
													echo lang("reports_sales_generator_matchType_Or_TEXT");
												} else {
													echo lang("reports_sales_generator_matchType_All_TEXT");					
												}
											?>
											</span>
											<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
											<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
										</td>
									</tr>				
									<?php
											}
										} else {
									?>
									<tr class="duplicate">
										<td class="field">
											<select name="field[]" class="selectField form-control">
												<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
												<option value="1" rel="customers"><?php echo lang("reports_sales_generator_selectField_1") ?></option>
												<option value="2" rel="itemsSN"><?php echo lang("reports_sales_generator_selectField_2") ?></option>
												<option value="3" rel="employees"><?php echo lang("reports_sales_generator_selectField_3") ?></option>
												<option value="4" rel="itemsCategory"><?php echo lang("reports_sales_generator_selectField_4") ?></option>
												<option value="5" rel="suppliers"><?php echo lang("reports_sales_generator_selectField_5") ?></option>
												<option value="6" rel="saleType"><?php echo lang("reports_sales_generator_selectField_6") ?></option>
												<option value="7" rel="saleAmount"><?php echo lang("reports_sales_generator_selectField_7") ?></option>
												<option value="8" rel="itemsKitName"><?php echo lang("reports_sales_generator_selectField_8") ?></option>
												<option value="9" rel="itemsName"><?php echo lang("reports_sales_generator_selectField_9") ?></option>
												<option value="10" rel="saleID"><?php echo lang("reports_sales_generator_selectField_10") ?></option>
												<option value="11" rel="paymentType"><?php echo lang("reports_sales_generator_selectField_11") ?></option>
												<option value="12" rel="saleItemDescription"><?php echo lang("reports_sales_generator_selectField_12") ?></option>
												<option value="13" rel="salesPerson"><?php echo lang("sales_sales_person") ?></option>
											</select>
										</td>
										<td class="condition">
											<select name="condition[]" class="selectCondition form-control">
												<option value="1"><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
												<option value="2"><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
												<option value="7"><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
												<option value="8"><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
												<option value="9"><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
												<option value="10"><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
												<option value="11"><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
											</select>
										</td>
										<td class="value">
											<input type="text" name="value[]" w="" value=""/>
										</td>
										<td class="actions">
											<span class="actionCondition">
											<?php 
												if ($matchType == 'matchType_Or') {
													echo lang("reports_sales_generator_matchType_Or_TEXT");
												} else {
													echo lang("reports_sales_generator_matchType_All_TEXT");					
												}
											?>
											</span>
											<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
											<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
										</td>
									</tr>
								
									<?php
										}
									?>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<div class="pull-right">
							<?php echo form_button(array(
								'name'=>'generate_report',
								'id'=>'generate_report',
								'type'=>'sumbit',
								'value'=>'1',
								'content'=>lang('common_submit'),
								'class'=>'btn btn-success')
							);?>
							</div>
						</div>
					</form>
					
					
				</div>
			</div>

			<?php 
				if (isset($results)) echo $results;
			?>

		</div>
	</div>

	<script type="text/javascript">

		var start_date=$('#start_date').datetimepicker({
			format: 'YYYY-MM-DD',
			locale: "es"
		});

		var end_date=$('#end_date').datetimepicker({
			format: 'YYYY-MM-DD',
			locale: "es"
		});

		(function($) 
		{
		  	$.fn.tokenize = function(options)
			{
				var settings = $.extend({}, {prePopulate: false}, options);
		    	return this.each(function() 
				{
		      		$(this).tokenInput('<?php echo site_url("reports/sales_generator"); ?>?act=autocomplete',
					{
						theme: "facebook",
						queryParam: "term",
						extraParam: "w",
						hintText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_hintText"));?>,
						noResultsText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_noResultsText"));?>,
						searchingText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_searchingText"));?>,
						preventDuplicates: true,
						prePopulate: settings.prePopulate
					});
		    	});
		 	}
		})(jQuery);

		$(document).on('change', "#matchType", function(){
			if ($(this).val() == 'matchType_All')
			{
				$("#matched_items_only").prop('disabled', false);
				$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_All_TEXT"));?>);
			}
			else 
			{
				$("#matched_items_only").prop('checked', false);
				$("#matched_items_only").prop('disabled', true);
				$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_Or_TEXT"));?>);
			}
		});


		$(document).on('click', "a.AddCondition", function(e){
			var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"form-control"});
			$('.conditions tr.duplicate:last').clone().insertAfter($('.conditions tr.duplicate:last'));
			$("input", $('.conditions tr.duplicate:last')).parent().html("").append(sInput).children("input").tokenize();
			$("option", $('.conditions tr.duplicate:last select')).removeAttr("disabled").removeAttr("selected").first().prop("selected", true);
			
			$('.conditions tr.duplicate:last').trigger('change');
			e.preventDefault();
		})

		$(document).on('click', "a.DelCondition", function(e){
			if ($(this).parent().parent().parent().children().length > 1)
				$(this).parent().parent().remove();
			
			e.preventDefault();
		})

		$(document).on('change', ".selectField", function(){
			var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"form-control"});
			var field = $(this);
			// Remove Value Field
			field.parent().parent().children("td.value").html("");
			if ($(this).val() == 0) 
			{
				field.parent().parent().children("td.condition").children(".selectCondition").prop("disabled", true);	
				field.parent().parent().children("td.value").append(sInput.prop("disabled", true));		
			} 
			else 
			{
				field.parent().parent().children("td.condition").children(".selectCondition").removeAttr("disabled");	
				if ($(this).val() == 2 || $(this).val() == 7 || $(this).val() == 10 || $(this).val() == 12) 
				{
					field.parent().parent().children("td.value").append(sInput);		
				} 
				else 
				{
					if ($(this).val() == 6) 
					{
						field.parent().parent().children("td.value").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":"", "class":"form-control"}));		
					} 
					else 
					{
						field.parent().parent().children("td.value").append(sInput.attr("w", $("option:selected", field).attr('rel'))).children("input").tokenize();		
					}
				}
				disableConditions(field, true);
			}
		});

		$(function() {
			<?php
				if (isset($prepopulate) and count($prepopulate) > 0) {
					echo "var prepopulate = ".json_encode($prepopulate).";";
				}
			?>
			var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"form-control"});
			$(".selectField").each(function(i) {
				if ($(this).val() == 0) {
					$(this).parent().parent().children("td.condition").children(".selectCondition").prop("disabled", true);
					$(this).parent().parent().children("td.value").html("").append(sInput.prop("disabled", true));	
				} else {
					if ($(this).val() != 2 && $(this).val() != 6 && $(this).val() != 7 && $(this).val() != 10 && $(this).val() != 12) {
						$(this).parent().parent().children("td.value").children("input").attr("w", $("option:selected", $(this)).attr('rel')).tokenize({prePopulate: prepopulate.field[i][$(this).val()] });	
					}
					if ($(this).val() == 6) {
						$(this).parent().parent().children("td.value").html("").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":"", "class":"form-control"}));	
					}
					disableConditions($(this), false);
				}
			});
			
			$("#start_date").click(function()
			{
				$("#complex_radio").prop('checked', true);
			});

			$("#end_date").click(function(){
				$("#complex_radio").prop('checked', true);
			}); 

			$("#report_date_range_simple").change(function()
			{
				$("#simple_radio").prop('checked', true);
			});
		});

		function disableConditions(elm, q) {
			var allowed1 = ['1', '2'];
			var allowed2 = ['7', '8', '9'];
			var allowed3 = ['10', '11'];
			var allowed4 = ['1', '2', '7', '8', '9'];
			var allowed5 = ['1'];
			var disabled = elm.parent().parent().children("td.condition").children(".selectCondition");
			
			if (q == true)
				$("option", disabled).removeAttr("selected");
			
			$("option", disabled).prop("disabled", true);
			$("option", disabled).each(function() {
				if (elm.val() == 11 && $.inArray($(this).attr("value"), allowed5) != -1) {
					$(this).removeAttr("disabled");
				}else if (elm.val() == 10 && $.inArray($(this).attr("value"), allowed4) != -1) {
					$(this).removeAttr("disabled");
				} else if (elm.val() == 6 && $.inArray($(this).attr("value"), allowed3) != -1) {
					$(this).removeAttr("disabled");
				} else if (elm.val() == 7 && $.inArray($(this).attr("value"), allowed2) != -1) {
					$(this).removeAttr("disabled");
				} else if (elm.val() != 6 && elm.val() != 7 && elm.val() != 10 && elm.val() != 11 && $.inArray($(this).attr("value"), allowed1) != -1) {
					$(this).removeAttr("disabled");
				} 
			});
			
			if (q == true)
				$("option:not(:disabled)", disabled).first().prop("selected", true);
		}

	</script>


<?php $this->load->view("partial/footer"); ?>