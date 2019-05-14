<?php $this->load->view("partial/header"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.5/push.js"></script>
<style>

.aprobada {
	color: #17202A;
	background-color: rgb(0, 255, 109  ,0.2);
}
.aprobada:hover {
	color: #17202A;
	background-color: rgb(15, 240, 111  ,0.4);
}
.rechazada {
	color: #17202A;
	background-color: rgb(245, 183, 177,0.5);
}
.rechazada:hover {
	color: #17202A;
	background-color: rgb(245, 183, 177  ,0.8);
}
.pendiente {
	color: #17202A;
	background-color: rgb(236, 247, 238 ,0.1);
}
.pendiente:hover {
	color: #17202A;
	background-color: rgb(236, 247, 238 ,0.5);
}
.entregado {
	color: #17202A;
	background-color: rgb(0, 255, 109  ,0.5);
}
.entregado:hover {
	color: #17202A;
	background-color: rgb(15, 240, 111  ,0.7);
}
.procesando {
	color: #17202A;
	background-color:#f4f916de;
}
.procesando:hover {
	color: #17202A;
	background-color: yellow;
}

</style>
<script type="text/javascript">
$(document).ready(function(){
	var table_columns = ["","order_sale_id","number",'date','items','state',''];	
	enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
		
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir);?>);
});
</script>
<audio id="audio" style="display:none" controls>
<source type="audio/mp3" src="<?php echo base_url() ?>sonidos/SD_ALERT_26.mp3">
</audio>
	<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class="icon fa fa-university"></i>
				<?php echo lang('module_'.$controller_name); ?>
				<!--<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack888"></a>-->
				<?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=4cnV1nv0las&feature=youtu.be";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("BODEGA");
						if($url_video!=null){
							$url_video_ver=$url_video;
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				?>

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


	<div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>


	
	<script type="text/javascript">

		var start_date="<?php echo date('Y-m-d H:i:s'); ?>";
		var url = "<?php echo site_url("warehouse/last_orders")?>";
		var audio = document.getElementById("audio");
		var reproducir_sonido = <?= (int) $this->config->item('reproducrir_sonido_orden') ?>;
		
		function onGranted() {};

		function onDenied() {};

		function add_rows(rows){
			var last_rows = $("#manage_table").find('tbody').html();

			$("#manage_table").find('tbody').html(rows+last_rows);
		}
		Push.Permission.request(onGranted, onDenied);
		function last_orders(){
			$.get(url+"/"+start_date, function(data)
			{
				data = JSON.parse(data);
				start_date = data.date;

				if(data.rows > 0 && Push.Permission.has()){
						
						Push.create("Bodega ", {
						body: "Nuevas órdenes disponibles.",
						icon: '<?= $this->Appconfig->get_logo_image()?>',
						timeout: 16000,
						onClick: function () {
							window.focus();
							this.close();
						}
					});
				}
				if(data.rows > 0 && reproducir_sonido == 1){
					audio.play();
					add_rows(data.data);
				}								
			});
		}
		$(document).ready( function (){
			var JS_DATE_FORMAT = 'YYYY-MM-DD';
			setInterval('last_orders()',20000);	
			$('#start_date, #end_date').datetimepicker({
				format: JS_DATE_FORMAT,
				locale: "es"
			});

			$("#report_date_range_simple").change(function(){
				
				let fechas= $("#report_date_range_simple").val().split("/");
				$("#start_date").val(fechas[0]);
				$("#end_date").val(fechas[1]);
			})
		});
  	</script>

<?php $this->load->view("partial/footer"); ?>

