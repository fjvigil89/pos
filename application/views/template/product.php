<div class="postcontent nobottommargin">
<div id="shop" class="shop shop product-3 clearfix" data-layout="fitRows">
<?php  foreach ($productos as $producto ){ 
$Base64Img = base64_encode($producto->file_data);
$sm = $this->config->item("thousand_separator");
$sd = $this->config->item("decimal_separator");
$symbol = $this->config->item("currency_symbol");
$cost_price = number_format($producto->cost_price, 2, $sm, $sd);


if(empty($Base64Img)){
	$src=base_url("img/no-photo.jpg");

}else {
	$src="data:image/jpeg;base64,". $Base64Img;

}

?>

		<div class="product clearfix">
			<div class="product-image">
				<a  href="<?php echo  site_url("tienda/shop-item"); ?>?id=<?php echo  $producto->item_id; ?>" class="item-quick-view" data-lightbox="ajax"><img src="<?php echo  $src; ?>" alt="<?php echo  $producto->name; ?>"></a>
				
				<!--div class="sale-flash">50% de descuento*</div-->
				<div class="product-overlay">
					<a href="#" class="add-to-cart"><i class="icon-shopping-cart"></i><span> Añadir al carrito</span></a>
					<a   href="<?php echo  site_url("tienda/shop-item"); ?>?id=<?php echo  $producto->item_id; ?>" class="item-quick-view" data-lightbox="ajax"><i class="icon-zoom-in2"></i><span> Vista previa</span></a>
				</div>
			</div>
			<div class="product-desc">
				<div class="product-title"><h3><a href="#"><?php echo  $producto->name; ?></a></h3></div>
				<div class="product-price"><del><?php echo  $symbol; ?><?php echo  $cost_price; ?></del> <ins><?php echo  $symbol; ?><?php echo  $cost_price; ?></ins></div>
				<div class="product-rating">
					<input id="input-11" type="number" class="rating form-control hide" max="5" data-step="1" data-size="sm" data-glyphicon="false" data-rating-class="fontawesome-icon">
				</div>
			</div>
		</div>
<?php } ?>


	</div>
</div>