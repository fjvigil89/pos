<div class="portlet box light col-xs-12 col-sm-12" >
	<?php
	foreach ($busqueda->result() as $ListarBusqueda): ?>
		<div class="col-xs-12 col-sm-12" style="padding: 10px;border-bottom: 1px solid #DDDDDD">
			<div class="pull-left bordered">
				<b><?php echo $ListarBusqueda->name ?></b><br>
				<b><?php echo "Categoria: " . $ListarBusqueda->category ." | Precio = " . to_currency("$ListarBusqueda->unit_price", "2") ?></b>
			</div>
			<div class="pull-right bordered"><a class="btn btn-success" href="javascript:void(0);" title="agregar" onclick="controler('<?php echo site_url() ?>/technical_supports/agregarItemSupport','id=<?php echo $ListarBusqueda->item_id ?>&idSupport=<?php echo $idSupport ?>','refrescar');"><i class="fa fa-plus"></i></a></div>
		</div>
	<?php endforeach ?>
	<div class="col-xs-12 col-sm-12 text-right" style="border-top: 1px solid #DDDDDD;background: #EEEEEE;padding: 7px 10px 10px 0;">
		<strong><?php echo "Total Resultado: " . count($busqueda); ?></strong>
	</div>
</div>