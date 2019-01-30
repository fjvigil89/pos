<div class="modal Ventana-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display:block;overflow-y: auto;">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header" style="height: 90px;background: url('assets/template/images/bannerventana.png') center right no-repeat;background-size: cover;">
                <button type="button"  class="btn btn-danger"  style="background: #FFFFFF;color: #000000;padding: 5px 7px 5px 7px;float: right;font-weight: 800;" data-dismiss="modal" onclick="controler('<?php echo site_url() ?>/config/tservicios/','1','listarFiador',$('#ventanaVer').html(''));">
                    <span aria-hidden="true">X</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><?php echo lang("technical_supports_customer_serv_tec_titu"); ?></h4>
                <small class="font-bold"><?php echo lang("technical_supports_customer_serv_tec_titu_menj"); ?></small>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content panel">

                            <div class="col-sm-3">
                                <div class="portlet light profile-sidebar-portlet bordered">
                                    <div class="portlet-title">
                                        <div class="text-center">
                                            <h4><?php echo lang("technical_supports_customer"); ?></h4>
                                            <span class="caption-subject font-blue-madison bold uppercase "></span>
                                        </div>
                                    </div>

                                    <div class="profile-userpic">
                                        <?php if ($cliente_equipo->image_id !=''): ?>
                                            <img src="<?php echo site_url() ?>/app_files/view/<?php echo $cliente_equipo->image_id ?>" class="img-responsive" alt="image" >
                                            <?php else: ?>
                                                <img src="assets/template/images/perfil.JPG" class="img-responsive" alt="image" >
                                            <?php endif ?>
                                        </div>

                                        <br>
                                        <div class="profile-usertitle text-center ">
                                            <div class="caption-subject bold "><?php echo $cliente_equipo->first_name . " ". $cliente_equipo->last_name  ?></div>
                                        </div>
                                        <div class="profile-usermenu">
                                            <ul class="nav">
                                                <li>
                                                    <a href="javascript:void(0)">
                                                        <i class="fa fa-inbox"></i> <?php echo $cliente_equipo->email ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)">
                                                        <i class="fa fa-mobile-phone"></i> <?php echo $cliente_equipo->phone_number ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9">
                                    <div class="portlet light form-fit bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-settings font-dark"></i>
                                                <span class="caption-subject font-dark sbold uppercase"></span> <?php echo lang('technical_supports_customer_serv_tec'); ?>
                                            </div>
                                            <div class="caption pull-right">
                                                <h4><?php echo lang('common_fields_required_message'); ?></h4>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">

                                            <form autocomplete="off" id="form_equipo" action="<?php echo site_url() ?>/technical_supports/editar_servicio" class="form">
                                                <div class="form-body">
                                                    <input type="text" name="id_support" class="hidden" value="<?php echo $id_support ?>">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-12">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_employee_receives') ?></label>
                                                                    <?php echo form_dropdown('empleado', $empleados, $cliente_equipo->id_employee_receive, 'id="empleado" class="form-control "'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('common_type_team_help') ?></label>
                                                                    <?php echo form_dropdown('equipo', $servicios, $cliente_equipo->type_team, 'id="equipo" class="form-control "'); ?>  
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_falla_tecnica') ?></label>
                                                                    <?php echo form_dropdown('falla', $fallas, $cliente_equipo->damage_failure, 'id="falla" class="form-control "'); ?>  
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_ubi_equipos') ?></label>
                                                                    <select class="bs-select form-control" style="width: 100%;" data-show-subtext="true" id="ubi_equipo" name="ubi_equipo">
                                                                        <option value="<?php echo $cliente_equipo->ubi_equipo; ?>"><?php echo $cliente_equipo->ubi_equipo; ?> </option>
                                                                        <?php  
                                                                        foreach($ubic as $ubic) {
                                                                            ?><option value="<?php echo $ubic->ubicacion; ?>"><?php echo $ubic->ubicacion; ?> </option><?php
                                                                        }                                                    
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_employee_asignado') ?></label>
                                                                    <?php echo form_dropdown('tecnico', $empleados, $cliente_equipo->id_employee_register, 'id="tecnico" class="form-control "'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_stat') ?></label>
                                                                    <?php echo form_dropdown('state', $states, $cliente_equipo->estados, 'id="state" class="form-control"'); ?>
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_marca') ?></label>
                                                                    <input type="text" name="marca" class="form-control" value="<?php echo $cliente_equipo->marca ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo lang('technical_supports_model') ?></label>
                                                                    <input type="text" name="model" class="form-control" value="<?php echo $cliente_equipo->model ?>">
                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo lang('technical_supports_color') ?></label>
                                                                        <input type="text" name="color" class="form-control" value="<?php echo $cliente_equipo->color ?>">
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo ($this->config->item("custom1_support_name") ? $this->config->item("custom1_support_name") : "Personalizado 1") . ':' ?> </label>
                                                                        <input type="text" name="custom1" class="form-control" value="<?php echo $cliente_equipo->custom1_support_name ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo ($this->config->item("custom2_support_name") ? $this->config->item("custom2_support_name") : "Personalizado 2") . ':' ?> </label>
                                                                        <input type="text" name="custom2" class="form-control" value="<?php echo $cliente_equipo->custom2_support_name ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo lang("technical_supports_abono") ?></label>
                                                                        <input type="text" name="abono" class="form-control" value="<?php echo $payment ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo lang("technical_supports_repair_cost") ?></label>
                                                                        <input type="text" name="costo" class="form-control" value="<?php echo $cliente_equipo->repair_cost ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label"><?php echo lang('technical_supports_accessory') ?></label>

                                                                        <div>
                                                                            <?php
                                                                            echo '<div class="text-right md-radio-inline col-sm-6">';
                                                                            echo '<div class="md-radio">';
                                                                            echo form_radio(array(
                                                                                'name' => 'do_have_accessory',
                                                                                'id' => 'do_have_accessory_yes',
                                                                                'value' => '1',
                                                                                'class' => 'md-check',
                                                                                'checked' => $cliente_equipo->do_have_accessory != 1 ? 1 : 1)
                                                                        );
                                                                            echo '<label id="show_comment_invoice" for="do_have_accessory_yes">';
                                                                            echo '<span></span>';
                                                                            echo '<span class="check"></span>';
                                                                            echo '<span class="box"></span>';
                                                                            echo lang('technical_supports_yes');
                                                                            echo '</label>';
                                                                            echo '</div>';
                                                                            echo '</div>';
                                                                            ?>
                                                                            <?php
                                                                            echo '<div class="md-radio-inline col-sm-6">';
                                                                            echo '<div class="md-radio">';
                                                                            echo form_radio(array(
                                                                                'name' => 'do_have_accessory',
                                                                                'id' => 'do_have_accessory_no',
                                                                                'value' => '0',
                                                                                'class' => 'md-check',
                                                                                'checked' => $cliente_equipo->do_have_accessory != 0 ? 0 : 1)
                                                                        );
                                                                            echo '<label  for="do_have_accessory_no">';
                                                                            echo '<span></span>';
                                                                            echo '<span class="check"></span>';
                                                                            echo '<span class="box"></span>';
                                                                            echo lang('technical_supports_no');
                                                                            echo '</label>';
                                                                            echo '</div>';
                                                                            echo '</div>';
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group" id="accessory" style="display:none">
                                                                        <label class="control-label"><?php echo lang('common_accessory') ?></label>
                                                                        <textarea name="accessory" id="accessory" class="form-control"><?php echo $cliente_equipo->accessory ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions">
                                                        <div class="col-md-12 text-center">
                                                            <button type="submit" class="btn green" ><i class="fa fa-check"></i> <?php echo lang('common_submit') ?></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!--col-sm-9-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php if ($cliente_equipo->do_have_accessory == 1): ?>
    <script>
        $('#accessory').show();
    </script>    
<?php endif ?>

<script>

    $('#do_have_accessory_yes').change(function () {
        $('#accessory').show('200');
    });
    $('#do_have_accessory_no').change(function () {
        $('#accessory').hide('200');
    });

    $('#form_equipo').bootstrapValidator({
        
        feedbackIcons: {
            valid: 'fa fa-check-circle',
            invalid: 'fa fa-times-rectangle',
            validating: 'fa fa-refresh'
        },
        fields: {
            empleado: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            equipo: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            tecnico: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            state: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            falla: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            marca: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            },
            modelo: {
                validators: {
                    notEmpty: {
                        message: "requerido"
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {

        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');

        controler($form.attr('action'), $form.serialize(), "contTabla", $('#ventanaVer').html(''));
         
    });

</script>