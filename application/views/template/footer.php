<footer id="footer" class="dark notopborder">
	<div class="container">
	<!-- Footer Contenido -->
		<div class="footer-widgets-wrap clearfix">
			<div class="row clearfix">
				<div class="col-md-6">
					<div class="widget clearfix">
						<div class="row">
							<div class="widget clearfix">
								<a href="<?php echo site_url('tienda'); ?>">
									<?php echo img(array(
										'src' => $logos['logotipo'],
										'class'=>'footer-logo',
										'id'=>'header-logo',
									)); ?>
								</a>
								<div class="clearfix" style="padding: 10px 0; background: url('<?php echo  $data['base_url']; ?>/assets/template/images/world-map.png') no-repeat center center;">
									<div class="col_half">
										<address class="nobottommargin">
										<abbr title="Headquarters" style="display: inline-block;margin-bottom: 7px;"><strong>Direccion:</strong></abbr><br>
										Ave. Antoni de Berrios, Cll 24<br>
										San Felix, CA 9410<br>
										</address>
									</div>
									<div class="col_half col_last">
										<abbr title="Phone Number"><strong>Telefono:</strong></abbr> (91) 8547 632521<br>
										<abbr title="Fax"><strong>Fax:</strong></abbr> (91) 11 4752 1433<br>
										<abbr title="Email Address"><strong>Email:</strong></abbr> ejemplo@email.com
									</div>
								</div>
							</div>
							<div>
								<a href="<?php echo $shop_redes_facebook;?>" class="social-icon si-rounded si-small si-colored si-facebook" target="_blank">
									<i class="icon-facebook"></i>
									<i class="icon-facebook"></i>
								</a>

								<a href="<?php echo  $shop_redes_twitter; ?>" class="social-icon si-rounded si-small si-colored si-twitter" target="_blank">
									<i class="icon-twitter"></i>
									<i class="icon-twitter"></i>
								</a>

								<a href="<?php echo  $shop_redes_google; ?>" class="social-icon si-rounded si-small si-colored si-gplus" target="_blank">
									<i class="icon-gplus"></i>
									<i class="icon-gplus"></i>
								</a>

								<a href="#" class="social-icon si-rounded si-small si-colored si-pinterest">
									<i class="icon-pinterest"></i>
									<i class="icon-pinterest"></i>
								</a>

								<a href="#" class="social-icon si-rounded si-small si-colored si-vimeo">
									<i class="icon-vimeo"></i>
									<i class="icon-vimeo"></i>
								</a>

								<a href="#" class="social-icon si-rounded si-small si-colored si-youtube">
									<i class="icon-youtube"></i>
									<i class="icon-youtube"></i>
								</a>

								<a href="#" class="social-icon si-rounded si-small si-colored si-skype">
									<i class="icon-skype"></i>
									<i class="icon-skype"></i>
								</a>

								<a href="#" class="social-icon si-rounded si-small si-colored si-wordpress">
									<i class="icon-wordpress"></i>
									<i class="icon-wordpress"></i>
								</a>
							</div>
						</div>
					</div>
						<div class="visible-sm bottommargin-sm"></div>
				</div>
					<div class="visible-sm visible-xs line"></div>

				<div class="col-md-6">
					<div class="widget quick-contact-widget clearfix">
						<h4>Enviar un mensaje</h4>
						<div class="quick-contact-form-result"></div>
						<?php echo form_open('',['id'=>'quick-contact-form','name'=>'quick-contact-form', 'method'=>'POST', 'class'=>'quick-contact-form nobottommargin', 'novalidate'=>'novalidate']);?>
								<div class="form-process"></div>
								<div class="input-group divcenter">
									<span class="input-group-addon"><i class="icon-user"></i></span>
									<input type="text" class="required form-control input-block-level" id="quick-contact-form-name" name="quick-contact-form-name" value="" placeholder="Nombre Completo" aria-required="true">
								</div>
								<div class="input-group divcenter">
									<span class="input-group-addon"><i class="icon-email2"></i></span>
									<input type="text" class="required form-control email input-block-level" id="quick-contact-form-email" name="quick-contact-form-email" value="" placeholder="Direccion de Email" aria-required="true">
								</div>
									<textarea class="required form-control input-block-level short-textarea" id="quick-contact-form-message" name="quick-contact-form-message" rows="4" cols="30" placeholder="Mensaje" aria-required="true"></textarea>
									<input type="text" class="hidden" id="quick-contact-form-botcheck" name="quick-contact-form-botcheck" value="">
									<button type="submit" id="quick-contact-form-submit" name="quick-contact-form-submit" class="btn btn-danger nomargin" value="submit">Enviar</button>
							</form>
					</div>
				</div>
			</div>
		</div>
	<!-- footer-contenido -->
	</div>

	<!-- Copyrights -->
		<div id="copyrights">
			<div class="container clearfix">
				<div class="col_full nobottommargin center">
					Copyrights © 2016 Todos los derechos reservados.
				</div>
			</div>
		</div>
	<!-- #copyrights -->

</footer>
