<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-social-dribbble font-green hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo lang('schedules_list'); ?></span>
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
                                        <div class="th-inner  ">Start</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">End</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">Color</div>

                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">Status</div>
                                    </th>
                                    <th style="text-align: center; ">
                                        <div class="th-inner  ">Facturar</div>
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
                                   
                                <tr data-index=<?php echo $key ?> >

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
                                            <div  class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-mini bootstrap-switch-id-test bootstrap-switch-animate" style="width: 64px;">
                                                <div onClick="Habilitar(<?php echo $value['id'];?>)" class="bootstrap-switch-container" style="width: 93px; margin-left: 0px;">                                        
                                                    <input  type="checkbox" checked="" class="make-switch" id="status" data-size="mini">
                                                </div>
                                            </div>
                                        <?php  }; ?>
                                        <?php if($value['status']==false) { ?>
                                            <div  class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-mini bootstrap-switch-id-test bootstrap-switch-animate" style="width: 64px;">
                                                <div onClick="Habilitar(<?php echo $value['id'];?>)" class="bootstrap-switch-container" style="width: 93px; margin-left: 0px;">                                        
                                                    <input  type="checkbox"  class="make-switch" id="status" data-size="mini">
                                                </div>
                                            </div>
                                        <?php  }; ?>
                                        
                                    </td>
                                    <td style="text-align: center; ">
                                        <a href="<?php echo site_url('schedules/facturar/'.$value['id']); ?>" class="btn btn-outline btn-circle btn-sm-purple">
                                            <i class="fa icon-credit-card"></i>
                                            Facturar
                                        </a>
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


function Habilitar(schedule_id, title, start, end, color) {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    var dataJson = {
        [csrfName]: csrfHash,
        id: schedule_id,
        update: 'true'
    };
    
    this.sendEnable(dataJson, 'setEnable');
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


function cancelSchedule(){
    $("#add_title").val('');
    $("#add_start").val('');
    $("#add_end").val('');
    $("#add_color").val('');
    $("#myonoffswitch").val('');

    $('#add').css('display', 'none');
}


</script>


