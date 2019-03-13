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
			 	?><!-- BEGIN FOOTER --><div class="page-footer">
					<div class="page-footer-inner"><?php echo $foot_msg; ?></div>
					<div class="scroll-to-top"><i class="fa fa-arrow-circle-up"></i></div>					
				</div><!-- END FOOTER --><?php

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
	
	</body>

</html>