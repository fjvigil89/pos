<option value="">No Aplica </option>
<?php  
foreach($ubicacione->result() as $ubicacione) {
    ?><option value="<?php echo $ubicacione->ubicacion; ?>"><?php echo $ubicacione->ubicacion; ?> </option><?php
}                                                    
?>
