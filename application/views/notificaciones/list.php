<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class="icon icon-bell "></i>
        <?= lang('module_notifications'); ?>
        <!--<a class="icon fa fa-youtube-play help_button" id='maxitems' data-toggle="modal" data-target="#stack888"></a>-->
        <?php 
			$extra="";
			$url_video_ver="#";
			if($this->Appconfig->es_franquicia()){
                $url_video=	$this->Appconfig->get_video("NOTIFICATIONS");
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
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-edit font-dark"></i>
                    <span
                        class="caption-subject font-dark bold uppercase"><?=lang("common_list_of")." ".lang("module_notifications");?></span>
                </div>
                <div class="actions">

                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"
                        data-original-title="" title=""> </a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <?php foreach ($notifications as $notification){ ?>

                    <div class="col-md-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-md-3">
                                    <?php if($notification->img == null)
                                            echo '<img src="'.base_url().'img/actualizar.png" class="img-thumbnail" alt=""> ';
                                        else
                                            echo '<img  class="img-thumbnail" src="'.site_url("app_files/view_notification/".$notification->id).'"> ';
                                    ?>
                                </div>
                                <div class="col-md-9">
                                    <h4 class="block"><strong><?= H($notification->title)?></strong>- <span
                                            class="badge"><?=  date(get_date_format(), strtotime($notification->created))?></span>
                                        <?php 
                                            if($notification->is_saw == 1)
                                                echo '<span title="Vista"> <i style="color:#01CD51;" class=" fa fa-check"></i>  </span>';
                                        ?>
                                    </h4>
                                    <div ALIGN="justify">
                                        <p><?= H($notification->description) . strlen($notification->description)?></p>
                                    </div>
                                    <ul class="pager">
                                        <li class="next">
                                            <a href="<?=site_url('notifications/view/'.$notification->id)?>"> Ver </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php   } ?>
                </div>

            </div>
        </div>
        <!-- END PORTLET-->

    </div>
</div>



<?php $this->load->view("partial/footer"); ?>