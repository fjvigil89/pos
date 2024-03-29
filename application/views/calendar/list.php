<div class="tab-pane active" id="tab_2">
          <div class="portlet box green">
                    <div class="portlet-title">
                              <div class="caption">
                                        <i class="fa fa-gift"></i><?php echo lang('schedules_list'); ?> </div>

                    </div>
<div class="portlet-body form">
          <!-- BEGIN FORM-->

          <?php echo form_open_multipart( isset($schedule[0]->id)? '/schedules/updateSchedule' : '/schedules/setSchedule', array('class'=>"form-horizontal")); ?>
          <div class="form-body">
               <h3 class="form-section"><?php echo lang('schedules_info'); ?></h3>
               
               <input type="hidden" name="id" value="<?php echo isset($schedule[0]->id)? $schedule[0]->id : '' ?>">
               <div class="row">
                    <div class="col-md-6">
                              <div class="form-group">
                                        <label
                                                  class="control-label col-md-3"><?php echo lang('schedules_info_title'); ?></label>
                                        <div class="col-md-9">
                                                  <input type="text" name="title"
                                                            class="form-control"
                                                            placeholder="Title.."
                                                            disabled=""
                                                            value="<?php echo isset($schedule[0]->title) ? $schedule[0]->title : '' ?>">

                                                  <!--span class="help-block"> This is inline help </span-->
                                        </div>
                              </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                              <label class="control-label col-md-3"><?php echo lang('schedules_info_dateStart'); ?></label>
                              <div class="col-md-6 margin-bottom-05">
                                        <div class="input-group input-daterange"
                                                  id="reportrange">
                                                  <input type="text"
                                                            class="form-control start_date"
                                                            name="start_date"
                                                            id="start_date"
                                                            disabled=""
                                                            placeholder="Selecciona una fecha"
                                                            value="<?php echo isset($schedule[0]->start) ? $schedule[0]->start : '' ?>">
                                        </div>
                              </div>
                    </div>

                              <!--/span-->
               </div>
                    <!--/row-->
                    <div class="row">
                              <div class="col-md-6">
                                        <div class="form-group">
                                                  <label
                                                            class="control-label col-md-3"><?php echo lang('schedules_info_detail'); ?></label>
                                                  <div class="col-md-9">
                                                            <textarea class="form-control"
                                                                      name="detail" id="" cols="30"
                                                                      rows="3"
                                                                      disabled=""
                                                                      placeholder="Detalles..">
                <?php echo isset($schedule[0]->detail) ? $schedule[0]->detail : '' ?>
                </textarea>

                                                            <!--span class="help-block"> Select your gender. </span-->
                                                  </div>
                                        </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                        <label class="control-label col-md-3"><?php echo lang('schedules_info_dateEnd'); ?></label>
                                        <div class="col-md-6 margin-bottom-05">
                                                  <div class="input-group input-daterange"
                                                            id="reportrange">
                                                            <input type="text"
                                                                      class="form-control end_date"
                                                                      name="end_date" id="end_date"
                                                                      disabled=""
                                                                      placeholder="Selecciona una fecha"
                                                                      value="<?php echo isset($schedule[0]->end) ? $schedule[0]->end : '' ?>">
                                                  </div>
                                        </div>
                              </div>
                              <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row">
                              <div class="col-md-6">
                                        <div class="form-group">
                                                  <label
                                                            class="control-label col-md-3"><?php echo lang('schedules_info_color'); ?></label>
                                                  <div class="col-md-9 input-group color colorpicker-default"
                                                            data-color="#3865a8"
                                                            data-color-format="rgba">
                                                            <input type="text" name="color"
                                                                      class="form-control"
                                                                      disabled=""
                                                                      value="<?php echo isset($schedule[0]->color) ? $schedule[0]->color : '#3865a8' ?>"
                                                                      readonly="">
                                                            <span class="input-group-btn">
                                                                      <button class="btn default"
                                                                                type="button">
                                                                                <i
                                                                                          style="background-color: #3865a8;"></i>&nbsp;</button>
                                                            </span>
                                                  </div>
                                        </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                        <div class="form-group">
                                                  <label
                                                            class="control-label col-md-3"><?php echo lang('schedules_info_status'); ?></label>
                                                  <div class="col-md-9">
                                                            <div class="radio-list">
                                                                      <?php  if (isset($schedule[0]->status) && $schedule[0]->status == 1 ) { ?>
                                                                      <label class="radio-inline">
                                                                                <input type="radio"
                                                                                          name="status"
                                                                                          value="on"
                                                                                          disabled=""
                                                                                          checked>
                                                                                Enable </label>
                                                                      <label class="radio-inline">
                                                                                <input type="radio"
                                                                                          name="status"
                                                                                          disabled=""
                                                                                          value="off">
                                                                                Disable </label>
                                                                      <?php } else{ ?>
                                                                      <label class="radio-inline">
                                                                                <input type="radio"
                                                                                          name="status"
                                                                                          disabled=""
                                                                                          value="on">
                                                                                Enable </label>
                                                                      <label class="radio-inline">
                                                                                <input type="radio"
                                                                                          name="status"
                                                                                          disabled=""
                                                                                          value="off"
                                                                                          checked>
                                                                                Disable </label>
                                                                      <?php } ?>
                                                            </div>
                                                  </div>
                                        </div>
                              </div>
                              <!--/span-->
                    </div>
                    <h3 class="form-section"><?php echo lang('schedules_info_product&service'); ?></h3>
                    <!--/row-->
                    
                    
                    <div class="row">
                              <div class="col-md-6">
                                        <div class="form-group">
                                                  <label
                                                            class="control-label col-md-3"><?php echo lang('schedules_info_product'); ?></label>
                                                  <div class="col-md-9">
                                                            <select disabled="" name="products[]" multiple=""
                                                                      class="form-control">
                                                                      
                                                                      <?php foreach ($items as $key => $value) { ?>
                                                                      <option value="<?php echo $items_id[$key] ?>"><?php echo $value ?>
                                                                      </option>
                                                                      <?php } ?>

                                                            </select>
                                                  </div>
                                        </div>

                                        <!--/span-->
                              </div>
                              <!--/row-->
                    </div>
                    <div class="form-actions">
                    <div class="row">
                         <div class="col-md-6">
                              <div class="row">
                                   <div class="col-md-offset-3 col-md-9">
                                         <a href=<?php echo site_url()."/schedules/editSchedule/". $schedule[0]->id; ?>
                                         class="btn purple">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                             <a href=<?php echo site_url()."/schedules/facturar/". $schedule[0]->id; ?>
                                                       class="btn green">
                                                  <i class="fa fa-dollar"></i>
                                                  Facturar</a>
                                             <a href='<?php echo site_url().'/schedules'; ?>'
                                                       id="cancel"
                                                       class="btn default">
                                                       <i class="fa fa-ban"></i>
                                                  Cancel</a>
                                   </div>
                              </div>
                         </div>
                         <div class="col-md-6"> </div>
                    </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- END FORM-->
          </div>
</div>

          </div>

          <script type="text/javascript" language="javascript">
          var JS_DATE_FORMAT = 'YYYY-MM-DD';
          var JS_TIME_FORMAT = "H:mm:s";

          $('#start_date').datetimepicker({
                    format: JS_DATE_FORMAT + " " + JS_TIME_FORMAT,
                    locale: "es"
          });

          $('#end_date').datetimepicker({
                    format: JS_DATE_FORMAT + " " + JS_TIME_FORMAT,
                    locale: "es"
          });

          $(document).ready(function() {

                    $("#start_date").click(function() {
                              $("#complex_radio").prop('checked', true);
                    });

                    $("#end_date").click(function() {
                              $("#complex_radio").prop('checked', true);
                    });

                    $("#report_date_range_simple").change(function() {
                              $("#simple_radio").prop('checked', true);
                    });

          });
          </script>