<div >
        <div class="portlet light  no-padding">
				<div class="portlet-title padding">
					<div class="caption">
						<i class="icon-basket-loaded"></i>
						<span class="caption-subject bold">
							<?php echo lang('sales_summary'); ?>
						</span>
					</div>
					<div class="options">
					<?php if(count($cart)>0):?>
						<a href="<?php echo site_url('sales/modal_cart') ; ?>" data-toggle="modal" data-target="#myModal" class="pull-right tooltips" id="opener" data-original-title="Producdos agregados">Editar <i class="icon-basket-loaded" style="font-size: 20px"></i></a>
					<?php endif;?>
					</div>
				</div>

					<ul class="list-group">
						<li class="list-group-item no-border-top tier-style">
							<div class="row">
								<div class="col-md-6">
									<span class="name-addpay">
										<a class="sales_help tooltips" data-placement="left" title=""><?php echo "Tasa de transacción" ?> </a>:
									</span>
								</div>
									<div class="col-md-6">
										<span class="">
										<?php  if($this->Employee->has_module_action_permission('changes_house', 'edit_rate_transition', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>

												<?php echo form_input(array(
														'class' => 'form-control form-inps',
														'name' => 'tasa_edit',
														'id' => 'tasa_edit',                                             
														"type"=>"number",
														"title"=>"Si edita la tasa, esta se aplicará a todas las transacciones que están en el carrito",
														"min"=>0.0000000001,
														'placeholder' => "Ingrese el % de la tasa",
														'value' => (double)$tasa)); 
													?>
											<?php }
												else{
													echo "<strong>".(double)$tasa."</strong>";
												}?>
										</span>
									</div>
								</div>
									
						</li>
					</ul>
				<?php if($this->Employee->has_module_action_permission('changes_house', 'edit_rate_transition', $this->Employee->get_logged_in_employee_info()->person_id)): ?>
					<ul class="list-group">	
					
						<div class="item-subtotal-block">
							<div class="num_items amount">
								<span class="name-item-summary">
									Tasa de costo:
								</span>
								<span class="pull-right">
									<strong>
										<?php echo (double) $rate_price; ?>
									</strong>
								</span>
							</div>
							
							<div class="subtotal">
								<span class="name-item-summary">
									Utilidad: 
								</span>
								<span class="pull-right ">
									<strong><?php echo $this->config->item('round_value')==1 ?
									to_currency(round($utilidad)) :to_currency($utilidad) ; ?></strong>
								</span>
							</div>
							
					</div>
				<?php endif;?>
				<ul class="list-group">	
					
					<div class="item-subtotal-block">
						<div class="num_items amount">
							<span class="name-item-summary">
								<?php echo lang('sales_items_in_cart'); ?>:
							</span>
							<span class="pull-right badge badge-cart badge-<?php echo count($cart)==0 ? 'warning' : 'success'?>">
								<?php echo count($cart); ?>
							</span>
						</div>
						
						<div class="subtotal">
							<span class="name-item-summary">
								<?php echo lang('sales_sub_total'); ?>: 
							</span>
							<span class="pull-right name-item-summary">
								<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($subtotal)) :to_currency($subtotal) ; ?></strong>
							</span>
						</div>
						
					</div>
					

					<?php foreach($payments as $payment) {?>
						<?php if (strpos($payment['payment_type'], lang('sales_giftcard'))!== FALSE) {?>
							<li class="list-group-item">
								<span class="name-item-summary">
									<?php echo $payment['payment_type']. ' '.lang('sales_balance') ?>:
								</span>
								<?php $giftcard_payment_row = explode(':', $payment['payment_type']); ?>
								<span class="name-item-summary pull-right">
									<?php echo to_currency($this->Giftcard->get_giftcard_value(end($giftcard_payment_row)) - $payment['payment_amount']);?>
								</span>
							</li>
						<?php }?>
					<?php }?>

					<?php foreach(array() as $name=>$value) {
						if(!($value=='0.00')) { ?>
							<li class="list-group-item">
								<span class="name-item-summary">
									<?php if (!$is_tax_inclusive && $this->Employee->has_module_action_permission('sales', 'delete_taxes', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
										<?php echo anchor("sales/delete_tax/".rawurlencode($name),'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_tax'));?>
									<?php } ?>
									<?php echo $name; ?>:
								</span>
								<span class="name-item-summary pull-right">
									<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($value)) :to_currency($value) ; ?></strong>
								</span>
							</li>
						<?php }
					}; ?>
				</ul>

				<div class="amount-block">
					<div class="total amount">
						<div class="side-heading">
							<?php echo lang('sales_total'); ?>:
						</div>
						<div id="total-amount" class="amount animation-count font-green-jungle" data-speed="1000" data-decimals="2">
							<?php echo $this->config->item('round_value')==1 ? to_currency(round($total)) :to_currency($total) ; ?>
							<br>
						</div>
						
						
					</div>
					<div class="total">
						<div class="side-heading">
							<?php echo lang('sales_amount_due'); ?>:
						</div>
						<div id="amount-due" class="amount animation-count <?php if($payments_cover_total) { echo 'font-green-jungle'; } else { echo 'text-danger'; }?>"  data-speed="1000" data-decimals="2">
							<?php echo $this->config->item('round_value')==1 ? to_currency(round($amount_due)) : to_currency($amount_due)?>
						</div>
					</div>
				</div>
			
				<div class="amount-block">
					<div class="total amount">
						<div class="side-heading">
							<?php echo lang('sales_total')." ".lang('sales_'.$divisa); ?>:
						</div>
						<div  class="amount  font-green-jungle" data-decimals="2">
							<?= to_currency($total_divisa,3,lang('sales_'.$divisa)." ")													
							// $total_divisa
							?>
							
						</div>				
					</div>	
					<?php if($this->Employee->has_module_action_permission('changes_house', 'edit_rate_transition', $this->Employee->get_logged_in_employee_info()->person_id)): ?>
						<div class="total amount">
							<div class="side-heading">
								Costo de transacción
							</div>
							<div  class="amount  font-green-jungle" data-decimals="2">
								<?php
									echo $this->config->item('round_value')==1 ? to_currency(round($total_transaccion)):to_currency($total_transaccion,3);				
								?>
								
							</div>				
						</div>	
				<?php endif;?>				
				</div>
				
				<?php if(count($cart) > 0) {		// Only show this part if there are Items already in the sale.?>
					<?php if(count($payments) > 0) {	// Only show this part if there is at least one payment entered. ?>
						<div class="payments">
							<ul class="list-group">
								<?php foreach($payments as $payment_id=>$payment) { ?>
									<li class="list-group-item no-border-top">
										<span class="name-item-summary">
											<?php echo anchor("sales/delete_payment/$payment_id",'<i class="fa fa-times-circle fa-lg font-red"></i>', array('class' => 'delete_payment'));?>
											<?php echo $payment['payment_type']; ?>
										</span>
										<span class="name-item-summary pull-right">
											<strong><?php echo $this->config->item('round_value')==1 ? to_currency(round($payment['payment_amount'])):to_currency($payment['payment_amount']);?></strong>
										</span>
									</li>
								<?php } ?>
							<ul>
						</div>
					<?php } ?>

					<?php if ($customer_required_check) { ?>
						<!-- BEGIN ADD PAYMENT -->
						<div class="add-payment">

							<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form', 'autocomplete'=> 'off')); ?>

								<ul class="list-group">
									<li class="list-group-item no-border-top tier-style">
										<div class="row">
											<div class="col-md-6">
												<span class="name-addpay">
													<a class="sales_help tooltips" data-placement="left" title="<?php echo lang('csales_add_payment_help'); ?>"><?php echo lang('sales_add_payment'); ?> </a>:
												</span>
											</div>
											<div class="col-md-6">
												<span class="">
													<?php echo form_dropdown('payment_type',$payment_options,$this->config->item('default_payment_type'), 'id="payment_types" class="bs-select form-control"');?>
												</span>
											</div>
										</div>
										<div class="row margin-top-10" id="panel1" style="display: <?php echo $pagar_otra_moneda? "none":"block" ?>;">
											<div class="col-md-12">
												<div class="input-group">
													<?php $value=$this->config->item('round_value')==1 ? round($amount_due): to_currency_no_money($amount_due); ?>
													<?php echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>$value,'class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
													<span class="input-group-btn">
													<input class="btn btn-success" type="button" id="add_payment_button" value="<?php echo lang('sales_add_payment'); ?>" />
													</span>
												</div>
											</div>
										</div>
										<div class="row margin-top-10" id="panel2" style="display: <?php echo $pagar_otra_moneda?"block":"none" ?>;">
											<div class="col-md-12">
												<div class="input-group">												
													<div class="input-group">
														<span class="input-group-addon" id="abreviatura"><?php echo $currency?></span>
														<?php echo form_input(array('name'=>'amount_tendered2','id'=>'amount_tendered2','class'=>'form-control form-inps-sale', 'accesskey' => 'p'));?>
														<span class="input-group-btn">
															<input class="btn btn-success" type="button" id="add_payment_button2" value="<?php echo lang('sales_add_payment'); ?>" />
														</span>
													</div>
												</div>
											</div>
											
										</div>
										<?php if($this->config->item('activar_pago_segunda_moneda')==1){
											$moneda1= $this->config->item('moneda1');
											$moneda2=$this->config->item('moneda2');
											$moneda3=$this->config->item('moneda3');
											?>
											<?= lang("sales_pay")?>
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda1')?>" equivalencia ="<?php echo $this->config->item('equivalencia1')?>" id="otra_moneda1" value="1" <?php echo ($pagar_otra_moneda and ($moneda_numero==1 or $currency==$moneda1))? "checked" :"" ?>> <strong> <?php echo $moneda1;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda2')?>" equivalencia ="<?php echo $this->config->item('equivalencia2')?>" id="otra_moneda2" value="2" <?php echo ($pagar_otra_moneda and ($moneda_numero==2 or $currency==$moneda2)) ? "checked" :"" ?>> <strong> <?php echo $moneda2;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="<?php echo $this->config->item('moneda3')?>" equivalencia ="<?php echo $this->config->item('equivalencia3')?>" id="otra_moneda3" value="3" <?php echo ($pagar_otra_moneda and ($moneda_numero==3 or $currency==$moneda3)) ? "checked" :"" ?>> <strong> <?php echo $moneda3;?></strong> - 
											 <input type="radio" name="otra_moneda" abreviatura="0" equivalencia ="1" id="otra_moneda0" value="0" <?php echo  ($pagar_otra_moneda==0 and $moneda_numero==0) ? "checked" :"" ?>> <strong>Default</strong><br>

										<?php }?>
									</li>
								</ul>
							</form>
						</div>
						<!-- END ADD PAYMENT -->
					<?php } ?>

					<div class="comment-sale">
						<ul class="list-group">
							<li class="list-group-item no-border-top tier-style">
								<?php
									// Only show this part if there is at least one payment entered.
									if((count($payments) > 0))
									{?>
										<div id="finish_sale">
											<?php echo form_open("sales/complete",array('id'=>'finish_sale_form', 'autocomplete'=> 'off')); ?>
												<?php if ($payments_cover_total && $customer_required_check && $valido_dato_casa_cambio)
												{
													echo "<input type='button' class='btn btn-success btn-large btn-block' id='finish_sale_button' value='".lang('sales_complete_sale')."' />";
												}?>
											</form>
										</div>

									<?php }							

									echo '<div id="container_comment" class="">';
									echo '<div class="title-heading"';
									echo '<label id="comment_label" for="comment">';
									echo "Observaciones para todo el ticket" ;//lang('common_comments');
									echo ':</label></div>';
									echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'class'=>'form-control form-textarea','value'=>$comment,'rows'=>'2',  'accesskey' => 'o'));
                                    echo '</div>';
                                    ?>

								
							</li>
						</ul>
					</div>

				<?php } ?>

				<div id="finish_sale" class="finish-sale">

				</div>


			</div>
    

