<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg" style="width: 97%;">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" class="close img-circle" style="background: #FFFFFF;color: #000000;padding: 7px 9px 7px 9px;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/config/tservicios/','1','listarFiador',$('#ventanaVer').html(''));"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title">Ver Historial del Cliente</h4>
                <small class="font-bold">Información de los servicios prestados al cliente.</small>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="portlet box green">
                        <div class="portlet-title">
                                <div class="caption">
                                        <span class="icon"><i class="fa fa-th"></i></span>
                                        <?php echo lang('config_company_ventetqtfallas'); ?>
                                </div> 
                        </div>
                        <div class="portlet-body table-responsive">
                            <table class="table table-bordered" id="table-hsuppor">
                                <thead>
                                <tr style="background: #FAFAFA;">
                                    <th><?php echo lang("technical_supports_vntorden"); ?></th>
                                    <th><?php echo lang("technical_supports_vntequipo"); ?></th> 
                                    <th><?php echo lang("technical_supports_vntmodelo"); ?></th>
                                    <th><?php echo lang("technical_supports_vntcolor"); ?></th>
                                    <th><?php echo lang("technical_supports_vntaccs"); ?></th> 
                                    <th><?php echo lang("technical_supports_vntfalla"); ?></th>
                                    <th><?php echo lang("technical_supports_vntfecha"); ?></th>
                                    <th><?php echo lang("technical_supports_vntestado"); ?></th>
                                </tr>
                                </thead> 
                                <tbody>
                                <?php 
                                foreach ($hCliente->result() as $hCliente){ 
                                    if($hCliente->state=="ENTREGADO"){ $color="color: blue"; $lFecha="Entregado: ".date('d-m-Y', strtotime($hCliente->date_entregado)); }
                                    if($hCliente->state=="RECHAZADO"){ $color="color: red";  $lFecha="Recibido: ".date('d-m-Y', strtotime($hCliente->date_register)); }
                                    if($hCliente->state=="RECIBIDO" Or $hCliente->state=="REPARADO"){ $color="color: #008966";  $lFecha="Recibido: ".date('d-m-Y', strtotime($hCliente->date_register)); }
                                    ?>
                                    <tr> 
                                        <td class="text-center"><?php echo $hCliente->order_support; ?></td>
                                        <td><?php echo $hCliente->type_team; ?></td>
                                        <td><?php echo $hCliente->model; ?></td> 
                                        <td><?php echo $hCliente->color; ?></td>
                                        <td><?php echo $hCliente->accessory; ?></td>
                                        <td><?php echo $hCliente->damage_failure; ?></td>
                                        <td><?php echo $lFecha; ?></td>
                                        <td class="text-center" style="<?php echo $color; ?> "><?php echo $hCliente->state; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>  
            
        </div>
        
    </div>
</div>

<script type="text/javascript">

	$(document).ready(function () {
		// DataTable
		var table = $('#table-hsuppor').DataTable({
			"scrollX": true,
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "<?php echo lang('common_search'); ?> <?php echo lang('module_' . $controller_name); ?>",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},

				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			"order": [[0, "asc"]],
			"searching": true
		});

		// Apply the search
		table.columns().every( function () {
			var that = this;

			$( 'input', this.footer() ).on( 'keyup change', function () {
				if ( that.search() !== this.value ) {
					that
						.search( this.value )
						.draw();
				}
			} );
		} );



	});

</script>