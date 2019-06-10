<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-social-dribbble font-green hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Table Pagination</span>
                </div>

                <div class="actions">
                    <a class="btn green" href="<?php echo site_url().'/schedules' ?>">
                        Calendar
                        <i class="fa fa-calendar"></i>
                    </a>

                </div>
                <div class="actions">
                    <a class="btn purple" href="<?php echo site_url().'/schedules/addSchedules' ?>">
                        Add Schedule
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                

            </div>
            <div class="portlet-body">
                <div class="bootstrap-table">
                <!-- tabla que se muestra solo para agrega un cita -->
                    <table id="add" data-height="399" class="table table-hover"
                                style="margin-top: -10px;">
                        <thead>
                            <tr>
                                <th style="text-align: right;">
                                    <div class="th-inner  ">Title</div>

                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">start</div>

                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">End</div>

                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">color</div>

                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">status</div>
                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">Save</div>
                                </th>
                                <th style="text-align: center; ">
                                    <div class="th-inner  ">Cancel</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr role="row" class="add">
                                    <td class="sorting_1">
                                        <input id="add_title" type="text" class="form-control " value=""></td>
                                    <td>                                    
                                    <input id="add_start" type="text" class="form-control form-control-inline date-picker" value=""></td>
                                    <td><input id="add_end" type="text" class="form-control input" value=""></td>
                                    <td>
                                    
                                    <div class="input-group color colorpicker-default" data-color="#3865a8" data-color-format="rgba">
                                        <input type="text" class="form-control" value="#3865a8" readonly="">
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i style="background-color: #3865a8;"></i>&nbsp;</button>
                                        </span>
                                    </div>
                                    </td>
                                    <td>
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                                id="myonoffswitch">
                                            <label class="onoffswitch-label" for="myonoffswitch">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td><a class="edit" onCLick="addSchedule()">Save</a></td>
                                    <td><a class="cancel" onCLick="cancelSchedule()">Cancel</a></td>
                                </tr>
                            </tbody>
                    </table>
                <!-- tabla que se muestra con todas las citas -->
                    <div class="fixed-table-body">
                        <div class="fixed-table-loading" style="top: 41px; display: none;">Loading, please wait...
                        </div>
                        
                        <table class="table table-striped table-hover table-bordered dataTable no-footer"
                         id="sample_editable_1" role="grid" 
                         aria-describedby="sample_editable_1_info">

                            <thead>
                                <tr>
                                
                                    <th style="text-align: right;">
                                        <div class="th-inner  ">Title</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">start</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">End</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">color</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">status</div>
                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">Edit</div>
                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">Delete</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                <?php foreach ($schedule as $key => $value) { ?>
                                <tr data-index=<?php echo $key ?>>

                                    <td style="text-align: right; ">
                                        <?php echo $value['title']; ?>
                                    </td>
                                    <td style="text-align: center; ">
                                        <?php echo $value['start']; ?>
                                    </td>
                                    <td style="text-align: center; ">
                                        <?php echo $value['end']; ?>
                                    </td>
                                    <td style="text-align: center; ">
                                        <?php echo $value['color']; ?>
                                    </td>

                                    <td style="text-align: center; ">
                                        <?php if($value['status']==true) { ?>
                                        <div class="onoffswitch">
                                            <input type="hidden" id="status" value="<?php echo $value['status']; ?>">
                                            <input onClick="Habilitar('<?php echo $value['id']; ?>; ?>')"
                                                type="checkbox" name="onoffswitch" class="onoffswitch-checkbox"
                                                id="myonoffswitch<?php echo $key ?>" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch<?php echo $key ?>">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                        <?php  }; ?>
                                        <?php if($value['status']==false) { ?>
                                        <div class="onoffswitch">
                                            <input type="hidden" id="status" value="<?php echo $value['status']; ?>">
                                            <input onClick="Habilitar(<?php echo $value['id'];?>)" type="checkbox"
                                                name="onoffswitch" class="onoffswitch-checkbox"
                                                id="myonoffswitch<?php echo $key ?>">
                                            <label class="onoffswitch-label" for="myonoffswitch<?php echo $key ?>">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                        <?php  }; ?>
                                    </td>
                                    <td style="text-align: center; ">
                                        <a href="<?php echo site_url('schedules/editSchedule/'.$value['id']); ?>" class="btn btn-outline btn-circle btn-sm-purple">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                    </td>
                                    <td style="text-align: center; ">
                                        <a href="<?php echo site_url('schedules/setDelete/'.$value['id']); ?>"
                                            class="btn btn-outline btn-circle btn-sm-purple">
                                            <i class="fa fa-trash-o"></i>
                                            Delete
                                        </a>

                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="fixed-table-footer" style="display: none;">
                        <table>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="fixed-table-pagination" style="display: block;">

                    </div>

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#add').css('display', 'none');
});

function Habilitar(schedule_id, title, start, end, color) {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    var dataJson = {
        [csrfName]: csrfHash,
        id: schedule_id,
        update: 'true'
    };
    this.sendEnable(dataJson, 'schedules/setEnable');
}

function Delete(schedule_id) {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    var dataJson = {
        [csrfName]: csrfHash,
        id: schedule_id,
    };
    this.sendEnable(dataJson, 'setDelete');
}

function sendEnable(dataJson, metodo) {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length -
        pathName
        .length));

    //console.log(JSON.stringify(dataJson));

    $.ajax({
        url: url + metodo,
        data: dataJson,
        type: "POST",
        success: function(result) {
            console.log(result);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("No se ha podido obtener la informacion");
            console.log(xhr);
            console.log(thrownError);
        }
    });


}
</script>
<script type="text/javascript">

/**
 * metodo para crear los datos necesarios para actualizar el schedulo
 */
function addSchedule() {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    var title = $("#add_title").val();
    var start = $("#add_start").val();
    var end = $("#add_end").val();
    var color = $("#add_color").val();
    var status = $("#myonoffswitch").val();

    var dataJson = {
        [csrfName]: csrfHash,
        title: title,
        start: start,
        end: end,
        status: status,
        color: color,
        update: 'false'
    };
    //console.log(JSON.stringify(dataJson));
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
//alert(url + 'citas/setApiSchedule');
    $.ajax({
        url: url + 'schedules/setApiSchedule',
        data: dataJson,
        type: "POST",
        success: function(result) {
            //console.log(result);
            location.reload(true);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("No se ha podido obtener la informacion");
            console.log(xhr);
            console.log(thrownError);
        }
    });
}

function cancelSchedule(){
    $("#add_title").val('');
    $("#add_start").val('');
    $("#add_end").val('');
    $("#add_color").val('');
    $("#myonoffswitch").val('');

    $('#add').css('display', 'none');
}

function mostrarTr(){
    $('#add').css('display', 'block');
}
</script>


