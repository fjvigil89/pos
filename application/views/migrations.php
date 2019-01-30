<?php $this->load->view("partial/header"); ?>

    <div class="row">
        
        <div class="col-md-12">
        
                      
				
                
                
                <script type="text/javascript">
                    $( document ).ready(function() {


    var count = 61,
      progress = $('<div id="sample-02b-progress">60</div>');

    function countDown() {
      progress.html('<center><span> Actualizando a la version: V.4.0.0 '+'<br>'+ '         Loading: ' + --count +' segundo </span></center>');
      if (count >= 0) { setTimeout(countDown, 1000); }
    }
    $('body').plainOverlay('show', {progress: function() { return progress; }});
    setTimeout(countDown, 1000);

 
                        var form_data = {sent : 1};
                        $('.page-container').plainOverlay('show', {duration: 1000});
                          $( "#sample-02b-progress" ).css({
                        'position': 'absolute',
                        'color':'white',
                        'z-index':'9001',
                        'cursor':'wait',
                        'font-size':'14px',
                        'font':'Arial',  
                        });
                       
                            $.ajax({
                            method: "POST",
                            url: '<?php echo site_url("migrations");?>',
                            data: form_data,
                            success: function(data)
                            {
                                if(data.success==true)
                                {
                                
                                    $('.page-container').plainOverlay('hide');
                                    $( ".update_version" ).text('');
                                     window.location = '<?php echo site_url();?>';
                                }    
                            },
                            beforesend:function(){


                            },

                            dataType:'json',        
                        });
                    });  
                </script>
                
            </div>
        </div>
            
    </div>

<?php $this->load->view("partial/footer"); ?>