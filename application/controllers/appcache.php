<?php
require_once ("secure_area.php");
class Appcache extends Secure_area 
{
	function __construct()
	{
        parent::__construct();	
    }
    public function index(){
        $this->load->view("offline_data/manifest");
        
    }
    
}