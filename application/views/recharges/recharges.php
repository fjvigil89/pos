<?php $this->load->view("partial/header"); ?>
<div id="content-header" class="hidden-print">
	<h1><i class="icon fa fa-dashboard"></i> 
	<?php echo lang('common_dashboard'); ?>
	<?php 
					$extra="style='display: none; '";
					$url_video_ver="";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("RECARGAS");
						if($url_video!=null){
							$url_video_ver=$url_video;
							$extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				?>
	
	</h1>
</div>
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
	
</div>
<div class="clear"></div>
<div class="text-center">					
	<iframe src="https://sirse.bemovil.net/login/?next=/" frameborder="0" height="700" width="100%"></iframe>
</div>
<?php $this->load->view("partial/footer"); ?>

guadalupe brito

guacamaya

http://45.55.209.97/pos/index.php/reports/store_account_statements/10/1969-12-31/2017-08-31/0/sale_date