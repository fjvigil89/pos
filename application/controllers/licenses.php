<?php
require_once ("secure_area.php");
class Licenses extends Secure_area 
{
    
    function __construct()
	{
		parent::__construct('config');
        $this->load->model('Subscriptions');
	}
    
    function index()
    {
        $this->load->view('licenses');
    }
    
    function payments()
    {
        echo '{"data":'.json_encode($this->Subscriptions->get_payments()).'}';
    }
    
}