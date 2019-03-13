  <?php $this->load->view("partial/header"); ?>
  <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
      <h1>
        <i class='fa fa-pencil'></i>
            <?php echo  lang("module_nomina")?>
      </h1>
    </div>
    <!-- END PAGE TITLE -->
  </div>
  <!-- END PAGE HEAD -->
  <!-- BEGIN PAGE BREADCRUMB -->
  <div id="breadcrumb" class="hidden-print">
    <?php echo create_breadcrumb(); ?>
  </div>
<div class="row">
                            <div class="col-md-12 ">
                                <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-book-open"></i>
                                            <span class="caption-subject bold">
                                                datos de nomina
                                            </span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">

                                    <div class="portlet-body form">
                                      <?php 	echo form_open('nomina/save_nomina/',array('id'=>'nomina_form','class'=>'form-horizontal',
                                    "autocomplete"=>"off")); ?>
                                            <div class="form-body">


                                                <div class="form-group">
                                                  <label class="col-md-3 control-label"><?php echo lang('nomina_periodo_desde') ?></label>
                                                  <div class="col-md-8">
                                                    <?php echo form_input(array(
                                                      'name'=>'periodo_desde',
                                                      'id'=>'periodo_desde',
                                                      'class'=>'fomr-control form-inps',
                                                      'value'=>"",
                                                      'type'=>'date'
                                                  ));
                                                    ?>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-md-3 control-label"><?php echo lang('nomina_periodo_hasta') ?></label>
                                                  <div class="col-md-8">
                                                    <?php echo form_input(array(
                                                      'name'=>'periodo_hasta',
                                                      'id'=>'periodo_hasta',
                                                      'class'=>'fomr-control form-inps',
                                                      'value'=>"",
                                                      'type'=>'date',
                                                  ));
                                                    ?>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-md-3 control-label"><?php echo lang('nomina_descripcion') ?></label>
                                                  <div class="col-md-8">
                                                        <?php $data = array(
                                                                'name'        => 'descripcion',
                                                                'id'          => 'descripcion',
                                                                'value'       => "",
                                                                'rows'        => '2',
                                                                'cols'        => '20',
                                                                'style'       => 'width:100%',
                                                                'class'       => 'fomr-control form-inps'
                                                            );
                                                            echo form_textarea($data);
                                                    ?>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-md-3 control-label"><?php echo lang('nomina_numero') ?></label>
                                                  <div class="col-md-8">
                                                    <?php echo form_input(array(
                                                      'name'=>'numero-nomina',
                                                      'id'=>'numero-nomina',
                                                      'class'=>'fomr-control form-inps',
                                                      'value'=>"" ,
                                                      'disabled'=>''
                                                  ));
                                                    ?>
                                                  </div>
                                                </div>

                                                <div class="form-group">
                                                  <label class="col-md-3 control-label"><?php echo lang('nomina_Fecha') ?></label>
                                                  <div class="col-md-8">
                                                    <?php echo form_input(array(
                                                      'name'=>'fecha',
                                                      'id'=>'fecha',
                                                      'class'=>'fomr-control form-inps',
                                                      'value'=>"",
                                                      'disabled'=>''
                                                  ));
                                                    ?>
                                                  </div>
                                                </div>

                                                <div >
                                                  <input type="submit" class="btn-success btn" name="crear" value="Crear nomina">
                                                </div>
                                            </div>

                                            <!--</form>-->
                                             <?php echo form_close(); ?>
                                           <div class="portlet light register-items margin-bottom-15">

                            <div class="row">
                                    <div class="col-md-12">

                                      <div class="widget-box">
                                        <div class="widget-title">
                                          <div class="caption">
                                                <!--  <i class="icon-bar-chart font-green-haze"></i>-->
                                                  <span class="caption-subject bold uppercase font-green-haze"><?php echo "empleados_" ?></span>
                                           </div>

                                        </div>

                                        <div class="widget-content nopadding">
                                          <div class="table-responsive">
                                          <table class="table table-bordered table-striped  table-condensed table-hover detailed-reports  tablesorter" id="sortable_table">
                                              <thead>
                                                  <tr align="center" style="font-weight:bold">
                                                      <td class="hidden-print">ID</td>

                                                      <td align="">Nombre</td>
                                                      <td align="">Apellidos</td>
                                                      <td align="">Saldo</td>
                                                      <td align="">Retiro</td>
                                                      <td align="">Opciones</td>


                                                  </tr>
                                              </thead>
                                              <tbody>

                                                  <tr>
                                                      <td align="center" class="hidden-print">1</td>
                                                      <td class="hidden-print">Fredys Jose</td>
                                                      <td class="hidden-print">Castro Nuñes</td>
                                                      <td align="center" class="hidden-print">$7380000</td>
                                                      <td align="center" class="hidden-print">$7380000</td>

                                                      <td class="hidden-print"> <center><button type="button" class="btn default">ver</button> </center></td>

                                                  </tr>



                                              </tbody>
                                          </table>
                                          </div>
                                        </div>
                                      </div>






                                    </div>


                        </div>
                      </div>
                                          <!--  <div class="row">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn blue">Submit</button>
                                                    <button type="button" class="btn default">Cancel</button>
                                                </div>
                                            </div>-->

                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
<?php $this->load->view("partial/footer"); ?>
