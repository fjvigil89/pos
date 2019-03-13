<!-- Document Wrapper -->
	<div id="top-bar">
		<div class="container clearfix">
			<div class="col_half nobottommargin hidden-xs">
				<p class="nobottommargin"><strong>Llamanos:</strong> 1800-547-2145 | <strong>Email:</strong> ejemplo@email.com</p>
			</div>

			<div class="col_half col_last fright nobottommargin">

				<div class="top-links">
					<ul class="nav nav-pills top-right">
           				<li class="dropdown">
              				<div class="divisa-block">
            					<button type="button" class="btn btn-link dropdown-toggle boton-top" data-toggle="dropdown">Divisa <span class="caret"></span>
            					</button>
              
           						<ul class="dropdown-menu">
         	    					<li><a href="#">COP</a></li>
             						<li><a href="#">USD</a></li>
           						</ul>
             				</div>
           				</li>
					
						<li><a href="login.php" class="sf-with-ul"><i class="acc-closed icon-lock3"></i> Acceder</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="wrapper" class="clearfix">
		<header id="header" class="">
			<div id="header-wrap">
				<div class="container clearfix">
					<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
					<!-- Logo -->
						<div id="logo">
							<a href="<?php echo site_url('tienda'); ?>">
								<?php echo img(array(
									'src' => $logos['logotipo'],
									'class'=>'standard-logo',
									'id'=>'header-logo',
								)); ?>
							</a>

						</div>
					<!-- #logo end -->

					<!-- Menu principal -->
					<nav id="primary-menu" class="style-2">
						<ul class="sf-js-enabled">
							<li><a href="<?php echo site_url('tienda'); ?>"><div>Inicio</div></a></li>
							<li><a href="<?php echo site_url('tienda/category'); ?>"><div>Categoria</div></a></li>
							<li><a href="#"><div>Blog</div></a></li>
							<li><a href="<?php echo site_url('tienda/contact'); ?>"><div>Contacto</div></a></li>
						</ul>
	
						<!-- Top Cart -->
						<div id="top-cart">
							<a href="#" id="top-cart-trigger">
							<i class="icon-shopping-cart"></i>
							<!--Cantidad de productos en el carrito -->
								<span>1</span>
							</a>
							<div class="top-cart-content">
								<div class="top-cart-title">
									<h4>Carrito de compras</h4>
								</div>

								<div class="top-cart-items">
									<div class="top-cart-item clearfix">
										<div class="top-cart-item-image">
											<a href="#"><img src="<?php echo  $data['base_url']; ?>/assets/template/images/productos/mesa2.1.jpg" alt="Mesa abatible"></a>
										</div>
										<div class="top-cart-item-desc">
											<a href="#">Mesa abatible</a>
											<span class="top-cart-item-price">$24.99</span>
											<span class="top-cart-item-quantity">x 3</span>
										</div>
									</div>
								</div>

								<div class="top-cart-action clearfix">
									<!--precio final-->
									<span class="fleft top-checkout-price"></span>
									<button class="button button-3d button-small nomargin fright">Ir Al Carrito</button>
								</div>
							</div>
						</div>
							
						<!-- Top Search -->
						<div id="top-search">
							<a href="#" id="top-search-trigger"><i class="icon-search3"></i><i class="icon-line-cross"></i></a>
							<?php echo form_open('search.html',['method'=>'GET']); ?>  	
								<input type="text" name="q" class="form-control" value="" placeholder="Escriba &amp; Presione Enter..">
							</form>
						</div>
						<!-- #top-search end -->
					</nav>
					<!-- #primary-menu end -->
				</div>
			</div>
		</header>