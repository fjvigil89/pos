<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class="icon fa fa-eye"></i>
        <?= lang('module_viewers'); ?>
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
                    <?= lang('module_viewers')." ".lang("viewer_and")." " .lang("common_settings")?>
                </div>
                <div class="tools">
                    <span data-original-title="" class="label label-primary tip-left tooltips"></span>
                </div>
            </div>
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                            <?php echo lang('module_viewers')." ".lang("viewer_and")." " .lang("viewer_carousel")?></a>
                    </li>
                    <li class="">
                        <a href="#tab_2" data-toggle="tab"
                            aria-expanded="false"><?=lang("viewer_price_consultant")?></a>
                    </li>

                </ul>
                <!--visir and carousel-->
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab_1">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="pull-right margin-bottom-10">
                                    <div class="btn-group">
                                        <?php   
                                        if ($this->Employee->has_module_action_permission($controller_name, 'add_update_img', $in_employee_info->person_id)) {
                                            echo anchor("$controller_name/view_img/-1",
                                                '<i class="fa fa-camera  hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang("common_new_img").'"></i><span class="visible-lg">'.lang("common_new_img").'</span>',
                                                array('class'=>'btn btn-medium green-seagreen',
                                                    'title'=>lang("common_new_img"),
                                                    'data-toggle'=>'modal',
                                                    'data-target'=>'#myModal'));
                                        }
                                        if ($this->Employee->has_module_action_permission($controller_name, 'config_viewer', $in_employee_info->person_id)) {
                                            echo anchor("$controller_name/config1",
                                                '<i class="fa fa-cog   hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang("common_settings").'"></i><span class="visible-lg">'.lang("common_settings").'</span>',
                                                array('class'=>'btn  btn-medium green-seagreen',
                                                    'title'=>lang("common_settings"),
                                                    'data-toggle'=>'modal',
                                                    'data-target'=>'#myModal'));
                                        }
                                        ?>

                                        <div class="btn-group">
                                            <button type="button" class="btn red-haze btn-md dropdown-toggle"
                                                data-toggle="dropdown" data-hover="dropdown" data-close-others="true"
                                                aria-expanded="false">
                                                <span class="hidden-sm hidden-xs">
                                                    <font style="vertical-align: inherit;">
                                                        <font style="vertical-align: inherit;"><?=lang('module_viewers')?>
                                                        </font>
                                                    </font>
                                                </span>
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="<?=site_url('all/viewer/'.$employee_id)?>">
                                                    
                                                        <font style="vertical-align: inherit;">
                                                            <font style="vertical-align: inherit;"><?=lang("viewer_open_tab")?>
                                                            </font>
                                                        </font>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?=site_url('all/viewer/'.$employee_id)?>" target="_blank">                                                      
                                                        <font style="vertical-align: inherit;">
                                                            <font style="vertical-align: inherit;"> <?=lang("viewer_open_another_tab")?>
                                                            </font>
                                                        </font>
                                                    </a>
                                                </li>                                                
                                            </ul>
                                        </div>
                                        <?php
                                       
                                        if ($this->Employee->has_module_action_permission($controller_name, 'add_update_img', $in_employee_info->person_id)) {
                                            echo anchor("$controller_name/delete",
                                                    '<i class="fa fa-trash-o hidden-lg fa fa-2x tip-bottom" data-original-title="'.lang('common_delete').'"></i><span class="visible-lg">'.lang("common_delete").'</span>',
                                                    array('id'=>'delete', 
                                                        'class'=>'btn btn-danger disabled','title'=>lang("common_delete"))); 
                                        }
                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- aqui body-->
                        <div class="row">
                            <?php if ($this->Employee->has_module_action_permission($controller_name, 'config_viewer', $in_employee_info->person_id)) {?>
                            <div class="col-md-12">
                                <?php echo form_open_multipart('viewers/save_viewer',array('id'=>'viewers_form','class'=>'form-horizontal ')); ?>
                                <div class="icheck-inline">
                                    <label>
                                        <input value="1" name="show_carrousel"
                                            <?=$this->config->item("show_carrousel") ? "checked":""?>
                                            onclick="checkbox(this)" type="checkbox">
                                        <?=lang("viewer_activate")." ".lang("viewer_carousel")?></label>
                                    <label>
                                        <label>
                                            <input value="1" name="show_viewer"
                                                <?=$this->config->item("show_viewer") ? "checked":""?>
                                                onclick="checkbox(this)" type="checkbox">
                                            <?=lang("viewer_activate")." ".lang("module_viewers")?></label>
                                        <label>
                                </div>
                                </form>
                            </div>
                            <?php }?>
                            <div class="col-md-12">
                                <div class="table-responsive" id="manage_table">
                                    <?=$manage_table; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--fin visir and carousel-->

                    <div class="tab-pane fade" id="tab_2">
                        <a href="<?=site_url("all/checker")?>" class="btn btn-lg btn-block default" title="Abrir">Abrir
                            Verificador</a>
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

function checkbox(element) {
    data = $("#viewers_form").serializeArray();
    url = $("#viewers_form").attr('action');

    $.get(url, data, function(data) {});
}
</script>

<?php $this->load->view("partial/footer"); ?>