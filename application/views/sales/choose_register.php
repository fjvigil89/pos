<?php $this->load->view("partial/header"); ?>

		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="fa fa-upload"></i>
				<?php echo lang('sales_choose_register');?> 
				<?php /* 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=Gkjdo5oDhYo";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("VENTAS");
						if($url_video!=null){
							$url_video_ver=$url_video;
							$extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;*/
					?>
					<a class="icon fa fa-youtube-play help_button" id='modal-video-tutorial' rel='0' data-toggle="modal" data-target="#stack"></a>

			</h1>
		</div>
		<!-- END PAGE TITLE -->		
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE BREADCRUMB -->
	<div id="breadcrumb" class="hidden-print">
        <a href="<?php echo site_url("sales/") ?>"><i class="fa fa-home"></i> Panel</a><a href="<?php echo site_url("sales/choose_register_offline") ?>">Ventas</a>	
    </div>
	<!-- END PAGE BREADCRUMB -->

	<div class="clear"></div>

 	<div class="portlet light">
		<div class="portlet-body">
			<div class="row">
				<div class="col-md-12">
					<div class="widget-box">

						<div class="row"> 
						<?php $location_id= $this->Employee->get_logged_in_employee_current_location_id() ;
								$person_id= $this->session->userdata('person_id');
								$cajas =$this->Cajas_empleados->get_cajas_ubicacion_por_persona($person_id,$location_id)?>
							<?php
							if($cajas->num_rows()==0){
								echo"<strong>No tiene caja asiganada</strong>";
							}
							foreach($cajas->result() as $register) { 
							// foreach($this->Register->get_all()->result() as $register) { 
								?>
								<div class="col-md-3 site-stats">
									<h4><?php echo anchor('sales/choose_register/'.$register->register_id, '<i class="fa fa-inbox fa-2x-custom"></i> '.$register->name, 'class="btn grey"'); ?></h4>
								</div>
							<?php } ?> 					
						</div>
						<div class="row">
						
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
	<?=$this->load->view("tutorials");?>

<?php $this->load->view('partial/footer'); ?>

<script type='text/javascript'>

</script>