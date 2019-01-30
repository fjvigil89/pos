<option value="">Seleccione </option>
<?php  
foreach($tfallas->result() as $tfallas) {
    ?><option value="<?php echo $tfallas->tfallas; ?>"><?php echo $tfallas->tfallas; ?> </option><?php
}                                                    
?>

