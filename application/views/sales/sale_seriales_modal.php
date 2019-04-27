<div class="modal-dialog">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i> 
                    <span class="caption-subject bold">
                    	
                      Búsqueda por serial
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
								 		Serial:
								 	</strong>
								 </td> 
								 <td>
								 <?php echo form_open("sales/add_by_serial",array('id'=>'add_item_serial','class'=>'', 'autocomplete'=> 'off')); ?>
								 	<?php echo form_input(array(
									'name'=>'item_serial',
									'id'=>'item_serial',
									
									'class'=>'form-control form-inps',
									'placeholder' =>"Ingrese el serial  y presione enter o buscar",
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
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-info-circle"></i>
									<span class="caption-subject bold">
										Productos con el mismos número de serie
									</span>
								</div>
							</div>
							<div class="portlet-body">
								<table  class="table table-bordered table-hover table-striped" width="1200px">
									<tr>
										<th>ID</th>
										<th>Nombre</th>
										<th><?php echo lang('items_item_number'); ?></th>
										<th></th>
									</tr>
									<tbody id="tbody-series">
										
									</tbody>
								</table>
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
		$("#item_serial").focus();

		$("#add_item_serial").submit(function(event)
		{
			event.preventDefault();				
			if($("#item_serial").val()!=""){
				$("#ajax-loader").show();
				$.post('<?php echo site_url("sales/add_by_serial");?>', $("#add_item_serial").serializeArray(),
				function(data)
				{
					let cargar_table=true;
					try{
						data=JSON.parse(data);
					}catch(e){
						cargar_table=false;		
					}	
					if(!cargar_table){
						$("#modal-button").click();
						$("#register_container").html(data);
					}					
					else{	
						$("#ajax-loader").hide();				
						if(data.resultado==true){
							$("#panel-table").show();
							add_row(data.data);
						}
					}
				});
			}
		});
		$("#boton-buscar-serial").click(function(){
			$("#add_item_serial").submit();
		});
	});
	function add_row(data){
		$("#tbody-series").html("");										
		for (i in data) {			
			$("#tbody-series").append(
				"<tr>"+ 
				"<td align='center'>"+data[i].item_id+"</td>"+
				"<td align='center'>"+data[i].name+"</td>"+
				"<td align='center'>"+data[i].item_number+"</td>"+
				"<td align='center'>"+
				"<button  data-item_id-type ='"
					+data[i].item_id+"' data-item_serial-type ='"
					+data[i].item_serial+
					"' onclick='add_cart(this)' style='width: 90%' class='btn btn-sm btn-success '>"+
				"Agregar</button></td>"+
				"</tr>"
			);
		}
	}
	function add_cart(elemento){
		let  item_id = elemento.getAttribute("data-item_id-type");
		let  item_serial = elemento.getAttribute("data-item_serial-type");
		$("#ajax-loader").show();
		$.post('<?php echo site_url("sales/add_by_serial");?>', {item_serial: item_serial,item_id: item_id},
		function(data)
		{					
			$("#modal-button").click();
			$("#register_container").html(data);					
		});
	}
</script>


