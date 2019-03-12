<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="display:block;overflow-y: auto">
    <div class="modal-dialog modal-lg" style="width: 96%">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header"
                style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button" title="Cerrar la ventana" class="btn btn-danger"
                    style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;"
                    data-dismiss="modal"
                    onclick="controler('<?php echo site_url() ?>/technical_supports/index/','quien=1','contTabla',$('#ventanaVer').html(''));">
                    <span aria-hidden="true">X</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><?php echo lang("technical_supports_orden_titu"); ?></h4>
                <small class="font-bold"><?php echo lang("technical_supports_orden_titu_menj"); ?></small>
            </div>
           <?= $this->load->View("technical_supports/carrito/carrito_cuerpo"); ?>
        </div>
    </div>
</div>

