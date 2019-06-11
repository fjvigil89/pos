<!DOCTYPE html>

<html>

<head>
    <?php
	if($this->Appconfig->es_franquicia()==true){
		$data_commpany = $this->Appconfig->get_data_commpany($this->config->item('resellers_id'));
	}
	$name_commpany = NAME_COMPANY;
	$arr_theme=array();
	$head_layout  = 'head';
	$theme_layout = 'layout';
	$theme_color  = 'default';
	$arr_theme['page-boxed']        = 'No';
	$arr_theme['navbar-fixed-top']  = ' navbar-fixed-top ';
	$arr_theme['page-footer-fixed'] = 'No';
	$arr_theme['dropdown-dark']     = ' dropdown-dark ';
	$arr_theme['Color']             = 'default';

	if($this->Appconfig->es_franquicia()==true){
	    
		$name_commpany = $data_commpany->short_name;
		$theme_commpany = $this->Appconfig->get_theme_commpany($this->config->item('resellers_id'));

		//if($this->Appconfig->is_theme_default($this->config->item('resellers_id'))===FALSE){
			foreach ($theme_commpany as $row) {
				$theme_layout = $row->layout;	        
				$arr_theme[$row->name_feature_css] = $row->css_class;
			}
			if(count($theme_commpany)!=0){
				$theme_color = $arr_theme['Color'];
				$head_layout = 'head_layout';
			}
		//}
	} 
	$html_title = $this->config->item('company').' -- '.lang('common_powered_by')." ".$name_commpany;
//print_r($arr_theme); die();
	$data = ['html_title' => $html_title,
			'theme_layout' => $theme_layout,
			'theme_color' => $theme_color];

	$this->load->view("partial/".$head_layout, $data);

	$this->load->view("tutorials"); 
	?>

    <script type="text/javascript">
    var SITE_URL = "<?php echo site_url(); ?>";
    var BASE_URL = '<?= base_url();?>';
    </script>

    <script type="text/javascript">
    COMMON_SUCCESS = <?php echo json_encode(lang('common_success')); ?>;
    COMMON_ERROR = <?php echo json_encode(lang('common_error')); ?>;
    $.ajaxSetup({
        cache: false,
        headers: {
            "cache-control": "no-cache"
        }
    });

    $(document).ready(function() {
        //Ajax submit current location
        $("#employee_current_location_id").change(function() {
            $("#form_set_employee_current_location_id").ajaxSubmit(function() {
                window.location.reload(true);
            });
        });

        //Keep session alive by sending a request every 5 minutes
        setInterval(function() {
            $.get('<?php echo site_url('home/keep_alive'); ?>');
        }, 300000);
    });
    </script><?php 
        if ($this->config->item('hide_support_chat')==0 && 
             $this->router->fetch_method()!="complete" and 
            ($this->router->fetch_class() != "technical_supports" )  and
            $this->router->fetch_method()!="receipt" and
            $this->router->fetch_method()!="receipt_comanda"  and $this->router->fetch_method()!="closeregister" 
            and $this->router->fetch_method()!="giftcard_print") {
				$id_sitio_api="56f2edbbd68ada7f0fa9bda5";
				if($this->Appconfig->es_franquicia()){
					$id_sitio_api=$data_commpany->script_chat;
				}
				else if($this->Employee-> es_demo()){
					$id_sitio_api="56f2ddc6d68ada7f0fa9b637";
				}
			//<!--Start of Tawk.to Script-->
			?><script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/<?php echo $id_sitio_api?>/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
    </script><?php 
			//<!--End of Tawk.to Script-->
		} 

		if($this->config->item('active_keyboard')==1){ 
			?><script type="text/javascript" src="<?php echo base_url();?>js/keyboard/keyboard_active.js"></script><?php 
		} 
		?>
