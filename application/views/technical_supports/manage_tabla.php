<table class="table table-bordered" id="table-suppor">
    <thead>
    <tr>
        <th style="width: 6%;"><?php echo lang("technical_supports_order_n"); ?></th>
        <th ><?php echo lang("sales_date"); ?></th>
        <th style="width: 20%;"><?php echo lang("technical_supports_customer"); ?></th>
        <th style="width: 15%;"><?php echo lang("technical_supports_model"); ?></th>
        <th><?php echo lang("technical_supports_type"); ?></th>
        <th><?php echo lang("technical_supports_estado"); ?></th>
        <th><?php echo lang("technical_supports_ubi_equipo"); ?></th>
        <th><?php echo lang("technical_supports_edit"); ?></th>
        <th><?php echo lang("technical_supports_option"); ?></th>
    </tr>
    </thead>
       
    <tbody>
    <?php 
    $color="";
    foreach ($servicios as $servicios):  
        $bgcolor="#FFFFFF;";
        if ($servicios->state == "RECHAZADO") { $color="red-flamingo"; $bgcolor="#F8E9E8"; } if ($servicios->state == "REPARADO") { $color="green"; $bgcolor="#DFF4DE"; } if ($servicios->state == "RECIBIDO") { $color="blue"; } if ($servicios->state == "DIAGNOSTICADO") { $color="blue-dark"; $bgcolor="#D7EBF3"; } if ($servicios->state == "APROBADO") { $color="blue-dark"; $bgcolor="#E6F0F4"; } ?>
            <tr class="text-center" style="font-weight: 600; background: <?php echo $bgcolor; ?>">
                <td><?php echo $servicios->Id_support; ?></td>
                <td><?php echo  date(get_date_format().' '.get_time_format(), strtotime($servicios->date_register)) ; ?></td>

                <td class="text-left"><?php echo $servicios->first_name . " " . $servicios->last_name; ?></td>
                <td><?php echo $servicios->model; ?></td>
                <td><?php echo $servicios->type_team; ?></td>
                <td style="text-align: left;">
                    <font class="font-<?php echo $color; ?>" style="font-weight: 800;"><?php echo $servicios->state; ?></font>
                </td>
                <td><?php echo $servicios->ubi_equipo; ?></td>
                <td id="apcioned<?php echo $servicios->Id_support; ?>">
                    <?php if ($servicios->state != "ENTREGADO") { ?>
                        <a onclick='controler("<?php echo site_url() ?>/<?php echo $controller_name ?>/viewEditarServicios","id_support=<?php echo $servicios->Id_support ?>","ventanaVer","")' class="btn btn-xs btn-primary"
                           title="<?php echo lang($controller_name . '_update') ?>"
                           href="javascript:void(0)" >
                                <i class="fa fa-edit"></i><?php echo lang('technical_supports_edit') ?>
                        </a>
                    <?php } ?>
                </td>
                <td id="apcion<?php echo $servicios->Id_support; ?>">
                        <?php if ($servicios->state == lang("technical_supports_recibido") Or $servicios->state == "APROBADO") { ?>
                                <a class="btn btn-xs btn-block btn-success"
                                   title="<?php echo lang('technical_supports_diagnose') ?>"
                                   href="<?php echo site_url() ?>/<?php echo $controller_name . "/repair/" . $servicios->Id_support ?>">
                                        <i class="fa fa-edit"></i><?php echo lang($controller_name . '_diagnose') ?>
                                </a>
                        <?php } elseif ($servicios->state == lang("technical_supports_diagnosticado")) { ?>
                                <a class="btn btn-xs btn-block btn-success"
                                   title="<?php echo lang($controller_name . '_aprobar_rechazar') ?>"
                                   href="<?php echo site_url() ?>/<?php echo $controller_name . "/repair/" . $servicios->Id_support ?>">
                                        <i class="fa fa-edit"></i><?php echo lang('technical_supports_aprobar_rechazar') ?>
                                </a>
                        <?php } elseif ($servicios->state == lang("technical_supports_aprobado")) { ?>
                                <a class="btn btn-xs btn-block btn-success"
                                   title="<?php echo lang($controller_name . '_reparar') ?>"
                                   href="<?php echo site_url() ?>/<?php echo $controller_name . "/repair/" . $servicios->Id_support ?>">
                                        <i class="fa fa-edit"></i><?php echo lang('technical_supports_reparar') ?>
                                </a>
                        <?php } elseif ($servicios->state == lang("technical_supports_reparado") ||
                                $servicios->state == lang("technical_supports_rechazado")) { ?>
                                <?php /* <a class="btn btn-xs btn-block btn-success"
                                   title="<?php echo lang($controller_name . '_entregar') ?>"
                                   href="<?php echo site_url() ?>/<?php echo $controller_name . "/repair/" . $servicios->Id_support ?>"> */ ?>
                                <div class="col-lg-6">
                                <a class="btn btn-xs btn-block btn-success" href="javascript:void(0);" title="<?php echo lang($controller_name . '_entregar') ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/carritoServicio/','hc=<?php echo $servicios->Id_support; ?>','ventanaVer','');"> 
                                    <i class="fa fa-shopping-cart"></i>
                                </a>
                                </div>
                                <div class="col-lg-6">
                                <a class="btn btn-xs btn-block btn-success " href="javascript:void(0);" title="<?php echo lang($controller_name . '_entregar') ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/detalles_serv_tecnico/','hc=<?php echo $servicios->Id_support; ?>','ventanaVer','');"> 
                                        <i class="fa fa-edit" ></i>
                                </a>
                                </div>
                        <?php } elseif ($servicios->state == "") { ?>
                                <a href="javascript:void(0)"></a>
                        <?php }if ($servicios->state == "ENTREGADO") { ?> 
                                <a class="btn btn-xs btn-block btn-success" href="javascript:void(0);" title="Ver registro" onclick="controler('<?php echo site_url() ?>/technical_supports/detalles_serv_tecnico/','hc=<?php echo $servicios->Id_support; ?>','ventanaVer','');"> 
                                        <i class="fa fa-search"></i>
                                </a>
                        <?php }  ?>
                </td>
            </tr>
    <?php endforeach ?>
    </tbody>
