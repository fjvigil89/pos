<section id="content">
	<!-- Sub Menu -->
		<div class="content-wrap">
			<div class="container clearfix">
				<ul id="portfolio-filter" class="portfolio-filter clearfix" data-container="#categorias" data-active-class="current">
					<li class="current"><a href="#" data-filter="*"><div>Mostrar Todos</div></a></li>
					<?php foreach ($category as $key => $value){ ?>
					<li><a href="#" data-filter=".catg-<?php echo  $value->category; ?>"><div><?php echo  $value->category; ?></div></a></li>
					<?php } ?>
				</ul>
				<div id="portfolio-shuffle" class="portfolio-shuffle" data-container="#portfolio">
					<i class="icon-random"></i>
				</div>
					<div class="clear"></div>
	
				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-9">
				<div id="categorias" class="portfolio grid-container portfolio-2 clearfix">
					<?php  foreach ($productos as $producto ){ 
					$Base64Img = base64_encode($producto->file_data);
					$sm = $this->config->item("thousand_separator");
					$sd = $this->config->item("decimal_separator");
					$symbol = $this->config->item("currency_symbol");
					$cost_price = number_format($producto->cost_price, 2, $sm, $sd);
					?>
					<article class="portfolio-item catg-<?php echo  $producto->category; ?>">
						<div class="portfolio-image">
							<a href="product-single.php">
								<img src="data:image/jpeg;base64, <?php echo  $Base64Img; ?>" alt="<?php echo  $producto->name; ?>">
							</a>
							<div class="portfolio-overlay">
								<a href="data:image/jpeg;base64, <?php echo  $Base64Img; ?>" class="left-icon" data-lightbox="image"><i class="icon-search"></i></a>
								<a href="<?php echo  site_url("tienda/shop-item"); ?>?id=<?php echo  $producto->item_id; ?>" class="right-icon"><i class="icon-line-ellipsis"></i></a>
							</div>
						</div>
						<div class="portfolio-desc">
							<div class="product-title"><h3><a href="#"><?php echo  $producto->name; ?></a></h3></div>
								<span><a href="#">Mesas</a></span>
							<div class="product-price"><del><?php echo  $symbol; ?><?php echo  $cost_price; ?></del> <ins><?php echo  $symbol; ?><?php echo  $cost_price; ?></ins></div>
							<div class="product-rating">
								<input id="input-11" type="number" class="rating form-control hide" max="5" data-step="1" data-size="sm" data-glyphicon="false" data-rating-class="fontawesome-icon">
							</div>
						</div>
					</article>
					
					<?php } ?>

				</div>
				</div>

				<div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
				<?php
				 $this->load->view('template/sidebar');
				 ?>
				</div>
			</div>
		</div>
</section>