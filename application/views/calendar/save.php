<div class="tab-pane active" id="tab_2">
          <div class="portlet box green">
                    <div class="portlet-title">
                              <div class="caption">
                                        <i class="fa fa-gift"></i>Add Schedule </div>

                    </div>
                    <div class="portlet-body form">
                              <!-- BEGIN FORM-->

                              <?php echo form_open_multipart('/schedules/updateSchedule', array('class'=>"form-horizontal")); ?>
                              <div class="form-body">
                                        <h3 class="form-section">schedule Info</h3>
                                        
                                        <input type="hidden" name="id" value="<?php echo isset($schedule[0]->id)? $schedule[0]->id : '' ?>">
                                        <div class="row">
                                                  <div class="col-md-6">
                                                            <div class="form-group">
                                                                      <label
                                                                                class="control-label col-md-3">Title</label>
                                                                      <div class="col-md-9">
                                                                                <input type="text" name="title"
                                                                                          class="form-control"
                                                                                          placeholder="Title.."
                                                                                          value="<?php echo isset($schedule[0]->title) ? $schedule[0]->title : '' ?>">

                                                                                <!--span class="help-block"> This is inline help </span-->
                                                                      </div>
                                                            </div>
                                                  </div>
                                                  <!--/span-->
                                                  <div class="col-md-6">
                                                            <label class="control-label col-md-3">Date Satart</label>
                                                            <div class="col-md-6 margin-bottom-05">
                                                                      <div class="input-group input-daterange"
                                                                                id="reportrange">
                                                                                <input type="text"
                                                                                          class="form-control start_date"
                                                                                          name="start_date"
                                                                                          id="start_date"
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
                                                                                class="control-label col-md-3">Detail</label>
                                                                      <div class="col-md-9">
                                                                                <textarea class="form-control"
                                                                                          name="detail" id="" cols="30"
                                                                                          rows="3"
                                                                                          placeholder="Detalles..">
                                    <?php echo isset($schedule[0]->detail) ? $schedule[0]->detail : '' ?>
                                    </textarea>

                                                                                <!--span class="help-block"> Select your gender. </span-->
                                                                      </div>
                                                            </div>
                                                  </div>
                                                  <!--/span-->
                                                  <div class="col-md-6">
                                                            <label class="control-label col-md-3">Date End</label>
                                                            <div class="col-md-6 margin-bottom-05">
                                                                      <div class="input-group input-daterange"
                                                                                id="reportrange">
                                                                                <input type="text"
                                                                                          class="form-control end_date"
                                                                                          name="end_date" id="end_date"
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
                                                                                class="control-label col-md-3">Color</label>
                                                                      <div class="col-md-9 input-group color colorpicker-default"
                                                                                data-color="#3865a8"
                                                                                data-color-format="rgba">
                                                                                <input type="text" name="color"
                                                                                          class="form-control"
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
                                                                                class="control-label col-md-3">Status</label>
                                                                      <div class="col-md-9">
                                                                                <div class="radio-list">
                                                                                          <?php  if (isset($schedule[0]->status) && $schedule[0]->status == 1 ) { ?>
                                                                                          <label class="radio-inline">
                                                                                                    <input type="radio"
                                                                                                              name="status"
                                                                                                              value="on"
                                                                                                              checked>
                                                                                                    Enable </label>
                                                                                          <label class="radio-inline">
                                                                                                    <input type="radio"
                                                                                                              name="status"
                                                                                                              value="off">
                                                                                                    Disable </label>
                                                                                          <?php } else{ ?>
                                                                                          <label class="radio-inline">
                                                                                                    <input type="radio"
                                                                                                              name="status"
                                                                                                              value="on">
                                                                                                    Enable </label>
                                                                                          <label class="radio-inline">
                                                                                                    <input type="radio"
                                                                                                              name="status"
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
                                        <h3 class="form-section">Product & Service</h3>
                                        <!--/row-->
                                        
                                        
                                        <div class="row">
                                                  <div class="col-md-6">
                                                            <div class="form-group">
                                                                      <label
                                                                                class="control-label col-md-3">Products</label>
                                                                      <div class="col-md-9">
                                                                                <select name="products[]" multiple=""
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
                                                                                          <button type="submit"
                                                                                                    class="btn green">Submit</button>
                                                                                          <a href='<?php echo site_url().'/schedules'; ?>'
                                                                                                    id="cancel"
                                                                                                    class="btn default">Cancel</a>
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