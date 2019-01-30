<!-- JAVASCRIPT OffLine-->
<?php if($this->Appconfig->is_offline_sales()){?>
   <script>   
        localStorage.setItem('store_login','store_<?php echo $this->Employee->get_store()?>');
        localStorage.setItem('sales_offline','1');
 </script>
  
   <script src=" https://momentjs.com/downloads/moment-with-locales.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>js/lib/idb.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/dexie.min.js" type="text/javascript"></script>    
    <script src="<?php echo base_url();?>js/offline/indexed.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>js/offline/conexion_evento.js" type="text/javascript"></script>			


    <!-- validacion indexedb browser--->
    <script src="<?php echo base_url();?>js/compatible_browser.js" type="text/javascript"></script>
    <script>    
    
			$(document).ready(function(){
				if(!validation_indexedDB_browser()){
                    document.getElementById("btn-offline").style.display = "block";
                    sessionStorage.setItem("person_id", <?php echo $this->Employee->get_logged_in_employee_info()->person_id?>);
                    window.addEventListener('offline', handleConnectionChange);

                }
			
			});
	</script>
<?php } else{ echo "<script>localStorage.setItem('sales_offline','0');</script>";}?>