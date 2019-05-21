<?php $this->load->view("partial/header"); ?>
<!-- BEGIN PAGE TITLE -->
<div class="page-title">
    <h1>
        <i class="icon fa fa-paper-plane-o"></i>
        Migración


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
    <?php echo form_open_multipart('migrations/update',array('id'=>'migration','class'=>'form-horizontal ')); ?>
        <input type="hidden" name="sent" value="1">
    </form>

        <script>
        function go_home() {
            window.location = '<?php echo site_url();?>';
        }

        $(document).ready(function() {

            var count = 0,
                error = false,
                udated = false,
                btn = "<br><br><button onclick='go_home()' class='btn'>Intentar de nuevo</button>",
                progress = $('<div id="sample-02b-progress"></div>'),
                msj_error =
                "Ocurrio un error en la actualización.<br> Intente nuevamente, y si el error persiste Comuniquese econ soporte" ;


            function countDown() {
                if (!udated)
                {
                    progress.html('<center><span> Actualizando a la version: <?=APPLICATION_VERSION?> ' +
                        '<br>' +
                        '         Loading: ' + count + ' segundo </span></center>');
                }
                if (!error) {
                    setTimeout(countDown, 1000);
                    count++
                }
                if (error || count > 40) {
                    progress.html('<center><span>' + msj_error + btn + '</span></center>');
                }
            }
            $('body').plainOverlay('show', {
                progress: function() {
                    return progress;
                }
            });
            setTimeout(countDown, 1000);


            $('.page-container').plainOverlay('show', {
                duration: 1000
            });

            $("#sample-02b-progress").css({
                'position': 'absolute',
                'color': 'white',
                'z-index': '9001',
                'cursor': 'wait',
                'font-size': '14px',
                'font': 'Arial',
            });

            $.ajax({
                method: "post",
                url: '<?php echo site_url("migrations/update");?>',
                data: $("#migration").serializeArray(),
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data);

                    if (data.success == 1) {
                        udated = true;
                        $('.page-container').plainOverlay('hide');
                        $(".update_version").text('');
                        progress.html(
                            '<center><span>Sistema actualizado a la última versión...</span></center>'
                            );
                        go_home();

                    } else if(data.success == 0) {
                        error = true;
                        console.log(data.message);

                    }
                }
            });
        });
        </script>
    </div>

</div>

<?php $this->load->view("partial/footer"); ?>