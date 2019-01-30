<div class="modal-dialog">
    <div class="modal-content">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-align-justify"></i> 
                    <span class="caption-subject bold">
                        <?php echo lang("common_options_excel_export"); ?>
                    </span>
                </div>
                <div class="tools">                         
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>

            <div class="portlet-body form">
                <?php echo form_open('',array('id'=>'item_form','class'=>'form-horizontal')); ?>
                    <div class="form-body">
                    <br />
                        <p><?php echo lang('common_options_excel_export_comment').':'?></p>   
                        <div class="form-group">
                            <?php echo form_label('Todos:', '', array('class'=>'col-md-3 control-label wide')); ?>
                            <div class="col-md-9 align-vertical">
                                <div class="md-radio">
                                    <input type="radio" name="export_type" id="all_radio" value='all' class="md-radiobtn" checked='checked'/>
                                    <label for="all_radio">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </div>                         
                        </div>

                        <div class="form-group">
                            <?php echo form_label('Rango:', '', array('class'=>'col-md-3 control-label wide')); ?>
                            <div class="col-md-9 align-vertical">                                
                                <div class="md-radio">
                                    <input type="radio" name="export_type" id="range_radio" value='range' class="md-radiobtn" checked='checked'/>
                                    <label for="range_radio">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>  

                                <br/>
                                <div class="row">
                                    <div class="col-md-6 margin-bottom-05">
                                        <div class="input-group input-daterange" id="reportrange">
                                            <span class="input-group-addon">
                                                Desde                                           
                                            </span>
                                            <div id="from_spinner">
                                                <div class="input-group">
                                                    <?php echo form_input(array(
                                                        'name'=>'from_range',
                                                        'id'=>'from_range',
                                                        'value'=> '1',
                                                        'class'=>'spinner-input form-control form-inps',
                                                    ));?>                                       
                                                    <div class="spinner-buttons input-group-btn btn-group-vertical">
                                                        <button type="button" class="btn spinner-up btn-xs green">
                                                            <i class="fa fa-angle-up"></i>
                                                        </button>
                                                        <button type="button" class="btn spinner-down btn-xs green">
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-daterange" id="reportrange1">
                                            <span class="input-group-addon">
                                                Hasta
                                            </span>
                                            <div id="to_spinner">
                                                <div class="input-group">
                                                    <?php echo form_input(array(
                                                        'name'=>'to_range',
                                                        'id'=>'to_range',
                                                        'value'=> '1',
                                                        'class'=>'spinner-input form-control form-inps',
                                                    ));?>                                       
                                                    <div class="spinner-buttons input-group-btn btn-group-vertical">
                                                        <button type="button" class="btn spinner-up btn-xs green">
                                                            <i class="fa fa-angle-up"></i>
                                                        </button>
                                                        <button type="button" class="btn spinner-down btn-xs green">
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>                                    
                                </div> 
                                                              
                            </div>                         
                        </div>                                                             
                         <p class="text-warning">
                                    Sugerencia: La cantidad maxima a exportar no debería superar los 3000 artículos.
                                </p>
                    </div>  

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12"> 
                                <?php
                                    echo form_button(array(
                                    'name'=>'export_button',
                                    'id'=>'export_button',
                                    'content'=>lang('common_submit'),
                                    'class'=>'btn btn-primary btn-block')
                                ); ?> 
                            </div>
                        </div>
                    </div>

                    <span id="save_status" style="display: none"></span>

                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function()
    {
        $('#from_spinner, #to_spinner').spinner({value:1, min: 1, max:3000});   
        
        $("#export_button").click(function()
        {        
            var from = parseInt($('#from_range').val());
            var to = parseInt($('#to_range').val());

            if ($("#all_radio").prop('checked'))
            {
                window.location = window.location + '/excel_export';
            }

            if ($("#range_radio").prop('checked'))
            {                
                if (from == 0 || to == 0) 
                {
                    toastr.error('Debe digitar valores mayor a 0', <?php echo json_encode(lang('common_error')); ?>);
                }
                
                if (from > to)
                {
                    toastr.error('El valor digitado en "Desde" no puede ser mayor al valor "Hasta"', <?php echo json_encode(lang('common_error')); ?>);
                }
                else
                {
                    window.location = window.location + '/excel_export/' + from + '/' + to;
                }
            }
        });

        $("#from_spinner").click(function()
        {
            $("#range_radio").prop('checked', true);
        });

        $("#to_spinner").click(function(){
            $("#range_radio").prop('checked', true);
        }); 
            
    });

</script>