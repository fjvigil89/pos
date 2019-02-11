<?php 
    //date_default_timezone_set($this->Location->get_info_for_key('timezone'));
    $hora = 60*60*24;//24 horas
    $seconds_to_cache = $hora;
    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
    header("Expires: $ts");
    header("Pragma: cache");
    header("Cache-Control: max-age=$seconds_to_cache");
?>