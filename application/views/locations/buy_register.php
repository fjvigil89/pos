<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE 
<div class="page-title">
	<h1>
		<i class='fa fa-pencil'></i>
			Title
	</h1>
</div>
-->
<!-- END PAGE TITLE -->		
</div>
<!-- END PAGE HEAD -->

<!-- BEGIN PAGE BREADCRUMB -->
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->

<div class="clear"></div>
	
<?php echo form_open( $gateway_url, array('id'=>'location_form_auth','class'=>'form-horizontal')); ?>		
<div class="portlet box green">
	
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-book-open"></i>
			<span class="caption-subject bold">
				<?php echo "Compra de cajas registradoras" ?>
			</span>
		</div>					
	</div>

	<div class="portlet-body form">
		<div class="form-body">	
			<input name="merchantId"      type="hidden"  value="<?php echo $merchantId;?>"   >
			<input name="accountId"       type="hidden"  value="<?php echo $accountId;?>" >
			<input name="description"     type="hidden"  value="Compra de caja registradora"  >
			<input name="referenceCode"   type="hidden"  value="<?php echo $referenceCode;?>" >
			<input name="tax"             type="hidden"  value="0"  >
			<input name="taxReturnBase"   type="hidden"  value="0" >
			<input name="currency"        type="hidden"  value="<?php echo $currency;?>" >
			<input name="test"            type="hidden"  value="<?php echo $test;?>" >
			<input name="responseUrl"     type="hidden"  value="<?php echo $responseUrl;?>" >
			<input name="confirmationUrl" type="hidden"  value="<?php echo $confirmationUrl;?>" >
			<input name="extra1"          type="hidden"  value="<?php echo $extra1;?>" >
			<input id="amount" name="amount" type="hidden" value="<?php echo $register_value;?>">
			<input id="signature" name="signature" type="hidden" value="">
			
			<div class="form-group">
				<?php echo form_label('Cantidad de cajas adicionales'.':', 'name',array('class'=>'col-md-3 control-label')); ?>
				<div class="col-md-8">
					<input type="number" class="form-control form-inps" name="quantity" id="quantity" min="0" value="1">
				</div>	
			</div>
			
			<div class="col-md-offset-3">
				<h4 id="total">Total: <?php echo $register_value;?> $</h4>
			</div>
		</div>

		<div class="form-actions right">
			<button onclick="this.form.urlOrigen.value = window.location.href;" class="btn btn-primary">Comprar</button>
		</div>
							
	</div>
</div>

<?php echo form_close(); ?>
<?php $this->load->view('partial/footer'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>
<script>
	$(document).ready(function(){
		
		var md5 = function(value) { return CryptoJS.MD5(value).toString(); };
		
		$('#quantity').change(function(){
			var quantity          = this.value;
			var register_value    = '<?php echo $register_value;?>';
			var value             = quantity * Number(register_value);
			 
			var signature_string  = '<?php echo $api_key."~".$merchantId."~".$referenceCode."~"."'+value+'"."~".$currency;?>';
			var signature         = md5(signature_string); 
			
			$('#signature').val(signature);
			$('#amount').val(value);
			$('#total').text('Total: '+value+' $');
		});
	});
</script>
