<?php
if($this->input->get('bas')=='') { ?>
    <div class="panel col-lg-12" style="padding: 0px;"> 
        <?php
        $resultBusq=$ListarBusquedaT->num_rows(); 
         foreach ($ListarBusqueda->result() as $ListarBusqueda) {
             ?>
            <div class="col-lg-12" style="border-bottom: 1px solid #DDDDDD;padding: 7px 0 10px 0;height: auto;overflow: hidden;">
            <div  style="padding: 7px;width: 80%;float: left;">        
                <b><?php echo $ListarBusqueda-> first_name." ".$ListarBusqueda-> last_name; ?></b><br>
                <?php echo $ListarBusqueda-> marca." ! ".$ListarBusqueda-> type_team." ! ".$ListarBusqueda-> model." ! ".$ListarBusqueda-> color; ?>         
            </div>
                <div  style="float: left;">
                <a class="btn btn-success" href="javascript:void(0);" title="<?php echo lang("technical_supports_title_detalle"); ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/detalles_serv_tecnico/','hc=<?php echo $ListarBusqueda->Id_support; ?>','ventanaVer','');"> 
                        <i class="fa fa-search"></i>
                </a>
                </div>
            </div>
            <?php
         } ?>
        <div class="col-lg-12 text-right" style="border-top: 1px solid #DDDDDD;background: #EEEEEE;padding: 7px 10px 10px 0;">
            <?php echo lang("technical_supports_titu_total").": ".$resultBusq; ?>
        </div>
    </div>
    <?php
}  
if($this->input->get('bas')=='1') { ?>
    <div class="panel col-lg-12" style="padding: 0px;"> 
        <?php 
         foreach ($ListarBusqueda->result() as $ListarBusqueda) {
        // foreach ($suggestions as $value) {
             ?>
            <div class="col-lg-12" style="border-bottom: 1px solid #DDDDDD;padding: 7px 0 10px 0;height: auto;overflow: hidden;">
               <!-- <a href="javascript:void(0);" title="Ver registro" onclick="controler('<?php echo site_url() ?>/technical_supports/getClientsInfo/','hc=<?php echo $value['value'] ?>','vista',$('#resultado').html(''));"> -->
                <a href="javascript:void(0);" title="<?php echo lang("technical_supports_title_detalle"); ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/getClientsInfo/','hc=<?php echo $ListarBusqueda->id_customer; ?>','vista',$('#resultado').html(''));"> 
                    <div  class="col-lg-12">    
                        <!--<b><?php// echo $value["label"] ?></b>  -->  
                        <b><?php echo  $ListarBusqueda->first_name." ". $ListarBusqueda->last_name ?></b>     
                    </div>
                </a> 
            </div>
            <?php
         } ?> 
    </div>
    <?php
}  
if($this->input->get('t')=='1') { ?>
    <div class="panel col-lg-12" style="padding: 0px;"> 
        <?php 
         foreach ($ListarBusqueda->result() as $ListarBusqueda) {
             ?>
            <div class="col-lg-12" style="border-bottom: 1px solid #DDDDDD;padding: 7px 0 10px 0;height: auto;overflow: hidden;">
                <a href="javascript:void(0);" title="<?php echo lang("technical_supports_title_detalle"); ?>" onclick="controler('<?php echo site_url() ?>/technical_supports/repair/','hc=<?php echo $ListarBusqueda->Id_support; ?>','vContenido','');"> 
                    <div  class="col-lg-12">    
                        <b style="color: #333333;"><?php echo $ListarBusqueda-> first_name." ".$ListarBusqueda-> last_name; ?></b><br>
                        <?php echo $ListarBusqueda-> marca." ! ".$ListarBusqueda-> type_team." ! ".$ListarBusqueda-> model; ?>        
                    </div>
                </a> 
            </div>
            <?php
         } ?> 
    </div>
    <?php
} ?>