<div class="portlet light profile-sidebar-portlet bordered">
        <div class="portlet-title">
		<div class="text-center">
                    <h4 style="font-weight: 700;"><?php echo lang("technical_supports_customer"); ?></h4>
			<span class="caption-subject font-blue-madison bold uppercase "></span>
		</div>
	</div>
	 
	<div class="profile-userpic">
            <?php
            if($result->image_id !='') { ?>
		<img src="<?php echo site_url() ?>/app_files/view/<?php echo $result->image_id ?>"
			 class="img-responsive" alt="profile-photo" ></div>
            <?php }  else { ?>
            <img src="assets/template/images/perfil.JPG"  class="img-responsive" alt="photo" ></div>
                         <?php
           } ?>
	 
	<br>
	<div class="profile-usertitle text-center ">
		<div class="caption-subject bold "><?php echo $result->first_name . " ". $result->last_name  ?></div>
	</div> 
	<div class="profile-usermenu">
            <ul class="nav">
                <li>
                    <a href="javascript:void(0)"> <i class="fa fa-inbox"></i> <?php echo $result->email ?></a>
                </li>
                <li>
                    <a href="javascript:void(0)"> <i class="fa fa-mobile-phone"></i> <?php echo $result->phone_number ?></a>
                </li>
            </ul>
	</div>
	 
</div>
<?php   
foreach($Cliente->result() as $Cliente) {  ?>
<div class="portlet light profile-sidebar-portlet bordered">   
    <div class="text-center" style="height: auto;overflow: hidden;"> 
        <a href="javascript:void(0);" title="Pulse para ver historial del cliente" onclick="controler('<?php echo site_url() ?>/technical_supports/ver_historial_cliente_serv/','hc=<?php echo $Cliente->id_customer; ?>','ventanaVer','');"> 
            <span class="btn btn-primary col-sm-12"><span class="icon"><i class="fa fa-search"></i> Ver Historial</span></span> 
        </a>
    </div>   
</div>
<?php } ?>

<script type="text/javascript"> 
    $('#vista2').load("<?php echo site_url() ?>/technical_supports/getClientsInfo2/","hc=<?php echo $this->input->get('hc'); ?>&idSupport=<?php echo $this->input->get('idSupport'); ?>");     
</script>