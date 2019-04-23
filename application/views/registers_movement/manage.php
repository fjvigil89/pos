  <?php $this->load->view("partial/header");
  $controller_name = "registers_movement";
  ?>

  <!-- BEGIN PAGE TITLE -->
  <div class="page-title">
  	<h1>
  		<i class="icon fa fa-money"></i>
  		<?php echo lang('module_' . $controller_name); ?>
		  <?php 
					$extra="";
					$url_video_ver="https://www.youtube.com/watch?v=I-gI9STQN1I";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("MOVIMIENTO DE CAJA");
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
  <!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD -->
<!-- BEGIN PAGE BREADCRUMB -->
<div id="breadcrumb" class="hidden-print">
	<?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->


<div class="clear"></div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box green" id="portlet-content">
			<div class="portlet-title">
				<div class="caption">
					<span class="icon">
						<i class="fa fa-th"></i>
					</span>
					<?php echo lang('common_list_of') . ' ' . lang('module_' . $controller_name) . " - " . $location_registers[$register_id]; ?>

				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-4 ">
						<?php if (count($location_registers) > 1) {?>
							<div class="pull-left">							
								<label style="padding:3px;"><?=lang('sales_register');?>: </label>
									<!-- Dropdown Locations -->
								<div class="btn-group">
									<?php echo form_dropdown('location_registers', $location_registers, $register_id, 'id="location_registers" class="bs-select form-control"'); ?>
								</div>							
							</div >	
						<?php }?>
					</div>
					<div class="col-md-8 ">
						<div class="pull-right margin-bottom-10">
								<div class="btn-group">
									<?php
									$CI = &get_instance();
									$permision = $CI->Employee->has_module_action_permission('registers_movement', 'add_update', $CI->Employee->get_logged_in_employee_info()->person_id);
									if ($permision == true) {
										echo anchor("registers_movement/operations/withdrawcash",
											'<i title="Registar gasto" class="fa fa-minus tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">Registar gasto</span>',
											array('class' => 'btn hidden-xs btn-danger', 'title' => 'Registar gasto'));

										echo anchor("registers_movement/operations/depositcash",
											'<i title="Depositar dinero" class="fa fa-plus tip-bottom hidden-lg fa fa-2x"></i><span class="visible-lg">' . lang('cash_flows_deposit_money') . '</span>',
											array('class' => 'btn hidden-xs btn-success', 'title' => 'Depositar dinero'));
										}?>
									</div>
								</div>

							</div>
					</div>
					<div class="col-md-12 ">
						<div class="row  margin-bottom-10">
							<div class="form-group">	
								<div class="col-md-2">
									Desde:
									<input type="text" placeholder="yyyy-mm-dd" value="<?php echo $desde;?>" class="datepicker form-control " id="datestart" name="desde">
								</div>
								<div class="col-md-2">
									Hasta: 
									<input type="text" placeholder="yyyy-mm-dd" value="<?php echo $hasta;?>" class="datepicker form-control" id="dateend" name="hasta">			
								</div>								
								<div class="col-md-2">
									Filtrar: 
									<select class="form-control" id="filter" name="filter">
										<option value="">Seleccione</option>
										<option <?php if($filter == 'register_movement_id'){ ?> selected <?php } ?> value="register_movement_id">ID</option>
										<option <?php if($filter == 'categorias_gastos'){ ?> selected <?php } ?> value="categorias_gastos">Categoría</option>
									</select>
		
								</div>
								<div class="col-md-2">
									Busqueda: 
									<input type="text" name="search" id="search" class="form-control" placeholder="Buscar" value="<?php echo $search ?>">
		
								</div>								
								<?php if(!$this->Employee->has_module_action_permission('registers_movement','see_cash_flows_uniqued',  $this->session->userdata('person_id'))):?>

									<div class="col-md-4">
										Empleado:
										<?php echo form_dropdown('empleado', $empleados, $id_empleado, 'id="empleado" class="bs-select form-control"'); ?>
									</div>
								<?php endif; ?>
								<div class="col-md-1">
									<br>
									<a id="findbydate" class="btn btn-primary dropdown-toggle" href="#">
										<span class="hidden-sm hidden-xs">Buscar</span >
									</a>								
								</div>
								<div class="col-md-3">
									<br>
									<a href="<?php echo base_url();?>/index.php/registers_movement" class="btn btn-info btn-block clear-state pull-right effect">Reestablecer la búsqueda</a>
									</a>								
								</div>
							</div>
							
						</div>
					
						
					</div>
					<div class="col-md-12 ">
							
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive" >

									<table id="table-registers_movement" class="table tablesorter table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>ID</th>
												<th>Fecha</th>
												<th>Descripción</th>
												<th>Entrada</th>
												<th>Salida</th>
												<th>Tipo de Documento</th>
												<th>Categoría</th>
												<th>Cajero</th>
												<?php if(!$this->config->item('not_show_column_cash_flow')){ ?>
												<th>En Caja</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($registers_movement->result() as $movement) {

												$amount      = number_format($movement->mount, 2, $this->config->item('decimal_separator'), $this->config->item('thousand_separator'));
												$amount_cash = number_format($movement->mount_cash, 2, $this->config->item('decimal_separator'), $this->config->item('thousand_separator'));

												$amount      = $this->config->item('currency_symbol') . $amount;
												$amount_cash = $this->config->item('currency_symbol') . $amount_cash;

												?>
												<tr>
													<td align='center'><?=$movement->register_movement_id;?></td>
													<td align='center'><?=$movement->register_date;?></td>
													<td align='center'><?=$movement->description;?></td>

													<?php if ($movement->type_movement == 1) {?>

													<td align='center'><?=$amount;?></td>
													<td></td>
													<td align='center'>INGRESO</td>

													<?php } 
													else {?>
														<td align='center'></td>
														<td align='center'><?=$amount;?></td>

													<td align='center'>GASTO</td>
													<?php }?>
													<td align='center'><?=$movement->categorias_gastos;?></td>
													<td><?=$movement->first_name;?> <?=$movement->last_name;?></td>
													<?php if(!$this->config->item('not_show_column_cash_flow')){ ?>
													<td align='center'><?=$amount_cash;?></td>
													<?php } ?>
												</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<script  type="text/javascript">


				$(document).ready(function(){


				// se traduce al español y se cambia el formato	al datepicker
					$('.datepicker').datepicker({
						dateFormat: "yy-mm-dd",
						firstDay: 1,
						changeMonth: true,
						dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
						dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
						monthNames: 
						["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
						"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
						monthNamesShort: 
						["Ene", "Feb", "Mar", "Abr", "May", "Jun",
						"Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
					});
					


			//Buscar todos los movimientos de la caja seleccionada
			$('#location_registers').change(function(){

				window.location = "<?=site_url("registers_movement/index")?>/"+$(this).val();

			});

			$('#findbydate').click(function(){
				var strdatestart = $("#datestart").val();
				var strdateend = $("#dateend").val();
				var id_empleado = $("#empleado").val();
				var filter = $("#filter").val();
				var search = $("#search").val();
				if (strdatestart === null || strdatestart === ""){
					alert("Debe llenar la fecha de inicio con la que quiere hacer la busqueda");
					return false;
				}
				if(strdateend === null || strdateend === ""){
					alert("Debe llenar la fecha fin para completar el rango de la busqueda");
					return false;
				}
				var datestart = strdatestart.split("-");
				var dateend = strdateend.split("-");
				var datestartclass = new Date(datestart[0], datestart[1] - 1, datestart[2],0,0,0,0);
				var dateend1class = new Date(dateend[0], dateend[1] - 1, dateend[2],0,0,0,0);
				var diff = dateend1class - datestartclass;
				var Ndays = diff/(1000*60*60*24) ;
				if (Ndays === null){Ndays = 60;}
				var Aceptar = false;
				if (Ndays > 30){
					if (confirm("El Rango de fecha es muy Alto esta Seguro que quiere buscar en este rango de fecha " + strdatestart + " hasta " + strdateend + " Esto puede tardar un poco")){
						Aceptar = true;
					}
				}else{
					if (confirm("Seguro que quiere buscar en este rango de fecha " + strdatestart + " hasta " + strdateend)){
						Aceptar = true;
					}
				}
				if (Aceptar === true){
					$("#findbydate").attr("href", "index.php/registers_movement?desde=" + strdatestart + "&hasta=" + strdateend + "&empleado=" + id_empleado + "&filter=" + filter + "&search=" + search);
					return true;
				}else{
					return false;
				}

			});

			$("#table-registers_movement").DataTable({
				"searching":       false,
				"language": {
					"sProcessing":     "Procesando...",
					"sLengthMenu":     "Mostrar _MENU_ registros",
					"sZeroRecords":    "No se encontraron resultados",
					"sEmptyTable":     "Ningún dato disponible en esta tabla",
					"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix":    "",
					"sSearch":         "",
					"sUrl":            "",
					"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "Primero",
						"sLast":     "Último",
						"sNext":     "Siguiente",
						"sPrevious": "Anterior"
					},

					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				},
				"order": [[ 0, "desc" ]]
			});


		});


	</script>

	<?php $this->load->view("partial/footer");?>

