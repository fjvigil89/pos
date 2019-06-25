<?php $this->load->view("partial/header"); ?>    
  
    <script type="text/javascript">
        $(document).ready(function() 
        { 
            <?php if ($controller_name == 'suppliers') { ?>
                var table_columns = ['','company_name','last_name','first_name','email','phone_number'];      
            <?php } else { ?>
                var table_columns = ['','<?php echo $this->db->dbprefix('people'); ?>'+'.person_id','last_name','first_name','email','phone_number'];
            <?php } ?>

            enable_sorting("<?php echo site_url('campaigns/sorting'); ?>",table_columns, <?php echo $per_page; ?>, <?php echo json_encode($order_col);?>, <?php echo json_encode($order_dir); ?>);
            enable_select_all();
            enable_checkboxes();
            enable_row_selection();
            enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);

            enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
            enable_cleanup(<?php echo json_encode(lang($controller_name."_confirm_cleanup"));?>);
            
            $('#new-person-btn, .update-person').click(function()
            {
                $("body").mask(<?php echo json_encode(lang('common_wait')); ?>);
            });
         
            <?php if ($this->session->flashdata('manage_success_message')) { ?>
                toastr.success(<?php echo json_encode($this->session->flashdata('manage_success_message')); ?>, <?php echo json_encode(lang('common_success')); ?>);
            <?php } ?>      
        }); 
    </script>


        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>
                <i class="icon fa fa-bullhorn"></i>
                <?php echo lang('module_'.$controller_name); ?>
                <?php 
					$extra="style='display: none; '";
					$url_video_ver="#";
					if($this->Appconfig->es_franquicia()){
						$url_video=	$this->Appconfig->get_video("CAMPAÑA");
						if($url_video!=null){
                            $url_video_ver=$url_video;
                            $extra="";
						}else{
							$extra=" style='display: none; '";
						}
					}
					$a_video= '<a target="_blank" href="'.$url_video_ver.'" '.$extra.' class="icon fa fa-youtube-play help_button" ></a>';
					echo $a_video;				
				?>
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
            <!-- BEGIN CONDENSED TABLE PORTLET-->
            <div class="portlet box green" id="portlet-content">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="icon">
                            <i class="fa fa-share-square-o"></i>
                        </span>
                        Agregar mensaje a los Contactos
                    </div>
                    
                </div>
                <div class="portlet-body">
                    <?php echo form_open('campaigns/send_tts',['class'=>'form-horizontal', 'id'=>'send_tts_form', 'method'=>'POST']);?>
                        <div class="form-body">                        
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Remitente del mensaje: </label>
                                <div class="col-lg-8">
                                    <input class="form-control form-inps" name='data3' type="text"  placeholder="Remitente del mensaje">                        
                                </div>
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-5">                                            
                                    <label id="lb_sender_error" class="has-error text-danger"></label>                        
                                </div>                                                                
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Mensaje: </label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" name='data5' class="form-control form-inps" placeholder="Digita el mensaje que deseas enviar">
                                        <span class="input-group-btn">
                                            <button class="btn btn-warning" id="btn_test" type="button" title="Escucha el mensaje"><i class="fa fa-volume-up fa-lg"></i></button>
                                        </span>
                                    </div>
                                </div>  
                                <label class="col-lg-3 control-label"></label>                     
                                <div class="col-lg-5">                                              
                                    <label id="lb_message_error" class="has-error text-danger"></label>                        
                                </div>                                                               
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-8">
                                    <button type="submit" id="btn_send" class="btn btn-success ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Enviar mensaje</span>
                                    </button>                      
                                </div>   
                                <label class="col-lg-3 control-label"></label> 
                                <div class="col-lg-5">                                              
                                    <label id="lb_contact_error" class="has-error text-danger"></label>                        
                                </div> 
                            </div>
                        </div>
                    </form>

                    <div id="result" class="alert alert-success alert-dismissible" role="alert">
                        <button id="btn_close" type="button" class="close">&times;</button>
                        <div id="result_content">
                        
                        </div>
                    </div>
                    <div id="result1"></div>

                </div>
            </div>
        </div>
    </div>

    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption bold">
                <i class="fa fa-group"></i>
                Listado de campañas
            </div>
            <div class="tools">
                <span data-original-title="<?php echo $total_rows; ?> total <?php echo $controller_name?>" class="label label-primary tip-left tooltips"><?php echo $total_rows; ?></span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row margin-bottom-10">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <?php echo form_open("$controller_name/search",array('id'=>'search_form', 'autocomplete'=> 'off')); ?>
                        <div class="input-group">                               
                            <input type="text" class="form-control form-inps" name ='search' id='search' value="<?php echo H($search);  ?>" placeholder="<?php echo lang('common_search'); ?> <?php echo lang('module_'.$controller_name); ?>"/>
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </form>
                </div>                      
            </div>

            <div class="row">
                <div class="col-md-12"> 
                    <?php if($pagination) {  ?>
                        <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_top">
                            <?php echo $pagination;?>
                        </div>
                    <?php } ?>      
                    
                    <div class="table-responsive" >
                        <?php echo $manage_table; ?>            
                    </div>                                              
                    
                    <?php if($pagination) {  ?>
                        <div class="pagination hidden-print alternate text-center fg-toolbar ui-toolbar" id="pagination_bottom" >
                            <?php echo $pagination;?>
                        </div>
                    <?php } ?>                                      
                </div>
            </div>

        </div>
    </div>
	<?=$this->load->view("tutorials");?>


    <script type="text/javascript">
        $(document).ready(function(){  
            $(function () 
            {        
                // New   
                //Ladda.bind( '#btn_send');
                $("#result").hide();
                $("#btn_close").click(function()
                {
                    $("#result_content").html('');
                    $("#result").hide();
                });

                //Button test sound
                $("#btn_test").click(function()
                {
                    var sound = new Audio();     
                    var mensaje = $('input:text[name=data5]').val();     
                    //console.log(mensaje);
                    sound.src = "http://www.voicerss.org/controls/speech.ashx?hl=es-es&src="+mensaje+"&rnd=0.16225783374986746";
                    sound.play();
                });
      
                function get_selected_values()
                {
                    var selected_values = new Array();
                    $("#sortable_table tbody :checkbox:checked").each(function()
                    {
                        selected_values.push($(this).val());
                    });
                    return selected_values;
                }
                
                console.log(enable_row_selection());
        
                $('#send_tts_form').submit(function()
                {             
                    //$('#result').html('');

                    rows_data=get_selected_values();
          
                    console.log(rows_data); 
                    //return false;
                    var sender = $('input[name=data3]').val();          
                    var message = $('input[name=data5]').val();

                    if(sender == "") 
                    {
                    
                        $('#lb_sender_error').html('El campo Remitente del mensaje es obligatorio');            
                    }
                    else
                    {
                        $('#lb_sender_error').html('');
                    }

                    if(message == "") 
                    {
                        $('#lb_message_error').html('El campo Mensaje es obligatorio');            
                    }
                    else
                    {
                        $('#lb_message_error').html('');
                    }

                    if(rows_data.length < 1) 
                    {
                        $('#lb_contact_error').html('Debe seleccionar al menos un contacto de la tabla');
                        //l.stop(); 
                        //return false;
                    }  
                    else
                    {
                        $('#lb_contact_error').html('');
                    }          

                    for (i = 0; i <= rows_data.length-1; i++) 
                    {          
                        var data2 ={
                            data3:sender,
                            data4:rows_data[i][0],
                            data5:message,
                            data6:rows_data[i][1]
                        }             
                        $.ajax({
                            type: this.method,
                            url: this.action, 
                            data: data2,
                            async: false,
                            dataType: 'json',
                            success: function(data){
                                // show the response  
                                //console.log(data);
                                if(data.result == "error")
                                {
                                    if (data.sender_error!="") 
                                    {
                                      $('#lb_sender_error').html(data.sender_error);                                     
                                    }
                                    else
                                    {
                                      $('#lb_sender_error').html('');  
                                    }
                                
                                    if (data.message_error!="") 
                                    {
                                      $('#lb_message_error').html(data.message_error);
                                    }
                                    else                            
                                    {
                                      $('#lb_message_error').html('');
                                    }

                                    if (data.contact_error!="") 
                                    {
                                      $('#lb_contact_error').html('Debe seleccionar al menos un contacto de la tabla');
                                    }
                                    else                            
                                    {
                                      $('#lb_contact_error').html('');                      
                                    }
                                }
                                else
                                {                
                                    //$('#lb_contact_error').html(data.result);
                                    $("#result").show();
                                    $('#result_content').append("&nbsp;&nbsp;Se envio el mensaje correctamente al contacto: "+data.contact_info+"<br>");
                                }                                                                                        
                            }
                        });                        
                    // to prevent refreshing the whole page page                     
                    }; 
                    //l.stop();   
                    return false;
                })
            });
        });
    </script>

<?php $this->load->view("partial/footer"); ?>