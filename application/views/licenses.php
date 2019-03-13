<?php $this->load->view("partial/header"); ?>
    
    <div class="row">
        
        <div class="col-md-12">
            <div class="portlet light">
                
                <div class="portlet-title">
					<div class="caption">
						<i class="icon-bar-chart font-green-haze"></i>
						<span class="caption-subject bold uppercase font-green-haze">
                            Pagos de Licencia
                        </span>
					</div>
				</div>
                
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Método de pago</th>
                                <th>Fecha de transacción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable_ajax').DataTable( {
            language:{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
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
            
            "ajax": "<?php echo site_url("licenses/payments");?>",
            "columns": [
                { "data": "description" },
                { "data": "value" },
                { "data": "payment_method" },
                { "data": "transaction_date" }
            ]
        });
    });
</script>
<?php $this->load->view("partial/footer"); ?>