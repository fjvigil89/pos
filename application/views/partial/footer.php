
<?php

if($this->Appconfig->es_franquicia()==true){
	$data_commpany= $this->Appconfig->get_data_commpany($this->config->item('resellers_id'));
	
	$foot_msg = "Todos los derechos reservados de ". $data_commpany->short_name .", Estas usando la versión <span class='label label-info'>". APPLICATION_VERSION  
		."</span> ". $data_commpany->short_name .",  visita <a target='_blank' href='".  $data_commpany->url_website ."'>".  $data_commpany->url_website ."</a>";

	$theme_commpany = $this->Appconfig->get_theme_commpany($this->config->item('resellers_id'));
    foreach ($theme_commpany as $row) {
        $arr_theme[$row->name_feature_css] = $row->css_class;
	}
} else {
	$foot_msg = "Todos los derechos reservados de ". NAME_COMPANY .", Estas usando la versión <span class='label label-info'>". APPLICATION_VERSION  
		."</span> ". NAME_COMPANY .",  visita <a target='_blank' href='".  URL_COMPANY ."'>FacilPos.co</a>";
	$arr_theme = array();
}
?>

				</div>
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END CONTAINER -->

		<?php 
		if (isset($arr_theme['page-boxed']) AND $arr_theme['page-boxed'] == 'page-boxed') {
			if (isset($arr_theme['page-footer-fixed']) AND $arr_theme['page-footer-fixed'] != 'page-footer-fixed') { 
			?>
				 <!-- BEGIN FOOTER --> 
				 <div class="page-footer">
					<div class="page-footer-inner"><?php echo $foot_msg; ?></div>
					<div class="scroll-to-top"><i class="fa fa-arrow-circle-up"></i></div>					
				</div>
				<!-- END FOOTER --><?php

		 		echo '</div>'; //se cierra <div class="container">
			} else {
				echo '</div>'; //se cierra <div class="container">
				?><div class="page-footer">
					<div class="container">
		            <div class="page-footer-inner"><?php echo $foot_msg; ?></div>
		            <div class="scroll-to-top"><i class="fa fa-arrow-circle-up"></i></div>
			        </div>
				</div><?php
			}	 
		 	
		} else {
			?><!-- BEGIN FOOTER --><div class="page-footer">
				<div class="page-footer-inner"><?php echo $foot_msg; ?></div>	
				<div class="scroll-to-top"><i class="fa fa-arrow-circle-up"></i></div>				
			</div><!-- END FOOTER --><?php
		}
		?>

		<script>
			jQuery(document).ready(function() {    
			   	Metronic.init(); // init metronic core componets
			   	Layout.init(); // init layout
			   	Demo.init(); // init demo features
			 	ComponentsDropdowns.init();

			/*
			//metodo para hacer que los edit carguen los valores monetarios separados
			//por miles
				$('input.money').val(function(index, value) {
					return value
						.replace(/\D/g, "")
						.replace(/([0-9])([0-9]{2})$/, '$1,$2')  
						.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".")
					;
				});
			//fin
			*/

			Split_by_miles($('input.money'));
			});


		function Split_by_miles(input)
		{

			/*

			//funcion en Jquery para separar numeros en milles
			input.keyup(function(event) {
				// skip for arrow keys
				if(event.which >= 37 && event.which <= 40){
					event.preventDefault();
				}

				input.val(function(index, value) {
					return value
						.replace(/\D/g, "")
						.replace(/([0-9])([0-9]{2})$/, '$1.$2')  
						.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
					;
				});
			});
			//fin de separar numeros

			*/


		}


		
		
        
		
		</script>

		<script> var csfrData={};csfrData['<?php echo $this->security->get_csrf_token_name();?>']= '<?php echo $this->security->get_csrf_hash();?>';$(function(){$.ajaxSetup({data: csfrData});});</script>	

		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script> -->

        <script src="js/publics.js"></script>
        <script src="js/confirm/jquery-confirm.js"></script>
        <script src="js/bootstrap-validator/bootstrapValidator.min.js"></script>
		 <?php if($this->Appconfig->is_offline_sales()):?>
			<script src="<?php echo base_url();?>js/offline/helper_data_sesion.js" type="text/javascript"></script>

		    <script src="<?php echo base_url();?>js/offline/sale.js" type="text/javascript"></script>

			<script src="<?php echo base_url();?>js/offline/customer.js" type="text/javascript"></script>
			<iframe style="display:none" src="<?php echo site_url("sincronizar/get_data_cache")?>"> 
			</iframe>
		<?php endif; ?>
	
	<!--Scrip de Calendar-->
		 <!-- BEGIN CORE PLUGINS -->
		 <script src=<?php echo base_url()."assets/global/plugins/fullcalendar/jquery.min.js" ?> type="text/javascript"></script>
        <script src=<?php echo base_url()."assets/global/plugins/fullcalendar/bootstrap.min.js" ?> type="text/javascript"></script>
        
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        
        <script  src=<?php echo base_url()."assets/global/plugins/fullcalendar/fullcalendar.min.js" ?> type="text/javascript"></script>
        <script  src=<?php echo base_url()."assets/global/plugins/fullcalendar/jquery-ui.min.js" ?> type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src=<?php echo base_url()."assets/global/plugins/fullcalendar/app.min.js" ?> type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script  src=<?php echo base_url()."assets/global/plugins/fullcalendar/calendar.min.js" ?> type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        
        <!-- END THEME LAYOUT SCRIPTS -->
	</body>

</html>