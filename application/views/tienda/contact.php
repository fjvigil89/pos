<section id="google-map" class="gmap"">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.392407132344!2d-62.655312885628106!3d8.363003201367283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8dcbf03bab7f5863%3A0xfb0ce1580b4575eb!2sResidencias+Orinoco%2C+Av.+Antonio+de+Berrio%2C+Ciudad+Guayana+8051%2C+Bol%C3%ADvar!5e0!3m2!1ses-419!2sve!4v1467126287252" width="100%" height="500px" frameborder="0" style="border:0" allowfullscreen></iframe>
</section>

<section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="postcontent nobottommargin">

                    <h3>Enviar correo</h3>
                    <div class="contact-widget">
                        <div class="contact-form-result"></div>
                        <?php echo form_open('include/sendemail.php',['class'=>'nobottommargin', 'id'=>'template-contactform', 'name'=>'template-contactform', 'method'=>'POST']);?>
                            <div class="form-process"></div>

                            <div class="col_one_third">
                                <label for="template-contactform-name">Nombre<small>*</small></label>
                                <input type="text" id="template-contactform-name" name="template-contactform-name" value="" class="sm-form-control required" />
                            </div>

                            <div class="col_one_third">
                                <label for="template-contactform-email">Email <small>*</small></label>
                                <input type="email" id="template-contactform-email" name="template-contactform-email" value="" class="required email sm-form-control" />
                            </div>

                            <div class="col_one_third col_last">
                                <label for="template-contactform-phone">Telefono</label>
                                <input type="text" id="template-contactform-phone" name="template-contactform-phone" value="" class="sm-form-control" />
                            </div>

                            <div class="clear"></div>

                            <div class="col_two_third">
                                <label for="template-contactform-subject">Asunto <small>*</small></label>
                                <input type="text" id="template-contactform-subject" name="template-contactform-subject" value="" class="required sm-form-control" />
                            </div>

                            <div class="clear"></div>

                            <div class="col_full">
                                <label for="template-contactform-message">Mensaje <small>*</small></label>
                                <textarea class="required sm-form-control" id="template-contactform-message" name="template-contactform-message" rows="6" cols="30"></textarea>
                            </div>

                            <div class="col_full hidden">
                                <input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
                            </div>

                            <div class="col_full">

                                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                <div class="g-recaptcha" data-sitekey="your-recaptcha-site-key"></div>

                            </div>

                            <div class="col_full">
                                <button class="button button-3d nomargin" type="submit" id="template-contactform-submit" name="template-contactform-submit" value="submit">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="sidebar col_last nobottommargin">
                    <address>
                        <strong>Oficinas:</strong><br>
                        Ave Antonio de Berrios, Cll 24<br>
                        San Felix, CA 94107<br>
                    </address>
                    <abbr title="Phone Number"><strong>Telefono:</strong></abbr> (91) 8547 632521<br>
                    <abbr title="Fax"><strong>Fax:</strong></abbr> (91) 11 4752 1433<br>
                    <abbr title="Email Address"><strong>Email:</strong></abbr> ejemplo@email.com

                    <div class="widget noborder notoppadding">
                        <div class="fslider customjs testimonial twitter-scroll twitter-feed" data-username="envato" data-count="3" data-animation="fade" data-arrows="false">
                            <i class="i-plain i-small color icon-twitter nobottommargin" style="margin-right: 15px;"></i>
                            <div class="flexslider" style="width: auto;">
                                <div class="slider-wrap">
                                    <div class="slide"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget noborder notoppadding">
                        <a href="#" class="social-icon si-small si-dark si-facebook">
                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a>

                        <a href="#" class="social-icon si-small si-dark si-twitter">
                            <i class="icon-twitter"></i>
                            <i class="icon-twitter"></i>
                        </a>

                        <a href="#" class="social-icon si-small si-dark si-gplus">
                            <i class="icon-gplus"></i>
                            <i class="icon-gplus"></i>
                        </a>

                        <a href="#" class="social-icon si-small si-dark si-pinterest">
                            <i class="icon-pinterest"></i>
                            <i class="icon-pinterest"></i>
                        </a>

                        <a href="#" class="social-icon si-small si-dark si-skype">
                            <i class="icon-skype"></i>
                            <i class="icon-skype"></i>
                        </a>

                        <a href="#" class="social-icon si-small si-dark si-wordpress">
                            <i class="icon-wordpress"></i>
                            <i class="icon-wordpress"></i>
                        </a>

                    </div>
                </div>
        </div>
    </div>
</section>