</div>

<script>
	<?php if($this->config->item('activar_pago_segunda_moneda')==1){?>
		let monto= convertir_moneda(<?php echo $amount_due ?>,<?php echo $equivalencia?>);
		$("#amount_tendered2").val(monto);
		$("#amount_tendered2").change(function(){
			if($.isNumeric($("#amount_tendered2").val()) ){
				$("#amount_tendered").val(revertir_moneda($("#amount_tendered2").val(),<?php echo $equivalencia?>));
			}
		});
	<?php }?>
		$( "input[type=radio][name=otra_moneda]").change(function(){
			let moneda_numero= $(this).val();
			let equivalencia= $(this).attr("equivalencia");
			let abreviatura= $(this).attr("abreviatura");
			if (moneda_numero>0) {
				$.post('<?php echo site_url("sales/set_otra_moneda");?>', {"otra_moneda": 1,"moneda_numero":moneda_numero}, function()
				{
					$("#panel2").show();
					$("#panel1").hide();
					$("#abreviatura").html(abreviatura);
					let monto= convertir_moneda(<?php echo $amount_due ?>,equivalencia);
					$("#amount_tendered2").val(monto);
						});		
				}
			else{
				$.post('<?php echo site_url("sales/set_otra_moneda");?>', {"otra_moneda": 0,"moneda_numero":"default"}, function()
				{
					$("#panel1").show();
					$("#panel2").hide();
				});		
			}
		});

