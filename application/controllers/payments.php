<?php
require_once ("person_controller.php");
class Payments extends Secure_area 
{
	function __construct()
	{
		parent::__construct();
		
	}
	public function receipt($payments_id=false){
		$data=array();
		// codigo se consultan los datos  para mandar imprimir 

		// se carga la vista receipt
		
		 $this->load->view("payments/receipt",$data); 


	}	
	
	

}
?>