</head>
<?php 
		$body_class = "";
		$sidebar_class = "";
		$sales_content_class = "";
		if ($this->router->fetch_class() == "sales" || $this->router->fetch_class() == "receivings" 
		 || $this->router->fetch_class() == "items" || ($this->router->fetch_class() == "changes_house" && $this->router->fetch_method()=="index")||
		 $this->router->fetch_class() == "technical_supports" || $this->router->fetch_class()=="warehouse" ) {
			$body_class = "page-sidebar-closed";
			$sidebar_class = "page-sidebar-menu-closed";
			$sales_content_class = "sales_content_minibar";
		}
		
	?>

<body class="page-md page-sidebar-closed-hide-logo page-container-bg-solid <?php 
	

	echo $body_class;

	if (isset($arr_theme['page-boxed']) AND $arr_theme['page-boxed'] == 'page-boxed') { 
	 	echo ' page-boxed ';
	} 

	if ($arr_theme['navbar-fixed-top'] == '') {
		echo ' page-header-fixed ';
	} else {
		if ($arr_theme['navbar-fixed-top'] == 'navbar-fixed-top') { 
		 	echo ' page-header-fixed ';
		} else {
			if ($arr_theme['page-boxed'] != 'page-boxed') { 
				//echo 'md-shadow-z-1-i';
			}
		}
	}

	if (isset($arr_theme['page-footer-fixed']) AND $arr_theme['page-footer-fixed'] == 'page-footer-fixed') { 
	 	echo ' page-footer-fixed ';
	} 
	?>" id="vContenido">
    <div class="modal fade" id="myModal" tabindex="-1" role="myModal" aria-hidden="true"></div>
    <!-- BEGIN HEADER -->
    <div class="page-header navbar <?php 
			if ($arr_theme['navbar-fixed-top'] == '') {
				echo ' navbar-fixed-top md-shadow-z-1-i ';
			} else {
				if ($arr_theme['navbar-fixed-top'] == 'navbar-fixed-top') { 
				 	echo ' navbar-fixed-top ';
				} else {
					if ($arr_theme['page-boxed'] != 'page-boxed') { 
						echo ' md-shadow-z-1-i ';
					}
				}
			}
			?>">
        <div id="ventanaVer" class="Ventana-modal"
            style="z-index: 200000;height: auto;overflow: hidden;position: absolute;"></div>
        <div class="page-header-inner <?php 
				if ($arr_theme['page-boxed'] == 'page-boxed') { 
				 	echo 'container';
				} 
				?>">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php echo site_url('home'); ?>">
                    <?php echo img(array(
		                    'src' => $this->Appconfig->get_logo_image(),
		                    'class'=>'logo-default',
							'id'=>'header-logo',
			            )); ?>
                </a>
                <div class="menu-toggler sidebar-toggler">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <div class="page-top-left">
                <div class="page-actions">
                    <!--License renewal-->
                    <?php if (  isset($_SESSION['days_left']) and !empty($_SESSION['days_left']) and ($_SESSION['days_left'] <= 5 or ( $this->config->item('license_type') == 'lifetime' and $_SESSION['days_left'] <= 15 )) ): ?>
                    <div class="btn-group">
                        <?php if ( $_SESSION['days_left'] <= 5 or ( $this->config->item('license_type') == 'lifetime' and $_SESSION['days_left'] <= 15 )): ?>
                        <a class="btn btn-danger" target="_blank"
                            href="<?php echo base_url();?>index.php/home/subscription_renewal_redirect">
                            <span
                                class="hidden-sm hidden-xs"><?php echo $_SESSION['days_left'].' '.lang("common_licence_about_to_expire");?>&nbsp;</span><i
                                class="fa fa-exclamation-triangle"></i>
                        </a>
                        <?php else:?>
                        <a class="btn btn-primary dropdown-toggle" target="_blank"
                            href="<?php echo base_url();?>index.php/home/subscription_renewal_redirect">
                            <span class="hidden-sm hidden-xs"><?php echo "Pago de Licencia";?>&nbsp;
                        </a>
                        <?php endif;?>
                    </div>
                    <?php endif;?>

                    <!-- Button tutorials -->
                    <?php
						if(!$this->Appconfig->es_franquicia()){
							$presentacio_url_video="";
							$configuracion_url_video="target='_blank' href='https://www.youtube.com/watch?v=0Z1avVwAqZE&feature=youtu.be'";
							$tiendas_url_video="target='_blank' href='https://www.youtube.com/watch?v=X7vpvIqo8Ww'";
							$proveedores_url_video="";
							$inventario_url_video="";
							$venta_url_video="";
							$reporte_url_video="";
							$servicio_tecnico_url_video="target='_blank' href='https://www.youtube.com/watch?v=SzJnORvT2vY&t=3s'";
						
						}

						?>
                    <div class="btn-group nav-bars">
                        <a href="javascript:;" class="btn-group btn red-haze" data-toggle="dropdown"
                            data-hover="dropdown" data-close-others="true">
                            <span class="hidden-sm hidden-xs"><?php echo lang("common_tutorials");?>&nbsp;</span><i
                                class="fa fa-life-ring"></i>
                        </a>
                        <ul class="dropdown-menu list_nav <?php 
									if (isset($arr_theme['dropdown-dark']) AND $arr_theme['dropdown-dark'] == 'dropdown-dark') { 
									 	echo ' dropdown-dark ';
									} ?>">
                            <li>
                                <a class="icon fa fa-cogs"><?php echo lang("common_basic_help");?></a>
                                <ul>
                                    <li><a onclick="ver_video(this)" data-video="PRESENTACIÓN"
                                            <?php  echo isset($presentacio_url_video)?$presentacio_url_video:"" ?>
                                            class="icon fa fa-dashboard">Presentación</a></li>
                                    <li><a onclick="ver_video(this)" data-video="CONFIGURACIÓN"
                                            <?php  echo  isset($configuracion_url_video)? $configuracion_url_video:""?>
                                            class="icon fa fa-cogs help_button">Configuración</a></li>
                                    <!--<a class="icon fa fa-cogs" data-toggle="modal"  data-target="#stack2">Configuración</a>-->
                            </li>
                            <li><a onclick="ver_video(this)" data-video="TIENDAS"
                                    <?php  echo isset($tiendas_url_video)? $tiendas_url_video:"" ?>
                                    class="icon fa fa-home help_button">Tienda</a></li>
                            <!--<a class="icon fa fa-home" data-toggle="modal"  data-target="#stack6">Tiendas</a>-->
                            </li>
                            <li><a onclick="ver_video(this)" data-video="PROVEEDORES"
                                    <?php  echo isset($proveedores_url_video)?$proveedores_url_video:""?>
                                    class="icon fa fa-download">Proveedores</a></li>
                            <li><a onclick="ver_video(this)" data-video="INVENTARIO"
                                    <?php  echo isset($inventario_url_video)?$inventario_url_video:""?>
                                    class="icon fa fa-table">Inventario</a></li>
                            <li><a onclick="ver_video(this)" data-video="VENTAS"
                                    <?php  echo isset($venta_url_video)?$venta_url_video:""?>
                                    class="icon fa fa-shopping-cart">Ventas</a></li>
                            <li><a onclick="ver_video(this)" data-video="SERVICIO TÉCNICO"
                                    <?php  echo isset($reporte_url_video)?$reporte_url_video:""?>
                                    class="icon fa fa-bar-chart-o">Reportes</a></li>
                            <li><a onclick="ver_video(this)" data-video="REPORTES"
                                    <?php  echo isset($servicio_tecnico_url_video)?$servicio_tecnico_url_video:""?>
                                    class="icon fa fa-wrench help_button">Servicio Técnico</a>
                                <!--<a class="icon fa fa-wrench" data-toggle="modal"  data-target="#stack7">Servicio Técnico</a>-->
                            </li>

                        </ul>

                        </li>
                        <li>
                            <a class="icon fa fa-cogs" data-toggle="modal" data-target="#stack7">
                                <?php echo lang("common_Intermediate_help");?>
                            </a>
                        </li>
                        <li>
                            <a class="icon fa fa-cogs" data-toggle="modal" data-target="#stack8">
                                <?php echo lang("common_advanced_help");?>
                            </a>
                        </li>
                        </ul>

                    </div>



                    <!-- Button Support -->

                    <div class="btn-group pull-right">
                        <?php 
							$url_apk="https://play.google.com/store/apps/details?id=software.posclub&hl=es_CO";
						
							/*if($this->Appconfig->es_franquicia()==true){
								$url_apk=$data_commpany->url_apk;
							 }*/
							
							?>
                        <a class="btn green" target="_blank" href="<?php echo $url_apk ?>">
                            <span class="hidden-sm hidden-xs">APP&nbsp;</span><i class="fa fa-android"></i>
                        </a>
                    </div>
                    <div class="btn-group pull-right">
                        <?php 
							$numero_wsp="573175791505";
						
							if($this->Appconfig->es_franquicia()==true){
								$numero_wsp= str_replace(array(" ","+"),"",$data_commpany->whatsapp) ;
							 }
							 $api_wsp="https://api.whatsapp.com/send?phone=".$numero_wsp."&text=Hola%20y%20bienvenido%20al%20sistema%20de%20soporte,%20dale%20click%20en%20enviar%20para%20comunicarte";
							?>
                        <a class="btn green-jungle" target="_blank" href="<?php echo $api_wsp ?>">
                            <span class="hidden-sm hidden-xs"><?php echo lang("common_support");?>&nbsp;</span><i
                                class="fa fa-whatsapp"></i>
                        </a>
                    </div>


                    <!-- Button offline -->
                    <?php if($this->Appconfig->is_offline_sales()):?>
                    <div class="btn-group pull-right">
                        <button class="btn green" id="btn-offline" style="display:none;">
                            <span class="hidden-sm hidden-xs"><?php echo "Offline";?>&nbsp;</span><i id="icon-offline"
                                class="fa fa-plug"></i>
                        </button>
                    </div>
                    <?php endif;?>
                    <!-- Dropdown Locations -->
                    <div class="btn-group">
                        <?php if (count($authenticated_locations) > 1) { ?>
                        <?php echo form_open('home/set_employee_current_location_id', array('id' => 'form_set_employee_current_location_id')) ?>
                        <?php echo form_dropdown('employee_current_location_id', $authenticated_locations,$this->Employee->get_logged_in_employee_current_location_id(),'id="employee_current_location_id" class="bs-select form-control"'); ?>
                        <?php echo form_close(); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- BEGIN PAGE TOP -->
            <div class="page-top">
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="flag-custom">
                            <h3 id="">
                                <?php 
										switch ($this->Appconfig->get_raw_language_value()) 
										{
											case 'spanish_argentina':
										        echo "<span class='flag-icon flag-icon-ar' title='Argentina'></span>";
										        break;
										    case 'spanish_bolivia':
										        echo "<span class='flag-icon flag-icon-bo' title='Bolivia'></span>";
										        break;
									        case 'portugues_brasil':
										        echo "<span class='flag-icon flag-icon-br' title='Brasil'></span>";
										        break;		
									        case 'english_canada':
										        echo "<span class='flag-icon flag-icon-ca' title='Canada'></span>";
										        break;									    
										    case 'spanish_chile':
										        echo "<span class='flag-icon flag-icon-cl' title='Chile'></span>";
										        break;	
									        case 'spanish':
										        echo "<span class='flag-icon flag-icon-co' title='Colombia'></span>";
										        break;	
										    case 'spanish_costarica':
										        echo "<span class='flag-icon flag-icon-cr' title='Costa Rica'></span>";
										        break;										        
										    case 'spanish_ecuador':
										        echo "<span class='flag-icon flag-icon-ec' title='Ecuador'></span>";
										        break;	
									        case 'spanish_elsalvador':
										        echo "<span class='flag-icon flag-icon-sv' title='El Salvador'></span>";
										        break;	
									        case 'spanish_spain':
										        echo "<span class='flag-icon flag-icon-es' title='España'></span>";
										        break;
									        case 'spanish_guatemala':
										        echo "<span class='flag-icon flag-icon-gt' title='Guatemala'></span>";
										        break;		
									        case 'spanish_honduras':
										        echo "<span class='flag-icon flag-icon-hn' title='Honduras'></span>";
										        break;		
									        case 'spanish_mexico':
										        echo "<span class='flag-icon flag-icon-mx' title='México'></span>";
										        break;	
									        case 'spanish_nicaragua':
										        echo "<span class='flag-icon flag-icon-ni' title='Nicaragua'></span>";
										        break;		
									        case 'spanish_panama':
										        echo "<span class='flag-icon flag-icon-pa' title='Panamá'></span>";
										        break;
									        case 'spanish_paraguay':
										        echo "<span class='flag-icon flag-icon-py' title='Paraguay'></span>";
										        break;	
									        case 'spanish_peru':
										        echo "<span class='flag-icon flag-icon-pe' title='Perú'></span>";
										        break;	
									        case 'spanish_puertorico':
										        echo "<span class='flag-icon flag-icon-pr' title='Puerto Rico'></span>";
										        break;	
									        case 'spanish_republicadominicana':
										        echo "<span class='flag-icon flag-icon-do' title='República Dominicana'></span>";
										        break;
									        case 'english':
										        echo "<span class='flag-icon flag-icon-us' title='USA'></span>";
										        break;	
									        case 'spanish_uruguay':
										        echo "<span class='flag-icon flag-icon-uy' title='Uruguay'></span>";
										        break;	
								        	case 'spanish_venezuela':
										        echo "<span class='flag-icon flag-icon-ve' title='Venezuela'></span>";
										        break;				      
										}
									?>
                            </h3>
                        </li>

                        <li class="info-time">
                            <h6 id="time">
                                <i class="icon fa fa-clock-o"></i>
                                <span class="text">
                                    <?php echo date(get_time_format()); ?>
                                    <?php echo date(get_date_format()) ?>
                                </span>
                            </h6>
                        </li>

                        <li class="separator hide">
                        </li>

                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <li class="dropdown dropdown-user <?php 
								if (isset($arr_theme['dropdown-dark']) AND $arr_theme['dropdown-dark'] == 'dropdown-dark') { 
								 	echo ' dropdown-dark ';
								} 
								?>">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                                data-close-others="true">
                                <span class="username username-hide-on-mobile">
                                    <?php echo "$user_info->first_name $user_info->last_name"; ?>
                                </span>
                                <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                                <img alt="" class="img-circle"
                                    src="<?php echo (isset($user_info->image_id)) ? site_url("app_files/view/$user_info->image_id") : base_url("img/avatar.jpg"); ?>" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a
                                        href="<?php echo base_url();?>index.php/employees/view/<?php echo $this->Employee->get_logged_in_employee_info()->person_id;?>">
                                        <i class="icon-user"></i> Editar perfil </a>
                                </li>
                                <?php if ($this->Employee->has_module_permission('config', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
                                <li>
                                    <?php echo anchor("config",'<i class="icon-settings"></i> '.lang("common_settings")); ?>
                                </li>
                                <?php } ?>

                                <!--
                                    <li>
										<a href="<?php //echo site_url('login/switch_user')?>" data-toggle="modal" data-target="#myModal" >
											<i class="icon-directions"></i> <?php //echo lang('common_switch_user'); ?>
										</a>										
									</li>
                                    -->

                                <li class="divider">
                                </li>
                                <li>
                                    <?php
											if ($this->config->item('track_cash') && $this->session->userdata("cash")==true) {
												echo anchor("sales/closeregister?continue=logout",'<i class="fa fa-power-off"></i>'.lang("common_logout"));
											} else {
												echo anchor("home/logout",'<i class="fa fa-power-off"></i>'.lang("common_logout"));
											}
										?>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
        </div>
    </div>
    <!-- END HEADER -->

    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <?php 
		if ($arr_theme['page-boxed'] == 'page-boxed') { 
		 	echo '<div class="container">';
		} 
		?>
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <ul class="page-sidebar-menu <?php 
					echo $sidebar_class;
					if ($arr_theme['navbar-fixed-top'] == '') {
						echo ' page-header-fixed';
					} else {
						if ($arr_theme['navbar-fixed-top'] == 'navbar-fixed-top') { 
						 	echo ' page-header-fixed';
						} else {
							if ($arr_theme['page-boxed'] != 'page-boxed') { 
								//echo 'md-shadow-z-1-i';
							}
						}
					}
					?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

                    <?php /*if (count($authenticated_locations) > 1) { ?>
                    <li id="">
                        <?php echo form_open('home/set_employee_current_location_id', array('id' => 'form_set_employee_current_location_id')) ?>
                        <?php echo form_dropdown('employee_current_location_id', $authenticated_locations,$this->Employee->get_logged_in_employee_current_location_id(),'id="employee_current_location_id" class="bs-select form-control"'); ?>
                        <?php echo form_close(); ?>
                    </li>
                    <?php } */?>

                    <li <?php echo $this->uri->segment(1)=='home'  ? 'class="active"' : ''; ?>>
                        <a href="<?php echo site_url('home'); ?>">
                            <i class="icon fa fa-dashboard"></i>
                            <span class="title"><?php echo lang('common_dashboard'); ?></span>
                            <?php echo $this->uri->segment(1)=='home'  ? '<span class="selected"></span>' : ''; ?>
                        </a>
                    </li>
                    <?php
                        
                            $sorted_modules = $this->Module->apend_translated_module_names($allowed_modules);
                            array_sort_by_column($sorted_modules,'translated_module_name');
                            foreach($sorted_modules as $module) { 
                            
                        ?>
                    <li <?php echo $module['module_id']==$this->uri->segment(1)  ? 'class="active"' : ''; ?>>
                        <a href="<?php echo site_url($module['module_id']);?>">
                            <i class="fa fa-<?php echo $module['icon']; ?>"></i>
                            <span class="title"><?php echo lang("module_".$module['module_id']) ?></span>
                            <?php echo $module['module_id']==$this->uri->segment(1)  ? '<span class="selected"></span>' : ''; ?>
                        </a>
                    </li>
                    <?php } ?>
                    <li>
                        <?php
								if ($this->config->item('track_cash') && $this->session->userdata("cash")==true) {
									echo anchor("sales/closeregister?continue=logout",'<i class="fa fa-power-off"></i><span class="title">'.lang("common_logout").'</span>');
								} else {
									echo anchor("home/logout",'<i class="fa fa-power-off"></i><span class="title">'.lang("common_logout").'</span>');
								}
							?>
                    </li>
                </ul>
                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <!-- END SIDEBAR -->
        <script>
        function ver_video(elemento) {
            <?php if($this->Appconfig->es_franquicia()){?>
            let url_sitio = '<?php echo site_url("Config/get_url_video/");?>/' + elemento.getAttribute("data-video");
            $.post(url_sitio, {}, function(url) {
                url = JSON.parse(url);
                if (url != null) {
                    window.open(url);
                }
            });
            return false;
            <?php }?>
            return true;
        }



        </script>
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content" id="content-page">
                <div class="page-head">
                    <!-- PAGE TOOLBAR -->