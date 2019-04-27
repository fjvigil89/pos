<div class="modal-dialog  ">
	<div class="modal-content">
		<div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-info-circle"></i>
                    <span class="caption-subject bold">
                        <?php echo "Imagen" ?>
                    </span>
                </div>
                <div class="tools">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body">
            	<div class="row">
					<div class="col-sm-12 ">
					<center>
					
						<?php $avatar_url=$img_id ?  site_url('app_files/view_transfer/'.$img_id) :(base_url().'img/no-image.png');?>
						<a target="_blank" href="<?= $avatar_url?>">
							<img src="<?= $avatar_url?>" alt="Capture" class="img-rounded">
						</a>
					</center>
					</div>	
					
					<div class="   col-md-4 col-md-offset-5 margin-top-10 ">
						<button id="cancel-button" type="button" class="btn default">Cerrar</button>
					</div>				
					
				</div>


			</div>
		</div>
	</div>
</div>

<script>
	$('#cancel-button').click(function(e){
		$('.close').click();
	});

</script>


