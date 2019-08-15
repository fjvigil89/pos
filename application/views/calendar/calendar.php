<div class="row">
    <div class="col-md-12">
        <?php  echo form_open('schedule',array('id'=>'schedule_form'));
		?>
        <!-- BEGIN CONDENSED TABLE PORTLET-->
        <div class="portlet box green" id="portlet-content">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers "></i>
                    <span class="caption-subject sbold uppercase"><?php echo lang('schedules_title_calendar');?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <!-- BEGIN DRAGGABLE EVENTS PORTLET-->
                        <h3 class="event-form-title margin-bottom-20"><?php echo lang('schedules_event');?></h3>
                        <div id="external-events">
                            <div class="actions">
                                <a class="btn purple  btn-default" href="<?php echo site_url().'/schedules/listar' ?>">
                                    Schedule
                                    <i class="icon-cloud-upload"></i>
                                </a>

                            </div>
                            <hr class="visible-xs">
                            <div class="actions">
                                <a class="btn blue" href="<?php echo site_url().'/schedules/addSchedules' ?>">
                                    Add Schedule
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <hr class="visible-xs">
                             <div class="actions">
                                <a class="btn red" href="<?php print $loginUrl ?>">
                                    Sync Google Calendar
                                    <i class="fa fa-exchange"></i>
                                </a>
                            </div>
                            <hr class="visible-xs">
                        </div>
                        <!-- END DRAGGABLE EVENTS PORTLET-->
                    </div>
                    <div class="col-md-9 col-sm-12">
                        <div id="calendar" class="has-toolbar fc fc-ltr fc-unthemed">
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <?php echo form_close();?>
    </div>

    <script type="text/javascript">
    
    /**
     * metodo para crear los datos necesarios para actualizar el schedulo
     */
    function addSchedule() {

        var e = $("#event_title").val();
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

        var titles = 0 === e.length ? "Untitled Event" : e;
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        var h = date.getHours(),
            min = date.getMinutes(),
            s = date.getSeconds();
        var starts = y + '-' + m + '-' + d + ' ' + h + ':' + min + ':' + s;
        var ends = y + '-' + m + '-' + d + ' ' + h + ':' + min + ':' + s;
        $("#event_start").val(starts);
        $("#event_end").val(ends);
        var dataJson = {
            [csrfName]: csrfHash,
            title: titles,
            start: starts,
            end: ends,
            status: 0,
            color: 'blue',
            update: 'false'
        };
        this.sendAdd(dataJson);

    };
    /**
     * funcion para enviar mediante ajax la peticion para guardar o actualizar
     * los schedules
     */
    function sendAdd(dataJson) {
        var loc = window.location;
        var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
        var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length -
            pathName
            .length));
        $.ajax({
            url: url + 'setApiSchedule',
            data: dataJson,
            type: "POST",
            success: function(result) {
                //console.log(result);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log("No se ha podido obtener la informacion");
                console.log(xhr);
                console.log(thrownError);
            }
        });
    }

    function Facturar(id){
        var loc = window.location;
        var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
        var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length -
            pathName
            .length));
        window.location= url + 'schedules/facturar/'+id;        
    }
    </script>