</table>

<script type="text/javascript">   
                                                        
	$(document).ready(function () {

		<?php if($this->config->item('hide_video_stack4') == '0'){?>
		$('.modal.fade').addClass('in');
		$('#stack4').css({'display': 'block'});
		<?php } ?>
		$('.modal.fade.in').click(function (e) {

			if ($(e.target)[0].id == "stack4") {
				$('.modal.fade.in').removeClass('in');
				$('#stack4').css({'display': 'none'});

			}


		});

		$('#closeitems').click(function () {

			$('.modal.fade.in').removeClass('in');
			$('#stack1').css({'display': 'none'});
			$('#maxitems').removeClass('icon fa fa-youtube-play help_button');
			$('#maxitems').html("<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>");
		});


		$('#checkBoxStack4').click(function (e) {

			$.post('<?php echo site_url("config/show_hide_video_help");?>',
				{show_hide_video4: $(this).is(':checked') ? '1' : '0', video4: 'hide_video_stack4'});

		});

		// Setup - add a text input to each footer cell
//		$('#table-suppor tfoot th').each( function () {
//			var title = $(this).text();
//			$(this).html( '<input type="text" class="form-control" placeholder=" '+title+'" />' );
//		} );

		// DataTable
                        
		var table = $('#table-suppor').DataTable({                    
                    
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

			$( 'input', this.header() ).on( 'keyup change', function () {
				if ( that.search() !== this.value ) {
					that
                                        .search( this.value )
                                        .draw();
                                                
				}
			} );
		} );
                 
                 
//		table.clean().draw();


	});
        
</script>
 
<?php if (isset($mensaje_editar_servicio)) {
echo "<script>toastr.success('$mensaje_editar_servicio','Exito!');</script>";
} ?>