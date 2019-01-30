
<?php
//para activar tutoriales quitar if
 if(false){?>
<div data-width="500" tabindex="-1" class="modal fade" id="stack1" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Presentacion 
				<button type="button" class='close' id="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">			
				<iframe width="560" height="315" src="https://www.youtube.com/watch?v=lc88w66rH2k/?rel=0&autoplay=<?php echo $this->config->item('hide_video_stack1') == '1'? "0":"1";?>" frameborder="0" allowfullscreen></iframe>
				<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack1" value="1" id="checkBoxStack1" class="md-check" <?php echo $this->config->item('hide_video_stack1') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack1"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			   <label class="pull-right">	
				     	<a class="btn green-jungle" href="<?php echo site_url('config');?>">Siguiente</a>
				</label>				
			</div>	
		</div>
	</div>
</div>	

<div data-width="500" tabindex="-1" class="modal fade" id="stack2" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				<button type="button" id='closeconfig' class="close" data-dismiss="modal" rel=0; aria-hidden="true"></button>
				Video Tutorial  de Configuracion

			</div>
			<div class="modal-body">			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/A7AGsLGGLso/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
				<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack2" value="1" id="checkBoxStack2" class="md-check" <?php echo $this->config->item('hide_video_stack2') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack2"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			 	    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('home');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('locations');?>">Siguiente</a>
				     </label>                    
				
				</div>	
			</div>
	</div>
</div>	
<div data-width="500" tabindex="-1" class="modal fade" id="stack3" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de Proveedores

					<button type="button" id='closesuppliers' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack3" value="1" id="checkBoxStack3" class="md-check" <?php echo $this->config->item('hide_video_stack3') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack3"><span></span><span class="check"></span><span class="box"></span>No volver a mostrar este video</label></div>				
			 
                      

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('locations');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('items');?>">Siguiente</a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>				


<div data-width="500" tabindex="-1" class="modal fade" id="stack4" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de Inventario
					<button type="button" id='closeitems' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack4" value="1" id="checkBoxStack4" class="md-check" <?php echo $this->config->item('hide_video_stack4') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack4"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			 
                      

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('suppliers');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('sales');?>">Siguiente</a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>				
			

<div data-width="500" tabindex="-1" class="modal fade" id="stack5" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de Ventas
					<button type="button" id='closesales' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack5" value="1" id="checkBoxStack5" class="md-check" <?php echo $this->config->item('hide_video_stack5') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack5"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			 
                      

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('items');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('reports');?>">Siguiente</a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>				
<div data-width="500" tabindex="-1" class="modal fade" id="stack6" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de  configuración de tiendas
					<button type="button" id='closestore' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack6" value="1" id="checkBoxStack6" class="md-check" <?php echo $this->config->item('hide_video_stack6') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack6"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			 
                      

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('config');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('suppliers');?>">Siguiente</a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>	
<div data-width="500" tabindex="-1" class="modal fade" id="stack7" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de  reportes 
					<button type="button" id='closereports' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack7" value="1" id="checkBoxStack7" class="md-check" <?php echo $this->config->item('hide_video_stack7') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack7"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			                     

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('sales');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('home');?>">Finalizar </a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>	
<div data-width="500" tabindex="-1" class="modal fade" id="stack8" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de apertura de caja  
					<button type="button" id='closeamount' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
			
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lc88w66rH2k/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
								<div class="md-checkbox"><input type="checkbox" name="scheckBoxStack8" value="1" id="checkBoxStack8" class="md-check" <?php echo $this->config->item('hide_video_stack8') == '1'? "checked":"";?> /><label id="show_comment_on_receipt_label" for="checkBoxStack8"><span></span><span class="check"></span><span class="box"></span>No volver mostrar este video</label></div>				
			 
                      

				 
				    <label class="pull-right">
				    	<a class="btn yellow-crusta" href="<?php echo site_url('items');?>">Atrás</a>
				     	<a class="btn green-jungle" href="<?php echo site_url('sales');?>">Siguiente </a>
				     </label>
                        
				
				</div>	
		</div>
	</div>
</div>				
<div data-width="500" tabindex="-1" class="modal fade" id="stack9" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="padding-bottom: 40px">
			<div class="modal-header">
				Video Tutorial  de Subcategoría 
					<button type="button" id='closeamount' class="close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/DJ3UH_TW_S4/?rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe>
			
			</div>	
		</div>
	</div>
</div>			
				<?php }?>