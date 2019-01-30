<?php
require_once ("secure_area.php");
class Email extends Secure_area 
{
    function __construct()
	{
        parent::__construct('Email');
        $this->load->library('Email_send');

    }
    function enviar(){
       $this->Email_send->send_("fredyjosevergaracardenas@gmail.com", "Prueba", "fredys", "Esto es una prueba") ;
    }
}