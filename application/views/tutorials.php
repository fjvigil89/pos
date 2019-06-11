<?php
	$controller_name = $this->router->fetch_class();
	$hide_video = true;
	$tutorials = $this->Tutorial->get_tutorial($controller_name, $this->config->item("profile_id"));
 if(!empty($tutorials))
 {
	$hide_video = $this->Tutorial->is_hide_video($controller_name, $this->Employee->person_id_logged_in());
	//$previous = $this->Tutorial->previous($tutorial->module_id, $tutorial->tutorial_id);
	$tutorial = $tutorials[0];
	 ?>
<div data-width="500" tabindex="-1" class="modal fade" id="stack" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="padding-bottom: 40px">
            <div class="modal-header">
                <button type="button" id='close-modal' class="close" data-dismiss="modal" rel=0;
                    aria-hidden="true"></button>
                <div id="title-tutorial">
                    <?=$tutorial->title?>
                </div>


            </div>
            <div class="modal-body">
                <div id="video-tutorial">
                    <?=$tutorial->video?>
                </div>
                <p id="description-tutorial"><?=H($tutorial->description)?></p>
                <div class="md-checkbox"><input type="checkbox" name="scheckBoxStack" value="1" id="checkBoxStack"
                        class="md-check"
                        <?= $hide_video== true ? "checked":"";?> /><label
                        id="show_comment_on_receipt_label" for="checkBoxStack"><span></span><span
                            class="check"></span><span class="box"></span>No volver mostrar este video</label>
                </div>
                <label class="pull-right">
					<input type="hidden" id="index" value ="0">
                    <a class="btn <?= empty($tutorial->previous_module_id)? "hide":""?> yellow-crusta"
                        id="previous"
                        href="<?php echo site_url(empty($tutorial->previous_module_id) ? "": $tutorial->previous_module_id);?>">Atrás</a>
                    <a class="btn  <?= empty($tutorial->next_module_id)? "hide":""?> green-jungle" id="next"
                        href="<?php echo site_url(empty($tutorial->next_module_id) ? "": $tutorial->next_module_id);?>">Siguiente</a>

                </label>
            </div>
        </div>
    </div>

</div>
<script>
<?php if(!$hide_video){?>
	$('.modal.fade').addClass('in');
	$('#stack').css({
		'display': 'block'
	});
<?php  } ?>
</script>
<?php } ?>
<script>
$('.modal.fade.in').click(function(e) {

    if ($(e.target)[0].id == "stack") {
        $('.modal.fade.in').removeClass('in');
        $('#stack').css({
            'display': 'none'
        });
    }

});
$('#close-modal').click(function() {

    $('.modal.fade.in').removeClass('in');
    $('#stack').css({
        'display': 'none'
    });
    $('#modal-video-tutorial').removeClass('icon fa fa-youtube-play help_button');
    $('#modal-video-tutorial').html(
        "<a href='javascript:;' id='maxhom' rel=1 class='tn-group btn red-haze' ><span class='hidden-sm hidden-xs'>Maximizar&nbsp;</span><i class='icon fa fa-youtube-play help_button'></i></a>"
    );
});

$('#checkBoxStack').click(function(e) {
    $.post('<?php echo site_url("config/show_hide_video_help");?>', {
        "show_hide_video": $(this).is(':checked') ? '1' : '0',
        "module_id": '<?=$controller_name?>'
    });

});

<?php if(!empty($tutorials) and count($tutorials) > 1)  : ?>
	var tutorials = <?=json_encode($tutorials)?>;

	$('#next').click(function(event) {
		event.preventDefault();
		$('#next').addClass("hide");
		$('#previous').addClass("hide");
		var index = Number($("#index").val());
		if (index + 1 >= tutorials.length) {
			window.location = SITE_URL + "/" + tutorials[index]["next_module_id"];
		} else {
			if (tutorials[index + 1]["next_module_id"] != null && tutorials[index + 1]["next_module_id"] != "")
				$('#next').removeClass("hide");
			if (tutorials[index +1 ]["previous_module_id"] != null && tutorials[index +1 ]["previous_module_id"] != "")
				$('#previous').removeClass("hide");

			var video = tutorials[index + 1]["video"];
			$("#video-tutorial").html(video);
			$("#title-tutorial").html(tutorials[index + 1]["title"]);
			$('#index').val( index + 1);
			$("#description-tutorial").html(tutorials[index + 1]["description"]);

		}
		config();
	});

	$('#previous').click(function(event) {
		event.preventDefault();
		$('#previous').addClass("hide");
		$('#next').addClass("hide");
		var index =Number($("#index").val());
		if (index - 1 < 0) {
			window.location = SITE_URL + "/" + tutorials[0]["previous_module_id"];
		} else {
			if (tutorials[index - 1]["previous_module_id"] != null && tutorials[index - 1]["previous_module_id"] !="")
				$('#previous').removeClass("hide");
			if (tutorials[index]["next_module_id"] != null && tutorials[index]["next_module_id"] != "")
				$('#next').removeClass("hide");
			var video = tutorials[index - 1]["video"];
			$("#video-tutorial").html(video);
			$("#title-tutorial").html(tutorials[index - 1]["title"]);
			$('#index').val( index - 1);
			$("#description-tutorial").html(tutorials[index - 1]["description"]);

		}
		config();
	});
<?php endif; ?>

config();

function config() {
    $(".modal-body > div > iframe").width(560);
    $(".modal-body > div > iframe").height(315);
}
</script>