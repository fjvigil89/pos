<option value="">Seleccione </option>
<?php  
foreach($tservice->result() as $tservice) {
    ?><option value="<?php echo $tservice->tservicios; ?>"><?php echo $tservice->tservicios; ?> </option><?php
}                                                    
?>