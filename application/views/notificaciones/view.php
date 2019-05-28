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

                <div class="row ">

                    <?php if($exists) {
                        echo "<div class='text-center'><h1>Notificación no existe.</h1><div>";
                        
                    }else {?>
                    <div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
                  
                        <div class="thumbnail">
                            <?php if(  $notification->video != null or trim($notification->video) != ""){ ?>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <?=$notification->video?>
                                </div>
                            <?php } ?>
                            <div class="caption">
                                <h3><?=H($notification->title)?> - <span
                                        class="badge"><?=  date(get_date_format(), strtotime($notification->created))?></span>
                                </h3>
                                <div ALIGN="justify">
                                    <p><?=H($notification->description)?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>


                </div>
            </div>
        </div>
        <!-- END CONDENSED TABLE PORTLET-->
    </div>
</div>



<?php $this->load->view("partial/footer"); ?>