$("#contenedor_boton").show();
$('.delete_payment, .delete_tax , .delete_item').unbind( "click" ).click(function(event){
	event.preventDefault();
	$("#resumen-venta").load($(this).attr('href'));
});
$("#add_payment_button").click(function()
{
	$('#add_payment_form').ajaxSubmit({target: "#resumen-venta", beforeSubmit: salesBeforeSubmit1});
});
$("#add_payment_button2").click(function()
{
	$("#add_payment_button").click();
});

function salesBeforeSubmit1(formData, jqForm, options)
{		
	//ajax-loader
	//$("#ajax-loader").show();
	$("#add_payment_button").hide();
	$("#contenedor_boton").hide();
	$("#finish_sale_button").hide();
}
$('#add_payment_form').ajaxForm({target: "#resumen-venta", beforeSubmit: salesBeforeSubmit1});

<?php
			if(isset($error))
			{
				echo "toastr.error(".json_encode($error).", ".json_encode(lang('common_error')).");";
			}

			if (isset($warning))
			{
				echo "toastr.warning(".json_encode($warning).", ".json_encode(lang('common_warning')).");";
			}

			if (isset($success))
			{
				echo "toastr.success(".json_encode($success).", ".json_encode(lang('common_success')).");";
			}
			if(isset($clear) && $clear==true)
			{
				echo"  $('#reset-button').click(); ";
			}
			?>
	$(' #add_payment_form').ajaxForm({target: "#resumen-venta", beforeSubmit: salesBeforeSubmit1});

	$("#finish_sale_button").unbind( "click" ).click(function()
			{
				<?php
					$total_max1=$total+$total_max;

					if($this->config->item('value_max_cash_flow') <= $total_max1 && $this->config->item('limit_cash_flow')==1) {?>
       	  				toastr.error(<?php echo json_encode(lang("cash_flows_limit_1").to_currency($total_max1).lang("cash_flows_limit_2").to_currency($this->config->item('value_max_cash_flow'))); ?>, <?php echo json_encode(lang('common_error')); ?>);
						return false;
					<?php }?>

					<?php	if ($subcategory_of_items==true && !$this->sale_lib->is_select_subcategory_items()){ ?>
								toastr.error(<?php
									echo json_encode(lang("error_subcategory_item")." (".$this->config->item("custom_subcategory1_name")." y ".$this->config->item("custom_subcategory2_name").")");
								?>);
							return false;
						<?php } ?>

				//Prevent double submission of form
				$("#finish_sale_button").hide();
				$("#register_container").plainOverlay('show');

				<?php if ($is_over_credit_limit) { ?>
					if (!confirm(<?php echo json_encode(lang('sales_over_credit_limit_warning')); ?>))
					{
						//Bring back submit and unmask if fail to confirm
						$("#finish_sale_button").show();
						$("#register_container").plainOverlay('hide');

						return;
					}
				<?php } ?>

				<?php if(!$payments_cover_total) { ?>

					if (!confirm(<?php echo json_encode(lang('sales_payment_not_cover_total_confirmation')); ?>))
					{
						//Bring back submit and unmask if fail to confirm
						$("#finish_sale_button").show();
						$("#register_container").plainOverlay('hide');

						return;
					}
				<?php } ?>

				<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
					if (confirm(<?php echo json_encode(lang("sales_confirm_finish_sale")); ?>))
					{
						<?php } ?>

						/*if ($("#comment").val())
						{
							$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function()
							{
								$('#finish_sale_form').submit();
							});
						}
						else
						{*/
							$('#finish_sale_form').submit();
						//}

						<?php if (!$this->config->item('disable_confirmation_sale')) { ?>
						}
						else
						{
							//Bring back submit and unmask if fail to confirm
							$("#finish_sale_button").show();
							$("#register_container").plainOverlay('hide');
						}
						<?php } ?>
					});
					$("#comment").change(function()
						{
							$('#finish_sale_form').hide();
							$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()}, function()
							{
								$('#finish_sale_form').show();
							});
						});
						 $("#tasa_edit").change(function(){
								$("#contenedor_boton").hide();
								$("#mensaje-erro").html("");
								$.post('<?php echo site_url("changes_house/set_rate_all");?>', 
								{
									tasa: $('#tasa_edit').val()
									},
									function(respuesta){
									
										respuesta=JSON.parse(respuesta);
										
										if(respuesta.success==true){
											$("#resumen-venta").load('<?php echo site_url("sales/reload_change/1"); ?>');
											$("#cantidad_peso").change();
											
										}else{
											$("#mensaje-erro").html(respuesta.message)
										}
										$("#contenedor_boton").show();

									}
								);

							});
				function convertir_moneda($value, equivalencia=1){
					let total =  $value/equivalencia;
					return total;
				}
				function revertir_moneda($value,equivalencia=1){
					let total =  $value*equivalencia;
					return total;
				}
					


</script>