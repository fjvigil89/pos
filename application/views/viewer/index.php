<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class="icon fa fa-eye"></i>
        <?php echo lang('module_viewers'); ?>
        <!--<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack888"></a>-->
        <?php 
			$extra="";
			$url_video_ver="#";
			if($this->Appconfig->es_franquicia()){
                $url_video=	$this->Appconfig->get_video("VIEWERS");
                if($url_video!=null){
                    $url_video_ver=$url_video;
                }else{
                    $extra=" style='display: none; '";
                }
            }
			$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
		    echo $a_video;				
		?>

    </h1>
</div>
<!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD -->
<!-- BEGIN PAGE BREADCRUMB -->
<div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
</div>
<!-- END PAGE BREADCRUMB -->

<div class="clear"></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN CONDENSED TABLE PORTLET-->
        <div class="portlet box green" id="portlet-content">
            <div class="portlet-title">
                <div class="caption">
                    <span class="icon">
                        <i class="fa fa-th"></i>
                    </span>
                    <?php echo lang('common_list_of')." Pedidos"; ?>

                </div>
                <div class="tools">
                    <span data-original-title="" class="label label-primary tip-left tooltips"></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="pull-right margin-bottom-10">
                            <div class="btn-group">
                                <?php   echo anchor("$controller_name/view_img/-1",
											'<i class="fa fa-download   hidden-lg fa fa-2x tip-bottom" data-original-title="Nueva imagen"></i><span class="visible-lg">Nueva imagen</span>',
											array('class'=>'btn btn-medium green-seagreen',
												'title'=>"Nueva imagen",
												'data-toggle'=>'modal',
												'data-target'=>'#myModal'));
										
                                        /*echo anchor("$controller_name/options_excel_export/",
											'<i class="fa fa-download   hidden-lg fa fa-2x tip-bottom" data-original-title="Configuración"></i><span class="visible-lg">Configuración</span>',
											array('class'=>'btn  btn-medium green-seagreen',
												'title'=>"Configuración",
												'data-toggle'=>'modal',
												'data-target'=>'#myModal'));*/
										
                                        echo anchor("all/viewer/$employee_id",
											'<i class="fa fa-eye   hidden-lg fa fa-eye tip-bottom" data-original-title="'.lang('module_viewers').'"></i><span class="visible-lg">'.lang('module_viewers').'</span>',
											array('class'=>'btn btn-medium red-haze',
												'title'=>lang('module_viewers'),
                                                'target'=>'_blank'));
                                        echo anchor("$controller_name/delete",
                                                '<i class="fa fa-trash-o hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_delete').'"></i><span class="visible-lg">'.lang("common_delete").'</span>',
                                                array('id'=>'delete', 
                                                    'class'=>'btn btn-danger disabled','title'=>lang("common_delete"))); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- aqui body-->
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_open_multipart('viewers/save_viewer',array('id'=>'viewers_form','class'=>'form-horizontal ')); ?>
                            <div class="icheck-inline">
                                <label>
                                    <input value="1" name="show_carrousel" <?=$this->config->item("show_carrousel") ? "checked":""?> onclick="checkbox(this)" type="checkbox" > Activar Carousel</label>
                                <label>
                                <label>
                                    <input value="1" name="show_viewer" <?=$this->config->item("show_viewer") ? "checked":""?> onclick="checkbox(this)" type="checkbox" > Activar Visor</label>
                                <label>
                                
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive" id="manage_table">
                            <?=$manage_table; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONDENSED TABLE PORTLET-->
    </div>
</div>
<script>
    enable_select_all();
    enable_checkboxes();
    enable_delete("Esta seguro que quiere eliminar la imagen?");

    function checkbox(element)
    {
       data = $("#viewers_form").serializeArray();
       url =  $("#viewers_form").attr('action');

       $.get( url ,data, function( data ) { });
    }
</script>

<?php $this->load->view("partial/footer"); ?>