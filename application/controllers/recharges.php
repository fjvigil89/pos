<?php
require_once ("secure_area.php");
class Recharges extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		
		$this->load->view("recharges/recharges");
	}
}
?>