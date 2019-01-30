<div class="modal-dialog">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                    	
                      Búsqueda de <?php echo strtolower ($this->config->item('sale_prefix')) ?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" id="modal-button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
	            	
					<div class="col-sm-12 margin-bottom-05">
						<div class="table-responsive">
							<table class="table table-bordered table-striped" width="100%">
							<tr>
								<td>
								 	<strong>
								 		Código:
								 	</strong>
								 </td> 
								 <td>
								 <?php echo form_open("sales/search_ticket",array('id'=>'search_ticket','class'=>'', 'autocomplete'=> 'off')); ?>
								 	<?php echo form_input(array(
									'name'=>'number_ticket',
									'id'=>'number_ticket',									
									'class'=>'form-control form-inps',
									'placeholder' =>"Ingrese el código  y presione enter o buscar",
									'value'=>"")
									);?>
								</form>		
								</td>
								<td>
								   <button class="btn" id="boton-buscar-serial" > BUSCAR </button>			
								</td>
							</tr>							
							</table>
						</div>
					</div>
					<div style="display:none" id="panel-table" class="col-sm-12 margin-bottom-05">
						<div class="portlet box grey-cascade">
							
							<div id="mensajes" class="portlet-body">
							
							</div>
						</div>
					</div>		
							
				</div>			
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("#number_ticket").focus();

		$("#search_ticket").submit(function(event)
		{
			event.preventDefault();		
			$("#panel-table").hide();	
			if($("#number_ticket").val()!=""){
				
				$("#ajax-loader").show();
				$.post('<?php echo site_url("sales/search_ticket");?>', $("#search_ticket").serializeArray(),
				function(data)
				{	
					data= JSON.parse(data);
					if(data.respuesta==true){	
						window.location='<?php echo site_url('sales/change_sale_with_ticket'); ?>'+'/'+data.sale_id;
						$("#modal-button").click();
					}else{
						$("#panel-table").show();
						$("#mensajes").html("<strong>"+data.mensaje+"</strong>")
					}
					$("#ajax-loader").hide();
						
				});
				$("#number_ticket").val("");
			}
		});
		$("#boton-buscar-serial").click(function(){
			$("#search_ticket").submit();
		});
	});
	
	
</script>


