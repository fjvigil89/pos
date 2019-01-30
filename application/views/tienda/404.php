<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
                <div class="col_half nobottommargin">
                    <div class="error404 center">404</div>
                </div>
            <div class="col_half nobottommargin col_last">

                    <div class="heading-block nobottomborder">
                        <h4>Ooopps! La página que estabas buscando, no se pudo encontrar.</h4>
                        <span>Intenta buscar otro termino o navegar por las secciones del menu.</span>
                    </div>
                    <?php echo form_open('#',['method'=>'GET', 'role'=>'form', 'class'=>'nobottommargin']);?>
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" placeholder="Buscar en la pagina...">
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button">Buscar</button>
                            </span>
                        </div>
                </form>

            </div>
        </div>
    </div>
</section>